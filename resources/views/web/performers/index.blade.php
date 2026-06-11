@extends('web.layouts.app')
@section('content')
<div class="page-header">
    <h4><i class="bi bi-music-note-beamed"></i> Daftar Performer</h4>
    <a href="{{ route('web.performers.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Performer</a>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        @if($performers->isEmpty())
            <p class="text-muted text-center py-4">Belum ada performer.</p>
        @else
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr><th>#</th><th>Nama</th><th>Genre</th><th>Event</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @foreach($performers as $i => $performer)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><strong>{{ $performer->name }}</strong></td>
                    <td>{{ $performer->genre ?? '-' }}</td>
                    <td>{{ $performer->event->name ?? '-' }}</td>
                    <td>
                        <a href="{{ route('web.performers.show', $performer->id) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('web.performers.edit', $performer->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('web.performers.destroy', $performer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
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