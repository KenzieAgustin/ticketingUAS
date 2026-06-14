@extends('web.layouts.app')
@section('content')
<div class="page-header">
    <h4><i class="bi bi-pencil"></i> Edit Media Event</h4>
    <a href="{{ route('web.event-media.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>
<div class="card shadow-sm" style="max-width:600px">
    <div class="card-body">
        <form action="{{ route('web.event-media.update', $media->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-bold">Event</label>
                <select name="event_id" class="form-select">
                    <option value="">-- Pilih Event --</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}" {{ old('event_id', $media->event_id) == $event->id ? 'selected' : '' }}>{{ $event->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Tipe Media</label>
                <select name="type" class="form-select">
                    <option value="photo" {{ old('type', $media->type) == 'photo' ? 'selected' : '' }}>Photo</option>
                    <option value="poster" {{ old('type', $media->type) == 'poster' ? 'selected' : '' }}>Poster</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">URL Media</label>
                <input type="text" name="url" class="form-control" value="{{ old('url', $media->url) }}">
            </div>
            <button type="submit" class="btn btn-warning"><i class="bi bi-save"></i> Update</button>
        </form>
    </div>
</div>
@endsection