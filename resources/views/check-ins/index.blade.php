<html>
<head><title>Check-in</title></head>
<body>

<h2>Check-in</h2>

<a href="{{ route('dashboard') }}">Dashboard</a> |
<a href="{{ route('gates.index') }}">Gate</a> |
<a href="{{ route('staff-assignments.index') }}">Jadwal Staff</a> |
<a href="{{ route('check-ins.index') }}">Check-in</a> |
<a href="{{ route('reviews.index') }}">Review</a> |
<a href="{{ route('sales-report.index') }}">Sales Report</a> |
<form method="POST" action="{{ route('logout') }}" style="display:inline">
    @csrf <button type="submit">Logout</button>
</form>

<br><br>

@if(session('success'))
    <p><strong>{{ session('success') }}</strong></p>
@endif
@if(session('error'))
    <p><strong>Error: {{ session('error') }}</strong></p>
@endif

<h3>Scan / Input Kode Tiket</h3>
<form method="POST" action="{{ route('check-ins.scan') }}">
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

{{-- Filter --}}
<form method="GET">
    Tanggal: <input type="date" name="date" value="{{ request('date', today()->format('Y-m-d')) }}">
    Status:
    <select name="status">
        <option value="">Semua Status</option>
        <option value="success"   {{ request('status') == 'success'   ? 'selected' : '' }}>Berhasil</option>
        <option value="failed"    {{ request('status') == 'failed'    ? 'selected' : '' }}>Gagal</option>
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
    <a href="{{ route('check-ins.index') }}">Reset</a>
</form>
<br>

@if($checkIns->isEmpty())
    <p>Belum ada data check-in.</p>
@else
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Booking</th>
            <th>Gate</th>
            <th>Staff</th>
            <th>Metode</th>
            <th>Status</th>
            <th>Keterangan</th>
            <th>Waktu</th>
        </tr>
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