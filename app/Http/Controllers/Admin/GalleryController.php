<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $galleries = Gallery::orderBy('created_at', 'desc')->paginate(5);
        return view('admin.galleries.index', compact('galleries'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.galleries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi sederhana
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'image' => 'required|image|max:2048',
            'category' => 'required',
            'tags' => 'nullable',
            'status' => 'required|in:0,1'
        ]);

        // Upload gambar
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('galleries', 'public');
        }

        // Simpan ke database
        Gallery::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'category' => $request->category,
            'tags' => $request->tags,
            'views' => 0,
            'likes' => 0,
            'is_featured' => $request->has('is_featured'),
            'status' => $request->status == '1' ? 'active' : 'inactive',
            'user_id' => 1, // User ID default
        ]);

        return redirect()->route('admin.galleries.index')->with('success', 'Gallery berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $gallery = Gallery::findOrFail($id);
        $relatedGalleries = Gallery::where('id', '!=', $id)
            ->latest()
            ->limit(4)
            ->get();
        return view('admin.galleries.show', compact('gallery', 'relatedGalleries'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $gallery = Gallery::findOrFail($id);
        return view('admin.galleries.edit', compact('gallery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Debug logging
        Log::info('Gallery update started', [
            'id' => $id,
            'data' => $request->all(),
            'has_file' => $request->hasFile('image')
        ]);

        $gallery = Gallery::findOrFail($id);

        // Validasi
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'image' => 'nullable|image|max:2048',
            'category' => 'required',
            'tags' => 'nullable',
        ]);

        // Upload gambar baru jika ada
        $imagePath = $gallery->image;
        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($gallery->image) {
                Storage::disk('public')->delete($gallery->image);
            }
            $imagePath = $request->file('image')->store('galleries', 'public');
        }

        // Update database
        $gallery->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'category' => $request->category,
            'tags' => $request->tags,
            'is_featured' => $request->has('is_featured'),
        ]);

        Log::info('Gallery updated successfully', ['gallery_id' => $gallery->id]);

        return redirect()->route('admin.galleries.index')->with('success', 'Gallery berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gallery = Gallery::findOrFail($id);

        // Hapus file gambar
        if ($gallery->image) {
            Storage::disk('public')->delete($gallery->image);
        }

        $gallery->delete();

        return redirect()->route('admin.galleries.index')->with('success', 'Gallery berhasil dihapus!');
    }
}
