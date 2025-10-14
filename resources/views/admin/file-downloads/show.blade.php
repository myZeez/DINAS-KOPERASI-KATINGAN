@extends('admin.layouts.app')

@section('title', 'Detail File - ' . $fileDownload->title)

@section('content')
    <div class="container-fluid">
        @include('admin.partials.page-header', [
            'title' => 'Detail File',
            'subtitle' => 'Informasi lengkap file download',
            'icon' => 'fas fa-file-alt',
            'breadcrumb' => [
                ['label' => 'File Downloads', 'route' => 'admin.file-downloads.index'],
                ['label' => 'Detail File', 'active' => true],
            ],
        ])

        <div class="row">
            <div class="col-lg-8">
                <!-- File Information -->
                <div class="glass-card">
                    <div class="d-flex align-items-center mb-4">
                        <div class="file-icon-large me-3">
                            <i class="fas fa-file-{{ getFileIcon($fileDownload->file_extension) }} fa-2x text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1">{{ $fileDownload->title }}</h4>
                            <p class="text-muted mb-0">{{ $fileDownload->original_filename }}</p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary-glass">{{ strtoupper($fileDownload->file_extension) }}</span>
                        </div>
                    </div>

                    @if ($fileDownload->description)
                        <div class="mb-4">
                            <h6 class="mb-2">Deskripsi:</h6>
                            <p class="text-muted">{{ $fileDownload->description }}</p>
                        </div>
                    @endif

                    <!-- File Statistics -->
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6 col-md-3">
                            <div class="stat-card text-center">
                                <div class="stat-icon">
                                    <i class="fas fa-download text-success"></i>
                                </div>
                                <h5 class="mb-1">{{ $fileDownload->download_count }}</h5>
                                <p class="text-muted mb-0 small">Unduhan</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="stat-card text-center">
                                <div class="stat-icon">
                                    <i class="fas fa-hdd text-info"></i>
                                </div>
                                <h5 class="mb-1">{{ $fileDownload->formatted_size }}</h5>
                                <p class="text-muted mb-0 small">Ukuran</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="stat-card text-center">
                                <div class="stat-icon">
                                    <i class="fas fa-calendar text-warning"></i>
                                </div>
                                <h5 class="mb-1">{{ $fileDownload->created_at->format('d M Y') }}</h5>
                                <p class="text-muted mb-0 small">Diupload</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="stat-card text-center">
                                <div class="stat-icon">
                                    <i class="fas fa-clock text-secondary"></i>
                                </div>
                                <h5 class="mb-1">{{ $fileDownload->updated_at->format('d M Y') }}</h5>
                                <p class="text-muted mb-0 small">Diperbarui</p>
                            </div>
                        </div>
                    </div>

                    <!-- File Preview/Actions -->
                    <div class="file-preview-section">
                        <h6 class="mb-3">Pratinjau File:</h6>
                        <div class="file-preview-container">
                            @if ($fileDownload->file_extension === 'pdf')
                                <div class="pdf-preview">
                                    <iframe src="{{ Storage::url($fileDownload->file_path) }}" width="100%" height="400px"
                                        class="border-0 rounded"></iframe>
                                </div>
                            @else
                                <div class="file-placeholder text-center py-5">
                                    <i
                                        class="fas fa-file-{{ getFileIcon($fileDownload->file_extension) }} fa-4x text-muted mb-3"></i>
                                    <h6 class="text-muted">Pratinjau tidak tersedia untuk tipe file ini</h6>
                                    <p class="text-muted">Klik tombol download untuk melihat file</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Activity Log (if you have activity logging) -->
                <div class="glass-card">
                    <h6 class="mb-3">
                        <i class="fas fa-history me-2"></i>Riwayat Aktivitas
                    </h6>

                    <div class="activity-timeline">
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-upload text-success"></i>
                            </div>
                            <div class="activity-content">
                                <h6 class="mb-1">File diupload</h6>
                                <p class="text-muted small mb-0">{{ $fileDownload->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>

                        @if ($fileDownload->updated_at != $fileDownload->created_at)
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-edit text-warning"></i>
                                </div>
                                <div class="activity-content">
                                    <h6 class="mb-1">File diperbarui</h6>
                                    <p class="text-muted small mb-0">{{ $fileDownload->updated_at->format('d M Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        @if ($fileDownload->download_count > 0)
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-download text-info"></i>
                                </div>
                                <div class="activity-content">
                                    <h6 class="mb-1">Total unduhan</h6>
                                    <p class="text-muted small mb-0">{{ $fileDownload->download_count }} kali</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="glass-card">
                    <h6 class="mb-3">
                        <i class="fas fa-bolt me-2"></i>Aksi Cepat
                    </h6>

                    <div class="d-grid gap-2">
                        <a href="{{ Storage::url($fileDownload->file_path) }}" class="btn btn-primary-glass"
                            target="_blank">
                            <i class="fas fa-download me-2"></i>Download File
                        </a>
                        <a href="{{ route('admin.file-downloads.edit', $fileDownload) }}" class="btn btn-warning-glass">
                            <i class="fas fa-edit me-2"></i>Edit File
                        </a>
                        <a href="{{ route('public.downloads.download', $fileDownload) }}" class="btn btn-success-glass">
                            <i class="fas fa-download me-2"></i>Download File
                        </a>
                        <hr class="my-3">
                        <form action="{{ route('admin.file-downloads.destroy', $fileDownload) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger-glass w-100"
                                onclick="return confirm('Yakin ingin menghapus file ini?')">
                                <i class="fas fa-trash me-2"></i>Hapus File
                            </button>
                        </form>
                    </div>
                </div>

                <!-- File Details -->
                <div class="glass-card">
                    <h6 class="mb-3">
                        <i class="fas fa-info-circle me-2"></i>Detail File
                    </h6>

                    <div class="file-details">
                        <div class="detail-item">
                            <span class="detail-label">Nama Asli:</span>
                            <span class="detail-value">{{ $fileDownload->original_filename }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Tipe MIME:</span>
                            <span class="detail-value">{{ $fileDownload->mime_type }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Ekstensi:</span>
                            <span class="detail-value">{{ strtoupper($fileDownload->file_extension) }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Ukuran:</span>
                            <span class="detail-value">{{ $fileDownload->formatted_size }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Path:</span>
                            <span class="detail-value text-break small">{{ $fileDownload->file_path }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Status File:</span>
                            <span class="detail-value">
                                @if ($fileDownload->fileExists())
                                    <span class="badge bg-success-glass">✓ File Ada</span>
                                @else
                                    <span class="badge bg-danger-glass">✗ File Hilang</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Share Options -->
                <div class="glass-card">
                    <h6 class="mb-3">
                        <i class="fas fa-share-alt me-2"></i>Bagikan
                    </h6>

                    <div class="share-options">
                        <div class="mb-3">
                            <label class="form-label small">Link Download:</label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm"
                                    value="{{ route('public.downloads.download', $fileDownload) }}" id="publicLink" readonly>
                                <button class="btn btn-outline-secondary btn-sm" onclick="copyToClipboard('publicLink')"
                                    title="Salin Link">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small">Link Download Langsung:</label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm"
                                    value="{{ route('public.downloads.download', $fileDownload) }}" id="downloadLink"
                                    readonly>
                                <button class="btn btn-outline-secondary btn-sm" onclick="copyToClipboard('downloadLink')"
                                    title="Salin Link">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .file-icon-large {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(13, 110, 253, 0.1);
            border-radius: 12px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 1.5rem 1rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            margin: 0 auto 0.5rem;
        }

        .file-preview-container {
            background: rgba(255, 255, 255, 0.02);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
        }

        .file-placeholder {
            background: rgba(255, 255, 255, 0.03);
        }

        .activity-timeline {
            position: relative;
        }

        .activity-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .activity-item:not(:last-child):before {
            content: '';
            position: absolute;
            left: 15px;
            top: 30px;
            bottom: -24px;
            width: 2px;
            background: rgba(255, 255, 255, 0.1);
        }

        .activity-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 0.8rem;
        }

        .activity-content {
            flex: 1;
            padding-top: 3px;
        }

        .file-details {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .detail-label {
            font-weight: 500;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.875rem;
            min-width: 80px;
            flex-shrink: 0;
        }

        .detail-value {
            text-align: right;
            font-size: 0.875rem;
            max-width: 60%;
        }

        .bg-success-glass {
            background: rgba(25, 135, 84, 0.2);
            color: #198754;
        }

        .bg-danger-glass {
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }

        .btn-warning-glass {
            background: rgba(255, 193, 7, 0.2);
            border: 1px solid rgba(255, 193, 7, 0.3);
            color: #ffc107;
        }

        .btn-warning-glass:hover {
            background: rgba(255, 193, 7, 0.3);
            border-color: rgba(255, 193, 7, 0.4);
            color: #000;
        }

        .btn-success-glass {
            background: rgba(25, 135, 84, 0.2);
            border: 1px solid rgba(25, 135, 84, 0.3);
            color: #198754;
        }

        .btn-success-glass:hover {
            background: rgba(25, 135, 84, 0.3);
            border-color: rgba(25, 135, 84, 0.4);
            color: #fff;
        }

        .btn-danger-glass {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #dc3545;
        }

        .btn-danger-glass:hover {
            background: rgba(220, 53, 69, 0.3);
            border-color: rgba(220, 53, 69, 0.4);
            color: #fff;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            element.select();
            element.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(element.value);

            showToast('Link berhasil disalin!', 'success');
        }
    </script>
@endpush

@php
    function getFileIcon($extension)
    {
        $icons = [
            'pdf' => 'pdf',
            'doc' => 'word',
            'docx' => 'word',
            'xls' => 'excel',
            'xlsx' => 'excel',
            'ppt' => 'powerpoint',
            'pptx' => 'powerpoint',
            'zip' => 'archive',
            'rar' => 'archive',
            'txt' => 'alt',
        ];

        return $icons[$extension] ?? 'alt';
    }
@endphp
