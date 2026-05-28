<?php

namespace App\Http\Controllers;

use App\Models\PricingRule;
use App\Models\Ticket;
use App\Models\TicketZone;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PricingRuleController extends Controller
{
    //perhitungan harga
    public function calculateFinalPrice(Request $request, $ticket_id)
    {
        $ticket = Ticket::findOrFail($ticket_id);
        $today = Carbon::now()->toDateString();

        $basePrice = $ticket->price;
        if ($request->has('zone_id')) {
            $zone = TicketZone::find($request->zone_id);
            if ($zone) {
                $basePrice = $zone->price;
            }
        }

        //cari promo yg aktif untuk tiker per hari ini
        $activePromo = PricingRule::where('ticket_id', $ticket_id)
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->first();
        
        $finalPrice = $basePrice;
        $discountApplied =0;

        if ($activePromo) {
            if ($activePromo->discount_type === 'fixed') {
                $discountApplied = $activePromo->discount_value;
            } elseif ($activePromo->discount_type === 'percentage') {
                $discountApplied = round($basePrice * ($activePromo->discount_value / 100));
            }
            $finalPrice = max(0, $basePrice - $discountApplied);
        }

        return response()->json([
            'success'        => true,
            'ticket_type'    => $ticket->ticket_type,
            'base_price'     => $basePrice,
            'discount'       => $discountApplied,
            'final_price'    => $finalPrice,
            'promo_name'     => $activePromo ? $activePromo->rule_name : 'Harga Normal',
        ], 200);
    }

    public function adminWeb()
    {
        $rules = PricingRule::with('ticket')->get();
        return view('admin.pricing', compact('rules'));
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
