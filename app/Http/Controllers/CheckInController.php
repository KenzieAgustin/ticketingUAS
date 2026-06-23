<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CheckIn;
use App\Models\Gate;
use App\Services\CheckInService;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function __construct(private readonly CheckInService $checkInService) {}

    // GET /check-ins
    public function index(Request $request)
    {
        $request->validate([
            'gate_id' => 'nullable|integer',
            'date'    => 'nullable|date',
            'status'  => 'nullable|in:success,failed,duplicate',
        ]);

        $checkIns = CheckIn::with(['gate', 'staff'])
            ->when($request->gate_id, fn ($q) => $q->where('gate_id', $request->gate_id))
            ->when($request->date,    fn ($q) => $q->whereDate('checked_at', $request->date))
            ->when($request->status,  fn ($q) => $q->where('status', $request->status))
            ->latest('checked_at')
            ->paginate(20);

        $gates = Gate::orderBy('name')->get();

        $stats = [
            'total'     => CheckIn::whereDate('checked_at', $request->date ?? today())->count(),
            'success'   => CheckIn::whereDate('checked_at', $request->date ?? today())->where('status', 'success')->count(),
            'failed'    => CheckIn::whereDate('checked_at', $request->date ?? today())->where('status', 'failed')->count(),
            'duplicate' => CheckIn::whereDate('checked_at', $request->date ?? today())->where('status', 'duplicate')->count(),
        ];

        return view('admin.check-ins.index', compact('checkIns', 'gates', 'stats'));
    }

    // POST /check-ins/scan
    public function scan(Request $request)
    {
        $request->validate([
            'booking_code' => 'required|string|max:100',
            'gate_id'      => 'required|integer|exists:gates,id',
            'method'       => 'nullable|in:qr_scan,manual_code',
        ]);

        $staff  = $request->user();
        $result = $this->checkInService->process(
            bookingCode: $request->booking_code,
            gateId:      $request->gate_id,
            staffId:     $staff->id ?? null,
            method:      $request->input('method') ?? 'qr_scan',
        );

        $msg = $result['success'] ? $result['message'] : null;
        $err = !$result['success'] ? $result['message'] : null;

        return redirect()->route('staff.check-ins.scan')
            ->with('success', $msg)
            ->with('error', $err);
    }

    // GET /staff/check-ins/scan
    public function staffScan()
    {
        $gates = Gate::where('status', 'active')->orderBy('name')->get();
        return view('staff.check-ins.scan', compact('gates'));
    }
}