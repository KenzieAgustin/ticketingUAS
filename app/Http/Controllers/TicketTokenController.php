<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\TicketToken;
use App\Notifications\AppNotification;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;

class TicketTokenController extends Controller
{
    // Membuat tiket setelah orderan lunas
    public function generateToken(Request $request)
    {
        $request->validate([
            'order_item_id' => 'required|integer',
        ]);

        //ini duplicate check untuk memastikan tidak ada token ganda untuk order item yang sama
        $existing = TicketToken::where('order_item_id', $request->order_item_id)->first();
        if($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Token sudah ada untuk order item ini.',
                'data'    => $existing
            ], 409);//conflict request valid tapi duplikat
        }

        //unique booking code, jadi loop sampe dapet kode yg belom kepake, biar ga ada duplikat booking code
        do {
            $bookingCode = 'PRJ2026-' . strtoupper(Str::random(6));
        } while (TicketToken::where('booking_code', $bookingCode)->exists());

        //ini buat folder qrcode kalo belom ada
        if (!file_exists(public_path('qrcodes'))) {
            mkdir(public_path('qrcodes'), 0777, true);
        }

        $fileName = $bookingCode . ".png";
        $path = public_path('qrcodes/' . $fileName);

        // Generate QR code dan simpan sebagai file PNG
        $qrCode = new QrCode($bookingCode);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        file_put_contents($path, $result->getString());

        $token = TicketToken::create([
            'order_item_id' => $request->order_item_id,
            'booking_code'  => $bookingCode,
            'qr_code_path'  => 'qrcodes/' . $fileName,
            'status'        => 'valid',
        ]);

        // Ngambil user dari order item buat mancing notif
        $orderItem = \App\Models\OrderItem::find($request->order_item_id);
        $orderItem?->order?->user?->notify(new AppNotification(
            type: 'ticket_generated',
            message: '🎫 Tiket kamu dengan kode ' . $bookingCode . ' berhasil dibuat. Tunjukkan QR code saat masuk!',
            refId: $token->id,
        ));

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

        //cari token pake booking code
        $token = TicketToken::with('orderItem.ticketZone.ticket')
            ->where('booking_code', $request->booking_code)
            ->first();

        if (!$token) {
            return response()->json([
                'success' => false,
                'status'  => 'invalid',
                'message' => 'Booking code tidak ditemukan.',
            ], 404);
        }
        //kalo tkn udah pernah dipake, tetap tampilkan info tiketnya tapi statusnya used
        if ($token->status === 'used') {
            return response()->json([
                'success' => false,
                'status'  => 'used',
                'message' => 'Tiket ini sudah digunakan.',
                'data'    => [
                    'booking_code' => $token->booking_code,
                    //tetap ditampilkan info tiket walau uda dipake
                    'ticket_type' => optional(optional(optional($token->orderItem)->ticketZone)->ticket)->ticket_type,
                    'zone_name'   => optional(optional($token->orderItem)->ticketZone)->zone_name,
                ],
            ], 200);
        }

        // Catatan: status TIDAK diubah jadi 'used' di sini.
        // Validasi/scan ini bersifat read-only (cek keaslian saja).
        // Token baru di-mark 'used' saat proses Check-in benar-benar selesai
        // (ditangani oleh CheckInService), supaya scan ulang/gagal jaringan
        // tidak mengunci tiket yang sebenarnya belum resmi masuk venue.

        return response()->json([
            'success' => true,
            'message' => 'Tiket valid. Selamat datang di PRJ 2026!',
            'data'    => [
                'booking_code' => $token->booking_code,
                //info lengkap tampilan dihalaman scan
                'ticket_type' => $token->orderItem?->ticketZone?->ticket?->ticket_type,
                'zone_name'   => $token->orderItem?->ticketZone?->zone_name,
            ],
        ], 200);
    }

    // Halaman scan untuk petugas
    public function scanWeb()
    {
        return view('scan.index');
    }
}
