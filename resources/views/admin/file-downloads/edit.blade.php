@extends('admin.layouts.app')

@section('title', 'Edit File - ' . $fileDownload->title)

@section('hide_chrome', true)

@push('styles')
    <style>
        /* Back Button */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: var(--text-primary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(0, 255, 136, 0.3);
            color: var(--accent-color);
            transform: translateX(-2px);
            text-decoration: none;
        }

        .btn-back i {
            font-size: 12px;
            transition: transform 0.3s ease;
        }

        .btn-back:hover i {
            transform: translateX(-2px);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Simple Back Button -->
        <div style="margin-bottom: 24px;">
            <a href="{{ route('admin.file-downloads.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Kembali ke File Downloads
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h6><i class="fas fa-exclamation-circle me-2"></i>Terjadi kesalahan:</h6>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="glass-card">
                    <form action="{{ route('admin.file-downloads.update', $fileDownload) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label">Judul File</label>
                            <input type="text" name="title" class="form-control form-control-glass"
                                value="{{ old('title', $fileDownload->title) }}" required
                                placeholder="Masukkan judul file...">
                            <div class="form-text">Judul yang akan ditampilkan kepada publik</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control form-control-glass" rows="4"
                                placeholder="Masukkan deskripsi file...">{{ old('description', $fileDownload->description) }}</textarea>
                            <div class="form-text">Deskripsi singkat tentang file (opsional)</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">File Baru (Opsional)</label>
                            <div class="upload-area" id="uploadArea">
                                <div class="upload-content">
                                    <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-3"></i>
                                    <h6>Klik untuk mengganti file atau drag & drop</h6>
                                    <p class="text-muted mb-2">Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, ZIP, RAR
                                    </p>
                                    <p class="text-muted">Maksimal: 50MB</p>
                                    <small class="text-warning">Kosongkan jika tidak ingin mengganti file</small>
                                </div>
                                <input type="file" name="file" class="form-control" id="fileInput"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar">
                            </div>
                            <div id="filePreview" class="mt-3" style="display: none;"></div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary-glass">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.file-downloads.index') }}" class="btn btn-glass">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Current File Info -->
                <div class="glass-card">
                    <h6 class="mb-3">
                        <i class="fas fa-file me-2"></i>File Saat Ini
                    </h6>

                    <div class="current-file-preview">
                        <div class="text-center mb-3">
                            <div class="file-icon-current">
                                <i class="fas fa-file-{{ getFileIcon($fileDownload->file_extension) }} fa-2x"></i>
                            </div>
                        </div>

                        <div class="file-info">
                            <h6 class="mb-2">{{ $fileDownload->original_filename }}</h6>

                            <div class="file-stats">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Ukuran:</span>
                                    <span>{{ $fileDownload->formatted_size }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Tipe:</span>
                                    <span>{{ strtoupper($fileDownload->file_extension) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Unduhan:</span>
                                    <span>{{ $fileDownload->download_count }}x</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Diupload:</span>
                                    <span>{{ $fileDownload->created_at->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="{{ Storage::url($fileDownload->file_path) }}"
                                class="btn btn-outline-gradient btn-sm w-100" target="_blank">
                                <i class="fas fa-download me-2"></i>Download File
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="glass-card">
                    <h6 class="mb-3">
                        <i class="fas fa-cogs me-2"></i>Aksi Lainnya
                    </h6>

                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.file-downloads.show', $fileDownload) }}"
                            class="btn btn-info-glass btn-sm">
                            <i class="fas fa-eye me-2"></i>Lihat Detail
                        </a>
                        <a href="{{ route('public.downloads.download', $fileDownload) }}"
                            class="btn btn-success-glass btn-sm">
                            <i class="fas fa-download me-2"></i>Download File
                        </a>
                        <form action="{{ route('admin.file-downloads.destroy', $fileDownload) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger-glass btn-sm w-100"
                                onclick="return confirm('Yakin ingin menghapus file ini?')">
                                <i class="fas fa-trash me-2"></i>Hapus File
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .upload-area {
            border: 2px dashed rgba(13, 110, 253, 0.3);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            background: rgba(13, 110, 253, 0.02);
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .upload-area:hover {
            border-color: rgba(13, 110, 253, 0.5);
            background: rgba(13, 110, 253, 0.05);
        }

        .upload-area input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-icon-current {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(13, 110, 253, 0.1);
            border-radius: 12px;
            margin: 0 auto;
            color: #0d6efd;
        }

        .current-file-preview {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .file-stats {
            font-size: 0.875rem;
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
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('fileInput');
        const filePreview = document.getElementById('filePreview');

        // Same upload functionality as create page
        uploadArea.addEventListener('click', () => {
            fileInput.click();
        });

        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect(files[0]);
            }
        });

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFileSelect(e.target.files[0]);
            }
        });

        function handleFileSelect(file) {
            // Same file handling as create page
            const allowedTypes = ['application/pdf', 'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'text/plain',
                'application/zip', 'application/x-rar-compressed'
            ];

            if (!allowedTypes.includes(file.type)) {
                showToast('Tipe file tidak didukung!', 'error');
                return;
            }

            if (file.size > 50 * 1024 * 1024) {
                showToast('Ukuran file terlalu besar! Maksimal 50MB.', 'error');
                return;
            }

            const fileExtension = file.name.split('.').pop().toLowerCase();
            const fileIcon = getFileIcon(fileExtension);
            const fileSize = formatFileSize(file.size);

            filePreview.innerHTML = `
                <div class="file-preview d-flex align-items-center">
                    <div class="file-icon">
                        <i class="fas fa-file-${fileIcon} fa-lg text-primary"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">${file.name}</h6>
                        <small class="text-muted">${fileSize}</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-glass" onclick="clearFile()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            filePreview.style.display = 'block';
        }

        function clearFile() {
            fileInput.value = '';
            filePreview.style.display = 'none';
        }

        function getFileIcon(extension) {
            const icons = {
                'pdf': 'pdf',
                'doc': 'word',
                'docx': 'word',
                'xls': 'excel',
                'xlsx': 'excel',
                'ppt': 'powerpoint',
                'pptx': 'powerpoint',
                'zip': 'archive',
                'rar': 'archive',
                'txt': 'alt'
            };
            return icons[extension] || 'alt';
        }

        function formatFileSize(bytes) {
            if (bytes >= 1073741824) {
                return (bytes / 1073741824).toFixed(2) + ' GB';
            } else if (bytes >= 1048576) {
                return (bytes / 1048576).toFixed(2) + ' MB';
            } else if (bytes >= 1024) {
                return (bytes / 1024).toFixed(2) + ' KB';
            } else {
                return bytes + ' bytes';
            }
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
