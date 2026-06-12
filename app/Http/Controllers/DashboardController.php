<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $dashboardService) {}

    /**
     * GET /api/dashboard/summary
     * Statistik ringkas: total pengunjung, pendapatan, kuota tersisa.
     */
    public function summary(Request $request): JsonResponse
    {
        $request->validate(['event_id' => 'nullable|integer']);

        $data = $this->dashboardService->getSummary($request->event_id);

        return response()->json(['success' => true, 'data' => $data]);
    }

    /**
     * GET /api/dashboard/sales-report
     * Rekap penjualan tiket.
     */
    public function salesReport(Request $request): JsonResponse
    {
        $request->validate([
            'event_id'       => 'nullable|integer',
            'ticket_type_id' => 'nullable|integer',
            'date_from'      => 'nullable|date',
            'date_to'        => 'nullable|date|after_or_equal:date_from',
        ]);

        $report = $this->dashboardService->getSalesReport($request->only([
            'event_id', 'ticket_type_id', 'date_from', 'date_to',
        ]));

        return response()->json(['success' => true, 'data' => $report]);
    }

    /**
     * GET /api/dashboard/check-in-report
     * Rekap check-in per gate per hari.
     */
    public function checkInReport(Request $request): JsonResponse
    {
        $request->validate([
            'event_id' => 'required|integer',
            'date'     => 'nullable|date',
        ]);

        $report = $this->dashboardService->getCheckInReport(
            $request->event_id,
            $request->date
        );

        return response()->json(['success' => true, 'data' => $report]);
    }
}
