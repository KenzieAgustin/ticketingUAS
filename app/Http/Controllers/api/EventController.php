<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller {
    public function index() {
        return response()->json(Event::with(['category','stage','performers'])->get());
    }
    public function store(Request $request) {
        $request->validate([
            'name'              => 'required|string',
            'date_start'        => 'required|date',
            'date_end'          => 'required|date',
            'capacity_total'    => 'required|integer',
            'event_category_id' => 'required|exists:event_categories,id',
            'stage_id'          => 'required|exists:stages,id',
            'status'            => 'required|in:active,inactive',
            'slug'              => 'required|unique:events',
        ]);
        return response()->json(Event::create($request->all()), 201);
    }
    public function show($id) {
        return response()->json(
            Event::with(['category','stage','performers','schedules','media'])->findOrFail($id)
        );
    }
    public function update(Request $request, $id) {
        $event = Event::findOrFail($id);
        $event->update($request->all());
        return response()->json($event);
    }
    public function destroy($id) {
        Event::findOrFail($id)->delete();
        return response()->json(['message' => 'deleted']);
    }
}