@extends('web.layouts.app')
@section('content')
<div class="page-header">
    <h4><i class="bi bi-pencil"></i> Edit Stage</h4>
    <a href="{{ route('web.stages.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>
<div class="card shadow-sm" style="max-width:600px">
    <div class="card-body">
        <form action="{{ route('web.stages.update', $stage->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Stage</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $stage->name) }}">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Lokasi</label>
                <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', $stage->location) }}">
                @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Kapasitas</label>
                <input type="number" name="capacity" class="form-control" value="{{ old('capacity', $stage->capacity) }}">
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $stage->description) }}</textarea>
            </div>
            <button type="submit" class="btn btn-warning"><i class="bi bi-save"></i> Update</button>
        </form>
    </div>
</div>
@endsection@extends('web.layouts.app')
@section('content')
<div class="page-header">
    <h4><i class="bi bi-pencil"></i> Edit Stage</h4>
    <a href="{{ route('web.stages.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>
<div class="card shadow-sm" style="max-width:600px">
    <div class="card-body">
        <form action="{{ route('web.stages.update', $stage->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Stage</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $stage->name) }}">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Lokasi</label>
                <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', $stage->location) }}">
                @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Kapasitas</label>
                <input type="number" name="capacity" class="form-control" value="{{ old('capacity', $stage->capacity) }}">
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $stage->description) }}</textarea>
            </div>
            <button type="submit" class="btn btn-warning"><i class="bi bi-save"></i> Update</button>
        </form>
    </div>
</div>
@endsection