<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\GateRequest;
use App\Models\Gate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GateController extends Controller
{
    /**
     * GET /api/gates
     */
    public function index(Request $request): JsonResponse
    {
        $gates = Gate::query()
            ->when($request->type,   fn ($q) => $q->where('type', $request->type))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->withCount(['staffAssignments', 'checkIns'])
            ->orderBy('code')
            ->get();

        return response()->json(['success' => true, 'data' => $gates]);
    }

    /**
     * POST /api/gates
     */
    public function store(GateRequest $request): JsonResponse
    {
        $gate = Gate::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Gate berhasil dibuat.',
            'data'    => $gate,
        ], 201);
    }

    /**
     * GET /api/gates/{gate}
     */
    public function show(Gate $gate): JsonResponse
    {
        $gate->load(['staffAssignments.staff', 'checkIns' => fn ($q) => $q->today()->latest()]);

        return response()->json(['success' => true, 'data' => $gate]);
    }

    /**
     * PUT /api/gates/{gate}
     */
    public function update(GateRequest $request, Gate $gate): JsonResponse
    {
        $gate->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Gate berhasil diperbarui.',
            'data'    => $gate,
        ]);
    }

    /**
     * DELETE /api/gates/{gate}
     */
    public function destroy(Gate $gate): JsonResponse
    {
        $gate->delete();

        return response()->json([
            'success' => true,
            'message' => 'Gate berhasil dihapus.',
        ]);
    }
}
