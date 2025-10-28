@extends('admin.layouts.app')

@section('title', 'Struktur Organisasi')

@push('styles')
    <link href="{{ asset('css/public-content.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Modern Page Header -->
        @include('admin.partials.page-header', [
            'title' => 'Struktur Organisasi',
            'subtitle' => 'Kelola hierarki dan susunan organisasi Dinas Koperasi',
            'icon' => 'fas fa-sitemap',
            'breadcrumb' => 'Struktur',
            'primaryAction' => [
                'url' => 'javascript:void(0)',
                'text' => 'Tambah Posisi',
                'icon' => 'fas fa-plus',
                'onclick' => 'createStructure()',
            ],
            'quickStats' => [
                [
                    'value' => $totalPositions ?? 0,
                    'label' => 'Total Posisi',
                    'icon' => 'fas fa-users',
                    'color' => 'primary',
                ],
                [
                    'value' => $organizationLevels ?? 0,
                    'label' => 'Level Organisasi',
                    'icon' => 'fas fa-layer-group',
                    'color' => 'success',
                ],
                [
                    'value' => $totalStaff ?? 0,
                    'label' => 'Total Staff',
                    'icon' => 'fas fa-user-tie',
                    'color' => 'info',
                ],
            ],
        ])

        <!-- Content Section -->
        <div class="row g-4">
            <!-- Statistics Cards -->
            <div class="col-12">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="stat-card primary">
                            <div class="stat-content">
                                <div class="stat-value">{{ $totalPositions ?? 0 }}</div>
                                <div class="stat-label">Total Posisi</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card success">
                            <div class="stat-content">
                                <div class="stat-value">{{ $organizationLevels ?? 0 }}</div>
                                <div class="stat-label">Level Organisasi</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-layer-group"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card info">
                            <div class="stat-content">
                                <div class="stat-value">{{ $totalStaff ?? 0 }}</div>
                                <div class="stat-label">Total Staff</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Structure Table/List -->
            <div class="col-12">
                <div class="card glass-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list"></i> Daftar Struktur Organisasi
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($structures && $structures->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Posisi</th>
                                            <th>Nama</th>
                                            <th>Level</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($structures as $structure)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="position-badge level-{{ $structure->level }}">
                                                            L{{ $structure->level }}
                                                        </div>
                                                        <div class="ms-2">
                                                            <strong>{{ $structure->position }}</strong>
                                                            @if ($structure->parent)
                                                                <br><small class="text-muted">←
                                                                    {{ $structure->parent->position }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong>{{ $structure->name }}</strong>
                                                        @if ($structure->nip)
                                                            <br><small class="text-muted">NIP: {{ $structure->nip }}</small>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">Level {{ $structure->level }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge {{ $structure->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ $structure->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-outline-info"
                                                            onclick="viewStructure({{ $structure->id }})">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                            onclick="editStructure({{ $structure->id }})">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                            onclick="deleteStructure({{ $structure->id }})">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Custom Pagination -->
                            @include('admin.partials.custom-pagination', ['paginator' => $structures])
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-sitemap fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada struktur organisasi</h5>
                                <p class="text-muted">Klik tombol "Tambah Posisi" untuk mulai membuat struktur organisasi.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $quickStats = [
            [
                'value' => $totalPositions ?? 0,
                'label' => 'POSISI',
                'icon' => 'fas fa-users',
            ],
            [
                'value' => $organizationLevels ?? 0,
                'label' => 'LEVEL',
                'icon' => 'fas fa-layer-group',
            ],
            [
                'value' => $totalStaff ?? 0,
                'label' => 'STAFF',
                'icon' => 'fas fa-user-friends',
            ],
        ];
    @endphp

    <!-- Organization Chart -->
    <div class="glass-card mb-4">
        <h5 class="mb-4">
            <i class="fas fa-diagram-project"></i> Bagan Organisasi
        </h5>

        @if ($hierarchyTree->count() > 0)
            @foreach ($hierarchyTree as $topLevel)
                <!-- Level 1 - Kepala Dinas -->
                <div class="text-center mb-4">
                    <div class="org-card mx-auto"
                        style="max-width: 300px; background: linear-gradient(135deg, {{ $topLevel->color }}, {{ $topLevel->color }}dd); color: white; padding: 20px; border-radius: 16px; position: relative;">
                        <div
                            style="width: 80px; height: 80px; background: rgba(255,255,255,0.2); border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                            @if ($topLevel->photo)
                                <img src="{{ asset('storage/' . $topLevel->photo) }}" alt="{{ $topLevel->name }}"
                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                            @else
                                <i class="fas fa-user" style="font-size: 32px; color: rgba(255,255,255,0.8);"></i>
                            @endif
                        </div>
                        <div class="fw-bold" style="font-size: 18px;">{{ $topLevel->position }}</div>
                        <div style="font-size: 14px; opacity: 0.9;">{{ $topLevel->name }}</div>
                        @if ($topLevel->nip)
                            <div style="font-size: 12px; opacity: 0.8;">NIP: {{ $topLevel->nip }}</div>
                        @endif
                        <button class="btn btn-sm mt-2"
                            style="background: rgba(255,255,255,0.2); color: white; border: none; border-radius: 8px;"
                            onclick="editStructure({{ $topLevel->id }})">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>

                @if ($topLevel->children->count() > 0)
                    <!-- Connection Line -->
                    <div class="text-center mb-4">
                        <div style="width: 2px; height: 40px; background: var(--glass-border); margin: 0 auto;"></div>
                    </div>

                    <!-- Level 2 - Department Heads -->
                    <div class="row mb-4">
                        @foreach ($topLevel->children as $child)
                            <div class="col-md-4 mb-3">
                                <div class="org-card"
                                    style="background: linear-gradient(135deg, {{ $child->color }}, {{ $child->color }}dd); color: #000; padding: 15px; border-radius: 12px; text-align: center;">
                                    <div
                                        style="width: 60px; height: 60px; background: rgba(0,0,0,0.1); border-radius: 50%; margin: 0 auto 10px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                        @if ($child->photo)
                                            <img src="{{ asset('storage/' . $child->photo) }}" alt="{{ $child->name }}"
                                                style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                        @else
                                            <i class="fas fa-user" style="font-size: 24px; color: rgba(0,0,0,0.6);"></i>
                                        @endif
                                    </div>
                                    <div class="fw-bold">{{ $child->position }}</div>
                                    <div style="font-size: 14px;">{{ $child->name }}</div>
                                    @if ($child->nip)
                                        <div style="font-size: 12px; opacity: 0.8;">NIP: {{ $child->nip }}</div>
                                    @endif
                                    <button class="btn btn-sm mt-2"
                                        style="background: rgba(0,0,0,0.1); border: none; border-radius: 6px;"
                                        onclick="editStructure({{ $child->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Level 3 - Staff -->
                    @if ($topLevel->children->pluck('children')->flatten()->count() > 0)
                        <div class="row">
                            @foreach ($topLevel->children as $department)
                                @foreach ($department->children as $staff)
                                    <div class="col-md-2 mb-3">
                                        <div class="org-card"
                                            style="background: var(--glass-bg); backdrop-filter: blur(20px); border: 1px solid var(--glass-border); padding: 12px; border-radius: 10px; text-align: center;">
                                            <div
                                                style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 50%; margin: 0 auto 8px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                                @if ($staff->photo)
                                                    <img src="{{ asset('storage/' . $staff->photo) }}"
                                                        alt="{{ $staff->name }}"
                                                        style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                                @else
                                                    <i class="fas fa-user"
                                                        style="font-size: 16px; color: rgba(255,255,255,0.6);"></i>
                                                @endif
                                            </div>
                                            <div class="fw-bold" style="font-size: 14px;">{{ $staff->position }}</div>
                                            <div style="font-size: 12px; color: var(--text-secondary);">
                                                {{ $staff->name }}</div>
                                            <button class="btn btn-sm mt-1"
                                                style="background: rgba(255,255,255,0.1); color: var(--text-primary); border: none; border-radius: 4px; font-size: 10px;"
                                                onclick="editStructure({{ $staff->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    @endif
                @endif
            @endforeach
        @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <i class="fas fa-sitemap"
                    style="font-size: 64px; color: var(--text-secondary); opacity: 0.5; margin-bottom: 1rem;"></i>
                <h5 style="color: var(--text-secondary);">Belum Ada Struktur Organisasi</h5>
                <p style="color: var(--text-secondary); opacity: 0.8;">Tambahkan posisi pertama untuk memulai struktur
                    organisasi</p>
                <button class="btn btn-accent" onclick="createStructure()">
                    <i class="fas fa-plus me-2"></i>Tambah Posisi
                </button>
            </div>
        @endif
    </div>

    <!-- Position List -->
    <div class="glass-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5><i class="fas fa-list"></i> Daftar Posisi</h5>
            <button class="btn btn-accent" onclick="createStructure()">
                <i class="fas fa-plus me-2"></i>Tambah Posisi
            </button>
        </div>

        @if ($structures->count() > 0)
            <div class="table-responsive">
                <table class="table table-glass">
                    <thead>
                        <tr>
                            <th>Posisi</th>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Level</th>
                            <th>Parent</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($structures as $structure)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3"
                                            style="width: 40px; height: 40px; background: {{ $structure->color }}; border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                            @if ($structure->photo)
                                                <img src="{{ asset('storage/' . $structure->photo) }}"
                                                    alt="{{ $structure->name }}"
                                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                                            @else
                                                <i class="fas fa-user" style="color: white; font-size: 18px;"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $structure->position }}</div>
                                            @if ($structure->rank)
                                                <small class="text-muted">{{ $structure->rank }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $structure->name }}</div>
                                    @if ($structure->email)
                                        <small class="text-muted">{{ $structure->email }}</small>
                                    @endif
                                </td>
                                <td>{{ $structure->nip ?? '-' }}</td>
                                <td>
                                    <span class="badge-glass"
                                        style="background: rgba({{ $structure->level == 1 ? '255, 107, 107' : ($structure->level == 2 ? '255, 217, 61' : '136, 255, 207') }}, 0.2); color: var(--text-primary);">
                                        Level {{ $structure->level }}
                                    </span>
                                </td>
                                <td>
                                    @if ($structure->parent)
                                        <small>{{ $structure->parent->position }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge-glass"
                                        style="background: rgba({{ $structure->is_active ? '0, 255, 136' : '128, 128, 128' }}, 0.2); color: var(--text-primary);">
                                        {{ $structure->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-glass"
                                            onclick="editStructure({{ $structure->id }})" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-glass"
                                            onclick="viewStructure({{ $structure->id }})" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm"
                                            style="background: rgba(255, 107, 107, 0.2); color: var(--danger-color);"
                                            onclick="deleteStructure({{ $structure->id }})" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Custom Pagination -->
            @if ($structures->hasPages())
                @include('admin.partials.custom-pagination', ['paginator' => $structures])
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <i class="fas fa-users-slash"
                    style="font-size: 64px; color: var(--text-secondary); opacity: 0.5; margin-bottom: 1rem;"></i>
                <h5 style="color: var(--text-secondary);">Belum Ada Data Struktur</h5>
                <p style="color: var(--text-secondary); opacity: 0.8;">Tambahkan posisi pertama untuk memulai struktur
                    organisasi</p>
                <button class="btn btn-accent" onclick="createStructure()">
                    <i class="fas fa-plus me-2"></i>Tambah Posisi
                </button>
            </div>
        @endif
    </div>
    </div>

    <!-- Structure Modal -->
    <!-- Tombol Update/Batal sekarang ada di header sebelah kanan title -->
    <!-- Untuk menggunakan tombol di footer, tambahkan class 'modal-footer-buttons-enabled' ke modal-dialog -->
    <div class="modal fade" id="structureModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content"
                style="background: var(--glass-bg); backdrop-filter: blur(20px); border: 1px solid var(--glass-border); border-radius: 16px;">
                <form id="structureForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="structureId" name="id">
                    <input type="hidden" id="formMethod" name="_method" value="POST">

                    <div class="modal-header" style="border: none; padding-bottom: 0;">
                        <h5 class="modal-title" style="color: var(--text-primary); flex-grow: 1;">
                            <i class="fas fa-plus"></i> <span id="modalTitle">Tambah Posisi Baru</span>
                        </h5>
                        <div class="header-buttons" style="display: flex; gap: 10px; align-items: center;">
                            <button type="button" class="btn btn-glass btn-sm" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i> Batal
                            </button>
                            <button type="submit" form="structureForm" class="btn btn-primary-glass btn-sm"
                                id="submitBtnHeader">
                                <i class="fas fa-save"></i> <span id="submitTextHeader">Simpan</span>
                            </button>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            style="margin-left: 15px; padding: 8px;"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Posisi *</label>
                                <select class="form-control form-control-glass" id="position" name="position" required>
                                    <option value="">Pilih Nama Posisi</option>

                                    <!-- Jabatan Struktural -->
                                    <optgroup label="🏛️ Jabatan Struktural">
                                        <option value="Kepala Dinas Koperinsi, Usaha Kecil Menengah dan Perdagangan Kabupaten Katingan">Kepala Dinas Koperinsi, Usaha Kecil Menengah dan Perdagangan Kabupaten Katingan</option>
                                        <option value="Sekretaris Dinas Koperinsi, Usaha Kecil Menengah dan Perdagangan Kabupaten">Sekretaris Dinas Koperinsi, Usaha Kecil Menengah dan Perdagangan Kabupaten</option>
                                        <option value="Kepala Sub Bagian Umum dan Kepegawaian">Kepala Sub Bagian Umum dan Kepegawaian</option>
                                        <option value="Kepala Sub Bagian Keuangan dan Pelaporan">Kepala Sub Bagian Keuangan dan Pelaporan</option>
                                        <option value="Kepala Bidang Koperasi dan UMKM">Kepala Bidang Koperasi dan UMKM</option>
                                        <option value="Kepala Bidang Pengembangan Perdagangan">Kepala Bidang Pengembangan Perdagangan</option>
                                        <option value="Kepala Bidang Kemetrologian">Kepala Bidang Kemetrologian</option>
                                    </optgroup>

                                    <!-- Jabatan Fungsional Tertentu (JFT) -->
                                    <optgroup label="🎯 Jabatan Fungsional Tertentu (JFT)">
                                        <option value="JFT Pengawas Koperasi Ahli Muda">JFT Pengawas Koperasi Ahli Muda</option>
                                        <option value="JFT Pengawas Perdagangan Ahli Muda">JFT Pengawas Perdagangan Ahli Muda</option>
                                        <option value="JFT Penera Ahli Muda">JFT Penera Ahli Muda</option>
                                        <option value="JFT Pengawas Kemetrologian Ahli Muda">JFT Pengawas Kemetrologian Ahli Muda</option>
                                        <option value="Fungsional Perencana Ahli Muda (JPT Perencana)">Fungsional Perencana Ahli Muda (JPT Perencana)</option>
                                    </optgroup>

                                    <!-- Custom -->
                                    <optgroup label="✏️ Lainnya">
                                        <option value="custom">+ Tulis Manual</option>
                                    </optgroup>
                                </select>

                                <!-- Input manual untuk posisi custom -->
                                <input type="text" class="form-control form-control-glass mt-2" id="position_custom"
                                    name="position_custom" placeholder="Tulis nama posisi custom..."
                                    style="display: none;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Level *</label>
                                <select class="form-control form-control-glass" id="level" name="level" required>
                                    <option value="">Pilih Level</option>
                                    <option value="1">Level 1 - Kepala/Pimpinan</option>
                                    <option value="2">Level 2 - Kabid/Sekretaris</option>
                                    <option value="3">Level 3 - Staff/Pelaksana</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap *</label>
                                <input type="text" class="form-control form-control-glass" id="name"
                                    name="name" placeholder="Nama pegawai" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NIP</label>
                                <input type="text" class="form-control form-control-glass" id="nip"
                                    name="nip" placeholder="NIP pegawai">
                            </div>
                        </div>



                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pangkat/Golongan</label>
                                <select class="form-control form-control-glass" id="rank" name="rank">
                                    <option value="">Pilih Pangkat/Golongan</option>
                                    <optgroup label="Golongan IV (Pembina)">
                                        <option value="Pembina Utama - IV/e">Pembina Utama - IV/e</option>
                                        <option value="Pembina Utama Madya - IV/d">Pembina Utama Madya - IV/d</option>
                                        <option value="Pembina Utama Muda - IV/c">Pembina Utama Muda - IV/c</option>
                                        <option value="Pembina Tingkat I - IV/b">Pembina Tingkat I - IV/b</option>
                                        <option value="Pembina - IV/a">Pembina - IV/a</option>
                                    </optgroup>
                                    <optgroup label="Golongan III (Penata)">
                                        <option value="Penata Tingkat I - III/d">Penata Tingkat I - III/d</option>
                                        <option value="Penata - III/c">Penata - III/c</option>
                                        <option value="Penata Muda Tingkat I - III/b">Penata Muda Tingkat I - III/b
                                        </option>
                                        <option value="Penata Muda - III/a">Penata Muda - III/a</option>
                                    </optgroup>
                                    <optgroup label="Golongan II (Pengatur)">
                                        <option value="Pengatur Tingkat I - II/d">Pengatur Tingkat I - II/d</option>
                                        <option value="Pengatur - II/c">Pengatur - II/c</option>
                                        <option value="Pengatur Muda Tingkat I - II/b">Pengatur Muda Tingkat I - II/b
                                        </option>
                                        <option value="Pengatur Muda - II/a">Pengatur Muda - II/a</option>
                                    </optgroup>
                                    <optgroup label="Golongan I (Juru)">
                                        <option value="Juru Tingkat I - I/d">Juru Tingkat I - I/d</option>
                                        <option value="Juru - I/c">Juru - I/c</option>
                                        <option value="Juru Muda Tingkat I - I/b">Juru Muda Tingkat I - I/b</option>
                                        <option value="Juru Muda - I/a">Juru Muda - I/a</option>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Parent/Atasan</label>
                                <select class="form-control form-control-glass" id="parent_id" name="parent_id">
                                    <option value="">Tidak Ada (Level Tertinggi)</option>
                                    @foreach ($structures->where('level', '<', 3) as $parent)
                                        <option value="{{ $parent->id }}">{{ $parent->position }} -
                                            {{ $parent->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Foto Profil</label>
                                <input type="file" class="form-control form-control-glass" id="photo"
                                    name="photo" accept="image/*">
                                <small class="text-muted">Format: JPG, PNG, JPEG. Max 2MB</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-control form-control-glass" id="is_active" name="is_active">
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>

                        <!-- PLT Section -->
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="form-check form-switch" style="padding-left: 2.5em;">
                                    <input class="form-check-input" type="checkbox" id="is_plt" name="is_plt" value="1"
                                        style="width: 3em; height: 1.5em; cursor: pointer;">
                                    <label class="form-check-label" for="is_plt" style="margin-left: 0.5em; cursor: pointer; font-weight: 500;">
                                        <i class="fas fa-user-clock"></i> PLT (Pelaksana Tugas)
                                    </label>
                                    <small class="text-muted d-block" style="margin-left: 3.5em;">
                                        Aktifkan jika jabatan ini sedang dijabat PLT
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- PLT Form Fields (hidden by default) -->
                        <div id="pltFields" style="display: none; border-top: 1px solid var(--glass-border); padding-top: 20px; margin-top: 10px;">
                            <h6 class="mb-3" style="color: var(--text-primary);">
                                <i class="fas fa-user-tie"></i> Data PLT (Pelaksana Tugas)
                            </h6>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="plt_source" id="plt_from_existing" value="existing" checked>
                                        <label class="form-check-label" for="plt_from_existing">
                                            Ambil dari Jabatan yang Ada
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="plt_source" id="plt_manual" value="manual">
                                        <label class="form-check-label" for="plt_manual">
                                            Input Manual (dari luar/jabatan lain)
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Option 1: Pilih dari struktur yang ada -->
                            <div id="pltFromExisting" class="plt-option">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Pilih Pejabat yang Menjabat PLT</label>
                                        <select class="form-control form-control-glass" id="plt_from_structure_id" name="plt_from_structure_id">
                                            <option value="">-- Pilih Pejabat --</option>
                                            @foreach ($structures->where('is_active', true) as $struct)
                                                <option value="{{ $struct->id }}">
                                                    {{ $struct->position }} - {{ $struct->name }}
                                                    @if($struct->nip) (NIP: {{ $struct->nip }})@endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Pilih pejabat yang akan menjabat sebagai PLT untuk posisi ini</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Option 2: Input manual -->
                            <div id="pltManualInput" class="plt-option" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama PLT *</label>
                                        <input type="text" class="form-control form-control-glass" id="plt_name" name="plt_name" placeholder="Nama lengkap PLT">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">NIP PLT</label>
                                        <input type="text" class="form-control form-control-glass" id="plt_nip" name="plt_nip" placeholder="NIP PLT">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Pangkat/Golongan PLT</label>
                                        <input type="text" class="form-control form-control-glass" id="plt_rank" name="plt_rank" placeholder="Contoh: Pembina - IV/a">
                                    </div>
                                </div>
                            </div>

                            <!-- Common PLT Fields -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Mulai PLT</label>
                                    <input type="date" class="form-control form-control-glass" id="plt_start_date" name="plt_start_date">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Selesai PLT (Estimasi)</label>
                                    <input type="date" class="form-control form-control-glass" id="plt_end_date" name="plt_end_date">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Keterangan PLT</label>
                                    <textarea class="form-control form-control-glass" id="plt_notes" name="plt_notes" rows="2" placeholder="Keterangan atau catatan tentang PLT ini..."></textarea>
                                    <small class="text-muted">Contoh: SK Nomor xxx, alasan PLT, dll</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer footer-alternative"
                        style="border: none; background: rgba(255, 255, 255, 0.05); margin: 0; padding: 15px 20px; border-radius: 0 0 16px 16px; display: none;">
                        <!-- Tombol alternatif di footer (hidden by default, dapat diaktifkan dengan mengubah useHeaderButtons = false) -->
                        <div style="display: flex; gap: 10px; justify-content: flex-end; width: 100%;">
                            <button type="button" class="btn btn-glass" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-primary-glass" id="submitBtn">
                                <i class="fas fa-save"></i> <span id="submitText">Simpan</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Structure Modal -->
    <div class="modal fade" id="viewStructureModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content"
                style="background: var(--glass-bg); backdrop-filter: blur(20px); border: 1px solid var(--glass-border); border-radius: 16px;">
                <div class="modal-header" style="border: none;">
                    <h5 class="modal-title" style="color: var(--text-primary);">
                        <i class="fas fa-eye"></i> Detail Posisi
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="structureDetail">
                    <!-- Detail content will be loaded here -->
                </div>
                <div class="modal-footer" style="border: none;">
                    <button type="button" class="btn btn-glass" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary-glass" onclick="editFromView()">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let currentStructureId = null;

            // Create Structure
            function createStructure() {
                $('#structureForm')[0].reset();
                $('#structureId').val('');
                $('#formMethod').val('POST');
                $('#modalTitle').text('Tambah Posisi Baru');
                $('#submitText, #submitTextHeader').text('Simpan');
                $('#structureModal .modal-title i').removeClass().addClass('fas fa-plus');

                // Reset position dropdown dan custom input
                $('#position').val('');
                $('#position_custom').hide().removeAttr('required').val('');

                // Reset PLT fields
                $('#is_plt').prop('checked', false);
                $('#pltFields').hide();
                $('#plt_from_existing').prop('checked', true);
                $('#pltFromExisting').show();
                $('#pltManualInput').hide();

                $('#structureModal').modal('show');
            }

            // Edit Structure
            function editStructure(id) {
                // Get structure data via AJAX
                fetch(`/admin/structure/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const structure = data.data;

                            $('#structureId').val(structure.id);
                            $('#formMethod').val('PUT');

                            // Handle position dropdown - check if position exists in dropdown options
                            const positionDropdown = $('#position');
                            const customInput = $('#position_custom');
                            let positionFound = false;

                            // Check if the current position exists in dropdown options
                            positionDropdown.find('option').each(function() {
                                if ($(this).val() === structure.position) {
                                    positionFound = true;
                                    return false; // break the loop
                                }
                            });

                            if (positionFound) {
                                // Position exists in dropdown
                                positionDropdown.val(structure.position);
                                customInput.hide().removeAttr('required').val('');
                            } else {
                                // Position doesn't exist, use custom input
                                positionDropdown.val('custom');
                                customInput.show().attr('required', true).val(structure.position);
                            }

                            $('#level').val(structure.level);
                            $('#name').val(structure.name);
                            $('#nip').val(structure.nip);
                            $('#rank').val(structure.rank);
                            $('#parent_id').val(structure.parent_id);
                            $('#is_active').val(structure.is_active ? 1 : 0);

                            // Handle PLT data
                            if (structure.is_plt) {
                                $('#is_plt').prop('checked', true);
                                $('#pltFields').show();

                                if (structure.plt_from_structure_id) {
                                    // PLT from existing structure
                                    $('#plt_from_existing').prop('checked', true);
                                    $('#pltFromExisting').show();
                                    $('#pltManualInput').hide();
                                    $('#plt_from_structure_id').val(structure.plt_from_structure_id);
                                } else if (structure.plt_name) {
                                    // PLT manual input
                                    $('#plt_manual').prop('checked', true);
                                    $('#pltFromExisting').hide();
                                    $('#pltManualInput').show();
                                    $('#plt_name').val(structure.plt_name);
                                    $('#plt_nip').val(structure.plt_nip);
                                    $('#plt_rank').val(structure.plt_rank);
                                }

                                // Common PLT fields
                                $('#plt_start_date').val(structure.plt_start_date);
                                $('#plt_end_date').val(structure.plt_end_date);
                                $('#plt_notes').val(structure.plt_notes);
                            } else {
                                $('#is_plt').prop('checked', false);
                                $('#pltFields').hide();
                            }

                            $('#modalTitle').text('Edit Posisi');
                            $('#submitText, #submitTextHeader').text('Update');
                            $('#structureModal .modal-title i').removeClass().addClass('fas fa-edit');
                            $('#structureModal').modal('show');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Gagal memuat data struktur', 'error');
                    });
            }

            // View Structure
            function viewStructure(id) {
                fetch(`/admin/structure/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const structure = data.data;
                            currentStructureId = id;

                            const detailHtml = `
                    <div class="row">
                        <div class="col-md-12 text-center mb-4">
                            <div style="width: 100px; height: 100px; background: #007bff; border-radius: 50%; margin: 0 auto; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                ${structure.photo ?
                                    `<img src="/storage/${structure.photo}" alt="${structure.name}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">` :
                                    `<i class="fas fa-user" style="color: white; font-size: 32px;"></i>`
                                }
                            </div>
                            <h4 class="mt-3">${structure.position}</h4>
                            <p class="text-muted">${structure.name}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><strong>NIP:</strong></label>
                                <p>${structure.nip || '-'}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><strong>Level:</strong></label>
                                <p>Level ${structure.level}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><strong>Pangkat:</strong></label>
                                <p>${structure.rank || '-'}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><strong>Status:</strong></label>
                                <p><span class="badge ${structure.is_active ? 'bg-success' : 'bg-secondary'}">${structure.is_active ? 'Aktif' : 'Tidak Aktif'}</span></p>
                            </div>
                        </div>
                    </div>
                    ${structure.description ? `
                                                                    <div class="mb-3">
                                                                        <label class="form-label"><strong>Deskripsi:</strong></label>
                                                                        <p>${structure.description}</p>
                                                                    </div>
                                                                ` : ''}
                `;

                            $('#structureDetail').html(detailHtml);
                            $('#viewStructureModal').modal('show');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Gagal memuat detail struktur', 'error');
                    });
            }

            // Edit from view modal
            function editFromView() {
                $('#viewStructureModal').modal('hide');
                setTimeout(() => {
                    editStructure(currentStructureId);
                }, 300);
            }

            // Delete Structure
            function deleteStructure(id) {
                confirmAction({
                        title: 'Konfirmasi Hapus',
                        message: 'Apakah Anda yakin ingin menghapus posisi ini?',
                        confirmText: 'Hapus',
                        icon: 'trash',
                        variant: 'warning'
                    })
                    .then(ok => {
                        if (!ok) return;
                        showLoading();
                        fetch(`/admin/structure/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    queueToast('Posisi berhasil dihapus', 'success');
                                    location.reload();
                                } else {
                                    showToast(data.message || 'Gagal menghapus posisi', 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showToast('Terjadi kesalahan saat menghapus posisi', 'error');
                            })
                            .finally(() => hideLoading());
                    });
            } // Submit handled below with jQuery AJAX (single handler)

            // Enhanced form handling
            $(document).ready(function() {
                // Configuration: Set button position
                // true = buttons in header, false = buttons in footer
                const useHeaderButtons = true;

                if (!useHeaderButtons) {
                    $('.modal-dialog').addClass('modal-footer-buttons-enabled');
                }

                // Prevent modal from closing when clicking on form elements
                $('#structureModal .modal-body').on('click', function(e) {
                    e.stopPropagation();
                });

                // Specifically prevent textarea and other form elements from closing modal
                $('#structureModal').on('click', 'textarea, input, select, label', function(e) {
                    e.stopPropagation();
                });

                // Ensure all form controls are interactive
                $('#structureModal').on('shown.bs.modal', function() {
                    // Focus pada field pertama
                    $('#position').focus();

                    // Pastikan semua textarea responsive
                    $('textarea.form-control-glass').off('focus blur').on('focus', function() {
                        $(this).addClass('focused');
                        // Prevent any interference with focus
                        $(this).off('click.modal');
                    }).on('blur', function() {
                        $(this).removeClass('focused');
                    });

                    // Auto-resize textarea
                    $('textarea.form-control-glass').off('input').on('input', function() {
                        this.style.height = 'auto';
                        this.style.height = (this.scrollHeight) + 'px';
                    });

                    // Ensure textarea is clickable and focusable
                    $('#description').off('click').on('click', function(e) {
                        e.stopPropagation();
                        $(this).focus();
                    });

                    // File input preview
                    $('#photo').off('change').on('change', function() {
                        const file = this.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                // Add preview if needed
                                console.log('Photo selected:', file.name);
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                });

                // Level change handler untuk parent dropdown
                $('#level').off('change').on('change', function() {
                    const level = parseInt($(this).val());
                    const parentDropdown = $('#parent_id');

                    // Clear current options
                    parentDropdown.find('option:not(:first)').remove();

                    if (level > 1) {
                        // Load parent options based on level
                        parentDropdown.prop('disabled', false);
                        // You can add AJAX call here to load appropriate parents
                    } else {
                        parentDropdown.prop('disabled', true);
                    }
                });

                // Position dropdown handler untuk custom input
                $('#position').off('change').on('change', function() {
                    const selectedValue = $(this).val();
                    const customInput = $('#position_custom');

                    if (selectedValue === 'custom') {
                        customInput.show().attr('required', true);
                        customInput.focus();
                        // Add placeholder animation
                        setTimeout(() => {
                            customInput.attr('placeholder', 'Tulis nama posisi custom... ✏️');
                        }, 100);
                    } else {
                        customInput.hide().removeAttr('required').val('');
                        customInput.attr('placeholder', 'Tulis nama posisi custom...');
                    }
                });

                // PLT Checkbox Toggle
                $('#is_plt').off('change').on('change', function() {
                    const isChecked = $(this).is(':checked');
                    if (isChecked) {
                        $('#pltFields').slideDown(300);
                    } else {
                        $('#pltFields').slideUp(300);
                        // Reset PLT fields
                        $('#plt_from_structure_id').val('');
                        $('#plt_name').val('');
                        $('#plt_nip').val('');
                        $('#plt_rank').val('');
                        $('#plt_start_date').val('');
                        $('#plt_end_date').val('');
                        $('#plt_notes').val('');
                    }
                });

                // PLT Source Radio Toggle
                $('input[name="plt_source"]').off('change').on('change', function() {
                    const source = $(this).val();
                    if (source === 'existing') {
                        $('#pltFromExisting').show();
                        $('#pltManualInput').hide();
                        // Clear manual inputs
                        $('#plt_name').val('').removeAttr('required');
                        $('#plt_nip').val('');
                        $('#plt_rank').val('');
                    } else {
                        $('#pltFromExisting').hide();
                        $('#pltManualInput').show();
                        // Clear existing selection
                        $('#plt_from_structure_id').val('');
                        // Set nama PLT as required for manual input
                        $('#plt_name').attr('required', true);
                    }
                });

                // Validation for custom input
                $('#position_custom').on('input', function() {
                    const value = $(this).val().trim();
                    if (value.length > 0) {
                        $(this).removeClass('is-invalid').addClass('is-valid');
                    } else if ($('#position').val() === 'custom') {
                        $(this).removeClass('is-valid').addClass('is-invalid');
                    }
                });
            });

            // Custom dropdown functionality removed - using standard selects
            // Form validation and submission is handled by standard form controls

            // Initialize when document is ready
            $(document).ready(function() {
                console.log('Form controls initialized');

                // Form submission dengan debug logging
                $('#structureForm').on('submit', function(e) {
                    e.preventDefault();
                    console.log('=== FORM SUBMISSION STARTED ===');

                    const formData = new FormData(this);
                    const structureId = $('#structureId').val();
                    const method = $('#formMethod').val();

                    // Log semua data yang akan dikirim
                    console.log('Form data being submitted:');
                    for (let [key, value] of formData.entries()) {
                        console.log(`${key}:`, value);
                    }

                    // Tentukan URL berdasarkan mode (create/update)
                    let url = '/admin/structure';
                    if (structureId && method === 'PUT') {
                        url = `/admin/structure/${structureId}`;
                        // Pastikan _method bernilai PUT
                        if (!formData.has('_method')) {
                            formData.append('_method', 'PUT');
                        }
                    }

                    // Submit form
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function() {
                            console.log('Sending request...');
                            $('#submitBtn, #submitBtnHeader').prop('disabled', true).html(
                                '<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
                        },
                        success: function(response) {
                            console.log('✅ Upload SUCCESS:', response);
                            queueToast('Data berhasil disimpan!', 'success');
                            $('#structureModal').modal('hide');
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            console.error('❌ Upload FAILED:', {
                                status: xhr.status,
                                statusText: xhr.statusText,
                                responseText: xhr.responseText,
                                error: error
                            });
                            showToast('Terjadi kesalahan: ' + (xhr.responseJSON?.message || error), 'error');
                        },
                        complete: function() {
                            $('#submitBtn, #submitBtnHeader').prop('disabled', false).html(
                                '<i class="fas fa-save"></i> <span id="submitText">Simpan</span>'
                            );
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
