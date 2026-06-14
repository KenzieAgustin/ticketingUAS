<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\EventMedia;
use Illuminate\Http\Request;

class EventMediaController extends Controller {
    public function index() {
        return response()->json(EventMedia::with('event')->get());
    }
    public function store(Request $request) {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'url'      => 'required|string',
            'type'     => 'required|in:photo,poster',
        ]);
        return response()->json(EventMedia::create($request->all()), 201);
    }
    public function show($id) {
        return response()->json(EventMedia::with('event')->findOrFail($id));
    }
    public function update(Request $request, $id) {
        $media = EventMedia::findOrFail($id);
        $media->update($request->all());
        return response()->json($media);
    }
    public function destroy($id) {
        EventMedia::findOrFail($id)->delete();
        return response()->json(['message' => 'deleted']);
    }
}