<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Gallery;
use App\Models\Review;
use App\Models\HeroCarousel;
use App\Models\FeaturedService;
use App\Models\PublicContent;
use App\Models\Structure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TrashController extends Controller
{
    public function index(): View
    {
        $trashedNews = News::onlyTrashed()->latest('deleted_at')->get();
        $trashedGalleries = Gallery::onlyTrashed()->latest('deleted_at')->get();
        $trashedReviews = Review::onlyTrashed()->latest('deleted_at')->get();
        $trashedCarousels = HeroCarousel::onlyTrashed()->latest('deleted_at')->get();
        $trashedServices = FeaturedService::onlyTrashed()->latest('deleted_at')->get();
        $trashedContent = PublicContent::onlyTrashed()->latest('deleted_at')->get();
        $trashedStructures = Structure::onlyTrashed()->latest('deleted_at')->get();

        $totalTrash = $trashedNews->count() + $trashedGalleries->count() +
            $trashedReviews->count() + $trashedCarousels->count() +
            $trashedServices->count() + $trashedContent->count() +
            $trashedStructures->count();

        return view('admin.trash.index', compact(
            'trashedNews',
            'trashedGalleries',
            'trashedReviews',
            'trashedCarousels',
            'trashedServices',
            'trashedContent',
            'trashedStructures',
            'totalTrash'
        ));
    }

    public function restore(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|string',
            'id' => 'required|integer'
        ]);

        $model = $this->getModel($request->type);
        if (!$model) {
            return response()->json(['success' => false, 'message' => 'Model tidak ditemukan'], 404);
        }

        $item = $model::onlyTrashed()->find($request->id);
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan'], 404);
        }

        $item->restore();

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil dipulihkan'
        ]);
    }

    public function forceDelete(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|string',
            'id' => 'required|integer'
        ]);

        $model = $this->getModel($request->type);
        if (!$model) {
            return response()->json(['success' => false, 'message' => 'Model tidak ditemukan'], 404);
        }

        $item = $model::onlyTrashed()->find($request->id);
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan'], 404);
        }

        // Delete associated files if any
        if (isset($item->image) && $item->image) {
            $imagePath = storage_path('app/public/' . $item->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $item->forceDelete();

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil dihapus permanen'
        ]);
    }

    public function emptyTrash(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|string'
        ]);

        $model = $this->getModel($request->type);
        if (!$model) {
            return response()->json(['success' => false, 'message' => 'Model tidak ditemukan'], 404);
        }

        $items = $model::onlyTrashed()->get();

        foreach ($items as $item) {
            // Delete associated files
            if (isset($item->image) && $item->image) {
                $imagePath = storage_path('app/public/' . $item->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $item->forceDelete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Semua item berhasil dihapus permanen'
        ]);
    }

    private function getModel(string $type): ?string
    {
        $models = [
            'news' => News::class,
            'gallery' => Gallery::class,
            'review' => Review::class,
            'carousel' => HeroCarousel::class,
            'service' => FeaturedService::class,
            'content' => PublicContent::class,
            'structure' => Structure::class,
        ];

        return $models[$type] ?? null;
    }
}
