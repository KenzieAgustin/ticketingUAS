<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Review;
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

        return redirect()->route('admin.reviews.index')->with('success', 'Ulasan berhasil ditolak.');
    }
}