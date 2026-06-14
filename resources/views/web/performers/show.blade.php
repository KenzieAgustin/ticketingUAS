@extends('web.layouts.app')
@section('content')
<div class="page-header">
    <h4><i class="bi bi-person-circle"></i> Detail Performer</h4>
    <a href="{{ route('web.performers.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>
<div class="card shadow-sm" style="max-width:600px">
    <div class="card-body">
        <table class="table table-borderless">
            <tr><th width="150">Nama</th><td>{{ $performer->name }}</td></tr>
            <tr><th>Genre</th><td>{{ $performer->genre ?? '-' }}</td></tr>
            <tr><th>Event</th><td>{{ $performer->event->name ?? '-' }}</td></tr>
            <tr><th>Bio</th><td>{{ $performer->bio ?? '-' }}</td></tr>
        </table>
        <a href="{{ route('web.performers.edit', $performer->id) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
    </div>
</div>
@endsection