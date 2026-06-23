<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\User;
use App\Notifications\AppNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{

    /** GET /support  list tiket milik customer */
    public function index()
    {
        abort_if(Auth::user()->role === 'admin', 403, 'Gunakan halaman admin untuk support.');

        $tickets = SupportTicket::with(['latestMessage.user'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('support.index', compact('tickets'));
    }

    /** GET /support/create  form buat tiket baru */
    public function create()
    {
        abort_if(Auth::user()->role === 'admin', 403, 'Gunakan halaman admin untuk support.');
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->get(['id', 'order_number', 'created_at']);

        return view('support.create', compact('orders'));
    }

    /** POST /support  simpan tiket baru */
    public function store(Request $request)
    {
        $request->validate([
            'subject'  => 'required|string|max:200',
            'message'  => 'required|string|max:3000',
            'order_id' => 'nullable|exists:orders,id',
        ]);

        // Pastikan order milik customer sendiri
        if ($request->order_id) {
            Order::where('id', $request->order_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
        }

        $ticket = SupportTicket::create([
            'user_id'  => Auth::id(),
            'order_id' => $request->order_id ?: null,
            'subject'  => $request->subject,
            'status'   => 'open',
        ]);

        SupportMessage::create([
            'ticket_id' => $ticket->id,
            'user_id'   => Auth::id(),
            'message'   => $request->message,
        ]);

        // Notif ke semua admin
        User::where('role', 'admin')->each(function (User $admin) use ($ticket) {
            $admin->notify(new AppNotification(
                type: 'support_new',
                message: 'Tiket support baru: "' . $ticket->subject . '" dari ' . Auth::user()->name,
                refId: $ticket->id,
            ));
        });

        return redirect()->route('support.show', $ticket)
            ->with('success', 'Tiket berhasil dibuat. Admin akan segera membalas.');
    }

    /** GET /support/{ticket}  thread percakapan (customer) */
    public function show(SupportTicket $ticket)
    {
        // Customer hanya boleh lihat tiket miliknya, admin tidak boleh akses route customer
        abort_if(Auth::user()->role === 'admin', 403, 'Gunakan halaman admin untuk support.');
        abort_unless($ticket->user_id === Auth::id(), 403);

        $ticket->load(['messages.user', 'order']);

        return view('support.show', compact('ticket'));
    }

    /** POST /support/{ticket}/reply  customer balas tiket */
    public function reply(Request $request, SupportTicket $ticket)
    {
        abort_unless($ticket->user_id === Auth::id(), 403);
        abort_if($ticket->status === 'closed', 403, 'Tiket sudah ditutup.');

        $request->validate(['message' => 'required|string|max:3000']);

        SupportMessage::create([
            'ticket_id' => $ticket->id,
            'user_id'   => Auth::id(),
            'message'   => $request->message,
        ]);

        $ticket->update(['status' => 'open']);

        // Notif balik ke admin
        User::where('role', 'admin')->each(function (User $admin) use ($ticket) {
            $admin->notify(new AppNotification(
                type: 'support_reply',
                message: Auth::user()->name . ' membalas tiket: "' . $ticket->subject . '"',
                refId: $ticket->id,
            ));
        });

        return redirect()->route('support.show', $ticket)
            ->with('success', 'Pesan terkirim.');
    }

    /** PATCH /support/{ticket}/close  customer tutup tiket */
    public function close(SupportTicket $ticket)
    {
        abort_unless($ticket->user_id === Auth::id(), 403);
        $ticket->update(['status' => 'closed']);

        return redirect()->route('support.show', $ticket)
            ->with('success', 'Tiket ditutup.');
    }


    /** GET /admin/support  list semua tiket */
    public function adminIndex(Request $request)
    {
        $request->validate([
            'status' => 'nullable|in:open,answered,closed',
        ]);

        $tickets = SupportTicket::with(['user', 'latestMessage.user'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20);

        return view('admin.support.index', compact('tickets'));
    }

    /** GET /admin/support/{ticket}  thread percakapan (admin) */
    public function adminShow(SupportTicket $ticket)
    {
        $ticket->load(['messages.user', 'user', 'order']);

        return view('admin.support.show', compact('ticket'));
    }

    /** POST /admin/support/{ticket}/reply  admin balas */
    public function adminReply(Request $request, SupportTicket $ticket)
    {
        $request->validate(['message' => 'required|string|max:3000']);

        SupportMessage::create([
            'ticket_id' => $ticket->id,
            'user_id'   => Auth::id(),
            'message'   => $request->message,
        ]);

        $ticket->update(['status' => 'answered']);

        // Notif ke customer
        $ticket->user->notify(new AppNotification(
            type: 'support_answered',
            message: 'Admin membalas tiket support Anda: "' . $ticket->subject . '"',
            refId: $ticket->id,
        ));

        return redirect()->route('admin.support.show', $ticket)
            ->with('success', 'Balasan terkirim.');
    }

    /** PATCH /admin/support/{ticket}/close  admin tutup tiket */
    public function adminClose(SupportTicket $ticket)
    {
        $ticket->update(['status' => 'closed']);

        $ticket->user->notify(new AppNotification(
            type: 'support_closed',
            message: 'Tiket support Anda "' . $ticket->subject . '" telah ditutup oleh admin.',
            refId: $ticket->id,
        ));

        return redirect()->route('admin.support.show', $ticket)
            ->with('success', 'Tiket ditutup.');
    }
}