<html>
<head><title>Detail Gate</title></head>
<body>

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
        <tr>
            <th>No</th>
            <th>Nama Staff</th>
            <th>Shift</th>
            <th>Jam Mulai</th>
            <th>Jam Selesai</th>
            <th>Status</th>
        </tr>
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
        <tr>
            <th>No</th>
            <th>Kode Booking</th>
            <th>Metode</th>
            <th>Status</th>
            <th>Waktu</th>
        </tr>
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