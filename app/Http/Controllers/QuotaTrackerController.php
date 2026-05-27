<?php

namespace App\Http\Controllers;

use App\Models\TicketZone;
use Illuminate\Http\Request;

class QuotaTrackerController extends Controller
{
    public function indexWeb()
    {
        $trackerData = TicketZone::with('ticket')->get();
        return view('tracker.index', compact('trackerData'));
    }
}
