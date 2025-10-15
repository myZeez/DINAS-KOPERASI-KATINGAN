@extends('public.layouts.app')

@section('title', 'Layanan Kami')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <div class="hero-content">
                        <div class="hero-badge mb-3">
                            <i class="fas fa-concierge-bell me-2"></i>
                            Layanan Profesional
                        </div>
                        <h1 class="hero-title mb-4">Layanan Unggulan Kami</h1>
                        <p class="hero-subtitle mb-4">
                            Berbagai layanan terbaik yang kami tawarkan untuk mendukung kemajuan koperasi dan ekonomi
                            kerakyatan
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="section-modern scroll-reveal">
        <div class="container">
            @if ($services->count() > 0)
                <div class="row g-4">
                    @foreach ($services as $service)
                        <div class="col-md-6 col-lg-4">
                            <div class="service-card">
                                <div class="service-card-header">
                                    @if ($service->image)
                                        <div class="service-image">
                                            <img src="{{ asset('storage/' . $service->image) }}" class="service-img"
                                                alt="{{ $service->title }}">
                                            <div class="service-badge">
                                                <span class="badge-text">Layanan</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="service-icon-container">
                                            <div class="service-icon">
                                                @if ($service->icon)
                                                    <i class="{{ $service->icon }}"></i>
                                                @else
                                                    <i class="fas fa-star"></i>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="service-card-body">
                                    <h5 class="service-title">{{ $service->title }}</h5>
                                    <p class="service-description">{{ $service->description }}</p>

                                    <div class="service-action">
                                        @php
                                            $detailUrl = $service->external_link
                                                ? $service->external_link
                                                : route('public.layanan.detail', $service);
                                            $isExternal = (bool) $service->external_link;
                                        @endphp
                                        <a href="{{ $detailUrl }}" class="service-btn"
                                            @if ($isExternal) target="_blank" rel="noopener" @endif>
                                            <span>Selengkapnya</span>
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-concierge-bell"></i>
                    </div>
                    <h5 class="empty-title">Layanan sedang dalam pengembangan</h5>
                    <p class="empty-subtitle">Informasi layanan akan segera tersedia untuk Anda.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Call to Action -->
    <section class="section section-alternate scroll-reveal">
        <div class="container text-center">
            <h3 class="section-title mb-4">Butuh Bantuan Lebih Lanjut?</h3>
            <p class="section-subtitle mb-4">Tim kami siap membantu Anda dengan layanan terbaik</p>

            @if ($profile->phone || $profile->email)
                <div class="d-flex flex-wrap gap-3 justify-content-center">
                    @if ($profile->phone)
                        <a href="tel:{{ $profile->phone }}" class="btn btn-gradient">
                            <i class="fas fa-phone me-2"></i>Hubungi Kami
                        </a>
                    @endif
                    @if ($profile->email)
                        <a href="mailto:{{ $profile->email }}" class="btn btn-outline-gradient">
                            <i class="fas fa-envelope me-2"></i>Kirim Email
                        </a>
                    @endif
                </div>
            @endif

            <div class="mt-4">
                <a href="{{ route('public.home') }}" class="btn btn-outline-gradient">
                    <i class="fas fa-home me-2"></i>Kembali ke Beranda
                </a>
            </div>
        </div>
    </section>
@endsection
