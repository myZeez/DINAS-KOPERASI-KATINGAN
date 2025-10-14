@extends('public.layouts.app')

@section('title', $service->title)

@section('content')
    <section class="section" style="padding-top:140px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-9">
                    <article class="modern-card p-4 p-md-5">
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
                            <div class="mb-4 text-center">
                                <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}"
                                    class="img-fluid rounded-4 shadow-sm" style="max-height:420px; object-fit:cover;">
                            </div>
                        @endif

                        <div class="service-content" style="font-size:1.05rem; line-height:1.75; color:#1f2937;">
                            {!! $service->content_detail
                                ? $service->content_detail
                                : '<p>Tidak ada konten detail tambahan untuk layanan ini.</p>' !!}
                        </div>

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
                    </article>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .service-content h1,
        .service-content h2,
        .service-content h3,
        .service-content h4 {
            font-weight: 600;
            margin-top: 2rem;
        }

        .service-content p {
            margin-bottom: 1.25rem;
        }

        .service-content ul,
        .service-content ol {
            margin-bottom: 1.25rem;
            padding-left: 1.25rem;
        }

        .service-content img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            margin: 1rem 0;
        }
    </style>
@endpush
