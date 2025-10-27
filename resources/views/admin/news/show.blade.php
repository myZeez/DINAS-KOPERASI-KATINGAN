@extends('admin.layouts.app')

@section('title', 'Detail Berita')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    @include('admin.partials.page-header', [
        'title' => 'Detail Berita',
        'subtitle' => 'Informasi lengkap tentang berita',
        'icon' => 'fas fa-newspaper',
        'breadcrumb' => 'Detail Berita',
        'primaryAction' => [
            'url' => route('admin.news.edit', $news->id),
            'text' => 'Edit Berita',
            'icon' => 'fas fa-edit',
        ],
        'secondaryActions' => [
            [
                'url' => 'javascript:void(0)',
                'text' => 'Kembali',
                'icon' => 'fas fa-arrow-left',
                'onclick' => 'goBackToNews()',
            ],
        ],
    ])

    <div class="custom-grid">
        <!-- News Content -->
        <div class="news-card">
            <div class="news-card-header">
                <h5>
                    <i class="fas fa-newspaper"></i>{{ $news->title }}
                </h5>
            </div>
            <div class="news-card-body">
                @if($news->image)
                    <div class="news-image-container">
                        <img src="{{ asset('storage/' . $news->image) }}"
                             alt="{{ $news->title }}"
                             class="news-image">
                    </div>
                @endif

                <div class="news-content">
                    {!! $news->content !!}
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- News Information -->
            <div class="sidebar-card">
                <div class="sidebar-card-header">
                    <h6>
                        <i class="fas fa-info-circle"></i>Informasi Berita
                    </h6>
                </div>
                <div class="sidebar-card-body">
                    <div class="info-item">
                        <label class="info-label">Status</label>
                        <div class="info-value">
                            @if($news->status == 'published')
                                <span class="status-badge published">Published</span>
                            @else
                                <span class="status-badge draft">Draft</span>
                            @endif
                        </div>
                    </div>

                    <div class="info-item">
                        <label class="info-label">Penulis</label>
                        <div class="info-value">{{ $news->user->name ?? 'Admin' }}</div>
                    </div>

                    <div class="info-item">
                        <label class="info-label">Tanggal Dibuat</label>
                        <div class="info-value">{{ $news->created_at->format('d F Y, H:i') }}</div>
                    </div>

                    <div class="info-item">
                        <label class="info-label">Terakhir Diupdate</label>
                        <div class="info-value">{{ $news->updated_at->format('d F Y, H:i') }}</div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="sidebar-card">
                <div class="sidebar-card-header">
                    <h6>
                        <i class="fas fa-cogs"></i>Aksi
                    </h6>
                </div>
                <div class="sidebar-card-body">
                    <div class="action-buttons">
                        <a href="{{ route('admin.news.edit', $news->id) }}" class="custom-btn btn-edit">
                            <i class="fas fa-edit"></i>Edit Berita
                        </a>

                        @if($news->status == 'draft')
                            <form action="{{ route('admin.news.update', $news->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="published">
                                <button type="submit" class="custom-btn btn-publish"
                                        onclick="return confirm('Publikasikan berita ini?')">
                                    <i class="fas fa-globe"></i>Publikasikan
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.news.update', $news->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="draft">
                                <button type="submit" class="custom-btn btn-unpublish"
                                        onclick="return confirm('Jadikan draft?')">
                                    <i class="fas fa-eye-slash"></i>Jadikan Draft
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('admin.news.destroy', $news->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="custom-btn btn-delete"
                                    onclick="return confirm('Yakin ingin menghapus berita ini?')">
                                <i class="fas fa-trash"></i>Hapus Berita
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Custom Grid Layout */
    .custom-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-top: 2rem;
    }

    /* News Content Card */
    .news-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .news-card-header {
        background: rgba(255, 255, 255, 0.05);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .news-card-header h5 {
        color: rgba(255, 255, 255, 0.95);
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .news-card-header i {
        color: #4facfe;
        font-size: 1.125rem;
    }

    .news-card-body {
        padding: 2rem;
    }

    /* News Image Styling */
    .news-image-container {
        margin-bottom: 2.5rem;
        text-align: center;
    }

    .news-image {
        width: 100%;
        max-height: 400px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }

    /* News Content */
    .news-content {
        line-height: 1.8;
        color: rgba(255, 255, 255, 0.95);
        font-size: 1.05rem;
    }

    .news-content * {
        color: inherit !important;
        background: transparent !important;
    }

    .news-content p {
        margin-bottom: 1.25rem;
    }

    .news-content h1, .news-content h2, .news-content h3, .news-content h4 {
        margin-top: 2rem;
        margin-bottom: 1.25rem;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.95) !important;
    }

    .news-content ul, .news-content ol {
        margin-bottom: 1.25rem;
        padding-left: 1.5rem;
    }

    .news-content li {
        margin-bottom: 0.5rem;
    }

    /* Sidebar Cards */
    .sidebar-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
    }

    .sidebar-card-header {
        background: rgba(255, 255, 255, 0.05);
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px 16px 0 0;
    }

    .sidebar-card-header h6 {
        color: rgba(255, 255, 255, 0.95);
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .sidebar-card-header i {
        color: #4facfe;
        font-size: 1rem;
    }

    .sidebar-card-body {
        padding: 1.5rem;
    }

    /* Info Items */
    .info-item {
        margin-bottom: 1.25rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .info-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .info-label {
        font-size: 0.875rem;
        color: rgba(255, 255, 255, 0.6);
        margin-bottom: 0.5rem;
        display: block;
        font-weight: 500;
    }

    .info-value {
        color: rgba(255, 255, 255, 0.95);
        font-size: 0.95rem;
        font-weight: 500;
    }

    /* Badge Styling */
    .status-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        border: 1px solid transparent;
    }

    .status-badge.published {
        background: rgba(40, 167, 69, 0.2);
        color: #28a745;
        border-color: rgba(40, 167, 69, 0.3);
    }

    .status-badge.draft {
        background: rgba(255, 193, 7, 0.2);
        color: #ffd93d;
        border-color: rgba(255, 193, 7, 0.3);
    }

    /* Custom Buttons */
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .custom-btn {
        padding: 0.75rem 1.25rem;
        border-radius: 12px;
        border: 1px solid transparent;
        font-weight: 500;
        font-size: 0.9rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        cursor: pointer;
        background: none;
        width: 100%;
    }

    /* Primary Button (Edit) */
    .btn-edit {
        background: rgba(79, 172, 254, 0.2);
        color: #4facfe;
        border-color: rgba(79, 172, 254, 0.3);
    }

    .btn-edit:hover {
        background: rgba(79, 172, 254, 0.3);
        color: #4facfe;
        border-color: rgba(79, 172, 254, 0.5);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(79, 172, 254, 0.3);
    }

    /* Success Button (Publish) */
    .btn-publish {
        background: rgba(40, 167, 69, 0.2);
        color: #28a745;
        border-color: rgba(40, 167, 69, 0.3);
    }

    .btn-publish:hover {
        background: rgba(40, 167, 69, 0.3);
        color: #28a745;
        border-color: rgba(40, 167, 69, 0.5);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    }

    /* Warning Button (Unpublish) */
    .btn-unpublish {
        background: rgba(255, 193, 7, 0.2);
        color: #ffd93d;
        border-color: rgba(255, 193, 7, 0.3);
    }

    .btn-unpublish:hover {
        background: rgba(255, 193, 7, 0.3);
        color: #ffd93d;
        border-color: rgba(255, 193, 7, 0.5);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
    }

    /* Danger Button (Delete) */
    .btn-delete {
        background: rgba(220, 53, 69, 0.2);
        color: #ff6b6b;
        border-color: rgba(220, 53, 69, 0.3);
    }

    .btn-delete:hover {
        background: rgba(220, 53, 69, 0.3);
        color: #ff6b6b;
        border-color: rgba(220, 53, 69, 0.5);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .custom-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .news-card-header {
            padding: 1.25rem 1.5rem;
        }

        .news-card-body {
            padding: 1.5rem;
        }

        .sidebar-card-header {
            padding: 1rem 1.25rem;
        }

        .sidebar-card-body {
            padding: 1.25rem;
        }

        .news-image {
            max-height: 300px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
// Global function for back button
function goBackToNews() {
    console.log('Navigating back to news index...');
    window.location.href = '{{ route("admin.news.index") }}';
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('News show page loaded');

    // Handle any back button clicks
    const backButtons = document.querySelectorAll('[data-action="back"], .btn-back, a[href*="news"]');

    backButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            console.log('Back button clicked');
            e.preventDefault();
            e.stopPropagation();
            goBackToNews();
        });
    });

    // Add keyboard shortcut for back (Escape or Alt + Left Arrow)
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' || (e.altKey && e.key === 'ArrowLeft')) {
            e.preventDefault();
            console.log('Keyboard shortcut triggered');
            goBackToNews();
        }
    });

    // Force any button with "Kembali" text to work
    setTimeout(function() {
        const kembaliButtons = document.querySelectorAll('button, a');
        kembaliButtons.forEach(btn => {
            if (btn.textContent.includes('Kembali')) {
                console.log('Found Kembali button:', btn);
                btn.addEventListener('click', function(e) {
                    console.log('Kembali button clicked');
                    e.preventDefault();
                    e.stopPropagation();
                    goBackToNews();
                });

                // Force proper href
                if (btn.tagName === 'A') {
                    btn.href = 'javascript:void(0)';
                }
            }
        });
    }, 500);
});
</script>
@endpush
