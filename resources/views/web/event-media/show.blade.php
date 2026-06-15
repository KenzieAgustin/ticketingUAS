@extends('web.layouts.app')
@section('content')
<div class="page-header">
    <h4><i class="bi bi-image"></i> Detail Media</h4>
    <a href="{{ route('web.event-media.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>
<div class="card shadow-sm" style="max-width:600px">
    <div class="card-body">
        <table class="table table-borderless">
            <tr><th width="150">Event</th><td>{{ $media->event->name ?? '-' }}</td></tr>
            <tr><th>Tipe</th><td><span class="badge bg-{{ $media->type == 'poster' ? 'warning text-dark' : 'info' }}">{{ $media->type }}</span></td></tr>
            <tr><th>URL</th><td><a href="{{ $media->url }}" target="_blank">{{ $media->url }}</a></td></tr>
        </table>
        <a href="{{ route('web.event-media.edit', $media->id) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
    </div>
</div>
@endsection@extends('web.layouts.app')
@section('content')
<div class="page-header">
    <h4><i class="bi bi-image"></i> Detail Media</h4>
    <a href="{{ route('web.event-media.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>
<div class="card shadow-sm" style="max-width:600px">
    <div class="card-body">
        <table class="table table-borderless">
            <tr><th width="150">Event</th><td>{{ $media->event->name ?? '-' }}</td></tr>
            <tr><th>Tipe</th><td><span class="badge bg-{{ $media->type == 'poster' ? 'warning text-dark' : 'info' }}">{{ $media->type }}</span></td></tr>
            <tr><th>URL</th><td><a href="{{ $media->url }}" target="_blank">{{ $media->url }}</a></td></tr>
        </table>
        <a href="{{ route('web.event-media.edit', $media->id) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
    </div>
</div>
@endsection