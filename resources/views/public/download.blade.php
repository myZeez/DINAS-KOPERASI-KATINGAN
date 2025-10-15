@extends('public.layouts.app')

@section('title', 'Download Center')

@section('content')
    <!-- Hero Section -->
    <section class="download-hero-section">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <div class="hero-content">
                        <div class="hero-badge mb-3">
                            <i class="fas fa-download me-2"></i>
                            Pusat Unduhan
                        </div>
                        <h1 class="hero-title mb-4">Download Center</h1>
                        <p class="hero-subtitle mb-4">
                            Akses berbagai dokumen, formulir, dan file penting dari Dinas Koperasi untuk kebutuhan Anda
                        </p>

                        <!-- Search Form -->
                        <form method="GET" class="search-form">
                            <div class="search-container">
                                <input type="text" name="search" class="search-input"
                                    placeholder="Cari dokumen atau file..." value="{{ request('search') }}">
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

    <!-- Files Section -->
    <section class="section-modern scroll-reveal">
        <div class="container">
            @if ($files->count() > 0)
                <div class="text-center mb-5">
                    <h2 class="section-title">File & Dokumen Tersedia</h2>
                    <p class="section-subtitle">Download berbagai dokumen resmi dan formulir yang Anda butuhkan</p>
                </div>

                <div class="row g-4">
                    @foreach ($files as $file)
                        <div class="col-lg-4 col-md-6">
                            <div class="modern-card download-card h-100">
                                <div class="download-card-header">
                                    <div class="file-icon">
                                        @php
                                            $extension = pathinfo($file->original_filename, PATHINFO_EXTENSION);
                                            $iconClass = match (strtolower($extension)) {
                                                'pdf' => 'fas fa-file-pdf text-danger',
                                                'doc', 'docx' => 'fas fa-file-word text-primary',
                                                'xls', 'xlsx' => 'fas fa-file-excel text-success',
                                                'ppt', 'pptx' => 'fas fa-file-powerpoint text-warning',
                                                'zip', 'rar' => 'fas fa-file-archive text-info',
                                                'jpg', 'jpeg', 'png', 'gif' => 'fas fa-file-image text-purple',
                                                default => 'fas fa-file text-secondary',
                                            };
                                        @endphp
                                        <i class="{{ $iconClass }}"></i>
                                    </div>
                                    <div class="file-badge">
                                        <span
                                            class="badge bg-gradient">{{ strtoupper(pathinfo($file->original_filename, PATHINFO_EXTENSION)) }}</span>
                                    </div>
                                </div>

                                <div class="download-card-body">
                                    <h5 class="download-title mb-3">{{ $file->title }}</h5>

                                    @if ($file->description)
                                        <p class="download-description text-muted mb-3">
                                            {{ Str::limit($file->description, 100) }}</p>
                                    @endif

                                    <div class="file-meta mb-4">
                                        <div class="meta-item">
                                            <i class="fas fa-file me-2 text-muted"></i>
                                            <small class="text-muted">{{ Str::limit($file->original_filename, 25) }}</small>
                                        </div>
                                        <div class="meta-item">
                                            <i class="fas fa-weight me-2 text-muted"></i>
                                            <small class="text-muted">{{ $file->formatted_size }}</small>
                                        </div>
                                        <div class="meta-item">
                                            <i class="fas fa-download me-2 text-muted"></i>
                                            <small class="text-muted">{{ $file->download_count }} unduhan</small>
                                        </div>
                                    </div>

                                    <div class="download-action">
                                        <a href="{{ route('public.downloads.download', $file) }}"
                                            class="btn btn-gradient w-100">
                                            <i class="fas fa-download me-2"></i>
                                            Download File
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($files->hasPages())
                    <div class="pagination-container mt-5">
                        {{ $files->links('partials.custom-pagination') }}
                    </div>
                @endif
            @else
                <div class="empty-state text-center py-5">
                    <div class="empty-icon mb-4">
                        <i class="fas fa-folder-open fa-3x text-muted"></i>
                    </div>
                    @if (request('search'))
                        <h5 class="empty-title mb-3">Tidak ada file yang ditemukan</h5>
                        <p class="empty-subtitle text-muted mb-4">Tidak ada hasil untuk "{{ request('search') }}". Coba
                            kata kunci lain.</p>
                        <a href="{{ route('public.downloads') }}" class="btn btn-outline-gradient">
                            <i class="fas fa-arrow-left me-2"></i>Lihat Semua File
                        </a>
                    @else
                        <h5 class="empty-title mb-3">Belum ada file tersedia</h5>
                        <p class="empty-subtitle text-muted mb-4">File dan dokumen akan segera tersedia untuk diunduh.</p>
                        <div class="empty-actions">
                            <a href="{{ route('public.home') }}" class="btn btn-outline-gradient">
                                <i class="fas fa-home me-2"></i>Kembali ke Beranda
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </section>

    <!-- Call to Action -->
    <section class="section-alternate scroll-reveal">
        <div class="container text-center">
            <h3 class="section-title mb-4">Butuh Bantuan?</h3>
            <p class="section-subtitle mb-4">Hubungi kami jika Anda memerlukan bantuan atau dokumen khusus</p>
            <div class="cta-buttons">
                <a href="{{ route('public.profile') }}" class="cta-btn primary">
                    <i class="fas fa-phone me-2"></i>
                    <span>Hubungi Kami</span>
                </a>
                <a href="{{ route('public.layanan') }}" class="cta-btn secondary">
                    <i class="fas fa-concierge-bell me-2"></i>
                    <span>Layanan Kami</span>
                </a>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .download-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
        }

        .download-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
            border-color: rgba(102, 126, 234, 0.2);
        }

        .download-card-header {
            padding: 1.5rem 1.5rem 0;
            position: relative;
        }

        .file-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 16px;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .file-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
        }

        .file-badge .badge {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.4rem 0.8rem;
        }

        .download-card-body {
            padding: 0 1.5rem 1.5rem;
        }

        .download-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2d3748;
            line-height: 1.4;
        }

        .download-description {
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .file-meta {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            font-size: 0.85rem;
        }

        .meta-item i {
            width: 16px;
            flex-shrink: 0;
        }

        .download-action .btn {
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .download-action .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        /* Search Form */
        .search-form {
            margin-top: 2rem;
        }

        .search-container {
            max-width: 500px;
            margin: 0 auto;
            position: relative;
            display: flex;
            border-radius: 50px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .search-input {
            flex: 1;
            border: none;
            padding: 15px 25px;
            font-size: 0.95rem;
            background: white;
            outline: none;
        }

        .search-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 15px 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .search-btn:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }

        /* Empty State */
        .empty-state {
            max-width: 500px;
            margin: 0 auto;
        }

        .empty-title {
            color: #4a5568;
            font-weight: 600;
        }

        .empty-subtitle {
            font-size: 1rem;
            line-height: 1.6;
        }

        /* CTA Buttons */
        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .cta-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .cta-btn.primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .cta-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .cta-btn.secondary {
            background: transparent;
            color: #667eea;
            border-color: #667eea;
        }

        .cta-btn.secondary:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }

        /* Text Colors */
        .text-purple {
            color: #8b5cf6 !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .download-card-header {
                padding: 1rem 1rem 0;
            }

            .download-card-body {
                padding: 0 1rem 1rem;
            }

            .search-container {
                max-width: 100%;
                margin: 0 1rem;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .cta-btn {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }
        }
    </style>
@endpush
