<?php

namespace App\Http\Controllers\Api;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Galleries",
 *     description="API untuk mengelola galeri foto"
 * )
 */
class GalleryController extends BaseApiController
{
    /**
     * @OA\Get(
     *     path="/api/galleries",
     *     operationId="getGalleriesList",
     *     tags={"Galleries"},
     *     summary="Daftar galeri foto",
     *     description="Mengambil daftar foto galeri yang aktif",
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Filter berdasarkan kategori",
     *         required=false,
     *         @OA\Schema(type="string", enum={"kegiatan", "rapat", "acara", "fasilitas"})
     *     ),
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
     *         @OA\Schema(type="integer", example=12)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil daftar galeri",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="galleries",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/Gallery")
     *                 ),
     *                 @OA\Property(
     *                     property="pagination",
     *                     type="object",
     *                     @OA\Property(property="current_page", type="integer", example=1),
     *                     @OA\Property(property="last_page", type="integer", example=3),
     *                     @OA\Property(property="per_page", type="integer", example=12),
     *                     @OA\Property(property="total", type="integer", example=36)
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 12);
        $category = $request->get('category');

        $query = Gallery::where('status', 1)
            ->orderBy('created_at', 'desc');

        if ($category) {
            $query->where('category', $category);
        }

        $galleries = $query->paginate($perPage);

        return $this->successResponse([
            'galleries' => $galleries->items(),
            'pagination' => [
                'current_page' => $galleries->currentPage(),
                'last_page' => $galleries->lastPage(),
                'per_page' => $galleries->perPage(),
                'total' => $galleries->total()
            ]
        ], 'Berhasil mengambil daftar galeri');
    }

    /**
     * @OA\Get(
     *     path="/api/galleries/{id}",
     *     operationId="getGalleryDetail",
     *     tags={"Galleries"},
     *     summary="Detail foto galeri",
     *     description="Mengambil detail foto galeri berdasarkan ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID galeri",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil detail galeri",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Success"),
     *             @OA\Property(property="data", ref="#/components/schemas/Gallery")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Foto tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Foto tidak ditemukan")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $gallery = Gallery::where('status', 1)->find($id);

        if (!$gallery) {
            return $this->errorResponse('Foto tidak ditemukan', 404);
        }

        return $this->successResponse($gallery, 'Berhasil mengambil detail galeri');
    }

    /**
     * @OA\Post(
     *     path="/api/admin/galleries",
     *     operationId="createGallery",
     *     tags={"Galleries"},
     *     summary="Upload foto baru",
     *     description="Upload foto baru ke galeri (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="title", type="string", example="Judul Foto"),
     *                 @OA\Property(property="description", type="string", example="Deskripsi foto..."),
     *                 @OA\Property(property="category", type="string", enum={"kegiatan", "rapat", "acara", "fasilitas"}, example="kegiatan"),
     *                 @OA\Property(property="tags", type="string", example="koperasi,kegiatan,meeting"),
     *                 @OA\Property(property="status", type="integer", enum={0, 1}, example=1),
     *                 @OA\Property(property="is_featured", type="boolean", example=false),
     *                 @OA\Property(property="image", type="string", format="binary", description="File foto")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Foto berhasil diupload",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Foto berhasil diupload"),
     *             @OA\Property(property="data", ref="#/components/schemas/Gallery")
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
     *                     property="image",
     *                     type="array",
     *                     @OA\Items(type="string", example="The image field is required.")
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
                'description' => 'nullable|string',
                'category' => 'required|in:kegiatan,rapat,acara,fasilitas',
                'tags' => 'nullable|string',
                'status' => 'required|boolean',
                'is_featured' => 'boolean',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120'
            ]);

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('galleries', 'public');
            }

            $gallery = Gallery::create($validated);

            return $this->successResponse($gallery, 'Foto berhasil diupload', 201);
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed', 422, $e->errors());
        }
    }
}
