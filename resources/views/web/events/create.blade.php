@extends('web.layouts.app')
@section('content')
<div class="page-header">
    <h4><i class="bi bi-calendar-plus"></i> Tambah Event Baru</h4>
    <a href="{{ route('web.events.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>
<div class="card shadow-sm" style="max-width:700px">
    <div class="card-body">
        <form action="{{ route('web.events.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Event</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="contoh: Konser Dewa 19">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Slug</label>
                <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="contoh: konser-dewa-19">
                <div class="form-text">Huruf kecil, gunakan -, harus unik</div>
                @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tanggal Mulai</label>
                    <input type="date" name="date_start" class="form-control @error('date_start') is-invalid @enderror" value="{{ old('date_start') }}">
                    @error('date_start') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tanggal Selesai</label>
                    <input type="date" name="date_end" class="form-control @error('date_end') is-invalid @enderror" value="{{ old('date_end') }}">
                    @error('date_end') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Kapasitas Total</label>
                <input type="number" name="capacity_total" class="form-control @error('capacity_total') is-invalid @enderror" value="{{ old('capacity_total') }}" placeholder="contoh: 10000">
                @error('capacity_total') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Kategori Event</label>
                    <select name="event_category_id" class="form-select @error('event_category_id') is-invalid @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('event_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('event_category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Stage / Area</label>
                    <select name="stage_id" class="form-select @error('stage_id') is-invalid @enderror">
                        <option value="">-- Pilih Stage --</option>
                        @foreach($stages as $stage)
                            <option value="{{ $stage->id }}" {{ old('stage_id') == $stage->id ? 'selected' : '' }}>{{ $stage->name }}</option>
                        @endforeach
                    </select>
                    @error('stage_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Status</label>
                <select name="status" class="form-select">
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Deskripsi</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
        </form>
    </div>
</div>
@endsection