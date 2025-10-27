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
                            <div class="gallery-card" onclick="openLightbox('{{ asset('storage/' . $gallery->image) }}', '{{ $gallery->title }}', '{{ $gallery->description }}')">
                                <div class="gallery-image-container">
                                    <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->title }}" class="gallery-image">
                                    <div class="gallery-overlay">
                                        <i class="fas fa-search-plus"></i>
                                    </div>
                                </div>
                                @if ($gallery->title)
                                    <div class="gallery-info">
                                        <h6 class="gallery-title">{{ Str::limit($gallery->title, 40) }}</h6>
                                    </div>
                                @endif
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

    <!-- Custom Lightbox -->
    <div id="lightbox" class="lightbox">
        <div class="lightbox-content">
            <div class="lightbox-header">
                <div class="lightbox-header-content">
                    <h5 id="lightbox-title"></h5>
                    <p id="lightbox-description" class="lightbox-subtitle"></p>
                </div>
                <button class="lightbox-close" onclick="closeLightbox()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="lightbox-body">
                <img id="lightbox-image" src="" alt="">
            </div>
        </div>
    </div>

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
        .gallery-card {
            cursor: pointer;
            border-radius: 15px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(79, 172, 254, 0.1);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .gallery-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(79, 172, 254, 0.2);
        }

        .gallery-image-container {
            position: relative;
            width: 100%;
            aspect-ratio: 1;
            overflow: hidden;
        }

        .gallery-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .gallery-overlay i {
            color: white;
            font-size: 2rem;
            transform: scale(0.8);
            transition: transform 0.3s ease;
        }

        .gallery-card:hover .gallery-overlay {
            opacity: 1;
        }

        .gallery-card:hover .gallery-overlay i {
            transform: scale(1);
        }

        .gallery-card:hover .gallery-image {
            transform: scale(1.05);
        }

        .gallery-info {
            padding: 1rem;
        }

        .gallery-title {
            color: var(--text-primary);
            font-weight: 600;
            font-size: 0.9rem;
            margin: 0;
            text-align: center;
        }

        /* Custom Lightbox Styles */
        .lightbox {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 10000;
            animation: fadeIn 0.3s ease;
        }

        .lightbox.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .lightbox-content {
            position: relative;
            max-width: 85%;
            max-height: 85%;
            background: rgba(255, 255, 255, 0.98);
            border: 1px solid rgba(79, 172, 254, 0.3);
            border-radius: 15px;
            backdrop-filter: blur(20px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideIn 0.3s ease;
        }

        .lightbox-header {
            padding: 1.5rem 1.5rem 1rem;
            border-bottom: 1px solid rgba(79, 172, 254, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            background: linear-gradient(135deg, rgba(79, 172, 254, 0.05), rgba(0, 242, 254, 0.02));
        }

        .lightbox-header-content {
            flex: 1;
            padding-right: 1rem;
        }

        .lightbox-header h5 {
            margin: 0 0 0.5rem 0;
            color: var(--text-primary);
            font-weight: 600;
            font-size: 1.25rem;
            line-height: 1.4;
        }

        .lightbox-subtitle {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.9rem;
            line-height: 1.5;
            opacity: 0.8;
        }

        .lightbox-close {
            background: rgba(79, 172, 254, 0.1);
            border: 1px solid rgba(79, 172, 254, 0.2);
            border-radius: 8px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .lightbox-close:hover {
            background: rgba(220, 53, 69, 0.15);
            border-color: rgba(220, 53, 69, 0.3);
            transform: scale(1.05);
        }

        .lightbox-close:hover i {
            color: #dc3545;
        }

        .lightbox-close i {
            color: var(--text-primary);
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }

        .lightbox-body {
            padding: 1.5rem;
            text-align: center;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .lightbox-body img {
            max-width: 100%;
            max-height: 65vh;
            object-fit: contain;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(79, 172, 254, 0.1);
        }



        /* Hide navigation when lightbox is open */
        body.lightbox-open {
            overflow: hidden;
        }

        body.lightbox-open .navbar,
        body.lightbox-open .navigation,
        body.lightbox-open nav {
            display: none !important;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: scale(0.8) translateY(20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .gallery-overlay i {
                font-size: 1.5rem;
            }

            .gallery-title {
                font-size: 0.8rem;
            }

            .lightbox-content {
                max-width: 95%;
                max-height: 95%;
            }

            .lightbox-header,
            .lightbox-footer {
                padding: 0.75rem 1rem;
            }

            .lightbox-body {
                padding: 0.5rem;
                max-height: 60vh;
            }
        }
    </style>
@endpush

@push('scripts')
<script>
    function openLightbox(imageSrc, title, description) {
        const lightbox = document.getElementById('lightbox');
        const lightboxImage = document.getElementById('lightbox-image');
        const lightboxTitle = document.getElementById('lightbox-title');
        const lightboxDescription = document.getElementById('lightbox-description');

        // Set content
        lightboxImage.src = imageSrc;
        lightboxTitle.textContent = title || '';
        lightboxDescription.textContent = description || '';

        // Show/hide description based on content
        if (description && description.trim()) {
            lightboxDescription.style.display = 'block';
        } else {
            lightboxDescription.style.display = 'none';
        }

        // Show lightbox
        lightbox.classList.add('active');
        document.body.classList.add('lightbox-open');
    }

    function closeLightbox() {
        const lightbox = document.getElementById('lightbox');
        lightbox.classList.remove('active');
        document.body.classList.remove('lightbox-open');

        // Reset image src to avoid flash
        setTimeout(() => {
            document.getElementById('lightbox-image').src = '';
        }, 300);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const lightbox = document.getElementById('lightbox');

        // Close lightbox when clicking outside content
        lightbox.addEventListener('click', function(e) {
            if (e.target === lightbox) {
                closeLightbox();
            }
        });

        // Close lightbox with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && lightbox.classList.contains('active')) {
                closeLightbox();
            }
        });
    });
</script>
@endpush
