@extends('admin.layouts.app')

@section('title', 'Kelola Galeri')

@section('content')
    <div class="container-fluid">
        <!-- Modern Page Header -->
        @include('admin.partials.page-header', [
            'title' => 'Kelola Galeri',
            'subtitle' => 'Kelola foto dan gambar untuk website Dinas Koperasi dengan mudah dan terorganisir',
            'icon' => 'fas fa-images',
            'breadcrumb' => 'Galeri',
            'primaryAction' => [
                'url' => route('admin.galleries.create'),
                'text' => 'Tambah Foto',
                'icon' => 'fas fa-plus',
            ],
            'secondaryActions' => [
                [
                    'text' => 'Pilih Banyak',
                    'icon' => 'fas fa-check-square',
                    'onclick' => 'bulkSelect()',
                    'title' => 'Pilih beberapa foto sekaligus',
                ],
            ],
            'quickStats' => [
                [
                    'value' => $galleries->count(),
                    'label' => 'Total Foto',
                    'icon' => 'fas fa-images',
                ],
                [
                    'value' => $galleries->sum('views'),
                    'label' => 'Views',
                    'icon' => 'fas fa-eye',
                ],
            ],
        ])

        <!-- Filter & Stats -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="glass-card">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" class="form-control form-control-glass" placeholder="Cari foto..."
                                id="searchGallery">
                        </div>
                        <div class="col-md-3">
                            <select class="form-control form-control-glass" id="filterCategory">
                                <option value="">Semua Kategori</option>
                                <option value="kegiatan">Kegiatan</option>
                                <option value="rapat">Rapat</option>
                                <option value="acara">Acara</option>
                                <option value="fasilitas">Fasilitas</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control form-control-glass" id="filterDate">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-glass w-100" onclick="resetFilters()">
                                <i class="fas fa-sync-alt"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Total Foto</div>
                            <div class="h4 mb-0">{{ $galleries->count() }}</div>
                        </div>
                        <div class="stat-icon-small">
                            <i class="fas fa-images"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gallery Grid -->
        <div class="glass-card">
            @if ($galleries->count() > 0)
                <div class="gallery-grid">
                    @foreach ($galleries as $gallery)
                        <div class="gallery-item" data-category="{{ $gallery->category }}">
                            <div class="gallery-image-container">
                                <img src="{{ Storage::url($gallery->image) }}" alt="{{ $gallery->title }}"
                                    class="gallery-image">
                                <div class="gallery-overlay">
                                    <div class="gallery-actions">
                                        <a href="{{ route('admin.galleries.show', $gallery->id) }}"
                                            class="action-btn view-btn" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.galleries.edit', $gallery->id) }}"
                                            class="action-btn edit-btn" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="action-btn delete-btn" onclick="deleteImage({{ $gallery->id }})"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <div class="gallery-checkbox">
                                        <input type="checkbox" class="form-check-input" value="{{ $gallery->id }}">
                                    </div>
                                </div>
                            </div>
                            <div class="gallery-info">
                                <h6 class="gallery-title">{{ $gallery->title }}</h6>
                                <p class="gallery-description">{{ Str::limit($gallery->description, 60) }}</p>
                                <div class="gallery-meta">
                                    <span class="badge badge-glass">{{ ucfirst($gallery->category) }}</span>
                                    <div class="gallery-stats">
                                        <span><i class="fas fa-eye"></i> {{ $gallery->views }}</span>
                                        <span><i class="fas fa-heart"></i> {{ $gallery->likes }}</span>
                                    </div>
                                </div>
                                <div class="gallery-date">
                                    {{ $gallery->created_at->format('d M Y') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-images"></i>
                    </div>
                    <h5>Belum Ada Foto</h5>
                    <p class="text-muted">Mulai dengan menambahkan foto pertama ke galeri</p>
                    <a href="{{ route('admin.galleries.create') }}" class="btn btn-primary-glass">
                        <i class="fas fa-plus"></i> Tambah Foto
                    </a>
                </div>
            @endif
        </div>

        <!-- Bulk Actions -->
        <div class="bulk-actions" id="bulkActions" style="display: none;">
            <div class="glass-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span id="selectedCount">0</span> foto dipilih
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-glass" onclick="bulkEdit()">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-danger-glass" onclick="bulkDelete()">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                        <button class="btn btn-glass" onclick="clearSelection()">
                            <i class="fas fa-times"></i> Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .gallery-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 20px;
                padding: 20px;
            }

            .gallery-item {
                background: rgba(255, 255, 255, 0.05);
                border-radius: 12px;
                overflow: hidden;
                transition: all 0.3s ease;
                border: 1px solid rgba(255, 255, 255, 0.1);
            }

            .gallery-item:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            }

            .gallery-image-container {
                position: relative;
                height: 200px;
                overflow: hidden;
            }

            .gallery-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.3s ease;
            }

            .gallery-item:hover .gallery-image {
                transform: scale(1.05);
            }

            .gallery-overlay {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.7);
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .gallery-item:hover .gallery-overlay {
                opacity: 1;
            }

            .gallery-actions {
                display: flex;
                gap: 10px;
            }

            .action-btn {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                border: none;
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
                text-decoration: none;
            }

            .view-btn {
                background: var(--info-color);
            }

            .edit-btn {
                background: var(--warning-color);
            }

            .delete-btn {
                background: var(--danger-color);
            }

            .action-btn:hover {
                transform: scale(1.1);
                color: white;
            }

            .gallery-checkbox {
                position: absolute;
                top: 10px;
                right: 10px;
            }

            .gallery-info {
                padding: 15px;
            }

            .gallery-title {
                color: var(--text-primary);
                margin-bottom: 8px;
                font-weight: 600;
            }

            .gallery-description {
                color: var(--text-secondary);
                margin-bottom: 10px;
                font-size: 14px;
            }

            .gallery-meta {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 10px;
            }

            .gallery-stats {
                display: flex;
                gap: 15px;
                color: var(--text-secondary);
                font-size: 12px;
            }

            .gallery-date {
                color: var(--text-secondary);
                font-size: 12px;
            }

            .badge-glass {
                background: rgba(255, 255, 255, 0.1);
                color: var(--text-primary);
                border: 1px solid rgba(255, 255, 255, 0.2);
                padding: 4px 8px;
                border-radius: 12px;
                font-size: 11px;
            }

            .empty-state {
                text-align: center;
                padding: 60px 20px;
                color: var(--text-secondary);
            }

            .empty-icon {
                font-size: 64px;
                margin-bottom: 20px;
                opacity: 0.5;
            }

            .bulk-actions {
                position: fixed;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%);
                z-index: 1000;
                min-width: 300px;
            }

            .stat-icon-small {
                width: 40px;
                height: 40px;
                background: var(--accent-color);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #000;
            }

            @media (max-width: 768px) {
                .gallery-grid {
                    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                    gap: 15px;
                    padding: 15px;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Handle flash messages
            @if(session('success'))
                showToast('{{ session('success') }}', 'success');
            @endif

            @if(session('error'))
                showToast('{{ session('error') }}', 'error');
            @endif

            @if($errors->any())
                showToast('{{ $errors->first() }}', 'error');
            @endif

            let bulkMode = false;
            let selectedItems = [];

            function bulkSelect() {
                bulkMode = !bulkMode;
                const checkboxes = document.querySelectorAll('.gallery-checkbox input');
                const button = event.target.closest('button');

                if (bulkMode) {
                    button.innerHTML = '<i class="fas fa-times"></i> Batal Pilih';
                    button.classList.add('btn-warning-glass');
                    checkboxes.forEach(checkbox => {
                        checkbox.style.display = 'block';
                        checkbox.addEventListener('change', updateSelection);
                    });
                } else {
                    button.innerHTML = '<i class="fas fa-check-square"></i> Pilih Banyak';
                    button.classList.remove('btn-warning-glass');
                    checkboxes.forEach(checkbox => {
                        checkbox.style.display = 'none';
                        checkbox.checked = false;
                    });
                    clearSelection();
                }
            }

            function updateSelection() {
                const checkboxes = document.querySelectorAll('.gallery-checkbox input:checked');
                selectedItems = Array.from(checkboxes).map(cb => cb.value);

                const bulkActions = document.getElementById('bulkActions');
                const selectedCount = document.getElementById('selectedCount');

                if (selectedItems.length > 0) {
                    bulkActions.style.display = 'block';
                    selectedCount.textContent = selectedItems.length;
                } else {
                    bulkActions.style.display = 'none';
                }
            }

            function clearSelection() {
                selectedItems = [];
                const checkboxes = document.querySelectorAll('.gallery-checkbox input');
                checkboxes.forEach(checkbox => checkbox.checked = false);
                document.getElementById('bulkActions').style.display = 'none';
            }

            async function deleteImage(id) {
                const confirmed = await confirmAction({
                    title: 'Hapus Foto',
                    message: 'Yakin ingin menghapus foto ini?',
                    confirmText: 'Hapus',
                    icon: 'trash',
                    variant: 'danger'
                });

                if (confirmed) {
                    showLoading();
                    // Implementasi delete
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/gallery/${id}`;  // Fixed: use 'gallery' not 'galleries'
                    form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
                    document.body.appendChild(form);
                    form.submit();
                }
            }

            async function bulkDelete() {
                if (selectedItems.length === 0) return;

                const confirmed = await confirmAction({
                    title: 'Hapus Foto Terpilih',
                    message: `Yakin ingin menghapus ${selectedItems.length} foto yang dipilih?`,
                    confirmText: 'Hapus Semua',
                    icon: 'trash',
                    variant: 'danger'
                });

                if (confirmed) {
                    showLoading();
                    // Implementasi bulk delete
                    console.log('Bulk delete:', selectedItems);
                    // TODO: Implement actual bulk delete logic
                    showToast('Fitur bulk delete akan segera tersedia', 'info');
                    hideLoading();
                }
            }

            function resetFilters() {
                document.getElementById('searchGallery').value = '';
                document.getElementById('filterCategory').value = '';
                document.getElementById('filterDate').value = '';

                // Show all items
                const items = document.querySelectorAll('.gallery-item');
                items.forEach(item => item.style.display = 'block');
            }

            // Search functionality
            document.getElementById('searchGallery').addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const items = document.querySelectorAll('.gallery-item');

                items.forEach(item => {
                    const title = item.querySelector('.gallery-title').textContent.toLowerCase();
                    const description = item.querySelector('.gallery-description').textContent.toLowerCase();

                    if (title.includes(searchTerm) || description.includes(searchTerm)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });

            // Category filter
            document.getElementById('filterCategory').addEventListener('change', function() {
                const category = this.value;
                const items = document.querySelectorAll('.gallery-item');

                items.forEach(item => {
                    if (!category || item.dataset.category === category) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        </script>
    @endpush
@endsection
