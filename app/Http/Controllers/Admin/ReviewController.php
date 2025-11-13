<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        // Aggregates from DB
        $totalReviews = Review::query()->count();
        $pendingReviews = Review::query()->where('status', 'pending')->count();
        $averageRating = round((float) Review::query()->where('rating', '>', 0)->avg('rating'), 1);

        // Filters (optional in future)
        $reviews = Review::query()
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.reviews.index', [
            'totalReviews' => $totalReviews,
            'pendingReviews' => $pendingReviews,
            'averageRating' => $averageRating,
            'reviews' => $reviews,
        ]);
    }

    public function toggleVisibility(Review $review)
    {
        $review->is_visible = !$review->is_visible;
        $review->save();

        if (request()->ajax() || request()->wantsJson() || request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status tampilan ulasan berhasil diubah.'
            ], 200, ['Content-Type' => 'application/json']);
        }
        return back()->with('success', 'Status tampilan ulasan berhasil diubah.');
    }

    public function verify(Review $review)
    {
        $review->is_verified = true;
        $review->save();

        if (request()->ajax()) {
            return response()->json(['message' => 'Ulasan berhasil diverifikasi.']);
        }
        return back()->with('success', 'Ulasan berhasil diverifikasi.');
    }

    public function approve(Review $review)
    {
        $review->status = 'approved';
        $review->is_visible = true;
        $review->save();

        if (request()->ajax() || request()->wantsJson() || request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Ulasan berhasil disetujui.'
            ], 200, ['Content-Type' => 'application/json']);
        }
        return back()->with('success', 'Ulasan berhasil disetujui.');
    }

    public function reject(Review $review)
    {
        $review->status = 'rejected';
        $review->is_visible = false;
        $review->save();

        if (request()->ajax() || request()->wantsJson() || request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Ulasan berhasil ditolak.'
            ], 200, ['Content-Type' => 'application/json']);
        }
        return back()->with('success', 'Ulasan berhasil ditolak.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        if (request()->ajax() || request()->wantsJson() || request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Ulasan berhasil dihapus.'
            ], 200, ['Content-Type' => 'application/json']);
        }
        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
