<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Check-in</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 40px auto; padding: 0 20px; color: #333; }
        h1 { font-size: 20px; margin-bottom: 2px; }
        .subtitle { font-size: 13px; color: #888; margin-bottom: 16px; }
        nav a { color: #333; text-decoration: none; margin-right: 4px; }
        nav a:hover { text-decoration: underline; }
        .alert-success { padding: 8px 12px; background: #e6f4ea; border-left: 3px solid #4caf50; margin-bottom: 16px; font-size: 14px; }
        .alert-error { padding: 8px 12px; background: #fee; border-left: 3px solid red; margin-bottom: 16px; font-size: 14px; }
        hr { border: none; border-top: 1px solid #ddd; margin: 16px 0; }
    </style>
</head>
<body>

<h1>Pekan Raya Jakarta</h1>
<p class="subtitle">{{ Auth::user()->name }} — {{ Auth::user()->role }}</p>

<nav>
    <a href="{{ route('home') }}">Home</a> |
    <a href="{{ route('admin.dashboard') }}">Dashboard</a> |
    <a href="{{ route('admin.gates.index') }}">Gate</a> |
    <a href="{{ route('admin.staff-assignments.index') }}">Jadwal Staff</a> |
    <a href="{{ route('admin.check-ins.index') }}">Check-in</a> |
    <a href="{{ route('admin.reviews.index') }}">Review</a> |
    <a href="{{ route('admin.sales-report.index') }}">Sales Report</a> |
    <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button type="submit" style="background:none;border:none;cursor:pointer;color:#c00;padding:0;font-size:14px">Logout</button>
    </form>
</nav>

<hr>

<h2>Check-in</h2>

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert-error">{{ session('error') }}</div>
@endif

<h3>Scan / Input Kode Tiket</h3>
<form method="POST" action="{{ route('admin.check-ins.scan') }}">
    @csrf
    Kode Booking: <input type="text" name="booking_code" required placeholder="Ketik atau scan...">
    Gate:
    <select name="gate_id" required>
        <option value="">-- Pilih Gate --</option>
        @foreach($gates as $gate)
        <option value="{{ $gate->id }}">{{ $gate->name }} ({{ $gate->code }})</option>
        @endforeach
    </select>
    Metode:
    <select name="method">
        <option value="qr_scan">QR Scan</option>
        <option value="manual">Manual</option>
    </select>
    <button type="submit">Proses Check-in</button>
</form>

<br>
<strong>Total: {{ $stats['total'] }}</strong> |
<strong>Berhasil: {{ $stats['success'] }}</strong> |
<strong>Gagal: {{ $stats['failed'] }}</strong> |
<strong>Duplikat: {{ $stats['duplicate'] }}</strong>
<br><br>

<form method="GET">
    Tanggal: <input type="date" name="date" value="{{ request('date', today()->format('Y-m-d')) }}">
    Status:
    <select name="status">
        <option value="">Semua Status</option>
        <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Berhasil</option>
        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
        <option value="duplicate" {{ request('status') == 'duplicate' ? 'selected' : '' }}>Duplikat</option>
    </select>
    Gate:
    <select name="gate_id">
        <option value="">Semua Gate</option>
        @foreach($gates as $gate)
        <option value="{{ $gate->id }}" {{ request('gate_id') == $gate->id ? 'selected' : '' }}>{{ $gate->name }}</option>
        @endforeach
    </select>
    <button type="submit">Filter</button>
    <a href="{{ route('admin.check-ins.index') }}">Reset</a>
</form>
<br>

@if($checkIns->isEmpty())
    <p>Belum ada data check-in.</p>
@else
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr><th>No</th><th>Kode Booking</th><th>Gate</th><th>Staff</th><th>Metode</th><th>Status</th><th>Keterangan</th><th>Waktu</th></tr>
    </thead>
    <tbody>
        @foreach($checkIns as $ci)
        <tr>
            <td style="text-align:center">{{ $loop->iteration }}</td>
            <td>{{ $ci->booking_code }}</td>
            <td>{{ $ci->gate->name ?? '-' }}</td>
            <td>{{ $ci->staff->name ?? '-' }}</td>
            <td>{{ $ci->method == 'qr_scan' ? 'QR Scan' : 'Manual' }}</td>
            <td>{{ ucfirst($ci->status) }}</td>
            <td>{{ $ci->failure_reason ?? '-' }}</td>
            <td>{{ $ci->checked_at?->format('H:i:s') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

</body>
</html>