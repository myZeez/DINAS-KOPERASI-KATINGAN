<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Services\BasicImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    protected $imageCompressionService;

    public function __construct(BasicImageCompressionService $imageCompressionService)
    {
        $this->imageCompressionService = $imageCompressionService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil data dari database dengan pagination
        $news = News::with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'excerpt' => Str::limit(strip_tags($item->content), 150),
                    'content' => $item->content,
                    'image' => $item->image ? asset('storage/' . $item->image) : 'https://via.placeholder.com/300x200/4CAF50/FFFFFF?text=' . urlencode(Str::limit($item->title, 20)),
                    'status' => $item->status,
                    'views' => rand(100, 2000), // Sementara random, nanti bisa ditambah kolom views
                    'published_at' => $item->published_at ? $item->published_at->format('Y-m-d') : null,
                    'created_at' => $item->created_at->format('Y-m-d'),
                    'author' => $item->user ? $item->user->name : 'Admin'
                ];
            });

        // Hitung jumlah berita yang di-trash
        $trashedNewsCount = News::onlyTrashed()->count();

        return view('admin.news.index', compact('news', 'trashedNewsCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.news.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB max
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->all();

        // Handle image upload with compression
        if ($request->hasFile('image')) {
            $data['image'] = $this->imageCompressionService->compressAndSave(
                $request->file('image'),
                'news'
            );
        }

        // Set published_at if status is published and no date is set
        if ($data['status'] === 'published' && !$data['published_at']) {
            $data['published_at'] = now();
        }

        // Use authenticated user ID or default to 1
        $userId = Auth::check() ? Auth::id() : 1;
        $data['user_id'] = $userId;

        News::create($data);

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $news = News::with('user')->findOrFail($id);
        return view('admin.news.show', compact('news'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB max
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->all();

        // Handle image upload with compression
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            $data['image'] = $this->imageCompressionService->compressAndSave(
                $request->file('image'),
                'news'
            );
        }

        // Set published_at if status is published and no date is set
        if ($data['status'] === 'published' && !$data['published_at']) {
            $data['published_at'] = now();
        }

        $news->update($data);

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        // Delete image if exists
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil dihapus!');
    }
}
