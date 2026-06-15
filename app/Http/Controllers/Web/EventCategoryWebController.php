<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\EventCategory;
use Illuminate\Http\Request;

class EventCategoryWebController extends Controller
{
    public function index()
    {
        $categories = EventCategory::all();
        return view('web.event-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('web.event-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string',
            'slug'        => 'required|string|unique:event_categories',
            'description' => 'nullable|string',
        ]);
        EventCategory::create($request->all());
        return redirect()->route('web.event-categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function show($id)
    {
        $category = EventCategory::with('events')->findOrFail($id);
        return view('web.event-categories.show', compact('category'));
    }

    public function edit($id)
    {
        $category = EventCategory::findOrFail($id);
        return view('web.event-categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string',
            'slug'        => 'required|string|unique:event_categories,slug,' . $id,
            'description' => 'nullable|string',
        ]);
        $category = EventCategory::findOrFail($id);
        $category->update($request->all());
        return redirect()->route('web.event-categories.index')->with('success', 'Kategori berhasil diupdate!');
    }

    public function destroy($id)
    {
        EventCategory::findOrFail($id)->delete();
        return redirect()->route('web.event-categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}