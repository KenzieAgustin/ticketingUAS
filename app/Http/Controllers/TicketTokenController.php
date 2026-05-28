<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\TicketToken;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;

class TicketTokenController extends Controller
{
    // Membuat tiket setelah orderan lunas
    public function generateToken(Request $request)
    {
        $request->validate([
            'order_item_id' => 'required|integer',
        ]);

        $bookingCode = 'PRJ2026-' . strtoupper(Str::random(6));

        if (!file_exists(public_path('qrcodes'))) {
            mkdir(public_path('qrcodes'), 0777, true);
        }

        $fileName = $bookingCode . ".png";
        $path = public_path('qrcodes/' . $fileName);

        QrCode::format('png')->size(250)->generate($bookingCode, $path);

        $token = TicketToken::create([
            'order_item_id' => $request->order_item_id,
            'booking_code'  => $bookingCode,
            'qr_code_path'  => 'qrcodes/' . $fileName,
            'status'        => 'valid',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tiket & QR Code berhasil dibuat',
            'data'    => $token
        ], 201);
    }

    // Validasi booking code — dipanggil dari halaman scan
    public function validateToken(Request $request)
    {
        $request->validate([
            'booking_code' => 'required|string',
        ]);

        $token = TicketToken::where('booking_code', $request->booking_code)->first();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Booking code tidak ditemukan.',
            ], 404);
        }

        if ($token->status === 'used') {
            return response()->json([
                'success' => false,
                'status'  => 'used',
                'message' => 'Tiket ini sudah digunakan.',
                'data'    => ['booking_code' => $token->booking_code],
            ], 200);
        }

        // Mark as used
        $token->update(['status' => 'used']);

        return response()->json([
            'success' => true,
            'message' => 'Tiket valid. Selamat datang di PRJ 2026!',
            'data'    => [
                'booking_code' => $token->booking_code,
                'status'       => 'used',
            ],
        ], 200);
    }

    // Halaman scan untuk petugas
    public function scanWeb()
    {
        return view('scan.index');
    }
}