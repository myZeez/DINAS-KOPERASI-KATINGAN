@extends('public.layouts.app')

@section('title', 'Beranda')

@section('content')
    <!-- Hero Section dengan Background Slideshow -->
    <section class="hero-slideshow" style="margin: 0px 30px 0px 30px">

        <div class="slideshow-container">
            @if ($carousels->count())
                @foreach ($carousels as $i => $carousel)
                    <!-- Test: Direct image URL: {{ asset('storage/' . $carousel->image) }} -->
                    <div class="slide {{ $i === 0 ? 'active' : '' }}"
                        style="background-image: url('{{ asset('storage/' . $carousel->image) }}'); background-color: #667eea; background-size: cover; background-position: center;">
                    </div>
                @endforeach
            @else
                <div class="slide active" style="background: var(--gradient-hero);"></div>
            @endif
            <div class="slide-overlay"></div>
        </div>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="hero-content">
                        <h1 class="mb-4 text-white fw-bold">{{ $heroTitle ?? ($profile->name ?? 'Dinas Koperasi') }}</h1>
                        <p class="lead mb-4 text-white">
                            {{ \Illuminate\Support\Str::limit($heroSubtitle ?? ($profile->vision ?? 'Mewujudkan pelayanan koperasi yang profesional, responsif, dan berdampak bagi kemajuan ekonomi kerakyatan.'), 200) }}
                        </p>
                        <div class="d-flex flex-wrap gap-3 justify-content-center">
                            <a href="{{ route('public.layanan') }}" class="btn btn-gradient btn-lg">
                                <i class="fas fa-rocket me-2"></i>Jelajahi Layanan
                            </a>
                            @if (!empty($profile?->phone))
                                <a href="tel:{{ $profile->phone }}" class="btn btn-outline-light btn-lg">
                                    <i class="fas fa-phone me-2"></i>Hubungi Kami
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang Dinas Koperasi Section -->
    <section id="tentang" class="section scroll-reveal">
        <div class="container">
            <div class="row align-items-center g-5 px-lg-5 px-md-4 px-3">
                <div class="col-lg-6 scroll-reveal-left">
                    <div class="about-content">
                        <h2 class="section-title text-start mb-4">Tentang {{ $profile->name ?? 'Dinas Koperasi' }}</h2>
                        <div class="about-description mb-4">
                            @if ($profile && $profile->detail)
                                <p class="text-muted mb-3">{{ $profile->detail }}</p>
                            @else
                                <p class="text-muted mb-3">
                                    {{ $profile->name ?? 'Dinas Koperasi' }} adalah lembaga pemerintahan yang bertugas untuk
                                    membina, mengembangkan, dan mengawasi perkoperasian di wilayah kerjanya. Kami
                                    berkomitmen untuk memberikan pelayanan terbaik kepada masyarakat dalam bidang koperasi
                                    dan usaha mikro kecil menengah.
                                </p>
                            @endif
                        </div>

                        @if ($profile && ($profile->vision || $profile->mission))
                            <div class="vision-mission">
                                @if ($profile->vision)
                                    <div class="mb-4">
                                        <h5 class="fw-bold text-primary mb-2">
                                            <i class="fas fa-eye me-2"></i>Visi
                                        </h5>
                                        <p class="text-muted">{{ $profile->vision }}</p>
                                    </div>
                                @endif

                                @if ($profile->mission)
                                    <div class="mb-4">
                                        <h5 class="fw-bold text-primary mb-2">
                                            <i class="fas fa-bullseye me-2"></i>Misi
                                        </h5>
                                        <p class="text-muted">{{ $profile->mission }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="mt-4">
                            <a href="{{ route('public.profile') }}" class="btn btn-gradient">
                                <i class="fas fa-arrow-right me-2"></i>Lihat Selengkapnya
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 scroll-reveal-right">
                    <div class="about-image-section">
                        @if ($kepalaDinas)
                            <!-- Foto Kepala Dinas dari Struktur -->
                            @if ($kepalaDinas->photo)
                                <div class="text-center mb-4">
                                    <div class="leader-photo-container">
                                        <img src="{{ asset('storage/' . $kepalaDinas->photo) }}"
                                            alt="Foto {{ $kepalaDinas->name }}" class="leader-photo">
                                    </div>
                                </div>
                            @else
                                <div class="text-center mb-4">
                                    <div class="leader-photo-container">
                                        <div class="leader-photo-placeholder">
                                            <i class="fas fa-user-tie fa-3x text-muted"></i>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Info Kepala Dinas dari Struktur -->
                            <div class="leader-info text-center">
                                <h4 class="fw-bold text-dark mb-2">{{ $kepalaDinas->name }}</h4>
                                <p class="text-primary fw-semibold mb-3">{{ $kepalaDinas->position }}</p>
                                <div class="leader-quote p-4 bg-light rounded-3">
                                    <i class="fas fa-quote-left text-primary mb-2"></i>
                                    <p class="mb-0 font-italic text-muted">
                                        @if ($profile->quotes)
                                            "{{ $profile->quotes }}"
                                        @else
                                            "Kami berkomitmen untuk terus memberikan pelayanan terbaik dan mendorong
                                            perkembangan koperasi di daerah ini."
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="placeholder-content text-center p-5 bg-light rounded-3">
                                <i class="fas fa-user-tie fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Informasi Kepala Dinas</h5>
                                <p class="text-muted">Akan ditampilkan setelah data profil dilengkapi</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Layanan Section -->
    <section id="layanan" class="section scroll-reveal">
        <div class="container">
            <div class="text-center mb-5 scroll-reveal-fade">
                <h2 class="section-title">Layanan Unggulan</h2>
                <p class="section-subtitle">
                    {{ $pcServicesIntro->content ?? ($pcServicesIntro->title ?? 'Akses semua layanan unggulan kami dengan mudah dan cepat') }}
                </p>
            </div>
            <div class="row g-4 scroll-stagger">
                @forelse($services as $s)
                    <div class="col-md-6 col-lg-4">
                        <div class="service-card h-100">
                            <div class="service-card-header">
                                @if ($s->image)
                                    <div class="service-image">
                                        <img src="{{ asset('storage/' . $s->image) }}" alt="{{ $s->title }}"
                                            class="service-img">
                                        <div class="service-badge">
                                            <span class="badge-text">Layanan</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="service-icon-container">
                                        <div class="service-icon">
                                            @if ($s->icon)
                                                <i class="{{ $s->icon }}"></i>
                                            @else
                                                <i class="fas fa-concierge-bell"></i>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="service-card-body">
                                <h5 class="service-title">{{ $s->title }}</h5>
                                <p class="service-description">{{ \Illuminate\Support\Str::limit($s->description, 120) }}
                                </p>

                                @if ($s->link)
                                    <div class="service-action">
                                        <a href="{{ $s->link }}" class="service-btn">
                                            <span>Selengkapnya</span>
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-concierge-bell"></i>
                            </div>
                            <h5 class="empty-title">Belum ada layanan tersedia</h5>
                            <p class="empty-subtitle">Layanan unggulan akan ditampilkan di sini</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- View All Button -->
            @if ($services->count() > 0)
                <div class="text-center mt-5">
                    <a href="{{ route('public.layanan') }}" class="btn btn-gradient">
                        <i class="fas fa-concierge-bell me-2"></i>Lihat Semua Layanan
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Berita Section -->
    <section id="berita" class="section section-alternate scroll-reveal">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Berita & Informasi</h2>
                <p class="section-subtitle">Tetap update dengan berita dan pengumuman terbaru dari kami</p>
            </div>
            <div class="row g-4">
                @forelse($latestNews as $n)
                    <div class="col-md-6 col-lg-4">
                        <div class="news-card h-100">
                            @if ($n->image)
                                <div class="news-card-header">
                                    <div class="news-image">
                                        <img src="{{ asset('storage/' . $n->image) }}" alt="{{ $n->title }}"
                                            class="news-img">
                                        <div class="news-badge">
                                            <span class="badge-text">Berita</span>
                                        </div>
                                        <div class="news-date">
                                            <span
                                                class="date-text">{{ optional($n->published_at)->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="news-card-header">
                                    <div class="news-placeholder">
                                        <div class="news-icon">
                                            <i class="fas fa-newspaper"></i>
                                        </div>
                                        <div class="news-badge">
                                            <span class="badge-text">Berita</span>
                                        </div>
                                        <div class="news-date">
                                            <span
                                                class="date-text">{{ optional($n->published_at)->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="news-card-body">
                                <h5 class="news-title">{{ $n->title }}</h5>
                                <p class="news-excerpt">{{ Str::limit(strip_tags($n->content), 150) }}</p>

                                <div class="news-action">
                                    <a href="{{ route('public.berita.detail', $n) }}" class="news-btn">
                                        <span>Baca Selengkapnya</span>
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            <h5 class="empty-title">Belum ada berita tersedia</h5>
                            <p class="empty-subtitle">Berita terbaru akan ditampilkan di sini</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- View All Button -->
            @if ($latestNews->count() > 0)
                <div class="text-center mt-5">
                    <a href="{{ route('public.berita') }}" class="btn btn-gradient">
                        <i class="fas fa-newspaper me-2"></i>Lihat Semua Berita
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Galeri Section -->
    <section id="galeri" class="section scroll-reveal">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Galeri Kegiatan</h2>
                <p class="section-subtitle">Dokumentasi kegiatan dan momen penting kami</p>
            </div>
            <div class="row g-4">
                @forelse($galleries as $index => $g)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="gallery-card" onclick="openLightbox('{{ asset('storage/' . $g->image) }}', '{{ $g->title }}', '{{ $g->description }}')">
                            <div class="gallery-image">
                                <img src="{{ asset('storage/' . $g->image) }}" alt="{{ $g->title }}"
                                    class="gallery-img">
                                <div class="gallery-overlay">
                                    <div class="gallery-icon">
                                        <i class="fas fa-search-plus"></i>
                                    </div>
                                </div>
                            </div>
                            @if ($g->title)
                                <div class="gallery-info">
                                    <h6 class="gallery-title">{{ Str::limit($g->title, 40) }}</h6>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-images"></i>
                            </div>
                            <h5 class="empty-title">Belum ada galeri tersedia</h5>
                            <p class="empty-subtitle">Foto kegiatan akan ditampilkan di sini</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- View All Button -->
            @if ($galleries->count() > 0)
                <div class="text-center mt-5">
                    <a href="{{ route('public.galeri') }}" class="btn btn-gradient">
                        <i class="fas fa-images me-2"></i>Lihat Semua Galeri
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Lightbox Modal -->
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

    <!-- Ulasan Section -->
    <section id="ulasan" class="section section-alternate scroll-reveal">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Testimoni Masyarakat</h2>
                <p class="section-subtitle">Apa kata mereka tentang layanan kami</p>
            </div>

            @php
                // Combine all approved reviews from database
                $allReviews = collect();

                // Add top reviews if available
                if (isset($topReviews) && $topReviews->count() > 0) {
                    $allReviews = $allReviews->merge($topReviews);
                }

                // Add latest reviews if available (avoiding duplicates)
                if (isset($latestReviews) && $latestReviews->count() > 0) {
                    $existingIds = $allReviews->pluck('id')->toArray();
                    $additionalReviews = $latestReviews->filter(function ($review) use ($existingIds) {
                        return !in_array($review->id, $existingIds);
                    });
                    $allReviews = $allReviews->merge($additionalReviews);
                }

                // Take maximum 4 reviews for display
                $testimonials = $allReviews
                    ->take(4)
                    ->map(function ($review) {
                        return [
                            'rating' => $review->rating,
                            'comment' => $review->comment,
                            'name' => $review->name ?? 'Anonim',
                            'created_at' => $review->created_at->format('d M Y'),
                        ];
                    })
                    ->toArray();
            @endphp

            @if (count($testimonials) > 0)
                <div class="row g-4">
                    @foreach ($testimonials as $testimonial)
                        <div class="col-lg-3 col-md-6">
                            <div class="card testimonial-card h-100 border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="star-rating mb-3 text-center">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i
                                                class="fa{{ $i <= (int) $testimonial['rating'] ? 's' : 'r' }} fa-star text-warning"></i>
                                        @endfor
                                    </div>
                                    <blockquote class="blockquote text-center mb-4">
                                        <p class="mb-0 font-italic text-muted">"{{ $testimonial['comment'] }}"</p>
                                    </blockquote>
                                    <div class="text-center">
                                        <div class="avatar-circle mx-auto mb-3">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <h6 class="card-title fw-bold mb-1">{{ $testimonial['name'] }}</h6>
                                        <small class="text-muted">{{ $testimonial['created_at'] }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-comments fa-4x text-muted mb-4"></i>
                        <h5 class="text-muted mb-3">Belum Ada Testimoni</h5>
                        <p class="text-muted mb-4">Jadilah yang pertama memberikan testimoni tentang layanan kami</p>
                        <a href="{{ route('public.ulasan') }}" class="btn btn-gradient">
                            <i class="fas fa-pen me-2"></i>Tulis Testimoni
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('styles')
<style>
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
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Gallery card cursor */
.gallery-card {
    cursor: pointer;
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
