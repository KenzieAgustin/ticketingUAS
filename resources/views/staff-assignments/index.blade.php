@extends('layouts.app')

@section('title', 'Jadwal Staff')

@section('topbar-actions')
    <button class="btn btn-primary" onclick="openModal('modal-create')">＋ Tambah Jadwal</button>
@endsection

@section('content')

<div class="filter-bar">
    <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap">
        <input type="date" name="assignment_date" class="form-control" value="{{ request('assignment_date') }}" onchange="this.form.submit()">
        <select name="shift" class="form-control" onchange="this.form.submit()">
            <option value="">Semua Shift</option>
            @foreach(['morning','afternoon','evening','full_day'] as $s)
            <option value="{{ $s }}" {{ request('shift') == $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
            @endforeach
        </select>
        <select name="status" class="form-control" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            @foreach(['scheduled','active','completed','absent'] as $s)
            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        @if(request()->anyFilled(['assignment_date','shift','status']))
            <a href="{{ route('staff-assignments.index') }}" class="btn btn-ghost">Reset</a>
        @endif
    </form>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Jadwal Staff</div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Staff</th>
                    <th>Gate</th>
                    <th>Tanggal</th>
                    <th>Shift</th>
                    <th>Jam Tugas</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $a)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:9px">
                            <div style="width:30px;height:30px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0">
                                {{ strtoupper(substr($a->staff->name ?? 'S', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:500">{{ $a->staff->name ?? 'Unknown' }}</div>
                                <div class="td-muted">{{ $a->staff->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:500">{{ $a->gate->name ?? '-' }}</div>
                        <div class="td-muted">{{ $a->gate->code ?? '' }}</div>
                    </td>
                    <td class="td-muted">{{ \Carbon\Carbon::parse($a->assignment_date)->format('d M Y') }}</td>
                    <td>
                        @php $shiftColors = ['morning'=>'badge-warning','afternoon'=>'badge-info','evening'=>'badge-neutral','full_day'=>'badge-success'] @endphp
                        <span class="badge {{ $shiftColors[$a->shift] ?? 'badge-neutral' }}">{{ ucfirst(str_replace('_',' ',$a->shift)) }}</span>
                    </td>
                    <td class="td-muted" style="font-size:13px">{{ $a->shift_start }} – {{ $a->shift_end }}</td>
                    <td>
                        @if($a->status === 'active') <span class="badge badge-success">Aktif</span>
                        @elseif($a->status === 'completed') <span class="badge badge-neutral">Selesai</span>
                        @elseif($a->status === 'absent') <span class="badge badge-danger">Absen</span>
                        @else <span class="badge badge-warning">Terjadwal</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <button class="btn btn-ghost btn-sm" onclick="openStatus({{ $a->id }}, '{{ $a->status }}')">Update Status</button>
                            <form method="POST" action="{{ route('staff-assignments.destroy', $a) }}" onsubmit="return confirm('Hapus jadwal ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <div class="empty-state-icon">👥</div>
                            <div class="empty-state-text">Belum ada jadwal staff. Tambahkan jadwal baru.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(method_exists($assignments, 'links'))
    <div class="pagination">{{ $assignments->links() }}</div>
    @endif
</div>

{{-- Modal Create --}}
<div class="modal-backdrop" id="modal-create">
    <div class="modal" style="max-width:560px">
        <div class="modal-header">
            <div class="modal-title">Tambah Jadwal Staff</div>
            <button class="modal-close" onclick="closeModal('modal-create')">✕</button>
        </div>
        <form method="POST" action="{{ route('staff-assignments.store') }}">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Staff (User ID)</label>
                    <input type="number" name="user_id" class="form-control" placeholder="ID User" required>
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
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Event ID</label>
                    <input type="number" name="event_id" class="form-control" placeholder="ID Event" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="assignment_date" class="form-control" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Shift</label>
                    <select name="shift" class="form-control" required>
                        <option value="morning">Morning</option>
                        <option value="afternoon">Afternoon</option>
                        <option value="evening">Evening</option>
                        <option value="full_day">Full Day</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="scheduled">Scheduled</option>
                        <option value="active">Active</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Jam Mulai</label>
                    <input type="time" name="shift_start" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Jam Selesai</label>
                    <input type="time" name="shift_end" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Catatan</label>
                <textarea name="notes" class="form-control" rows="2" placeholder="Catatan opsional"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal('modal-create')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Update Status --}}
<div class="modal-backdrop" id="modal-status">
    <div class="modal" style="max-width:380px">
        <div class="modal-header">
            <div class="modal-title">Update Status Kehadiran</div>
            <button class="modal-close" onclick="closeModal('modal-status')">✕</button>
        </div>
        <form method="POST" id="form-status">
            @csrf @method('PATCH')
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" id="status-select" class="form-control">
                    <option value="scheduled">Scheduled</option>
                    <option value="active">Active</option>
                    <option value="completed">Completed</option>
                    <option value="absent">Absent</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Catatan</label>
                <textarea name="notes" class="form-control" rows="2" placeholder="Catatan (opsional)"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal('modal-status')">Batal</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
function openStatus(id, currentStatus) {
    document.getElementById('form-status').action = '/staff-assignments/' + id + '/status';
    document.getElementById('status-select').value = currentStatus;
    openModal('modal-status');
}
document.querySelectorAll('.modal-backdrop').forEach(el => {
    el.addEventListener('click', e => { if (e.target === el) el.classList.remove('open'); });
});
</script>
@endpush