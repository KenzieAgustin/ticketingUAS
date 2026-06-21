<?php

namespace App\Http\Controllers;

use App\Models\WaitList;
use App\Models\TicketZone;
use App\Notifications\AppNotification;
use Illuminate\Http\Request;

class WaitListController extends Controller
{
    ///user masuk antrian
    public function joinWaitList(Request $request)
    {
        $request->validate([
            'ticket_zone_id' => 'required|exists:ticket_zones,id',
        ]);

        //cek apakah kuota zona benar benar habis
        $zone = TicketZone::find($request->ticket_zone_id);

        if ($zone->isAvailable()) {
            return response()->json([
                'success' => false,
                'message' => 'Kuota zona masih tersedia, tidak perlu masuk antrian'
            ], 400);
        }

        $alreadyWaiting = WaitList::where('user_id', auth()->id())
            ->where('ticket_zone_id', $request->ticket_zone_id)
            ->where('status', 'waiting')
            ->exists();

        if ($alreadyWaiting) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah berada di antrian untuk zona tiket ini'
            ], 400);
        }
        //masukkan ke tabel wait_list
        $waitList = WaitList::create([
            //'user_id' => auth()->id(), //gunakan user_id dari token yang sedang login
            'user_id' => auth()->id(),//ngambil dari jwt
            'ticket_zone_id' => $request->ticket_zone_id,
            'status' => 'waiting',
        ]);

        // Mancing notif
        auth()->user()->notify(new AppNotification(
            type: 'waitlist_joined',
            message: '📋 Kamu berhasil masuk antrian untuk zona ' . $zone->zone_name . '. Kami akan notif kalau ada slot!',
            refId: $waitList->id,
        ));

        return response()->json([
            'success' => true,
            'message' => 'Berhasil masuk antrian',
            'data' => $waitList
        ], 201);
    }

    public function destroy($id)
    {
        $waitList = WaitList::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$waitList) {
            return response()->json([
                'success' => false,
                'message' => 'data antrian tidak ditemukan'
            ], 404);
        }

        $waitList->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil keluar dari antrian'
        ], 200);
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
}
