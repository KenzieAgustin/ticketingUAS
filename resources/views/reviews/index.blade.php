@extends('layouts.app')

@section('title', 'Review')

@section('content')

{{-- Stats --}}
<div class="stats-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:20px">
    <div class="stat-card">
        <div class="stat-label">Total Review</div>
        <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Pending</div>
        <div class="stat-value" style="color:var(--warning)">{{ $stats['pending'] ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Disetujui</div>
        <div class="stat-value" style="color:var(--success)">{{ $stats['approved'] ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Ditolak</div>
        <div class="stat-value" style="color:var(--danger)">{{ $stats['rejected'] ?? 0 }}</div>
    </div>
</div>

{{-- Filter --}}
<div class="filter-bar">
    <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap">
        <select name="status" class="form-control" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="pending"  {{ request('status') == 'pending'  ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
        </select>
        <select name="rating" class="form-control" onchange="this.form.submit()">
            <option value="">Semua Rating</option>
            @for($i = 5; $i >= 1; $i--)
            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
            @endfor
        </select>
        @if(request()->anyFilled(['status','rating']))
            <a href="{{ route('reviews.index') }}" class="btn btn-ghost">Reset</a>
        @endif
    </form>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Daftar Review</div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Pengguna</th>
                    <th>Event</th>
                    <th>Rating</th>
                    <th>Ulasan</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                <tr>
                    <td>
                        <div style="font-weight:500">{{ $review->user->name ?? 'Unknown' }}</div>
                        <div class="td-muted">{{ $review->user->email ?? '' }}</div>
                    </td>
                    <td class="td-muted">Event #{{ $review->event_id }}</td>
                    <td>
                        <div class="stars">
                            @for($i = 1; $i <= 5; $i++){{ $i <= $review->rating ? '★' : '☆' }}@endfor
                        </div>
                        <div style="font-size:11px;color:var(--text-muted)">{{ $review->rating }}/5</div>
                    </td>
                    <td style="max-width:220px">
                        @if($review->title)
                        <div style="font-weight:500;font-size:13px">{{ $review->title }}</div>
                        @endif
                        <div class="td-muted" style="font-size:12.5px">{{ Str::limit($review->body, 70) }}</div>
                    </td>
                    <td>
                        @if($review->status === 'approved') <span class="badge badge-success">Disetujui</span>
                        @elseif($review->status === 'rejected') <span class="badge badge-danger">Ditolak</span>
                        @else <span class="badge badge-warning">Pending</span>
                        @endif
                    </td>
                    <td class="td-muted">{{ $review->created_at->format('d M Y') }}</td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                            <button class="btn btn-ghost btn-sm" onclick="openDetail({{ $review->toJson() }})">Detail</button>
                            @if($review->status === 'pending')
                            <form method="POST" action="{{ route('reviews.approve', $review) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                            </form>
                            <button class="btn btn-danger btn-sm" onclick="openReject({{ $review->id }})">Tolak</button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <div class="empty-state-icon">⭐</div>
                            <div class="empty-state-text">Belum ada review.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(method_exists($reviews, 'links'))
    <div class="pagination">{{ $reviews->links() }}</div>
    @endif
</div>

{{-- Modal Detail --}}
<div class="modal-backdrop" id="modal-detail">
    <div class="modal" style="max-width:480px">
        <div class="modal-header">
            <div class="modal-title">Detail Review</div>
            <button class="modal-close" onclick="closeModal('modal-detail')">✕</button>
        </div>
        <div id="detail-content"></div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeModal('modal-detail')">Tutup</button>
        </div>
    </div>
</div>

{{-- Modal Reject --}}
<div class="modal-backdrop" id="modal-reject">
    <div class="modal" style="max-width:400px">
        <div class="modal-header">
            <div class="modal-title">Tolak Review</div>
            <button class="modal-close" onclick="closeModal('modal-reject')">✕</button>
        </div>
        <form method="POST" id="form-reject">
            @csrf @method('PATCH')
            <div class="form-group">
                <label class="form-label">Alasan Penolakan</label>
                <textarea name="reason" class="form-control" rows="3" placeholder="Jelaskan alasan penolakan..." required></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal('modal-reject')">Batal</button>
                <button type="submit" class="btn btn-danger">Tolak Review</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

function openDetail(r) {
    const stars = '★'.repeat(r.rating) + '☆'.repeat(5 - r.rating);
    document.getElementById('detail-content').innerHTML = `
        <div style="display:flex;flex-direction:column;gap:14px">
            <div style="display:flex;justify-content:space-between">
                <div>
                    <div style="font-weight:600">${r.user ? r.user.name : 'Unknown'}</div>
                    <div style="font-size:12px;color:var(--text-muted)">Event #${r.event_id}</div>
                </div>
                <div style="color:#f59e0b;font-size:18px">${stars}</div>
            </div>
            ${r.title ? `<div style="font-weight:600;font-size:15px">${r.title}</div>` : ''}
            <div style="font-size:14px;line-height:1.7;color:var(--text-muted)">${r.body}</div>
            ${r.rejected_reason ? `<div style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);border-radius:8px;padding:12px;font-size:13px;color:#f87171"><strong>Alasan Ditolak:</strong> ${r.rejected_reason}</div>` : ''}
            <div style="font-size:12px;color:var(--text-muted)">${new Date(r.created_at).toLocaleDateString('id-ID', {day:'numeric',month:'long',year:'numeric'})}</div>
        </div>
    `;
    openModal('modal-detail');
}

function openReject(id) {
    document.getElementById('form-reject').action = '/reviews/' + id + '/reject';
    openModal('modal-reject');
}

document.querySelectorAll('.modal-backdrop').forEach(el => {
    el.addEventListener('click', e => { if (e.target === el) el.classList.remove('open'); });
});
</script>
@endpush