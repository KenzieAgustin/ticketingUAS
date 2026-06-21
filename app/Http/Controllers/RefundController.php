<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Refund;
use App\Models\User;
use App\Notifications\AppNotification;

class RefundController extends Controller
{
    public function store(Request $request, $orderId)
    {
        $request->validate([
            'reason' => 'required|string|min:5'
        ]);

        $order = Order::findOrFail($orderId);

        Refund::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        $order->update([
            'status' => 'refund_pending'
        ]);

        // Mancing notif
        Auth::user()->notify(new AppNotification(
            type: 'refund_request',
            message: '🔄 Pengajuan refund untuk pesanan #' . $order->order_number . ' sedang diproses.',
            refId: $order->id,
        ));

        // Mancing notif buat admin
        User::where('role', 'admin')->each(function ($admin) use ($order) {
            $admin->notify(new AppNotification(
                type: 'new_refund_admin',
                message: '🔔 Ada pengajuan refund baru untuk pesanan #' . $order->order_number,
                refId: $order->id,
            ));
        });
        
        return redirect()->back()->with('success', 'Pengajuan refund berhasil dikirim. Menunggu persetujuan admin.');
    }

    public function index()
    {
        $refunds = Refund::with(['order'])->orderBy('created_at', 'desc')->get();
        return view('admin.refunds', compact('refunds'));
    }

    public function approve($id)
    {
        $refund = Refund::findOrFail($id);
        $refund->update(['status' => 'approved']);
        $refund->order->update(['status' => 'refunded']);

        // Mancing notif
        $refund->order->user->notify(new AppNotification(
            type: 'refund_approved',
            message: '✅ Refund untuk pesanan #' . $refund->order->order_number . ' telah disetujui!',
            refId: $refund->id,
        ));

        return redirect()->back()->with('success', 'Refund berhasil disetujui. Tiket telah dibatalkan.');
    }

    public function reject(Request $request, $id)
    {
        $refund = Refund::findOrFail($id);

        $refund->update([
            'status' => 'rejected',
            'admin_note' => $request->admin_note ?? 'Ditolak oleh admin.'
        ]);

        $refund->order->update(['status' => 'paid']);

        // Mancing notif
        $refund->order->user->notify(new AppNotification(
            type: 'refund_rejected',
            message: '❌ Refund untuk pesanan #' . $refund->order->order_number . ' ditolak. ' . ($request->admin_note ?? ''),
            refId: $refund->id,
        ));

        return redirect()->back()->with('error', 'Refund ditolak. Tiket kembali aktif.');
    }
}
