@extends('web.layouts.app')
@section('content')
<div class="page-header">
    <h4><i class="bi bi-person-plus"></i> Tambah Performer</h4>
    <a href="{{ route('web.performers.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>
<div class="card shadow-sm" style="max-width:600px">
    <div class="card-body">
        <form action="{{ route('web.performers.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Performer</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="contoh: Dewa 19">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Event</label>
                <select name="event_id" class="form-select @error('event_id') is-invalid @enderror">
                    <option value="">-- Pilih Event --</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>{{ $event->name }}</option>
                    @endforeach
                </select>
                @error('event_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Genre</label>
                <input type="text" name="genre" class="form-control" value="{{ old('genre') }}" placeholder="contoh: Pop, Rock">
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">URL Foto</label>
                <input type="text" name="photo" class="form-control" value="{{ old('photo') }}" placeholder="https://...">
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Bio</label>
                <textarea name="bio" class="form-control" rows="3">{{ old('bio') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
        </form>
    </div>
</div>
@endsection