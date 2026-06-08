@extends('web.layouts.app')
@section('content')
<div class="page-header">
    <h4><i class="bi bi-tags"></i> Kategori Event</h4>
    <a href="{{ route('web.event-categories.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Kategori</a>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        @if($categories->isEmpty())
            <p class="text-muted text-center py-4">Belum ada kategori.</p>
        @else
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr><th>#</th><th>Nama</th><th>Slug</th><th>Deskripsi</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @foreach($categories as $i => $category)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $category->name }}</td>
                    <td><span class="badge bg-secondary">{{ $category->slug }}</span></td>
                    <td>{{ Str::limit($category->description, 50) ?? '-' }}</td>
                    <td>
                        <a href="{{ route('web.event-categories.show', $category->id) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('web.event-categories.edit', $category->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('web.event-categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
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