@extends('web.layouts.app')
@section('content')
<div class="page-header">
    <h4><i class="bi bi-clock"></i> Jadwal Event</h4>
    <a href="{{ route('web.event-schedules.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Jadwal</a>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        @if($schedules->isEmpty())
            <p class="text-muted text-center py-4">Belum ada jadwal.</p>
        @else
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr><th>#</th><th>Event</th><th>Tanggal</th><th>Buka</th><th>Tutup</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @foreach($schedules as $i => $schedule)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $schedule->event->name ?? '-' }}</td>
                    <td>{{ $schedule->date }}</td>
                    <td>{{ $schedule->open_time }}</td>
                    <td>{{ $schedule->close_time }}</td>
                    <td>
                        <a href="{{ route('web.event-schedules.show', $schedule->id) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('web.event-schedules.edit', $schedule->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('web.event-schedules.destroy', $schedule->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
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