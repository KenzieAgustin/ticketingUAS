<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Ticket;
use App\Models\TicketZone;
use App\Models\PricingRule;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    //hapus data lama
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Ticket::truncate();
        TicketZone::truncate();
        PricingRule::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        //tiket jenis 1 (entry only)
        $entryOnly = Ticket::create([
            'event_id' => 1,
            'ticket_type' => 'Entry_only',
            'price' => 50000,
        ]);

        //tiket jenis 2 (entry + concert)
        $entryConcert = Ticket::create([
            'event_id' => 1,
            'ticket_type' => 'Entry_concert',
            'price' => 150000,
        ]);

        TicketZone::create([
            'ticket_id' => $entryOnly->id,
            'zone_name' => 'Festival Zone',
            'price' => 150000,
            'quota_total' => 500,
            'quota_remaining' => 500,
        ]);

        TicketZone::create([
            'ticket_id' => $entryConcert->id,
            'zone_name' => 'VIP Zone',
            'price' => 250000,
            'quota_total' => 100,
            'quota_remaining' => 100,
        ]);

        TicketZone::create([
            'ticket_id' => $entryConcert->id,
            'zone_name' => 'VVIP Zone',
            'price' => 400000,
            'quota_total' => 50,
            'quota_remaining' => 3,
        ]);

        PricingRule::create([
            'ticket_id' => $entryConcert->id,
            'rule_name' => 'Early Bird',
            'discount_type' => 'fixed',
            'discount_value' => 25002,
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
        ]);

        PricingRule::create([
            'ticket_id' => $entryOnly->id,
            'rule_name' => 'Weekday discount',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
        ]);

    }
}
