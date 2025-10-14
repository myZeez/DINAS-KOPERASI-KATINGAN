@extends('admin.layouts.app')

@section('title', 'Detail Foto')

@section('content')
    <div class="container-fluid">
        <!-- Modern Page Header -->
        @include('admin.partials.page-header', [
            'title' => 'Detail Foto',
            'subtitle' => 'Lihat informasi lengkap dan kelola foto galeri',
            'icon' => 'fas fa-image',
            'breadcrumb' => 'Detail Foto',
            'primaryAction' => [
                'url' => route('admin.galleries.index'),
                'text' => 'Kembali ke Galeri',
                'icon' => 'fas fa-arrow-left',
            ],
            'quickStats' => [
                [
                    'value' => '1.2k',
                    'label' => 'Views',
                    'icon' => 'fas fa-eye',
                ],
                [
                    'value' => '45',
                    'label' => 'Likes',
                    'icon' => 'fas fa-heart',
                ],
            ],
        ])

        <div class="row">
            <!-- Main Image -->
            <div class="col-lg-8">
                <div class="glass-card">
                    <div class="image-container">
                        <img src="{{ Storage::url($gallery->image) }}" alt="{{ $gallery->title }}" class="main-image">
                    </div>

                    <div class="image-info mt-4">
                        <h3 class="image-title">{{ $gallery->title }}</h3>
                        <p class="image-description">
                            {{ $gallery->description ?? 'Tidak ada deskripsi.' }}
                        </p>

                        <div class="image-tags">
                            <span class="tag">{{ ucfirst($gallery->category) }}</span>
                            @if ($gallery->tags)
                                @foreach (explode(',', $gallery->tags) as $tag)
                                    <span class="tag">{{ trim($tag) }}</span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="col-lg-4">
                <!-- Image Details -->
                <div class="glass-card">
                    <h5 class="mb-3">
                        <i class="fas fa-info-circle"></i> Informasi Foto
                    </h5>

                    <div class="info-grid">
                        <div class="info-item">
                            <label>Nama File:</label>
                            <span>{{ basename($gallery->image) }}</span>
                        </div>
                        <div class="info-item">
                            <label>Kategori:</label>
                            <span class="badge-glass">{{ ucfirst($gallery->category) }}</span>
                        </div>
                        <div class="info-item">
                            <label>Status:</label>
                            <span class="badge-glass">{{ ucfirst($gallery->status) }}</span>
                        </div>
                        <div class="info-item">
                            <label>Tanggal Upload:</label>
                            <span>{{ $gallery->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="info-item">
                            <label>Dilihat:</label>
                            <span>{{ number_format($gallery->views) }} kali</span>
                        </div>
                        <div class="info-item">
                            <label>Disukai:</label>
                            <span>{{ number_format($gallery->likes) }} kali</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="glass-card">
                    <h5 class="mb-3">
                        <i class="fas fa-tools"></i> Aksi
                    </h5>

                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.galleries.edit', $gallery->id) }}" class="btn btn-primary-glass">
                            <i class="fas fa-edit"></i> Edit Foto
                        </a>
                        <button class="btn btn-glass" onclick="downloadImage()">
                            <i class="fas fa-download"></i> Download
                        </button>
                        <button class="btn btn-glass" onclick="copyLink()">
                            <i class="fas fa-link"></i> Salin Link
                        </button>
                        <button class="btn btn-glass" onclick="setAsFeatured()"
                            style="background: rgba(255, 215, 61, 0.1); color: var(--warning-color);">
                            <i class="fas fa-star"></i> Set Unggulan
                        </button>
                        <button class="btn btn-glass" onclick="deleteImage({{ $id ?? 1 }})"
                            style="background: rgba(255, 107, 107, 0.1); color: var(--danger-color);">
                            <i class="fas fa-trash"></i> Hapus Foto
                        </button>
                    </div>
                </div>

                <!-- Related Images -->
                <div class="glass-card">
                    <h5 class="mb-3">
                        <i class="fas fa-images"></i> Foto Terkait
                    </h5>

                    <div class="related-images">
                        @forelse($relatedGalleries as $related)
                            <div class="related-item">
                                <img src="{{ Storage::url($related->image) }}" alt="{{ $related->title }}">
                                <div class="related-info">
                                    <div class="related-title">{{ Str::limit($related->title, 20) }}</div>
                                    <div class="related-date">{{ $related->created_at->format('d M Y') }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted">
                                <i class="fas fa-images"></i>
                                <p>Belum ada foto terkait</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content"
                style="background: var(--glass-bg); backdrop-filter: blur(20px); border: 1px solid var(--glass-border); border-radius: 16px;">
                <div class="modal-header" style="border: none;">
                    <h5 class="modal-title" style="color: var(--text-primary);">
                        <i class="fas fa-exclamation-triangle text-danger"></i> Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="color: var(--text-secondary);">
                    Apakah Anda yakin ingin menghapus foto ini? Tindakan ini tidak dapat dibatalkan.
                </div>
                <div class="modal-footer" style="border: none;">
                    <button type="button" class="btn btn-glass" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn"
                            style="background: var(--danger-color); color: white; border: none; border-radius: 8px; padding: 8px 16px;">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .image-container {
            position: relative;
            overflow: hidden;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.05);
            padding: 16px;
        }

        .main-image {
            width: 100%;
            height: auto;
            max-height: 600px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }

        .image-title {
            color: var(--text-primary);
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .image-description {
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .image-tags {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .tag {
            background: rgba(0, 255, 136, 0.1);
            color: var(--accent-color);
            padding: 4px 12px;
            border-radius: 16px;
            font-size: 12px;
            border: 1px solid rgba(0, 255, 136, 0.2);
        }

        .info-grid {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 8px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .info-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .info-item label {
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 500;
        }

        .info-item span {
            color: var(--text-primary);
            font-size: 13px;
        }

        .related-images {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .related-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .related-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(4px);
        }

        .related-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
        }

        .related-info {
            flex: 1;
        }

        .related-title {
            color: var(--text-primary);
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 2px;
        }

        .related-date {
            color: var(--text-secondary);
            font-size: 11px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .image-title {
                font-size: 1.4rem;
            }

            .main-image {
                max-height: 400px;
            }

            .info-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function deleteImage(id) {
            const form = document.getElementById('deleteForm');
            form.action = `/admin/galleries/${id}`;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        function downloadImage() {
            // Simulate download
            const link = document.createElement('a');
            link.href = document.querySelector('.main-image').src;
            link.download = 'foto-galeri.jpg';
            link.click();
        }

        function copyLink() {
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(() => {
                showToast('Link berhasil disalin!', 'success');
            });
        }

        function setAsFeatured() {
            showToast('Foto telah dijadikan unggulan!', 'success');
        }

        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: rgba(0, 255, 136, 0.9);
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        z-index: 9999;
        font-size: 14px;
    `;
            toast.textContent = message;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    </script>
@endpush
