@extends('public.layouts.app')

@section('title', 'Berita & Informasi')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <div class="hero-content">
                        <div class="hero-badge mb-3">
                            <i class="fas fa-newspaper me-2"></i>
                            Pusat Informasi
                        </div>
                        <h1 class="hero-title mb-4">Berita & Informasi</h1>
                        <p class="hero-subtitle mb-4">
                            Tetap update dengan berita terbaru, pengumuman, dan informasi penting dari kami
                        </p>

                        <!-- Search Form -->
                        <form method="GET" class="search-form">
                            <div class="search-container">
                                <input type="text" name="search" class="search-input"
                                    placeholder="Cari berita atau informasi..." value="{{ request('search') }}">
                                <button class="search-btn" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- News Section -->
    <section class="section-modern scroll-reveal">
        <div class="container">
            @if ($news->count() > 0)
                <div class="row g-4">
                    @foreach ($news as $item)
                        <div class="col-md-6 col-lg-4">
                            <div class="news-card">
                                <div class="news-card-header">
                                    @if ($item->image)
                                        <div class="news-image">
                                            <img src="{{ asset('storage/' . $item->image) }}" class="news-img"
                                                alt="{{ $item->title }}">
                                            <div class="news-badge">
                                                <span class="badge-text">Berita</span>
                                            </div>
                                            <div class="news-date">
                                                <i class="far fa-calendar me-1"></i>
                                                {{ $item->published_at->format('d M Y') }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="news-placeholder">
                                            <i class="fas fa-newspaper"></i>
                                            <div class="news-badge">
                                                <span class="badge-text">Berita</span>
                                            </div>
                                            <div class="news-date">
                                                <i class="far fa-calendar me-1"></i>
                                                {{ $item->published_at->format('d M Y') }}
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="news-card-body">
                                    <h5 class="news-title">{{ $item->title }}</h5>
                                    <p class="news-description">{{ Str::limit(strip_tags($item->content), 120) }}</p>

                                    <div class="news-action">
                                        <a href="{{ route('public.berita.detail', $item->slug) }}" class="news-btn">
                                            <span>Baca Selengkapnya</span>
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($news->hasPages())
                    <div class="pagination-container">
                        {{ $news->links('partials.custom-pagination') }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    @if (request('search'))
                        <h5 class="empty-title">Tidak ada berita yang ditemukan</h5>
                        <p class="empty-subtitle">Tidak ada hasil untuk "{{ request('search') }}". Coba kata kunci lain.</p>
                        <a href="{{ route('public.berita') }}" class="contact-btn">
                            <i class="fas fa-arrow-left me-2"></i>Lihat Semua Berita
                        </a>
                    @else
                        <h5 class="empty-title">Belum ada berita tersedia</h5>
                        <p class="empty-subtitle">Berita dan informasi akan segera hadir untuk Anda.</p>
                    @endif
                </div>
            @endif
        </div>
    </section>

    <!-- Call to Action -->
    <section class="section section-alternate scroll-reveal">
        <div class="container text-center">
            <h3 class="section-title mb-4">Jangan Lewatkan Update Terbaru</h3>
            <p class="section-subtitle mb-4">Ikuti terus informasi dan perkembangan terbaru dari kami</p>
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="{{ route('public.home') }}" class="btn btn-gradient">
                    <i class="fas fa-home me-2"></i>Beranda
                </a>
                <a href="{{ route('public.layanan') }}" class="btn btn-outline-gradient">
                    <i class="fas fa-concierge-bell me-2"></i>Layanan Kami
                </a>
                <a href="{{ route('public.galeri') }}" class="btn btn-outline-gradient">
                    <i class="fas fa-images me-2"></i>Galeri
                </a>
            </div>
        </div>
    </section>
@endsection
