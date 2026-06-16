<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Gate</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 40px auto; padding: 0 20px; color: #333; }
        h1 { font-size: 20px; margin-bottom: 2px; }
        .subtitle { font-size: 13px; color: #888; margin-bottom: 16px; }
        nav a { color: #333; text-decoration: none; margin-right: 4px; }
        nav a:hover { text-decoration: underline; }
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

<a href="{{ route('gates.index') }}">← Kembali ke Daftar Gate</a>

<h2>Detail Gate: {{ $gate->name }}</h2>

<table border="1" cellpadding="5" cellspacing="0">
    <tr><td><strong>Kode</strong></td><td>{{ $gate->code }}</td></tr>
    <tr><td><strong>Nama</strong></td><td>{{ $gate->name }}</td></tr>
    <tr><td><strong>Tipe</strong></td><td>{{ ucfirst($gate->type) }}</td></tr>
    <tr><td><strong>Status</strong></td><td>{{ ucfirst($gate->status) }}</td></tr>
    <tr><td><strong>Deskripsi</strong></td><td>{{ $gate->description ?? '-' }}</td></tr>
    <tr><td><strong>Dibuat</strong></td><td>{{ $gate->created_at->format('d M Y') }}</td></tr>
</table>

<br>
<h3>Staff Bertugas</h3>

@if($gate->staffAssignments->isEmpty())
    <p>Belum ada staff ditugaskan.</p>
@else
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr><th>No</th><th>Nama Staff</th><th>Shift</th><th>Jam Mulai</th><th>Jam Selesai</th><th>Status</th></tr>
    </thead>
    <tbody>
        @foreach($gate->staffAssignments as $sa)
        <tr>
            <td style="text-align:center">{{ $loop->iteration }}</td>
            <td>{{ $sa->staff->name ?? '-' }}</td>
            <td>{{ ucfirst(str_replace('_', ' ', $sa->shift)) }}</td>
            <td>{{ $sa->shift_start }}</td>
            <td>{{ $sa->shift_end }}</td>
            <td>{{ ucfirst($sa->status) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<br>
<h3>Check-in Terbaru</h3>

@if($gate->checkIns->isEmpty())
    <p>Belum ada check-in di gate ini.</p>
@else
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr><th>No</th><th>Kode Booking</th><th>Metode</th><th>Status</th><th>Waktu</th></tr>
    </thead>
    <tbody>
        @foreach($gate->checkIns as $ci)
        <tr>
            <td style="text-align:center">{{ $loop->iteration }}</td>
            <td>{{ $ci->booking_code }}</td>
            <td>{{ $ci->method == 'qr_scan' ? 'QR Scan' : 'Manual' }}</td>
            <td>{{ ucfirst($ci->status) }}</td>
            <td>{{ $ci->checked_at?->format('d M Y H:i') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

</body>
</html>