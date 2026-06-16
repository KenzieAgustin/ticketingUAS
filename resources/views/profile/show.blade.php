<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil - PRJ</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 40px auto; padding: 0 20px; color: #333; }
        h1 { font-size: 20px; margin-bottom: 2px; }
        .subtitle { font-size: 13px; color: #888; margin-bottom: 16px; }
        nav a { color: #333; text-decoration: none; margin-right: 4px; }
        nav a:hover { text-decoration: underline; }
        .notif-badge { background: red; color: white; font-size: 11px; padding: 1px 6px; border-radius: 10px; }
        .alert { padding: 8px 12px; background: #e6f4ea; border-left: 3px solid #4caf50; margin-bottom: 16px; font-size: 14px; }
        hr { border: none; border-top: 1px solid #ddd; margin: 16px 0; }
        .avatar { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; margin-bottom: 12px; }
        .profile-row { font-size: 14px; margin-bottom: 8px; }
        .profile-row span { color: #888; }
        .btn { background: none; border: 1px solid #ccc; cursor: pointer; color: #333; padding: 4px 10px; font-size: 13px; border-radius: 4px; text-decoration: none; }
        .btn:hover { background: #f5f5f5; }
    </style>
</head>
<body>

<h1>Pekan Raya Jakarta</h1>
<p class="subtitle">Profil saya</p>

@if (session('success'))
    <div class="alert">{{ session('success') }}</div>
@endif

<nav>
    <a href="{{ route('home') }}">Home</a> |
    <a href="{{ route('profile.show') }}" style="font-weight:bold;">Profil</a> |
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

<hr>

<img src="{{ $user->avatar_url }}" alt="Avatar" class="avatar"><br>

<div class="profile-row"><span>Nama:</span> {{ $user->name }}</div>
<div class="profile-row"><span>Email:</span> {{ $user->email }}</div>
<div class="profile-row"><span>No. HP:</span> {{ $user->phone ?? '-' }}</div>
<div class="profile-row"><span>Alamat:</span> {{ $user->address ?? '-' }}</div>

<div style="margin-top:16px; display:flex; gap:10px;">
    <a href="{{ route('profile.edit') }}" class="btn">Edit profil</a>
    <a href="{{ route('profile.password') }}" class="btn">Ganti password</a>
</div>

</body>
</html>