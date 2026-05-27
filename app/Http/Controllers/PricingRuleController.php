<?php

namespace App\Http\Controllers;

use App\Models\PricingRule;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PricingRuleController extends Controller
{
    //perhitungan harga
    public function calculateFinalPrice($ticket_id)
    {
        $ticket = Ticket::findOrFail($ticket_id);
        $today = Carbon::now()->toDateString();

        //cari promo yg aktif untuk tiker per hari ini
        $activePromo = PricingRule::where('ticket_id', $ticket_id)
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->first();
        
        $finalPrice = $ticket->base_price;
        $discountApplied =0;

        if ($activePromo) {
            $discountApplied = $activePromo->discount_value;
            $finalPrice = max(0, $ticket->base_price - $discountApplied);
        }

        return response()->json([
            'success' => true,
            'ticket_type' => $ticket->ticket_type,
            'original_price' => $ticket->base_price,
            'discount' => $discountApplied,
            'final_price' => $finalPrice,
            'promo_name' => $activePromo ? $activePromo->rule_name : 'Harga normal'
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
    public function destroy(string $id)
    {
        //
    }
}
