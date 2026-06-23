<?php

namespace App\Http\Controllers;

use App\Models\TicketZone;
use Illuminate\Http\Request;

class QuotaTrackerController extends Controller
{
    public function indexWeb()
    {
        $trackerData = TicketZone::with('ticket')->get();
        $zoneIds = $trackerData->pluck('id');

        $orderItems = \App\Models\OrderItem::with(['tokens', 'order.user', 'order.payment'])
            ->whereIn('ticket_zone_id', $zoneIds)
            ->get()
            ->groupBy('ticket_zone_id');

        return view('tracker.index', compact('trackerData', 'orderItems'));
    }

    //status kuota semua zone
    Public function index()
    {
        $zones = TicketZone::with('ticket')->get()->map(function ($zone) {
            return [
                'zone_id' => $zone->id,
                'zone_name' => $zone->zone_name,
                'ticket_type' => $zone->ticket->ticket_type,
                'quota_total' => $zone->quota_total,
                'quota_remaining' => $zone->quota_remaining,
                'quota_used' => $zone->quota_total - $zone->quota_remaining,
                //sisa kuota dalam persen, jadi bisa buat progress bar di frontend
                'percentage_remaining' => $zone->quota_total > 0 ? round(($zone->quota_remaining / $zone->quota_total) * 100) : 0,
                'status' => $zone->quota_remaining <= 0 ? 'habis' : ($zone->quota_remaining <= ($zone->quota_total * 0.1) ? 'hampir habis' : 'tersedia'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $zones
        ], 200);
    }

    // ambil status kuota zone tertentu,
    public function show($id)
    {
        $zone = TicketZone::with('ticket')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'zone_id' => $zone->id,
                'zone_name' => $zone->zone_name,
                'ticket_type' => $zone->ticket->ticket_type,
                'quota_total' => $zone->quota_total,
                'quota_remaining' => $zone->quota_remaining,
                'quota_used' => $zone->quota_total - $zone->quota_remaining,
                'percentage_remaining' => $zone->quota_total > 0 ? round(($zone->quota_remaining / $zone->quota_total) * 100) : 0,
                'status' => $zone->quota_remaining <= 0 ? 'habis' : ($zone->quota_remaining <= ($zone->quota_total * 0.1) ? 'hampir habis' : 'tersedia'),
            ]
        ], 200);
    }
}
