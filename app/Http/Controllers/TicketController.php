<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\PricingRule;

class TicketController extends Controller
{
    public function buyWeb($id)
    {
        $ticket = Ticket::with('zones', 'pricingRules')->findOrFail($id);
        return view('tickets.buy', compact('ticket'));
    }
    public function indexWeb()
    {
        $tickets = Ticket::all();
        return view('tickets.index', compact('tickets'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::with('zones', 'pricingRules')->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar tiket berhasil diambil',
            'data' => $tickets
        ], 200);
    }
    public function adminWeb()
{
    $tickets      = Ticket::with('zones', 'pricingRules')->get();
    $pricingRules = PricingRule::with('ticket')->get();
    $activePromos = PricingRule::where('start_date', '<=', now())
                               ->where('end_date', '>=', now())
                               ->get();

    return view('admin.tickets', compact('tickets', 'pricingRules', 'activePromos'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * dipake nanti saat nambah jenis tiket baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'ticket_type' => 'required|string|unique:tickets,ticket_type',
            'base_price' => 'required|numeric|min:0',
        ]);

        $ticket = Ticket::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Tiket berhasil dibuat',
            'data' => $ticket
        ], 201);
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
