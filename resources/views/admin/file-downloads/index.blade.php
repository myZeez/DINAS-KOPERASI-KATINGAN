@extends('admin.layouts.app')

@section('title', 'File Downloads')

@section('content')
    <div class="container-fluid">
        @include('admin.partials.page-header', [
            'title' => 'File Downloads',
            'subtitle' => 'Kelola file download untuk publik',
            'icon' => 'fas fa-download',
            'primaryAction' => [
                'url' => route('admin.file-downloads.create'),
                'text' => 'Upload File',
                'icon' => 'fas fa-plus',
            ],
            'secondaryActions' => [
                [
                    'onclick' => "window.location.href='" . route('admin.file-downloads.trash') . "'",
                    'text' => 'Kelola Trash',
                    'icon' => 'fas fa-trash',
                    'title' => 'Kelola file yang dihapus',
                ],
            ],
        ])

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Search and Filter -->
        <div class="glass-card mb-4">
            <form method="GET" action="{{ route('admin.file-downloads.index') }}">
                <div class="row g-3 align-items-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-glass">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control form-control-glass"
                                placeholder="Cari berdasarkan nama file, judul, atau deskripsi..."
                                value="{{ $search }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary-glass">
                                <i class="fas fa-search me-1"></i> Cari
                            </button>
                            @if ($search)
                                <a href="{{ route('admin.file-downloads.index') }}" class="btn btn-glass">
                                    <i class="fas fa-times me-1"></i> Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Files Grid -->
        @if ($files->count() > 0)
            <div class="row g-4">
                @foreach ($files as $file)
                    <div class="col-lg-4 col-md-6">
                        <div class="glass-card h-100">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="file-icon">
                                    <i class="fas fa-file-{{ getFileIcon($file->file_extension) }} fa-2x text-primary"></i>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-glass btn-sm dropdown-toggle" type="button"
                                        id="dropdownMenuButton{{ $file->id }}" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end"
                                        aria-labelledby="dropdownMenuButton{{ $file->id }}">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.file-downloads.show', $file) }}">
                                                <i class="fas fa-eye me-2"></i>Lihat
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.file-downloads.edit', $file) }}">
                                                <i class="fas fa-edit me-2"></i>Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ Storage::url($file->file_path) }}"
                                                target="_blank">
                                                <i class="fas fa-download me-2"></i>Download
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.file-downloads.destroy', $file) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"
                                                    onclick="return confirm('Yakin ingin menghapus file ini?')">
                                                    <i class="fas fa-trash me-2"></i>Hapus
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <h6 class="file-title mb-2">{{ $file->title }}</h6>

                            @if ($file->description)
                                <p class="text-muted small mb-3">{{ Str::limit($file->description, 100) }}</p>
                            @endif

                            <div class="file-meta">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-file me-1"></i>{{ $file->original_filename }}
                                    </small>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-weight me-1"></i>{{ $file->formatted_size }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-download me-1"></i>{{ $file->download_count }}x
                                    </small>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>{{ $file->created_at->format('d M Y H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $files->links() }}
            </div>
        @else
            <div class="glass-card text-center py-5">
                <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                <h5 class="text-muted mb-3">Tidak Ada File</h5>
                <p class="text-muted mb-4">
                    @if ($search)
                        Tidak ada file yang ditemukan dengan kata kunci "{{ $search }}"
                    @else
                        Belum ada file yang diupload
                    @endif
                </p>
                <a href="{{ route('admin.file-downloads.create') }}" class="btn btn-primary-glass">
                    <i class="fas fa-plus me-2"></i>Upload File Pertama
                </a>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .file-title {
            color: var(--text-primary);
            font-weight: 600;
        }

        .file-meta {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1rem;
        }

        .file-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(13, 110, 253, 0.1);
            border-radius: 12px;
            margin-bottom: 1rem;
        }

        /* Dropdown styling for glass theme */
        .dropdown-menu {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            z-index: 1050 !important;
            min-width: 160px;
        }

        .dropdown {
            position: relative;
            z-index: 10;
        }

        .dropdown.show .dropdown-menu {
            display: block;
            opacity: 1;
            transform: scale(1);
        }

        .dropdown-item {
            color: #333 !important;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }

        .dropdown-item:hover,
        .dropdown-item:focus {
            background: rgba(13, 110, 253, 0.1) !important;
            color: #0d6efd !important;
        }

        .dropdown-item.text-danger:hover,
        .dropdown-item.text-danger:focus {
            background: rgba(220, 53, 69, 0.1) !important;
            color: #dc3545 !important;
        }

        .btn-glass.dropdown-toggle::after {
            display: none;
        }

        .btn-glass {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--text-primary);
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 1;
        }

        .btn-glass:hover,
        .btn-glass:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
            color: var(--text-primary);
        }

        .glass-card {
            position: relative;
            z-index: 1;
        }

        .glass-card:hover {
            z-index: 2;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all dropdowns
            const dropdownTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
            const dropdownList = dropdownTriggerList.map(function(dropdownTriggerEl) {
                return new bootstrap.Dropdown(dropdownTriggerEl, {
                    boundary: 'viewport'
                });
            });

            // Manual click handling as fallback
            document.querySelectorAll('.dropdown-toggle').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Close all other dropdowns
                    document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
                        if (menu !== button.nextElementSibling) {
                            menu.classList.remove('show');
                            menu.parentElement.classList.remove('show');
                        }
                    });

                    // Toggle current dropdown
                    const dropdownMenu = button.nextElementSibling;
                    const dropdown = button.parentElement;

                    if (dropdownMenu.classList.contains('show')) {
                        dropdownMenu.classList.remove('show');
                        dropdown.classList.remove('show');
                    } else {
                        dropdownMenu.classList.add('show');
                        dropdown.classList.add('show');
                    }
                });
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.dropdown')) {
                    document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
                        menu.classList.remove('show');
                        menu.parentElement.classList.remove('show');
                    });
                }
            });
        });
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
