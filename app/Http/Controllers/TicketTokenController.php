<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketTokenController extends Controller
{
    //Membuat tiket setelah orderan lunas
    public function generateToken(Request $request)
    {
        $request->validate([
            'order_item_id' => 'required|integer',
        ]);

        $bookingCode = 'PRJ-' . strtoupper(Str::random(6));

        if (!file_exists(public_path('qrcodes'))) {
            mkdir(public_path('qrcodes'), 0777, true);
        }

        $fileName = $bookingCode . ".png";
        $path = public_path('qrcodes/' . $fileName);

        QrCode::format('png')->size(250)->generate($bookingCode, $path);

        ///simpen info tiket ke database
        $token = TicketToken::create([
            'order_item_id' => $request->order_item_id,
            'booking_code' => $bookingCode,
            'qr_code_path' => 'qrcodes/' . $fileName,
            'status' => 'valid',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tiket & QR Code berhasil dibuat',
            'data' => $token
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
