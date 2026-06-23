<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
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

<h2>Dashboard</h2>

<table border="1" cellpadding="8" cellspacing="0">
    <tr><td><strong>Total Gate</strong></td><td>{{ $totalGates }} gate ({{ $activeGates }} aktif)</td></tr>
    <tr><td><strong>Check-in Hari Ini</strong></td><td>{{ $todayCheckIns }} ({{ $successRate }}% berhasil)</td></tr>
    <tr><td><strong>Staff Bertugas Hari Ini</strong></td><td>{{ $activeStaff }} aktif dari {{ $totalAssignments }} terjadwal</td></tr>
    <tr><td><strong>Review Pending</strong></td><td>{{ $pendingReviews }} menunggu moderasi</td></tr>
</table>

<br>
<h3>Check-in Terbaru Hari Ini</h3>
@if($recentCheckIns->isEmpty())
    <p>Belum ada data check-in hari ini.</p>
@else
<table border="1" cellpadding="5" cellspacing="0">
    <thead><tr><th>No</th><th>Kode Booking</th><th>Gate</th><th>Staff</th><th>Status</th><th>Waktu</th></tr></thead>
    <tbody>
        @foreach($recentCheckIns as $ci)
        <tr>
            <td style="text-align:center">{{ $loop->iteration }}</td>
            <td>{{ $ci->booking_code }}</td>
            <td>{{ $ci->gate->name ?? '-' }}</td>
            <td>{{ $ci->staff->name ?? '-' }}</td>
            <td>{{ ucfirst($ci->status) }}</td>
            <td>{{ $ci->checked_at?->format('H:i:s') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<br>
<h3>Status Gate</h3>
@if($gateStatus->isEmpty())
    <p>Belum ada gate.</p>
@else
<table border="1" cellpadding="5" cellspacing="0">
    <thead><tr><th>No</th><th>Kode</th><th>Nama</th><th>Tipe</th><th>Status</th><th>Check-in Hari Ini</th></tr></thead>
    <tbody>
        @foreach($gateStatus as $gate)
        <tr>
            <td style="text-align:center">{{ $loop->iteration }}</td>
            <td>{{ $gate->code }}</td>
            <td>{{ $gate->name }}</td>
            <td>{{ ucfirst($gate->type) }}</td>
            <td>{{ ucfirst($gate->status) }}</td>
            <td>{{ $gate->check_ins_count }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

</body>
</html>