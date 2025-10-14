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
                    <!-- Tabs for different content types -->
                    <ul class="nav nav-tabs" id="trashTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="news-tab" data-bs-toggle="tab" data-bs-target="#news"
                                type="button" role="tab">
                                <i class="fas fa-newspaper me-1"></i>
                                Berita <span class="badge bg-danger ms-1">{{ $trashedNews->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="gallery-tab" data-bs-toggle="tab" data-bs-target="#gallery"
                                type="button" role="tab">
                                <i class="fas fa-images me-1"></i>
                                Galeri <span class="badge bg-danger ms-1">{{ $trashedGalleries->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#review"
                                type="button" role="tab">
                                <i class="fas fa-star me-1"></i>
                                Ulasan <span class="badge bg-danger ms-1">{{ $trashedReviews->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="carousel-tab" data-bs-toggle="tab" data-bs-target="#carousel"
                                type="button" role="tab">
                                <i class="fas fa-carousel me-1"></i>
                                Carousel <span class="badge bg-danger ms-1">{{ $trashedCarousels->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="service-tab" data-bs-toggle="tab" data-bs-target="#service"
                                type="button" role="tab">
                                <i class="fas fa-cogs me-1"></i>
                                Layanan <span class="badge bg-danger ms-1">{{ $trashedServices->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="content-tab" data-bs-toggle="tab" data-bs-target="#content"
                                type="button" role="tab">
                                <i class="fas fa-file-alt me-1"></i>
                                Konten <span class="badge bg-danger ms-1">{{ $trashedContent->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="structure-tab" data-bs-toggle="tab" data-bs-target="#structure"
                                type="button" role="tab">
                                <i class="fas fa-sitemap me-1"></i>
                                Struktur <span class="badge bg-danger ms-1">{{ $trashedStructures->count() }}</span>
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="trashTabsContent">
                        <!-- News Tab -->
                        <div class="tab-pane fade show active" id="news" role="tabpanel">
                            @include('admin.trash.partials.content-table', [
                                'items' => $trashedNews,
                                'type' => 'news',
                                'typeName' => 'Berita',
                                'columns' => ['title' => 'Judul', 'deleted_at' => 'Dihapus Pada'],
                            ])
                        </div>

                        <!-- Gallery Tab -->
                        <div class="tab-pane fade" id="gallery" role="tabpanel">
                            @include('admin.trash.partials.content-table', [
                                'items' => $trashedGalleries,
                                'type' => 'gallery',
                                'typeName' => 'Galeri',
                                'columns' => ['title' => 'Judul', 'deleted_at' => 'Dihapus Pada'],
                            ])
                        </div>

                        <!-- Review Tab -->
                        <div class="tab-pane fade" id="review" role="tabpanel">
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
                        <div class="tab-pane fade" id="carousel" role="tabpanel">
                            @include('admin.trash.partials.content-table', [
                                'items' => $trashedCarousels,
                                'type' => 'carousel',
                                'typeName' => 'Carousel',
                                'columns' => ['title' => 'Judul', 'deleted_at' => 'Dihapus Pada'],
                            ])
                        </div>

                        <!-- Service Tab -->
                        <div class="tab-pane fade" id="service" role="tabpanel">
                            @include('admin.trash.partials.content-table', [
                                'items' => $trashedServices,
                                'type' => 'service',
                                'typeName' => 'Layanan',
                                'columns' => ['title' => 'Judul', 'deleted_at' => 'Dihapus Pada'],
                            ])
                        </div>

                        <!-- Content Tab -->
                        <div class="tab-pane fade" id="content" role="tabpanel">
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
                        <div class="tab-pane fade" id="structure" role="tabpanel">
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

    @push('scripts')
        <script>
            $(document).ready(function() {
                console.log('Trash page scripts loaded');
                let currentType = '';
                let currentId = '';

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
            });
        </script>
    @endpush
@endsection
