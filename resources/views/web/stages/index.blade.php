@extends('web.layouts.app')
@section('content')
<div class="page-header">
    <h4><i class="bi bi-geo-alt"></i> Stage / Area</h4>
    @if(auth()->check() && auth()->user()->isAdmin())
        <a href="{{ route('web.stages.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Stage</a>
    @endif
</div>
<div class="card shadow-sm">
    <div class="card-body">
        @if($stages->isEmpty())
            <p class="text-muted text-center py-4">Belum ada data stage.</p>
        @else
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th><th>Nama Stage</th><th>Lokasi</th><th>Kapasitas</th>
                    @if(auth()->check() && auth()->user()->isAdmin()) <th>Aksi</th> @endif
                </tr>
            </thead>
            <tbody>
                @foreach($stages as $i => $stage)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $stage->name }}</td>
                    <td>{{ $stage->location }}</td>
                    <td>{{ number_format($stage->capacity) }}</td>
                    @if(auth()->check() && auth()->user()->isAdmin())
                    <td>
                        <a href="{{ route('web.stages.show', $stage->id) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('web.stages.edit', $stage->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('web.stages.destroy', $stage->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection