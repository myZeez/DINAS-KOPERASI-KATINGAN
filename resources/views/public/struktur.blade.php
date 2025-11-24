@extends('public.layouts.app')

@section('title', 'Struktur Organisasi')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <div class="hero-content">
                        <div class="hero-badge mb-3">
                            <i class="fas fa-sitemap me-2"></i>
                            Bagan Organisasi
                        </div>
                        <h1 class="hero-title mb-4">Struktur Organisasi</h1>
                        <p class="hero-subtitle mb-4">
                            Mengenal lebih dekat struktur kepemimpinan dan tim yang menggerakkan
                            {{ $profile->name ?? 'Dinas Koperasi' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Organization Chart Section -->
    <section class="section-modern scroll-reveal">
        <div class="container">
            @if ($structures->count() > 0)
                @php
                    // Group structures by level (hierarki sebenarnya dari database)
                    $level1 = $structures->where('level', 1); // Kepala Dinas
                    $level2 = $structures->where('level', 2); // Sekretaris, Kabid, JPT Fungsional
                    $level3 = $structures->where('level', 3); // Kasubbag dan JFT per bidang
                @endphp

                <div class="org-chart">
                    <!-- Level 1: Kepala Dinas -->
                    @if ($level1->count() > 0)
                        <div class="org-level level-1">
                            @foreach ($level1 as $kepala)
                                <div class="org-card kepala">
                                    <div class="card-photo">
                                        @if ($kepala->photo)
                                            <img src="{{ asset('storage/' . $kepala->photo) }}" alt="{{ $kepala->name }}">
                                        @else
                                            <div class="photo-placeholder">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-info">
                                        <h4>
                                            @php
                                                // Persingkat nama jabatan untuk tampilan card
                                                $shortPosition = $kepala->position;
                                                if (stripos($shortPosition, 'KEPALA DINAS') !== false) {
                                                    $shortPosition = 'KEPALA DINAS';
                                                } elseif (stripos($shortPosition, 'SEKRETARIS') !== false) {
                                                    $shortPosition = 'SEKRETARIS DINAS';
                                                } elseif (stripos($shortPosition, 'KEPALA BIDANG') !== false || stripos($shortPosition, 'KABID') !== false) {
                                                    // Ambil nama bidang setelah "KEPALA BIDANG" atau "KABID"
                                                    if (stripos($shortPosition, 'KOPERASI') !== false) {
                                                        $shortPosition = 'KEPALA BIDANG KOPERASI';
                                                    } elseif (stripos($shortPosition, 'UMKM') !== false || stripos($shortPosition, 'UKM') !== false) {
                                                        $shortPosition = 'KEPALA BIDANG UMKM';
                                                    } elseif (stripos($shortPosition, 'PERDAGANGAN') !== false) {
                                                        $shortPosition = 'KEPALA BIDANG PERDAGANGAN';
                                                    } else {
                                                        $shortPosition = 'KEPALA BIDANG';
                                                    }
                                                } elseif (stripos($shortPosition, 'KASUBBAG') !== false || stripos($shortPosition, 'KEPALA SUB BAGIAN') !== false) {
                                                    $shortPosition = preg_replace('/KEPALA SUB BAGIAN|KASUBBAG/i', 'KASUBBAG', $shortPosition);
                                                    $shortPosition = preg_replace('/DINAS.*$/i', '', $shortPosition);
                                                    $shortPosition = trim($shortPosition);
                                                } elseif (stripos($shortPosition, 'JABATAN FUNGSIONAL') !== false) {
                                                    $shortPosition = 'JABATAN FUNGSIONAL TERTENTU';
                                                }
                                            @endphp
                                            {{ $shortPosition }}
                                        </h4>
                                        <h5>{{ $kepala->name }}</h5>
                                        @if ($kepala->nip)
                                            <p class="nip">NIP: {{ $kepala->nip }}</p>
                                        @endif
                                        @if ($kepala->is_plt)
                                            <div class="plt-badge">
                                                <i class="fas fa-user-clock"></i> PLT
                                            </div>
                                            @if ($kepala->pltFromStructure)
                                                <p class="plt-info">
                                                    <small>Dijabat oleh: <strong>{{ $kepala->pltFromStructure->name }}</strong></small>
                                                    <small class="d-block">{{ $kepala->pltFromStructure->position }}</small>
                                                </p>
                                            @elseif ($kepala->plt_name)
                                                <p class="plt-info">
                                                    <small>Dijabat oleh: <strong>{{ $kepala->plt_name }}</strong></small>
                                                    @if($kepala->plt_nip)
                                                        <small class="d-block">NIP: {{ $kepala->plt_nip }}</small>
                                                    @endif
                                                </p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Connection Line -->
                        <div class="org-connector vertical"></div>
                    @endif

                    <!-- Level 2a: Sekretaris Dinas -->
                    @php
                        $sekretaris = $level2->filter(function($item) {
                            return stripos($item->position, 'SEKRETARIS') !== false;
                        });
                        $otherLevel2 = $level2->filter(function($item) {
                            return stripos($item->position, 'SEKRETARIS') === false;
                        });
                    @endphp

                    @if ($sekretaris->count() > 0)
                        <div class="org-level level-2a">
                            <div class="sekretaris-container">
                                @foreach ($sekretaris as $sek)
                                    <div class="org-card level2 sekretaris">
                                        <div class="card-photo">
                                            @if ($sek->photo)
                                                <img src="{{ asset('storage/' . $sek->photo) }}" alt="{{ $sek->name }}">
                                            @else
                                                <div class="photo-placeholder">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-info">
                                            <h4>SEKRETARIS DINAS</h4>
                                            <h5>{{ $sek->name }}</h5>
                                            @if ($sek->nip)
                                                <p class="nip">NIP: {{ $sek->nip }}</p>
                                            @endif
                                            @if ($sek->is_plt)
                                                <div class="plt-badge">
                                                    <i class="fas fa-user-clock"></i> PLT
                                                </div>
                                                @if ($sek->pltFromStructure)
                                                    <p class="plt-info">
                                                        <small>Dijabat oleh: <strong>{{ $sek->pltFromStructure->name }}</strong></small>
                                                        <small class="d-block">{{ $sek->pltFromStructure->position }}</small>
                                                    </p>
                                                @elseif ($sek->plt_name)
                                                    <p class="plt-info">
                                                        <small>Dijabat oleh: <strong>{{ $sek->plt_name }}</strong></small>
                                                        @if($sek->plt_nip)
                                                            <small class="d-block">NIP: {{ $sek->plt_nip }}</small>
                                                        @endif
                                                    </p>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- Connection Line -->
                        <div class="org-connector vertical"></div>
                    @endif

                    <!-- Level 2b: Kepala Bidang, JPT Fungsional -->
                    @if ($otherLevel2->count() > 0)
                        <div class="org-level level-2b">
                            <div class="level2-container">
                                @foreach ($otherLevel2 as $index => $staff2)
                                    <div class="org-card level2 level2-{{ $index + 1 }}">
                                        <div class="card-photo">
                                            @if ($staff2->photo)
                                                <img src="{{ asset('storage/' . $staff2->photo) }}" alt="{{ $staff2->name }}">
                                            @else
                                                <div class="photo-placeholder">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-info">
                                            <h4>
                                                @php
                                                    // Persingkat nama jabatan untuk tampilan card
                                                    $shortPosition = $staff2->position;
                                                    if (stripos($shortPosition, 'KEPALA DINAS') !== false) {
                                                        $shortPosition = 'KEPALA DINAS';
                                                    } elseif (stripos($shortPosition, 'SEKRETARIS') !== false) {
                                                        $shortPosition = 'SEKRETARIS DINAS';
                                                    } elseif (stripos($shortPosition, 'KEPALA BIDANG') !== false || stripos($shortPosition, 'KABID') !== false) {
                                                        // Ambil nama bidang setelah "KEPALA BIDANG" atau "KABID"
                                                        if (stripos($shortPosition, 'KOPERASI') !== false) {
                                                            $shortPosition = 'KEPALA BIDANG KOPERASI';
                                                        } elseif (stripos($shortPosition, 'UMKM') !== false || stripos($shortPosition, 'UKM') !== false) {
                                                            $shortPosition = 'KEPALA BIDANG UMKM';
                                                        } elseif (stripos($shortPosition, 'PERDAGANGAN') !== false) {
                                                            $shortPosition = 'KEPALA BIDANG PERDAGANGAN';
                                                        } else {
                                                            $shortPosition = 'KEPALA BIDANG';
                                                        }
                                                    } elseif (stripos($shortPosition, 'KASUBBAG') !== false || stripos($shortPosition, 'KEPALA SUB BAGIAN') !== false) {
                                                        $shortPosition = preg_replace('/KEPALA SUB BAGIAN|KASUBBAG/i', 'KASUBBAG', $shortPosition);
                                                        $shortPosition = preg_replace('/DINAS.*$/i', '', $shortPosition);
                                                        $shortPosition = trim($shortPosition);
                                                    } elseif (stripos($shortPosition, 'JABATAN FUNGSIONAL') !== false) {
                                                        $shortPosition = 'JABATAN FUNGSIONAL TERTENTU';
                                                    }
                                                @endphp
                                                {{ $shortPosition }}
                                            </h4>
                                            <h5>{{ $staff2->name }}</h5>
                                            @if ($staff2->nip)
                                                <p class="nip">NIP: {{ $staff2->nip }}</p>
                                            @endif
                                            @if ($staff2->is_plt)
                                                <div class="plt-badge">
                                                    <i class="fas fa-user-clock"></i> PLT
                                                </div>
                                                @if ($staff2->pltFromStructure)
                                                    <p class="plt-info">
                                                        <small>Dijabat oleh: <strong>{{ $staff2->pltFromStructure->name }}</strong></small>
                                                        <small class="d-block">{{ $staff2->pltFromStructure->position }}</small>
                                                    </p>
                                                @elseif ($staff2->plt_name)
                                                    <p class="plt-info">
                                                        <small>Dijabat oleh: <strong>{{ $staff2->plt_name }}</strong></small>
                                                        @if($staff2->plt_nip)
                                                            <small class="d-block">NIP: {{ $staff2->plt_nip }}</small>
                                                        @endif
                                                    </p>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- Connection Line -->
                        <div class="org-connector vertical"></div>
                    @endif

                    <!-- Level 3: Kasubbag dan JFT -->
                    @if ($level3->count() > 0)
                        <div class="org-level level-3">
                            <div class="level3-container">
                                @foreach ($level3 as $index => $staff3)
                                    <div class="org-card level3 level3-{{ $index + 1 }}">
                                        <div class="card-photo">
                                            @if ($staff3->photo)
                                                <img src="{{ asset('storage/' . $staff3->photo) }}" alt="{{ $staff3->name }}">
                                            @else
                                                <div class="photo-placeholder">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-info">
                                            <h4>
                                                @php
                                                    // Persingkat nama jabatan untuk tampilan card
                                                    $shortPosition = $staff3->position;
                                                    if (stripos($shortPosition, 'KEPALA DINAS') !== false) {
                                                        $shortPosition = 'KEPALA DINAS';
                                                    } elseif (stripos($shortPosition, 'SEKRETARIS') !== false) {
                                                        $shortPosition = 'SEKRETARIS DINAS';
                                                    } elseif (stripos($shortPosition, 'KEPALA BIDANG') !== false || stripos($shortPosition, 'KABID') !== false) {
                                                        // Ambil nama bidang setelah "KEPALA BIDANG" atau "KABID"
                                                        if (stripos($shortPosition, 'KOPERASI') !== false) {
                                                            $shortPosition = 'KEPALA BIDANG KOPERASI';
                                                        } elseif (stripos($shortPosition, 'UMKM') !== false || stripos($shortPosition, 'UKM') !== false) {
                                                            $shortPosition = 'KEPALA BIDANG UMKM';
                                                        } elseif (stripos($shortPosition, 'PERDAGANGAN') !== false) {
                                                            $shortPosition = 'KEPALA BIDANG PERDAGANGAN';
                                                        } else {
                                                            $shortPosition = 'KEPALA BIDANG';
                                                        }
                                                    } elseif (stripos($shortPosition, 'KASUBBAG') !== false || stripos($shortPosition, 'KEPALA SUB BAGIAN') !== false) {
                                                        $shortPosition = preg_replace('/KEPALA SUB BAGIAN|KASUBBAG/i', 'KASUBBAG', $shortPosition);
                                                        $shortPosition = preg_replace('/DINAS.*$/i', '', $shortPosition);
                                                        $shortPosition = trim($shortPosition);
                                                    } elseif (stripos($shortPosition, 'JABATAN FUNGSIONAL') !== false) {
                                                        $shortPosition = 'JABATAN FUNGSIONAL TERTENTU';
                                                    }
                                                @endphp
                                                {{ $shortPosition }}
                                            </h4>
                                            <h5>{{ $staff3->name }}</h5>
                                            @if ($staff3->nip)
                                                <p class="nip">NIP: {{ $staff3->nip }}</p>
                                            @endif
                                            @if ($staff3->is_plt)
                                                <div class="plt-badge">
                                                    <i class="fas fa-user-clock"></i> PLT
                                                </div>
                                                @if ($staff3->pltFromStructure)
                                                    <p class="plt-info">
                                                        <small>Dijabat oleh: <strong>{{ $staff3->pltFromStructure->name }}</strong></small>
                                                        <small class="d-block">{{ $staff3->pltFromStructure->position }}</small>
                                                    </p>
                                                @elseif ($staff3->plt_name)
                                                    <p class="plt-info">
                                                        <small>Dijabat oleh: <strong>{{ $staff3->plt_name }}</strong></small>
                                                        @if($staff3->plt_nip)
                                                            <small class="d-block">NIP: {{ $staff3->plt_nip }}</small>
                                                        @endif
                                                    </p>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Data Table Section -->
                <div class="structure-table-section mt-5">
                    <div class="section-header text-center mb-4">
                        <h3 class="section-title">Daftar Lengkap Struktur Organisasi</h3>
                        <p class="section-subtitle">Data lengkap pegawai dan jabatan struktural</p>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-gradient">
                                <tr>
                                    <th width="5%" class="text-center">No</th>
                                    <th width="30%">Jabatan</th>
                                    <th width="25%">Nama</th>
                                    <th width="20%">NIP</th>
                                    <th width="10%" class="text-center">Level</th>
                                    <th width="10%" class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($structures as $index => $structure)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $structure->position }}</strong>
                                            @if ($structure->is_plt)
                                                <span class="badge bg-warning text-dark ms-2">
                                                    <i class="fas fa-user-clock"></i> PLT
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $structure->name }}
                                            @if ($structure->is_plt && $structure->pltFromStructure)
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-info-circle"></i> Dijabat oleh:
                                                    <strong>{{ $structure->pltFromStructure->name }}</strong>
                                                </small>
                                            @elseif ($structure->is_plt && $structure->plt_name)
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-info-circle"></i> Dijabat oleh:
                                                    <strong>{{ $structure->plt_name }}</strong>
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            <code class="text-primary">{{ $structure->nip ?? '-' }}</code>
                                        </td>
                                        <td class="text-center">
                                            @if ($structure->level == 1)
                                                <span class="badge bg-danger">Level {{ $structure->level }}</span>
                                            @elseif ($structure->level == 2)
                                                <span class="badge bg-success">Level {{ $structure->level }}</span>
                                            @else
                                                <span class="badge bg-info">Level {{ $structure->level }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($structure->is_active)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle"></i> Aktif
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-times-circle"></i> Tidak Aktif
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary Statistics -->
                    <div class="row mt-4 g-3">
                        <div class="col-md-3 col-6">
                            <div class="stat-card">
                                <div class="stat-icon bg-danger">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div class="stat-content">
                                    <h4>{{ $level1->count() }}</h4>
                                    <p>Kepala Dinas</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card">
                                <div class="stat-icon bg-success">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stat-content">
                                    <h4>{{ $level2->count() }}</h4>
                                    <p>Level 2</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card">
                                <div class="stat-icon bg-info">
                                    <i class="fas fa-user-friends"></i>
                                </div>
                                <div class="stat-content">
                                    <h4>{{ $level3->count() }}</h4>
                                    <p>Level 3</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-card">
                                <div class="stat-icon bg-primary">
                                    <i class="fas fa-users-cog"></i>
                                </div>
                                <div class="stat-content">
                                    <h4>{{ $structures->count() }}</h4>
                                    <p>Total Pegawai</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <h5 class="empty-title">Struktur organisasi sedang dalam pembaruan</h5>
                    <p class="empty-subtitle">Informasi struktur organisasi akan segera tersedia.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Back to Home Section -->
    <section class="section section-alternate scroll-reveal">
        <div class="container text-center">
            <h3 class="section-title mb-4">Ingin Tahu Lebih Banyak?</h3>
            <p class="section-subtitle mb-4">Jelajahi layanan dan informasi lainnya dari kami</p>
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="{{ route('public.home') }}" class="btn btn-gradient">
                    <i class="fas fa-home me-2"></i>Beranda
                </a>
                <a href="{{ route('public.layanan') }}" class="btn btn-outline-gradient">
                    <i class="fas fa-concierge-bell me-2"></i>Layanan Kami
                </a>
                <a href="{{ route('public.berita') }}" class="btn btn-outline-gradient">
                    <i class="fas fa-newspaper me-2"></i>Berita Terbaru
                </a>
            </div>
        </div>
    </section>
@endsection
