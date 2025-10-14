@extends('public.layouts.app')

@section('title', 'Profil Organisasi')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center min-vh-75">
                <div class="col-lg-6 scroll-reveal-left">
                    <div class="hero-content">
                        <div class="hero-badge mb-3">
                            <i class="fas fa-building me-2"></i>
                            Profil Organisasi
                        </div>
                        <h1 class="hero-title mb-4">{{ $profile->name ?? 'Dinas Koperasi' }}</h1>
                        <p class="hero-subtitle mb-4">
                            {{ $profile->detail ?? 'Lembaga yang berkomitmen untuk mengembangkan koperasi dan ekonomi kerakyatan dengan profesional dan berkelanjutan.' }}
                        </p>
                        @if ($profile->quotes)
                            <div class="hero-quote">
                                <i class="fas fa-quote-left"></i>
                                <em>{{ $profile->quotes }}</em>
                                <i class="fas fa-quote-right"></i>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 scroll-reveal-right">
                    <div class="hero-image">
                        @if ($profile->logo)
                            <div class="profile-card">
                                <img src="{{ asset('storage/' . $profile->logo) }}" class="profile-img"
                                    alt="{{ $profile->name }}">
                                <div class="profile-overlay">
                                    <div class="profile-info">
                                        <h5>{{ $profile->name }}</h5>
                                        <p>Lembaga Resmi Pemerintah</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="profile-card">
                                <div class="profile-placeholder">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="profile-overlay">
                                    <div class="profile-info">
                                        <h5>{{ $profile->name }}</h5>
                                        <p>Lembaga Resmi Pemerintah</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang Kami Section -->
    @if ($profile->tentang || $profile->tujuan)
        <section class="section-modern">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="section-title">Tentang Kami</h2>
                    <p class="section-subtitle">Mengenal lebih dekat profil dan tujuan organisasi</p>
                </div>

                <div class="row g-4 mb-5">
                    @if ($profile->tentang)
                        <div class="col-lg-8">
                            <div class="info-card">
                                <div class="info-card-header">
                                    <div class="info-icon">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div>
                                        <h4 class="info-title">Profil Organisasi</h4>
                                        <p class="info-subtitle">Mengenal lebih dekat Dinas Koperasi dan UMKM</p>
                                    </div>
                                </div>
                                <div class="info-content">
                                    <p>{{ $profile->tentang }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($profile->tujuan)
                        <div class="col-lg-4">
                            <div class="info-card h-100">
                                <div class="info-card-header">
                                    <div class="info-icon">
                                        <i class="fas fa-bullseye"></i>
                                    </div>
                                    <div>
                                        <h4 class="info-title">Tujuan</h4>
                                        <p class="info-subtitle">Target yang ingin dicapai</p>
                                    </div>
                                </div>
                                <div class="info-content">
                                    <p>{{ $profile->tujuan }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    <!-- Tugas & Peran Section -->
    @if ($profile->tugas_pokok || $profile->peran || $profile->fokus_utama)
        <section class="section">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="section-title">Tugas & Peran</h2>
                    <p class="section-subtitle">Fungsi dan peran strategis dalam pembangunan</p>
                </div>

                <div class="row g-4">
                    @if ($profile->tugas_pokok)
                        <div class="col-lg-4 col-md-6">
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="fas fa-tasks"></i>
                                </div>
                                <h5 class="feature-title">Tugas Pokok</h5>
                                <p class="feature-description">{{ $profile->tugas_pokok }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($profile->peran)
                        <div class="col-lg-4 col-md-6">
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="fas fa-users-cog"></i>
                                </div>
                                <h5 class="feature-title">Peran Strategis</h5>
                                <p class="feature-description">{{ $profile->peran }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($profile->fokus_utama)
                        <div class="col-lg-4 col-md-6">
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="fas fa-crosshairs"></i>
                                </div>
                                <h5 class="feature-title">Fokus Utama</h5>
                                <p class="feature-description">{{ $profile->fokus_utama }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    <!-- Visi Misi Section -->
    <section class="section-modern">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Visi & Misi</h2>
                <p class="section-subtitle">Arah dan tujuan organisasi dalam melayani masyarakat</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="vision-mission-card">
                        <div class="card-header">
                            <div class="card-icon vision-icon">
                                <i class="fas fa-eye"></i>
                            </div>
                            <h3>Visi</h3>
                        </div>
                        <div class="card-body">
                            <p>{{ $profile->vision ?? 'Menjadi lembaga terdepan dalam pembinaan dan pengembangan koperasi untuk kesejahteraan masyarakat.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="vision-mission-card">
                        <div class="card-header">
                            <div class="card-icon mission-icon">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <h3>Misi</h3>
                        </div>
                        <div class="card-body">
                            <p>{{ $profile->mission ?? 'Membina dan mengembangkan koperasi sebagai pilar ekonomi kerakyatan yang mandiri, profesional, dan berkelanjutan.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="section-alternate">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Hubungi Kami</h2>
                <p class="section-subtitle">Kami siap melayani dan menjawab pertanyaan Anda</p>
            </div>

            <div class="row g-4">
                @if ($profile->address)
                    <div class="col-md-6 col-lg-4">
                        <div class="contact-card">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h5>Alamat Kantor</h5>
                            <p>{{ $profile->address }}</p>
                            <a href="https://maps.google.com/?q={{ urlencode($profile->address) }}" target="_blank"
                                class="contact-btn">
                                <i class="fas fa-directions me-2"></i>Lihat Peta
                            </a>
                        </div>
                    </div>
                @endif

                @if ($profile->phone)
                    <div class="col-md-6 col-lg-4">
                        <div class="contact-card">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <h5>Telepon</h5>
                            <p>{{ $profile->phone }}</p>
                            <a href="tel:{{ $profile->phone }}" class="contact-btn">
                                <i class="fas fa-phone me-2"></i>Hubungi
                            </a>
                        </div>
                    </div>
                @endif

                @if ($profile->email)
                    <div class="col-md-6 col-lg-4">
                        <div class="contact-card">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h5>Email Resmi</h5>
                            <p>{{ $profile->email }}</p>
                            <a href="mailto:{{ $profile->email }}" class="contact-btn">
                                <i class="fas fa-envelope me-2"></i>Kirim Email
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Location Map Section -->
    <section class="section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Lokasi Kami</h2>
                <p class="section-subtitle">Temukan kantor kami dengan mudah</p>
            </div>

            <div class="row g-5">
                <div class="col-lg-8">
                    <div>
                        @if ($profile->latitude && $profile->longitude)
                            <div class="public-map-container custom-map">
                                <span class="map-badge"><i class="fas fa-map-marker-alt me-1"></i>Lokasi</span>
                                <iframe
                                    src="https://www.google.com/maps?q={{ $profile->latitude }},{{ $profile->longitude }}&hl=id&z=15&output=embed"
                                    allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        @elseif ($profile->address)
                            <div class="public-map-container custom-map">
                                <span class="map-badge"><i class="fas fa-map-marker-alt me-1"></i>Lokasi</span>
                                <iframe
                                    src="https://www.google.com/maps?q={{ urlencode($profile->address) }}&hl=id&z=15&output=embed"
                                    allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center py-5"
                                style="min-height: 280px;">
                                <i class="fas fa-map fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Lokasi belum diatur</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="modern-card h-100">
                        <h4 class="mb-4">Informasi Lokasi</h4>

                        @if ($profile->address)
                            <div class="mb-4">
                                <h6 class="fw-bold mb-2">
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>Alamat Lengkap
                                </h6>
                                <p class="text-muted mb-0">{{ $profile->address }}</p>
                            </div>
                        @endif

                        <div class="mb-4">
                            <h6 class="fw-bold mb-2">
                                <i class="fas fa-clock text-primary me-2"></i>Jam Operasional
                            </h6>
                            <p class="text-muted mb-1">Senin - Jumat: 08:00 - 16:00</p>
                            <p class="text-muted mb-0">Sabtu - Minggu: Tutup</p>
                        </div>

                        <div class="d-grid gap-2">
                            @if ($profile->latitude && $profile->longitude)
                                <a href="https://maps.google.com/?q={{ $profile->latitude }},{{ $profile->longitude }}"
                                    target="_blank" class="btn btn-gradient">
                                    <i class="fas fa-directions me-2"></i>Petunjuk Arah
                                </a>
                            @elseif ($profile->address)
                                <a href="https://maps.google.com/?q={{ urlencode($profile->address) }}" target="_blank"
                                    class="btn btn-gradient">
                                    <i class="fas fa-directions me-2"></i>Petunjuk Arah
                                </a>
                            @else
                                <button class="btn btn-gradient" type="button" disabled title="Lokasi belum diatur">
                                    <i class="fas fa-directions me-2"></i>Petunjuk Arah
                                </button>
                            @endif
                            @if ($profile->phone)
                                <a href="tel:{{ $profile->phone }}" class="btn btn-outline-gradient">
                                    <i class="fas fa-phone me-2"></i>Hubungi Sekarang
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Additional Info -->
    @if ($profile->website || $profile->established_date)
        <section class="section">
            <div class="container">
                <div class="row g-4 justify-content-center">
                    @if ($profile->website)
                        <div class="col-md-6">
                            <div class="modern-card text-center">
                                <div class="card-icon">
                                    <i class="fas fa-globe"></i>
                                </div>
                                <h5 class="mb-3">Website</h5>
                                <p class="text-muted">{{ $profile->website }}</p>
                                <a href="{{ $profile->website }}" target="_blank"
                                    class="btn btn-outline-gradient btn-sm">
                                    <i class="fas fa-external-link-alt me-2"></i>Kunjungi
                                </a>
                            </div>
                        </div>
                    @endif

                    @if ($profile->established_date)
                        <div class="col-md-6">
                            <div class="modern-card text-center">
                                <div class="card-icon">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <h5 class="mb-3">Didirikan</h5>
                                <p class="text-muted">{{ $profile->established_date->format('d F Y') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            </div>
    @endif

    <!-- Call to Action -->
    <section class="section section-alternate">
        <div class="container text-center">
            <h3 class="section-title mb-4">Jelajahi Lebih Lanjut</h3>
            <p class="section-subtitle mb-4">Temukan layanan dan informasi terbaru dari kami</p>
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="{{ route('public.layanan') }}" class="btn btn-gradient">
                    <i class="fas fa-concierge-bell me-2"></i>Layanan Kami
                </a>
                <a href="{{ route('public.struktur') }}" class="btn btn-outline-gradient">
                    <i class="fas fa-users me-2"></i>Struktur Organisasi
                </a>
                <a href="{{ route('public.berita') }}" class="btn btn-outline-gradient">
                    <i class="fas fa-newspaper me-2"></i>Berita Terbaru
                </a>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        /* Ensure embedded map fully covers the card */
        .map-container {
            border-radius: 20px;
            /* follow modern-card rounded look */
            padding: 0 !important;
            /* ensure no inner spacing */
            min-height: 0 !important;
            /* remove any theme min-height */
            overflow: hidden;
            /* clip map corners */
        }

        .public-map-container {
            position: relative;
            width: 100%;
            /* default map height; card follows this without extra space */
            height: 400px;
            /* matches design */
            overflow: hidden;
            border-radius: 20px;
        }

        .public-map-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        /* Custom branded map style */
        .custom-map {
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.08), rgba(25, 135, 84, 0.08));
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12), inset 0 0 0 1px rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(13, 110, 253, 0.25);
        }

        .custom-map::after {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 20px;
            pointer-events: none;
            background: radial-gradient(80% 50% at 10% 10%, rgba(13, 110, 253, 0.12), transparent 60%),
                radial-gradient(60% 40% at 90% 20%, rgba(25, 135, 84, 0.12), transparent 60%);
        }

        .custom-map:hover {
            border-color: rgba(13, 110, 253, 0.4);
            box-shadow: 0 12px 36px rgba(13, 110, 253, 0.15);
        }

        .custom-map .map-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            font-size: 0.85rem;
            color: #0d6efd;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(13, 110, 253, 0.25);
            border-radius: 999px;
            backdrop-filter: blur(6px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }

        /* Subtle animated shine */
        .custom-map::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            pointer-events: none;
            background: linear-gradient(120deg, transparent 30%, rgba(255, 255, 255, 0.12), transparent 70%);
            transform: translateX(-100%);
            transition: transform 0.8s ease;
        }

        .custom-map:hover::before {
            transform: translateX(0%);
        }

        @media (max-width: 576px) {
            .public-map-container {
                height: 320px;
            }
        }

        /* Explicitly unset any custom min-height from theme on this card variant */
        .modern-card.map-container {
            min-height: unset !important;
        }
    </style>
@endpush
