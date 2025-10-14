<?php

namespace App\Http\Controllers\Api;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="News",
 *     description="API untuk mengelola berita"
 * )
 */
class NewsController extends BaseApiController
{
    /**
     * @OA\Get(
     *     path="/api/news",
     *     operationId="getNewsList",
     *     tags={"News"},
     *     summary="Daftar semua berita",
     *     description="Mengambil daftar berita yang dipublikasi",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Nomor halaman",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Jumlah item per halaman",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Kata kunci pencarian",
     *         required=false,
     *         @OA\Schema(type="string", example="koperasi")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil daftar berita",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="news",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/News")
     *                 ),
     *                 @OA\Property(
     *                     property="pagination",
     *                     type="object",
     *                     @OA\Property(property="current_page", type="integer", example=1),
     *                     @OA\Property(property="last_page", type="integer", example=5),
     *                     @OA\Property(property="per_page", type="integer", example=10),
     *                     @OA\Property(property="total", type="integer", example=50)
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');

        $query = News::where('status', 'published')
            ->with('user:id,name')
            ->orderBy('published_at', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $news = $query->paginate($perPage);

        return $this->successResponse([
            'news' => $news->items(),
            'pagination' => [
                'current_page' => $news->currentPage(),
                'last_page' => $news->lastPage(),
                'per_page' => $news->perPage(),
                'total' => $news->total()
            ]
        ], 'Berhasil mengambil daftar berita');
    }

    /**
     * @OA\Get(
     *     path="/api/news/{id}",
     *     operationId="getNewsDetail",
     *     tags={"News"},
     *     summary="Detail berita",
     *     description="Mengambil detail berita berdasarkan ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID berita",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil detail berita",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Success"),
     *             @OA\Property(property="data", ref="#/components/schemas/News")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Berita tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Berita tidak ditemukan")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $news = News::where('status', 'published')
            ->with('user:id,name')
            ->find($id);

        if (!$news) {
            return $this->errorResponse('Berita tidak ditemukan', 404);
        }

        return $this->successResponse($news, 'Berhasil mengambil detail berita');
    }

    /**
     * @OA\Post(
     *     path="/api/admin/news",
     *     operationId="createNews",
     *     tags={"News"},
     *     summary="Buat berita baru",
     *     description="Membuat berita baru (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="title", type="string", example="Judul Berita"),
     *                 @OA\Property(property="content", type="string", example="Konten berita..."),
     *                 @OA\Property(property="status", type="string", enum={"draft", "published"}, example="published"),
     *                 @OA\Property(property="published_at", type="string", format="datetime", example="2025-01-01 12:00:00"),
     *                 @OA\Property(property="image", type="string", format="binary", description="File gambar")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Berita berhasil dibuat",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Berita berhasil dibuat"),
     *             @OA\Property(property="data", ref="#/components/schemas/News")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     type="array",
     *                     @OA\Items(type="string", example="The title field is required.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'status' => 'required|in:draft,published',
                'published_at' => 'nullable|date',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('news', 'public');
            }

            $validated['user_id'] = auth()->id();

            $news = News::create($validated);

            return $this->successResponse($news, 'Berita berhasil dibuat', 201);
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed', 422, $e->errors());
        }
    }
}
