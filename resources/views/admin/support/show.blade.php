<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tiket #{{ $ticket->id }} — Admin PRJ 2026</title>
    <style>
        body { font-family: sans-serif; max-width: 720px; margin: 40px auto; padding: 0 20px; color: #333; }
        h1 { font-size: 20px; margin-bottom: 2px; }
        .subtitle { font-size: 13px; color: #888; margin-bottom: 16px; }
        nav a { color: #333; text-decoration: none; margin-right: 4px; }
        hr { border: none; border-top: 1px solid #ddd; margin: 16px 0; }
        .alert-success { padding: 8px 12px; background: #e6f4ea; border-left: 3px solid #4caf50; margin-bottom: 16px; font-size: 14px; }
        .badge { display: inline-block; padding: 2px 10px; border-radius: 10px; font-size: 12px; color: white; }

        /* Chat bubbles */
        .message-list { display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px; }
        .bubble { max-width: 80%; padding: 10px 14px; border-radius: 12px; font-size: 14px; line-height: 1.5; }
        .bubble-customer { background: #f0f0f0; align-self: flex-start; border-bottom-left-radius: 2px; }
        .bubble-admin    { background: #1a7a1a; color: white; align-self: flex-end; border-bottom-right-radius: 2px; }
        .bubble-meta { font-size: 11px; color: #aaa; margin-top: 3px; }
        .bubble-admin .bubble-meta { color: #aaffaa; text-align: right; }

        /* Form */
        textarea { width: 100%; padding: 9px; font-size: 14px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; resize: vertical; min-height: 90px; }
        .btn { padding: 9px 22px; background: #1a7a1a; color: white; border: none; cursor: pointer; font-size: 14px; border-radius: 4px; }
        .btn-close { padding: 8px 16px; background: #888; color: white; border: none; cursor: pointer; font-size: 13px; border-radius: 4px; }
        .ticket-info { background: #fafafa; border: 1px solid #eee; border-radius: 6px; padding: 12px 16px; font-size: 13px; margin-bottom: 20px; }
        .ticket-info td { padding: 3px 12px 3px 0; color: #555; }
        .ticket-info td:first-child { font-weight: bold; color: #333; white-space: nowrap; }
    </style>
</head>
<body>

<h1>Pekan Raya Jakarta</h1>
<p class="subtitle">{{ Auth::user()->name }} — {{ Auth::user()->role }}</p>
<nav>
    <a href="{{ route('admin.dashboard') }}">Dashboard</a> |
    <a href="{{ route('admin.support.index') }}">Support</a> |
    <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf <button type="submit" style="background:none;border:none;cursor:pointer;color:#c00;padding:0;font-size:14px">Logout</button>
    </form>
</nav>
<hr>

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

<div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
    <h2 style="margin:0;">{{ $ticket->subject }}</h2>
    <span class="badge" style="background:{{ $ticket->status_color }}">{{ $ticket->status_label }}</span>
</div>

<div class="ticket-info">
    <table>
        <tr><td>Tiket #</td><td>{{ $ticket->id }}</td></tr>
        <tr><td>Customer</td><td>{{ $ticket->user->name }} ({{ $ticket->user->email }})</td></tr>
        @if($ticket->order)
        <tr><td>Order</td><td>{{ $ticket->order->order_number }}</td></tr>
        @endif
        <tr><td>Dibuat</td><td>{{ $ticket->created_at->format('d M Y, H:i') }}</td></tr>
    </table>
</div>

{{-- Thread --}}
<div class="message-list">
    @foreach($ticket->messages as $msg)
        @php $isAdmin = $msg->user->role === 'admin'; @endphp
        <div>
            <div class="bubble {{ $isAdmin ? 'bubble-admin' : 'bubble-customer' }}">
                {{ $msg->message }}
                <div class="bubble-meta">
                    {{ $isAdmin ? 'Admin (' . $msg->user->name . ')' : $msg->user->name }}
                    · {{ $msg->created_at->format('d M Y, H:i') }}
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- Reply form --}}
@if($ticket->status !== 'closed')
<hr>
<h3 style="font-size:15px;">Balas sebagai Admin</h3>
<form method="POST" action="{{ route('admin.support.reply', $ticket) }}">
    @csrf
    <textarea name="message" maxlength="3000" placeholder="Tulis balasan..." required></textarea>
    <div style="margin-top: 10px; display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
        <button type="submit" class="btn">Kirim Balasan</button>
        <form method="POST" action="{{ route('admin.support.close', $ticket) }}" style="display:inline;">
            @csrf
            <button type="submit" class="btn-close" onclick="return confirm('Tutup tiket ini?')">Tutup Tiket</button>
        </form>
    </div>
</form>
@else
<div style="text-align:center; padding:20px 0; color:#888; font-size:14px;">
    Tiket ini sudah ditutup.
</div>
@endif

</body>
</html>