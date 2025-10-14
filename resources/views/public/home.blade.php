@extends('public.layouts.app')

@section('title', 'Beranda')

@section('content')
    <!-- Hero Section dengan Background Slideshow -->
    <section class="hero-slideshow" style="margin: 0px 30px 0px 30px">

        <div class="slideshow-container"
            style="padding: 20px; border-radius: 30px; overflow: hidden; width: 100%; height: 720px; ">
            @if ($carousels->count())
                @foreach ($carousels as $i => $carousel)
                    <!-- Test: Direct image URL: {{ asset('storage/' . $carousel->image) }} -->
                    <div class="slide {{ $i === 0 ? 'active' : '' }}"
                        style="background-image: url('{{ asset('storage/' . $carousel->image) }}'); background-color: #667eea; background-size: cover; background-position: center; border-radius: 20px;">
                    </div>
                @endforeach
            @else
                <div class="slide active" style="background: var(--gradient-hero); border-radius: 20px;"></div>
            @endif
            <div class="slide-overlay" style="border-radius: 20px;"></div>
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
            <div class="row align-items-center g-5">
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
                        <div class="gallery-card">
                            <a class="gallery-item" href="{{ asset('storage/' . $g->image) }}" target="_blank"
                                rel="noopener" data-title="{{ $g->title }}">
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
                            </a>
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
