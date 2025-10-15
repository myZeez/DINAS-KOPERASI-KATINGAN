@extends('public.layouts.app')

@section('title', $news->title)

@section('content')
    <!-- Header Section -->
    <section class="hero" style="min-height: 60vh;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <div class="hero-content">
                        <nav aria-label="breadcrumb" class="mb-4">
                            <ol class="breadcrumb justify-content-center">
                                <li class="breadcrumb-item"><a href="{{ route('public.home') }}"
                                        class="text-white-50">Beranda</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('public.berita') }}"
                                        class="text-white-50">Berita</a></li>
                                <li class="breadcrumb-item active text-white" aria-current="page">{{ $news->title }}</li>
                            </ol>
                        </nav>
                        <h1 class="mb-4">{{ $news->title }}</h1>
                        <div class="d-flex justify-content-center align-items-center gap-3 text-white-50">
                            <i class="fas fa-calendar"></i>
                            <span>{{ $news->published_at->format('d F Y') }}</span>
                            <span>•</span>
                            <i class="fas fa-user"></i>
                            <span>{{ $news->user->name ?? 'Admin' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- News Content -->
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    @if ($news->image)
                        <div class="news-cover mb-4">
                            <img src="{{ asset('storage/' . $news->image) }}" class="w-100" alt="{{ $news->title }}">
                        </div>
                    @endif

                    <article class="modern-card p-4 p-md-5">
                        <div class="news-content">
                            {!! $news->content !!}
                        </div>

                        <!-- Share Buttons -->
                        <div class="mt-5 pt-4 border-top">
                            <h6 class="mb-3">Bagikan Berita Ini:</h6>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                                    target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="fab fa-facebook-f me-2"></i>Facebook
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($news->title) }}"
                                    target="_blank" class="btn btn-outline-dark btn-sm">
                                    <i class="fab fa-x-twitter me-2"></i>X
                                </a>
                                <a href="https://www.instagram.com/" target="_blank" 
                                    onclick="navigator.clipboard.writeText('{{ $news->title }} - {{ request()->fullUrl() }}'); alert('Link berita disalin! Silakan paste di Instagram.');"
                                    class="btn btn-outline-danger btn-sm">
                                    <i class="fab fa-instagram me-2"></i>Instagram
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($news->title . ' - ' . request()->fullUrl()) }}"
                                    target="_blank" class="btn btn-outline-success btn-sm">
                                    <i class="fab fa-whatsapp me-2"></i>WhatsApp
                                </a>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <!-- Related News -->
    @if ($relatedNews->count() > 0)
        <section class="section section-alternate">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="section-title">Berita Terkait</h2>
                    <p class="section-subtitle">Berita lainnya yang mungkin menarik untuk Anda</p>
                </div>

                <div class="row g-4">
                    @foreach ($relatedNews as $related)
                        <div class="col-md-4">
                            <div class="modern-card h-100">
                                @if ($related->image)
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $related->image) }}"
                                            class="img-fluid rounded-3 w-100" style="height: 200px; object-fit: cover;"
                                            alt="{{ $related->title }}">
                                    </div>
                                @endif

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $related->published_at->format('d M Y') }}
                                    </small>
                                </div>

                                <h5 class="mb-3">
                                    <a href="{{ route('public.berita.detail', $related) }}"
                                        class="text-decoration-none text-dark">
                                        {{ $related->title }}
                                    </a>
                                </h5>

                                <p class="text-muted mb-3">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($related->content), 100) }}
                                </p>

                                <a href="{{ route('public.berita.detail', $related) }}"
                                    class="btn btn-outline-gradient btn-sm">
                                    Baca Selengkapnya <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-5">
                    <a href="{{ route('public.berita') }}" class="btn btn-gradient">
                        <i class="fas fa-newspaper me-2"></i>Lihat Semua Berita
                    </a>
                </div>
            </div>
        </section>
    @endif
@endsection

@push('styles')
    <style>
        .news-content {
            font-size: 1.1rem;
            line-height: 1.8;
            /* Force base text color to black */
            color: #000;
            /* Disable text selection */
            user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
        }

        /* Ensure all nested elements inside the news content also become black, overriding inline styles from editor */
        .news-content * {
            color: #000 !important;
            background: transparent !important;
            /* Remove any pasted background */
        }

        /* Keep links distinguishable (optional subtle underline) */
        .news-content a {
            text-decoration: underline;
        }

        .news-content p {
            margin-bottom: 1.5rem;
        }

        .news-cover img {
            display: block;
            width: 100%;
            max-height: 520px;
            object-fit: cover;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .breadcrumb {
            background: none;
            padding: 0;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            content: ">";
            color: rgba(255, 255, 255, 0.5);
        }

        /* Limit the width and provide comfortable reading measure */
        article.modern-card {
            max-width: 880px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Avoid card stretching with huge empty space; the article already wraps content naturally.
                               If there is any min-height from global styles, neutralize it here. */
        article.modern-card {
            min-height: unset !important;
        }

        /* Force dark text color for news content */
        .news-content {
            color: #2d3748 !important;
            line-height: 1.8;
        }

        .news-content * {
            color: inherit !important;
        }

        .news-content p,
        .news-content span,
        .news-content div,
        .news-content h1,
        .news-content h2,
        .news-content h3,
        .news-content h4,
        .news-content h5,
        .news-content h6,
        .news-content li,
        .news-content td,
        .news-content th {
            color: #2d3748 !important;
        }

        .news-content strong,
        .news-content b {
            color: #1a202c !important;
            font-weight: 600;
        }

        .news-content a {
            color: #3182ce !important;
            text-decoration: underline;
        }

        .news-content a:hover {
            color: #2c5aa0 !important;
        }

        /* Optional: tighter bottom spacing so it doesn't visually collide with footer */
        article.modern-card {
            margin-bottom: 40px;
        }

        @media (max-width: 992px) {
            article.modern-card {
                padding: 2rem !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.news-content');
            if (!container) return;

            // Prevent selecting / copying text inside the news content
            ['copy', 'cut', 'contextmenu'].forEach(evt => {
                container.addEventListener(evt, function(e) {
                    e.preventDefault();
                });
            });
            container.addEventListener('selectstart', function(e) {
                e.preventDefault();
            });

            // Remove only problematic white/light color styles, keep dark colors
            container.querySelectorAll('[style]').forEach(el => {
                const style = el.getAttribute('style');
                if (/color:/i.test(style)) {
                    // Only remove white, light colors that are not visible
                    const cleaned = style
                        .replace(
                            /color\s*:\s*(white|#fff|#ffffff|rgb\(255,\s*255,\s*255\)|rgba\(255,\s*255,\s*255,.*?\))\s*;?/gi,
                            '')
                        .replace(/color\s*:\s*rgba?\([2-9]\d{2}.*?\)\s*;?/gi,
                        '') // Remove very light colors (rgb values > 200)
                        .trim();
                    if (cleaned) {
                        el.setAttribute('style', cleaned);
                    } else {
                        el.removeAttribute('style');
                    }
                }
            });
        });
    </script>
@endpush
