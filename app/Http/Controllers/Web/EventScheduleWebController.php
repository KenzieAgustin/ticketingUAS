<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\EventSchedule;
use App\Models\Event;
use Illuminate\Http\Request;

class EventScheduleWebController extends Controller
{
    public function index()
    {
        $schedules = EventSchedule::with('event')->get();
        return view('web.event-schedules.index', compact('schedules'));
    }

    public function create()
    {
        $events = Event::all();
        return view('web.event-schedules.create', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_id'   => 'required|exists:events,id',
            'date'       => 'required|date',
            'open_time'  => 'required',
            'close_time' => 'required',
            'notes'      => 'nullable|string',
        ]);
        EventSchedule::create($request->all());
        return redirect()->route('web.event-schedules.index')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function show($id)
    {
        $schedule = EventSchedule::with('event')->findOrFail($id);
        return view('web.event-schedules.show', compact('schedule'));
    }

    public function edit($id)
    {
        $schedule = EventSchedule::findOrFail($id);
        $events = Event::all();
        return view('web.event-schedules.edit', compact('schedule', 'events'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'event_id'   => 'required|exists:events,id',
            'date'       => 'required|date',
            'open_time'  => 'required',
            'close_time' => 'required',
            'notes'      => 'nullable|string',
        ]);
        $schedule = EventSchedule::findOrFail($id);
        $schedule->update($request->all());
        return redirect()->route('web.event-schedules.index')->with('success', 'Jadwal berhasil diupdate!');
    }

    public function destroy($id)
    {
        EventSchedule::findOrFail($id)->delete();
        return redirect()->route('web.event-schedules.index')->with('success', 'Jadwal berhasil dihapus!');
    }
}