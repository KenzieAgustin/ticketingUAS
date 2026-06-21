<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>PRJ - Pekan Raya Jakarta</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 40px auto; padding: 0 20px; color: #333; }
        h1 { font-size: 20px; margin-bottom: 2px; }
        .subtitle { font-size: 13px; color: #888; margin-bottom: 16px; }
        nav a { color: #333; text-decoration: none; margin-right: 4px; }
        nav a:hover { text-decoration: underline; }
        .notif-badge { background: red; color: white; font-size: 11px; padding: 1px 6px; border-radius: 10px; }
        .admin-section { margin-top: 6px; padding: 8px 12px; background: #f5f5f5; border-left: 3px solid #888; font-size: 14px; }
        .admin-section a { color: #333; text-decoration: none; margin-right: 10px; }
        .admin-section a:hover { text-decoration: underline; }
        .staff-section { margin-top: 6px; padding: 8px 12px; background: #fff8e1; border-left: 3px solid #f0a500; font-size: 14px; }
        .staff-section a { color: #333; text-decoration: none; margin-right: 10px; }
        .staff-section a:hover { text-decoration: underline; }
        .alert { padding: 8px 12px; background: #e6f4ea; border-left: 3px solid #4caf50; margin-bottom: 16px; font-size: 14px; }
        hr { border: none; border-top: 1px solid #ddd; margin: 16px 0; }
        .role { font-size: 13px; color: #888; }
    </style>
</head>
<body>

<h1>Pekan Raya Jakarta</h1>
<p class="subtitle">Selamat datang, {{ Auth::user()->name }}!</p>

@if (session('success'))
    <div class="alert">{{ session('success') }}</div>
@endif

<nav>
    <a href="{{ route('home') }}" style="font-weight:bold;">Home</a> |
    <a href="{{ route('profile.show') }}">Profil</a> |
    <a href="{{ route('notifications.index') }}">
        Notifikasi
        @php $unread = Auth::user()->unreadNotifications->count() @endphp
        @if ($unread > 0)<span class="notif-badge">{{ $unread }}</span>@endif
    </a> |
    <a href="{{ route('web.events.index') }}">Event</a> |
    <a href="{{ route('order.index') }}">Pesanan Saya</a> |
    <a href="/tickets">Tiket Saya</a> |
    <a href="/points">Poin</a> |
    <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button type="submit" style="background:none; border:none; cursor:pointer; color:#c00; padding:0; font-size:14px">Logout</button>
    </form>
</nav>

{{-- Admin section --}}
@if(Auth::user()->isAdmin())
    <div class="admin-section">
        <span style="color:#888; font-size:13px;">Admin:</span>
        <a href="{{ route('admin.users.index') }}">Kelola User</a>
        <a href="{{ route('admin.activities.index') }}">Log Aktivitas</a>
        <a href="{{ route('admin.gates.index') }}">Gate</a>
        <a href="{{ route('staff.scan') }}">Scan Tiket</a>
        <a href="{{ route('admin.tracker.index') }}">Quota Tracker</a>
        <a href="{{ route('admin.check-ins.index') }}">Check-in</a>
        <a href="{{ route('admin.reviews.index') }}">Ulasan</a>
        <a href="{{ route('admin.refunds.index') }}">Refund</a>
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    </div>
@endif

{{-- Staff Gate section --}}
@if(Auth::user()->isStaffGate())
    <div class="staff-section">
        Staff:
        <a href="{{ route('staff.scan') }}">Scan Tiket</a>
        <a href="{{ route('staff.check-ins.scan') }}">Check-in</a>
    </div>
@endif

<hr>

@if(Auth::user()->isAdmin() || Auth::user()->isStaffGate())
    <p class="role">Role: {{ Auth::user()->role }}</p>
@endif

</body>
</html>