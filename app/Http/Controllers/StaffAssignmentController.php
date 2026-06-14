<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StaffAssignmentRequest;
use App\Models\Gate;
use App\Models\StaffAssignment;
use Illuminate\Http\Request;

class StaffAssignmentController extends Controller
{
    // GET /staff-assignments
    public function index(Request $request)
    {
        $assignments = StaffAssignment::with(['staff', 'gate'])
            ->when($request->gate_id,         fn ($q) => $q->where('gate_id', $request->gate_id))
            ->when($request->assignment_date, fn ($q) => $q->whereDate('assignment_date', $request->assignment_date))
            ->when($request->shift,           fn ($q) => $q->where('shift', $request->shift))
            ->when($request->status,          fn ($q) => $q->where('status', $request->status))
            ->orderBy('assignment_date')
            ->orderBy('shift_start')
            ->paginate(30);

        $gates = Gate::orderBy('name')->get();

        return view('staff-assignments.index', compact('assignments', 'gates'));
    }

    // POST /staff-assignments
    public function store(StaffAssignmentRequest $request)
    {
        StaffAssignment::create($request->validated());

        return redirect()->route('staff-assignments.index')->with('success', 'Jadwal staff berhasil dibuat.');
    }

    // DELETE /staff-assignments/{assignment}
    public function destroy(StaffAssignment $staffAssignment)
    {
        $staffAssignment->delete();

        return redirect()->route('staff-assignments.index')->with('success', 'Jadwal berhasil dihapus.');
    }

    // PATCH /staff-assignments/{assignment}/status
    public function updateStatus(Request $request, StaffAssignment $staffAssignment)
    {
        $request->validate([
            'status' => 'required|in:scheduled,active,completed,absent',
            'notes'  => 'nullable|string|max:500',
        ]);

        $staffAssignment->update([
            'status' => $request->status,
            'notes'  => $request->notes ?? $staffAssignment->notes,
        ]);

        return redirect()->route('staff-assignments.index')->with('success', 'Status kehadiran diperbarui.');
    }
}