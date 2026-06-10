<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use Illuminate\Support\Str;

class RedeemController extends Controller
{
  public function redeem(Request $request)
    {
    $request->validate([
        'required_points' => 'required|integer|min:1',
    ]);

    $user = \App\Models\User::first();
    $pointsNeeded = $request->required_points;

    if ($user->points < $pointsNeeded) {
        return back()->with('error', 'Poin tidak cukup. Poin kamu: ' . $user->points);
    }

    $discountAmount = ($pointsNeeded / 10) * 10000;

    $voucher = \App\Models\Voucher::create([
        'code'            => 'POINT-' . strtoupper(\Illuminate\Support\Str::random(8)),
        'discount_amount' => $discountAmount,
        'discount_type'   => 'fixed',
        'quota'           => 1,
        'used'            => 0,
        'expired_at'      => now()->addDays(30),
        'valid_until'     => now()->addDays(30),
        'required_points' => $pointsNeeded,
    ]);

    $user->decrement('points', $pointsNeeded);

    return back()->with('success', 'Berhasil! Kode voucher kamu: ' . $voucher->code . ' (diskon Rp' . number_format($discountAmount) . ')');
    }

    public function myPoints()
    {
    return view('points');
    }

}
