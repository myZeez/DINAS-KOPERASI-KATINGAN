@extends('admin.layouts.app')

@section('title', 'Kelola Complaint')

@section('content')
<div class="container-fluid">
    <!-- Modern Page Header -->
    @include('admin.partials.page-header', [
        'title' => 'Kelola Complaint',
        'subtitle' => 'Monitor dan tangani pengaduan masyarakat dengan sistem yang responsif dan terorganisir',
        'icon' => 'fas fa-exclamation-triangle',
        'breadcrumb' => 'Complaint',
        'badge' => ($pendingComplaints ?? 0) . ' Baru',
        'quickStats' => [
            [
                'value' => $totalComplaints ?? 0,
                'label' => 'Total',
                'icon' => 'fas fa-list'
            ],
            [
                'value' => $pendingComplaints ?? 0,
                'label' => 'Pending',
                'icon' => 'fas fa-clock'
            ],
            [
                'value' => ($resolvedPercentage ?? 0) . '%',
                'label' => 'Resolved',
                'icon' => 'fas fa-check-circle'
            ]
        ]
    ])

    <!-- Filter Tabs -->
    <div class="glass-card mb-4">
        <div class="row">
            <div class="col-md-8">
                <ul class="nav nav-pills" style="gap: 10px;">
                    <li class="nav-item">
                        <a class="nav-link active" style="background: var(--accent-color); color: #000; border-radius: 20px; padding: 8px 16px;">
                            Semua (12)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="background: rgba(255, 107, 107, 0.2); color: var(--danger-color); border-radius: 20px; padding: 8px 16px;">
                            Baru (5)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="background: rgba(255, 215, 61, 0.2); color: var(--warning-color); border-radius: 20px; padding: 8px 16px;">
                            Proses (4)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="background: rgba(0, 255, 136, 0.2); color: var(--accent-color); border-radius: 20px; padding: 8px 16px;">
                            Selesai (3)
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control form-control-glass" placeholder="Cari complaint...">
            </div>
        </div>
    </div>

    <!-- Complaints List -->
    <div class="row">
        @for($i = 1; $i <= 6; $i++)
        <div class="col-md-6 mb-4">
            <div class="glass-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="mb-1">Complaint #{{ str_pad($i, 3, '0', STR_PAD_LEFT) }}</h6>
                        <div style="font-size: 12px; color: var(--text-secondary);">
                            {{ date('d M Y, H:i', strtotime('-' . $i . ' hours')) }}
                        </div>
                    </div>
                    @if($i <= 2)
                        <span class="badge-glass" style="background: rgba(255, 107, 107, 0.2); color: var(--danger-color);">
                            Baru
                        </span>
                    @elseif($i <= 4)
                        <span class="badge-glass" style="background: rgba(255, 215, 61, 0.2); color: var(--warning-color);">
                            Proses
                        </span>
                    @else
                        <span class="badge-glass" style="background: rgba(0, 255, 136, 0.2); color: var(--accent-color);">
                            Selesai
                        </span>
                    @endif
                </div>

                <div class="mb-3">
                    <div class="fw-bold mb-2">Pelayanan yang kurang memuaskan</div>
                    <p style="font-size: 14px; color: var(--text-secondary); margin-bottom: 10px;">
                        Saya merasa pelayanan di bagian administrasi masih kurang cepat dan responsif. Mohon untuk diperbaiki...
                    </p>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #ff6b6b, #ee5a24); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; margin-right: 8px;">
                            {{ chr(64 + $i) }}
                        </div>
                        <div>
                            <div style="font-size: 14px; font-weight: 600;">Andi Susilo</div>
                            <div style="font-size: 12px; color: var(--text-secondary);">andi@email.com</div>
                        </div>
                    </div>
                    <div class="priority-indicator">
                        @if($i <= 2)
                            <i class="fas fa-exclamation-circle" style="color: var(--danger-color);" title="Priority Tinggi"></i>
                        @elseif($i <= 4)
                            <i class="fas fa-exclamation-triangle" style="color: var(--warning-color);" title="Priority Sedang"></i>
                        @else
                            <i class="fas fa-info-circle" style="color: var(--info-color);" title="Priority Rendah"></i>
                        @endif
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-glass flex-fill">
                        <i class="fas fa-eye"></i> Detail
                    </button>
                    @if($i <= 4)
                    <button class="btn btn-sm btn-primary-glass">
                        <i class="fas fa-reply"></i> Tanggapi
                    </button>
                    @endif
                    <button class="btn btn-sm" style="background: rgba(255, 107, 107, 0.2); color: var(--danger-color);">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        @endfor
    </div>

    <!-- Statistics -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ff6b6b, #ee5a24);">
                    <i class="fas fa-exclamation-triangle text-white"></i>
                </div>
                <div class="stat-number">12</div>
                <div class="stat-label">Total Complaint</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ffd93d, #f39c12);">
                    <i class="fas fa-clock text-white"></i>
                </div>
                <div class="stat-number">4</div>
                <div class="stat-label">Dalam Proses</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #00ff88, #6bcf7f);">
                    <i class="fas fa-check-circle text-white"></i>
                </div>
                <div class="stat-number">3</div>
                <div class="stat-label">Selesai</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #a8e6cf, #88d8c0);">
                    <i class="fas fa-chart-line text-white"></i>
                </div>
                <div class="stat-number">75%</div>
                <div class="stat-label">Tingkat Resolusi</div>
            </div>
        </div>
    </div>
</div>
@endsection
