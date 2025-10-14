<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\ReviewController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public API Routes
Route::prefix('v1')->group(function () {
    // News API
    Route::get('/news', [NewsController::class, 'index']);
    Route::get('/news/{id}', [NewsController::class, 'show']);

    // Gallery API
    Route::get('/galleries', [GalleryController::class, 'index']);
    Route::get('/galleries/{id}', [GalleryController::class, 'show']);

    // Reviews API
    Route::get('/reviews', [ReviewController::class, 'index']);
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::get('/reviews/statistics', [ReviewController::class, 'statistics']);
});

// Admin API Routes (Protected)
Route::prefix('v1/admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
    // News Management
    Route::post('/news', [NewsController::class, 'store']);
    Route::put('/news/{id}', [NewsController::class, 'update']);
    Route::delete('/news/{id}', [NewsController::class, 'destroy']);

    // Gallery Management
    Route::post('/galleries', [GalleryController::class, 'store']);
    Route::put('/galleries/{id}', [GalleryController::class, 'update']);
    Route::delete('/galleries/{id}', [GalleryController::class, 'destroy']);
});
