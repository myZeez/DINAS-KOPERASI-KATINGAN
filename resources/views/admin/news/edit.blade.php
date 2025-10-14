@extends('admin.layouts.app')

@section('title', 'Edit Berita')

@section('hide_chrome', true)

@push('styles')
    <style>
        /* Page Container */
        .news-edit-container {
            background: linear-gradient(135deg, rgba(0, 255, 136, 0.1), rgba(255, 255, 255, 0.05));
            min-height: 100vh;
            padding: 20px 0;
        }

        /* Glass Card Components */
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 32px;
            margin-bottom: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .glass-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--accent-color), transparent, var(--accent-color));
            opacity: 0.7;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.2);
            border-color: rgba(0, 255, 136, 0.3);
        }

        /* Card Headers */
        .card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .card-header h4 {
            color: var(--text-primary);
            font-weight: 600;
            margin: 0;
            font-size: 1.3rem;
        }

        .card-header i {
            color: var(--accent-color);
            font-size: 1.4rem;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 255, 136, 0.1);
            border-radius: 8px;
        }

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

        /* Form Styling */
        .form-group {
            margin-bottom: 24px;
            position: relative;
        }

        .form-label {
            color: var(--text-primary);
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-label.required::after {
            content: '*';
            color: #ff6b6b;
            margin-left: 2px;
        }

        .form-control-modern {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: var(--text-primary);
            padding: 14px 18px;
            font-size: 14px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            width: 100%;
        }

        .form-control-modern::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .form-control-modern:focus {
            background: rgba(255, 255, 255, 0.12);
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(0, 255, 136, 0.1);
            outline: none;
            transform: translateY(-2px);
        }

        /* Enhanced Glass Style for Select Dropdown */
        select.form-control-glass {
            background: rgba(255, 255, 255, 0.08) !important;
            backdrop-filter: blur(15px) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            border-radius: 12px !important;
            color: var(--text-primary) !important;
            padding: 14px 18px !important;
            font-size: 14px !important;
            transition: all 0.3s ease !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='rgba(0,255,136,0.7)' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: right 12px center !important;
            background-size: 16px !important;
            cursor: pointer !important;
        }

        select.form-control-glass:hover {
            background: rgba(255, 255, 255, 0.12) !important;
            border-color: rgba(0, 255, 136, 0.3) !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 15px rgba(0, 255, 136, 0.1) !important;
        }

        select.form-control-glass:focus {
            background: rgba(255, 255, 255, 0.15) !important;
            border-color: var(--accent-color) !important;
            box-shadow: 0 0 0 3px rgba(0, 255, 136, 0.2) !important;
            outline: none !important;
            transform: translateY(-2px) !important;
        }

        /* Style for dropdown options */
        select.form-control-glass option {
            background: rgba(30, 30, 50, 0.95) !important;
            color: var(--text-primary) !important;
            padding: 12px 16px !important;
            border: none !important;
            backdrop-filter: blur(20px) !important;
        }

        select.form-control-glass option:hover,
        select.form-control-glass option:checked {
            background: rgba(0, 255, 136, 0.2) !important;
            color: var(--accent-color) !important;
        }

        /* Text Editor Container */
        .editor-container {
            position: relative;
            margin-bottom: 16px;
        }

        .editor-toolbar {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px 12px 0 0;
            padding: 12px 16px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .editor-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            color: rgba(255, 255, 255, 0.8);
            padding: 6px 12px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .editor-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .editor-btn.active {
            background: rgba(0, 255, 136, 0.2);
            color: var(--accent-color);
            border-color: rgba(0, 255, 136, 0.3);
        }

        .editor-content {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-top: none;
            border-radius: 0 0 12px 12px;
            min-height: 400px;
            padding: 20px;
            color: var(--text-primary);
            font-size: 14px;
            line-height: 1.6;
            outline: none;
            resize: vertical;
        }

        /* Image Upload Area */
        .upload-zone {
            background: rgba(255, 255, 255, 0.05);
            border: 2px dashed rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .upload-zone:hover {
            background: rgba(0, 255, 136, 0.05);
            border-color: var(--accent-color);
            transform: translateY(-2px);
        }

        .upload-zone.dragover {
            background: rgba(0, 255, 136, 0.1);
            border-color: var(--accent-color);
            box-shadow: 0 0 20px rgba(0, 255, 136, 0.2);
        }

        .upload-icon {
            font-size: 64px;
            color: var(--accent-color);
            margin-bottom: 16px;
            opacity: 0.8;
            transition: all 0.3s ease;
        }

        .upload-zone:hover .upload-icon {
            opacity: 1;
            transform: scale(1.1);
        }

        .upload-text {
            color: var(--text-primary);
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .upload-hint {
            color: var(--text-secondary);
            font-size: 12px;
            margin-bottom: 16px;
        }

        .upload-preview {
            position: relative;
            max-width: 100%;
            margin: 16px 0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .upload-preview img {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            display: block;
        }

        .preview-overlay {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(255, 107, 107, 0.9);
            color: white;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .preview-overlay:hover {
            background: #ff6b6b;
            transform: scale(1.1);
        }

        /* Button Styles */
        .btn-modern {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 500;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent-color), #00cc6a);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 255, 136, 0.4);
            background: linear-gradient(135deg, #00cc6a, var(--accent-color));
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateY(-2px);
        }

        .btn-outline {
            background: transparent;
            color: var(--accent-color);
            border: 1px solid var(--accent-color);
        }

        .btn-outline:hover {
            background: var(--accent-color);
            color: white;
            transform: translateY(-2px);
        }

        /* Settings Panel */
        .settings-panel {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 16px;
            padding: 24px;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .setting-item {
            margin-bottom: 20px;
        }

        .setting-item:last-child {
            margin-bottom: 0;
        }

        /* Character Counter */
        .char-counter {
            position: absolute;
            bottom: -20px;
            right: 0;
            font-size: 11px;
            color: var(--text-secondary);
        }

        .char-counter.warning {
            color: #ffa726;
        }

        .char-counter.danger {
            color: #ff6b6b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .glass-card {
                padding: 20px;
                margin-bottom: 16px;
            }

            .upload-zone {
                padding: 24px 16px;
                min-height: 150px;
            }

            .upload-icon {
                font-size: 48px;
            }

            .editor-toolbar {
                padding: 8px 12px;
            }

            .editor-btn {
                padding: 4px 8px;
                font-size: 11px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="news-edit-container">
        <div class="container-fluid">
            <!-- Simple Back Button -->
            <div style="margin-bottom: 24px;">
                <a href="{{ route('admin.news.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Daftar Berita
                </a>
            </div>

            <form action="{{ route('admin.news.update', $news->id) }}" method="POST" enctype="multipart/form-data"
                id="news-form">
                @csrf
                @method('PUT')
                <div class="row">
                    <!-- Main Content -->
                    <div class="col-lg-8">
                        <!-- Article Information -->
                        <div class="glass-card">
                            <div class="card-header">
                                <i class="fas fa-edit"></i>
                                <h4>Edit Informasi Artikel</h4>
                            </div>

                            <!-- Title -->
                            <div class="form-group">
                                <label class="form-label required" for="title">
                                    <i class="fas fa-heading"></i>
                                    Judul Berita
                                </label>
                                <input type="text" class="form-control-glass @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title', $news->title) }}"
                                    placeholder="Masukkan judul berita yang menarik dan informatif..." maxlength="100"
                                    required>
                                <div class="char-counter" id="title-counter">0/100</div>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Content Editor -->
                            <div class="form-group">
                                <label class="form-label required" for="content">
                                    <i class="fas fa-align-left"></i>
                                    Konten Berita
                                </label>
                                <div class="editor-container">
                                    <div class="editor-toolbar">
                                        <button type="button" class="editor-btn" onclick="formatText('bold')">
                                            <i class="fas fa-bold"></i>
                                        </button>
                                        <button type="button" class="editor-btn" onclick="formatText('italic')">
                                            <i class="fas fa-italic"></i>
                                        </button>
                                        <button type="button" class="editor-btn" onclick="formatText('underline')">
                                            <i class="fas fa-underline"></i>
                                        </button>
                                        <div
                                            style="width: 1px; height: 20px; background: rgba(255,255,255,0.2); margin: 0 4px;">
                                        </div>
                                        <button type="button" class="editor-btn" onclick="applyHeading('H1')">H1</button>
                                        <button type="button" class="editor-btn" onclick="applyHeading('H2')">H2</button>
                                        <button type="button" class="editor-btn" onclick="applyHeading('H3')">H3</button>
                                        <div
                                            style="width: 1px; height: 20px; background: rgba(255,255,255,0.2); margin: 0 4px;">
                                        </div>
                                        <button type="button" class="editor-btn" onclick="alignText('justifyLeft')">
                                            <i class="fas fa-align-left"></i>
                                        </button>
                                        <button type="button" class="editor-btn" onclick="alignText('justifyCenter')">
                                            <i class="fas fa-align-center"></i>
                                        </button>
                                        <button type="button" class="editor-btn" onclick="alignText('justifyRight')">
                                            <i class="fas fa-align-right"></i>
                                        </button>
                                        <button type="button" class="editor-btn" onclick="alignText('justifyFull')">
                                            <i class="fas fa-align-justify"></i>
                                        </button>
                                        <div
                                            style="width: 1px; height: 20px; background: rgba(255,255,255,0.2); margin: 0 4px;">
                                        </div>
                                        <button type="button" class="editor-btn" onclick="insertList('ul')">
                                            <i class="fas fa-list-ul"></i>
                                        </button>
                                        <button type="button" class="editor-btn" onclick="insertList('ol')">
                                            <i class="fas fa-list-ol"></i>
                                        </button>
                                        <button type="button" class="editor-btn" onclick="insertLink()">
                                            <i class="fas fa-link"></i>
                                        </button>
                                    </div>
                                    <div class="editor-content" contenteditable="true" id="content-editor"
                                        data-placeholder="Tulis konten berita di sini... Gunakan toolbar di atas untuk formatting.">
                                        {{ old('content', $news->content) }}
                                    </div>
                                    <textarea name="content" id="content" style="display: none;" required>{{ old('content', $news->content) }}</textarea>
                                </div>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image Upload -->
                            <div class="form-group">
                                <label class="form-label" for="image">
                                    <i class="fas fa-image"></i>
                                    Gambar Utama
                                </label>
                                <div class="upload-zone" id="upload-zone">
                                    <div id="upload-placeholder" style="display: none;">
                                        <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                        <div class="upload-text">Klik atau drag & drop gambar</div>
                                        <div class="upload-hint">JPG, PNG, GIF - Maksimal 2MB</div>
                                        <button type="button" class="btn-modern btn-outline btn-sm">
                                            <i class="fas fa-folder-open"></i>
                                            Pilih File
                                        </button>
                                    </div>
                                    <div id="image-preview">
                                        <div class="upload-preview">
                                            <img src="https://picsum.photos/400/300?random=1" alt="Current Image">
                                            <button type="button" class="preview-overlay" onclick="removeImage()">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <input type="file" class="@error('image') is-invalid @enderror" id="image"
                                    name="image" accept="image/*" style="display: none;">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <!-- Publish Settings -->
                        <div class="glass-card">
                            <div class="card-header">
                                <i class="fas fa-cogs"></i>
                                <h4>Pengaturan Publikasi</h4>
                            </div>

                            <div class="settings-panel">
                                <div class="setting-item">
                                    <label class="form-label required" for="status">
                                        <i class="fas fa-toggle-on"></i>
                                        Status Publikasi
                                    </label>
                                    <select class="form-control-glass @error('status') is-invalid @enderror"
                                        id="status" name="status" required>
                                        <option value="draft"
                                            {{ old('status', $news->status) == 'draft' ? 'selected' : '' }}>
                                            📝 Draft (Belum Dipublikasi)
                                        </option>
                                        <option value="published"
                                            {{ old('status', $news->status) == 'published' ? 'selected' : '' }}>
                                            🌐 Published (Langsung Tampil)
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="setting-item">
                                    <label class="form-label" for="published_at">
                                        <i class="fas fa-calendar-alt"></i>
                                        Jadwal Publikasi
                                    </label>
                                    <input type="datetime-local"
                                        class="form-control-glass @error('published_at') is-invalid @enderror"
                                        id="published_at" name="published_at"
                                        value="{{ old('published_at', $news->published_at ? $news->published_at->format('Y-m-d\TH:i') : '') }}">
                                    <small class="form-text text-muted"
                                        style="color: rgba(255,255,255,0.6); font-size: 11px; margin-top: 4px;">
                                        Bisa disesuaikan manual sesuai kebutuhan
                                    </small>
                                    @error('published_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div style="margin-top: 24px; display: flex; flex-direction: column; gap: 12px;">
                                <button type="submit" class="btn-modern btn-primary">
                                    <i class="fas fa-save"></i>
                                    Update Berita
                                </button>
                                <button type="button" class="btn-modern btn-secondary" onclick="previewArticle()">
                                    <i class="fas fa-eye"></i>
                                    Preview Artikel
                                </button>
                                <a href="{{ route('admin.news.index') }}" class="btn-modern btn-outline">
                                    <i class="fas fa-arrow-left"></i>
                                    Batal & Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content modal-content-glass">
                <div class="modal-header" style="border: none; padding: 24px;">
                    <h5 class="modal-title"
                        style="color: var(--text-primary); display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-eye"></i>
                        Preview Artikel
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        style="background: rgba(255,255,255,0.1); border: none; border-radius: 50%; width: 32px; height: 32px; color: white;"></button>
                </div>
                <div class="modal-body" style="padding: 0 24px 24px;" id="preview-content">
                    <!-- Preview content will be inserted here -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const titleInput = document.getElementById('title');
            const contentEditor = document.getElementById('content-editor');
            const contentTextarea = document.getElementById('content');
            const titleCounter = document.getElementById('title-counter');
            const imageInput = document.getElementById('image');
            const uploadZone = document.getElementById('upload-zone');
            const uploadPlaceholder = document.getElementById('upload-placeholder');
            const imagePreview = document.getElementById('image-preview');

            // Title character counter
            function updateTitleCounter() {
                const length = titleInput.value.length;
                titleCounter.textContent = `${length}/100`;

                if (length > 100) {
                    titleCounter.className = 'char-counter danger';
                } else if (length > 80) {
                    titleCounter.className = 'char-counter warning';
                } else {
                    titleCounter.className = 'char-counter';
                }
            }

            titleInput.addEventListener('input', updateTitleCounter);
            updateTitleCounter();

            // Content editor functionality
            contentEditor.addEventListener('input', function() {
                contentTextarea.value = this.innerHTML;
            });

            // Image upload functionality
            uploadZone.addEventListener('click', function(e) {
                if (e.target.closest('.preview-overlay')) return;
                imageInput.click();
            });

            // Drag and drop
            uploadZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('dragover');
            });

            uploadZone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
            });

            uploadZone.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');

                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    const file = files[0];
                    if (file.type.startsWith('image/')) {
                        handleImageUpload(file);
                    }
                }
            });

            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    handleImageUpload(file);
                }
            });

            function handleImageUpload(file) {
                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB.');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.innerHTML = `
                <div class="upload-preview">
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="preview-overlay" onclick="removeImage()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
                    uploadPlaceholder.style.display = 'none';
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        // Editor formatting functions
        function formatText(command) {
            document.execCommand(command, false, null);
            document.getElementById('content-editor').focus();
        }

        function applyHeading(heading) {
            document.execCommand('formatBlock', false, heading);
            document.getElementById('content-editor').focus();
        }

        function alignText(command) {
            document.execCommand(command, false, null);
            document.getElementById('content-editor').focus();
        }

        function insertList(type) {
            const command = type === 'ul' ? 'insertUnorderedList' : 'insertOrderedList';
            document.execCommand(command, false, null);
            document.getElementById('content-editor').focus();
        }

        function insertLink() {
            const url = prompt('Masukkan URL:');
            if (url) {
                document.execCommand('createLink', false, url);
            }
            document.getElementById('content-editor').focus();
        }

        // Remove image function
        function removeImage() {
            document.getElementById('image').value = '';
            document.getElementById('image-preview').style.display = 'none';
            document.getElementById('upload-placeholder').style.display = 'block';
        }

        // Preview function
        function previewArticle() {
            const title = document.getElementById('title').value || 'Judul Berita';
            const content = document.getElementById('content-editor').innerHTML || 'Konten berita...';
            const imagePreview = document.querySelector('#image-preview img');
            const imageSrc = imagePreview ? imagePreview.src : '';

            const previewHtml = `
        <article style="color: var(--text-primary); line-height: 1.8;">
            <h2 style="color: var(--accent-color); margin-bottom: 20px; font-size: 2rem;">${title}</h2>
            ${imageSrc ? `<img src="${imageSrc}" style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 12px; margin-bottom: 24px;">` : ''}
            <div style="font-size: 16px; line-height: 1.8;">
                ${content}
            </div>
            <hr style="border-color: rgba(255,255,255,0.1); margin: 32px 0;">
            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: var(--text-secondary);">
                <div>
                    <i class="fas fa-calendar"></i> ${new Date().toLocaleDateString('id-ID')}
                    <i class="fas fa-user ms-3"></i> Admin
                </div>
                <div>
                    <i class="fas fa-eye"></i> Preview Mode
                </div>
            </div>
        </article>
    `;

            document.getElementById('preview-content').innerHTML = previewHtml;
            const modal = new bootstrap.Modal(document.getElementById('previewModal'));
            modal.show();
        }

        // === FORCE PLAIN TEXT PASTE ===
        contentEditor.addEventListener('paste', function(e) {
            e.preventDefault();
            const clipboardData = (e.clipboardData || window.clipboardData);
            const text = clipboardData ? clipboardData.getData('text/plain') : '';
            if (document.queryCommandSupported && document.queryCommandSupported('insertText')) {
                document.execCommand('insertText', false, text);
            } else {
                const selection = window.getSelection();
                if (!selection.rangeCount) return;
                selection.deleteFromDocument();
                selection.getRangeAt(0).insertNode(document.createTextNode(text));
            }
            // Sinkronkan ke textarea hidden jika ada
            const hidden = document.getElementById('content');
            if (hidden) hidden.value = contentEditor.innerHTML;
        });
        // === END FORCE PLAIN TEXT PASTE ===
    </script>
@endpush
