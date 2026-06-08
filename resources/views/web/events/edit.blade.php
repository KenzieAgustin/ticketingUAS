@extends('web.layouts.app')
@section('content')
<div class="page-header">
    <h4><i class="bi bi-pencil"></i> Edit Event</h4>
    <a href="{{ route('web.events.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>
<div class="card shadow-sm" style="max-width:700px">
    <div class="card-body">
        <form action="{{ route('web.events.update', $event->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Event</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $event->name) }}">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Slug</label>
                <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $event->slug) }}">
                @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tanggal Mulai</label>
                    <input type="date" name="date_start" class="form-control" value="{{ old('date_start', $event->date_start) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tanggal Selesai</label>
                    <input type="date" name="date_end" class="form-control" value="{{ old('date_end', $event->date_end) }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Kapasitas Total</label>
                <input type="number" name="capacity_total" class="form-control" value="{{ old('capacity_total', $event->capacity_total) }}">
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Kategori Event</label>
                    <select name="event_category_id" class="form-select">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('event_category_id', $event->event_category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Stage / Area</label>
                    <select name="stage_id" class="form-select">
                        <option value="">-- Pilih Stage --</option>
                        @foreach($stages as $stage)
                            <option value="{{ $stage->id }}" {{ old('stage_id', $event->stage_id) == $stage->id ? 'selected' : '' }}>{{ $stage->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Status</label>
                <select name="status" class="form-select">
                    <option value="active" {{ old('status', $event->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $event->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Deskripsi</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description', $event->description) }}</textarea>
            </div>
            <button type="submit" class="btn btn-warning"><i class="bi bi-save"></i> Update</button>
        </form>
    </div>
</div>
@endsection