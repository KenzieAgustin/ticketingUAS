<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\EventCategory;
use Illuminate\Http\Request;

class EventCategoryController extends Controller {
    public function index() {
        return response()->json(EventCategory::all());
    }
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string|unique:event_categories',
        ]);
        return response()->json(EventCategory::create($request->all()), 201);
    }
    public function show($id) {
        return response()->json(EventCategory::with('events')->findOrFail($id));
    }
    public function update(Request $request, $id) {
        $category = EventCategory::findOrFail($id);
        $category->update($request->all());
        return response()->json($category);
    }
    public function destroy($id) {
        EventCategory::findOrFail($id)->delete();
        return response()->json(['message' => 'deleted']);
    }
}