<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Ticket;
use App\Models\TicketZone;
use App\Models\PricingRule;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pricing_rules')->truncate();
        DB::table('ticket_zones')->truncate();
        DB::table('tickets')->truncate();
        DB::table('events')->truncate();
        DB::table('stages')->truncate();
        DB::table('event_categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Dummy event_category
        $categoryId = DB::table('event_categories')->insertGetId([
            'name'        => 'Konser',
            'description' => 'Event konser musik',
            'slug'        => 'konser',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // Dummy stage
        $stageId = DB::table('stages')->insertGetId([
            'name'        => 'Panggung Utama',
            'location'    => 'JIExpo Kemayoran',
            'description' => 'Panggung utama PRJ',
            'capacity'    => 10000,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // Dummy event
        $eventId = DB::table('events')->insertGetId([
            'name'              => 'PRJ 2026',
            'description'       => 'Pekan Raya Jakarta 2026',
            'date_start'        => '2026-06-01',
            'date_end'          => '2026-07-01',
            'capacity_total'    => 10000,
            'event_category_id' => $categoryId,
            'stage_id'          => $stageId,
            'status'            => 'active',
            'slug'              => 'prj-2026',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // Tiket entry only
        $entryOnly = Ticket::create([
            'event_id'    => $eventId,
            'ticket_type' => 'entry_only',
            'price'       => 50000,
        ]);

        // Tiket entry + concert
        $entryConcert = Ticket::create([
            'event_id'    => $eventId,
            'ticket_type' => 'entry_concert',
            'price'       => 150000,
        ]);

        // Zones untuk entry only
        TicketZone::create([
            'ticket_id'       => $entryConcert->id,
            'zone_name'       => 'Festival Zone',
            'price'           => 150000,
            'quota_total'     => 500,
            'quota_remaining' => 500,
        ]);

        // Zones untuk entry concert
        TicketZone::create([
            'ticket_id'       => $entryConcert->id,
            'zone_name'       => 'VIP Zone',
            'price'           => 250000,
            'quota_total'     => 100,
            'quota_remaining' => 100,
        ]);

        TicketZone::create([
            'ticket_id'       => $entryConcert->id,
            'zone_name'       => 'VVIP Zone',
            'price'           => 400000,
            'quota_total'     => 50,
            'quota_remaining' => 3,
        ]);

        // Pricing rules
        PricingRule::create([
            'ticket_id'      => $entryConcert->id,
            'rule_name'      => 'Early Bird',
            'discount_type'  => 'fixed',
            'discount_value' => 25000,
            'start_date'     => '2026-01-01',
            'end_date'       => '2026-12-31',
        ]);

        PricingRule::create([
            'ticket_id'      => $entryOnly->id,
            'rule_name'      => 'Weekday Discount',
            'discount_type'  => 'percentage',
            'discount_value' => 10,
            'start_date'     => '2026-01-01',
            'end_date'       => '2026-12-31',
        ]);
    }
}