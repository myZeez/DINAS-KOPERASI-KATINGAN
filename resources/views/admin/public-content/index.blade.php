@extends('admin.layouts.app')

@section('title', 'Kelola Website')

@push('styles')
    <link href="{{ asset('css/public-content.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid">

        <!-- Modern Page Header -->
        @include('admin.partials.page-header', [
            'title' => 'Kelola Website',
            'subtitle' => 'Atur slideshow, layanan unggulan, dan konten halaman website',
            'icon' => 'fas fa-globe',
            'breadcrumb' => 'Kelola Website',
            'primaryAction' => [
                'url' => '#',
                'text' => 'Tambah Konten',
                'icon' => 'fas fa-plus',
                'modal' => 'publicContentModal',
            ],
            'quickStats' => [
                [
                    'value' => $totalCarousels ?? 0,
                    'label' => 'SLIDESHOW',
                    'icon' => 'fas fa-images',
                ],
                [
                    'value' => $activeCarousels ?? 0,
                    'label' => 'AKTIF',
                    'icon' => 'fas fa-check',
                ],
                [
                    'value' => $totalServices ?? 0,
                    'label' => 'LAYANAN',
                    'icon' => 'fas fa-cogs',
                ],
                [
                    'value' => $activeServices ?? 0,
                    'label' => 'TERSEDIA',
                    'icon' => 'fas fa-star',
                ],
            ],
        ])

        <!-- Main Content Grid -->
        <div class="row">

            <!-- Slideshow Beranda -->
            <div class="col-xl-8 mb-4">
                <div class="glass-card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-images me-2"></i>Slideshow Beranda
                        </h5>
                        <button class="btn btn-sm btn-accent" id="addCarousel">
                            <i class="fas fa-plus me-1"></i>Tambah Slide
                        </button>
                    </div>
                    <div class="card-body">

                        <!-- Carousel List -->
                        <div class="row">
                            @forelse($carousels ?? [] as $carousel)
                                <div class="col-lg-6 mb-3">
                                    <div class="carousel-preview-card">
                                        <div class="carousel-image-container">
                                            <img src="{{ $carousel->image ? asset('storage/' . $carousel->image) : asset('Img/BackroudAdmin.jpg') }}"
                                                class="carousel-preview-image" alt="Carousel">
                                            <div class="carousel-overlay">
                                                <h6 class="mb-1">{{ $carousel->title }}</h6>
                                                <small>{{ $carousel->subtitle ?? 'No subtitle' }}</small>
                                            </div>
                                            <div class="carousel-actions">
                                                <button class="btn btn-sm btn-light me-1 edit-carousel"
                                                    data-id="{{ $carousel->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger delete-carousel"
                                                    data-id="{{ $carousel->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="carousel-info">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">Slide {{ $carousel->sort_order }} -
                                                    {{ $carousel->is_active ? 'Active' : 'Inactive' }}</small>
                                                <span class="badge bg-{{ $carousel->is_active ? 'success' : 'secondary' }}">
                                                    {{ $carousel->is_active ? 'Live' : 'Draft' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-images mb-3"
                                            style="font-size: 3rem; color: var(--text-secondary);"></i>
                                        <h6>Belum ada slideshow</h6>
                                        <p class="text-muted">Tambahkan gambar slide pertama untuk halaman utama</p>
                                        <button class="btn btn-accent"
                                            onclick="document.getElementById('addCarousel').click()">
                                            <i class="fas fa-plus me-2"></i>Tambah Slide
                                        </button>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Bantuan -->
            <div class="col-xl-4 mb-4">
                <div class="glass-card h-100">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Panduan Penggunaan
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Slideshow Info -->
                        <div class="info-card mb-3">
                            <div class="d-flex align-items-start">
                                <div class="info-icon me-3">
                                    <i class="fas fa-images"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Slideshow Beranda</h6>
                                    <p class="small text-muted mb-1">Gambar besar yang tampil di halaman utama website</p>
                                    <span class="badge bg-primary small">Header Website</span>
                                </div>
                            </div>
                        </div>

                        <!-- Services Info -->
                        <div class="info-card mb-3">
                            <div class="d-flex align-items-start">
                                <div class="info-icon me-3">
                                    <i class="fas fa-cogs"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Layanan Unggulan</h6>
                                    <p class="small text-muted mb-1">Layanan utama yang ditampilkan dengan logo/gambar</p>
                                    <span class="badge bg-success small">Section Layanan</span>
                                </div>
                            </div>
                        </div>

                        <!-- Content Info -->
                        <div class="info-card">
                            <div class="d-flex align-items-start">
                                <div class="info-icon me-3">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Konten Halaman</h6>
                                    <p class="small text-muted mb-1">Teks dan informasi di berbagai bagian website</p>
                                    <span class="badge bg-info small">Seluruh Website</span>
                                </div>
                            </div>
                        </div>

                        <hr class="my-3">

                        <!-- Quick Actions -->
                        <h6 class="mb-2">
                            <i class="fas fa-bolt me-1"></i>Aksi Cepat
                        </h6>
                        <div class="d-grid gap-2">
                            <button class="btn btn-glass btn-sm" onclick="previewWebsite()">
                                <i class="fas fa-eye me-2"></i>Lihat Website
                            </button>
                            <button class="btn btn-glass btn-sm" data-bs-toggle="modal" data-bs-target="#settingsModal">
                                <i class="fas fa-cog me-2"></i>Pengaturan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Services Section -->
        <div class="row">
            <div class="col-12">
                <div class="glass-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-cogs me-2"></i>Layanan Unggulan
                        </h5>
                        <button class="btn btn-sm btn-accent" id="addService">
                            <i class="fas fa-plus me-1"></i>Tambah Layanan
                        </button>
                    </div>
                    <div class="card-body">

                        <!-- Services Table -->
                        <div class="table-responsive">
                            <table class="table table-glass">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">Logo</th>
                                        <th style="width: 25%;">Nama Layanan</th>
                                        <th style="width: 40%;">Deskripsi</th>
                                        <th style="width: 10%;">Status</th>
                                        <th style="width: 15%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($services ?? [] as $service)
                                        <tr>
                                            <td>
                                                <div class="service-logo"
                                                    style="width: 40px; height: 40px; background: rgba(0, 255, 136, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                                    @if ($service->image)
                                                        <img src="{{ asset('storage/' . $service->image) }}"
                                                            alt="{{ $service->title }}"
                                                            style="width: 100%; height: 100%; object-fit: cover;">
                                                    @else
                                                        <i class="fas fa-image" style="color: #999; font-size: 16px;"></i>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-medium">{{ $service->title }}</div>
                                            </td>
                                            <td>
                                                <div style="font-size: 14px; color: var(--text-secondary);">
                                                    {{ Str::limit($service->description, 80) }}
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $service->is_active ? 'success' : 'secondary' }}">
                                                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-glass edit-service"
                                                        data-id="{{ $service->id }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-glass"
                                                        onclick="toggleServiceStatus({{ $service->id }}, '{{ $service->is_active ? 'inactive' : 'active' }}')">
                                                        <i
                                                            class="fas fa-{{ $service->is_active ? 'eye-slash' : 'eye' }}"></i>
                                                    </button>
                                                    <button class="btn btn-glass text-danger delete-service"
                                                        data-id="{{ $service->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <div class="empty-state">
                                                    <i class="fas fa-images mb-3"
                                                        style="font-size: 3rem; color: var(--text-secondary);"></i>
                                                    <h6>Belum ada layanan</h6>
                                                    <p class="text-muted">Tambahkan layanan unggulan dengan logo/gambar
                                                        menarik</p>
                                                    <button class="btn btn-accent"
                                                        onclick="document.getElementById('addService').click()">
                                                        <i class="fas fa-plus me-2"></i>Tambah Layanan
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Konten Halaman Website -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="glass-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-file-alt me-2"></i>Konten Halaman Website
                        </h5>
                        <button class="btn btn-sm btn-accent" data-bs-toggle="modal"
                            data-bs-target="#publicContentModal">
                            <i class="fas fa-plus me-1"></i>Tambah Konten
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Konten Halaman Table -->
                        <div class="table-responsive">
                            <table class="table table-glass">
                                <thead>
                                    <tr>
                                        <th style="width: 20%;">Bagian Halaman</th>
                                        <th style="width: 25%;">Judul</th>
                                        <th style="width: 40%;">Konten</th>
                                        <th style="width: 10%;">Status</th>
                                        <th style="width: 15%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($publicContents ?? [] as $content)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary">{{ $content->section_name }}</span>
                                            </td>
                                            <td>
                                                <div class="fw-medium">{{ $content->title ?? 'Tidak ada judul' }}</div>
                                            </td>
                                            <td>
                                                <div style="font-size: 14px; color: var(--text-secondary);">
                                                    {{ Str::limit($content->content ?? 'Tidak ada konten', 80) }}
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $content->is_active ? 'success' : 'secondary' }}">
                                                    {{ $content->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-glass"
                                                        onclick="editPublicContent({{ $content->id }})">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    <button class="btn btn-glass text-danger"
                                                        onclick="deletePublicContent({{ $content->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <div class="empty-state">
                                                    <i class="fas fa-file-alt mb-3"
                                                        style="font-size: 3rem; color: var(--text-secondary);"></i>
                                                    <h6>Belum ada konten halaman</h6>
                                                    <p class="text-muted">Tambahkan teks dan informasi untuk berbagai
                                                        bagian website</p>
                                                    <button class="btn btn-accent" data-bs-toggle="modal"
                                                        data-bs-target="#publicContentModal">
                                                        <i class="fas fa-plus me-2"></i>Tambah Konten
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Carousel Modal -->
    <div class="modal fade" id="carouselModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-glass">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-images me-2"></i>Tambah Gambar Slideshow
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="carouselForm" action="{{ route('admin.public-content.carousel.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Judul *</label>
                                <input type="text" class="form-control form-control-glass" name="title" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sub Judul</label>
                                <input type="text" class="form-control form-control-glass" name="subtitle">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gambar Slideshow</label>
                            <input type="file" class="form-control form-control-glass" name="image"
                                accept="image/*">
                            <small class="text-muted">Ukuran maksimal 2MB. Format: JPG, PNG, GIF</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Teks Tombol</label>
                                <input type="text" class="form-control form-control-glass" name="button_text"
                                    placeholder="Contoh: Selengkapnya">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Link Tombol</label>
                                <input type="url" class="form-control form-control-glass" name="button_link"
                                    placeholder="https://...">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-accent">
                            <i class="fas fa-save me-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Service Modal -->
    <div class="modal fade" id="serviceModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content modal-glass">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-cogs me-2"></i>Tambah Layanan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="serviceForm" action="{{ route('admin.public-content.service.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <!-- Service Form Tabs -->
                        <ul class="nav nav-tabs nav-tabs-glass mb-4" id="serviceFormTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="basic-info-tab" data-bs-toggle="tab"
                                    data-bs-target="#basic-info" type="button" role="tab">
                                    <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="requirements-tab" data-bs-toggle="tab"
                                    data-bs-target="#requirements" type="button" role="tab">
                                    <i class="fas fa-clipboard-list me-2"></i>Persyaratan
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="procedure-tab" data-bs-toggle="tab"
                                    data-bs-target="#procedure" type="button" role="tab">
                                    <i class="fas fa-route me-2"></i>Prosedur & Biaya
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#contact" type="button" role="tab">
                                    <i class="fas fa-phone me-2"></i>Kontak & Link
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="serviceFormTabContent">
                            <!-- Basic Information Tab -->
                            <div class="tab-pane fade show active" id="basic-info" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Layanan *</label>
                                            <input type="text" class="form-control form-control-glass" name="title" required>
                                            <small class="text-muted">Contoh: Pendirian Koperasi Baru</small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Kategori Layanan *</label>
                                            <select class="form-control form-control-glass" name="service_category" required>
                                                <option value="">Pilih Kategori</option>
                                                <option value="perizinan">Perizinan</option>
                                                <option value="pembinaan">Pembinaan</option>
                                                <option value="pengawasan">Pengawasan</option>
                                                <option value="konsultasi">Konsultasi</option>
                                                <option value="pelatihan">Pelatihan</option>
                                                <option value="sertifikasi">Sertifikasi</option>
                                                <option value="lainnya">Lainnya</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Deskripsi Singkat *</label>
                                            <textarea class="form-control form-control-glass" name="description" rows="3" maxlength="500" required></textarea>
                                            <small class="text-muted">Maksimal 500 karakter. Deskripsi yang akan tampil di halaman utama.</small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Deskripsi Lengkap</label>
                                            <div id="serviceDetailEditor" class="custom-editor" contenteditable="true"
                                                style="min-height:180px;background:#1e2330;border:1px solid #2d3444;border-radius:8px;padding:12px;overflow:auto">
                                            </div>
                                            <textarea name="content_detail" id="serviceDetailHidden" class="d-none"></textarea>
                                            <small class="text-muted">Penjelasan detail tentang layanan ini.</small>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Logo/Ikon Layanan *</label>
                                            <input type="file" class="form-control form-control-glass" name="image"
                                                accept="image/*" required>
                                            <small class="text-muted">Format: JPG, PNG, GIF, SVG. Maksimal 2MB. Rekomendasi ukuran: 300x300px</small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Status Layanan *</label>
                                            <select class="form-control form-control-glass" name="service_status" required>
                                                <option value="active">Aktif</option>
                                                <option value="inactive">Tidak Aktif</option>
                                                <option value="maintenance">Dalam Perbaikan</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Urutan Tampil</label>
                                            <input type="number" class="form-control form-control-glass" name="sort_order"
                                                value="1" min="1" max="100">
                                            <small class="text-muted">Semakin kecil angka, semakin atas posisinya</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Requirements Tab -->
                            <div class="tab-pane fade" id="requirements" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Persyaratan Umum</label>
                                            <div id="requirementsEditor" class="custom-editor" contenteditable="true"
                                                style="min-height:200px;background:#1e2330;border:1px solid #2d3444;border-radius:8px;padding:12px;overflow:auto">
                                                <p>Contoh:</p>
                                                <ul>
                                                    <li>KTP pemohon yang masih berlaku</li>
                                                    <li>Surat keterangan domisili</li>
                                                    <li>NPWP (jika ada)</li>
                                                </ul>
                                            </div>
                                            <textarea name="requirements" id="requirementsHidden" class="d-none"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Dokumen yang Diperlukan</label>
                                            <div id="documentsEditor" class="custom-editor" contenteditable="true"
                                                style="min-height:200px;background:#1e2330;border:1px solid #2d3444;border-radius:8px;padding:12px;overflow:auto">
                                                <p>Contoh:</p>
                                                <ul>
                                                    <li>Formulir permohonan (bermaterai)</li>
                                                    <li>Fotocopy KTP semua pengurus</li>
                                                    <li>Akta pendirian koperasi</li>
                                                    <li>Berita acara pembentukan</li>
                                                </ul>
                                            </div>
                                            <textarea name="required_documents" id="documentsHidden" class="d-none"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Catatan Penting</label>
                                            <textarea class="form-control form-control-glass" name="important_notes" rows="3"
                                                placeholder="Catatan khusus atau informasi penting yang perlu diperhatikan pemohon..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Procedure & Cost Tab -->
                            <div class="tab-pane fade" id="procedure" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Langkah-langkah Prosedur</label>
                                            <div id="procedureEditor" class="custom-editor" contenteditable="true"
                                                style="min-height:250px;background:#1e2330;border:1px solid #2d3444;border-radius:8px;padding:12px;overflow:auto">
                                                <p>Contoh prosedur:</p>
                                                <ol>
                                                    <li>Pemohon datang ke kantor dengan membawa persyaratan</li>
                                                    <li>Mengisi formulir permohonan</li>
                                                    <li>Verifikasi dokumen oleh petugas</li>
                                                    <li>Proses penelitian dan pemeriksaan</li>
                                                    <li>Penerbitan surat keputusan</li>
                                                </ol>
                                            </div>
                                            <textarea name="procedure_steps" id="procedureHidden" class="d-none"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Biaya Layanan</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control form-control-glass" name="service_fee"
                                                    value="0" min="0" placeholder="0">
                                            </div>
                                            <small class="text-muted">Isi 0 jika gratis. Gunakan angka tanpa titik/koma</small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Waktu Penyelesaian</label>
                                            <div class="row">
                                                <div class="col-6">
                                                    <input type="number" class="form-control form-control-glass" name="processing_time"
                                                        placeholder="7" min="1">
                                                </div>
                                                <div class="col-6">
                                                    <select class="form-control form-control-glass" name="processing_time_unit">
                                                        <option value="hari">Hari Kerja</option>
                                                        <option value="minggu">Minggu</option>
                                                        <option value="bulan">Bulan</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <small class="text-muted">Estimasi waktu penyelesaian layanan</small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Jam Pelayanan</label>
                                            <textarea class="form-control form-control-glass" name="service_hours" rows="3"
                                                placeholder="Senin - Jumat: 08:00 - 16:00 WIB&#10;Istirahat: 12:00 - 13:00 WIB&#10;Sabtu-Minggu: Libur"></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Lokasi Pelayanan</label>
                                            <textarea class="form-control form-control-glass" name="service_location" rows="2"
                                                placeholder="Kantor Dinas Koperasi dan UKM Kabupaten Katingan&#10;Jl. Contoh No. 123, Kasongan"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact & Links Tab -->
                            <div class="tab-pane fade" id="contact" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Penanggung Jawab</label>
                                            <input type="text" class="form-control form-control-glass" name="responsible_person"
                                                placeholder="Nama lengkap penanggung jawab">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Nomor Telefon</label>
                                            <input type="tel" class="form-control form-control-glass" name="phone_number"
                                                placeholder="0812-3456-7890">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Email Kontak</label>
                                            <input type="email" class="form-control form-control-glass" name="contact_email"
                                                placeholder="layanan@diskopukm-katingan.go.id">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Link Eksternal</label>
                                            <input type="url" class="form-control form-control-glass" name="external_link"
                                                placeholder="https://...">
                                            <small class="text-muted">Link ke website/aplikasi terkait layanan ini</small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Link Download Formulir</label>
                                            <input type="url" class="form-control form-control-glass" name="form_download_link"
                                                placeholder="https://...">
                                            <small class="text-muted">Link untuk mengunduh formulir permohonan</small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Link Panduan/Tutorial</label>
                                            <input type="url" class="form-control form-control-glass" name="tutorial_link"
                                                placeholder="https://...">
                                            <small class="text-muted">Link ke video tutorial atau panduan lengkap</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>Tips:</strong> Lengkapi informasi kontak untuk memudahkan masyarakat menghubungi petugas terkait layanan ini.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-accent">
                            <i class="fas fa-save me-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Settings Modal -->
    <div class="modal fade" id="settingsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content modal-glass">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-cog me-2"></i>Pengaturan Tampilan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6 class="mb-3">Warna Aksen</h6>
                    <div class="d-flex gap-2 mb-4">
                        <div class="color-option" style="background: #00ff88;" onclick="applyAdminAccent('#00ff88')">
                        </div>
                        <div class="color-option" style="background: #ff6b6b;" onclick="applyAdminAccent('#ff6b6b')">
                        </div>
                        <div class="color-option" style="background: #ffd93d;" onclick="applyAdminAccent('#ffd93d')">
                        </div>
                        <div class="color-option" style="background: #6bcf7f;" onclick="applyAdminAccent('#6bcf7f')">
                        </div>
                    </div>



                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="showStatistics" checked>
                        <label class="form-check-label" for="showStatistics">Tampilkan Statistik</label>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="showTestimonials" checked>
                        <label class="form-check-label" for="showTestimonials">Tampilkan Testimoni</label>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="showLatestNews" checked>
                        <label class="form-check-label" for="showLatestNews">Tampilkan Berita Terbaru</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-accent" onclick="saveSettings()">
                        <i class="fas fa-save me-2"></i>Simpan Pengaturan
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection



@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Modals
            const carouselModal = new bootstrap.Modal(document.getElementById('carouselModal'));
            const serviceModal = new bootstrap.Modal(document.getElementById('serviceModal'));
            const publicContentModal = new bootstrap.Modal(document.getElementById('publicContentModal'));

            // Get form elements
            const carouselForm = document.getElementById('carouselForm');
            const serviceForm = document.getElementById('serviceForm');
            const serviceDetailEditor = document.getElementById('serviceDetailEditor');
            const serviceDetailHidden = document.getElementById('serviceDetailHidden');
            const publicContentForm = document.getElementById('publicContentForm');

            // Handle paste events for all editors to remove formatting
            function handlePaste(e) {
                e.preventDefault();

                // Get plain text from clipboard
                const text = (e.originalEvent || e).clipboardData.getData('text/plain');

                // Insert plain text without formatting
                if (window.getSelection) {
                    const selection = window.getSelection();
                    if (selection.getRangeAt && selection.rangeCount) {
                        const range = selection.getRangeAt(0);
                        range.deleteContents();
                        range.insertNode(document.createTextNode(text));

                        // Move cursor to end of inserted text
                        range.setStartAfter(range.endContainer);
                        range.collapse(true);
                        selection.removeAllRanges();
                        selection.addRange(range);
                    }
                }
            }

            // Apply paste handler to all contenteditable editors
            const editors = [
                'serviceDetailEditor',
                'requirementsEditor',
                'documentsEditor',
                'procedureEditor'
            ];

            editors.forEach(editorId => {
                const editor = document.getElementById(editorId);
                if (editor) {
                    editor.addEventListener('paste', handlePaste);
                }
            });

            // Add Service Button
            const addServiceBtn = document.getElementById('addService');
                    if (addServiceBtn) {
                        addServiceBtn.addEventListener('click', function() {
                            serviceForm.reset();

                            // Reset all editors
                            if (serviceDetailEditor) serviceDetailEditor.innerHTML = '';
                            const requirementsEditor = document.getElementById('requirementsEditor');
                            if (requirementsEditor) requirementsEditor.innerHTML = '<p>Contoh:</p><ul><li>KTP pemohon yang masih berlaku</li><li>Surat keterangan domisili</li><li>NPWP (jika ada)</li></ul>';
                            const documentsEditor = document.getElementById('documentsEditor');
                            if (documentsEditor) documentsEditor.innerHTML = '<p>Contoh:</p><ul><li>Formulir permohonan (bermaterai)</li><li>Fotocopy KTP semua pengurus</li><li>Akta pendirian koperasi</li><li>Berita acara pembentukan</li></ul>';
                            const procedureEditor = document.getElementById('procedureEditor');
                            if (procedureEditor) procedureEditor.innerHTML = '<p>Contoh prosedur:</p><ol><li>Pemohon datang ke kantor dengan membawa persyaratan</li><li>Mengisi formulir permohonan</li><li>Verifikasi dokumen oleh petugas</li><li>Proses penelitian dan pemeriksaan</li><li>Penerbitan surat keputusan</li></ol>';

                            // Set default values
                            serviceForm.querySelector('[name="service_status"]').value = 'active';
                            serviceForm.querySelector('[name="service_fee"]').value = '0';
                            serviceForm.querySelector('[name="processing_time_unit"]').value = 'hari';

                            // Reset to first tab
                            const firstTab = document.querySelector('#basic-info-tab');
                            if (firstTab) firstTab.click();

                            serviceForm.action = '{{ route('admin.public-content.service.store') }}';
                            serviceForm.querySelector('input[name="_method"]')?.remove();

                            // Remove any existing image info
                            const existingInfo = serviceForm.querySelector('.text-info');
                            if (existingInfo) existingInfo.remove();

                            // Make image required for new service
                            const fileInput = serviceForm.querySelector('[name="image"]');
                            if (fileInput) fileInput.required = true;

                            document.querySelector('#serviceModal .modal-title').innerHTML =
                                '<i class="fas fa-cogs me-2"></i>Tambah Layanan';
                            serviceModal.show();
                        });
                    }

                    // Edit Carousel
                    document.addEventListener('click', function(e) {
                        if (e.target.classList.contains('edit-carousel') || e.target.closest('.edit-carousel')) {
                            const button = e.target.classList.contains('edit-carousel') ? e.target : e.target
                                .closest('.edit-carousel');
                            const id = button.dataset.id;

                            fetch(`{{ url('admin/public-content/carousel') }}/${id}`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        const carousel = data.data;
                                        carouselForm.querySelector('[name="title"]').value = carousel.title ||
                                            '';
                                        carouselForm.querySelector('[name="subtitle"]').value = carousel
                                            .subtitle || '';
                                        carouselForm.querySelector('[name="button_text"]').value = carousel
                                            .button_text || '';
                                        carouselForm.querySelector('[name="button_link"]').value = carousel
                                            .button_link || '';

                                        carouselForm.action =
                                            `{{ url('admin/public-content/carousel') }}/${id}`;

                                        let methodInput = carouselForm.querySelector('input[name="_method"]');
                                        if (!methodInput) {
                                            methodInput = document.createElement('input');
                                            methodInput.type = 'hidden';
                                            methodInput.name = '_method';
                                            carouselForm.appendChild(methodInput);
                                        }
                                        methodInput.value = 'PUT';

                                        document.querySelector('#carouselModal .modal-title').innerHTML =
                                            '<i class="fas fa-edit me-2"></i>Edit Gambar Slideshow';
                                        carouselModal.show();
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('Gagal mengambil data carousel');
                                });
                        }
                    });

                    // Edit Service
                    document.addEventListener('click', function(e) {
                        if (e.target.classList.contains('edit-service') || e.target.closest('.edit-service')) {
                            const button = e.target.classList.contains('edit-service') ? e.target : e.target
                                .closest('.edit-service');
                            const id = button.dataset.id;

                            fetch(`{{ url('admin/public-content/service') }}/${id}`, {
                                    method: 'GET',
                                    headers: {
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                            .getAttribute('content'),
                                        'X-Requested-With': 'XMLHttpRequest'
                                    },
                                    credentials: 'same-origin'
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        const service = data.data;

                                        // Basic Information Tab
                                        serviceForm.querySelector('[name="title"]').value = service.title || '';
                                        serviceForm.querySelector('[name="service_category"]').value = service.service_category || '';
                                        serviceForm.querySelector('[name="description"]').value = service.description || '';
                                        if (serviceDetailEditor) serviceDetailEditor.innerHTML = service.content_detail || '';
                                        serviceForm.querySelector('[name="service_status"]').value = service.service_status || 'active';
                                        serviceForm.querySelector('[name="sort_order"]').value = service.sort_order || '';

                                        // Requirements Tab
                                        const requirementsEditor = document.getElementById('requirementsEditor');
                                        if (requirementsEditor) requirementsEditor.innerHTML = service.requirements || '';
                                        const documentsEditor = document.getElementById('documentsEditor');
                                        if (documentsEditor) documentsEditor.innerHTML = service.required_documents || '';
                                        serviceForm.querySelector('[name="important_notes"]').value = service.important_notes || '';

                                        // Procedure & Cost Tab
                                        const procedureEditor = document.getElementById('procedureEditor');
                                        if (procedureEditor) procedureEditor.innerHTML = service.procedure_steps || '';
                                        serviceForm.querySelector('[name="service_fee"]').value = service.service_fee || '';
                                        serviceForm.querySelector('[name="processing_time"]').value = service.processing_time || '';
                                        serviceForm.querySelector('[name="processing_time_unit"]').value = service.processing_time_unit || 'hari';
                                        serviceForm.querySelector('[name="service_hours"]').value = service.service_hours || '';
                                        serviceForm.querySelector('[name="service_location"]').value = service.service_location || '';

                                        // Contact & Links Tab
                                        serviceForm.querySelector('[name="responsible_person"]').value = service.responsible_person || '';
                                        serviceForm.querySelector('[name="phone_number"]').value = service.phone_number || '';
                                        serviceForm.querySelector('[name="contact_email"]').value = service.contact_email || '';
                                        serviceForm.querySelector('[name="external_link"]').value = service.external_link || '';
                                        serviceForm.querySelector('[name="form_download_link"]').value = service.form_download_link || '';
                                        serviceForm.querySelector('[name="tutorial_link"]').value = service.tutorial_link || '';

                                        // Note: File input cannot be pre-filled for security reasons
                                        // Show current image info if exists
                                        const fileInput = serviceForm.querySelector('[name="image"]');
                                        if (service.image && fileInput) {
                                            const imageInfo = document.createElement('small');
                                            imageInfo.className = 'text-info d-block mt-1';
                                            imageInfo.innerHTML =
                                                `<i class="fas fa-info-circle"></i> Gambar saat ini: ${service.image}`;

                                            // Remove previous info if exists
                                            const existingInfo = fileInput.parentNode.querySelector(
                                                '.text-info');
                                            if (existingInfo) existingInfo.remove();

                                            fileInput.parentNode.appendChild(imageInfo);
                                            fileInput.required = false; // Make optional for edit
                                        }

                                        serviceForm.action = `{{ url('admin/public-content/service') }}/${id}`;

                                        let methodInput = serviceForm.querySelector('input[name="_method"]');
                                        if (!methodInput) {
                                            methodInput = document.createElement('input');
                                            methodInput.type = 'hidden';
                                            methodInput.name = '_method';
                                            serviceForm.appendChild(methodInput);
                                        }
                                        methodInput.value = 'PUT';

                                        document.querySelector('#serviceModal .modal-title').innerHTML =
                                            '<i class="fas fa-edit me-2"></i>Edit Layanan';
                                        serviceModal.show();
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('Gagal mengambil data service');
                                });
                        }
                    });

                    // Delete Carousel
                    document.addEventListener('click', function(e) {
                        if (e.target.classList.contains('delete-carousel') || e.target.closest(
                                '.delete-carousel')) {
                            const button = e.target.classList.contains('delete-carousel') ? e.target : e.target
                                .closest('.delete-carousel');
                            const id = button.dataset.id;

                            if (confirm('Apakah Anda yakin ingin menghapus carousel ini?')) {
                                fetch(`{{ url('admin/public-content/carousel') }}/${id}`, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                                .getAttribute('content'),
                                            'Content-Type': 'application/json',
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            location.reload();
                                        } else {
                                            alert('Gagal menghapus carousel');
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        alert('Terjadi kesalahan saat menghapus carousel');
                                    });
                            }
                        }
                    });

                    // Delete Service
                    document.addEventListener('click', function(e) {
                        if (e.target.classList.contains('delete-service') || e.target.closest('.delete-service')) {
                            const button = e.target.classList.contains('delete-service') ? e.target : e.target
                                .closest('.delete-service');
                            const id = button.dataset.id;

                            confirmAction({
                                    title: 'Konfirmasi Hapus',
                                    message: 'Apakah Anda yakin ingin menghapus layanan ini?',
                                    confirmText: 'Hapus',
                                    icon: 'trash',
                                    variant: 'warning'
                                })
                                .then(ok => {
                                    if (!ok) return;
                                    showLoading();
                                    fetch(`{{ url('admin/public-content/service') }}/${id}`, {
                                            method: 'DELETE',
                                            headers: {
                                                'X-CSRF-TOKEN': document.querySelector(
                                                    'meta[name="csrf-token"]').getAttribute('content'),
                                                'Content-Type': 'application/json',
                                            }
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                queueToast('Layanan berhasil dihapus', 'success');
                                                location.reload();
                                            } else {
                                                showToast('Gagal menghapus layanan', 'error');
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                            showToast('Terjadi kesalahan saat menghapus layanan', 'error');
                                        })
                                        .finally(() => hideLoading());
                                });
                        }
                    });

                    // Form Submissions
                    if (carouselForm) {
                        carouselForm.addEventListener('submit', function(e) {
                            e.preventDefault();
                            const formData = new FormData(this);

                            showLoading();
                            fetch(this.action, {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                            .getAttribute('content'),
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        carouselModal.hide();
                                        queueToast('Carousel berhasil disimpan', 'success');
                                        location.reload();
                                    } else {
                                        showToast('Gagal menyimpan carousel', 'error');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    showToast('Terjadi kesalahan saat menyimpan carousel', 'error');
                                })
                                .finally(() => hideLoading());
                        });
                    }

                    if (serviceForm) {
                        serviceForm.addEventListener('submit', function(e) {
                            e.preventDefault();

                            // Handle all editors content
                            if (serviceDetailEditor && serviceDetailHidden) {
                                serviceDetailHidden.value = serviceDetailEditor.innerHTML.trim();
                            }

                            const requirementsEditor = document.getElementById('requirementsEditor');
                            const requirementsHidden = document.getElementById('requirementsHidden');
                            if (requirementsEditor && requirementsHidden) {
                                requirementsHidden.value = requirementsEditor.innerHTML.trim();
                            }

                            const documentsEditor = document.getElementById('documentsEditor');
                            const documentsHidden = document.getElementById('documentsHidden');
                            if (documentsEditor && documentsHidden) {
                                documentsHidden.value = documentsEditor.innerHTML.trim();
                            }

                            const procedureEditor = document.getElementById('procedureEditor');
                            const procedureHidden = document.getElementById('procedureHidden');
                            if (procedureEditor && procedureHidden) {
                                procedureHidden.value = procedureEditor.innerHTML.trim();
                            }

                            const formData = new FormData(this);

                            showLoading();
                            fetch(this.action, {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                            .getAttribute('content'),
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        serviceModal.hide();
                                        queueToast('Layanan berhasil disimpan', 'success');
                                        location.reload();
                                    } else {
                                        showToast('Gagal menyimpan layanan', 'error');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    showToast('Terjadi kesalahan saat menyimpan layanan', 'error');
                                })
                                .finally(() => hideLoading());
                        });
                    }

                    if (publicContentForm) {
                        publicContentForm.addEventListener('submit', function(e) {
                            e.preventDefault();
                            const formData = new FormData(this);

                            showLoading();
                            fetch(this.action, {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                            .getAttribute('content'),
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        publicContentModal.hide();
                                        queueToast('Konten berhasil disimpan', 'success');
                                        location.reload();
                                    } else {
                                        showToast('Gagal menyimpan konten publik', 'error');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    showToast('Terjadi kesalahan saat menyimpan konten publik', 'error');
                                })
                                .finally(() => hideLoading());
                        });
                    }

                    // Quick Actions
                    window.previewWebsite = function() {
                        window.open('/', '_blank');
                    };

                    // Remove duplicated functions - they are already defined below

                    window.toggleServiceStatus = function(id, status) {
                        showLoading();
                        fetch(`{{ url('admin/public-content/toggle-status') }}`, {
                                method: 'PATCH',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content'),
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    type: 'service',
                                    id: id,
                                    status: status
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    queueToast('Status layanan diperbarui', 'success');
                                    location.reload();
                                }
                            })
                            .catch(() => {
                                showToast('Gagal mengubah status', 'error');
                            })
                            .finally(() => hideLoading());
                    };
        });
    </script>
