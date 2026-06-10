@extends('layouts.app')

@section('title', 'Detail Gate — ' . $gate->name)

@section('topbar-actions')
    <a href="{{ route('gates.index') }}" class="btn btn-ghost btn-sm">← Kembali</a>
    <button class="btn btn-primary btn-sm" onclick="openModal('modal-edit')">Edit</button>
@endsection

@section('content')
<div style="display:grid;grid-template-columns:1fr 2fr;gap:20px">

    {{-- Info Gate --}}
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div class="card-body">
                <div style="text-align:center;padding:10px 0 20px">
                    <div style="font-size:48px;margin-bottom:8px">🚪</div>
                    <div style="font-family:'Space Grotesk',sans-serif;font-size:22px;font-weight:700">{{ $gate->name }}</div>
                    <code style="font-size:13px;background:var(--surface-2);padding:3px 10px;border-radius:4px;letter-spacing:1px">{{ $gate->code }}</code>
                </div>
                <div style="display:flex;flex-direction:column;gap:12px;border-top:1px solid var(--border);padding-top:16px">
                    <div style="display:flex;justify-content:space-between;align-items:center">
                        <span style="font-size:12.5px;color:var(--text-muted)">Tipe</span>
                        <span class="badge badge-info">{{ ucfirst($gate->type) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center">
                        <span style="font-size:12.5px;color:var(--text-muted)">Status</span>
                        @if($gate->status === 'active')
                            <span class="badge badge-success">● Aktif</span>
                        @elseif($gate->status === 'maintenance')
                            <span class="badge badge-warning">⚠ Maintenance</span>
                        @else
                            <span class="badge badge-neutral">○ Nonaktif</span>
                        @endif
                    </div>
                    @if($gate->stage_id)
                    <div style="display:flex;justify-content:space-between;align-items:center">
                        <span style="font-size:12.5px;color:var(--text-muted)">Stage ID</span>
                        <span style="font-size:13px">{{ $gate->stage_id }}</span>
                    </div>
                    @endif
                    <div style="display:flex;justify-content:space-between;align-items:center">
                        <span style="font-size:12.5px;color:var(--text-muted)">Dibuat</span>
                        <span style="font-size:13px;color:var(--text-muted)">{{ $gate->created_at->format('d M Y') }}</span>
                    </div>
                </div>
                @if($gate->description)
                <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border)">
                    <div style="font-size:12px;color:var(--text-muted);margin-bottom:6px;font-weight:600;text-transform:uppercase;letter-spacing:.4px">Deskripsi</div>
                    <div style="font-size:13.5px;line-height:1.6;color:var(--text-muted)">{{ $gate->description }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Stats --}}
        <div class="card">
            <div class="card-body" style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div style="text-align:center">
                    <div style="font-family:'Space Grotesk',sans-serif;font-size:28px;font-weight:700;color:var(--accent-2)">{{ $gate->staffAssignments->count() }}</div>
                    <div style="font-size:12px;color:var(--text-muted)">Total Staff</div>
                </div>
                <div style="text-align:center">
                    <div style="font-family:'Space Grotesk',sans-serif;font-size:28px;font-weight:700;color:var(--success)">{{ $gate->checkIns->count() }}</div>
                    <div style="font-size:12px;color:var(--text-muted)">Check-in Hari Ini</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail kanan --}}
    <div style="display:flex;flex-direction:column;gap:16px">
        {{-- Staff Assignments --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Staff Bertugas</div>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Staff</th>
                            <th>Shift</th>
                            <th>Jam</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gate->staffAssignments as $sa)
                        <tr>
                            <td>{{ $sa->staff->name ?? '-' }}</td>
                            <td><span class="badge badge-info">{{ ucfirst($sa->shift) }}</span></td>
                            <td class="td-muted">{{ $sa->shift_start }} – {{ $sa->shift_end }}</td>
                            <td>
                                @if($sa->status === 'active') <span class="badge badge-success">Aktif</span>
                                @elseif($sa->status === 'completed') <span class="badge badge-neutral">Selesai</span>
                                @elseif($sa->status === 'absent') <span class="badge badge-danger">Absen</span>
                                @else <span class="badge badge-warning">Terjadwal</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4"><div class="empty-state" style="padding:24px"><div class="empty-state-icon">👤</div><div class="empty-state-text">Belum ada staff ditugaskan</div></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Check-ins --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Check-in Terbaru Hari Ini</div>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Kode Booking</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gate->checkIns as $ci)
                        <tr>
                            <td><code style="font-size:12px;background:var(--surface-2);padding:2px 7px;border-radius:4px">{{ $ci->booking_code }}</code></td>
                            <td class="td-muted">{{ $ci->method === 'qr_scan' ? '📷 QR Scan' : '⌨ Manual' }}</td>
                            <td>
                                @if($ci->status === 'success') <span class="badge badge-success">Berhasil</span>
                                @elseif($ci->status === 'failed') <span class="badge badge-danger">Gagal</span>
                                @else <span class="badge badge-warning">Duplikat</span>
                                @endif
                            </td>
                            <td class="td-muted">{{ $ci->checked_at?->format('H:i') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4"><div class="empty-state" style="padding:24px"><div class="empty-state-icon">📭</div><div class="empty-state-text">Belum ada check-in hari ini</div></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal-backdrop" id="modal-edit">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Edit Gate</div>
            <button class="modal-close" onclick="closeModal('modal-edit')">✕</button>
        </div>
        <form method="POST" action="{{ route('gates.update', $gate) }}">
            @csrf @method('PUT')
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kode Gate</label>
                    <input type="text" name="code" class="form-control" value="{{ $gate->code }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Gate</label>
                    <input type="text" name="name" class="form-control" value="{{ $gate->name }}" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tipe</label>
                    <select name="type" class="form-control">
                        @foreach(['main','concert','exhibition','emergency'] as $t)
                        <option value="{{ $t }}" {{ $gate->type === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        @foreach(['active','inactive','maintenance'] as $s)
                        <option value="{{ $s }}" {{ $gate->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="2">{{ $gate->description }}</textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal('modal-edit')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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