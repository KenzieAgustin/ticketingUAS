@extends('web.layouts.app')
@section('content')
<div class="page-header">
    <h4><i class="bi bi-tags"></i> Detail Kategori</h4>
    <a href="{{ route('web.event-categories.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>
<div class="card shadow-sm" style="max-width:600px">
    <div class="card-body">
        <table class="table table-borderless">
            <tr><th width="150">Nama</th><td>{{ $category->name }}</td></tr>
            <tr><th>Slug</th><td><span class="badge bg-secondary">{{ $category->slug }}</span></td></tr>
            <tr><th>Deskripsi</th><td>{{ $category->description ?? '-' }}</td></tr>
        </table>
        <a href="{{ route('web.event-categories.edit', $category->id) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
    </div>
</div>
@endsection