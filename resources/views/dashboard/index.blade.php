<html>
<head><title>Dashboard</title></head>
<body>

<h2>Dashboard</h2>

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

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <td><strong>Total Gate</strong></td>
        <td>{{ $totalGates }} gate ({{ $activeGates }} aktif)</td>
    </tr>
    <tr>
        <td><strong>Check-in Hari Ini</strong></td>
        <td>{{ $todayCheckIns }} ({{ $successRate }}% berhasil)</td>
    </tr>
    <tr>
        <td><strong>Staff Bertugas Hari Ini</strong></td>
        <td>{{ $activeStaff }} aktif dari {{ $totalAssignments }} terjadwal</td>
    </tr>
    <tr>
        <td><strong>Review Pending</strong></td>
        <td>{{ $pendingReviews }} menunggu moderasi</td>
    </tr>
</table>

<br>
<h3>Check-in Terbaru Hari Ini</h3>

@if($recentCheckIns->isEmpty())
    <p>Belum ada data check-in hari ini.</p>
@else
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Booking</th>
            <th>Gate</th>
            <th>Staff</th>
            <th>Status</th>
            <th>Waktu</th>
        </tr>
    </thead>
    <tbody>
        @foreach($recentCheckIns as $ci)
        <tr>
            <td style="text-align:center">{{ $loop->iteration }}</td>
            <td>{{ $ci->booking_code }}</td>
            <td>{{ $ci->gate->name ?? '-' }}</td>
            <td>{{ $ci->staff->name ?? '-' }}</td>
            <td>{{ ucfirst($ci->status) }}</td>
            <td>{{ $ci->checked_at?->format('H:i:s') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<br>
<h3>Status Gate</h3>

@if($gateStatus->isEmpty())
    <p>Belum ada gate.</p>
@else
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama</th>
            <th>Tipe</th>
            <th>Status</th>
            <th>Check-in Hari Ini</th>
        </tr>
    </thead>
    <tbody>
        @foreach($gateStatus as $gate)
        <tr>
            <td style="text-align:center">{{ $loop->iteration }}</td>
            <td>{{ $gate->code }}</td>
            <td>{{ $gate->name }}</td>
            <td>{{ ucfirst($gate->type) }}</td>
            <td>{{ ucfirst($gate->status) }}</td>
            <td>{{ $gate->check_ins_count }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

</body>
</html>