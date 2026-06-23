<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Point Reward - PRJ</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 40px auto; padding: 0 20px; color: #333; }
        h1 { font-size: 20px; margin-bottom: 2px; }
        .subtitle { font-size: 13px; color: #888; margin-bottom: 16px; }
        nav a { color: #333; text-decoration: none; margin-right: 4px; }
        nav a:hover { text-decoration: underline; }
        .notif-badge { background: red; color: white; font-size: 11px; padding: 1px 6px; border-radius: 10px; }
        .alert { padding: 8px 12px; background: #e6f4ea; border-left: 3px solid #4caf50; margin-bottom: 16px; font-size: 14px; }
        hr { border: none; border-top: 1px solid #ddd; margin: 16px 0; }

        .point-container { max-width: 500px; margin-top: 20px; }
        .point-container input { padding: 8px; margin: 10px 0; width: 100%; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        .point-container button { padding: 10px 20px; background: #4CAF50; color: white; border: none; cursor: pointer; border-radius: 4px; }
        .point-container button:hover { background: #45a049; }
        .success { color: green; background: #e8f5e9; padding: 10px; border-radius: 4px; margin-top: 10px; }
        .error { color: red; background: #ffebee; padding: 10px; border-radius: 4px; margin-top: 10px; }
    </style>
</head>
<body>

    <!-- Header & Notifikasi Umum -->
    <h1>Pekan Raya Jakarta</h1>
    <p class="subtitle">Point Reward</p>

    <!-- Navigation Bar -->
    <nav>
        <a href="{{ route('home') }}">Home</a> |
        <a href="{{ route('profile.show') }}">Profil</a> |
        <a href="{{ route('notifications.index') }}">
            Notifikasi
            @php $unread = Auth::user()->unreadNotifications->count() @endphp
            @if ($unread > 0)<span class="notif-badge">{{ $unread }}</span>@endif
        </a> |
        <a href="{{ route('web.events.index') }}">Event</a> |
        <a href="{{ route('order.index') }}">Pesanan Saya</a> |
        <a href="/tickets">Tiket Saya</a> |
        <a href="/points" style="font-weight:bold;">Poin</a> |
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit" style="background:none; border:none; cursor:pointer; color:#c00; padding:0; font-size:14px">Logout</button>
        </form>
    </nav>

    <hr>

    <!-- Area Konten Penukaran Poin -->
    <div class="point-container">
        <h2>Tukar Poin</h2>

        @php $user = auth()->user(); @endphp

        @if(!$user)
            <p style="color:red"> Tidak ada user di database.</p>
        @else
            <p>User: <strong>{{ $user->name }}</strong></p>
            <p>Poin kamu: <strong>{{ $user->points ?? 0 }}</strong></p>
            <p><small>Info: Setiap Rp100.000 pembelian = 10 poin. Setiap 10 poin = diskon Rp10.000</small></p>

            <form method="POST" action="/redeem">
                @csrf
                <label>Jumlah Poin yang ditukar (kelipatan 10):</label>
                <input type="number" name="required_points" min="10" step="10" value="10">
                <button type="submit"> Tukar Poin</button>
            </form>

            @if(session('success'))
                <div class="success"> {{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="error"> {{ session('error') }}</div>
            @endif
        @endif

        <hr style="border: none; border-top: 1px dashed #ccc; margin: 30px 0 20px 0;">
        <h3 style="font-size: 16px; margin-bottom: 15px;">Riwayat Mutasi Poin</h3>

        @if(isset($histories) && $histories->isEmpty())
            <p style="color: #888; font-size: 14px; text-align: center; padding: 20px; background: #f9f9f9; border-radius: 4px;">Belum ada aktivitas poin.</p>
        @elseif(isset($histories))
            <table style="width: 100%; border-collapse: collapse; font-size: 14px; margin-bottom: 30px;">
                <thead>
                    <tr style="background-color: #f4f4f4;">
                        <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Tanggal</th>
                        <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Keterangan</th>
                        <th style="padding: 10px; border: 1px solid #ddd; text-align: right;">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($histories as $history)
                        <tr>
                            <td style="padding: 10px; border: 1px solid #ddd; color: #555;">
                                {{ $history->created_at->format('d M Y, H:i') }}
                            </td>
                            <td style="padding: 10px; border: 1px solid #ddd;">
                                {{ $history->description }}
                            </td>
                            <td style="padding: 10px; border: 1px solid #ddd; text-align: right; font-weight: bold; color: {{ $history->type === 'earn' ? '#4CAF50' : '#d00' }};">
                                {{ $history->amount > 0 ? '+' : '' }}{{ $history->amount }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</body>
</html>
