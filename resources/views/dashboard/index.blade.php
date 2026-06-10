@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(99,102,241,.15)">🚪</div>
        <div class="stat-label">Total Gate</div>
        <div class="stat-value">{{ $totalGates ?? 0 }}</div>
        <div class="stat-sub">{{ $activeGates ?? 0 }} aktif</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(34,197,94,.15)">✅</div>
        <div class="stat-label">Check-in Hari Ini</div>
        <div class="stat-value">{{ $todayCheckIns ?? 0 }}</div>
        <div class="stat-sub">{{ $successRate ?? 0 }}% berhasil</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(245,158,11,.15)">👥</div>
        <div class="stat-label">Staff Bertugas</div>
        <div class="stat-value">{{ $activeStaff ?? 0 }}</div>
        <div class="stat-sub">dari {{ $totalAssignments ?? 0 }} ditugaskan</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(239,68,68,.15)">⭐</div>
        <div class="stat-label">Review Pending</div>
        <div class="stat-value">{{ $pendingReviews ?? 0 }}</div>
        <div class="stat-sub">menunggu moderasi</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px">
    {{-- Recent Check-ins --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Check-in Terbaru</div>
            <a href="{{ route('check-ins.index') }}" class="btn btn-ghost btn-sm">Lihat Semua</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Kode Booking</th>
                        <th>Gate</th>
                        <th>Staff</th>
                        <th>Status</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentCheckIns ?? [] as $ci)
                    <tr>
                        <td><code style="font-size:12px;background:var(--surface-2);padding:2px 7px;border-radius:4px">{{ $ci->booking_code }}</code></td>
                        <td>{{ $ci->gate->name ?? '-' }}</td>
                        <td>{{ $ci->staff->name ?? '-' }}</td>
                        <td>
                            @if($ci->status === 'success')
                                <span class="badge badge-success">Berhasil</span>
                            @elseif($ci->status === 'failed')
                                <span class="badge badge-danger">Gagal</span>
                            @else
                                <span class="badge badge-warning">Duplikat</span>
                            @endif
                        </td>
                        <td class="td-muted">{{ $ci->checked_at?->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5"><div class="empty-state" style="padding:30px"><div class="empty-state-icon">📭</div><div class="empty-state-text">Belum ada data check-in</div></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Gate Status --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Status Gate</div>
        </div>
        <div class="card-body" style="padding:0">
            @forelse($gateStatus ?? [] as $gate)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid var(--border)">
                <div>
                    <div style="font-size:13.5px;font-weight:600">{{ $gate->name }}</div>
                    <div style="font-size:12px;color:var(--text-muted)">{{ $gate->code }} · {{ $gate->type }}</div>
                </div>
                @if($gate->status === 'active')
                    <span class="badge badge-success">Aktif</span>
                @elseif($gate->status === 'maintenance')
                    <span class="badge badge-warning">Maintenance</span>
                @else
                    <span class="badge badge-neutral">Nonaktif</span>
                @endif
            </div>
            @empty
            <div class="empty-state" style="padding:30px"><div class="empty-state-icon">🚪</div><div class="empty-state-text">Belum ada gate</div></div>
            @endforelse
        </div>
    </div>
</div>
@endsection