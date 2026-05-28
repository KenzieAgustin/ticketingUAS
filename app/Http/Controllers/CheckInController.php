<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckInRequest;
use App\Models\CheckIn;
use App\Services\CheckInService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function __construct(private readonly CheckInService $checkInService) {}

    /**
     * POST /api/check-ins/scan
     * Scan QR atau input kode manual oleh staff gate.
     */
    public function scan(CheckInRequest $request): JsonResponse
    {
        /** @var \App\Models\User $staff */
        $staff  = $request->user();
        $result = $this->checkInService->process(
            bookingCode: $request->booking_code,
            gateId:      $request->gate_id,
            staffId:     $staff->id,
            method:      $request->method ?? 'qr_scan',
        );

        $httpStatus = $result['success'] ? 200 : 422;

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
            'data'    => $result,
        ], $httpStatus);
    }

    /**
     * GET /api/check-ins
     * Riwayat check-in (Admin / Staff).
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'gate_id'    => 'nullable|integer',
            'date'       => 'nullable|date',
            'status'     => 'nullable|in:success,failed,duplicate',
            'per_page'   => 'nullable|integer|max:100',
        ]);

        $checkIns = CheckIn::with(['gate', 'staff'])
            ->when($request->gate_id,  fn ($q) => $q->where('gate_id', $request->gate_id))
            ->when($request->date,     fn ($q) => $q->whereDate('checked_at', $request->date))
            ->when($request->status,   fn ($q) => $q->where('status', $request->status))
            ->latest('checked_at')
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data'    => $checkIns,
        ]);
    }
}
