@extends('admin.layouts.app')

@section('title', 'Trash - File Downloads')

@section('content')
    <div class="container-fluid">
        @include('admin.partials.page-header', [
            'title' => 'Trash - File Downloads',
            'subtitle' => 'File yang telah dihapus',
            'icon' => 'fas fa-trash',
            'actions' => [
                [
                    'label' => 'Kembali',
                    'icon' => 'fas fa-arrow-left',
                    'class' => 'btn-glass',
                    'route' => 'admin.file-downloads.index',
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

        <!-- Search -->
        <div class="glass-card mb-4">
            <form method="GET" action="{{ route('admin.file-downloads.trash') }}">
                <div class="row g-3 align-items-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-glass">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control form-control-glass"
                                placeholder="Cari file di trash..." value="{{ $search }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary-glass">
                                <i class="fas fa-search me-1"></i> Cari
                            </button>
                            @if ($search)
                                <a href="{{ route('admin.file-downloads.trash') }}" class="btn btn-glass">
                                    <i class="fas fa-times me-1"></i> Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Deleted Files -->
        @if ($files->count() > 0)
            <div class="glass-card">
                <div class="table-responsive">
                    <table class="table table-glass">
                        <thead>
                            <tr>
                                <th>File</th>
                                <th>Judul</th>
                                <th>Ukuran</th>
                                <th>Dihapus</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($files as $file)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="file-icon-small me-3">
                                                <i
                                                    class="fas fa-file-{{ getFileIcon($file->file_extension) }} text-muted"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $file->original_filename }}</div>
                                                <small class="text-muted">{{ $file->mime_type }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $file->title }}</div>
                                        @if ($file->description)
                                            <small class="text-muted">{{ Str::limit($file->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $file->formatted_size }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $file->deleted_at->format('d M Y H:i') }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <form action="{{ route('admin.file-downloads.restore', $file->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success-glass btn-sm"
                                                    onclick="return confirm('Yakin ingin memulihkan file ini?')"
                                                    title="Pulihkan">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.file-downloads.force-delete', $file->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger-glass btn-sm"
                                                    onclick="return confirm('PERINGATAN: File akan dihapus permanen dan tidak dapat dipulihkan. Yakin melanjutkan?')"
                                                    title="Hapus Permanen">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $files->links() }}
                </div>
            </div>
        @else
            <div class="glass-card text-center py-5">
                <i class="fas fa-trash fa-4x text-muted mb-3"></i>
                <h5 class="text-muted mb-3">Trash Kosong</h5>
                <p class="text-muted mb-4">
                    @if ($search)
                        Tidak ada file yang ditemukan dengan kata kunci "{{ $search }}"
                    @else
                        Tidak ada file yang dihapus
                    @endif
                </p>
                <a href="{{ route('admin.file-downloads.index') }}" class="btn btn-primary-glass">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke File Downloads
                </a>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .file-icon-small {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(108, 117, 125, 0.1);
            border-radius: 6px;
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
