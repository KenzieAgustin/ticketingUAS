<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    public function getSummary(?int $eventId = null): array
    {
        $cacheKey = 'dashboard_summary_' . ($eventId ?? 'all');

        return Cache::remember($cacheKey, 300, function () use ($eventId) {
            return [
                'total_visitors'  => $this->totalVisitors($eventId),
                'total_revenue'   => $this->totalRevenue($eventId),
                'tickets_sold'    => $this->ticketsSold($eventId),
                'check_ins_today' => $this->checkInsToday(),
                'avg_rating'      => $this->avgRating($eventId),
                'pending_reviews' => $this->pendingReviews($eventId),
            ];
        });
    }

    // Rekap penjualan per ticket_type per hari.
    public function getSalesReport(array $filters = []): array
    {
        $query = DB::table('order_items as oi')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->join('ticket_types as tt', 'tt.id', '=', 'oi.ticket_type_id')
            ->select([
                'tt.event_id',
                'tt.name as ticket_type',
                DB::raw('DATE(o.created_at) as sale_date'),
                DB::raw('SUM(oi.quantity) as qty_sold'),
                DB::raw('SUM(oi.subtotal) as revenue'),
            ])
            ->where('o.status', 'paid');

        if (! empty($filters['event_id'])) {
            $query->where('tt.event_id', $filters['event_id']);
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('o.created_at', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('o.created_at', '<=', $filters['date_to']);
        }

        if (! empty($filters['ticket_type_id'])) {
            $query->where('oi.ticket_type_id', $filters['ticket_type_id']);
        }

        $rows = $query
            ->groupBy('tt.event_id', 'oi.ticket_type_id', DB::raw('DATE(o.created_at)'))
            ->orderBy('sale_date')
            ->get();

        return [
            'filters'       => $filters,
            'total_revenue' => $rows->sum('revenue'),
            'total_qty'     => $rows->sum('qty_sold'),
            'breakdown'     => $rows,
        ];
    }

    // Rekap check-in per gate per hari.
    public function getCheckInReport(int $eventId, ?string $date = null): array
    {
        $query = DB::table('check_ins as ci')
            ->join('gates as g', 'g.id', '=', 'ci.gate_id')
            ->select([
                'g.name as gate_name',
                'g.type as gate_type',
                DB::raw('DATE(ci.checked_at) as check_date'),
                DB::raw('COUNT(CASE WHEN ci.status = "success" THEN 1 END) as success_count'),
                DB::raw('COUNT(CASE WHEN ci.status = "failed" THEN 1 END) as failed_count'),
                DB::raw('COUNT(CASE WHEN ci.status = "duplicate" THEN 1 END) as duplicate_count'),
            ]);

        if ($date) {
            $query->whereDate('ci.checked_at', $date);
        }

        $rows = $query
            ->groupBy('ci.gate_id', DB::raw('DATE(ci.checked_at)'))
            ->orderBy('check_date')
            ->get();

        return [
            'event_id'    => $eventId,
            'date_filter' => $date,
            'data'        => $rows,
            'grand_total' => $rows->sum('success_count'),
        ];
    }

    // private function totalVisitors(int $eventId): int

    private function totalVisitors(?int $eventId): int
    {
        return DB::table('check_ins')
            ->where('status', 'success')
            ->count();
    }

    private function totalRevenue(?int $eventId): float
    {
        $query = DB::table('orders')->where('status', 'paid');

        if ($eventId) {
            // Join lewat order_items dan ticket_types untuk filter by event
            $query = DB::table('orders as o')
                ->join('order_items as oi', 'oi.order_id', '=', 'o.id')
                ->join('ticket_types as tt', 'tt.id', '=', 'oi.ticket_type_id')
                ->where('o.status', 'paid')
                ->where('tt.event_id', $eventId);

            return (float) $query->sum('oi.subtotal');
        }

        return (float) $query->sum('total_amount');
    }

    private function ticketsSold(?int $eventId): int
    {
        $query = DB::table('order_items as oi')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->join('ticket_types as tt', 'tt.id', '=', 'oi.ticket_type_id')
            ->where('o.status', 'paid');

        if ($eventId) {
            $query->where('tt.event_id', $eventId);
        }

        return (int) $query->sum('oi.quantity');
    }

    private function checkInsToday(): int
    {
        return DB::table('check_ins')
            ->where('status', 'success')
            ->whereDate('checked_at', today())
            ->count();
    }

    private function avgRating(?int $eventId): float
    {
        $query = DB::table('reviews')->where('status', 'approved');
        if ($eventId) {
            $query->where('event_id', $eventId);
        }
        return round((float) $query->avg('rating'), 1);
    }

    private function pendingReviews(?int $eventId): int
    {
        $query = DB::table('reviews')->where('status', 'pending');
        if ($eventId) {
            $query->where('event_id', $eventId);
        }
        return $query->count();
    }
}