<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Gallery;
use App\Models\Review;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // Get actual dynamic data for header statistics
        $totalViews = $this->calculateTotalViews();
        $activeUsers = $this->getActiveUsersCount();
        $systemUptime = $this->calculateSystemUptime();

        // Get recent activities
        $recentActivities = $this->getRecentActivities();

        // Get notifications
        $notifications = $this->getRecentNotifications();

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
            'systemUptime',
            'recentActivities',
            'notifications'
        ));
    }

    /**
     * Calculate total content views (news + gallery)
     */
    private function calculateTotalViews()
    {
        // For now we'll use a simple calculation based on content count
        // In a real app, you'd track actual page views
        $newsViews = News::count() * 150; // Estimate 150 views per news
        $galleryViews = Gallery::count() * 80; // Estimate 80 views per gallery
        return $newsViews + $galleryViews;
    }

    /**
     * Get count of users who have been active recently
     */
    private function getActiveUsersCount()
    {
        // Users who logged in within the last 30 days
        return User::where('last_login_at', '>=', now()->subDays(30))
                   ->where('is_active', true)
                   ->count();
    }

    /**
     * Calculate system uptime based on activity logs
     */
    private function calculateSystemUptime()
    {
        // Simple uptime calculation based on recent activity
        $totalDays = 30;
        $activeDays = ActivityLog::whereDate('created_at', '>=', now()->subDays($totalDays))
                                ->distinct('created_at')
                                ->count();

        if ($activeDays === 0) {
            return '100%';
        }

        $uptime = ($activeDays / $totalDays) * 100;
        return number_format($uptime, 1) . '%';
    }

    /**
     * Get recent system activities
     */
    private function getRecentActivities()
    {
        return ActivityLog::with('user')
                         ->whereDate('created_at', '>=', now()->subDays(1)) // Last 24 hours
                         ->orderBy('created_at', 'desc')
                         ->take(5)
                         ->get()
                         ->map(function ($activity) {
                             return [
                                 'time' => $activity->created_at->format('H:i'),
                                 'description' => $this->formatActivityDescription($activity),
                                 'status' => $this->getActivityStatus($activity->action),
                                 'badge_class' => $this->getActivityBadgeClass($activity->action)
                             ];
                         });
    }

    /**
     * Format activity description
     */
    private function formatActivityDescription($activity)
    {
        $user = $activity->user ? $activity->user->name : 'System';
        $modelName = class_basename($activity->model_type);

        switch ($activity->action) {
            case 'create':
                return "{$user} menambahkan {$modelName} baru";
            case 'update':
                return "{$user} memperbarui {$modelName}";
            case 'delete':
                return "{$user} menghapus {$modelName}";
            case 'login':
                return "{$user} login ke sistem";
            default:
                return $activity->description ?: "{$user} melakukan aktivitas";
        }
    }

    /**
     * Get activity status
     */
    private function getActivityStatus($action)
    {
        switch ($action) {
            case 'create':
            case 'update':
            case 'login':
                return 'Berhasil';
            case 'delete':
                return 'Dihapus';
            default:
                return 'Selesai';
        }
    }

    /**
     * Get activity badge class
     */
    private function getActivityBadgeClass($action)
    {
        switch ($action) {
            case 'create':
            case 'update':
            case 'login':
                return 'rgba(0, 255, 136, 0.2)';
            case 'delete':
                return 'rgba(255, 107, 107, 0.2)';
            default:
                return 'rgba(255, 215, 61, 0.2)';
        }
    }

    /**
     * Get recent notifications
     */
    private function getRecentNotifications()
    {
        $notifications = collect();

        // Check for new negative reviews (complaints)
        $recentComplaints = Review::where('rating', '<=', 2)
                                 ->whereDate('created_at', '>=', now()->subDays(7))
                                 ->latest()
                                 ->first();

        if ($recentComplaints) {
            $notifications->push([
                'icon' => 'fas fa-exclamation',
                'icon_bg' => 'rgba(255, 107, 107, 0.2)',
                'icon_color' => 'text-danger',
                'title' => 'Review Negatif Baru',
                'time' => $recentComplaints->created_at->diffForHumans()
            ]);
        }

        // Check for new positive reviews
        $recentPositiveReview = Review::where('rating', '>=', 4)
                                    ->whereDate('created_at', '>=', now()->subDays(7))
                                    ->latest()
                                    ->first();

        if ($recentPositiveReview) {
            $notifications->push([
                'icon' => 'fas fa-star',
                'icon_bg' => 'rgba(0, 255, 136, 0.2)',
                'icon_color' => 'text-success',
                'title' => 'Ulasan Positif Baru',
                'time' => $recentPositiveReview->created_at->diffForHumans()
            ]);
        }

        // Check for new content that might need approval
        $recentNews = News::whereDate('created_at', '>=', now()->subDays(1))
                         ->latest()
                         ->first();

        if ($recentNews) {
            $notifications->push([
                'icon' => 'fas fa-newspaper',
                'icon_bg' => 'rgba(255, 215, 61, 0.2)',
                'icon_color' => 'text-warning',
                'title' => 'Berita Baru Ditambahkan',
                'time' => $recentNews->created_at->diffForHumans()
            ]);
        }

        // If no real notifications, show system status
        if ($notifications->isEmpty()) {
            $notifications->push([
                'icon' => 'fas fa-check-circle',
                'icon_bg' => 'rgba(0, 255, 136, 0.2)',
                'icon_color' => 'text-success',
                'title' => 'Sistem Berjalan Normal',
                'time' => 'Real time'
            ]);
        }

        return $notifications->take(3);
    }
}
