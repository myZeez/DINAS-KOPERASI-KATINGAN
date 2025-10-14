<?php

namespace App\Http\Controllers\Api;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Reviews",
 *     description="API untuk mengelola ulasan/review"
 * )
 */
class ReviewController extends BaseApiController
{
    /**
     * @OA\Get(
     *     path="/api/reviews",
     *     operationId="getReviewsList",
     *     tags={"Reviews"},
     *     summary="Daftar ulasan",
     *     description="Mengambil daftar ulasan yang tersedia",
     *     @OA\Parameter(
     *         name="rating",
     *         in="query",
     *         description="Filter berdasarkan rating",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, maximum=5, example=5)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Nomor halaman",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil daftar ulasan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="reviews",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/Review")
     *                 ),
     *                 @OA\Property(
     *                     property="pagination",
     *                     type="object",
     *                     @OA\Property(property="current_page", type="integer", example=1),
     *                     @OA\Property(property="last_page", type="integer", example=2),
     *                     @OA\Property(property="per_page", type="integer", example=10),
     *                     @OA\Property(property="total", type="integer", example=15)
     *                 ),
     *                 @OA\Property(
     *                     property="statistics",
     *                     type="object",
     *                     @OA\Property(property="average_rating", type="number", format="float", example=4.5),
     *                     @OA\Property(property="total_reviews", type="integer", example=15)
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $rating = $request->get('rating');

        $query = Review::orderBy('created_at', 'desc');

        if ($rating) {
            $query->where('rating', $rating);
        }

        $reviews = $query->paginate($perPage);

        // Calculate statistics
        $averageRating = Review::avg('rating');
        $totalReviews = Review::count();

        return $this->successResponse([
            'reviews' => $reviews->items(),
            'pagination' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total()
            ],
            'statistics' => [
                'average_rating' => round($averageRating, 1),
                'total_reviews' => $totalReviews
            ]
        ], 'Berhasil mengambil daftar ulasan');
    }

    /**
     * @OA\Post(
     *     path="/api/reviews",
     *     operationId="createReview",
     *     tags={"Reviews"},
     *     summary="Buat ulasan baru",
     *     description="Mengirim ulasan/review baru",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=5),
     *             @OA\Property(property="message", type="string", example="Pelayanan sangat memuaskan")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Ulasan berhasil dikirim",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Ulasan berhasil dikirim"),
     *             @OA\Property(property="data", ref="#/components/schemas/Review")
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
     *                     property="rating",
     *                     type="array",
     *                     @OA\Items(type="string", example="The rating field is required.")
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
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'rating' => 'required|integer|min:1|max:5',
                'message' => 'required|string|max:1000'
            ]);

            $review = Review::create($validated);

            return $this->successResponse($review, 'Ulasan berhasil dikirim', 201);
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed', 422, $e->errors());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/reviews/statistics",
     *     operationId="getReviewsStatistics",
     *     tags={"Reviews"},
     *     summary="Statistik ulasan",
     *     description="Mengambil statistik rating dan ulasan",
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil statistik ulasan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="average_rating", type="number", format="float", example=4.5),
     *                 @OA\Property(property="total_reviews", type="integer", example=100),
     *                 @OA\Property(
     *                     property="rating_breakdown",
     *                     type="object",
     *                     @OA\Property(property="5_stars", type="integer", example=60),
     *                     @OA\Property(property="4_stars", type="integer", example=25),
     *                     @OA\Property(property="3_stars", type="integer", example=10),
     *                     @OA\Property(property="2_stars", type="integer", example=3),
     *                     @OA\Property(property="1_star", type="integer", example=2)
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function statistics()
    {
        $averageRating = Review::avg('rating');
        $totalReviews = Review::count();

        $ratingBreakdown = [
            '5_stars' => Review::where('rating', 5)->count(),
            '4_stars' => Review::where('rating', 4)->count(),
            '3_stars' => Review::where('rating', 3)->count(),
            '2_stars' => Review::where('rating', 2)->count(),
            '1_star' => Review::where('rating', 1)->count(),
        ];

        return $this->successResponse([
            'average_rating' => round($averageRating, 1),
            'total_reviews' => $totalReviews,
            'rating_breakdown' => $ratingBreakdown
        ], 'Berhasil mengambil statistik ulasan');
    }
}
