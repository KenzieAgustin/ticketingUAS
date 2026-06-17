<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Review & Rating</title>
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
    <a href="{{ route('dashboard') }}">Dashboard</a> |
    <a href="{{ route('gates.index') }}">Gate</a> |
    <a href="{{ route('staff-assignments.index') }}">Jadwal Staff</a> |
    <a href="{{ route('check-ins.index') }}">Check-in</a> |
    <a href="{{ route('reviews.index') }}">Review</a> |
    <a href="{{ route('sales-report.index') }}">Sales Report</a> |
    <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button type="submit" style="background:none;border:none;cursor:pointer;color:#c00;padding:0;font-size:14px">Logout</button>
    </form>
</nav>

<hr>

<h2>Review & Rating</h2>

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

<strong>Total: {{ $stats['total'] }}</strong> |
<strong>Pending: {{ $stats['pending'] }}</strong> |
<strong>Disetujui: {{ $stats['approved'] }}</strong> |
<strong>Ditolak: {{ $stats['rejected'] }}</strong>
<br><br>

<form method="GET">
    Status:
    <select name="status">
        <option value="">Semua Status</option>
        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
    </select>
    Rating:
    <select name="rating">
        <option value="">Semua Rating</option>
        @for($i = 5; $i >= 1; $i--)
        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
        @endfor
    </select>
    <button type="submit">Filter</button>
    <a href="{{ route('reviews.index') }}">Reset</a>
</form>
<br>

@if($reviews->isEmpty())
    <p>Belum ada review.</p>
@else
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr><th>No</th><th>Pengguna</th><th>Event ID</th><th>Rating</th><th>Judul</th><th>Ulasan</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
    </thead>
    <tbody>
        @foreach($reviews as $review)
        <tr>
            <td style="text-align:center">{{ $loop->iteration }}</td>
            <td>{{ $review->user->name ?? 'Unknown' }}</td>
            <td>{{ $review->event_id }}</td>
            <td>
                @for($i = 1; $i <= 5; $i++){{ $i <= $review->rating ? '★' : '☆' }}@endfor
                ({{ $review->rating }}/5)
            </td>
            <td>{{ $review->title ?? '-' }}</td>
            <td>{{ Str::limit($review->body, 60) }}</td>
            <td>{{ ucfirst($review->status) }}</td>
            <td>{{ $review->created_at->format('d M Y') }}</td>
            <td>
                @if($review->status === 'pending')
                    <form action="{{ route('reviews.approve', $review) }}" method="POST" style="display:inline">
                        @csrf @method('PATCH')
                        <button type="submit">Setujui</button>
                    </form>
                    |
                    <a href="#" onclick="document.getElementById('tolak-{{ $review->id }}').style.display='block'">Tolak</a>
                @else
                    —
                @endif
            </td>
        </tr>
        <tr id="tolak-{{ $review->id }}" style="display:none;background:#f5f5f5">
            <td colspan="9">
                <form method="POST" action="{{ route('reviews.reject', $review) }}">
                    @csrf @method('PATCH')
                    Alasan penolakan: <input type="text" name="reason" required style="width:300px">
                    <button type="submit">Tolak</button>
                    <button type="button" onclick="document.getElementById('tolak-{{ $review->id }}').style.display='none'">Batal</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

</body>
</html>