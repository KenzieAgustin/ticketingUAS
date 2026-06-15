<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Performer;
use Illuminate\Http\Request;

class PerformerController extends Controller {
    public function index() {
        return response()->json(Performer::with('event')->get());
    }
    public function store(Request $request) {
        $request->validate([
            'name'     => 'required|string',
            'event_id' => 'required|exists:events,id',
        ]);
        return response()->json(Performer::create($request->all()), 201);
    }
    public function show($id) {
        return response()->json(Performer::with('event')->findOrFail($id));
    }
    public function update(Request $request, $id) {
        $performer = Performer::findOrFail($id);
        $performer->update($request->all());
        return response()->json($performer);
    }
    public function destroy($id) {
        Performer::findOrFail($id)->delete();
        return response()->json(['message' => 'deleted']);
    }
}