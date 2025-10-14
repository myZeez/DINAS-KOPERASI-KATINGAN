@extends('admin.layouts.app')

@section('title', 'Log Aktivitas Admin')

@section('content')
    <div class="container-fluid">
        <!-- Modern Page Header -->
        @include('admin.partials.page-header', [
            'title' => 'Log Aktivitas Admin',
            'subtitle' => 'Monitor dan kelola semua aktivitas admin dalam sistem',
            'icon' => 'fas fa-history',
            'breadcrumb' => 'Activity Logs',
            'secondaryActions' => [
                [
                    'onclick' => "$('#clearLogsModal').modal('show')",
                    'text' => 'Hapus Semua Log',
                    'icon' => 'fas fa-trash-alt',
                    'title' => 'Hapus semua log aktivitas',
                ],
            ],
            'quickStats' => [
                [
                    'value' => $logs->total(),
                    'label' => 'Total Log',
                    'icon' => 'fas fa-history',
                ],
                [
                    'value' => $actions->count(),
                    'label' => 'Jenis Aksi',
                    'icon' => 'fas fa-cogs',
                ],
            ],
        ])

        <!-- Filter & Search -->
        <div class="glass-card mb-4">
            <div class="card-header-glass">
                <h6 class="mb-0" style="color: var(--text-primary);">
                    <i class="fas fa-filter me-2"></i>Filter Log
                </h6>
            </div>
            <div class="card-body-glass">
                <form method="GET" action="{{ route('admin.activity-logs.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="action" class="form-label-glass">Aksi</label>
                            <select name="action" id="action" class="form-control-glass">
                                <option value="">Semua Aksi</option>
                                @foreach ($actions as $action)
                                    <option value="{{ $action }}"
                                        {{ request('action') == $action ? 'selected' : '' }}>
                                        {{ ucfirst($action) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="model_type" class="form-label-glass">Tipe Model</label>
                            <select name="model_type" id="model_type" class="form-control-glass">
                                <option value="">Semua Model</option>
                                @foreach ($modelTypes as $modelType)
                                    <option value="{{ $modelType }}"
                                        {{ request('model_type') == $modelType ? 'selected' : '' }}>
                                        {{ class_basename($modelType) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="user_id" class="form-label-glass">Admin</label>
                            <select name="user_id" id="user_id" class="form-control-glass">
                                <option value="">Semua Admin</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="date_from" class="form-label-glass">Dari Tanggal</label>
                            <input type="date" name="date_from" id="date_from" class="form-control-glass"
                                value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date_to" class="form-label-glass">Sampai Tanggal</label>
                            <input type="date" name="date_to" id="date_to" class="form-control-glass"
                                value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-glass me-2">
                                <i class="fas fa-search me-1"></i>Filter
                            </button>
                            <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-glass-outline">
                                <i class="fas fa-times me-1"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Activity Logs -->
        <div class="glass-card">
            <div class="card-header-glass">
                <h6 class="mb-0" style="color: var(--text-primary);">
                    <i class="fas fa-list me-2"></i>Daftar Aktivitas
                    <span class="badge-glass status-info ms-2">{{ $logs->total() }} aktivitas</span>
                </h6>
            </div>
            <div class="card-body-glass">
                @if ($logs->count() == 0)
                    <div class="text-center py-5">
                        <i class="fas fa-history"
                            style="font-size: 48px; margin-bottom: 10px; opacity: 0.5; color: var(--text-secondary);"></i>
                        <div style="color: var(--text-secondary); font-size: 18px; margin-bottom: 5px;">Tidak ada log
                            aktivitas</div>
                        <div style="color: var(--text-secondary); font-size: 14px;">Belum ada aktivitas yang tercatat atau
                            sesuai dengan filter</div>
                    </div>
                @else
                    <div class="table-responsive-glass">
                        <table class="table-glass">
                            <thead>
                                <tr>
                                    <th class="col-waktu">Waktu</th>
                                    <th class="col-admin">Admin</th>
                                    <th class="col-aksi">Aksi</th>
                                    <th class="col-model">Model</th>
                                    <th class="col-deskripsi">Deskripsi</th>
                                    <th class="col-ip">IP Address</th>
                                    <th class="col-detail">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $log)
                                    <tr>
                                        <td>
                                            <small style="color: var(--text-secondary);">
                                                {{ $log->created_at->format('d/m/Y') }}<br>
                                                {{ $log->created_at->format('H:i:s') }}
                                            </small>
                                        </td>
                                        <td>
                                            @if ($log->user)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2">
                                                        <div
                                                            style="background: var(--primary-color); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; font-size: 14px; font-weight: 600;">
                                                            {{ substr($log->user->name, 0, 1) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div style="color: var(--text-primary); font-weight: 500;">
                                                            {{ $log->user->name }}</div>
                                                        <small
                                                            style="color: var(--text-secondary);">{{ $log->user->email }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span style="color: var(--text-secondary);">Admin tidak diketahui</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge-glass {{ $log->action_badge_class }}">
                                                {{ ucfirst($log->action) }}
                                            </span>
                                        </td>
                                        <td>
                                            <code
                                                style="background: rgba(255, 255, 255, 0.1); color: var(--info-color); padding: 2px 6px; border-radius: 4px; font-size: 12px;">{{ class_basename($log->model_type) }}</code>
                                            <br>
                                            <small style="color: var(--text-secondary);">ID: {{ $log->model_id }}</small>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 300px; color: var(--text-primary);"
                                                title="{{ $log->formatted_description }}">
                                                {{ $log->formatted_description }}
                                            </div>
                                        </td>
                                        <td>
                                            <small style="color: var(--text-secondary);">{{ $log->ip_address }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.activity-logs.show', $log) }}" <a
                                                href="{{ route('admin.activity-logs.show', $log) }}" class="btn btn-sm"
                                                style="background: rgba(108, 207, 127, 0.2); color: var(--info-color); border: none; border-radius: 6px;">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Custom Pagination -->
                    @if ($logs->hasPages())
                        <div class="pagination-container">
                            <div class="pagination-custom">
                                {{-- Previous Page Link --}}
                                @if ($logs->onFirstPage())
                                    <button class="btn-pagination" disabled>
                                        <i class="fas fa-chevron-left"></i> Prev
                                    </button>
                                @else
                                    <a href="{{ $logs->previousPageUrl() }}" class="btn-pagination">
                                        <i class="fas fa-chevron-left"></i> Prev
                                    </a>
                                @endif

                                {{-- Page Numbers --}}
                                <div class="page-numbers">
                                    @for ($i = max(1, $logs->currentPage() - 2); $i <= min($logs->lastPage(), $logs->currentPage() + 2); $i++)
                                        @if ($i == $logs->currentPage())
                                            <button class="page-number active">{{ $i }}</button>
                                        @else
                                            <a href="{{ $logs->url($i) }}" class="page-number">{{ $i }}</a>
                                        @endif
                                    @endfor
                                </div>

                                {{-- Next Page Link --}}
                                @if ($logs->hasMorePages())
                                    <a href="{{ $logs->nextPageUrl() }}" class="btn-pagination">
                                        Next <i class="fas fa-chevron-right"></i>
                                    </a>
                                @else
                                    <button class="btn-pagination" disabled>
                                        Next <i class="fas fa-chevron-right"></i>
                                    </button>
                                @endif
                            </div>
                            <div class="pagination-info">
                                Menampilkan {{ $logs->firstItem() }}-{{ $logs->lastItem() }} dari {{ $logs->total() }}
                                hasil
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Clear Logs Modal -->
    <div class="modal fade" id="clearLogsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content"
                style="background: var(--glass-bg); backdrop-filter: blur(20px); border: 1px solid var(--glass-border); border-radius: 16px;">
                <div class="modal-header" style="border: none;">
                    <h5 class="modal-title" style="color: var(--text-primary);">
                        <i class="fas fa-exclamation-triangle text-danger"></i> Hapus Semua Log
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.activity-logs.clear') }}"
                    onsubmit="return handleClearLogs(event)">
                    @csrf
                    <div class="modal-body" style="color: var(--text-secondary);">
                        <div class="alert alert-danger"
                            style="background: rgba(255, 107, 107, 0.1); border: 1px solid rgba(255, 107, 107, 0.2); color: var(--danger-color);">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Peringatan!</strong> Tindakan ini tidak dapat dibatalkan.
                        </div>
                        <p>Apakah Anda yakin ingin menghapus semua log aktivitas?</p>
                        <p class="text-muted small">Semua riwayat aktivitas admin akan hilang selamanya.</p>

                        <div class="form-check-glass">
                            <input class="form-check-input-glass" type="checkbox" id="confirm" name="confirm"
                                required>
                            <label class="form-check-label-glass" for="confirm">
                                Saya memahami bahwa tindakan ini tidak dapat dibatalkan
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer" style="border: none;">
                        <button type="button" class="btn btn-glass" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn"
                            style="background: var(--danger-color); color: white; border: none; border-radius: 8px; padding: 8px 16px;">
                            <i class="fas fa-trash-alt me-1"></i>Hapus Semua Log
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function handleClearLogs(event) {
                event.preventDefault();

                const form = event.target;
                const confirmCheckbox = form.querySelector('#confirm');

                if (!confirmCheckbox.checked) {
                    showToast('Silakan centang konfirmasi terlebih dahulu', 'warning');
                    return false;
                }

                // Submit form via AJAX
                const formData = new FormData(form);

                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('Semua log aktivitas berhasil dihapus!', 'success');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            showToast('Error: ' + (data.message || 'Terjadi kesalahan'), 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Terjadi kesalahan saat menghapus log', 'error');
                    });

                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('clearLogsModal'));
                modal.hide();

                return false;
            }
        </script>
    @endpush

    @push('styles')
        <style>
            /* Custom Pagination Styles */
            .pagination-container {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 10px;
                margin-top: 20px;
            }

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
                text-decoration: none;
            }

            .btn-pagination:hover:not(:disabled) {
                background: rgba(255, 255, 255, 0.2);
                color: white;
                transform: translateY(-1px);
                text-decoration: none;
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
                font-size: 13px;
                cursor: pointer;
                transition: all 0.3s ease;
                min-width: 36px;
                justify-content: center;
                display: flex;
                align-items: center;
                text-decoration: none;
            }

            .page-number:hover {
                background: rgba(255, 255, 255, 0.2);
                color: white;
                text-decoration: none;
            }

            .page-number.active {
                background: var(--primary-color);
                color: white;
                border-color: var(--primary-color);
            }

            .pagination-info {
                color: var(--text-secondary);
                font-size: 12px;
                text-align: center;
            }

            /* Dropdown Glass Styling */
            .form-control-glass {
                background: rgba(255, 255, 255, 0.1) !important;
                border: 1px solid rgba(255, 255, 255, 0.1) !important;
                border-radius: 8px !important;
                color: var(--text-primary) !important;
                padding: 8px 12px !important;
                font-size: 14px !important;
                transition: all 0.3s ease !important;
                backdrop-filter: blur(10px) !important;
            }

            .form-control-glass:focus {
                background: rgba(255, 255, 255, 0.15) !important;
                border-color: var(--primary-color) !important;
                box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb), 0.25) !important;
                outline: none !important;
            }

            .form-control-glass option {
                background: var(--glass-bg) !important;
                color: var(--text-primary) !important;
                padding: 8px 12px !important;
            }

            /* Custom dropdown arrow for glass select */
            .form-control-glass[type="select"],
            select.form-control-glass {
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e") !important;
                background-repeat: no-repeat !important;
                background-position: right 0.75rem center !important;
                background-size: 16px 12px !important;
                appearance: none !important;
            }

            /* Table Glass Proportional Layout */
            .table-glass {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 0;
                background: transparent;
            }

            .table-glass th {
                background: rgba(255, 255, 255, 0.05);
                color: var(--text-primary);
                font-weight: 600;
                padding: 12px 8px;
                font-size: 13px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                white-space: nowrap;
            }

            /* Specific column widths for better proportions */
            .table-glass th.col-waktu,
            .table-glass td:nth-child(1) {
                width: 12%;
                min-width: 110px;
            }

            .table-glass th.col-admin,
            .table-glass td:nth-child(2) {
                width: 22%;
                min-width: 180px;
            }

            .table-glass th.col-aksi,
            .table-glass td:nth-child(3) {
                width: 10%;
                min-width: 90px;
            }

            .table-glass th.col-model,
            .table-glass td:nth-child(4) {
                width: 12%;
                min-width: 100px;
            }

            .table-glass th.col-deskripsi,
            .table-glass td:nth-child(5) {
                width: 30%;
                min-width: 200px;
            }

            .table-glass th.col-ip,
            .table-glass td:nth-child(6) {
                width: 10%;
                min-width: 100px;
            }

            .table-glass th.col-detail,
            .table-glass td:nth-child(7) {
                width: 8%;
                min-width: 80px;
            }

            .table-glass td {
                padding: 12px 8px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
                color: var(--text-primary);
                font-size: 13px;
                vertical-align: middle;
            }

            .table-glass tr:hover {
                background: rgba(255, 255, 255, 0.02);
            }

            /* Better responsive table */
            .table-responsive-glass {
                overflow-x: auto;
                margin: -1px;
                border-radius: 8px;
            }

            @media (max-width: 768px) {

                .table-glass th.col-ip,
                .table-glass td:nth-child(6) {
                    display: none;
                    /* Hide IP Address on mobile */
                }

                .table-glass th.col-model,
                .table-glass td:nth-child(4) {
                    display: none;
                    /* Hide Model on mobile */
                }

                /* Adjust widths for remaining columns on mobile */
                .table-glass th.col-waktu,
                .table-glass td:nth-child(1) {
                    width: 18%;
                }

                .table-glass th.col-admin,
                .table-glass td:nth-child(2) {
                    width: 30%;
                }

                .table-glass th.col-aksi,
                .table-glass td:nth-child(3) {
                    width: 15%;
                }

                .table-glass th.col-deskripsi,
                .table-glass td:nth-child(5) {
                    width: 35%;
                }

                .table-glass th.col-detail,
                .table-glass td:nth-child(7) {
                    width: 12%;
                }
            }

            /* Form Check Glass */
            .form-check-glass {
                display: flex;
                align-items: center;
                gap: 8px;
                margin: 15px 0;
            }

            .form-check-input-glass {
                width: 16px;
                height: 16px;
                background: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: 4px;
                cursor: pointer;
            }

            .form-check-input-glass:checked {
                background: var(--primary-color);
                border-color: var(--primary-color);
            }

            .form-check-label-glass {
                color: var(--text-primary);
                font-size: 14px;
                cursor: pointer;
            }

            /* Badge Glass Styling */
            .badge-glass.bg-success {
                background: rgba(40, 167, 69, 0.2) !important;
                color: var(--success-color) !important;
                border: 1px solid rgba(40, 167, 69, 0.3);
            }

            .badge-glass.bg-primary {
                background: rgba(13, 110, 253, 0.2) !important;
                color: var(--primary-color) !important;
                border: 1px solid rgba(13, 110, 253, 0.3);
            }

            .badge-glass.bg-warning {
                background: rgba(255, 193, 7, 0.2) !important;
                color: var(--warning-color) !important;
                border: 1px solid rgba(255, 193, 7, 0.3);
            }

            .badge-glass.bg-info {
                background: rgba(13, 202, 240, 0.2) !important;
                color: var(--info-color) !important;
                border: 1px solid rgba(13, 202, 240, 0.3);
            }

            .badge-glass.bg-danger {
                background: rgba(220, 53, 69, 0.2) !important;
                color: var(--danger-color) !important;
                border: 1px solid rgba(220, 53, 69, 0.3);
            }

            .badge-glass.bg-secondary {
                background: rgba(108, 117, 125, 0.2) !important;
                color: var(--secondary-color) !important;
                border: 1px solid rgba(108, 117, 125, 0.3);
            }
        </style>
    @endpush
@endsection
