<?php

namespace App\Http\Controllers;

use App\Models\TicketZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketZoneController extends Controller
{
    //cel kuota (festival/vvip/vip) berdasarkan jenisnya
    public function getZoneByTicket($ticket_id)
    {
        $zones = TicketZone::where('ticket_id', $ticket_id)->get();
        return response()->json([
            'success' => true,
            'data' => $zones
        ], 200);
    }

    public function reduceQuota(Request $request)
    {
        $request->validate([
            'ticket_zone_id' => 'required|exists:ticket_zones,id'
        ]);

        try {
            $zone = null;

            DB::transaction(function () use ($request, &$zone) {
                $zone = TicketZone::lockForUpdate()->find($request->ticket_zone_id);

                if (!$zone->isAvailable()) {
                    throw new \Exception('Kuota sudah habis');
                }

                $zone->decrement('quota_remaining');

            });
            return response()->json([
                'success' => true,
                'message' => 'Kuota berhasil dikurangi',
                'data' => $zone->fresh()
            ], 200);
        } catch (\Exception $e) {
            if ($e->getMessage() === 'Kuota sudah habis') {
                return response()->json([
                    'success' => false,
                    'message' => 'Kuota sudah habis'
                ], 400);
            }

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '
            ], 500);
        }

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
