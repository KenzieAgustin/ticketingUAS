<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>
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

<h2>Sales Report</h2>

<form method="GET">
    Tipe Tiket:
    <select name="ticket_type_id">
        <option value="">Semua Tipe</option>
        @foreach($ticketTypes as $tt)
        <option value="{{ $tt->id }}" {{ request('ticket_type_id') == $tt->id ? 'selected' : '' }}>
            {{ $tt->name }}
        </option>
        @endforeach
    </select>
    Dari: <input type="date" name="date_from" value="{{ request('date_from') }}">
    Sampai: <input type="date" name="date_to" value="{{ request('date_to') }}">
    <button type="submit">Filter</button>
    <a href="{{ route('admin.sales-report.index') }}">Reset</a>
</form>
<br>

<table border="1" cellpadding="8" cellspacing="0">
    <tr><td><strong>Total Tiket Terjual</strong></td><td>{{ number_format($summary->total_tickets ?? 0) }} tiket</td></tr>
    <tr><td><strong>Total Pendapatan</strong></td><td>Rp {{ number_format($summary->total_revenue ?? 0, 0, ',', '.') }}</td></tr>
    <tr><td><strong>Total Order</strong></td><td>{{ number_format($summary->total_orders ?? 0) }} order</td></tr>
    <tr><td><strong>Rata-rata per Order</strong></td><td>Rp {{ number_format($summary->avg_order_value ?? 0, 0, ',', '.') }}</td></tr>
</table>

<br>
<h3>Rekap Penjualan Harian</h3>

@if($dailyReport->isEmpty())
    <p>Belum ada data penjualan.</p>
@else
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr><th>No</th><th>Tanggal</th><th>Tiket Terjual</th><th>Total Order</th><th>Pendapatan</th></tr>
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
        <tr><th>No</th><th>Tipe Tiket</th><th>Tiket Terjual</th><th>Pendapatan</th></tr>
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

</body>
</html>