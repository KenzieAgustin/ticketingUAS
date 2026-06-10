@extends('layouts.app')

@section('title', 'Gate')

@section('topbar-actions')
    <button class="btn btn-primary" onclick="openModal('modal-create')">＋ Tambah Gate</button>
@endsection

@section('content')

{{-- Filter bar --}}
<div class="filter-bar">
    <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap">
        <select name="type" class="form-control" onchange="this.form.submit()">
            <option value="">Semua Tipe</option>
            <option value="main"       {{ request('type') == 'main'       ? 'selected' : '' }}>Main</option>
            <option value="concert"    {{ request('type') == 'concert'    ? 'selected' : '' }}>Concert</option>
            <option value="exhibition" {{ request('type') == 'exhibition' ? 'selected' : '' }}>Exhibition</option>
            <option value="emergency"  {{ request('type') == 'emergency'  ? 'selected' : '' }}>Emergency</option>
        </select>
        <select name="status" class="form-control" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="active"      {{ request('status') == 'active'      ? 'selected' : '' }}>Aktif</option>
            <option value="inactive"    {{ request('status') == 'inactive'    ? 'selected' : '' }}>Nonaktif</option>
            <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
        </select>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Daftar Gate <span style="font-weight:400;color:var(--text-muted);font-size:13px">({{ $gates->total() ?? count($gates) }} gate)</span></div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Gate</th>
                    <th>Tipe</th>
                    <th>Status</th>
                    <th>Staff</th>
                    <th>Check-in</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gates as $gate)
                <tr>
                    <td><code style="font-size:12px;background:var(--surface-2);padding:2px 8px;border-radius:4px;letter-spacing:.5px">{{ $gate->code }}</code></td>
                    <td>
                        <div style="font-weight:500">{{ $gate->name }}</div>
                        @if($gate->description)
                        <div class="td-muted" style="margin-top:2px">{{ Str::limit($gate->description, 50) }}</div>
                        @endif
                    </td>
                    <td>
                        @php $typeColors = ['main'=>'badge-info','concert'=>'badge-warning','exhibition'=>'badge-success','emergency'=>'badge-danger'] @endphp
                        <span class="badge {{ $typeColors[$gate->type] ?? 'badge-neutral' }}">{{ ucfirst($gate->type) }}</span>
                    </td>
                    <td>
                        @if($gate->status === 'active')
                            <span class="badge badge-success">● Aktif</span>
                        @elseif($gate->status === 'maintenance')
                            <span class="badge badge-warning">⚠ Maintenance</span>
                        @else
                            <span class="badge badge-neutral">○ Nonaktif</span>
                        @endif
                    </td>
                    <td class="td-muted">{{ $gate->staff_assignments_count ?? 0 }}</td>
                    <td class="td-muted">{{ $gate->check_ins_count ?? 0 }}</td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('gates.show', $gate) }}" class="btn btn-ghost btn-sm">Detail</a>
                            <button class="btn btn-ghost btn-sm" onclick="openEdit({{ $gate->toJson() }})">Edit</button>
                            <form method="POST" action="{{ route('gates.destroy', $gate) }}" onsubmit="return confirm('Hapus gate ini?')">
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
                            <div class="empty-state-icon">🚪</div>
                            <div class="empty-state-text">Belum ada gate. Tambahkan gate pertama Anda.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(method_exists($gates, 'links'))
    <div class="pagination">{{ $gates->links() }}</div>
    @endif
</div>

{{-- Modal Create --}}
<div class="modal-backdrop" id="modal-create">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Tambah Gate Baru</div>
            <button class="modal-close" onclick="closeModal('modal-create')">✕</button>
        </div>
        <form method="POST" action="{{ route('gates.store') }}">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kode Gate</label>
                    <input type="text" name="code" class="form-control" placeholder="G-001" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Gate</label>
                    <input type="text" name="name" class="form-control" placeholder="Gate Utama Barat" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tipe</label>
                    <select name="type" class="form-control" required>
                        <option value="main">Main</option>
                        <option value="concert">Concert</option>
                        <option value="exhibition">Exhibition</option>
                        <option value="emergency">Emergency</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="active">Aktif</option>
                        <option value="inactive">Nonaktif</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="2" placeholder="Deskripsi gate (opsional)"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal('modal-create')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Gate</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal-backdrop" id="modal-edit">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Edit Gate</div>
            <button class="modal-close" onclick="closeModal('modal-edit')">✕</button>
        </div>
        <form method="POST" id="form-edit">
            @csrf @method('PUT')
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kode Gate</label>
                    <input type="text" name="code" id="edit-code" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Gate</label>
                    <input type="text" name="name" id="edit-name" class="form-control" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tipe</label>
                    <select name="type" id="edit-type" class="form-control">
                        <option value="main">Main</option>
                        <option value="concert">Concert</option>
                        <option value="exhibition">Exhibition</option>
                        <option value="emergency">Emergency</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" id="edit-status" class="form-control">
                        <option value="active">Aktif</option>
                        <option value="inactive">Nonaktif</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" id="edit-description" class="form-control" rows="2"></textarea>
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

function openEdit(gate) {
    document.getElementById('edit-code').value        = gate.code;
    document.getElementById('edit-name').value        = gate.name;
    document.getElementById('edit-type').value        = gate.type;
    document.getElementById('edit-status').value      = gate.status;
    document.getElementById('edit-description').value = gate.description || '';
    document.getElementById('form-edit').action       = '/gates/' + gate.id;
    openModal('modal-edit');
}
// Close on backdrop click
document.querySelectorAll('.modal-backdrop').forEach(el => {
    el.addEventListener('click', function(e) {
        if (e.target === el) el.classList.remove('open');
    });
});
</script>
@endpush