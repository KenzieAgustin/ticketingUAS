<html>
<head><title>Sales Report</title></head>
<body>

<h2>Sales Report</h2>

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

{{-- Filter --}}
<form method="GET">
    Event ID: <input type="number" name="event_id" value="{{ request('event_id') }}" placeholder="Semua Event">
    Tipe Tiket ID: <input type="number" name="ticket_type_id" value="{{ request('ticket_type_id') }}" placeholder="Semua Tipe">
    Dari: <input type="date" name="date_from" value="{{ request('date_from') }}">
    Sampai: <input type="date" name="date_to" value="{{ request('date_to') }}">
    <button type="submit">Filter</button>
    <a href="{{ route('sales-report.index') }}">Reset</a>
</form>
<br>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <td><strong>Total Tiket Terjual</strong></td>
        <td>{{ number_format($summary->total_tickets ?? 0) }} tiket</td>
    </tr>
    <tr>
        <td><strong>Total Pendapatan</strong></td>
        <td>Rp {{ number_format($summary->total_revenue ?? 0, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td><strong>Total Order</strong></td>
        <td>{{ number_format($summary->total_orders ?? 0) }} order</td>
    </tr>
    <tr>
        <td><strong>Rata-rata per Order</strong></td>
        <td>Rp {{ number_format($summary->avg_order_value ?? 0, 0, ',', '.') }}</td>
    </tr>
</table>

<br>
<h3>Rekap Penjualan Harian</h3>

@if($dailyReport->isEmpty())
    <p>Belum ada data penjualan.</p>
@else
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Tiket Terjual</th>
            <th>Total Order</th>
            <th>Pendapatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dailyReport as $row)
        <tr>
            <td style="text-align:center">{{ $loop->iteration }}</td>
            <td>{{ \Carbon\Carbon::parse($row->date)->format('d M Y') }}</td>
            <td>{{ number_format($row->tickets_sold) }}</td>
            <td>{{ number_format($row->total_orders) }}</td>
            <td>Rp {{ number_format($row->revenue, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2"><strong>Total</strong></td>
            <td><strong>{{ number_format($dailyReport->sum('tickets_sold')) }}</strong></td>
            <td><strong>{{ number_format($dailyReport->sum('total_orders')) }}</strong></td>
            <td><strong>Rp {{ number_format($dailyReport->sum('revenue'), 0, ',', '.') }}</strong></td>
        </tr>
    </tfoot>
</table>
@endif

<br>
<h3>Per Tipe Tiket</h3>

@if($byTicketType->isEmpty())
    <p>Belum ada data.</p>
@else
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Tipe Tiket</th>
            <th>Tiket Terjual</th>
            <th>Pendapatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($byTicketType as $row)
        <tr>
            <td style="text-align:center">{{ $loop->iteration }}</td>
            <td>{{ $row->ticket_type_name ?? 'Tipe #'.$row->ticket_type_id }}</td>
            <td>{{ number_format($row->tickets_sold) }}</td>
            <td>Rp {{ number_format($row->revenue, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<br>
<h3>Top 5 Event</h3>

@if($topEvents->isEmpty())
    <p>Belum ada data.</p>
@else
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Event ID</th>
            <th>Tiket Terjual</th>
            <th>Pendapatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($topEvents as $row)
        <tr>
            <td style="text-align:center">{{ $loop->iteration }}</td>
            <td>Event #{{ $row->event_id }}</td>
            <td>{{ number_format($row->tickets_sold) }}</td>
            <td>Rp {{ number_format($row->revenue, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

</body>
</html>