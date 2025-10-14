@extends('admin.layouts.app')

@section('title', 'Detail Berita')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="page-title mb-0">
                <i class="fas fa-newspaper"></i> Detail Berita
            </h1>
            <div class="btn-group">
                <a href="{{ route('admin.news.index') }}" class="btn btn-glass">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('admin.news.edit', $news->id) }}" class="btn btn-primary-glass">
                    <i class="fas fa-edit"></i> Edit
                </a>
            </div>
        </div>

        <!-- News Detail Card -->
        <div class="glass-card">
            <div class="row">
                <div class="col-lg-8">
                    <!-- News Image -->
                    @if ($news->image)
                        <div class="news-image-container mb-4">
                            <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}"
                                class="img-fluid rounded-3" style="width: 100%; max-height: 400px; object-fit: cover;">
                        </div>
                    @endif

                    <!-- News Title -->
                    <h2 class="news-title mb-3" style="color: var(--text-primary); font-weight: 700;">
                        {{ $news->title }}
                    </h2>

                    <!-- News Meta Info -->
                    <div class="news-meta mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="meta-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>{{ $news->created_at->format('d F Y') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="meta-item">
                                    <i class="fas fa-user"></i>
                                    <span>{{ $news->user->name ?? 'Admin' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- News Content -->
                    <div class="news-content">
                        <div style="color: var(--text-primary); line-height: 1.8; font-size: 16px;">
                            {!! $news->content !!}
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Status Card -->
                    <div class="status-card mb-4">
                        <h5 style="color: var(--text-primary); margin-bottom: 20px;">
                            <i class="fas fa-info-circle"></i> Informasi Berita
                        </h5>

                        <div class="status-item mb-3">
                            <label style="color: var(--text-secondary); font-size: 14px;">Status:</label>
                            <div class="mt-1">
                                @if ($news->status == 'published')
                                    <span class="badge-glass status-published"
                                        style="background: rgba(0, 255, 136, 0.2); color: var(--accent-color);">
                                        <i class="fas fa-check-circle"></i>
                                        <span class="status-text">Published</span>
                                    </span>
                                @else
                                    <span class="badge-glass status-draft"
                                        style="background: rgba(255, 215, 61, 0.2); color: var(--warning-color);">
                                        <i class="fas fa-clock"></i>
                                        <span class="status-text">Draft</span>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="status-item mb-3">
                            <label style="color: var(--text-secondary); font-size: 14px;">Dibuat:</label>
                            <div style="color: var(--text-primary);">{{ $news->created_at->format('d F Y, H:i') }}</div>
                        </div>

                        <div class="status-item mb-3">
                            <label style="color: var(--text-secondary); font-size: 14px;">Diperbarui:</label>
                            <div style="color: var(--text-primary);">{{ $news->updated_at->format('d F Y, H:i') }}</div>
                        </div>

                        <div class="status-item mb-3">
                            <label style="color: var(--text-secondary); font-size: 14px;">ID Berita:</label>
                            <div style="color: var(--text-primary); font-family: monospace;">#{{ $news->id }}</div>
                        </div>
                    </div>

                    <!-- Actions Card -->
                    <div class="actions-card">
                        <h5 style="color: var(--text-primary); margin-bottom: 20px;">
                            <i class="fas fa-tools"></i> Aksi
                        </h5>

                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.news.edit', $news->id) }}" class="btn btn-primary-glass">
                                <i class="fas fa-edit"></i> Edit Berita
                            </a>

                            @if ($news->status == 'draft')
                                <button onclick="publishNews({{ $news->id }})" class="btn btn-glass"
                                    style="background: rgba(0, 255, 136, 0.1); color: var(--accent-color);">
                                    <i class="fas fa-globe"></i> Publikasikan
                                </button>
                            @else
                                <button onclick="unpublishNews({{ $news->id }})" class="btn btn-glass"
                                    style="background: rgba(255, 215, 61, 0.1); color: var(--warning-color);">
                                    <i class="fas fa-eye-slash"></i> Jadikan Draft
                                </button>
                            @endif

                            <button onclick="deleteNews({{ $news->id }})" class="btn btn-glass"
                                style="background: rgba(255, 107, 107, 0.1); color: var(--danger-color);">
                                <i class="fas fa-trash"></i> Hapus Berita
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .news-image-container {
                position: relative;
                overflow: hidden;
                border-radius: 12px;
            }

            .news-title {
                font-size: 2.5rem;
                line-height: 1.2;
            }

            .news-meta {
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                padding-bottom: 20px;
            }

            .meta-item {
                display: flex;
                align-items: center;
                gap: 8px;
                color: var(--text-secondary);
                font-size: 14px;
                margin-bottom: 10px;
            }

            .meta-item i {
                color: var(--accent-color);
                width: 16px;
            }

            .news-content {
                font-size: 16px;
                line-height: 1.8;
            }

            /* Force proper text styling for news content */
            .news-content * {
                color: var(--text-primary) !important;
                background: transparent !important;
                font-family: inherit !important;
            }

            .news-content p,
            .news-content div,
            .news-content span {
                color: var(--text-primary) !important;
                background: transparent !important;
            }

            .news-content a {
                color: var(--accent-color) !important;
                text-decoration: underline;
            }

            .news-content strong,
            .news-content b {
                font-weight: bold;
                color: var(--text-primary) !important;
            }

            .news-content em,
            .news-content i {
                font-style: italic;
                color: var(--text-primary) !important;
            }

            .status-card,
            .actions-card {
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 16px;
                padding: 24px;
            }

            .status-item {
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
                padding-bottom: 12px;
            }

            .status-item:last-child {
                border-bottom: none;
                padding-bottom: 0;
            }

            .status-item label {
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            @media (max-width: 768px) {
                .news-title {
                    font-size: 1.8rem;
                }

                .news-meta .row>div {
                    margin-bottom: 10px;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Clean up unwanted styles from copy-pasted content
            document.addEventListener('DOMContentLoaded', function() {
                const newsContent = document.querySelector('.news-content');
                if (newsContent) {
                    // Remove all inline styles from content elements
                    const allElements = newsContent.querySelectorAll('*');
                    allElements.forEach(function(element) {
                        // Remove style attribute
                        element.removeAttribute('style');

                        // Remove common styling attributes
                        element.removeAttribute('color');
                        element.removeAttribute('bgcolor');
                        element.removeAttribute('background');
                        element.removeAttribute('face');
                        element.removeAttribute('size');

                        // Remove class attributes that might contain styling
                        const classList = element.className;
                        if (classList && typeof classList === 'string') {
                            // Keep only basic formatting classes, remove others
                            const allowedClasses = ['bold', 'italic', 'underline'];
                            const classes = classList.split(' ');
                            const filteredClasses = classes.filter(cls => allowedClasses.includes(cls));
                            element.className = filteredClasses.join(' ');
                        }
                    });
                }
            });

            function publishNews(id) {
                if (confirm('Apakah Anda yakin ingin mempublikasikan berita ini?')) {
                    // Add your publish logic here
                    console.log('Publishing news ID:', id);
                    // You can make an AJAX call to update the status
                }
            }

            function unpublishNews(id) {
                if (confirm('Apakah Anda yakin ingin menjadikan berita ini sebagai draft?')) {
                    // Add your unpublish logic here
                    console.log('Unpublishing news ID:', id);
                    // You can make an AJAX call to update the status
                }
            }

            function deleteNews(id) {
                if (confirm('Apakah Anda yakin ingin menghapus berita ini? Tindakan ini tidak dapat dibatalkan.')) {
                    // Create form and submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/admin/news/' + id;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        </script>
    @endpush
@endsection
