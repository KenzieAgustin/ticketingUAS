<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Stage;
use Illuminate\Http\Request;

class StageController extends Controller {
    public function index() {
        return response()->json(Stage::all());
    }
    public function store(Request $request) {
        $request->validate([
            'name'     => 'required|string',
            'location' => 'required|string',
        ]);
        return response()->json(Stage::create($request->all()), 201);
    }
    public function show($id) {
        return response()->json(Stage::with('events')->findOrFail($id));
    }
    public function update(Request $request, $id) {
        $stage = Stage::findOrFail($id);
        $stage->update($request->all());
        return response()->json($stage);
    }
    public function destroy($id) {
        Stage::findOrFail($id)->delete();
        return response()->json(['message' => 'deleted']);
    }
}