@extends('public.layouts.app')

@section('title', 'Struktur Organisasi')

@section('content')
    <!-- Hero Section -->
    <section class="structure-hero-section">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <div class="hero-content">
                        <div class="hero-badge mb-3">
                            <i class="fas fa-sitemap me-2"></i>
                            Bagan Organisasi
                        </div>
                        <h1 class="hero-title mb-4">Struktur Organisasi</h1>
                        <p class="hero-subtitle mb-4">
                            Mengenal lebih dekat struktur kepemimpinan dan tim yang menggerakkan
                            {{ $profile->name ?? 'Dinas Koperasi' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Organization Chart Section -->
    <section class="section-modern scroll-reveal">
        <div class="container">
            @if ($structures->count() > 0)
                @php
                    // Group structures by hierarchy
                    $kepala = $structures
                        ->filter(function ($item) {
                            return stripos($item->position, 'kepala') !== false;
                        })
                        ->first();

                    $sekretaris = $structures
                        ->filter(function ($item) {
                            return stripos($item->position, 'sekretaris') !== false;
                        })
                        ->first();

                    $kabid = $structures->filter(function ($item) {
                        return stripos($item->position, 'kabid') !== false ||
                            stripos($item->position, 'kepala bidang') !== false;
                    });

                    $staff = $structures->filter(function ($item) {
                        return stripos($item->position, 'staff') !== false;
                    });
                @endphp

                <div class="org-chart">
                    <!-- Level 1: Kepala Dinas -->
                    @if ($kepala)
                        <div class="org-level level-1">
                            <div class="org-card kepala">
                                <div class="card-photo">
                                    @if ($kepala->photo)
                                        <img src="{{ asset('storage/' . $kepala->photo) }}" alt="{{ $kepala->name }}">
                                    @else
                                        <div class="photo-placeholder">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-info">
                                    <h4>{{ $kepala->position }}</h4>
                                    <h5>{{ $kepala->name }}</h5>
                                    @if ($kepala->nip)
                                        <p class="nip">NIP: {{ $kepala->nip }}</p>
                                    @endif
                                </div>
                                <div class="edit-icon">
                                    <i class="fas fa-edit"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Connection Line -->
                        <div class="org-connector vertical"></div>
                    @endif

                    <!-- Level 2: Sekretaris -->
                    @if ($sekretaris)
                        <div class="org-level level-2">
                            <div class="org-card sekretaris">
                                <div class="card-photo">
                                    @if ($sekretaris->photo)
                                        <img src="{{ asset('storage/' . $sekretaris->photo) }}"
                                            alt="{{ $sekretaris->name }}">
                                    @else
                                        <div class="photo-placeholder">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-info">
                                    <h4>{{ $sekretaris->position }}</h4>
                                    <h5>{{ $sekretaris->name }}</h5>
                                    @if ($sekretaris->nip)
                                        <p class="nip">NIP: {{ $sekretaris->nip }}</p>
                                    @endif
                                </div>
                                <div class="edit-icon">
                                    <i class="fas fa-edit"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Connection Line -->
                        <div class="org-connector vertical"></div>
                    @endif

                    <!-- Level 3: Kabid -->
                    @if ($kabid->count() > 0)
                        <div class="org-level level-3">
                            <div class="kabid-container">
                                @foreach ($kabid as $index => $kb)
                                    <div class="org-card kabid kabid-{{ $index + 1 }}">
                                        <div class="card-photo">
                                            @if ($kb->photo)
                                                <img src="{{ asset('storage/' . $kb->photo) }}" alt="{{ $kb->name }}">
                                            @else
                                                <div class="photo-placeholder">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-info">
                                            <h4>{{ $kb->position }}</h4>
                                            <h5>{{ $kb->name }}</h5>
                                            @if ($kb->nip)
                                                <p class="nip">NIP: {{ $kb->nip }}</p>
                                            @endif
                                        </div>
                                        <div class="edit-icon">
                                            <i class="fas fa-edit"></i>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Horizontal Connector for Kabid -->
                        <div class="org-connector horizontal kabid-connector"></div>

                        <!-- Connection Line -->
                        <div class="org-connector vertical"></div>
                    @endif

                    <!-- Level 4: Staff -->
                    @if ($staff->count() > 0)
                        <div class="org-level level-4">
                            <div class="staff-container">
                                @foreach ($staff as $index => $st)
                                    <div class="org-card staff staff-{{ $index + 1 }}">
                                        <div class="card-photo small">
                                            @if ($st->photo)
                                                <img src="{{ asset('storage/' . $st->photo) }}" alt="{{ $st->name }}">
                                            @else
                                                <div class="photo-placeholder">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-info">
                                            <h5>{{ $st->position }}</h5>
                                            <h6>{{ $st->name }}</h6>
                                            @if ($st->nip)
                                                <p class="nip">NIP: {{ $st->nip }}</p>
                                            @endif
                                        </div>
                                        <div class="edit-icon">
                                            <i class="fas fa-edit"></i>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Horizontal Connector for Staff -->
                        <div class="org-connector horizontal staff-connector"></div>
                    @endif
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <h5 class="empty-title">Struktur organisasi sedang dalam pembaruan</h5>
                    <p class="empty-subtitle">Informasi struktur organisasi akan segera tersedia.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Back to Home Section -->
    <section class="section section-alternate scroll-reveal">
        <div class="container text-center">
            <h3 class="section-title mb-4">Ingin Tahu Lebih Banyak?</h3>
            <p class="section-subtitle mb-4">Jelajahi layanan dan informasi lainnya dari kami</p>
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="{{ route('public.home') }}" class="btn btn-gradient">
                    <i class="fas fa-home me-2"></i>Beranda
                </a>
                <a href="{{ route('public.layanan') }}" class="btn btn-outline-gradient">
                    <i class="fas fa-concierge-bell me-2"></i>Layanan Kami
                </a>
                <a href="{{ route('public.berita') }}" class="btn btn-outline-gradient">
                    <i class="fas fa-newspaper me-2"></i>Berita Terbaru
                </a>
            </div>
        </div>
    </section>
@endsection
