<?php

namespace App\Services;

use App\Models\CheckIn;
use App\Models\Gate;
use Illuminate\Support\Facades\DB;

class CheckInService
{
    // Proses scan tiket (QR atau kode manual).
    public function process(
        string $bookingCode,
        int    $gateId,
        ?int    $staffId,
        string $method = 'qr_scan'
    ): array {
        return DB::transaction(function () use ($bookingCode, $gateId, $staffId, $method) {

            // 1. Cek apakah gate aktif
            $gate = Gate::where('id', $gateId)->where('status', 'active')->first();
            if (! $gate) {
                return $this->failResult($bookingCode, $gateId, $staffId, $method, 'Gate tidak aktif atau tidak ditemukan.');
            }

            // 2. Cek duplikat di tabel check_ins
            $duplicate = CheckIn::where('booking_code', $bookingCode)
                ->where('status', 'success')
                ->exists();

            if ($duplicate) {
                $this->recordCheckIn($bookingCode, null, null, $gateId, $staffId, $method, 'duplicate', 'Tiket sudah digunakan.');
                return [
                    'success'      => false,
                    'status'       => 'duplicate',
                    'message'      => 'Tiket sudah digunakan sebelumnya.',
                    'booking_code' => $bookingCode,
                ];
            }

            // 3. Validasi booking_code ke tabel ticket_tokens
            $ticketToken = DB::table('ticket_tokens')
                ->where('booking_code', $bookingCode)
                ->where('status', 'valid')
                ->first();

            if (! $ticketToken) {
                // Cek apakah tiket ada tapi sudah dipakai
                $usedToken = DB::table('ticket_tokens')
                    ->where('booking_code', $bookingCode)
                    ->where('status', 'used')
                    ->first();

                $reason = $usedToken
                    ? 'Tiket ini sudah digunakan.'
                    : 'Kode booking tidak valid.';

                $this->recordCheckIn($bookingCode, null, null, $gateId, $staffId, $method, 'failed', $reason);
                return [
                    'success'      => false,
                    'status'       => 'failed',
                    'message'      => $reason,
                    'booking_code' => $bookingCode,
                ];
            }

            // 4. Mark token as used
            DB::table('ticket_tokens')
                ->where('id', $ticketToken->id)
                ->update(['status' => 'used']);

            // 5. Simpan check-in sukses
            $checkIn = $this->recordCheckIn(
                $bookingCode,
                $ticketToken->id,
                $ticketToken->order_item_id,
                $gateId,
                $staffId,
                $method,
                'success'
            );

            return [
                'success'      => true,
                'status'       => 'success',
                'message'      => 'Check-in berhasil. Selamat datang di PRJ 2026!',
                'booking_code' => $bookingCode,
                'check_in_id'  => $checkIn->id,
                'checked_at'   => $checkIn->checked_at,
                'gate'         => $gate->name,
            ];
        });
    }

    private function failResult(string $bookingCode, int $gateId, int $staffId, string $method, string $reason): array
    {
        $this->recordCheckIn($bookingCode, null, null, $gateId, $staffId, $method, 'failed', $reason);
        return [
            'success'      => false,
            'status'       => 'failed',
            'message'      => $reason,
            'booking_code' => $bookingCode,
        ];
    }

    private function recordCheckIn(
        string  $bookingCode,
        ?int    $ticketTokenId,
        ?int    $orderItemId,
        int     $gateId,
        int     $staffId,
        string  $method,
        string  $status,
        ?string $failureReason = null
    ): CheckIn {
        return CheckIn::create([
            'booking_code'    => $bookingCode,
            'ticket_token_id' => $ticketTokenId,
            'order_item_id'   => $orderItemId,
            'gate_id'         => $gateId,
            'checked_by'      => $staffId,
            'method'          => $method,
            'status'          => $status,
            'failure_reason'  => $failureReason,
            'checked_at'      => now(),
        ]);
    }
}