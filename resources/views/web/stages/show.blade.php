@extends('web.layouts.app')
@section('content')
<div class="page-header">
    <h4><i class="bi bi-geo-alt"></i> Detail Stage</h4>
    <a href="{{ route('web.stages.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>
<div class="card shadow-sm mb-4" style="max-width:600px">
    <div class="card-body">
        <table class="table table-borderless">
            <tr><th width="150">Nama Stage</th><td>{{ $stage->name }}</td></tr>
            <tr><th>Lokasi</th><td>{{ $stage->location }}</td></tr>
            <tr><th>Kapasitas</th><td>{{ number_format($stage->capacity) }} orang</td></tr>
            <tr><th>Deskripsi</th><td>{{ $stage->description ?? '-' }}</td></tr>
        </table>
        <a href="{{ route('web.stages.edit', $stage->id) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
    </div>
</div>
@endsection