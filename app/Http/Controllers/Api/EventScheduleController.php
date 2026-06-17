<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\EventSchedule;
use Illuminate\Http\Request;

class EventScheduleController extends Controller {
    public function index() {
        return response()->json(EventSchedule::with('event')->get());
    }
    public function store(Request $request) {
        $request->validate([
            'event_id'   => 'required|exists:events,id',
            'date'       => 'required|date',
            'open_time'  => 'required',
            'close_time' => 'required',
        ]);
        return response()->json(EventSchedule::create($request->all()), 201);
    }
    public function show($id) {
        return response()->json(EventSchedule::with('event')->findOrFail($id));
    }
    public function update(Request $request, $id) {
        $schedule = EventSchedule::findOrFail($id);
        $schedule->update($request->all());
        return response()->json($schedule);
    }
    public function destroy($id) {
        EventSchedule::findOrFail($id)->delete();
        return response()->json(['message' => 'deleted']);
    }
}