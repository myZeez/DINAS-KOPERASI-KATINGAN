@extends('public.layouts.app')

@section('title', 'Galeri')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <div class="hero-content">
                        <div class="hero-badge mb-3">
                            <i class="fas fa-images me-2"></i>
                            Dokumentasi Kegiatan
                        </div>
                        <h1 class="hero-title mb-4">Galeri Kegiatan</h1>
                        <p class="hero-subtitle mb-4">
                            Dokumentasi kegiatan, acara, dan momen penting dalam perjalanan kami melayani masyarakat
                        </p>

                        <!-- Search Form -->
                        <form method="GET" class="search-form">
                            <div class="search-container">
                                <input type="text" name="search" class="search-input"
                                    placeholder="Cari foto atau kegiatan..." value="{{ request('search') }}">
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

    <!-- Gallery Section -->
    <section class="section-modern scroll-reveal">
        <div class="container">
            @if ($galleries->count() > 0)
                <div class="row g-4">
                    @foreach ($galleries as $gallery)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="gallery-card">
                                <div class="gallery-item" data-bs-toggle="modal"
                                    data-bs-target="#galleryModal{{ $gallery->id }}">
                                    <div class="gallery-image">
                                        <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->title }}"
                                            class="gallery-img">
                                        <div class="gallery-overlay">
                                            <div class="gallery-icon">
                                                <i class="fas fa-search-plus"></i>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($gallery->title)
                                        <div class="gallery-info">
                                            <h6 class="gallery-title">{{ Str::limit($gallery->title, 40) }}</h6>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Modal for each gallery item -->
                            <div class="modal fade" id="galleryModal{{ $gallery->id }}" tabindex="-1">
                                <div class="modal-dialog modal-xl modal-dialog-centered">
                                    <div class="modal-content gallery-modal">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ $gallery->title }}</h5>
                                            <button type="button" class="btn-close btn-close-white"
                                                data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-0">
                                            <img src="{{ asset('storage/' . $gallery->image) }}" class="modal-gallery-img"
                                                alt="{{ $gallery->title }}">
                                        </div>
                                        @if ($gallery->description)
                                            <div class="modal-footer">
                                                <p class="gallery-description">{{ $gallery->description }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($galleries->hasPages())
                    <div class="pagination-container">
                        {{ $galleries->links('partials.custom-pagination') }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-images"></i>
                    </div>
                    @if (request('search'))
                        <h5 class="empty-title">Tidak ada foto yang ditemukan</h5>
                        <p class="empty-subtitle">Tidak ada hasil untuk "{{ request('search') }}". Coba kata kunci lain.
                        </p>
                        <a href="{{ route('public.galeri') }}" class="contact-btn">
                            <i class="fas fa-arrow-left me-2"></i>Lihat Semua Foto
                        </a>
                    @else
                        <h5 class="empty-title">Belum ada foto tersedia</h5>
                        <p class="empty-subtitle">Galeri foto kegiatan akan segera hadir untuk Anda.</p>
                    @endif
                </div>
            @endif
        </div>
    </section>

    <!-- Call to Action -->
    <section class="section section-alternate scroll-reveal">
        <div class="container text-center">
            <h3 class="section-title mb-4">Ikuti Kegiatan Kami</h3>
            <p class="section-subtitle mb-4">Jangan lewatkan berbagai kegiatan dan acara menarik dari kami</p>
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="{{ route('public.berita') }}" class="btn btn-gradient">
                    <i class="fas fa-newspaper me-2"></i>Berita Terbaru
                </a>
                <a href="{{ route('public.layanan') }}" class="btn btn-outline-gradient">
                    <i class="fas fa-concierge-bell me-2"></i>Layanan Kami
                </a>
                <a href="{{ route('public.home') }}" class="btn btn-outline-gradient">
                    <i class="fas fa-home me-2"></i>Beranda
                </a>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .gallery-item {
            cursor: pointer;
            position: relative;
        }

        .gallery-item::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 2;
        }

        .gallery-item::after {
            content: '\f00e';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: var(--primary);
            font-size: 1.5rem;
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 3;
        }
    </style>
@endpush
