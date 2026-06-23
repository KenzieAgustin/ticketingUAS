<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Review & Rating</title>
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
    <a href="{{ route('admin.reviews.index') }}">Reset</a>
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
            <td>{{ $review->created_at ? $review->created_at->format('d M Y') : '-' }}</td>
            <td>
                @if($review->status === 'pending')
                    <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" style="display:inline">
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
                <form method="POST" action="{{ route('admin.reviews.reject', $review) }}">
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