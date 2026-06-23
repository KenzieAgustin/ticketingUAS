<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Support Saya — PRJ 2026</title>
    <style>
        body { font-family: sans-serif; max-width: 820px; margin: 40px auto; padding: 0 20px; color: #333; }
        h1 { font-size: 20px; margin-bottom: 2px; }
        .subtitle { font-size: 13px; color: #888; margin-bottom: 16px; }
        nav a { color: #333; text-decoration: none; margin-right: 4px; }
        nav a:hover { text-decoration: underline; }
        hr { border: none; border-top: 1px solid #ddd; margin: 16px 0; }
        .alert-success { padding: 8px 12px; background: #e6f4ea; border-left: 3px solid #4caf50; margin-bottom: 16px; font-size: 14px; }
        .btn { display: inline-block; padding: 9px 20px; background: #c00; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th { text-align: left; padding: 8px; background: #f4f4f4; border-bottom: 2px solid #ddd; }
        td { padding: 10px 8px; border-bottom: 1px solid #eee; vertical-align: top; }
        tr:hover td { background: #fafafa; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 12px; color: white; }
        .empty { color: #aaa; padding: 32px 0; text-align: center; }
    </style>
</head>
<body>

<h1>Pekan Raya Jakarta</h1>
<p class="subtitle">{{ Auth::user()->name }} — {{ Auth::user()->role }}</p>
<nav>
    <a href="{{ route('home') }}">Home</a> |
    <a href="{{ route('support.index') }}">Support</a> |
    <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf <button type="submit" style="background:none;border:none;cursor:pointer;color:#c00;padding:0;font-size:14px">Logout</button>
    </form>
</nav>
<hr>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
    <h2 style="margin:0;">Tiket Support Saya</h2>
    <a href="{{ route('support.create') }}" class="btn">+ Buat Tiket Baru</a>
</div>

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

@if($tickets->isEmpty())
    <div class="empty">Belum ada tiket support. <a href="{{ route('support.create') }}">Buat tiket baru</a>.</div>
@else
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Subjek</th>
            <th>Status</th>
            <th>Terakhir Diperbarui</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($tickets as $ticket)
        <tr>
            <td style="color:#888; font-size:13px;">{{ $ticket->id }}</td>
            <td>
                {{ $ticket->subject }}
                @if($ticket->order_id)
                    <br><span style="font-size:12px; color:#888;">Order terkait</span>
                @endif
            </td>
            <td>
                <span class="badge" style="background: {{ $ticket->status_color }}">
                    {{ $ticket->status_label }}
                </span>
            </td>
            <td style="font-size:13px; color:#555;">{{ $ticket->updated_at->diffForHumans() }}</td>
            <td><a href="{{ route('support.show', $ticket) }}" style="font-size:13px;">Lihat →</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
<div style="margin-top:16px;">{{ $tickets->links() }}</div>
@endif

</body>
</html>