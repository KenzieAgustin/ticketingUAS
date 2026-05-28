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
            'total_amount' => 500000,
            'status' => 'pending',
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => $order->total_amount,
            ],
            'customer_details' => [
                'first_name' => 'Budi',
                'email' => 'budi@example.com',
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'status' => 'success',
                'order_number' => $order->order_number,
                'snap_token' => $snapToken
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
