<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\EventMedia;
use App\Models\Event;
use Illuminate\Http\Request;

class EventMediaWebController extends Controller
{
    public function index()
    {
        $medias = EventMedia::with('event')->get();
        return view('web.event-media.index', compact('medias'));
    }

    public function create()
    {
        $events = Event::all();
        return view('web.event-media.create', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'url'      => 'required|string',
            'type'     => 'required|in:photo,poster',
        ]);
        EventMedia::create($request->all());
        return redirect()->route('web.event-media.index')->with('success', 'Media berhasil ditambahkan!');
    }

    public function show($id)
    {
        $media = EventMedia::with('event')->findOrFail($id);
        return view('web.event-media.show', compact('media'));
    }

    public function edit($id)
    {
        $media = EventMedia::findOrFail($id);
        $events = Event::all();
        return view('web.event-media.edit', compact('media', 'events'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'url'      => 'required|string',
            'type'     => 'required|in:photo,poster',
        ]);
        $media = EventMedia::findOrFail($id);
        $media->update($request->all());
        return redirect()->route('web.event-media.index')->with('success', 'Media berhasil diupdate!');
    }

    public function destroy($id)
    {
        EventMedia::findOrFail($id)->delete();
        return redirect()->route('web.event-media.index')->with('success', 'Media berhasil dihapus!');
    }
}