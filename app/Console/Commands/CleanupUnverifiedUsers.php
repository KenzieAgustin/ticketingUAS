<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CleanupUnverifiedUsers extends Command
{
    protected $signature = 'users:cleanup-unverified';

    protected $description = 'Hapus akun yang belum verifikasi email lebih dari 24 jam';

    public function handle(): void
    {
        $count = User::whereNull('email_verified_at')
            ->where('created_at', '<', now()->subDay())
            ->delete();

        $this->info("Berhasil menghapus {$count} akun yang belum diverifikasi.");
    }
}
