<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StaffAssignmentRequest;
use App\Models\StaffAssignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StaffAssignmentController extends Controller
{
    /**
     * GET /api/staff-assignments
     * List jadwal — bisa filter by event, gate, date, staff.
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'event_id'        => 'nullable|integer',
            'gate_id'         => 'nullable|integer',
            'assignment_date' => 'nullable|date',
            'user_id'         => 'nullable|integer',
            'shift'           => 'nullable|in:morning,afternoon,evening,full_day',
        ]);

        $assignments = StaffAssignment::with(['staff', 'gate'])
            ->when($request->event_id,        fn ($q) => $q->where('event_id', $request->event_id))
            ->when($request->gate_id,         fn ($q) => $q->where('gate_id', $request->gate_id))
            ->when($request->assignment_date, fn ($q) => $q->whereDate('assignment_date', $request->assignment_date))
            ->when($request->user_id,         fn ($q) => $q->where('user_id', $request->user_id))
            ->when($request->shift,           fn ($q) => $q->where('shift', $request->shift))
            ->orderBy('assignment_date')
            ->orderBy('shift_start')
            ->paginate(30);

        return response()->json(['success' => true, 'data' => $assignments]);
    }

    /**
     * POST /api/staff-assignments
     * Buat jadwal baru (Admin only).
     */
    public function store(StaffAssignmentRequest $request): JsonResponse
    {
        $assignment = StaffAssignment::create($request->validated());
        $assignment->load(['staff', 'gate']);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal staff berhasil dibuat.',
            'data'    => $assignment,
        ], 201);
    }

    /**
     * GET /api/staff-assignments/{assignment}
     */
    public function show(StaffAssignment $staffAssignment): JsonResponse
    {
        $staffAssignment->load(['staff', 'gate']);

        return response()->json(['success' => true, 'data' => $staffAssignment]);
    }

    /**
     * PUT /api/staff-assignments/{assignment}
     */
    public function update(StaffAssignmentRequest $request, StaffAssignment $staffAssignment): JsonResponse
    {
        $staffAssignment->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil diperbarui.',
            'data'    => $staffAssignment->fresh(['staff', 'gate']),
        ]);
    }

    /**
     * DELETE /api/staff-assignments/{assignment}
     */
    public function destroy(StaffAssignment $staffAssignment): JsonResponse
    {
        $staffAssignment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil dihapus.',
        ]);
    }

    /**
     * PATCH /api/staff-assignments/{assignment}/status
     * Update status kehadiran staff (absent, active, completed).
     */
    public function updateStatus(Request $request, StaffAssignment $staffAssignment): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:scheduled,active,completed,absent',
            'notes'  => 'nullable|string|max:500',
        ]);

        $staffAssignment->update([
            'status' => $request->status,
            'notes'  => $request->notes ?? $staffAssignment->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status kehadiran diperbarui.',
            'data'    => $staffAssignment,
        ]);
    }
}
