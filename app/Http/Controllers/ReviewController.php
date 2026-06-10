<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * GET /api/reviews
     * List ulasan publik yang sudah approved.
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'event_id' => 'nullable|integer',
            'rating'   => 'nullable|integer|min:1|max:5',
            'per_page' => 'nullable|integer|max:50',
        ]);

        $reviews = Review::approved()
            ->with('user:id,name')
            ->when($request->event_id, fn ($q) => $q->where('event_id', $request->event_id))
            ->when($request->rating,   fn ($q) => $q->where('rating', $request->rating))
            ->latest()
            ->paginate($request->per_page ?? 15);

        $reviews->getCollection()->transform(function (Review $r) {
            $r->display_name = $r->display_name;
            if ($r->is_anonymous ?? false) {
                unset($r->user_id);
            }
            return $r;
        });

        return response()->json(['success' => true, 'data' => $reviews]);
    }

    /**
     * POST /api/reviews
     * Customer submit ulasan.
     * Verifikasi: user harus pernah beli tiket event ini.
     */
    public function store(ReviewRequest $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        // Verifikasi user pernah beli tiket event ini
        // order_items tidak punya event_id langsung, harus join ticket_types untuk cek event_id
        $hasBought = DB::table('orders as o')
            ->join('order_items as oi', 'oi.order_id', '=', 'o.id')
            ->join('ticket_types as tt', 'tt.id', '=', 'oi.ticket_type_id')
            ->where('o.user_id', $user->id)
            ->where('o.status', 'paid')
            ->where('tt.event_id', $request->event_id)
            ->exists();

        if (! $hasBought) {
            return response()->json([
                'success' => false,
                'message' => 'Anda hanya bisa memberi ulasan untuk event yang tiketnya sudah dibeli.',
            ], 403);
        }

        $review = Review::create([
            ...$request->validated(),
            'user_id' => $user->id,
            'status'  => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ulasan berhasil dikirim dan menunggu moderasi.',
            'data'    => $review,
        ], 201);
    }

    /**
     * GET /api/reviews/{review}
     */
    public function show(Review $review): JsonResponse
    {
        if ($review->status !== 'approved') {
            abort(404);
        }
        $review->load('user:id,name');
        return response()->json(['success' => true, 'data' => $review]);
    }

    /**
     * GET /api/admin/reviews — Admin lihat semua ulasan
     */
    public function adminIndex(Request $request): JsonResponse
    {
        $reviews = Review::with('user:id,name,email')
            ->when($request->status,   fn ($q) => $q->where('status', $request->status))
            ->when($request->event_id, fn ($q) => $q->where('event_id', $request->event_id))
            ->latest()
            ->paginate(20);

        return response()->json(['success' => true, 'data' => $reviews]);
    }

    /**
     * PATCH /api/reviews/{review}/approve
     */
    public function approve(Review $review): JsonResponse
    {
        $review->update([
            'status'      => 'approved',
            'approved_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ulasan disetujui.',
            'data'    => $review,
        ]);
    }

    /**
     * PATCH /api/reviews/{review}/reject
     */
    public function reject(Request $request, Review $review): JsonResponse
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $review->update([
            'status'          => 'rejected',
            'rejected_reason' => $request->reason,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ulasan ditolak.',
            'data'    => $review,
        ]);
    }

    /**
     * GET /api/reviews/event/{eventId}/summary
     */
    public function eventSummary(int $eventId): JsonResponse
    {
        $summary = Review::approved()
            ->where('event_id', $eventId)
            ->selectRaw('
                COUNT(*) as total_reviews,
                ROUND(AVG(rating), 1) as avg_rating,
                SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as star_5,
                SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as star_4,
                SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as star_3,
                SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as star_2,
                SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as star_1
            ')
            ->first();

        return response()->json(['success' => true, 'data' => $summary]);
    }
}