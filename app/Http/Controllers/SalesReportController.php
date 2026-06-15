<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'event_id'       => 'nullable|integer',
            'ticket_type_id' => 'nullable|integer',
            'date_from'      => 'nullable|date',
            'date_to'        => 'nullable|date',
        ]);

        $baseQuery = DB::table('orders as o')
            ->join('order_items as oi', 'oi.order_id', '=', 'o.id')
            ->join('ticket_types as tt', 'tt.id', '=', 'oi.ticket_type_id')
            ->where('o.status', 'paid')
            ->when($request->event_id,       fn($q) => $q->where('tt.event_id', $request->event_id))
            ->when($request->ticket_type_id, fn($q) => $q->where('oi.ticket_type_id', $request->ticket_type_id))
            ->when($request->date_from,      fn($q) => $q->whereDate('o.created_at', '>=', $request->date_from))
            ->when($request->date_to,        fn($q) => $q->whereDate('o.created_at', '<=', $request->date_to));

        // Summary
        $summary = (clone $baseQuery)->selectRaw('
            COUNT(DISTINCT o.id)   as total_orders,
            SUM(oi.quantity)       as total_tickets,
            SUM(oi.subtotal)       as total_revenue,
            AVG(o.total_amount)    as avg_order_value
        ')->first();

        // Daily report
        $dailyReport = (clone $baseQuery)
            ->selectRaw('DATE(o.created_at) as date, SUM(oi.quantity) as tickets_sold, COUNT(DISTINCT o.id) as total_orders, SUM(oi.subtotal) as revenue')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        // By ticket type
        $byTicketType = (clone $baseQuery)
            ->selectRaw('oi.ticket_type_id, tt.name as ticket_type_name, SUM(oi.quantity) as tickets_sold, SUM(oi.subtotal) as revenue')
            ->groupBy('oi.ticket_type_id', 'tt.name')
            ->orderBy('revenue', 'desc')
            ->get();

        // Top events
        $topEvents = (clone $baseQuery)
            ->selectRaw('tt.event_id, SUM(oi.quantity) as tickets_sold, SUM(oi.subtotal) as revenue')
            ->groupBy('tt.event_id')
            ->orderBy('revenue', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.sales-report', compact('summary', 'dailyReport', 'byTicketType', 'topEvents'));
    }
}