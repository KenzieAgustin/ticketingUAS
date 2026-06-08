<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Performer;
use App\Models\Event;
use Illuminate\Http\Request;

class PerformerWebController extends Controller
{
    public function index()
    {
        $performers = Performer::with('event')->get();
        return view('web.performers.index', compact('performers'));
    }

    public function create()
    {
        $events = Event::all();
        return view('web.performers.create', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'event_id' => 'required|exists:events,id',
            'bio'      => 'nullable|string',
            'photo'    => 'nullable|string',
            'genre'    => 'nullable|string',
        ]);
        Performer::create($request->all());
        return redirect()->route('web.performers.index')->with('success', 'Performer berhasil ditambahkan!');
    }

    public function show($id)
    {
        $performer = Performer::with('event')->findOrFail($id);
        return view('web.performers.show', compact('performer'));
    }

    public function edit($id)
    {
        $performer = Performer::findOrFail($id);
        $events = Event::all();
        return view('web.performers.edit', compact('performer', 'events'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required|string',
            'event_id' => 'required|exists:events,id',
            'bio'      => 'nullable|string',
            'photo'    => 'nullable|string',
            'genre'    => 'nullable|string',
        ]);
        $performer = Performer::findOrFail($id);
        $performer->update($request->all());
        return redirect()->route('web.performers.index')->with('success', 'Performer berhasil diupdate!');
    }

    public function destroy($id)
    {
        Performer::findOrFail($id)->delete();
        return redirect()->route('web.performers.index')->with('success', 'Performer berhasil dihapus!');
    }
}