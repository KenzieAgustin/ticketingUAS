@extends('layouts.app')

@section('title', 'Check-in')

@section('topbar-actions')
    <button class="btn btn-primary" onclick="openModal('modal-scan')">📷 Scan QR</button>
@endsection

@section('content')

{{-- Summary stats --}}
<div class="stats-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:20px">
    <div class="stat-card">
        <div class="stat-label">Total Hari Ini</div>
        <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Berhasil</div>
        <div class="stat-value" style="color:var(--success)">{{ $stats['success'] ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Gagal</div>
        <div class="stat-value" style="color:var(--danger)">{{ $stats['failed'] ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Duplikat</div>
        <div class="stat-value" style="color:var(--warning)">{{ $stats['duplicate'] ?? 0 }}</div>
    </div>
</div>

{{-- Filter --}}
<div class="filter-bar">
    <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap">
        <input type="date" name="date" class="form-control" value="{{ request('date', today()->format('Y-m-d')) }}" onchange="this.form.submit()">
        <select name="status" class="form-control" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="success"   {{ request('status') == 'success'   ? 'selected' : '' }}>Berhasil</option>
            <option value="failed"    {{ request('status') == 'failed'    ? 'selected' : '' }}>Gagal</option>
            <option value="duplicate" {{ request('status') == 'duplicate' ? 'selected' : '' }}>Duplikat</option>
        </select>
        <select name="gate_id" class="form-control" onchange="this.form.submit()">
            <option value="">Semua Gate</option>
            @foreach($gates ?? [] as $gate)
            <option value="{{ $gate->id }}" {{ request('gate_id') == $gate->id ? 'selected' : '' }}>{{ $gate->name }}</option>
            @endforeach
        </select>
        @if(request()->anyFilled(['status','gate_id']))
            <a href="{{ route('check-ins.index') }}" class="btn btn-ghost">Reset</a>
        @endif
    </form>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Riwayat Check-in</div>
        <div style="font-size:12.5px;color:var(--text-muted)">{{ request('date', today()->format('d M Y')) }}</div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
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
                @forelse($checkIns as $ci)
                <tr>
                    <td><code style="font-size:12px;background:var(--surface-2);padding:2px 8px;border-radius:4px">{{ $ci->booking_code }}</code></td>
                    <td>
                        <div style="font-weight:500">{{ $ci->gate->name ?? '-' }}</div>
                        <div class="td-muted">{{ $ci->gate->code ?? '' }}</div>
                    </td>
                    <td>{{ $ci->staff->name ?? '-' }}</td>
                    <td class="td-muted">{{ $ci->method === 'qr_scan' ? '📷 QR Scan' : '⌨ Manual' }}</td>
                    <td>
                        @if($ci->status === 'success') <span class="badge badge-success">✓ Berhasil</span>
                        @elseif($ci->status === 'failed') <span class="badge badge-danger">✕ Gagal</span>
                        @else <span class="badge badge-warning">⟳ Duplikat</span>
                        @endif
                    </td>
                    <td class="td-muted" style="max-width:180px;font-size:12.5px">{{ $ci->failure_reason ?? '—' }}</td>
                    <td class="td-muted">{{ $ci->checked_at?->format('H:i:s') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <div class="empty-state-icon">✅</div>
                            <div class="empty-state-text">Belum ada data check-in untuk filter ini.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(method_exists($checkIns, 'links'))
    <div class="pagination">{{ $checkIns->links() }}</div>
    @endif
</div>

{{-- Modal Scan QR --}}
<div class="modal-backdrop" id="modal-scan">
    <div class="modal" style="max-width:420px">
        <div class="modal-header">
            <div class="modal-title">Scan / Input Kode Tiket</div>
            <button class="modal-close" onclick="closeModal('modal-scan')">✕</button>
        </div>
        <form method="POST" action="{{ route('check-ins.scan') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Kode Booking</label>
                <input type="text" name="booking_code" class="form-control" placeholder="Scan QR atau ketik kode..." autofocus required style="font-size:16px;letter-spacing:1px">
            </div>
            <div class="form-group">
                <label class="form-label">Gate</label>
                <select name="gate_id" class="form-control" required>
                    <option value="">Pilih Gate</option>
                    @foreach($gates ?? [] as $gate)
                    <option value="{{ $gate->id }}">{{ $gate->name }} ({{ $gate->code }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Metode</label>
                <select name="method" class="form-control">
                    <option value="qr_scan">📷 QR Scan</option>
                    <option value="manual">⌨ Manual</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal('modal-scan')">Batal</button>
                <button type="submit" class="btn btn-primary">Proses Check-in</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
document.querySelectorAll('.modal-backdrop').forEach(el => {
    el.addEventListener('click', e => { if (e.target === el) el.classList.remove('open'); });
});
</script>
@endpush