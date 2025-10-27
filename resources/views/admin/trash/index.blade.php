@extends('admin.layouts.app')

@section('title', 'Kelola Sampah')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">
                        <i class="fas fa-trash me-2"></i>Kelola Sampah
                    </h1>
                    <div>
                        <span class="badge bg-secondary">
                            <i class="fas fa-items me-1"></i>
                            Total: {{ $totalTrash }} item
                        </span>
                    </div>
                </div>

                @if ($totalTrash == 0)
                    <div class="text-center py-5">
                        <i class="fas fa-trash-alt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada item di sampah</h5>
                        <p class="text-muted">Semua data aman tersimpan</p>
                    </div>
                @else
                    <!-- Custom Tab Navigation for Trash -->
                    <div class="glass-card mb-4">
                        <div class="custom-tab-container">
                            <div class="custom-tab-wrapper">
                                <button class="custom-tab-button active" data-tab="news">
                                    <i class="fas fa-newspaper"></i>
                                    <span>Berita</span>
                                    <span class="tab-badge">{{ $trashedNews->count() }}</span>
                                </button>
                                <button class="custom-tab-button" data-tab="gallery">
                                    <i class="fas fa-images"></i>
                                    <span>Galeri</span>
                                    <span class="tab-badge">{{ $trashedGalleries->count() }}</span>
                                </button>
                                <button class="custom-tab-button" data-tab="review">
                                    <i class="fas fa-star"></i>
                                    <span>Ulasan</span>
                                    <span class="tab-badge">{{ $trashedReviews->count() }}</span>
                                </button>
                                <button class="custom-tab-button" data-tab="carousel">
                                    <i class="fas fa-images"></i>
                                    <span>Carousel</span>
                                    <span class="tab-badge">{{ $trashedCarousels->count() }}</span>
                                </button>
                                <button class="custom-tab-button" data-tab="service">
                                    <i class="fas fa-cogs"></i>
                                    <span>Layanan</span>
                                    <span class="tab-badge">{{ $trashedServices->count() }}</span>
                                </button>
                                <button class="custom-tab-button" data-tab="content">
                                    <i class="fas fa-file-alt"></i>
                                    <span>Konten</span>
                                    <span class="tab-badge">{{ $trashedContent->count() }}</span>
                                </button>
                                <button class="custom-tab-button" data-tab="structure">
                                    <i class="fas fa-sitemap"></i>
                                    <span>Struktur</span>
                                    <span class="tab-badge">{{ $trashedStructures->count() }}</span>
                                </button>
                            </div>
                            <div class="custom-tab-indicator"></div>
                        </div>
                    </div>

                    <div class="tab-content" id="trashTabsContent">
                        <!-- News Tab -->
                        <div class="tab-pane fade show active tab-content-animation" id="news" role="tabpanel">
                            @include('admin.trash.partials.content-table', [
                                'items' => $trashedNews,
                                'type' => 'news',
                                'typeName' => 'Berita',
                                'columns' => ['title' => 'Judul', 'deleted_at' => 'Dihapus Pada'],
                            ])
                        </div>

                        <!-- Gallery Tab -->
                        <div class="tab-pane fade tab-content-animation" id="gallery" role="tabpanel">
                            @include('admin.trash.partials.content-table', [
                                'items' => $trashedGalleries,
                                'type' => 'gallery',
                                'typeName' => 'Galeri',
                                'columns' => ['title' => 'Judul', 'deleted_at' => 'Dihapus Pada'],
                            ])
                        </div>

                        <!-- Review Tab -->
                        <div class="tab-pane fade tab-content-animation" id="review" role="tabpanel">
                            @include('admin.trash.partials.content-table', [
                                'items' => $trashedReviews,
                                'type' => 'review',
                                'typeName' => 'Ulasan',
                                'columns' => [
                                    'reviewer_name' => 'Nama Pengulas',
                                    'rating' => 'Rating',
                                    'deleted_at' => 'Dihapus Pada',
                                ],
                            ])
                        </div>

                        <!-- Carousel Tab -->
                        <div class="tab-pane fade tab-content-animation" id="carousel" role="tabpanel">
                            @include('admin.trash.partials.content-table', [
                                'items' => $trashedCarousels,
                                'type' => 'carousel',
                                'typeName' => 'Carousel',
                                'columns' => ['title' => 'Judul', 'deleted_at' => 'Dihapus Pada'],
                            ])
                        </div>

                        <!-- Service Tab -->
                        <div class="tab-pane fade tab-content-animation" id="service" role="tabpanel">
                            @include('admin.trash.partials.content-table', [
                                'items' => $trashedServices,
                                'type' => 'service',
                                'typeName' => 'Layanan',
                                'columns' => ['title' => 'Judul', 'deleted_at' => 'Dihapus Pada'],
                            ])
                        </div>

                        <!-- Content Tab -->
                        <div class="tab-pane fade tab-content-animation" id="content" role="tabpanel">
                            @include('admin.trash.partials.content-table', [
                                'items' => $trashedContent,
                                'type' => 'content',
                                'typeName' => 'Konten Publik',
                                'columns' => [
                                    'section' => 'Bagian',
                                    'key' => 'Kunci',
                                    'deleted_at' => 'Dihapus Pada',
                                ],
                            ])
                        </div>

                        <!-- Structure Tab -->
                        <div class="tab-pane fade tab-content-animation" id="structure" role="tabpanel">
                            @include('admin.trash.partials.content-table', [
                                'items' => $trashedStructures,
                                'type' => 'structure',
                                'typeName' => 'Struktur Organisasi',
                                'columns' => [
                                    'name' => 'Nama',
                                    'position' => 'Jabatan',
                                    'deleted_at' => 'Dihapus Pada',
                                ],
                            ])
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Restore Modal -->
    <div class="modal fade" id="restoreModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content"
                style="background: var(--glass-bg); backdrop-filter: blur(20px); border: 1px solid var(--glass-border); border-radius: 16px;">
                <div class="modal-header" style="border: none;">
                    <h5 class="modal-title" style="color: var(--text-primary);">
                        <i class="fas fa-undo text-success"></i> Pulihkan Item
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="color: var(--text-secondary);">
                    <p>Apakah Anda yakin ingin memulihkan item ini?</p>
                    <div class="alert alert-info"
                        style="background: rgba(23, 162, 184, 0.1); border: 1px solid rgba(23, 162, 184, 0.2); color: var(--info-color);">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Info:</strong> Item akan dikembalikan ke daftar aktif dan dapat diakses kembali.
                    </div>
                </div>
                <div class="modal-footer" style="border: none;">
                    <button type="button" class="btn btn-glass" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn"
                        style="background: var(--success-color); color: white; border: none; border-radius: 8px; padding: 8px 16px;"
                        id="confirmRestore">
                        <i class="fas fa-undo me-1"></i>Pulihkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Force Delete Modal -->
    <div class="modal fade" id="forceDeleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content"
                style="background: var(--glass-bg); backdrop-filter: blur(20px); border: 1px solid var(--glass-border); border-radius: 16px;">
                <div class="modal-header" style="border: none;">
                    <h5 class="modal-title" style="color: var(--text-primary);">
                        <i class="fas fa-exclamation-triangle text-danger"></i> Hapus Permanen
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="color: var(--text-secondary);">
                    <div class="alert alert-danger"
                        style="background: rgba(255, 107, 107, 0.1); border: 1px solid rgba(255, 107, 107, 0.2); color: var(--danger-color);">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Peringatan!</strong> Tindakan ini tidak dapat dibatalkan.
                    </div>
                    <p>Apakah Anda yakin ingin menghapus item ini secara permanen?</p>
                    <p class="text-muted small">Data akan hilang selamanya dan tidak dapat dipulihkan.</p>
                </div>
                <div class="modal-footer" style="border: none;">
                    <button type="button" class="btn btn-glass" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn"
                        style="background: var(--danger-color); color: white; border: none; border-radius: 8px; padding: 8px 16px;"
                        id="confirmForceDelete">
                        <i class="fas fa-trash me-1"></i>Hapus Permanen
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Empty Trash Modal -->
    <div class="modal fade" id="emptyTrashModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content"
                style="background: var(--glass-bg); backdrop-filter: blur(20px); border: 1px solid var(--glass-border); border-radius: 16px;">
                <div class="modal-header" style="border: none;">
                    <h5 class="modal-title" style="color: var(--text-primary);">
                        <i class="fas fa-trash-alt text-danger"></i> Kosongkan Sampah
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="color: var(--text-secondary);">
                    <div class="alert alert-danger"
                        style="background: rgba(255, 107, 107, 0.1); border: 1px solid rgba(255, 107, 107, 0.2); color: var(--danger-color);">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Peringatan!</strong> Tindakan ini tidak dapat dibatalkan.
                    </div>
                    <p>Apakah Anda yakin ingin mengosongkan seluruh sampah untuk kategori <strong
                            id="emptyTrashType"></strong>?</p>
                    <p class="text-muted small">Semua data dalam kategori ini akan hilang selamanya.</p>
                </div>
                <div class="modal-footer" style="border: none;">
                    <button type="button" class="btn btn-glass" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn"
                        style="background: var(--danger-color); color: white; border: none; border-radius: 8px; padding: 8px 16px;"
                        id="confirmEmptyTrash">
                        <i class="fas fa-trash-alt me-1"></i>Kosongkan Sampah
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Custom Manual Tab Navigation - Trash Page */
            .custom-tab-container {
                position: relative;
                background: transparent;
                border-bottom: 2px solid rgba(79, 172, 254, 0.15);
                overflow: hidden;
            }

            .custom-tab-wrapper {
                display: flex;
                position: relative;
                gap: 0;
                flex-wrap: wrap;
            }

            .custom-tab-button {
                position: relative;
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 14px 16px;
                background: transparent;
                border: none;
                color: rgba(255, 255, 255, 0.7);
                font-size: 14px;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
                border-radius: 0;
                outline: none;
                white-space: nowrap;
                z-index: 2;
                flex: 1;
                justify-content: center;
                min-width: 0;
            }

            .custom-tab-button i {
                font-size: 15px;
                opacity: 0.8;
                transition: all 0.25s ease;
                flex-shrink: 0;
            }

            .custom-tab-button span:not(.tab-badge) {
                transition: all 0.25s ease;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .tab-badge {
                background: rgba(220, 53, 69, 0.8);
                color: white;
                font-size: 11px;
                font-weight: 600;
                padding: 2px 6px;
                border-radius: 10px;
                margin-left: 4px;
                min-width: 18px;
                text-align: center;
                transition: all 0.25s ease;
            }

            /* Hover State */
            .custom-tab-button:hover {
                color: rgba(255, 255, 255, 0.9);
                background: rgba(79, 172, 254, 0.08);
            }

            .custom-tab-button:hover i {
                opacity: 1;
                transform: translateY(-1px);
            }

            .custom-tab-button:hover .tab-badge {
                background: rgba(220, 53, 69, 1);
                transform: scale(1.05);
            }

            /* Active State */
            .custom-tab-button.active {
                color: #4facfe;
                font-weight: 600;
                background: rgba(79, 172, 254, 0.12);
            }

            .custom-tab-button.active i {
                opacity: 1;
                color: #4facfe;
                text-shadow: 0 0 8px rgba(79, 172, 254, 0.4);
            }

            .custom-tab-button.active span:not(.tab-badge) {
                color: #4facfe;
            }

            .custom-tab-button.active .tab-badge {
                background: #dc3545;
                box-shadow: 0 0 8px rgba(220, 53, 69, 0.4);
            }

            /* Moving Indicator */
            .custom-tab-indicator {
                position: absolute;
                bottom: 0;
                left: 0;
                height: 3px;
                background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
                border-radius: 2px 2px 0 0;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 0 0 12px rgba(79, 172, 254, 0.5);
                z-index: 3;
            }

            /* Focus States for Accessibility */
            .custom-tab-button:focus {
                outline: 2px solid rgba(79, 172, 254, 0.5);
                outline-offset: -2px;
            }

            /* Animation for tab content */
            .tab-content-animation {
                opacity: 0;
                transform: translateY(20px);
                transition: all 0.3s ease;
            }

            .tab-content-animation.active {
                opacity: 1;
                transform: translateY(0);
            }

            /* Responsive Design */
            @media (max-width: 992px) {
                .custom-tab-button {
                    font-size: 13px;
                    padding: 12px 14px;
                }

                .custom-tab-button span:not(.tab-badge) {
                    display: none;
                }

                .custom-tab-button i {
                    font-size: 16px;
                }
            }

            @media (max-width: 768px) {
                .custom-tab-wrapper {
                    justify-content: space-around;
                }

                .custom-tab-button {
                    flex: 1;
                    min-width: 60px;
                    padding: 12px 8px;
                }
            }

            @media (max-width: 480px) {
                .custom-tab-button {
                    padding: 10px 6px;
                    font-size: 12px;
                }

                .tab-badge {
                    font-size: 10px;
                    padding: 1px 4px;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            $(document).ready(function() {
                console.log('Trash page scripts loaded');
                let currentType = '';
                let currentId = '';

                // Initialize Custom Tabs for Trash Page
                initTrashTabs();

                // Restore functionality
                $('.restore-btn').on('click', function() {
                    console.log('Restore button clicked');
                    currentType = $(this).data('type');
                    currentId = $(this).data('id');
                    console.log('Type:', currentType, 'ID:', currentId);
                    $('#restoreModal').modal('show');
                });

                $('#confirmRestore').on('click', function() {
                    console.log('Confirm restore clicked');
                    $.post('{{ route('admin.trash.restore') }}', {
                            _token: '{{ csrf_token() }}',
                            type: currentType,
                            id: currentId
                        })
                        .done(function(response) {
                            console.log('Restore response:', response);
                            if (response.success) {
                                showToast('Item berhasil dipulihkan!', 'success');
                                location.reload();
                            } else {
                                showToast('Error: ' + response.message, 'error');
                            }
                        })
                        .fail(function(xhr) {
                            console.log('Restore failed:', xhr);
                            showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
                        });
                });

                // Force delete functionality
                $('.force-delete-btn').on('click', function() {
                    console.log('Force delete button clicked');
                    currentType = $(this).data('type');
                    currentId = $(this).data('id');
                    console.log('Type:', currentType, 'ID:', currentId);
                    $('#forceDeleteModal').modal('show');
                });

                $('#confirmForceDelete').on('click', function() {
                    console.log('Confirm force delete clicked');
                    $.post('{{ route('admin.trash.force-delete') }}', {
                            _token: '{{ csrf_token() }}',
                            type: currentType,
                            id: currentId
                        })
                        .done(function(response) {
                            console.log('Force delete response:', response);
                            if (response.success) {
                                showToast('Item berhasil dihapus permanen!', 'success');
                                location.reload();
                            } else {
                                showToast('Error: ' + response.message, 'error');
                            }
                        })
                        .fail(function(xhr) {
                            console.log('Force delete failed:', xhr);
                            showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
                        });
                });

                // Empty trash functionality
                $('.empty-trash-btn').on('click', function() {
                    console.log('Empty trash button clicked');
                    currentType = $(this).data('type');
                    const typeName = $(this).data('type-name');
                    console.log('Empty trash type:', currentType, 'typeName:', typeName);
                    $('#emptyTrashType').text(typeName);
                    $('#emptyTrashModal').modal('show');
                });

                $('#confirmEmptyTrash').on('click', function() {
                    console.log('Confirm empty trash clicked');
                    $.post('{{ route('admin.trash.empty') }}', {
                            _token: '{{ csrf_token() }}',
                            type: currentType
                        })
                        .done(function(response) {
                            console.log('Empty trash response:', response);
                            if (response.success) {
                                showToast('Sampah berhasil dikosongkan!', 'success');
                                location.reload();
                            } else {
                                showToast('Error: ' + response.message, 'error');
                            }
                        })
                        .fail(function(xhr) {
                            console.log('Empty trash failed:', xhr);
                            showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
                        });
                });

                // Custom Tab Functionality for Trash Page
                function initTrashTabs() {
                    const tabButtons = $('.custom-tab-button');
                    const tabContents = $('.tab-pane');
                    const indicator = $('.custom-tab-indicator');

                    // Set initial indicator position
                    updateIndicatorPosition();

                    // Tab button click handler
                    tabButtons.on('click', function() {
                        const targetTab = $(this).data('tab');

                        // Remove active class from all buttons and contents
                        tabButtons.removeClass('active');
                        tabContents.removeClass('show active').addClass('fade');

                        // Add active class to clicked button
                        $(this).addClass('active');

                        // Show target content with animation
                        const targetContent = $(`#${targetTab}`);
                        setTimeout(() => {
                            targetContent.removeClass('fade').addClass('show active');
                        }, 50);

                        // Update indicator position
                        updateIndicatorPosition();

                        // Update URL without page reload
                        const newUrl = new URL(window.location);
                        newUrl.searchParams.set('tab', targetTab === 'news' ? '' : targetTab);
                        if (targetTab === 'news') {
                            newUrl.searchParams.delete('tab');
                        }
                        window.history.pushState({}, '', newUrl);
                    });

                    // Update indicator position function
                    function updateIndicatorPosition() {
                        const activeButton = $('.custom-tab-button.active');
                        if (activeButton.length) {
                            const buttonOffset = activeButton.offset();
                            const containerOffset = $('.custom-tab-wrapper').offset();
                            const leftPosition = buttonOffset.left - containerOffset.left;
                            const buttonWidth = activeButton.outerWidth();

                            indicator.css({
                                'left': leftPosition + 'px',
                                'width': buttonWidth + 'px'
                            });
                        }
                    }

                    // Handle browser back/forward
                    window.addEventListener('popstate', function() {
                        const urlParams = new URLSearchParams(window.location.search);
                        const tabParam = urlParams.get('tab') || 'news';

                        // Activate correct tab
                        tabButtons.removeClass('active');
                        $(`.custom-tab-button[data-tab="${tabParam}"]`).addClass('active');

                        // Show correct content
                        tabContents.removeClass('show active').addClass('fade');
                        setTimeout(() => {
                            $(`#${tabParam}`).removeClass('fade').addClass('show active');
                        }, 50);

                        updateIndicatorPosition();
                    });

                    // Initialize from URL parameter
                    const urlParams = new URLSearchParams(window.location.search);
                    const initialTab = urlParams.get('tab');
                    if (initialTab && initialTab !== 'news') {
                        $(`.custom-tab-button[data-tab="${initialTab}"]`).click();
                    }

                    // Handle window resize
                    $(window).on('resize', function() {
                        setTimeout(updateIndicatorPosition, 100);
                    });
                }
            });
        </script>
    @endpush
@endsection
