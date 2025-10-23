<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroCarousel;
use App\Models\FeaturedService;
use App\Models\PublicContent;
use App\Models\Gallery;
use App\Models\News;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PublicContentController extends Controller
{
    /**
     * Display the public content management page
     *
     * @return View
     */
    public function index(): View
    {
        // Get data from database
        $carousels = HeroCarousel::orderBy('sort_order')->get();
        $services = FeaturedService::orderBy('sort_order')->get();
        $publicContents = PublicContent::active()->get();

        $totalCarousels = $carousels->count();
        $activeCarousels = $carousels->where('is_active', true)->count();
        $totalServices = $services->count();
        $activeServices = $services->where('is_active', true)->count();

        // Get real statistics from database
        $totalGalleries = Gallery::count();
        $totalNews = News::count();
        $totalUsers = User::count();
        $totalPublicContents = $publicContents->count();
        $activeContent = $activeCarousels + $activeServices + $totalNews + $totalPublicContents;

        $data = [
            // Real statistics from database
            'statistics' => [
                'total_users' => $totalUsers,
                'active_content' => $activeContent,
                'total_galleries' => $totalGalleries,
                'total_news' => $totalNews
            ],

            // Dynamic counters from database
            'totalCarousels' => $totalCarousels,
            'activeCarousels' => $activeCarousels,
            'totalServices' => $totalServices,
            'activeServices' => $activeServices,
            'totalGalleries' => $totalGalleries,
            'totalNews' => $totalNews,
            'totalPublicContents' => $totalPublicContents,

            // Data from database
            'carousels' => $carousels,
            'services' => $services,
            'publicContents' => $publicContents
        ];

        // Add derived data
        $data['heroCarousels'] = $data['carousels']->where('is_active', true);
        $data['featuredServices'] = $data['services']->where('is_active', true);

        return view('admin.public-content.index', $data);
    }

    /**
     * Store a new public content item
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'section_name' => 'required|string|in:hero_main,hero_secondary',
                'title' => 'nullable|string|max:255',
                'content' => 'nullable|string',
                'settings' => 'nullable|string',
                'is_active' => 'boolean'
            ]);

            $data = [
                'section_name' => $request->section_name,
                'title' => $request->title,
                'content' => $request->content,
                'is_active' => $request->has('is_active')
            ];

            // Parse settings JSON
            if ($request->settings) {
                $settings = json_decode($request->settings, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data['settings'] = $settings;
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Format JSON settings tidak valid'
                    ], 422);
                }
            }

            $content = PublicContent::create($data);

            Log::info('Public content created successfully', ['content_id' => $content->id]);

            return response()->json([
                'success' => true,
                'message' => 'Konten publik berhasil ditambahkan',
                'data' => $content
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating public content', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan konten'
            ], 500);
        }
    }

    /**
     * Store a new carousel item
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storeCarousel(Request $request): JsonResponse
    {
        Log::info('Carousel store request received', $request->all());

        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'subtitle' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'button_text' => 'nullable|string|max:100',
                'button_link' => 'nullable|string|max:255'
            ]);

            Log::info('Validation passed');

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                Log::info('Image file found, processing upload');
                $imagePath = $request->file('image')->store('carousels', 'public');
                Log::info('Image stored at: ' . $imagePath);
            }

            $carouselData = [
                'title' => $request->title,
                'subtitle' => $request->subtitle,
                'image' => $imagePath,
                'button_text' => $request->button_text,
                'button_link' => $request->button_link,
                'sort_order' => HeroCarousel::max('sort_order') + 1,
                'is_active' => true
            ];

            Log::info('Creating carousel with data:', $carouselData);

            $carousel = HeroCarousel::create($carouselData);

            Log::info('Carousel created successfully', ['id' => $carousel->id]);

            return response()->json([
                'success' => true,
                'message' => 'Carousel berhasil ditambahkan',
                'data' => $carousel
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error storing carousel: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show a specific carousel item
     *
     * @param int $id
     * @return JsonResponse
     */
    public function showCarousel(int $id): JsonResponse
    {
        try {
            $carousel = HeroCarousel::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $carousel
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Carousel tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update an existing carousel item
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateCarousel(Request $request, int $id): JsonResponse
    {
        Log::info('Carousel update request received', ['id' => $id, 'data' => $request->all()]);

        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'subtitle' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'button_text' => 'nullable|string|max:100',
                'button_link' => 'nullable|string|max:255'
            ]);

            $carousel = HeroCarousel::findOrFail($id);

            $updateData = [
                'title' => $request->title,
                'subtitle' => $request->subtitle,
                'button_text' => $request->button_text,
                'button_link' => $request->button_link
            ];

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($carousel->image && Storage::disk('public')->exists($carousel->image)) {
                    Storage::disk('public')->delete($carousel->image);
                }
                $updateData['image'] = $request->file('image')->store('carousels', 'public');
            }

            $carousel->update($updateData);

            Log::info('Carousel updated successfully', ['id' => $carousel->id]);

            return response()->json([
                'success' => true,
                'message' => 'Carousel berhasil diperbarui',
                'data' => $carousel
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error on carousel update: ', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating carousel: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a carousel item
     *
     * @param int $id
     * @return JsonResponse
     */
    public function deleteCarousel(int $id): JsonResponse
    {
        try {
            $carousel = HeroCarousel::findOrFail($id);

            // Delete associated image
            if ($carousel->image && Storage::disk('public')->exists($carousel->image)) {
                Storage::disk('public')->delete($carousel->image);
            }

            $carousel->delete();

            return response()->json([
                'success' => true,
                'message' => 'Carousel berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus carousel',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show a specific service item
     *
     * @param int $id
     * @return JsonResponse
     */
    public function showService(int $id): JsonResponse
    {
        try {
            $service = FeaturedService::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $service
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Service tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Store a new service item
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storeService(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'service_category' => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'content_detail' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'service_status' => 'required|in:active,inactive,maintenance',
            'sort_order' => 'nullable|integer|min:1|max:100',
            // Requirements
            'requirements' => 'nullable|string',
            'required_documents' => 'nullable|string',
            'important_notes' => 'nullable|string',
            // Procedure and cost
            'procedure_steps' => 'nullable|string',
            'service_fee' => 'nullable|numeric|min:0',
            'processing_time' => 'nullable|integer|min:1',
            'processing_time_unit' => 'nullable|in:hari,minggu,bulan',
            'service_hours' => 'nullable|string',
            'service_location' => 'nullable|string',
            // Contact information
            'responsible_person' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            // Links
            'external_link' => 'nullable|url|max:255',
            'form_download_link' => 'nullable|url|max:255',
            'tutorial_link' => 'nullable|url|max:255'
        ]);

        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('services', 'public');
            }

            $service = FeaturedService::create([
                'title' => $request->title,
                'service_category' => $request->service_category,
                'description' => $request->description,
                'content_detail' => $request->content_detail,
                'image' => $imagePath,
                'service_status' => $request->service_status ?? 'active',
                'sort_order' => $request->sort_order ?? (FeaturedService::max('sort_order') + 1),
                'is_active' => $request->service_status === 'active',
                // Requirements
                'requirements' => $request->requirements,
                'required_documents' => $request->required_documents,
                'important_notes' => $request->important_notes,
                // Procedure and cost
                'procedure_steps' => $request->procedure_steps,
                'service_fee' => $request->service_fee ?? 0,
                'processing_time' => $request->processing_time,
                'processing_time_unit' => $request->processing_time_unit ?? 'hari',
                'service_hours' => $request->service_hours,
                'service_location' => $request->service_location,
                // Contact information
                'responsible_person' => $request->responsible_person,
                'phone_number' => $request->phone_number,
                'contact_email' => $request->contact_email,
                // Links
                'external_link' => $request->external_link,
                'form_download_link' => $request->form_download_link,
                'tutorial_link' => $request->tutorial_link
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Layanan berhasil ditambahkan',
                'data' => $service
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambah layanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing service item
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateService(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'service_category' => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'content_detail' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'service_status' => 'required|in:active,inactive,maintenance',
            'sort_order' => 'nullable|integer|min:1|max:100',
            // Requirements
            'requirements' => 'nullable|string',
            'required_documents' => 'nullable|string',
            'important_notes' => 'nullable|string',
            // Procedure and cost
            'procedure_steps' => 'nullable|string',
            'service_fee' => 'nullable|numeric|min:0',
            'processing_time' => 'nullable|integer|min:1',
            'processing_time_unit' => 'nullable|in:hari,minggu,bulan',
            'service_hours' => 'nullable|string',
            'service_location' => 'nullable|string',
            // Contact information
            'responsible_person' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            // Links
            'external_link' => 'nullable|url|max:255',
            'form_download_link' => 'nullable|url|max:255',
            'tutorial_link' => 'nullable|url|max:255'
        ]);

        try {
            $service = FeaturedService::findOrFail($id);

            $imagePath = $service->image; // Keep existing image by default

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($service->image && Storage::disk('public')->exists($service->image)) {
                    Storage::disk('public')->delete($service->image);
                }

                // Store new image
                $imagePath = $request->file('image')->store('services', 'public');
            }

            $service->update([
                'title' => $request->title,
                'service_category' => $request->service_category,
                'description' => $request->description,
                'content_detail' => $request->content_detail,
                'image' => $imagePath,
                'service_status' => $request->service_status,
                'sort_order' => $request->sort_order,
                'is_active' => $request->service_status === 'active',
                // Requirements
                'requirements' => $request->requirements,
                'required_documents' => $request->required_documents,
                'important_notes' => $request->important_notes,
                // Procedure and cost
                'procedure_steps' => $request->procedure_steps,
                'service_fee' => $request->service_fee ?? 0,
                'processing_time' => $request->processing_time,
                'processing_time_unit' => $request->processing_time_unit ?? 'hari',
                'service_hours' => $request->service_hours,
                'service_location' => $request->service_location,
                // Contact information
                'responsible_person' => $request->responsible_person,
                'phone_number' => $request->phone_number,
                'contact_email' => $request->contact_email,
                // Links
                'external_link' => $request->external_link,
                'form_download_link' => $request->form_download_link,
                'tutorial_link' => $request->tutorial_link
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Layanan berhasil diperbarui',
                'data' => $service
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui layanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a service item
     *
     * @param int $id
     * @return JsonResponse
     */
    public function deleteService(int $id): JsonResponse
    {
        try {
            $service = FeaturedService::findOrFail($id);
            $service->delete();

            return response()->json([
                'success' => true,
                'message' => 'Layanan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus layanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle status of carousel, service, or public content item
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function toggleStatus(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|string|in:carousel,service,public-content',
            'id' => 'required|integer',
            'status' => 'required|string|in:active,inactive'
        ]);

        try {
            $isActive = $request->status === 'active';

            if ($request->type === 'carousel') {
                $item = HeroCarousel::findOrFail($request->id);
                $item->update(['is_active' => $isActive]);
            } elseif ($request->type === 'service') {
                $item = FeaturedService::findOrFail($request->id);
                $item->update(['is_active' => $isActive]);
            } else {
                $item = PublicContent::findOrFail($request->id);
                $item->update(['is_active' => $isActive]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui',
                'data' => [
                    'type' => $request->type,
                    'id' => $request->id,
                    'status' => $request->status
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show a specific public content item
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $content = PublicContent::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $content
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Konten tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update a public content item
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'section_name' => 'required|string|in:hero_main,hero_secondary',
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        try {
            $content = PublicContent::findOrFail($id);

            $content->update([
                'section_name' => $request->section_name,
                'title' => $request->title,
                'content' => $request->content,
                'is_active' => $request->has('is_active')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Konten berhasil diperbarui',
                'data' => $content
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui konten',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a public content item
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $content = PublicContent::findOrFail($id);
            $content->delete();

            return response()->json([
                'success' => true,
                'message' => 'Konten berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus konten',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
