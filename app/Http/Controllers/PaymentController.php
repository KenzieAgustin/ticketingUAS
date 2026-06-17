<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\TicketToken;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PaymentController extends Controller
{
    public function webhook(Request $request)
    {
        \Log::info('Midtrans webhook masuk:', $request->all());

        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed    = hash('sha512',
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        if ($hashed !== $request->signature_key) {
            \Log::warning('Midtrans signature tidak valid');
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order = Order::where('order_number', $request->order_id)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if (in_array($request->transaction_status, ['capture', 'settlement'])) {

            $order->update(['status' => 'paid']);

            // Tambah poin user
            $user = $order->user;
            if ($user) {
                $pointsEarned = floor($order->total_amount / 100000) * 10;
                if ($pointsEarned > 0) {
                    $user->increment('points', $pointsEarned);
                }
            }

            // Simpan payment
            Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'payment_method'    => $request->payment_type,
                    'transaction_id'    => $request->transaction_id,
                    'status'            => 'success',
                    'midtrans_response' => json_encode($request->all()),
                ]
            );

            // Generate token & QR untuk setiap order item
            $this->generateTokensForOrder($order);

        } elseif ($request->transaction_status === 'expire') {
            $order->update(['status' => 'expired']);

            // Kembalikan kuota zona
            foreach ($order->items as $item) {
                if ($item->ticketZone) {
                    $item->ticketZone->increment('quota_remaining', $item->quantity);
                }
            }

        } elseif ($request->transaction_status === 'cancel') {
            $order->update(['status' => 'cancelled']);

            // Kembalikan kuota zona
            foreach ($order->items as $item) {
                if ($item->ticketZone) {
                    $item->ticketZone->increment('quota_remaining', $item->quantity);
                }
            }
        }

        return response()->json(['message' => 'Callback received']);
    }

    private function generateTokensForOrder(Order $order)
    {
        $items = OrderItem::where('order_id', $order->id)->get();

        foreach ($items as $item) {
            // Skip kalau token sudah ada
            if (TicketToken::where('order_item_id', $item->id)->exists()) {
                continue;
            }

            // Generate booking code unik
            do {
                $bookingCode = 'PRJ2026-' . strtoupper(Str::random(6));
            } while (TicketToken::where('booking_code', $bookingCode)->exists());

            // Buat folder qrcodes kalau belum ada
            if (!file_exists(public_path('qrcodes'))) {
                mkdir(public_path('qrcodes'), 0777, true);
            }

            $fileName = $bookingCode . '.png';
            $path     = public_path('qrcodes/' . $fileName);

            QrCode::format('png')->size(250)->generate($bookingCode, $path);

            TicketToken::create([
                'order_item_id' => $item->id,
                'booking_code'  => $bookingCode,
                'qr_code_path'  => 'qrcodes/' . $fileName,
                'status'        => 'valid',
            ]);

            \Log::info('Token generated: ' . $bookingCode . ' untuk order item ' . $item->id);
        }
    }
}