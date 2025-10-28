<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\HeroCarousel;
use App\Models\FeaturedService;
use App\Models\News;
use App\Models\Gallery;
use App\Models\Review;
use App\Models\Structure;
use App\Models\PublicContent;
use App\Models\FileDownload;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class HomeController extends Controller
{
    public function index(): View
    {
        $profile = Profile::first();
        $carousels = HeroCarousel::active()->ordered()->get();
        $services = FeaturedService::active()->ordered()->take(6)->get();
        $latestNews = News::published()->latest('published_at')->take(3)->get();
        $galleries = Gallery::active()->latest()->take(6)->get();

        // Get Kepala Dinas from structure table
        $kepalaDinas = Structure::where('position', 'like', '%kepala dinas%')
                               ->first();        // PublicContent for Home hero (simple: Title + Subtitle)
        $heroMain = PublicContent::active()
            ->whereIn('section_name', ['HERO_MAIN', 'Judul Utama', 'HOME_TITLE', 'hero_main'])
            ->orderBy('id', 'desc')
            ->first();
        $heroSub = PublicContent::active()
            ->whereIn('section_name', ['HERO_SUBTITLE', 'HERO_SUB', 'Sub Judul', 'HOME_SUBTITLE', 'hero_secondary'])
            ->orderBy('id', 'desc')
            ->first();
        $heroTitle = $heroMain ? ($heroMain->content ?: ($heroMain->title ?: null)) : null;
        $heroSubtitle = $heroSub ? ($heroSub->content ?: ($heroSub->title ?: null)) : null;

        // Reviews: 3 terbaik (rating tertinggi, terbaru lebih dulu), dan 3 terbaru (tanpa duplikasi dengan terbaik)
        $topReviews = Review::query()
            ->where('is_visible', true)
            ->where('status', 'approved')
            ->orderByDesc('rating')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        $latestReviews = Review::query()
            ->where('is_visible', true)
            ->where('status', 'approved')
            ->whereNotIn('id', $topReviews->pluck('id'))
            ->latest()
            ->take(3)
            ->get();

        return view('public.home', compact(
            'profile',
            'carousels',
            'services',
            'latestNews',
            'galleries',
            'topReviews',
            'latestReviews',
            'heroTitle',
            'heroSubtitle',
            'kepalaDinas'
        ));
    }

    public function struktur(): View
    {
        $profile = Profile::first();
        $structures = Structure::with('pltFromStructure')->active()->ordered()->get();

        return view('public.struktur', compact('profile', 'structures'));
    }

    public function berita(Request $request): View
    {
        $profile = Profile::first();
        $news = News::published()
            ->when($request->search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            })
            ->latest('published_at')
            ->paginate(12);

        return view('public.berita', compact('profile', 'news'));
    }

    public function beritaDetail(News $news): View
    {
        $profile = Profile::first();
        $relatedNews = News::published()
            ->where('id', '!=', $news->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('public.berita-detail', compact('profile', 'news', 'relatedNews'));
    }

    public function galeri(Request $request): View
    {
        $profile = Profile::first();
        $galleries = Gallery::active()
            ->when($request->search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(24);

        return view('public.galeri', compact('profile', 'galleries'));
    }

    public function layanan(): View
    {
        $profile = Profile::first();
        $services = FeaturedService::active()->ordered()->get();
        $pcServicesIntro = PublicContent::active()->bySection('Pengantar Layanan')->first();

        return view('public.layanan', compact('profile', 'services', 'pcServicesIntro'));
    }

    public function layananDetail(FeaturedService $service): View
    {
        $profile = Profile::first();
        return view('public.layanan-detail', compact('profile', 'service'));
    }

    public function ulasan(): View
    {
        $profile = Profile::first();
        $reviews = Review::where('is_visible', true)
            ->where('status', 'approved')
            ->latest()
            ->paginate(12);

        return view('public.ulasan', compact('profile', 'reviews'));
    }

    public function profile(): View
    {
        $profile = Profile::first();
        $pcAbout = PublicContent::active()->bySection('Tentang Kami')->first();
        $pcContactInfo = PublicContent::active()->bySection('Informasi Kontak')->first();

        return view('public.profile', compact('profile', 'pcAbout', 'pcContactInfo'));
    }

    public function download(Request $request): View
    {
        $profile = Profile::first();
        $search = $request->get('search');

        $files = FileDownload::when($search, function ($query, $search) {
            return $query->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('original_filename', 'like', "%{$search}%");
        })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('public.download', compact('profile', 'files', 'search'));
    }

    public function downloadFile(FileDownload $fileDownload): BinaryFileResponse
    {
        try {
            $filePath = storage_path('app/public/' . $fileDownload->file_path);

            if (!file_exists($filePath)) {
                abort(404, 'File tidak ditemukan');
            }

            // Increment download count
            $fileDownload->increment('download_count');

            return response()->download($filePath, $fileDownload->original_filename);
        } catch (\Exception $e) {
            abort(500, 'Gagal mengunduh file');
        }
    }

    public function storeUlasan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ], [
            'name.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'rating.required' => 'Rating wajib dipilih',
            'rating.min' => 'Rating minimal 1 bintang',
            'rating.max' => 'Rating maksimal 5 bintang',
            'comment.required' => 'Ulasan & testimoni wajib diisi',
            'comment.max' => 'Ulasan maksimal 1000 karakter',
        ]);

        try {
            Review::create([
                'name' => $request->name,
                'email' => $request->email,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'is_visible' => false, // Default hidden, admin review dulu
                'is_verified' => false,
                'status' => 'pending',
            ]);

            return back()->with('success', 'Terima kasih! Ulasan Anda telah dikirim dan akan ditampilkan setelah diverifikasi oleh admin.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengirim ulasan. Silakan coba lagi.')->withInput();
        }
    }
}
