@extends('web.layouts.app')
@section('content')
<div class="page-header">
    <h4><i class="bi bi-calendar-event"></i> Daftar Event</h4>
    <a href="{{ route('web.events.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Event</a>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        @if($events->isEmpty())
            <p class="text-muted text-center py-4">Belum ada event.</p>
        @else
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr><th>#</th><th>Nama Event</th><th>Kategori</th><th>Stage</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @foreach($events as $i => $event)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><strong>{{ $event->name }}</strong></td>
                    <td>{{ $event->category->name ?? '-' }}</td>
                    <td>{{ $event->stage->name ?? '-' }}</td>
                    <td>{{ $event->date_start }} s/d {{ $event->date_end }}</td>
                    <td><span class="badge bg-{{ $event->status == 'active' ? 'success' : 'secondary' }}">{{ $event->status }}</span></td>
                    <td>
                        <a href="{{ route('web.events.show', $event->id) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('web.events.edit', $event->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('web.events.destroy', $event->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection