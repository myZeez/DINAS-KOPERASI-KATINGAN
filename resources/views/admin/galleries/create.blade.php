@extends('admin.layouts.app')

@section('title', 'Tambah Foto')

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
            <a href="{{ route('admin.galleries.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Galeri
            </a>
        </div>

        <form action="{{ route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data" id="gallery-form">
            @csrf
            <div class="row">
                <!-- Main Form -->
                <div class="col-lg-8">
                    <div class="glass-card">
                        <div class="card-header">
                            <i class="fas fa-camera"></i>
                            <h4>Informasi Foto</h4>
                        </div>

                        <!-- Title -->
                        <div class="form-group">
                            <label class="form-label required" for="title">
                                <i class="fas fa-heading"></i>
                                Judul Foto
                            </label>
                            <input type="text" class="form-control-glass @error('title') is-invalid @enderror"
                                id="title" name="title" value="{{ old('title') }}"
                                placeholder="Masukkan judul foto..." maxlength="100" required>
                            <div class="char-counter" id="title-counter">0/100</div>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label class="form-label" for="description">
                                <i class="fas fa-align-left"></i>
                                Deskripsi
                            </label>
                            <textarea class="form-control-glass @error('description') is-invalid @enderror" id="description" name="description"
                                rows="4" placeholder="Deskripsi foto (opsional)...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image Upload -->
                        <div class="form-group">
                            <label class="form-label required" for="image">
                                <i class="fas fa-upload"></i>
                                Upload Foto
                            </label>
                            <div class="upload-zone" id="upload-zone">
                                <div id="upload-placeholder">
                                    <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                    <div class="upload-text">Klik atau drag & drop foto di sini</div>
                                    <div class="upload-hint">JPG, PNG, GIF - Maksimal 5MB</div>
                                    <button type="button" class="btn-modern btn-outline btn-sm">
                                        <i class="fas fa-folder-open"></i>
                                        Pilih File
                                    </button>
                                </div>
                                <div id="image-preview" style="display: none;"></div>
                            </div>
                            <input type="file" class="@error('image') is-invalid @enderror" id="image" name="image"
                                accept="image/*" style="display: none;" required>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Settings -->
                    <div class="glass-card">
                        <div class="card-header">
                            <i class="fas fa-cogs"></i>
                            <h4>Pengaturan</h4>
                        </div>

                        <div class="settings-panel">
                            <div class="setting-item">
                                <label class="form-label required" for="category">
                                    <i class="fas fa-tags"></i>
                                    Kategori
                                </label>
                                <select class="form-control-glass @error('category') is-invalid @enderror" id="category"
                                    name="category" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="kegiatan" {{ old('category') == 'kegiatan' ? 'selected' : '' }}>
                                        📅 Kegiatan
                                    </option>
                                    <option value="rapat" {{ old('category') == 'rapat' ? 'selected' : '' }}>
                                        🏢 Rapat
                                    </option>
                                    <option value="acara" {{ old('category') == 'acara' ? 'selected' : '' }}>
                                        🎉 Acara
                                    </option>
                                    <option value="fasilitas" {{ old('category') == 'fasilitas' ? 'selected' : '' }}>
                                        🏗️ Fasilitas
                                    </option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="setting-item">
                                <label class="form-label" for="tags">
                                    <i class="fas fa-hashtag"></i>
                                    Tags
                                </label>
                                <input type="text" class="form-control-glass @error('tags') is-invalid @enderror"
                                    id="tags" name="tags" value="{{ old('tags') }}"
                                    placeholder="Pisahkan dengan koma...">
                                <small class="form-text" style="color: rgba(255,255,255,0.6); font-size: 11px;">
                                    Contoh: sosialisasi, koperasi, jakarta
                                </small>
                                @error('tags')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="setting-item">
                                <label class="form-label required" for="status">
                                    <i class="fas fa-toggle-on"></i>
                                    Status
                                </label>
                                <select class="form-control-glass @error('status') is-invalid @enderror" id="status"
                                    name="status" required>
                                    <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>
                                        ✅ Aktif
                                    </option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>
                                        ❌ Tidak Aktif
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="setting-item">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                        value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured"
                                        style="color: var(--text-primary);">
                                        <i class="fas fa-star text-warning"></i>
                                        Jadikan foto unggulan
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div style="margin-top: 24px; display: flex; flex-direction: column; gap: 12px;">
                            <button type="submit" class="btn-modern btn-primary">
                                <i class="fas fa-save"></i>
                                Simpan Foto
                            </button>
                            <button type="button" class="btn-modern btn-secondary" onclick="previewPhoto()">
                                <i class="fas fa-eye"></i>
                                Preview
                            </button>
                            <a href="{{ route('admin.galleries.index') }}" class="btn-modern btn-outline">
                                <i class="fas fa-arrow-left"></i>
                                Batal & Kembali
                            </a>
                        </div>
                    </div>

                    <!-- Upload Tips -->
                    <div class="glass-card">
                        <div class="card-header">
                            <i class="fas fa-lightbulb"></i>
                            <h4>Tips Upload</h4>
                        </div>

                        <div class="tips-content">
                            <div class="tip-item">
                                <i class="fas fa-check-circle text-success"></i>
                                <span>Gunakan resolusi tinggi (min. 1200px)</span>
                            </div>
                            <div class="tip-item">
                                <i class="fas fa-check-circle text-success"></i>
                                <span>Format JPG untuk foto biasa</span>
                            </div>
                            <div class="tip-item">
                                <i class="fas fa-check-circle text-success"></i>
                                <span>Format PNG untuk gambar transparan</span>
                            </div>
                            <div class="tip-item">
                                <i class="fas fa-check-circle text-success"></i>
                                <span>Maksimal ukuran file 5MB</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <style>
        /* Glass Card Styles */
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 32px;
            margin-bottom: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

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
        }

        /* Glass Select Dropdown Styling */
        select.form-control-glass {
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 12px !important;
            color: var(--text-primary) !important;
            padding: 14px 18px !important;
            font-size: 14px !important;
            transition: all 0.3s ease !important;
            backdrop-filter: blur(10px) !important;
            width: 100% !important;
            cursor: pointer !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='rgba(255,255,255,0.7)' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: right 14px center !important;
            background-size: 16px !important;
        }

        select.form-control-glass:hover {
            background: rgba(255, 255, 255, 0.12) !important;
            border-color: rgba(0, 255, 136, 0.3) !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        }

        select.form-control-glass:focus {
            background: rgba(255, 255, 255, 0.15) !important;
            border-color: var(--accent-color) !important;
            box-shadow: 0 0 0 3px rgba(0, 255, 136, 0.1) !important;
            outline: none !important;
        }

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

        /* Upload Zone */
        .upload-zone {
            background: rgba(255, 255, 255, 0.05);
            border: 2px dashed rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .upload-zone:hover {
            background: rgba(0, 255, 136, 0.05);
            border-color: var(--accent-color);
        }

        .upload-zone.dragover {
            background: rgba(0, 255, 136, 0.1);
            border-color: var(--accent-color);
            transform: scale(1.02);
        }

        .upload-icon {
            font-size: 48px;
            color: var(--accent-color);
            margin-bottom: 16px;
            opacity: 0.8;
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
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent-color), #00cc6a);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 255, 136, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .btn-outline {
            background: transparent;
            color: var(--accent-color);
            border: 1px solid var(--accent-color);
        }

        .btn-outline:hover {
            background: var(--accent-color);
            color: white;
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

        /* Form Text */
        .form-text {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 4px;
        }

        /* Form Check */
        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-check-input {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            width: 18px;
            height: 18px;
        }

        .form-check-input:checked {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .form-check-label {
            font-size: 14px;
            margin-bottom: 0;
        }

        /* Tips Content */
        .tips-content {
            padding: 16px 0;
        }

        .tip-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            color: var(--text-primary);
            font-size: 13px;
        }

        .tip-item:last-child {
            margin-bottom: 0;
        }

        .tip-item i {
            width: 16px;
            flex-shrink: 0;
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
                font-size: 36px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const titleInput = document.getElementById('title');
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

            if (titleInput) {
                titleInput.addEventListener('input', updateTitleCounter);
                updateTitleCounter();
            }

            // Image upload functionality
            if (uploadZone) {
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
                    if (!this.contains(e.relatedTarget)) {
                        this.classList.remove('dragover');
                    }
                });

                uploadZone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.classList.remove('dragover');

                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        const file = files[0];
                        if (file.type.startsWith('image/')) {
                            handleImageUpload(file);
                        } else {
                            alert('Please select a valid image file.');
                        }
                    }
                });

                imageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        handleImageUpload(file);
                    }
                });
            }

            function handleImageUpload(file) {
                // Validate file size (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 5MB.');
                    return;
                }

                // Validate file type
                if (!file.type.match(/^image\/(jpeg|jpg|png|gif)$/)) {
                    alert('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.innerHTML = `
                <div style="position: relative; max-width: 100%; margin: 16px 0; border-radius: 12px; overflow: hidden; box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);">
                    <img src="${e.target.result}" style="width: 100%; max-height: 300px; object-fit: cover; display: block;">
                    <div class="preview-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.6); display: flex; align-items: center; justify-content: center; opacity: 0; transition: all 0.3s ease;">
                        <button type="button" onclick="removeImage()" style="background: rgba(255, 107, 107, 0.9); color: white; border: none; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div style="position: absolute; bottom: 8px; left: 8px; background: rgba(0, 0, 0, 0.7); color: white; padding: 4px 8px; border-radius: 4px; font-size: 11px;">
                        ${(file.size / 1024 / 1024).toFixed(2)} MB
                    </div>
                </div>
            `;
                    uploadPlaceholder.style.display = 'none';
                    imagePreview.style.display = 'block';

                    // Add hover effect to preview overlay
                    const previewContainer = imagePreview.querySelector('div');
                    const previewOverlay = previewContainer.querySelector('.preview-overlay');

                    previewContainer.addEventListener('mouseenter', () => {
                        previewOverlay.style.opacity = '1';
                    });

                    previewContainer.addEventListener('mouseleave', () => {
                        previewOverlay.style.opacity = '0';
                    });
                };
                reader.readAsDataURL(file);
            }

            // Make functions global
            window.removeImage = function() {
                imageInput.value = '';
                imagePreview.style.display = 'none';
                uploadPlaceholder.style.display = 'block';
            };

            window.previewPhoto = function() {
                const title = titleInput.value || 'Judul Foto';
                const description = document.getElementById('description').value || 'Deskripsi foto...';
                const category = document.getElementById('category').selectedOptions[0]?.text || 'Kategori';
                const status = document.getElementById('status').selectedOptions[0]?.text || 'Status';

                alert(
                    `Preview:\nJudul: ${title}\nKategori: ${category}\nStatus: ${status}\nDeskripsi: ${description.substring(0, 100)}...`
                );
            };
        });
    </script>
@endpush
