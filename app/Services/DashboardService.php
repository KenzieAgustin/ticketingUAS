<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    /**
     * Statistik utama dashboard (di-cache 5 menit).
     */
    public function getSummary(?int $eventId = null): array
    {
        $cacheKey = 'dashboard_summary_' . ($eventId ?? 'all');

        return Cache::remember($cacheKey, 300, function () use ($eventId) {
            return [
                'total_visitors'  => $this->totalVisitors($eventId),
                'total_revenue'   => $this->totalRevenue($eventId),
                'tickets_sold'    => $this->ticketsSold($eventId),
                'quota_remaining' => $this->quotaRemaining($eventId),
                'check_ins_today' => $this->checkInsToday($eventId),
                'avg_rating'      => $this->avgRating($eventId),
                'pending_reviews' => $this->pendingReviews($eventId),
            ];
        });
    }

    /**
     * Rekap penjualan per event, per ticket type, per hari.
     */
    public function getSalesReport(array $filters = []): array
    {
        $query = DB::table('order_items as oi')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->join('ticket_types as tt', 'tt.id', '=', 'oi.ticket_type_id')
            ->select([
                'oi.event_id',
                'tt.name as ticket_type',
                DB::raw('DATE(o.created_at) as sale_date'),
                DB::raw('COUNT(oi.id) as qty_sold'),
                DB::raw('SUM(oi.subtotal) as revenue'),
            ])
            ->where('o.status', 'paid');

        if (! empty($filters['event_id'])) {
            $query->where('oi.event_id', $filters['event_id']);
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

        $rows = $query->groupBy('oi.event_id', 'oi.ticket_type_id', DB::raw('DATE(o.created_at)'))
            ->orderBy('sale_date')
            ->get();

        return [
            'filters'       => $filters,
            'total_revenue' => $rows->sum('revenue'),
            'total_qty'     => $rows->sum('qty_sold'),
            'breakdown'     => $rows,
        ];
    }

    /**
     * Rekap check-in per gate per hari.
     */
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

        $rows = $query->groupBy('ci.gate_id', DB::raw('DATE(ci.checked_at)'))
            ->orderBy('check_date')
            ->get();

        return [
            'event_id'    => $eventId,
            'date_filter' => $date,
            'data'        => $rows,
            'grand_total' => $rows->sum('success_count'),
        ];
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function totalVisitors(?int $eventId): int
    {
        $q = DB::table('check_ins')->where('status', 'success');
        if ($eventId) {
            $q->join('ticket_tokens', 'ticket_tokens.id', '=', 'check_ins.ticket_token_id')
              ->where('ticket_tokens.event_id', $eventId);
        }
        return $q->count();
    }

    private function totalRevenue(?int $eventId): float
    {
        if ($eventId) {
            return (float) DB::table('orders')
                ->join('order_items', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.status', 'paid')
                ->where('order_items.event_id', $eventId)
                ->sum('order_items.subtotal');
        }
        return (float) DB::table('orders')
            ->where('status', 'paid')
            ->sum('total_amount');
    }

    private function ticketsSold(?int $eventId): int
    {
        $q = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'paid');
        if ($eventId) {
            $q->where('order_items.event_id', $eventId);
        }
        return $q->count();
    }

    private function quotaRemaining(?int $eventId): array
    {
        $q = DB::table('quota_trackers');
        if ($eventId) {
            $q->where('event_id', $eventId);
        }
        $result = $q->selectRaw('
            SUM(total_quota) as total_quota,
            SUM(sold_quota) as sold_quota,
            SUM(total_quota - sold_quota) as remaining
        ')->first();

        return $result ? (array) $result : [
            'total_quota' => 0,
            'sold_quota'  => 0,
            'remaining'   => 0,
        ];
    }

    private function checkInsToday(?int $eventId): int
    {
        return DB::table('check_ins')
            ->where('status', 'success')
            ->whereDate('checked_at', today())
            ->count();
    }

    private function avgRating(?int $eventId): float
    {
        $q = DB::table('reviews')->where('status', 'approved');
        if ($eventId) {
            $q->where('event_id', $eventId);
        }
        return round((float) $q->avg('rating'), 1);
    }

    private function pendingReviews(?int $eventId): int
    {
        $q = DB::table('reviews')->where('status', 'pending');
        if ($eventId) {
            $q->where('event_id', $eventId);
        }
        return $q->count();
    }
}