<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\GateRequest;
use App\Models\Gate;
use Illuminate\Http\Request;

class GateController extends Controller
{
    // GET /gates
    public function index(Request $request)
    {
        $gates = Gate::query()
            ->when($request->type,   fn ($q) => $q->where('type', $request->type))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->withCount(['staffAssignments', 'checkIns'])
            ->orderBy('code')
            ->get();

        return view('gates.index', compact('gates'));
    }

    // POST /gates
    public function store(GateRequest $request)
    {
        Gate::create($request->validated());

        return redirect()->route('gates.index')->with('success', 'Gate berhasil dibuat.');
    }

    // GET /gates/{gate}
    public function show(Gate $gate)
    {
        $gate->load(['staffAssignments.staff', 'checkIns' => fn ($q) => $q->latest()]);

        return view('gates.show', compact('gate'));
    }

    // PUT /gates/{gate}
    public function update(GateRequest $request, Gate $gate)
    {
        $gate->update($request->validated());

        return redirect()->route('gates.index')->with('success', 'Gate berhasil diperbarui.');
    }

    // DELETE /gates/{gate}
    public function destroy(Gate $gate)
    {
        $gate->delete();

        return redirect()->route('gates.index')->with('success', 'Gate berhasil dihapus.');
    }
}