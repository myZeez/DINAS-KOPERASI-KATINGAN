@extends('public.layouts.app')

@section('title', $service->title)

@section('content')
    <section class="section" style="padding-top:140px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-9">
                    <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('public.home') }}">Beranda</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('public.layanan') }}">Layanan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $service->title }}</li>
                        </ol>
                    </nav>

                    <h1 class="mb-3">{{ $service->title }}</h1>
                    <p class="lead text-muted mb-4">{{ $service->description }}</p>

                    @if ($service->image)
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}"
                                class="w-100 rounded-4 shadow-sm" style="max-height:420px; object-fit:cover;">
                        </div>
                    @endif

                    <div class="service-content">

                        <!-- Service Information Cards -->
                        <div class="row g-3 mb-5">
                            @if ($service->service_category)
                                <div class="col-sm-6 col-lg-4">
                                    <div class="info-card">
                                        <div class="d-flex align-items-center">
                                            <div class="info-icon me-3">
                                                <i class="fas fa-tag"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-bold text-muted">Kategori</h6>
                                                <p class="mb-0">{{ ucfirst($service->service_category) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($service->service_fee !== null)
                                <div class="col-sm-6 col-lg-4">
                                    <div class="info-card">
                                        <div class="d-flex align-items-center">
                                            <div class="info-icon me-3">
                                                <i class="fas fa-money-bill-wave"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-bold text-muted">Biaya</h6>
                                                <p class="mb-0">
                                                    @if ($service->service_fee == 0)
                                                        <span class="text-success fw-bold">Gratis</span>
                                                    @else
                                                        Rp {{ number_format($service->service_fee, 0, ',', '.') }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($service->processing_time)
                                <div class="col-sm-6 col-lg-4">
                                    <div class="info-card">
                                        <div class="d-flex align-items-center">
                                            <div class="info-icon me-3">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-bold text-muted">Waktu Proses</h6>
                                                <p class="mb-0">{{ $service->processing_time }} {{ $service->processing_time_unit }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Detailed Content -->
                        @if ($service->content_detail)
                            <div class="mb-5">
                                <h3 class="mb-3"><i class="fas fa-info-circle me-2 text-primary"></i>Deskripsi Lengkap</h3>
                                <div class="content-card">
                                    <div class="service-content">
                                        {!! $service->content_detail !!}
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Requirements Section -->
                        @if ($service->requirements || $service->required_documents)
                            <div class="mb-5">
                                <h3 class="mb-4"><i class="fas fa-clipboard-list me-2 text-primary"></i>Persyaratan</h3>
                                <div class="row g-4">
                                    @if ($service->requirements)
                                        <div class="col-lg-6">
                                            <div class="content-card">
                                                <h5 class="mb-3 text-muted">Persyaratan Umum</h5>
                                                <div class="service-content">
                                                    {!! $service->requirements !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($service->required_documents)
                                        <div class="col-lg-6">
                                            <div class="content-card">
                                                <h5 class="mb-3 text-muted">Dokumen yang Diperlukan</h5>
                                                <div class="service-content">
                                                    {!! $service->required_documents !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                @if ($service->important_notes)
                                    <div class="alert alert-warning mt-4">
                                        <h6 class="mb-2"><i class="fas fa-exclamation-triangle me-2"></i>Catatan Penting</h6>
                                        <p class="mb-0">{{ $service->important_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Procedure Section -->
                        @if ($service->procedure_steps)
                            <div class="mb-5">
                                <h3 class="mb-3"><i class="fas fa-route me-2 text-primary"></i>Prosedur Pelayanan</h3>
                                <div class="content-card">
                                    <div class="service-content">
                                        {!! $service->procedure_steps !!}
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Service Details Section -->
                        @if ($service->service_hours || $service->service_location)
                            <div class="mb-5">
                                <h3 class="mb-4"><i class="fas fa-building me-2 text-primary"></i>Informasi Pelayanan</h3>
                                <div class="row g-3">
                                    @if ($service->service_hours)
                                        <div class="col-sm-12 col-lg-6">
                                            <div class="content-card">
                                                <h5 class="mb-3 text-muted"><i class="fas fa-clock me-2"></i>Jam Pelayanan</h5>
                                                <div class="service-content">
                                                    {!! nl2br(e($service->service_hours)) !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($service->service_location)
                                        <div class="col-sm-12 col-lg-6">
                                            <div class="content-card">
                                                <h5 class="mb-3 text-muted"><i class="fas fa-map-marker-alt me-2"></i>Lokasi Pelayanan</h5>
                                                <div class="service-content">
                                                    {!! nl2br(e($service->service_location)) !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Contact Information -->
                        @if ($service->responsible_person || $service->phone_number || $service->contact_email)
                            <div class="mb-5">
                                <h3 class="mb-4"><i class="fas fa-phone me-2 text-primary"></i>Informasi Kontak</h3>
                                <div class="content-card">
                                    <div class="row g-4">
                                        @if ($service->responsible_person)
                                            <div class="col-12 col-sm-6 col-lg-4">
                                                <div class="contact-item">
                                                    <div class="contact-icon-wrapper mb-2">
                                                        <i class="fas fa-user-tie"></i>
                                                    </div>
                                                    <small class="text-muted d-block mb-1">Penanggung Jawab</small>
                                                    <strong class="d-block">{{ $service->responsible_person }}</strong>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($service->phone_number)
                                            <div class="col-12 col-sm-6 col-lg-4">
                                                <div class="contact-item">
                                                    <div class="contact-icon-wrapper mb-2">
                                                        <i class="fas fa-phone"></i>
                                                    </div>
                                                    <small class="text-muted d-block mb-1">Telefon</small>
                                                    <strong class="d-block">{{ $service->phone_number }}</strong>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($service->contact_email)
                                            <div class="col-12 col-sm-6 col-lg-4">
                                                <div class="contact-item">
                                                    <div class="contact-icon-wrapper mb-2">
                                                        <i class="fas fa-envelope"></i>
                                                    </div>
                                                    <small class="text-muted d-block mb-1">Email</small>
                                                    <strong class="d-block" style="word-break: break-word;">{{ $service->contact_email }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Additional Links -->
                        @if ($service->form_download_link || $service->tutorial_link)
                            <div class="mb-5">
                                <h3 class="mb-4"><i class="fas fa-link me-2 text-primary"></i>Tautan Berguna</h3>
                                <div class="d-flex flex-wrap gap-3">
                                    @if ($service->form_download_link)
                                        <a href="{{ $service->form_download_link }}" target="_blank" rel="noopener" class="btn btn-outline-primary">
                                            <i class="fas fa-download me-2"></i>Download Formulir
                                        </a>
                                    @endif

                                    @if ($service->tutorial_link)
                                        <a href="{{ $service->tutorial_link }}" target="_blank" rel="noopener" class="btn btn-outline-info">
                                            <i class="fas fa-play-circle me-2"></i>Panduan/Tutorial
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="mt-5 pt-4 border-top d-flex flex-wrap gap-3">
                            <a href="{{ route('public.layanan') }}" class="btn btn-outline-gradient"><i
                                    class="fas fa-arrow-left me-2"></i>Kembali</a>
                            @if ($service->external_link)
                                <a href="{{ $service->external_link }}" target="_blank" rel="noopener"
                                    class="btn btn-gradient">
                                    Kunjungi Tautan <i class="fas fa-external-link-alt ms-2"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .info-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(79, 172, 254, 0.05));
            border: 1px solid rgba(79, 172, 254, 0.1);
            border-radius: 15px;
            padding: 1.5rem;
            height: 100%;
            transition: all 0.3s ease;
            text-align: left !important;
        }

        .info-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.2);
        }

        .info-card .d-flex {
            text-align: left !important;
        }

        .info-card .d-flex > div {
            text-align: left !important;
        }

        .info-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .info-icon i {
            color: white;
            font-size: 1.2rem;
        }

        .info-card h6 {
            text-align: left !important;
            margin-bottom: 0.25rem;
        }

        .info-card p {
            text-align: left !important;
            margin-bottom: 0;
        }

        .content-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(79, 172, 254, 0.02));
            border: 1px solid rgba(79, 172, 254, 0.1);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 1rem;
            text-align: left !important;
        }

        .service-content h1,
        .service-content h2,
        .service-content h3,
        .service-content h4 {
            font-weight: 600;
            margin-top: 2rem;
            text-align: left !important;
        }

        .service-content p {
            margin-bottom: 1.25rem;
            text-align: left !important;
        }

        .service-content ul,
        .service-content ol {
            margin-bottom: 1.25rem;
            padding-left: 1.25rem;
            text-align: left !important;
        }

        .service-content ul li {
            margin-bottom: 0.5rem;
            text-align: left !important;
        }

        .service-content ul li::marker {
            color: #4facfe;
        }

        .service-content img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            margin: 1rem 0;
        }

        .alert-warning {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 193, 7, 0.05));
            border: 1px solid rgba(255, 193, 7, 0.3);
            border-radius: 12px;
        }

        .contact-item {
            text-align: left !important;
            padding: 1.25rem;
            background: rgba(79, 172, 254, 0.03);
            border-radius: 12px;
            transition: all 0.3s ease;
            height: 100%;
        }

        .contact-item:hover {
            background: rgba(79, 172, 254, 0.08);
            transform: translateY(-2px);
        }

        .contact-icon-wrapper {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .contact-icon-wrapper i {
            color: white;
            font-size: 1.1rem;
        }

        .contact-item small {
            text-align: left !important;
        }

        .contact-item strong {
            text-align: left !important;
            font-size: 0.95rem;
        }

        @media (max-width: 768px) {
            .info-card {
                padding: 1rem;
            }

            .content-card {
                padding: 1.5rem;
            }

            .contact-item {
                padding: 1rem;
                margin-bottom: 0.5rem;
            }

            .contact-icon-wrapper {
                width: 35px;
                height: 35px;
            }

            .contact-icon-wrapper i {
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .info-card {
                padding: 0.875rem;
            }

            .content-card {
                padding: 1.25rem;
            }

            .service-content h3 {
                font-size: 1.25rem;
            }

            .service-content h5 {
                font-size: 1.1rem;
            }
        }
    </style>
@endpush
