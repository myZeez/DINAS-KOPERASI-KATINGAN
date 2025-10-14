<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Gallery;
use App\Models\Review;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get real statistics from database
        $newsCount = News::count();
        $galleryCount = Gallery::count();
        $reviewCount = Review::count();

        // Since complaints table was dropped, we'll use reviews with negative ratings as complaints
        $complaintCount = Review::where('rating', '<=', 2)->count(); // Reviews with 1-2 stars considered as complaints

        // Get monthly statistics
        $monthlyNews = News::whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year)
                          ->count();

        $monthlyGallery = Gallery::whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)
                                ->count();

        $monthlyReviews = Review::whereMonth('created_at', now()->month)
                               ->whereYear('created_at', now()->year)
                               ->count();

        $monthlyComplaints = Review::where('rating', '<=', 2)
                                  ->whereMonth('created_at', now()->month)
                                  ->whereYear('created_at', now()->year)
                                  ->count();

        // Header statistics (sample data - would come from analytics in real app)
        $totalViews = 8500;
        $activeUsers = 24;
        $systemUptime = '99.8%';

        return view('admin.dashboard', compact(
            'newsCount',
            'galleryCount',
            'reviewCount',
            'complaintCount',
            'monthlyNews',
            'monthlyGallery',
            'monthlyReviews',
            'monthlyComplaints',
            'totalViews',
            'activeUsers',
            'systemUptime'
        ));
    }
}
