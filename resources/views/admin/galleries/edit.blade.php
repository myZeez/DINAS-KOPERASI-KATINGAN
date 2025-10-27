@extends('admin.layouts.app')

@section('title', 'Edit Foto')

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

        <form action="{{ route('admin.galleries.update', $gallery->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Debug Errors Display -->
            @if ($errors->any())
                <div class="alert alert-danger"
                    style="background: rgba(255, 107, 107, 0.1); border: 1px solid rgba(255, 107, 107, 0.3); color: #ff6b6b; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
                    <h4><i class="fas fa-exclamation-triangle"></i> Ada kesalahan validasi:</h4>
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Success Message -->
            @if (session('success'))
                <div class="alert alert-success"
                    style="background: rgba(72, 187, 120, 0.1); border: 1px solid rgba(72, 187, 120, 0.3); color: #48bb78; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <!-- Error Message -->
            @if (session('error'))
                <div class="alert alert-danger"
                    style="background: rgba(255, 107, 107, 0.1); border: 1px solid rgba(255, 107, 107, 0.3); color: #ff6b6b; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
                    <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                </div>
            @endif

            <div class="row">
                <!-- Main Form -->
                <div class="col-lg-8">
                    <div class="glass-card">
                        <div class="card-titile-header">
                            <i class="fas fa-image"></i>
                            <h4>Edit Informasi Foto</h4>
                        </div>

                        <!-- Current Image Preview -->
                        <div class="current-image-section mb-4">
                            <label class="form-label">Gambar Saat Ini</label>
                            <div class="current-image-container">
                                <img src="{{ Storage::url($gallery->image) }}" alt="{{ $gallery->title }}"
                                    class="current-image">
                                <div class="image-overlay">
                                    <button type="button" class="btn btn-sm btn-outline-light" onclick="changeImage()">
                                        <i class="fas fa-camera"></i> Ganti Gambar
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Title -->
                        <div class="form-group">
                            <label class="form-label required" for="title">
                                <i class="fas fa-heading"></i>
                                Judul Foto
                            </label>
                            <input type="text" class="form-control-glass @error('title') is-invalid @enderror"
                                id="title" name="title" value="{{ old('title', $gallery->title) }}"
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
                                rows="4" placeholder="Deskripsi foto (opsional)...">{{ old('description', $gallery->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- New Image Upload -->
                        <div class="form-group" id="new-image-section" style="display: none;">
                            <label class="form-label" for="image">
                                <i class="fas fa-upload"></i>
                                Upload Foto Baru
                            </label>
                            <div class="upload-zone" id="upload-zone">
                                <div id="upload-placeholder">
                                    <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                    <div class="upload-text">Klik atau drag & drop gambar baru</div>
                                    <div class="upload-hint">JPG, PNG, GIF - Maksimal 5MB</div>
                                    <button type="button" class="btn-modern btn-outline btn-sm">
                                        <i class="fas fa-folder-open"></i>
                                        Pilih File
                                    </button>
                                </div>
                                <div id="image-preview" style="display: none;"></div>
                            </div>
                            <input type="file" class="@error('image') is-invalid @enderror" id="image" name="image"
                                accept="image/*" style="display: none;">
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
                        <div class="card-titile-header">
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
                                    <option value="kegiatan"
                                        {{ old('category', 'kegiatan') == 'kegiatan' ? 'selected' : '' }}>
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
                                    id="tags" name="tags" value="{{ old('tags', $gallery->tags) }}"
                                    placeholder="Pisahkan dengan koma...">
                                <small class="form-text" style="color: rgba(255,255,255,0.6); font-size: 11px;">
                                    Contoh: sosialisasi, koperasi, jakarta
                                </small>
                                @error('tags')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="setting-item">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                        value="1" {{ old('is_featured', $gallery->is_featured) ? 'checked' : '' }}>
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
                            <button type="submit" id="submitBtn" class="btn-modern btn-primary">
                                <i class="fas fa-save"></i>
                                <span id="submitText">Update Foto</span>
                            </button>
                            <button type="button" class="btn-modern btn-secondary" onclick="previewChanges()">
                                <i class="fas fa-eye"></i>
                                Preview
                            </button>
                            <a href="{{ route('admin.galleries.index') }}" class="btn-modern btn-outline">
                                <i class="fas fa-arrow-left"></i>
                                Batal & Kembali
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <style>
        /* Current Image Section */
        .current-image-section {
            margin-bottom: 24px;
        }

        .current-image-container {
            position: relative;
            display: inline-block;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .current-image {
            width: 100%;
            max-width: 400px;
            height: auto;
            display: block;
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .current-image-container:hover .image-overlay {
            opacity: 1;
        }

        /* Form Components */
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

        .card-titile-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        h4 {
            color: var(--text-primary);
            font-weight: 600;
            margin: 0;
            font-size: 1.3rem;
        }

        .card-title-header i {
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
            width: 100%;
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

        .form-control-glass {
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 12px !important;
            color: var(--text-primary) !important;
            padding: 14px 18px !important;
            font-size: 14px !important;
            transition: all 0.3s ease !important;
            backdrop-filter: blur(10px) !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        .form-control-glass::placeholder {
            color: rgba(255, 255, 255, 0.5) !important;
        }

        .form-control-glass:focus {
            background: rgba(255, 255, 255, 0.12) !important;
            border-color: var(--accent-color) !important;
            box-shadow: 0 0 0 3px rgba(0, 255, 136, 0.1) !important;
            outline: none !important;
        }

        /* Textarea specific styling */
        textarea.form-control-glass {
            resize: vertical !important;
            min-height: 100px !important;
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
            max-width: 100% !important;
            box-sizing: border-box !important;
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
            width: 100%;
        }

        .setting-item:last-child {
            margin-bottom: 0;
        }

        /* Stats Grid */
        .stats-grid {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--accent-color), #00cc6a);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000;
            font-size: 16px;
        }

        .stat-info {
            flex: 1;
        }

        .stat-value {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
        }

        .stat-label {
            font-size: 12px;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
            const newImageSection = document.getElementById('new-image-section');
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const form = document.querySelector('form');

            // Form submit handler
            form.addEventListener('submit', function(e) {
                // Prevent double submission
                if (submitBtn.disabled) {
                    e.preventDefault();
                    return false;
                }

                // Disable button and show loading
                submitBtn.disabled = true;
                submitText.textContent = 'Memproses...';
                submitBtn.style.opacity = '0.7';

                // Basic validation
                if (!titleInput.value.trim()) {
                    e.preventDefault();
                    alert('Judul foto harus diisi!');
                    // Reset button
                    submitBtn.disabled = false;
                    submitText.textContent = 'Update Foto';
                    submitBtn.style.opacity = '1';
                    titleInput.focus();
                    return false;
                }

                const categorySelect = document.getElementById('category');
                if (!categorySelect.value) {
                    e.preventDefault();
                    alert('Kategori harus dipilih!');
                    // Reset button
                    submitBtn.disabled = false;
                    submitText.textContent = 'Update Foto';
                    submitBtn.style.opacity = '1';
                    categorySelect.focus();
                    return false;
                }

                console.log('Form submission started');
            });

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
            }

            function handleImageUpload(file) {
                // Validate file size (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 5MB.');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.innerHTML = `
                <div style="position: relative; max-width: 100%; margin: 16px 0; border-radius: 12px; overflow: hidden; box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);">
                    <img src="${e.target.result}" style="width: 100%; max-height: 300px; object-fit: cover; display: block;">
                    <button type="button" onclick="removeNewImage()" style="position: absolute; top: 8px; right: 8px; background: rgba(255, 107, 107, 0.9); color: white; border: none; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
                    uploadPlaceholder.style.display = 'none';
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }

            // Make functions global
            window.removeNewImage = function() {
                imageInput.value = '';
                imagePreview.style.display = 'none';
                uploadPlaceholder.style.display = 'block';
            };

            window.changeImage = function() {
                newImageSection.style.display = 'block';
                newImageSection.scrollIntoView({
                    behavior: 'smooth'
                });
            };

            window.previewChanges = function() {
                const title = titleInput.value || 'Judul Foto';
                const description = document.getElementById('description').value || 'Deskripsi foto...';
                const category = document.getElementById('category').selectedOptions[0].text;

                alert(
                    `Preview:\nJudul: ${title}\nKategori: ${category}\nDeskripsi: ${description.substring(0, 100)}...`
                );
            };
        });
    </script>
@endpush
