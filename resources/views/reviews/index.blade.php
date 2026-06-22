<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ulasan Saya</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 40px auto; padding: 0 20px; color: #333; }
        h1 { font-size: 20px; margin-bottom: 2px; }
        .subtitle { font-size: 13px; color: #888; margin-bottom: 16px; }
        nav a { color: #333; text-decoration: none; margin-right: 4px; }
        nav a:hover { text-decoration: underline; }
        .alert-success { padding: 8px 12px; background: #e6f4ea; border-left: 3px solid #4caf50; margin-bottom: 16px; font-size: 14px; }
        hr { border: none; border-top: 1px solid #ddd; margin: 16px 0; }
    </style>
</head>
<body>

<h1>Pekan Raya Jakarta</h1>
<p class="subtitle">{{ Auth::user()->name }} — {{ Auth::user()->role }}</p>

<nav>
    <a href="{{ route('home') }}">Home</a> |
    <a href="{{ route('reviews.create') }}">Tulis Ulasan</a> |
    <a href="{{ route('reviews.index') }}">Ulasan Saya</a> |
    <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button type="submit" style="background:none;border:none;cursor:pointer;color:#c00;padding:0;font-size:14px">Logout</button>
    </form>
</nav>

<hr>

<h2>Ulasan Saya</h2>

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

<a href="{{ route('reviews.create') }}">+ Tulis Ulasan Baru</a>
<br><br>

@if($reviews->isEmpty())
    <p>Belum ada ulasan. <a href="{{ route('reviews.create') }}">Tulis sekarang</a>.</p>
@else
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Rating</th>
            <th>Ulasan</th>
            <th>Status</th>
            <th>Keterangan</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reviews as $review)
        <tr>
            <td style="text-align:center">{{ $loop->iteration }}</td>
            <td>{{ $review->title ?? '-' }}</td>
            <td style="text-align:center">{{ $review->rating }}/5</td>
            <td>{{ Str::limit($review->body, 60) }}</td>
            <td>
                @if($review->status == 'pending')
                    <span style="color:#92400e;background:#fef3c7;padding:2px 8px;border-radius:10px;font-size:12px">Pending</span>
                @elseif($review->status == 'approved')
                    <span style="color:#065f46;background:#d1fae5;padding:2px 8px;border-radius:10px;font-size:12px">Disetujui</span>
                @else
                    <span style="color:#991b1b;background:#fee2e2;padding:2px 8px;border-radius:10px;font-size:12px">Ditolak</span>
                @endif
            </td>
            <td>{{ $review->rejected_reason ?? '-' }}</td>
            <td>{{ $review->created_at ? $review->created_at->format('d M Y') : '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

</body>
</html>