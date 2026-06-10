<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function webhook(Request $request)
    {

        \Log::info('Data masuk dari Midtrans:', $request->all());

        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            $order = Order::where('order_number', $request->order_id)->first();

            if ($order) {
                if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    $order->update(['status' => 'paid']);

                    $user = \App\Models\User::find($order->user_id);

                    if ($user) {
                        $pointsEarned = floor($order->total_amount / 100000) * 10;

                        if ($pointsEarned > 0) {
                            $user->increment('points', $pointsEarned);
                        }
                    }

                    Payment::updateOrCreate(
                        ['order_id' => $order->id],
                        [
                            'payment_method' => $request->payment_type,
                            'transaction_id' => $request->transaction_id,
                            'status' => 'success',
                            'midtrans_response' => json_encode($request->all())
                        ]
                    );
                } elseif ($request->transaction_status == 'expire') {
                    $order->update(['status' => 'expired']);
                } elseif ($request->transaction_status == 'cancel') {
                    $order->update(['status' => 'cancelled']);
                }
            }
        }

        return response()->json(['message' => 'Callback received']);

    }
}
