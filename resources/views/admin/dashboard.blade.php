@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">

        <!-- Special Dashboard Header -->
        @include('admin.partials.page-header', [
            'title' => 'Dashboard Admin',
            'subtitle' =>
                'Selamat datang! Monitor dan kelola seluruh sistem informasi Dinas Koperasi dalam satu tempat',
            'icon' => 'fas fa-tachometer-alt',
            'breadcrumb' => 'Dashboard',
            'badge' => 'Live',
            'quickStats' => [
                [
                    'value' => $totalViews ?? 0,
                    'label' => 'Total Views',
                    'icon' => 'fas fa-eye',
                ],
                [
                    'value' => $activeUsers ?? 0,
                    'label' => 'Active Users',
                    'icon' => 'fas fa-users',
                ],
                [
                    'value' => $systemUptime ?? '100%',
                    'label' => 'Uptime',
                    'icon' => 'fas fa-server',
                ],
            ],
            'showProgress' => true,
            'progressValue' => 75,
        ])

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #ff6b6b, #ee5a24);">
                        <i class="fas fa-newspaper text-white"></i>
                    </div>
                    <div class="stat-number">{{ $newsCount ?? 0 }}</div>
                    <div class="stat-label">Total Berita</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #00ff88, #6bcf7f);">
                        <i class="fas fa-images text-white"></i>
                    </div>
                    <div class="stat-number">{{ $galleryCount ?? 0 }}</div>
                    <div class="stat-label">Total Galeri</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #ffd93d, #f39c12);">
                        <i class="fas fa-star text-white"></i>
                    </div>
                    <div class="stat-number">{{ $reviewCount ?? 0 }}</div>
                    <div class="stat-label">Total Ulasan</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #a8e6cf, #88d8c0);">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                    <div class="stat-number">{{ $complaintCount ?? 0 }}</div>
                    <div class="stat-label">Review Negatif</div>
                    <small style="font-size: 10px; color: var(--text-secondary); margin-top: 4px; display: block;">
                        Rating 1-2 bintang
                    </small>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="glass-card">
                    <h5 class="mb-3">
                        <i class="fas fa-bolt"></i> Aksi Cepat
                    </h5>
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.news.create') }}" class="btn btn-glass w-100">
                                <i class="fas fa-plus"></i> Tambah Berita
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.galleries.create') }}" class="btn btn-glass w-100">
                                <i class="fas fa-camera"></i> Tambah Galeri
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.profile') }}" class="btn btn-glass w-100">
                                <i class="fas fa-edit"></i> Edit Profile
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.structure') }}" class="btn btn-glass w-100">
                                <i class="fas fa-sitemap"></i> Kelola Struktur
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="/api/documentation" target="_blank" class="btn btn-glass w-100 api-docs-btn">
                                <i class="fas fa-code"></i> API Documentation
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.accounts.index') }}" class="btn btn-glass w-100">
                                <i class="fas fa-users"></i> Kelola Akun
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.reviews') }}" class="btn btn-glass w-100">
                                <i class="fas fa-star"></i> Review & Ulasan
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.file-downloads.index') }}" class="btn btn-glass w-100">
                                <i class="fas fa-download"></i> File Download
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="row">
            <div class="col-md-8">
                <div class="glass-card">
                    <h5 class="mb-3">
                        <i class="fas fa-clock"></i> Aktivitas Terbaru
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-glass">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Aktivitas</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ date('H:i') }}</td>
                                    <td>Admin login ke sistem</td>
                                    <td><span class="badge-glass"
                                            style="background: rgba(0, 255, 136, 0.2); color: var(--accent-color);">Berhasil</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ date('H:i', strtotime('-30 minutes')) }}</td>
                                    <td>Berita baru dipublikasikan</td>
                                    <td><span class="badge-glass"
                                            style="background: rgba(0, 255, 136, 0.2); color: var(--accent-color);">Berhasil</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ date('H:i', strtotime('-1 hour')) }}</td>
                                    <td>Galeri foto diperbarui</td>
                                    <td><span class="badge-glass"
                                            style="background: rgba(255, 215, 61, 0.2); color: var(--warning-color);">Pending</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ date('H:i', strtotime('-2 hours')) }}</td>
                                    <td>Complaint baru diterima</td>
                                    <td><span class="badge-glass"
                                            style="background: rgba(255, 107, 107, 0.2); color: var(--danger-color);">Perlu
                                            Review</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card">
                    <h5 class="mb-3">
                        <i class="fas fa-chart-pie"></i> Statistik Bulan Ini
                    </h5>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Berita Dipublikasi</span>
                            <span class="fw-bold">{{ $monthlyNews ?? 5 }}</span>
                        </div>
                        <div class="progress" style="height: 8px; background: rgba(255,255,255,0.1);">
                            <div class="progress-bar" style="width: 70%; background: var(--accent-color);"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Foto Galeri</span>
                            <span class="fw-bold">{{ $monthlyGallery ?? 12 }}</span>
                        </div>
                        <div class="progress" style="height: 8px; background: rgba(255,255,255,0.1);">
                            <div class="progress-bar" style="width: 85%; background: var(--info-color);"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Ulasan Masuk</span>
                            <span class="fw-bold">{{ $monthlyReviews ?? 8 }}</span>
                        </div>
                        <div class="progress" style="height: 8px; background: rgba(255,255,255,0.1);">
                            <div class="progress-bar" style="width: 60%; background: var(--warning-color);"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Review Negatif</span>
                            <span class="fw-bold">{{ $monthlyComplaints ?? 0 }}</span>
                        </div>
                        <div class="progress" style="height: 8px; background: rgba(255,255,255,0.1);">
                            <div class="progress-bar"
                                style="width: {{ $monthlyComplaints > 0 ? min(($monthlyComplaints / max($monthlyReviews, 1)) * 100, 100) : 0 }}%; background: var(--danger-color);">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass-card mt-3">
                    <h5 class="mb-3">
                        <i class="fas fa-bell"></i> Notifikasi
                    </h5>
                    <div class="notification-item d-flex align-items-center mb-3">
                        <div class="notification-icon me-3"
                            style="width: 40px; height: 40px; background: rgba(255, 107, 107, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-exclamation text-danger"></i>
                        </div>
                        <div>
                            <div class="fw-bold" style="font-size: 14px;">Complaint Baru</div>
                            <div style="font-size: 12px; color: var(--text-secondary);">2 menit yang lalu</div>
                        </div>
                    </div>
                    <div class="notification-item d-flex align-items-center mb-3">
                        <div class="notification-icon me-3"
                            style="width: 40px; height: 40px; background: rgba(0, 255, 136, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-star text-success"></i>
                        </div>
                        <div>
                            <div class="fw-bold" style="font-size: 14px;">Ulasan Positif</div>
                            <div style="font-size: 12px; color: var(--text-secondary);">15 menit yang lalu</div>
                        </div>
                    </div>
                    <div class="notification-item d-flex align-items-center">
                        <div class="notification-icon me-3"
                            style="width: 40px; height: 40px; background: rgba(255, 215, 61, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-image text-warning"></i>
                        </div>
                        <div>
                            <div class="fw-bold" style="font-size: 14px;">Foto Perlu Approval</div>
                            <div style="font-size: 12px; color: var(--text-secondary);">1 jam yang lalu</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="glass-card">
                    <h5 class="mb-3">
                        <i class="fas fa-server"></i> Status Sistem
                    </h5>
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <div class="system-status">
                                <i class="fas fa-database" style="color: var(--accent-color); font-size: 24px;"></i>
                                <div class="mt-2">Database</div>
                                <div style="color: var(--accent-color); font-size: 12px;">Online</div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="system-status">
                                <i class="fas fa-server" style="color: var(--accent-color); font-size: 24px;"></i>
                                <div class="mt-2">Server</div>
                                <div style="color: var(--accent-color); font-size: 12px;">Aktif</div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="system-status">
                                <i class="fas fa-shield-alt" style="color: var(--accent-color); font-size: 24px;"></i>
                                <div class="mt-2">Security</div>
                                <div style="color: var(--accent-color); font-size: 12px;">Aman</div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="system-status">
                                <i class="fas fa-sync-alt" style="color: var(--accent-color); font-size: 24px;"></i>
                                <div class="mt-2">Backup</div>
                                <div style="color: var(--accent-color); font-size: 12px;">Terkini</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .api-docs-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: 1px solid rgba(102, 126, 234, 0.3) !important;
            color: white !important;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .api-docs-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white !important;
        }

        .api-docs-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .api-docs-btn:hover::before {
            left: 100%;
        }

        .api-docs-btn i {
            margin-right: 8px;
            font-size: 1.1em;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Real-time clock update
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID');
            const dateString = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            const timeElement = document.querySelector('.admin-name div:last-child');
            if (timeElement) {
                timeElement.textContent = dateString + ', ' + timeString;
            }
        }

        // Update time every second
        setInterval(updateTime, 1000);
        updateTime(); // Initial call

        // Add animation to stat cards
        document.addEventListener('DOMContentLoaded', function() {
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'all 0.5s ease';

                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 100);
                }, index * 100);
            });

            // Update time and date in welcome section
            function updateDateTime() {
                const now = new Date();

                // Format time
                const timeOptions = {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                };
                const timeString = now.toLocaleTimeString('id-ID', timeOptions);

                // Format date
                const dateOptions = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                const dateString = now.toLocaleDateString('id-ID', dateOptions);

                // Update elements
                const timeElement = document.getElementById('current-time');
                const dateElement = document.getElementById('current-date');

                if (timeElement) timeElement.textContent = timeString;
                if (dateElement) dateElement.textContent = dateString;
            }

            // Update immediately and then every second
            updateDateTime();
            setInterval(updateDateTime, 1000);
        });
    </script>
@endpush
