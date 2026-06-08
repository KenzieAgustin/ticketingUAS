@extends('web.layouts.app')
@section('content')
<div class="page-header">
    <h4><i class="bi bi-pencil"></i> Edit Jadwal</h4>
    <a href="{{ route('web.event-schedules.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>
<div class="card shadow-sm" style="max-width:600px">
    <div class="card-body">
        <form action="{{ route('web.event-schedules.update', $schedule->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-bold">Event</label>
                <select name="event_id" class="form-select">
                    <option value="">-- Pilih Event --</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}" {{ old('event_id', $schedule->event_id) == $event->id ? 'selected' : '' }}>{{ $event->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Tanggal</label>
                <input type="date" name="date" class="form-control" value="{{ old('date', $schedule->date) }}">
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Jam Buka</label>
                    <input type="time" name="open_time" class="form-control" value="{{ old('open_time', $schedule->open_time) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Jam Tutup</label>
                    <input type="time" name="close_time" class="form-control" value="{{ old('close_time', $schedule->close_time) }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Catatan</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $schedule->notes) }}</textarea>
            </div>
            <button type="submit" class="btn btn-warning"><i class="bi bi-save"></i> Update</button>
        </form>
    </div>
</div>
@endsection