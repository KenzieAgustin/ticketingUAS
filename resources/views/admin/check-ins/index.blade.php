<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Check-in</title>
    <style>
        body { font-family: sans-serif; max-width: 900px; margin: 40px auto; padding: 0 20px; color: #333; }
        h1 { font-size: 20px; margin-bottom: 2px; }
        .subtitle { font-size: 13px; color: #888; margin-bottom: 16px; }
        nav { font-size: 14px; }
        nav a { color: #333; text-decoration: none; margin-right: 4px; }
        nav a:hover { text-decoration: underline; }
        .admin-nav { margin-top: 6px; padding: 6px 10px; background: #f5f5f5; border-left: 3px solid #888; font-size: 13px; }
        .admin-nav a { color: #555; text-decoration: none; margin-right: 10px; }
        .admin-nav a:hover { text-decoration: underline; }
        .admin-nav a.active { font-weight: bold; color: #333; }
        hr { border: none; border-top: 1px solid #ddd; margin: 12px 0; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th { text-align: left; padding: 8px 12px; border-bottom: 2px solid #ddd; color: #888; font-weight: normal; }
        td { padding: 8px 12px; border-bottom: 1px solid #eee; }
        tr:hover td { background: #fafafa; }
        .action-badge { font-size: 11px; padding: 2px 8px; border-radius: 10px; }
        .action-login { background: #e8f4ea; color: #2e7d32; }
        .action-logout { background: #fde8e8; color: #c00; }
        .action-update_profile { background: #e8f0fe; color: #1a73e8; }
        .action-change_password { background: #fff3cd; color: #856404; }
        .empty { color: #aaa; font-size: 14px; padding: 20px 0; }
    </style>
</head>
<body>

<h1>Pekan Raya Jakarta</h1>
<p class="subtitle">Log aktivitas user</p>

<nav>
    <a href="{{ route('home') }}">← Home</a> |
    <a href="{{ route('profile.show') }}">Profil</a> |
    <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button type="submit" style="background:none; border:none; cursor:pointer; color:#c00; padding:0; font-size:14px">Logout</button>
    </form>
</nav>

<div class="admin-nav">
    Admin:
    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">Kelola User</a>
    <a href="{{ route('admin.activities.index') }}" class="{{ request()->routeIs('admin.activities*') ? 'active' : '' }}">Log Aktivitas</a>
    <a href="{{ route('admin.gates.index') }}" class="{{ request()->routeIs('admin.gates*') ? 'active' : '' }}">Gate</a>
    <a href="{{ route('admin.staff-assignments.index') }}" class="{{ request()->routeIs('admin.staff*') ? 'active' : '' }}">Staff</a>
    <a href="{{ route('admin.check-ins.index') }}" class="{{ request()->routeIs('admin.check*') ? 'active' : '' }}">Check-in</a>
    <a href="{{ route('admin.reviews.index') }}" class="{{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">Ulasan</a>
    <a href="{{ route('admin.refunds.index') }}" class="{{ request()->routeIs('admin.refunds*') ? 'active' : '' }}">Refund</a>
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">Dashboard</a>
</div>

<hr>

<h2>Check-in</h2>

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert-error">{{ session('error') }}</div>
@endif

<h3>Scan / Input Kode Tiket</h3>
<form method="POST" action="{{ route('admin.check-ins.scan') }}">
    @csrf
    Kode Booking: <input type="text" name="booking_code" required placeholder="Ketik atau scan...">
    Gate:
    <select name="gate_id" required>
        <option value="">-- Pilih Gate --</option>
        @foreach($gates as $gate)
        <option value="{{ $gate->id }}">{{ $gate->name }} ({{ $gate->code }})</option>
        @endforeach
    </select>
    Metode:
    <select name="method">
        <option value="qr_scan">QR Scan</option>
        <option value="manual">Manual</option>
    </select>
    <button type="submit">Proses Check-in</button>
</form>

<br>
<strong>Total: {{ $stats['total'] }}</strong> |
<strong>Berhasil: {{ $stats['success'] }}</strong> |
<strong>Gagal: {{ $stats['failed'] }}</strong> |
<strong>Duplikat: {{ $stats['duplicate'] }}</strong>
<br><br>

<form method="GET">
    Tanggal: <input type="date" name="date" value="{{ request('date', today()->format('Y-m-d')) }}">
    Status:
    <select name="status">
        <option value="">Semua Status</option>
        <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Berhasil</option>
        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
        <option value="duplicate" {{ request('status') == 'duplicate' ? 'selected' : '' }}>Duplikat</option>
    </select>
    Gate:
    <select name="gate_id">
        <option value="">Semua Gate</option>
        @foreach($gates as $gate)
        <option value="{{ $gate->id }}" {{ request('gate_id') == $gate->id ? 'selected' : '' }}>{{ $gate->name }}</option>
        @endforeach
    </select>
    <button type="submit">Filter</button>
    <a href="{{ route('admin.check-ins.index') }}">Reset</a>
</form>
<br>

@if($checkIns->isEmpty())
    <p>Belum ada data check-in.</p>
@else
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr><th>No</th><th>Kode Booking</th><th>Gate</th><th>Staff</th><th>Metode</th><th>Status</th><th>Keterangan</th><th>Waktu</th></tr>
    </thead>
    <tbody>
        @foreach($checkIns as $ci)
        <tr>
            <td style="text-align:center">{{ $loop->iteration }}</td>
            <td>{{ $ci->booking_code }}</td>
            <td>{{ $ci->gate->name ?? '-' }}</td>
            <td>{{ $ci->staff->name ?? '-' }}</td>
            <td>{{ $ci->method == 'qr_scan' ? 'QR Scan' : 'Manual' }}</td>
            <td>{{ ucfirst($ci->status) }}</td>
            <td>{{ $ci->failure_reason ?? '-' }}</td>
            <td>{{ $ci->checked_at?->format('H:i:s') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

</body>
</html>