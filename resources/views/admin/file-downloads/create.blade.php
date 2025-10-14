@extends('admin.layouts.app')

@section('title', 'Upload File')

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
                    <form action="{{ route('admin.file-downloads.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label">Judul File</label>
                            <input type="text" name="title" class="form-control form-control-glass"
                                value="{{ old('title') }}" required placeholder="Masukkan judul file...">
                            <div class="form-text">Judul yang akan ditampilkan kepada publik</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control form-control-glass" rows="4"
                                placeholder="Masukkan deskripsi file...">{{ old('description') }}</textarea>
                            <div class="form-text">Deskripsi singkat tentang file (opsional)</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">File</label>
                            <div class="upload-area" id="uploadArea">
                                <div class="upload-content">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                    <h5 class="mb-3">Klik untuk memilih file atau drag & drop</h5>
                                    <p class="text-muted mb-3">Format yang didukung:</p>
                                    <div class="supported-formats mb-3">
                                        <span class="badge bg-primary-glass me-1">PDF</span>
                                        <span class="badge bg-primary-glass me-1">DOC/DOCX</span>
                                        <span class="badge bg-primary-glass me-1">XLS/XLSX</span>
                                        <span class="badge bg-primary-glass me-1">PPT/PPTX</span>
                                        <span class="badge bg-primary-glass me-1">TXT</span>
                                        <span class="badge bg-primary-glass me-1">ZIP/RAR</span>
                                    </div>
                                    <p class="text-muted"><i class="fas fa-info-circle me-1"></i>Maksimal ukuran file: 50MB
                                    </p>
                                </div>
                                <input type="file" name="file" class="form-control" id="fileInput" required
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar">
                            </div>
                            <div id="filePreview" class="mt-3" style="display: none;"></div>
                        </div>

                        <div class="d-flex gap-2 justify-content-between">
                            <a href="{{ route('admin.file-downloads.index') }}" class="btn btn-glass">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary-glass">
                                <i class="fas fa-upload me-2"></i>Upload File
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Help Card -->
                <div class="glass-card">
                    <h6 class="mb-3">
                        <i class="fas fa-question-circle me-2"></i>Panduan Upload
                    </h6>

                    <div class="help-item">
                        <div class="help-icon">
                            <i class="fas fa-file text-primary"></i>
                        </div>
                        <div class="help-content">
                            <h6>Format File</h6>
                            <p class="mb-0">Pastikan file dalam format yang didukung: PDF, DOC, XLS, PPT, TXT, ZIP, atau
                                RAR</p>
                        </div>
                    </div>

                    <div class="help-item">
                        <div class="help-icon">
                            <i class="fas fa-weight-hanging text-warning"></i>
                        </div>
                        <div class="help-content">
                            <h6>Ukuran File</h6>
                            <p class="mb-0">Maksimal 50MB per file. File yang terlalu besar dapat memperlambat download
                            </p>
                        </div>
                    </div>

                    <div class="help-item">
                        <div class="help-icon">
                            <i class="fas fa-edit text-success"></i>
                        </div>
                        <div class="help-content">
                            <h6>Judul & Deskripsi</h6>
                            <p class="mb-0">Gunakan judul yang jelas dan deskripsi informatif untuk memudahkan pengguna
                            </p>
                        </div>
                    </div>

                    <div class="help-item">
                        <div class="help-icon">
                            <i class="fas fa-globe text-info"></i>
                        </div>
                        <div class="help-content">
                            <h6>Akses Publik</h6>
                            <p class="mb-0">File yang diupload akan dapat diakses oleh semua pengunjung website</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats Card -->
                <div class="glass-card">
                    <h6 class="mb-3">
                        <i class="fas fa-chart-line me-2"></i>Statistik File
                    </h6>

                    <div class="stat-row">
                        <span class="stat-label">Total File:</span>
                        <span class="stat-value">{{ \App\Models\FileDownload::count() }}</span>
                    </div>

                    <div class="stat-row">
                        <span class="stat-label">File Hari Ini:</span>
                        <span
                            class="stat-value">{{ \App\Models\FileDownload::whereDate('created_at', today())->count() }}</span>
                    </div>

                    <div class="stat-row">
                        <span class="stat-label">Total Download:</span>
                        <span class="stat-value">{{ \App\Models\FileDownload::sum('download_count') }}</span>
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
            padding: 3rem 2rem;
            text-align: center;
            background: rgba(13, 110, 253, 0.02);
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            min-height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .upload-area:hover {
            border-color: rgba(13, 110, 253, 0.5);
            background: rgba(13, 110, 253, 0.05);
            transform: translateY(-2px);
        }

        .upload-area.dragover {
            border-color: #0d6efd;
            background: rgba(13, 110, 253, 0.1);
            transform: scale(1.02);
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

        .upload-content h5 {
            color: var(--text-primary);
            font-weight: 600;
        }

        .supported-formats {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.5rem;
        }

        .bg-primary-glass {
            background: rgba(13, 110, 253, 0.2);
            border: 1px solid rgba(13, 110, 253, 0.3);
            color: #0d6efd;
        }

        /* Help Card Styling */
        .help-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .help-item:last-child {
            margin-bottom: 0;
        }

        .help-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
        }

        .help-content h6 {
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .help-content p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.8rem;
            line-height: 1.4;
        }

        /* Stats Card Styling */
        .stat-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .stat-row:last-child {
            border-bottom: none;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
        }

        .stat-value {
            color: var(--accent-color);
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* Form improvements */
        .form-control-glass {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--text-primary);
            border-radius: 8px;
        }

        .form-control-glass:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(13, 110, 253, 0.5);
            color: var(--text-primary);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .form-control-glass::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-label {
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-text {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.875rem;
        }

        /* Button positioning */
        @media (max-width: 768px) {
            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 1rem;
            }

            .help-item {
                flex-direction: column;
                text-align: center;
                gap: 0.5rem;
            }
        }

        margin-bottom: 0.5rem;
        }

        .file-preview {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .file-preview .file-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(13, 110, 253, 0.2);
            border-radius: 8px;
            margin-right: 1rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('fileInput');
        const filePreview = document.getElementById('filePreview');

        // Click to select file
        uploadArea.addEventListener('click', () => {
            fileInput.click();
        });

        // Drag and drop functionality
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

        // Handle file selection
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFileSelect(e.target.files[0]);
            }
        });

        function handleFileSelect(file) {
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

            if (file.size > 50 * 1024 * 1024) { // 50MB
                showToast('Ukuran file terlalu besar! Maksimal 50MB.', 'error');
                return;
            }

            // Show file preview
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
