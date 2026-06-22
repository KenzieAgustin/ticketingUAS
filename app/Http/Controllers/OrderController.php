<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\TicketZone;
use App\Models\Ticket;
use App\Models\Voucher;
use App\Models\WaitList;
use App\Notifications\AppNotification;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'ticket_id'      => 'required|exists:tickets,id',
            'ticket_zone_id' => 'nullable|exists:ticket_zones,id',
            'quantity'       => 'integer|min:1|max:10',
        ]);

        $ticket   = Ticket::findOrFail($request->ticket_id);
        $quantity = $request->quantity ?? 1;
        $zone     = null;

        // Kalau entry_concert, wajib pilih zona
        if ($ticket->ticket_type === 'entry_concert') {
            $request->validate([
                'ticket_zone_id' => 'required|exists:ticket_zones,id',
            ]);
            $zone = TicketZone::findOrFail($request->ticket_zone_id);
            $hargaSatuan = $zone->price;
        } else {
            $hargaSatuan = $ticket->price;
        }

        $hargaSatuanAsli = $hargaSatuan;
        $today = \Carbon\Carbon::now()->toDateString();
        $activePromo = \App\Models\PricingRule::where('ticket_id', $ticket->id)
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->first();

        if ($activePromo) {
            $discountApplied = 0;
        if ($activePromo->discount_type === 'fixed') {
            $discountApplied = $activePromo->discount_value;
        } elseif ($activePromo->discount_type === 'percentage') {
            $discountApplied = round($hargaSatuan * ($activePromo->discount_value / 100));
        }
        $hargaSatuan = max(0, $hargaSatuan - $discountApplied);
    }

        $total_amount = $hargaSatuan * $quantity;
        $gross_amount = $hargaSatuanAsli * $quantity;

        // Cek voucher
        $voucherId = null;
        if ($request->filled('voucher_code')) {
            $voucher = Voucher::where('code', trim($request->voucher_code))
                ->where(function ($q) {
                    $q->whereNull('expired_at')->orWhere('expired_at', '>=', now());
                })
                ->where(function ($q) {
                    $q->whereNull('quota')->orWhereColumn('used', '<', 'quota');
                })
                ->first();

            if ($voucher && $voucher->isValid()) {
            $total_amount = max(1, $total_amount - $voucher->discount_amount);
            $voucherId    = $voucher->id;
            }
        }

        try {
            $order = null;

            DB::transaction(function () use ($request, $ticket, $zone, $quantity, $hargaSatuan, $total_amount, $gross_amount, $voucherId, &$order)  {

                // Cek & kurangi kuota kalau ada zona
                if ($zone) {
                    $zoneLocked = TicketZone::lockForUpdate()->find($zone->id);
                    if ($zoneLocked->quota_remaining < $quantity) {
                        // Masukkan ke waitlist
                        WaitList::firstOrCreate([
                            'user_id'        => Auth::id(),
                            'ticket_zone_id' => $zone->id,
                        ], ['status' => 'waiting']);

                        throw new \Exception('WAITLIST');
                    }
                    $zoneLocked->decrement('quota_remaining', $quantity);
                }

                // Buat order
                $order = Order::create([
                    'user_id'      => Auth::id(),
                    'order_number' => 'PRJ-' . time(),
                    'total_amount' => $total_amount,
                    'gross_amount' => $gross_amount,
                    'voucher_id'   => $voucherId,
                    'status'       => 'pending',
                ]);

                if ($voucherId) {
                    \App\Models\Voucher::where('id', $voucherId)->increment('used');
                }

                // Buat order item
                OrderItem::create([
                    'order_id'       => $order->id,
                    'ticket_zone_id' => $zone?->id,
                    'quantity'       => $quantity,
                    'price'          => $hargaSatuan,
                    'subtotal'       => $hargaSatuan * $quantity,
                ]);
            });

            // Mancing notif
            Auth::user()->notify(new AppNotification(
                type: 'order_created',
                message: '🧾 Pesanan #' . $order->order_number . ' berhasil dibuat. Segera selesaikan pembayaran!',
                refId: $order->id,
            ));

            // Midtrans
            Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            Config::$isSanitized  = true;
            Config::$is3ds        = true;

            $params = [
                'transaction_details' => [
                    'order_id'     => $order->order_number,
                    'gross_amount' => (int) $total_amount,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email'      => Auth::user()->email,
                ],
            ];

            $snapToken = Snap::getSnapToken($params);
            $order->update(['snap_token' => $snapToken]);

            return view('checkout', [
                'snapToken' => $snapToken,
                'order'     => $order,
            ]);

        } catch (\Exception $e) {
            if ($e->getMessage() === 'WAITLIST') {
                return back()->with('error', 'Kuota zona ini habis. Kamu sudah masuk waitlist!');
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function index(Request $request)
    {
        $sort  = $request->query('sort', 'terbaru');
        $query = Order::where('user_id', Auth::id());

        match ($sort) {
            'terlama'  => $query->orderBy('created_at', 'asc'),
            'tertinggi' => $query->orderBy('total_amount', 'desc'),
            'terendah' => $query->orderBy('total_amount', 'asc'),
            default    => $query->orderBy('created_at', 'desc'),
        };

        $orders = $query->get();
        return view('orders.index', compact('orders', 'sort'));
    }

    public function show($id)
    {
        $order = Order::where('user_id', Auth::id())
                        ->with(['items.ticketZone.ticket', 'items.tokens'])
                        ->findOrFail($id);

        $snapToken = $order->snap_token;

        if ($order->status === 'pending') {
            Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            Config::$isSanitized  = true;
            Config::$is3ds        = true;

            $params = [
                'transaction_details' => [
                    'order_id'     => $order->order_number,
                    'gross_amount' => (int) $order->total_amount,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email'      => Auth::user()->email,
                ],
            ];

            try {
                $snapToken = Snap::getSnapToken($params);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Gagal generate snap token: ' . $e->getMessage());
            }
        }

        return view('orders.show', compact('order', 'snapToken'));
    }

    public function checkVoucher(Request $request)
    {
        $voucherId = null;
        if ($request->filled('voucher_code')) {
                $voucher = Voucher::where('code', $request->voucher_code)
                    ->where(function ($q) {
                        $q->whereNull('expired_at')->orWhere('expired_at', '>=', now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('quota')->orWhereColumn('used', '<', 'quota');
                    })
                    ->first();

            if ($voucher && $voucher->isValid()) {
                return response()->json([
                'status'          => 'success',
                'discount_amount' => $voucher->discount_amount,
                'message'         => 'Voucher berhasil digunakan!',
            ]);
        }

        return response()->json([
            'status'          => 'error',
            'discount_amount' => 0,
            'message'         => 'Voucher tidak valid, kadaluarsa, atau kuota habis.',
            ]);
        }
    }
}
