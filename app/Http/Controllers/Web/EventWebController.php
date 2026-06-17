<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Stage;
use Illuminate\Http\Request;

class EventWebController extends Controller
{
    public function index()
    {
        $events = Event::with(['category', 'stage'])->get();
        return view('web.events.index', compact('events'));
    }

    public function create()
    {
        $categories = EventCategory::all();
        $stages = Stage::all();
        return view('web.events.create', compact('categories', 'stages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string',
            'date_start'        => 'required|date',
            'date_end'          => 'required|date',
            'capacity_total'    => 'required|integer',
            'event_category_id' => 'required|exists:event_categories,id',
            'stage_id'          => 'required|exists:stages,id',
            'status'            => 'required|in:active,inactive',
            'slug'              => 'required|unique:events',
            'description'       => 'nullable|string',
        ]);
        Event::create($request->all());
        return redirect()->route('web.events.index')->with('success', 'Event berhasil ditambahkan!');
    }

    public function show($id)
    {
        $event = Event::with(['category', 'stage', 'performers', 'schedules', 'media'])->findOrFail($id);
        return view('web.events.show', compact('event'));
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $categories = EventCategory::all();
        $stages = Stage::all();
        return view('web.events.edit', compact('event', 'categories', 'stages'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'              => 'required|string',
            'date_start'        => 'required|date',
            'date_end'          => 'required|date',
            'capacity_total'    => 'required|integer',
            'event_category_id' => 'required|exists:event_categories,id',
            'stage_id'          => 'required|exists:stages,id',
            'status'            => 'required|in:active,inactive',
            'slug'              => 'required|unique:events,slug,' . $id,
            'description'       => 'nullable|string',
        ]);
        $event = Event::findOrFail($id);
        $event->update($request->all());
        return redirect()->route('web.events.index')->with('success', 'Event berhasil diupdate!');
    }

    public function destroy($id)
    {
        Event::findOrFail($id)->delete();
        return redirect()->route('web.events.index')->with('success', 'Event berhasil dihapus!');
    }
}