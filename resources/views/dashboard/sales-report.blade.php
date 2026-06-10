@extends('layouts.app')

@section('title', 'Sales Report')

@section('topbar-actions')
    <button class="btn btn-ghost" onclick="window.print()">🖨 Print</button>
    <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="btn btn-primary">⬇ Export CSV</a>
@endsection

@section('content')

{{-- Filter --}}
<div class="card" style="margin-bottom:20px">
    <div class="card-body" style="padding:16px 22px">
        <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
            <div class="form-group" style="margin:0;flex:1;min-width:140px">
                <label class="form-label">Event ID</label>
                <input type="number" name="event_id" class="form-control" placeholder="Semua Event" value="{{ request('event_id') }}">
            </div>
            <div class="form-group" style="margin:0;flex:1;min-width:140px">
                <label class="form-label">Tipe Tiket ID</label>
                <input type="number" name="ticket_type_id" class="form-control" placeholder="Semua Tipe" value="{{ request('ticket_type_id') }}">
            </div>
            <div class="form-group" style="margin:0;flex:1;min-width:140px">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="form-group" style="margin:0;flex:1;min-width:140px">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div style="display:flex;gap:8px">
                <button type="submit" class="btn btn-primary">🔍 Filter</button>
                @if(request()->anyFilled(['event_id','ticket_type_id','date_from','date_to']))
                    <a href="{{ route('sales-report.index') }}" class="btn btn-ghost">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Summary Stats --}}
<div class="stats-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:20px">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(99,102,241,.15)">🎫</div>
        <div class="stat-label">Total Tiket Terjual</div>
        <div class="stat-value">{{ number_format($summary['total_tickets'] ?? 0) }}</div>
        <div class="stat-sub">tiket</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(34,197,94,.15)">💰</div>
        <div class="stat-label">Total Pendapatan</div>
        <div class="stat-value" style="font-size:20px">Rp {{ number_format($summary['total_revenue'] ?? 0, 0, ',', '.') }}</div>
        <div class="stat-sub">dari order paid</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(245,158,11,.15)">📦</div>
        <div class="stat-label">Total Order</div>
        <div class="stat-value">{{ number_format($summary['total_orders'] ?? 0) }}</div>
        <div class="stat-sub">order berhasil</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(239,68,68,.15)">📊</div>
        <div class="stat-label">Rata-rata / Order</div>
        <div class="stat-value" style="font-size:20px">Rp {{ number_format($summary['avg_order_value'] ?? 0, 0, ',', '.') }}</div>
        <div class="stat-sub">per transaksi</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px">

    {{-- Tabel Rekap Harian --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Rekap Penjualan Harian</div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Tiket Terjual</th>
                        <th>Total Order</th>
                        <th>Pendapatan</th>
                        <th>Trend</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dailyReport ?? [] as $row)
                    <tr>
                        <td style="font-weight:500">{{ \Carbon\Carbon::parse($row->date)->format('d M Y') }}</td>
                        <td>{{ number_format($row->tickets_sold) }}</td>
                        <td>{{ number_format($row->total_orders) }}</td>
                        <td style="font-weight:600;color:var(--success)">Rp {{ number_format($row->revenue, 0, ',', '.') }}</td>
                        <td>
                            @if(isset($row->trend) && $row->trend > 0)
                                <span style="color:var(--success);font-size:12px">▲ {{ $row->trend }}%</span>
                            @elseif(isset($row->trend) && $row->trend < 0)
                                <span style="color:var(--danger);font-size:12px">▼ {{ abs($row->trend) }}%</span>
                            @else
                                <span style="color:var(--text-muted);font-size:12px">— 0%</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-state-icon">📊</div>
                                <div class="empty-state-text">Belum ada data penjualan.</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if(!empty($dailyReport) && count($dailyReport) > 0)
                <tfoot>
                    <tr style="background:var(--surface-2)">
                        <td style="font-weight:700;padding:12px 16px">Total</td>
                        <td style="font-weight:700;padding:12px 16px">{{ number_format(collect($dailyReport)->sum('tickets_sold')) }}</td>
                        <td style="font-weight:700;padding:12px 16px">{{ number_format(collect($dailyReport)->sum('total_orders')) }}</td>
                        <td style="font-weight:700;padding:12px 16px;color:var(--success)">Rp {{ number_format(collect($dailyReport)->sum('revenue'), 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
        @if(method_exists($dailyReport ?? collect(), 'links'))
        <div class="pagination">{{ $dailyReport->links() }}</div>
        @endif
    </div>

    {{-- Rekap per Tipe Tiket --}}
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Per Tipe Tiket</div>
            </div>
            <div style="padding:0">
                @forelse($byTicketType ?? [] as $row)
                <div style="padding:14px 20px;border-bottom:1px solid var(--border)">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
                        <div>
                            <div style="font-size:13.5px;font-weight:600">{{ $row->ticket_type_name ?? 'Tipe #'.$row->ticket_type_id }}</div>
                            <div style="font-size:12px;color:var(--text-muted)">{{ number_format($row->tickets_sold) }} tiket terjual</div>
                        </div>
                        <div style="text-align:right">
                            <div style="font-size:13px;font-weight:600;color:var(--success)">Rp {{ number_format($row->revenue, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    {{-- Progress bar --}}
                    @php $pct = ($summary['total_tickets'] ?? 0) > 0 ? round($row->tickets_sold / $summary['total_tickets'] * 100) : 0; @endphp
                    <div style="height:4px;background:var(--border);border-radius:4px;overflow:hidden">
                        <div style="height:100%;width:{{ $pct }}%;background:var(--accent);border-radius:4px;transition:width .4s"></div>
                    </div>
                    <div style="font-size:11px;color:var(--text-muted);margin-top:4px">{{ $pct }}% dari total</div>
                </div>
                @empty
                <div class="empty-state" style="padding:30px">
                    <div class="empty-state-icon">🎫</div>
                    <div class="empty-state-text">Belum ada data</div>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Top Events --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Top Event</div>
            </div>
            <div>
                @forelse($topEvents ?? [] as $i => $row)
                <div style="display:flex;align-items:center;gap:12px;padding:12px 20px;border-bottom:1px solid var(--border)">
                    <div style="width:26px;height:26px;border-radius:50%;background:{{ $i === 0 ? '#f59e0b' : ($i === 1 ? '#94a3b8' : ($i === 2 ? '#b45309' : 'var(--surface-2)')) }};display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0">
                        {{ $i + 1 }}
                    </div>
                    <div style="flex:1">
                        <div style="font-size:13px;font-weight:600">Event #{{ $row->event_id }}</div>
                        <div style="font-size:12px;color:var(--text-muted)">{{ number_format($row->tickets_sold) }} tiket</div>
                    </div>
                    <div style="font-size:13px;font-weight:600;color:var(--success)">Rp {{ number_format($row->revenue, 0, ',', '.') }}</div>
                </div>
                @empty
                <div class="empty-state" style="padding:30px">
                    <div class="empty-state-icon">🏆</div>
                    <div class="empty-state-text">Belum ada data</div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
@media print {
    .sidebar, .topbar, .filter-bar, .btn { display: none !important; }
    .main-wrap { margin-left: 0 !important; }
    body { background: white; color: black; }
    .card { border: 1px solid #ddd; }
}
</style>
@endpush