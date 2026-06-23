<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Notifications\AppNotification;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // GET /reviews (admin web)
    public function adminIndex(Request $request)
    {
        $reviews = Review::with('user:id,name,email')
            ->when($request->status,   fn ($q) => $q->where('status', $request->status))
            ->when($request->rating,   fn ($q) => $q->where('rating', $request->rating))
            ->when($request->event_id, fn ($q) => $q->where('event_id', $request->event_id))
            ->latest()
            ->paginate(20);

        $stats = [
            'total'    => Review::count(),
            'pending'  => Review::where('status', 'pending')->count(),
            'approved' => Review::where('status', 'approved')->count(),
            'rejected' => Review::where('status', 'rejected')->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    // PATCH /reviews/{review}/approve
    public function approve(Review $review)
    {
        $review->update([
            'status'      => 'approved',
            'approved_at' => now(),
        ]);

        $review->user->notify(new AppNotification(
            type: 'review_approved',
            message: '⭐ Ulasan kamu telah disetujui dan dipublikasikan!',
            refId: $review->id,
        ));

        return redirect()->route('admin.reviews.index')->with('success', 'Ulasan berhasil disetujui.');
    }

    // PATCH /reviews/{review}/reject
    public function reject(Request $request, Review $review)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $review->update([
            'status'          => 'rejected',
            'rejected_reason' => $request->reason,
        ]);

        // Mancing notif
        $review->user->notify(new AppNotification(
            type: 'review_rejected',
            message: '🚫 Ulasan kamu ditolak. Alasan: ' . $request->reason,
            refId: $review->id,
        ));

        return redirect()->route('admin.reviews.index')->with('success', 'Ulasan berhasil ditolak.');
    }

    // GET /reviews
    public function customerIndex()
    {
        $reviews = Review::where('user_id', auth()->id())->latest()->get();
        return view('reviews.index', compact('reviews'));
    }

    // GET /reviews/create
    public function create()
    {
        $events = \App\Models\Event::where('status', 'active')->get();
        $orders = \App\Models\Order::where('user_id', auth()->id())->where('status', 'paid')->get();
        return view('reviews.create', compact('events', 'orders'));
    }

    // POST /reviews
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|integer',
            'order_id' => 'required|integer',
            'rating'   => 'required|integer|min:1|max:5',
            'title'    => 'required|string|max:255',
            'body'     => 'required|string',
        ]);

        Review::create([
            'user_id'     => auth()->id(),
            'event_id'    => $request->event_id,
            'order_id'    => $request->order_id,
            'rating'      => $request->rating,
            'title'       => $request->title,
            'body'        => $request->body,
            'status'      => 'pending',
            'is_approved' => false,
        ]);

    return redirect()->route('reviews.index')
        ->with('success', 'Ulasan berhasil dikirim, menunggu moderasi admin.');
    }
}