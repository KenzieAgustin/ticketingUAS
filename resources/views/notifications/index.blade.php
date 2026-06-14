<html>
<head>
    <title>Notifikasi</title>
</head>
<body>

<h2>Notifikasi</h2>

@if (session('success'))
    <div>{{ session('success') }}</div><br>
@endif

<nav>
    <a href="{{ route('home') }}">Home</a> |
    <a href="{{ route('profile.show') }}">Profil</a> |
    <a href="{{ route('notifications.index') }}">Notifikasi</a> |
    <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button type="submit">Logout</button>
    </form>
</nav>

<hr>

@if($notifications->isNotEmpty())
    <form method="POST" action="{{ route('notifications.read-all') }}" style="display:inline">
        @csrf
        @method('PATCH')
        <button type="submit">Tandai Semua Dibaca</button>
    </form>
    <br><br>
@endif

@forelse($notifications as $notif)
    @php $data = $notif->data; @endphp
    <div style="border:1px solid {{ $notif->read_at ? '#ccc' : '#3498db' }}; padding:10px; margin-bottom:10px; border-radius:4px;">
        <span style="{{ !$notif->read_at ? 'font-weight:bold' : '' }}">
            {{ $data['message'] }}
        </span>
        @if(!empty($data['ref_id']))
            <small style="color:#666"> — Ref #{{ $data['ref_id'] }}</small>
        @endif
        <br>
        <small style="color:#999">{{ $notif->created_at->diffForHumans() }}</small>

        <div style="margin-top:8px;">
            @if(!$notif->read_at)
                <form method="POST" action="{{ route('notifications.read', $notif->id) }}" style="display:inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit">Tandai Dibaca</button>
                </form>
            @endif

            <form method="POST" action="{{ route('notifications.destroy', $notif->id) }}" style="display:inline" onsubmit="return confirm('Hapus notifikasi ini?')">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        </div>
    </div>
@empty
    <p>Tidak ada notifikasi.</p>
@endforelse

{{ $notifications->links() }}

</body>
</html>
