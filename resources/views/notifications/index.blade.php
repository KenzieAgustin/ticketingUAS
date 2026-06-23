<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Notifikasi - PRJ</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 40px auto; padding: 0 20px; color: #333; }
        h1 { font-size: 20px; margin-bottom: 2px; }
        .subtitle { font-size: 13px; color: #888; margin-bottom: 16px; }
        nav a { color: #333; text-decoration: none; margin-right: 4px; }
        nav a:hover { text-decoration: underline; }
        .notif-badge { background: red; color: white; font-size: 11px; padding: 1px 6px; border-radius: 10px; }
        .alert { padding: 8px 12px; background: #e6f4ea; border-left: 3px solid #4caf50; margin-bottom: 16px; font-size: 14px; }
        hr { border: none; border-top: 1px solid #ddd; margin: 16px 0; }
        .btn { background: none; border: 1px solid #ccc; cursor: pointer; color: #333; padding: 4px 10px; font-size: 13px; border-radius: 4px; }
        .btn:hover { background: #f5f5f5; }
        .btn-red { color: #c00; border-color: #f5c6cb; }
        .notif-card { border: 1px solid #ccc; padding: 12px 14px; margin-bottom: 10px; border-radius: 4px; }
        .notif-card.unread { border-color: #3498db; }
        .notif-msg { font-size: 14px; }
        .notif-msg.bold { font-weight: bold; }
        .notif-ref { font-size: 12px; color: #999; }
        .notif-time { font-size: 12px; color: #aaa; margin-top: 4px; }
        .notif-actions { margin-top: 8px; display: flex; gap: 8px; }
    </style>
</head>
<body>

<h1>Pekan Raya Jakarta</h1>
<p class="subtitle">Notifikasi kamu</p>

@if (session('success'))
    <div class="alert">{{ session('success') }}</div>
@endif

<nav>
    <a href="{{ route('home') }}">Home</a> |
    <a href="{{ route('profile.show') }}">Profil</a> |
    <a href="{{ route('notifications.index') }}" style="font-weight:bold;">
        Notifikasi
        @php $unread = Auth::user()->unreadNotifications->count() @endphp
        @if ($unread > 0)<span class="notif-badge">{{ $unread }}</span>@endif
    </a> |
    <a href="{{ route('web.events.index') }}">Event</a> |
    <a href="{{ route('order.index') }}">Pesanan Saya</a> |
    <a href="/tickets">Tiket Saya</a> |
    <a href="/points">Poin</a> |
    <a href="{{ route('reviews.index') }}">Ulasan</a> |
    <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button type="submit" style="background:none; border:none; cursor:pointer; color:#c00; padding:0; font-size:14px">Logout</button>
    </form>
</nav>

<hr>

@if($notifications->isNotEmpty())
    <form method="POST" action="{{ route('notifications.read-all') }}" style="display:inline">
        @csrf
        @method('PATCH')
        <button class="btn" type="submit" style="margin-bottom:14px;">Tandai semua dibaca</button>
    </form>
@endif

@forelse($notifications as $notif)
    @php $data = $notif->data; @endphp
    <div class="notif-card {{ !$notif->read_at ? 'unread' : '' }}">
        <div class="notif-msg {{ !$notif->read_at ? 'bold' : '' }}">
            {{ $data['message'] }}
            @if(!empty($data['ref_id']))
                <span class="notif-ref"> — Ref #{{ $data['ref_id'] }}</span>
            @endif
        </div>
        <div class="notif-time">{{ $notif->created_at->diffForHumans() }}</div>
        <div class="notif-actions">
            @if(!$notif->read_at)
                <form method="POST" action="{{ route('notifications.read', $notif->id) }}" style="display:inline">
                    @csrf
                    @method('PATCH')
                    <button class="btn" type="submit">Tandai dibaca</button>
                </form>
            @endif
            <form method="POST" action="{{ route('notifications.destroy', $notif->id) }}" style="display:inline" onsubmit="return confirm('Hapus notifikasi ini?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-red" type="submit">Hapus</button>
            </form>
        </div>
    </div>
@empty
    <p style="font-size:14px; color:#888;">Tidak ada notifikasi.</p>
@endforelse

{{ $notifications->links() }}

</body>
</html>