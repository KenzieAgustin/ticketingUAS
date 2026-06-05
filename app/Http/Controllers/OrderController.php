<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $order = Order::create([
            'user_id' => 1,
            'order_number' => 'ORD-' . time(),
            'nama' => $request->nama ?? 'Guest',
            'quantity' => $request->quantity ?? 1,
            'total_amount' => ($request->quantity ?? 1)*500000,
            'status' => 'pending',
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int)$order->total_amount,
            ],
            'customer_details' => [
                'first_name' => $order->nama,
                'email' => 'budi@example.com',
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            //return response()->json([
            //    'status' => 'success',
            //    'order_number' => $order->order_number,
            //    'snap_token' => $snapToken
            //]);

            return view('checkout', [
                'snapToken' => $snapToken,
                'order' => $order
                ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Pastikan kamu meng-import Request di bagian atas file jika belum ada:
// use Illuminate\Http\Request;

    public function index(Request $request)
    {
        $sort = $request->query('sort', 'terbaru');
        $query = Order::query();

        if ($sort == 'terlama') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort == 'tertinggi') {
            $query->orderBy('total_amount', 'desc');
        } elseif ($sort == 'terendah') {
        $query->orderBy('total_amount', 'asc');
        } else {
        $query->orderBy('created_at', 'desc');
        }

        $orders = $query->get();

        return view('orders.index', compact('orders', 'sort'));
    }

    public function show($id)
    {
    $order = Order::findOrFail($id);

    return view('orders.show', compact('order'));
    }
}