@endpush

    <!-- Public Content Modal -->
    <div class="modal fade" id="publicContentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-glass">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-file-alt me-2"></i>Tambah Konten Publik
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="publicContentForm" action="{{ route('admin.public-content.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Bagian Halaman *</label>
                                <select class="form-control form-control-glass" name="section_name" required>
                                    <option value="">Pilih Bagian Halaman</option>
                                    <option value="hero_main">Judul Utama</option>
                                    <option value="hero_secondary">Sub Judul</option>
                                </select>
                                <small class="text-muted d-block mt-1">Disederhanakan: hanya untuk teks hero beranda (Judul
                                    & Sub Judul).</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Judul</label>
                                <input type="text" class="form-control form-control-glass" name="title">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konten</label>
                            <textarea class="form-control form-control-glass" name="content" rows="4"
                                placeholder="Isi teks yang akan tampil di hero beranda (singkat dan jelas)"></textarea>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                                <label class="form-check-label">
                                    Aktifkan untuk ditampilkan di beranda
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-glass" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-accent">
                            <i class="fas fa-save me-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Modals
            const carouselModal = new bootstrap.Modal(document.getElementById('carouselModal'));
            const serviceModal = new bootstrap.Modal(document.getElementById('serviceModal'));
            const publicContentModal = new bootstrap.Modal(document.getElementById('publicContentModal'));

            // Get Forms
            const carouselForm = document.getElementById('carouselForm');
            const serviceForm = document.getElementById('serviceForm');
            const serviceDetailEditor = document.getElementById('serviceDetailEditor');
            const serviceDetailHidden = document.getElementById('serviceDetailHidden');
            const publicContentForm = document.getElementById('publicContentForm');

            // Header "Tambah Konten" Button
            const headerAddButton = document.querySelector('[data-bs-target="#publicContentModal"]');
            if (headerAddButton) {
                headerAddButton.addEventListener('click', function() {
                    publicContentForm.reset();
                    publicContentForm.action = '{{ route('admin.public-content.store') }}';
                    publicContentForm.querySelector('input[name="_method"]')?.remove();
                    document.querySelector('#publicContentModal .modal-title').innerHTML =
                        '<i class="fas fa-file-alt me-2"></i>Tambah Konten Publik';
                    publicContentModal.show();
                });
            }

            // Add Carousel Button
            const addCarouselBtn = document.getElementById('addCarousel');
            if (addCarouselBtn) {
                addCarouselBtn.addEventListener('click', function() {
                    carouselForm.reset();
                    carouselForm.action = '{{ route('admin.public-content.carousel.store') }}';
                    carouselForm.querySelector('input[name="_method"]')?.remove();
                    document.querySelector('#carouselModal .modal-title').innerHTML =
                        '<i class="fas fa-images me-2"></i>Tambah Gambar Slideshow';
                    carouselModal.show();
                });
            }

            // Add Service Button
            const addServiceBtn = document.getElementById('addService');
            if (addServiceBtn) {
                addServiceBtn.addEventListener('click', function() {
                    serviceForm.reset();
                    if (serviceDetailEditor) serviceDetailEditor.innerHTML = '';
                    serviceForm.action = '{{ route('admin.public-content.service.store') }}';
                    serviceForm.querySelector('input[name="_method"]')?.remove();
                    document.querySelector('#serviceModal .modal-title').innerHTML =
                        '<i class="fas fa-cogs me-2"></i>Tambah Layanan';
                    serviceModal.show();
                });
            }

            // Edit Carousel
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('edit-carousel') || e.target.closest('.edit-carousel')) {
                    const button = e.target.classList.contains('edit-carousel') ? e.target : e.target
                        .closest('.edit-carousel');
                    const id = button.dataset.id;

                    fetch(`{{ url('admin/public-content/carousel') }}/${id}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const carousel = data.data;
                                carouselForm.querySelector('[name="title"]').value = carousel.title ||
                                    '';
                                carouselForm.querySelector('[name="subtitle"]').value = carousel
                                    .subtitle || '';
                                carouselForm.querySelector('[name="button_text"]').value = carousel
                                    .button_text || '';
                                carouselForm.querySelector('[name="button_link"]').value = carousel
                                    .button_link || '';

                                carouselForm.action =
                                    `{{ url('admin/public-content/carousel') }}/${id}`;

                                let methodInput = carouselForm.querySelector('input[name="_method"]');
                                if (!methodInput) {
                                    methodInput = document.createElement('input');
                                    methodInput.type = 'hidden';
                                    methodInput.name = '_method';
                                    carouselForm.appendChild(methodInput);
                                }
                                methodInput.value = 'PUT';

                                document.querySelector('#carouselModal .modal-title').innerHTML =
                                    '<i class="fas fa-edit me-2"></i>Edit Gambar Slideshow';
                                carouselModal.show();
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('Gagal mengambil data carousel', 'error');
                        });
                }
            });

            // Edit Service
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('edit-service') || e.target.closest('.edit-service')) {
                    const button = e.target.classList.contains('edit-service') ? e.target : e.target
                        .closest('.edit-service');
                    const id = button.dataset.id;

                    console.log('Edit service clicked, ID:', id);
                    showLoading();

                    fetch(`/admin/public-content/service/${id}`, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin'
                        })
                        .then(response => {
                            console.log('Response status:', response.status);
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Service data received:', data);
                            hideLoading();

                            if (data.success) {
                                const service = data.data;
                                serviceForm.querySelector('[name="title"]').value = service.title || '';
                                serviceForm.querySelector('[name="description"]').value = service
                                    .description || '';

                                // Handle external_link field (not link)
                                const externalLinkField = serviceForm.querySelector('[name="external_link"]');
                                if (externalLinkField) {
                                    externalLinkField.value = service.external_link || '';
                                }

                                // Handle content_detail if editor exists
                                if (serviceDetailEditor) {
                                    serviceDetailEditor.innerHTML = service.content_detail || '';
                                }

                                // Reset file input
                                const imageInput = serviceForm.querySelector('[name="image"]');
                                imageInput.value = '';

                                // Show current image info if exists
                                const currentImageInfo = serviceForm.querySelector(
                                    '.current-image-info');
                                if (currentImageInfo) {
                                    currentImageInfo.remove();
                                }

                                if (service.image) {
                                    const imageInfo = document.createElement('div');
                                    imageInfo.className = 'current-image-info text-muted small mt-1';
                                    imageInfo.innerHTML =
                                        `Gambar saat ini: ${service.image.split('/').pop()}<br><small>Pilih file baru untuk mengubah gambar</small>`;
                                    imageInput.parentNode.appendChild(imageInfo);
                                }

                                serviceForm.action = `/admin/public-content/service/${id}`;

                                let methodInput = serviceForm.querySelector('input[name="_method"]');
                                if (!methodInput) {
                                    methodInput = document.createElement('input');
                                    methodInput.type = 'hidden';
                                    methodInput.name = '_method';
                                    serviceForm.appendChild(methodInput);
                                }
                                methodInput.value = 'PUT';

                                document.querySelector('#serviceModal .modal-title').innerHTML =
                                    '<i class="fas fa-edit me-2"></i>Edit Layanan';
                                serviceModal.show();
                            } else {
                                showToast('Gagal mengambil data service: ' + (data.message || 'Unknown error'), 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching service data:', error);
                            hideLoading();
                            showToast('Gagal mengambil data service: ' + error.message, 'error');
                        });
                }
            });

            // Delete Carousel
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('delete-carousel') || e.target.closest(
                        '.delete-carousel')) {
                    const button = e.target.classList.contains('delete-carousel') ? e.target : e.target
                        .closest('.delete-carousel');
                    const id = button.dataset.id;

                    confirmAction({
                            title: 'Konfirmasi Hapus',
                            message: 'Apakah Anda yakin ingin menghapus carousel ini?',
                            confirmText: 'Hapus',
                            icon: 'trash',
                            variant: 'warning'
                        })
                        .then(ok => {
                            if (!ok) return;
                            showLoading();
                            fetch(`{{ url('admin/public-content/carousel') }}/${id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                            .getAttribute('content'),
                                        'Content-Type': 'application/json',
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        queueToast('Carousel berhasil dihapus', 'success');
                                        location.reload();
                                    } else {
                                        showToast('Gagal menghapus carousel', 'error');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    showToast('Terjadi kesalahan saat menghapus carousel', 'error');
                                })
                                .finally(() => hideLoading());
                        });
                }
            });

            // Delete Service
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('delete-service') || e.target.closest('.delete-service')) {
                    const button = e.target.classList.contains('delete-service') ? e.target : e.target
                        .closest('.delete-service');
                    const id = button.dataset.id;

                    confirmAction({
                            title: 'Konfirmasi Hapus',
                            message: 'Apakah Anda yakin ingin menghapus layanan ini?',
                            confirmText: 'Hapus',
                            icon: 'trash',
                            variant: 'warning'
                        })
                        .then(ok => {
                            if (!ok) return;
                            showLoading();
                            fetch(`{{ url('admin/public-content/service') }}/${id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                            .getAttribute('content'),
                                        'Content-Type': 'application/json',
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        queueToast('Layanan berhasil dihapus', 'success');
                                        location.reload();
                                    } else {
                                        showToast('Gagal menghapus layanan', 'error');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    showToast('Terjadi kesalahan saat menghapus layanan', 'error');
                                })
                                .finally(() => hideLoading());
                        });
                }
            });

            // Form Submissions
            if (carouselForm) {
                carouselForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);

                    fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                carouselModal.hide();
                                queueToast('Carousel berhasil disimpan', 'success');
                                location.reload();
                            } else {
                                showToast('Gagal menyimpan carousel', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('Terjadi kesalahan saat menyimpan carousel', 'error');
                        });
                });
            }



            if (publicContentForm) {
                publicContentForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);

                    fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                publicContentModal.hide();
                                queueToast('Konten berhasil disimpan', 'success');
                                location.reload();
                            } else {
                                showToast('Gagal menyimpan konten publik', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('Terjadi kesalahan saat menyimpan konten publik', 'error');
                        });
                });
            }

            // Quick Actions
            window.previewWebsite = function() {
                window.open('/', '_blank');
            };

            window.exportContent = function() {
                showToast('Fitur export akan segera tersedia', 'info');
            };

            window.importContent = function() {
                showToast('Fitur import akan segera tersedia', 'info');
            };

            // Settings persistence (theme fixed to glass). Only close modal.
            window.saveSettings = function() {
                // Ensure theme remains glass
                try {
                    localStorage.setItem('adminTheme', 'glass');
                } catch (e) {}
                showToast('Pengaturan berhasil disimpan', 'success');
                const settingsModal = bootstrap.Modal.getInstance(document.getElementById('settingsModal'));
                if (settingsModal) settingsModal.hide();
            };

            // Apply admin accent color
            window.applyAdminAccent = function(color) {
                document.documentElement.style.setProperty('--accent-color', color);
                showToast('Warna aksen berhasil diubah', 'success');
            };

            window.toggleServiceStatus = function(id, status) {
                fetch(`{{ url('admin/public-content/toggle-status') }}`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            type: 'service',
                            id: id,
                            status: status
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
            };

            // Image preview for service modal
            const serviceImageInput = document.querySelector('#serviceModal [name="image"]');
            if (serviceImageInput) {
                serviceImageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    let previewContainer = document.querySelector('.service-image-preview');

                    // Remove existing preview
                    if (previewContainer) {
                        previewContainer.remove();
                    }

                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewContainer = document.createElement('div');
                            previewContainer.className = 'service-image-preview mt-2';
                            previewContainer.innerHTML = `
                                <img src="${e.target.result}" alt="Preview" style="max-width: 200px; max-height: 150px; object-fit: cover; border-radius: 8px; border: 2px solid #dee2e6;">
                                <div class="text-muted small mt-1">Preview: ${file.name}</div>
                            `;
                            serviceImageInput.parentNode.appendChild(previewContainer);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Public Content Functions
            window.editPublicContent = function(id) {
                fetch(`{{ url('admin/public-content') }}/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const content = data.data;
                            publicContentForm.querySelector('[name="section_name"]').value = content
                                .section_name || '';
                            publicContentForm.querySelector('[name="title"]').value = content.title || '';
                            publicContentForm.querySelector('[name="content"]').value = content.content ||
                                '';

                            // Set checkbox
                            const checkbox = publicContentForm.querySelector('[name="is_active"]');
                            checkbox.checked = content.is_active == 1;

                            publicContentForm.action = `{{ url('admin/public-content') }}/${id}`;

                            let methodInput = publicContentForm.querySelector('input[name="_method"]');
                            if (!methodInput) {
                                methodInput = document.createElement('input');
                                methodInput.type = 'hidden';
                                methodInput.name = '_method';
                                publicContentForm.appendChild(methodInput);
                            }
                            methodInput.value = 'PUT';

                            document.querySelector('#publicContentModal .modal-title').innerHTML =
                                '<i class="fas fa-edit me-2"></i>Edit Konten Halaman';
                            publicContentModal.show();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Gagal mengambil data konten', 'error');
                    });
            };

            window.toggleContentStatus = function(id, status) {
                showLoading();
                fetch(`{{ url('admin/public-content/toggle-status') }}`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            type: 'public-content',
                            id: id,
                            status: status
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            queueToast('Status konten diperbarui', 'success');
                            location.reload();
                        } else {
                            showToast('Gagal mengubah status konten', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Terjadi kesalahan saat mengubah status', 'error');
                    })
                    .finally(() => hideLoading());
            };

            window.deletePublicContent = function(id) {
                confirmAction({
                        title: 'Konfirmasi Hapus',
                        message: 'Apakah Anda yakin ingin menghapus konten ini?',
                        confirmText: 'Hapus',
                        icon: 'trash',
                        variant: 'warning'
                    })
                    .then(ok => {
                        if (!ok) return;
                        showLoading();
                        fetch(`{{ url('admin/public-content') }}/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content'),
                                    'Content-Type': 'application/json',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    queueToast('Konten berhasil dihapus', 'success');
                                    location.reload();
                                } else {
                                    showToast('Gagal menghapus konten', 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showToast('Terjadi kesalahan saat menghapus konten', 'error');
                            })
                            .finally(() => hideLoading());
                    });
            };
        });
    </script>

@push('styles')
    <style>
        /* Service Form Tabs */
        .nav-tabs-glass {
            border: none;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 4px;
        }

        .nav-tabs-glass .nav-link {
            border: none;
            background: transparent;
            color: #8892b0;
            border-radius: 6px;
            padding: 10px 16px;
            margin: 0 2px;
            font-weight: 500;
        }

        .nav-tabs-glass .nav-link:hover {
            background: transparent !important;
            color: #8892b0 !important;
            border: none !important;
        }

        .nav-tabs-glass .nav-link.active {
            background: #2d5a47;
            color: #ffffff;
        }

        .tab-content {
            padding: 20px 0;
        }

        .custom-editor {
            background: #1e2330 !important;
            border: 1px solid #2d3444 !important;
            border-radius: 8px;
            padding: 12px;
            min-height: 180px;
            overflow: auto;
            color: #ccd6f6;
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
        }

        .custom-editor:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px rgba(100, 255, 218, 0.2);
        }

        .custom-editor p {
            margin-bottom: 12px;
        }

        .custom-editor ul, .custom-editor ol {
            margin-left: 20px;
            margin-bottom: 12px;
        }

        .custom-editor li {
            margin-bottom: 6px;
        }

        /* Form improvements */
        .form-label {
            font-weight: 600;
            color: #ccd6f6;
            margin-bottom: 8px;
        }

        .form-control-glass {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid #2d3444;
            color: #ccd6f6;
            border-radius: 8px;
        }

        .form-control-glass:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px rgba(100, 255, 218, 0.2);
            color: #ccd6f6;
        }

        .form-control-glass::placeholder {
            color: #8892b0;
        }

        .input-group-text {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid #2d3444;
            color: #8892b0;
        }

        .alert-info {
            background: rgba(100, 255, 218, 0.1);
            border: 1px solid rgba(100, 255, 218, 0.3);
            color: #64ffda;
            border-radius: 8px;
        }

        .carousel-preview-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .carousel-preview-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 255, 136, 0.15);
        }

        .carousel-image-container {
            position: relative;
            height: 120px;
            overflow: hidden;
        }

        .carousel-preview-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .carousel-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.7));
            padding: 15px;
            color: white;
        }

        .carousel-actions {
            position: absolute;
            top: 10px;
            right: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .carousel-preview-card:hover .carousel-actions {
            opacity: 1;
        }

        .carousel-info {
            padding: 10px 15px;
        }

        .service-icon {
            transition: transform 0.3s ease;
        }

        .service-icon:hover {
            transform: scale(1.1);
        }

        .empty-state {
            padding: 2rem;
        }

        .color-option {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            border: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .color-option:hover,
        .color-option.active {
            border-color: rgba(255, 255, 255, 0.5);
            transform: scale(1.1);
        }

        /* Info Cards Styling */
        .info-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 12px;
            transition: all 0.3s ease;
        }

        .info-card:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(0, 255, 136, 0.3);
        }

        .info-icon {
            width: 35px;
            height: 35px;
            background: rgba(0, 255, 136, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .info-icon i {
            color: var(--accent-color);
            font-size: 16px;
        }
    </style>
@endpush
