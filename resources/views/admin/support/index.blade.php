<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Support — Admin PRJ 2026</title>
    <style>
        body { font-family: sans-serif; max-width: 960px; margin: 40px auto; padding: 0 20px; color: #333; }
        h1 { font-size: 20px; margin-bottom: 2px; }
        .subtitle { font-size: 13px; color: #888; margin-bottom: 16px; }
        nav a { color: #333; text-decoration: none; margin-right: 4px; }
        hr { border: none; border-top: 1px solid #ddd; margin: 16px 0; }
        .alert-success { padding: 8px 12px; background: #e6f4ea; border-left: 3px solid #4caf50; margin-bottom: 16px; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th { text-align: left; padding: 8px; background: #f4f4f4; border-bottom: 2px solid #ddd; }
        td { padding: 10px 8px; border-bottom: 1px solid #eee; vertical-align: top; }
        tr:hover td { background: #fafafa; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 12px; color: white; }
        .filter-form { display: flex; gap: 8px; align-items: center; margin-bottom: 16px; flex-wrap: wrap; }
        .filter-form select, .filter-form button { padding: 7px 12px; font-size: 13px; border: 1px solid #ccc; border-radius: 4px; }
        .filter-form button { background: #333; color: white; cursor: pointer; border: none; }
        .empty { color: #aaa; padding: 32px 0; text-align: center; }
        .dot-open { display: inline-block; width: 8px; height: 8px; background: #e67e22; border-radius: 50%; margin-right: 4px; }
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

<h2>Tiket Support Masuk</h2>

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

<form class="filter-form" method="GET">
    <label style="font-size:13px;">Filter:</label>
    <select name="status">
        <option value="">Semua Status</option>
        <option value="open"     {{ request('status') === 'open'     ? 'selected' : '' }}>Menunggu Balasan</option>
        <option value="answered" {{ request('status') === 'answered' ? 'selected' : '' }}>Sudah Dibalas</option>
        <option value="closed"   {{ request('status') === 'closed'   ? 'selected' : '' }}>Ditutup</option>
    </select>
    <button type="submit">Filter</button>
    @if(request('status'))
        <a href="{{ route('admin.support.index') }}" style="font-size:13px; color:#888;">Reset</a>
    @endif
</form>

@if($tickets->isEmpty())
    <div class="empty">Tidak ada tiket support.</div>
@else
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Customer</th>
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
            <td style="font-size:13px;">
                {{ $ticket->user->name }}<br>
                <span style="color:#aaa;">{{ $ticket->user->email }}</span>
            </td>
            <td>
                @if($ticket->status === 'open')
                    <span class="dot-open" title="Menunggu balasan"></span>
                @endif
                {{ $ticket->subject }}
            </td>
            <td>
                <span class="badge" style="background:{{ $ticket->status_color }}">
                    {{ $ticket->status_label }}
                </span>
            </td>
            <td style="font-size:13px; color:#555;">{{ $ticket->updated_at->diffForHumans() }}</td>
            <td><a href="{{ route('admin.support.show', $ticket) }}" style="font-size:13px;">Balas →</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
<div style="margin-top:16px;">{{ $tickets->links() }}</div>
@endif

</body>
</html>