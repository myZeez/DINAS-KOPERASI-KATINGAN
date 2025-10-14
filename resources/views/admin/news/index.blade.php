@extends('admin.layouts.app')

@section('title', 'Kelola Berita')

@section('content')
    <div class="container-fluid">
        <!-- Modern Page Header -->
        @include('admin.partials.page-header', [
            'title' => 'Kelola Berita',
            'subtitle' => 'Kelola artikel berita dan pengumuman untuk website Dinas Koperasi',
            'icon' => 'fas fa-newspaper',
            'breadcrumb' => 'Berita',
            'primaryAction' => [
                'url' => route('admin.news.create'),
                'text' => 'Tambah Berita',
                'icon' => 'fas fa-plus',
            ],
            'secondaryActions' => [
                [
                    'onclick' => "window.location.href='" . route('admin.trash.index') . "'",
                    'text' => 'Kelola Sampah',
                    'icon' => 'fas fa-trash',
                    'title' => 'Kelola item yang dihapus',
                ],
            ],
            'quickStats' => [
                [
                    'value' => $news->count(),
                    'label' => 'Total Berita',
                    'icon' => 'fas fa-newspaper',
                ],
                [
                    'value' => $news->where('status', 'draft')->count(),
                    'label' => 'Draft',
                    'icon' => 'fas fa-edit',
                ],
                [
                    'value' => $trashedNewsCount ?? 0,
                    'label' => 'Di Sampah',
                    'icon' => 'fas fa-trash',
                ],
            ],
        ])

        <!-- Filter & Search -->
        <div class="glass-card mb-4">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" class="form-control form-control-glass" placeholder="Cari berita..." id="searchNews">
                </div>
                <div class="col-md-3">
                    <select class="form-control form-control-glass" id="filterStatus">
                        <option value="">Semua Status</option>
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
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

        <!-- News List -->
        <div class="glass-card">
            <div class="table-responsive">
                <table class="table table-glass">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 15%;">Gambar</th>
                            <th style="width: 30%;">Judul</th>
                            <th style="width: 15%;">Tanggal</th>
                            <th style="width: 10%;">Status</th>
                            <th style="width: 10%;">Penulis</th>
                            <th style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($news ?? [] as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if ($item['image'])
                                        <img src="{{ $item['image'] }}" alt="News Image"
                                            style="width: 60px; height: 40px; object-fit: cover; border-radius: 8px;">
                                    @else
                                        <div
                                            style="width: 60px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-image" style="color: var(--text-secondary);"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $item['title'] }}</div>
                                    <div style="font-size: 12px; color: var(--text-secondary);">
                                        {{ Str::limit($item['excerpt'] ?? '', 50) }}
                                    </div>
                                </td>
                                <td>{{ $item['created_at'] }}</td>
                                <td>
                                    @if ($item['status'] == 'published')
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
                                </td>
                                <td>Admin</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.news.show', $item['id']) }}" class="btn btn-sm"
                                            style="background: rgba(108, 207, 127, 0.2); color: var(--info-color); border: none; border-radius: 6px; margin-right: 5px;">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.news.edit', $item['id']) }}" class="btn btn-sm"
                                            style="background: rgba(255, 215, 61, 0.2); color: var(--warning-color); border: none; border-radius: 6px; margin-right: 5px;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="deleteNews({{ $item['id'] }})" class="btn btn-sm"
                                            style="background: rgba(255, 107, 107, 0.2); color: var(--danger-color); border: none; border-radius: 6px;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div style="opacity: 0.7;">
                                        <i class="fas fa-newspaper" style="font-size: 48px; margin-bottom: 10px;"></i>
                                        <div>Belum ada berita</div>
                                        <div style="font-size: 12px; color: var(--text-secondary);">
                                            Klik tombol "Tambah Berita" untuk membuat berita pertama
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if (count($news) > 0)
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3"
                    style="border-top: 1px solid rgba(255,255,255,0.1);">
                    <div style="color: var(--text-secondary); font-size: 14px;">
                        Menampilkan {{ min(5, count($news)) }} dari {{ count($news) }} berita
                    </div>
                    <div class="pagination-custom">
                        <button class="btn-pagination" id="prevBtn" onclick="changePage(-1)" disabled>
                            <i class="fas fa-chevron-left"></i>
                            <span>Sebelumnya</span>
                        </button>
                        <div class="page-numbers" id="pageNumbers">
                            <button class="page-number active" onclick="goToPage(1)">1</button>
                            @if (count($news) > 5)
                                <button class="page-number" onclick="goToPage(2)">2</button>
                            @endif
                            @if (count($news) > 10)
                                <button class="page-number" onclick="goToPage(3)">3</button>
                            @endif
                        </div>
                        <button class="btn-pagination" id="nextBtn" onclick="changePage(1)"
                            {{ count($news) <= 5 ? 'disabled' : '' }}>
                            <span>Berikutnya</span>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            @endif
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
                    <p>Apakah Anda yakin ingin menghapus berita ini?</p>
                    <div class="alert alert-info"
                        style="background: rgba(23, 162, 184, 0.1); border: 1px solid rgba(23, 162, 184, 0.2); color: var(--info-color);">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Info:</strong> Berita akan dipindahkan ke sampah dan dapat dipulihkan kembali dari halaman
                        "Kelola Sampah".
                    </div>
                </div>
                <div class="modal-footer" style="border: none;">
                    <button type="button" class="btn btn-glass" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn"
                            style="background: var(--danger-color); color: white; border: none; border-radius: 8px; padding: 8px 16px;">
                            <i class="fas fa-trash"></i> Pindahkan ke Sampah
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Pagination Styles */
        .pagination-custom {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-pagination {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: var(--text-primary);
            padding: 8px 12px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-pagination:hover:not(:disabled) {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateY(-1px);
        }

        .btn-pagination:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .page-numbers {
            display: flex;
            gap: 4px;
            margin: 0 8px;
        }

        .page-number {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            color: var(--text-primary);
            padding: 6px 10px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 32px;
            text-align: center;
        }

        .page-number:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .page-number.active {
            background: var(--accent-color);
            color: #000;
            font-weight: 600;
        }

        /* Responsive pagination */
        @media (max-width: 768px) {
            .btn-pagination span {
                display: none;
            }

            .btn-pagination {
                padding: 8px;
                min-width: 36px;
                justify-content: center;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        let currentPage = 1;
        const itemsPerPage = 5;
        let totalItems = {{ count($news) }};
        let filteredItems = totalItems;

        function deleteNews(id) {
            const form = document.getElementById('deleteForm');
            form.action = `/admin/news/${id}`;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        } // Pagination functions
        function showPage(page) {
            const newsRows = document.querySelectorAll('#newsTable tbody tr:not(.no-data)');
            const startIndex = (page - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;

            newsRows.forEach((row, index) => {
                if (index >= startIndex && index < endIndex) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            updatePaginationControls();
        }

        function updatePaginationControls() {
            const totalPages = Math.ceil(filteredItems / itemsPerPage);

            // Update prev/next buttons
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            if (prevBtn) prevBtn.disabled = currentPage === 1;
            if (nextBtn) nextBtn.disabled = currentPage === totalPages;

            // Update page numbers
            const pageNumbers = document.getElementById('pageNumbers');
            if (pageNumbers) {
                pageNumbers.innerHTML = '';

                for (let i = 1; i <= totalPages; i++) {
                    const button = document.createElement('button');
                    button.className = `page-number ${i === currentPage ? 'active' : ''}`;
                    button.textContent = i;
                    button.onclick = () => goToPage(i);
                    pageNumbers.appendChild(button);
                }
            }
        }

        function goToPage(page) {
            currentPage = page;
            showPage(page);
        }

        function changePage(direction) {
            const totalPages = Math.ceil(filteredItems / itemsPerPage);
            const newPage = currentPage + direction;

            if (newPage >= 1 && newPage <= totalPages) {
                goToPage(newPage);
            }
        }

        function resetFilters() {
            document.getElementById('searchNews').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterDate').value = '';

            const newsRows = document.querySelectorAll('#newsTable tbody tr');
            newsRows.forEach(row => {
                row.classList.remove('filtered-out');
            });

            filteredItems = totalItems;
            currentPage = 1;
            showPage(1);
        }

        // Search and filter functions
        function filterNews() {
            const searchTerm = document.getElementById('searchNews').value.toLowerCase();
            const statusFilter = document.getElementById('filterStatus').value;
            const dateFilter = document.getElementById('filterDate').value;

            const newsRows = document.querySelectorAll('#newsTable tbody tr:not(.no-data)');
            let visibleCount = 0;

            newsRows.forEach(row => {
                let showRow = true;

                // Search filter
                if (searchTerm) {
                    const title = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    if (!title.includes(searchTerm)) {
                        showRow = false;
                    }
                }

                // Status filter
                if (statusFilter) {
                    const status = row.querySelector('.badge-glass').textContent.toLowerCase();
                    if (!status.includes(statusFilter.toLowerCase())) {
                        showRow = false;
                    }
                }

                // Date filter
                if (dateFilter) {
                    const rowDate = row.querySelector('td:nth-child(4)').textContent;
                    if (!rowDate.includes(dateFilter)) {
                        showRow = false;
                    }
                }

                if (showRow) {
                    visibleCount++;
                    row.classList.remove('filtered-out');
                } else {
                    row.classList.add('filtered-out');
                }
            });

            filteredItems = visibleCount;
            currentPage = 1;
            showPage(1);
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Add table ID for easier manipulation
            const table = document.querySelector('.table');
            if (table) {
                table.id = 'newsTable';
            }

            // Initialize pagination
            showPage(1);

            // Add search and filter listeners
            const searchInput = document.getElementById('searchNews');
            const statusFilter = document.getElementById('filterStatus');
            const dateFilter = document.getElementById('filterDate');

            if (searchInput) searchInput.addEventListener('input', filterNews);
            if (statusFilter) statusFilter.addEventListener('change', filterNews);
            if (dateFilter) dateFilter.addEventListener('change', filterNews);

            // Hide filtered items with CSS
            const style = document.createElement('style');
            style.textContent = `
            .filtered-out {
                display: none !important;
            }
        `;
            document.head.appendChild(style);
        });

        // Search functionality
        document.getElementById('searchNews').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const title = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
                const content = row.querySelector('td:nth-child(3) div:last-child')?.textContent
                    .toLowerCase() || '';

                if (title.includes(searchTerm) || content.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Status filter
        document.getElementById('filterStatus').addEventListener('change', function() {
            const filterValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const status = row.querySelector('td:nth-child(5)')?.textContent.toLowerCase() || '';

                if (!filterValue || status.includes(filterValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Add entrance animation
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateY(20px)';
                row.style.transition = 'all 0.3s ease';

                setTimeout(() => {
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, index * 50);
            });
        });
    </script>
@endpush
