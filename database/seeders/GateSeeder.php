<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gate;

class GateSeeder extends Seeder
{
    public function run(): void
    {
        $gates = [
            // Gate utama PRJ
            ['code' => 'GATE-UTAMA-1', 'name' => 'Gate Utama 1', 'type' => 'main', 'stage_id' => null, 'description' => 'Gate utama 1 untuk masuk ke area PRJ', 'status' => 'active'],
            ['code' => 'GATE-UTAMA-2', 'name' => 'Gate Utama 2', 'type' => 'main', 'stage_id' => null, 'description' => 'Gate utama 2 untuk masuk ke area PRJ', 'status' => 'active'],
            ['code' => 'GATE-UTAMA-3', 'name' => 'Gate Utama 3 (VIP)', 'type' => 'main', 'stage_id' => null, 'description' => 'Gate VIP untuk masuk ke area PRJ', 'status' => 'active'],

            // Gate area konser (stage_id diisi setelah seeder Stage dijalankan)
            ['code' => 'GATE-KONSER-FESTIVAL', 'name' => 'Gate Konser Festival', 'type' => 'concert', 'stage_id' => 1, 'description' => 'Gate untuk masuk ke area konser festival', 'status' => 'active'],
            ['code' => 'GATE-KONSER-VIP', 'name' => 'Gate Konser VIP', 'type' => 'concert', 'stage_id' => 1, 'description' => 'Gate VIP untuk masuk ke area konser utama', 'status' => 'active'],
            ['code' => 'GATE-KONSER-VVIP', 'name' => 'Gate Konser VVIP', 'type' => 'concert', 'stage_id' => 1, 'description' => 'Gate VVIP untuk masuk ke area konser utama', 'status' => 'active'],

            // Gate area pameran (stage_id diisi setelah seeder Stage dijalankan)
            ['code' => 'GATE-HALL-A', 'name' => 'Gate Hall A', 'type' => 'exhibition', 'stage_id' => 2, 'description' => 'Gate untuk masuk ke area pameran 1', 'status' => 'active'],
            ['code' => 'GATE-HALL-B', 'name' => 'Gate Hall B', 'type' => 'exhibition', 'stage_id' => 2, 'description' => 'Gate untuk masuk ke area pameran 2', 'status' => 'active'],
            
            // Gate darurat / khusus
            ['code' => 'GATE-DARURAT-1', 'name' => 'Gate Darurat Utara', 'type' => 'emergency', 'stage_id' => null, 'description' => 'Gate untuk keperluan darurat atau evakuasi', 'status' => 'inactive'],
            ['code' => 'GATE-DARURAT-2', 'name' => 'Gate Darurat Selatan', 'type' => 'emergency', 'stage_id' => null, 'description' => 'Gate untuk keperluan darurat atau evakuasi', 'status' => 'inactive'],
        ];

        foreach ($gates as $gate) {
            Gate::firstOrCreate(['code' => $gate['code']], $gate);
        }

        $this->command->info('Gate Seeder: ' . count($gates) . ' gates seeded.');
    }
}
