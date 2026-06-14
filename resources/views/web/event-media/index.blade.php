@extends('web.layouts.app')
@section('content')
<div class="page-header">
    <h4><i class="bi bi-image"></i> Media Event</h4>
    @if(auth()->check() && auth()->user()->isAdmin())
        <a href="{{ route('web.event-media.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Media</a>
    @endif
</div>
<div class="card shadow-sm">
    <div class="card-body">
        @if($medias->isEmpty())
            <p class="text-muted text-center py-4">Belum ada media.</p>
        @else
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th><th>Event</th><th>Tipe</th><th>URL</th>
                    @if(auth()->check() && auth()->user()->isAdmin()) <th>Aksi</th> @endif
                </tr>
            </thead>
            <tbody>
                @foreach($medias as $i => $media)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $media->event->name ?? '-' }}</td>
                    <td><span class="badge bg-{{ $media->type == 'poster' ? 'warning text-dark' : 'info' }}">{{ $media->type }}</span></td>
                    <td><a href="{{ $media->url }}" target="_blank">{{ Str::limit($media->url, 40) }}</a></td>
                    @if(auth()->check() && auth()->user()->isAdmin())
                    <td>
                        <a href="{{ route('web.event-media.show', $media->id) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('web.event-media.edit', $media->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('web.event-media.destroy', $media->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection@extends('web.layouts.app')
@section('content')
<div class="page-header">
    <h4><i class="bi bi-image"></i> Media Event</h4>
    @if(auth()->check() && auth()->user()->isAdmin())
        <a href="{{ route('web.event-media.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Media</a>
    @endif
</div>
<div class="card shadow-sm">
    <div class="card-body">
        @if($medias->isEmpty())
            <p class="text-muted text-center py-4">Belum ada media.</p>
        @else
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th><th>Event</th><th>Tipe</th><th>URL</th>
                    @if(auth()->check() && auth()->user()->isAdmin()) <th>Aksi</th> @endif
                </tr>
            </thead>
            <tbody>
                @foreach($medias as $i => $media)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $media->event->name ?? '-' }}</td>
                    <td><span class="badge bg-{{ $media->type == 'poster' ? 'warning text-dark' : 'info' }}">{{ $media->type }}</span></td>
                    <td><a href="{{ $media->url }}" target="_blank">{{ Str::limit($media->url, 40) }}</a></td>
                    @if(auth()->check() && auth()->user()->isAdmin())
                    <td>
                        <a href="{{ route('web.event-media.show', $media->id) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('web.event-media.edit', $media->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('web.event-media.destroy', $media->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection@extends('web.layouts.app')
@section('content')
<div class="page-header">
    <h4><i class="bi bi-image"></i> Media Event</h4>
    @if(auth()->check() && auth()->user()->isAdmin())
        <a href="{{ route('web.event-media.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Media</a>
    @endif
</div>
<div class="card shadow-sm">
    <div class="card-body">
        @if($medias->isEmpty())
            <p class="text-muted text-center py-4">Belum ada media.</p>
        @else
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th><th>Event</th><th>Tipe</th><th>URL</th>
                    @if(auth()->check() && auth()->user()->isAdmin()) <th>Aksi</th> @endif
                </tr>
            </thead>
            <tbody>
                @foreach($medias as $i => $media)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $media->event->name ?? '-' }}</td>
                    <td><span class="badge bg-{{ $media->type == 'poster' ? 'warning text-dark' : 'info' }}">{{ $media->type }}</span></td>
                    <td><a href="{{ $media->url }}" target="_blank">{{ Str::limit($media->url, 40) }}</a></td>
                    @if(auth()->check() && auth()->user()->isAdmin())
                    <td>
                        <a href="{{ route('web.event-media.show', $media->id) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('web.event-media.edit', $media->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('web.event-media.destroy', $media->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection