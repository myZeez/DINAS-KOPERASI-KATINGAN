<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Site\HomeController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\PublicContentController;
use App\Http\Controllers\Admin\StructureController;
use App\Http\Controllers\Admin\ReviewController;
// use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\FileDownloadController;
use App\Http\Controllers\Public\FileDownloadController as PublicFileDownloadController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ForgotPasswordController::class, 'reset'])->name('password.reset.update');

// API Documentation Route
Route::get('/api/docs', function () {
    return redirect('/api/documentation');
})->name('api.docs');

// Public homepage
Route::get('/', [HomeController::class, 'index'])->name('public.home');

// Public pages - sesuai dengan fitur admin
Route::prefix('public')->name('public.')->group(function () {
    Route::get('/struktur', [HomeController::class, 'struktur'])->name('struktur');
    Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
    Route::get('/berita', [HomeController::class, 'berita'])->name('berita');
    Route::get('/berita/{slug}', [HomeController::class, 'beritaDetail'])->name('berita.detail');
    Route::get('/galeri', [HomeController::class, 'galeri'])->name('galeri');
    Route::get('/layanan', [HomeController::class, 'layanan'])->name('layanan');
    Route::get('/layanan/{service:slug}', [HomeController::class, 'layananDetail'])->name('layanan.detail');
    Route::get('/ulasan', [HomeController::class, 'ulasan'])->name('ulasan');
    Route::post('/ulasan', [HomeController::class, 'storeUlasan'])->name('ulasan.store');

    // Downloads - menggunakan HomeController seperti halaman lain
    Route::get('/downloads', [HomeController::class, 'download'])->name('downloads');
    Route::get('/downloads/{fileDownload}/download', [HomeController::class, 'downloadFile'])->name('downloads.download');
});

// Redirect old downloads URL to new URL
Route::get('/downloads', function () {
    return redirect()->route('public.downloads.index');
});

