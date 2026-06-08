@extends('web.layouts.app')
@section('content')
<div class="page-header">
    <h4><i class="bi bi-clock"></i> Detail Jadwal</h4>
    <a href="{{ route('web.event-schedules.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>
<div class="card shadow-sm" style="max-width:600px">
    <div class="card-body">
        <table class="table table-borderless">
            <tr><th width="150">Event</th><td>{{ $schedule->event->name ?? '-' }}</td></tr>
            <tr><th>Tanggal</th><td>{{ $schedule->date }}</td></tr>
            <tr><th>Jam Buka</th><td>{{ $schedule->open_time }}</td></tr>
            <tr><th>Jam Tutup</th><td>{{ $schedule->close_time }}</td></tr>
            <tr><th>Catatan</th><td>{{ $schedule->notes ?? '-' }}</td></tr>
        </table>
        <a href="{{ route('web.event-schedules.edit', $schedule->id) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
    </div>
</div>
@endsection