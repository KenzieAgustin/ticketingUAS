<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use Illuminate\Http\Request;

class StageWebController extends Controller
{
    public function index()
    {
        $stages = Stage::all();
        return view('web.stages.index', compact('stages'));
    }

    public function create()
    {
        return view('web.stages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string',
            'location'    => 'required|string',
            'capacity'    => 'nullable|integer',
            'description' => 'nullable|string',
        ]);
        Stage::create($request->all());
        return redirect()->route('web.stages.index')->with('success', 'Stage berhasil ditambahkan!');
    }

    public function show($id)
    {
        $stage = Stage::with('events')->findOrFail($id);
        return view('web.stages.show', compact('stage'));
    }

    public function edit($id)
    {
        $stage = Stage::findOrFail($id);
        return view('web.stages.edit', compact('stage'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string',
            'location'    => 'required|string',
            'capacity'    => 'nullable|integer',
            'description' => 'nullable|string',
        ]);
        $stage = Stage::findOrFail($id);
        $stage->update($request->all());
        return redirect()->route('web.stages.index')->with('success', 'Stage berhasil diupdate!');
    }

    public function destroy($id)
    {
        Stage::findOrFail($id)->delete();
        return redirect()->route('web.stages.index')->with('success', 'Stage berhasil dihapus!');
    }
}