// Admin Routes (requires authentication)
Route::middleware(['auth'])->group(function () {

    // Dashboard Routes
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Personal Profile Routes (semua user bisa akses)
    Route::get('/admin/profile', [AccountController::class, 'profile'])->name('profile');
    Route::put('/admin/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
    Route::put('/admin/password', [AccountController::class, 'updatePassword'])->name('admin.password.update');

    // Super Admin Only Routes
    Route::middleware(['role:super_admin'])->group(function () {
        // User Management Routes
        Route::prefix('admin/users')->name('admin.accounts.')->group(function () {
            Route::get('/', [AccountController::class, 'index'])->name('index');
            Route::post('/', [AccountController::class, 'store'])->name('store');
            Route::put('/{user}', [AccountController::class, 'update'])->name('update');
            Route::delete('/{user}', [AccountController::class, 'destroy'])->name('destroy');
            Route::patch('/{user}/toggle-status', [AccountController::class, 'toggleStatus'])->name('toggle-status');
        });

        // Mail Settings Routes
        Route::prefix('admin/mail-settings')->name('admin.mail-settings.')->group(function () {
            Route::post('/', [AccountController::class, 'storeMailSettings'])->name('store');
            Route::post('/test', [AccountController::class, 'testMailSettings'])->name('test');
        });

        // Activity Log Management Routes (Super Admin Only)
        Route::prefix('admin/activity-logs')->name('admin.activity-logs.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('index');
            Route::get('/{activityLog}', [\App\Http\Controllers\Admin\ActivityLogController::class, 'show'])->name('show');
            Route::delete('/{activityLog}', [\App\Http\Controllers\Admin\ActivityLogController::class, 'destroy'])->name('destroy');
            Route::post('/clear', [\App\Http\Controllers\Admin\ActivityLogController::class, 'clear'])->name('clear');
        });
    });

    // Content Management Routes (admin dan super_admin)
    Route::middleware(['role:admin,super_admin'])->group(function () {

        // News Management Routes
        Route::prefix('admin/news')->name('admin.news.')->group(function () {
            Route::get('/', [NewsController::class, 'index'])->name('index');
            Route::get('/create', [NewsController::class, 'create'])->name('create');
            Route::post('/', [NewsController::class, 'store'])->name('store');
            Route::get('/{news}', [NewsController::class, 'show'])->name('show');
            Route::get('/{news}/edit', [NewsController::class, 'edit'])->name('edit');
            Route::put('/{news}', [NewsController::class, 'update'])->name('update');
            Route::delete('/{news}', [NewsController::class, 'destroy'])->name('destroy');
        });

        // Gallery Management Routes
        Route::prefix('admin/gallery')->name('admin.galleries.')->group(function () {
            Route::get('/', [GalleryController::class, 'index'])->name('index');
            Route::get('/create', [GalleryController::class, 'create'])->name('create');
            Route::post('/', [GalleryController::class, 'store'])->name('store');
            Route::get('/{gallery}', [GalleryController::class, 'show'])->name('show');
            Route::get('/{gallery}/edit', [GalleryController::class, 'edit'])->name('edit');
            Route::put('/{gallery}', [GalleryController::class, 'update'])->name('update');
            Route::delete('/{gallery}', [GalleryController::class, 'destroy'])->name('destroy');
        });

        // Profile Content Management Routes (Dinas Profile)
        Route::prefix('admin/profile-content')->name('admin.profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'index'])->name('index');
            Route::match(['POST', 'PUT'], '/update', [ProfileController::class, 'update'])->name('update');
            Route::post('/update-location', [ProfileController::class, 'updateLocation'])->name('update-location');
            Route::post('/logo', [ProfileController::class, 'uploadLogo'])->name('logo.upload');
            Route::delete('/logo', [ProfileController::class, 'deleteLogo'])->name('logo.delete');
        });

        // Single route untuk backward compatibility
        Route::get('/admin/profile', [ProfileController::class, 'index'])->name('admin.profile');

        // Public Content Management Routes
        Route::prefix('admin/public-content')->name('admin.public-content.')->group(function () {
            Route::get('/', [PublicContentController::class, 'index'])->name('index');
            Route::post('/', [PublicContentController::class, 'store'])->name('store');
            Route::get('/{id}', [PublicContentController::class, 'show'])->name('show');
            Route::put('/{id}', [PublicContentController::class, 'update'])->name('update');
            Route::delete('/{id}', [PublicContentController::class, 'destroy'])->name('destroy');

            // Carousel Routes
            Route::post('/carousel', [PublicContentController::class, 'storeCarousel'])->name('carousel.store');
            Route::get('/carousel/{id}', [PublicContentController::class, 'showCarousel'])->name('carousel.show');
            Route::put('/carousel/{id}', [PublicContentController::class, 'updateCarousel'])->name('carousel.update');
            Route::delete('/carousel/{id}', [PublicContentController::class, 'deleteCarousel'])->name('carousel.destroy');

            // Service Routes
            Route::post('/service', [PublicContentController::class, 'storeService'])->name('service.store');
            Route::get('/service/{id}', [PublicContentController::class, 'showService'])->name('service.show');
            Route::put('/service/{id}', [PublicContentController::class, 'updateService'])->name('service.update');
            Route::delete('/service/{id}', [PublicContentController::class, 'deleteService'])->name('service.destroy');

            // Toggle Status
            Route::patch('/toggle-status', [PublicContentController::class, 'toggleStatus'])->name('toggle-status');

            // Legacy routes (kept for compatibility)
            Route::post('/hero', [PublicContentController::class, 'updateHero'])->name('hero');
            Route::post('/services', [PublicContentController::class, 'updateServices'])->name('services');
        });

        // Structure Management Routes
        Route::prefix('admin/structure')->name('admin.structure.')->group(function () {
            Route::get('/', [StructureController::class, 'index'])->name('index');
            Route::post('/', [StructureController::class, 'store'])->name('store');
            Route::get('/{structure}', [StructureController::class, 'show'])->name('show');
            Route::put('/{structure}', [StructureController::class, 'update'])->name('update');
            Route::delete('/{structure}', [StructureController::class, 'destroy'])->name('destroy');
        });

        // Single route untuk backward compatibility
        Route::get('/admin/structure', [StructureController::class, 'index'])->name('admin.structure');

        // Review Management Routes
        Route::prefix('admin/reviews')->name('admin.reviews.')->group(function () {
            Route::get('/', [ReviewController::class, 'index'])->name('index');
            // Changed all to POST for better hosting compatibility
            Route::post('/{review}/toggle-visibility', [ReviewController::class, 'toggleVisibility'])->name('toggle-visibility');
            Route::post('/{review}/verify', [ReviewController::class, 'verify'])->name('verify');
            Route::post('/{review}/approve', [ReviewController::class, 'approve'])->name('approve');
            Route::post('/{review}/reject', [ReviewController::class, 'reject'])->name('reject');
            Route::post('/{review}/delete', [ReviewController::class, 'destroy'])->name('destroy');
        });

        // Single route untuk backward compatibility
        Route::get('/admin/reviews', [ReviewController::class, 'index'])->name('admin.reviews');

        // Trash Management Routes
        Route::prefix('admin/trash')->name('admin.trash.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\TrashController::class, 'index'])->name('index');
            Route::post('/restore', [\App\Http\Controllers\Admin\TrashController::class, 'restore'])->name('restore');
            Route::post('/force-delete', [\App\Http\Controllers\Admin\TrashController::class, 'forceDelete'])->name('force-delete');
            Route::post('/empty', [\App\Http\Controllers\Admin\TrashController::class, 'emptyTrash'])->name('empty');
        });

        // File Download Management Routes
        Route::prefix('admin/file-downloads')->name('admin.file-downloads.')->group(function () {
            Route::get('/', [FileDownloadController::class, 'index'])->name('index');
            Route::get('/create', [FileDownloadController::class, 'create'])->name('create');
            Route::post('/', [FileDownloadController::class, 'store'])->name('store');
            Route::get('/trash', [FileDownloadController::class, 'trash'])->name('trash');
            Route::get('/{fileDownload}', [FileDownloadController::class, 'show'])->name('show');
            Route::get('/{fileDownload}/edit', [FileDownloadController::class, 'edit'])->name('edit');
            Route::put('/{fileDownload}', [FileDownloadController::class, 'update'])->name('update');
            Route::delete('/{fileDownload}', [FileDownloadController::class, 'destroy'])->name('destroy');
            Route::patch('/{fileDownload}/restore', [FileDownloadController::class, 'restore'])->name('restore');
            Route::delete('/{fileDownload}/force-delete', [FileDownloadController::class, 'forceDelete'])->name('force-delete');
        });

        // Complaint Management Routes removed
    });
});
