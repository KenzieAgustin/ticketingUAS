@extends('web.layouts.app')
@section('content')
<div class="page-header">
    <h4><i class="bi bi-calendar-event"></i> Detail Event</h4>
    <a href="{{ route('web.events.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5>{{ $event->name }}</h5>
        <p class="text-muted">{{ $event->description ?? 'Tidak ada deskripsi.' }}</p>
        <table class="table table-borderless table-sm">
            <tr><th width="180">Kategori</th><td>{{ $event->category->name ?? '-' }}</td></tr>
            <tr><th>Stage</th><td>{{ $event->stage->name ?? '-' }}</td></tr>
            <tr><th>Tanggal</th><td>{{ $event->date_start }} s/d {{ $event->date_end }}</td></tr>
            <tr><th>Kapasitas</th><td>{{ number_format($event->capacity_total) }} orang</td></tr>
            <tr><th>Status</th><td><span class="badge bg-{{ $event->status == 'active' ? 'success' : 'secondary' }}">{{ $event->status }}</span></td></tr>
        </table>
        <a href="{{ route('web.events.edit', $event->id) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header"><strong><i class="bi bi-music-note-beamed"></i> Performer ({{ $event->performers->count() }})</strong></div>
            <div class="card-body">
                @forelse($event->performers as $p)
                    <div class="mb-2"><strong>{{ $p->name }}</strong> <small class="text-muted">{{ $p->genre ?? '' }}</small></div>
                @empty
                    <p class="text-muted mb-0">Belum ada performer.</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header"><strong><i class="bi bi-clock"></i> Jadwal ({{ $event->schedules->count() }})</strong></div>
            <div class="card-body">
                @forelse($event->schedules as $s)
                    <div class="mb-2 p-2 bg-light rounded">
                        <strong>{{ $s->date }}</strong> | {{ $s->open_time }} - {{ $s->close_time }}
                    </div>
                @empty
                    <p class="text-muted mb-0">Belum ada jadwal.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection