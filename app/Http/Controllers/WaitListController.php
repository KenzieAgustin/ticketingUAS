<?php

namespace App\Http\Controllers;

use App\Models\WaitList;
use App\Models\TicketZone;
use Illuminate\Http\Request;

class WaitListController extends Controller
{
    ///user masuk antrian
    public function joinWaitList(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'ticket_zone_id' => 'required|exists:ticket_zones,id',
        ]);

        //cek apakah kuota zona benar benar habis
        $zone = TicketZone::find($request->ticket_zone_id);
        if ($zone->quota_remaining > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Kuota zona masih tersedia, tidak perlu masuk antrian'
            ], 400);
        }

        //masukkan ke tabel wait_list
        $waitList = WaitList::create([
            'user_id' => $request->user_id,
            'ticket_zone_id' => $request->ticket_zone_id,
            'status' => 'waiting',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil masuk antrian',
            'data' => $waitList
        ], 201);
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
