<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CheckIn;
use App\Models\Gate;
use App\Models\Review;
use App\Models\StaffAssignment;

class DashboardController extends Controller
{
    public function index()
    {
        $totalGates      = Gate::count();
        $activeGates     = Gate::where('status', 'active')->count();
        $todayCheckIns   = CheckIn::whereDate('checked_at', today())->count();
        $successRate     = $todayCheckIns > 0
            ? round(CheckIn::whereDate('checked_at', today())->where('status', 'success')->count() / $todayCheckIns * 100)
            : 0;
        $activeStaff     = StaffAssignment::where('status', 'active')->count();
        $totalAssignments = StaffAssignment::whereDate('assignment_date', today())->count();
        $pendingReviews  = Review::where('status', 'pending')->count();
 
        $recentCheckIns = CheckIn::with(['gate', 'staff'])
            ->whereDate('checked_at', today())
            ->latest('checked_at')
            ->limit(10)
            ->get();
 
        $gateStatus = Gate::withCount(['checkIns' => fn ($q) => $q->whereDate('checked_at', today())])
            ->orderBy('code')
            ->get();
 
        return view('admin.dashboard.index', compact(
            'totalGates', 'activeGates', 'todayCheckIns', 'successRate',
            'activeStaff', 'totalAssignments', 'pendingReviews',
            'recentCheckIns', 'gateStatus'
        ));
    }
}
