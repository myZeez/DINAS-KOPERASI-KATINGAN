@extends('admin.layouts.app')

@section('title', 'Kelola Akun')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
    <div class="container-fluid">
        <!-- Modern Page Header -->
        @include('admin.partials.page-header', [
            'title' => 'Kelola Akun',
            'subtitle' => 'Kelola akun administrator dan hak akses sistem dengan keamanan tingkat tinggi',
            'icon' => 'fas fa-users-cog',
            'breadcrumb' => 'Accounts',
            'primaryAction' => [
                'url' => '#',
                'text' => 'Tambah Admin',
                'icon' => 'fas fa-user-plus',
                'modal' => 'addUserModal',
            ],
            'quickStats' => [
                [
                    'value' => $totalAdmins,
                    'label' => 'Total Admin',
                    'icon' => 'fas fa-users',
                ],
                [
                    'value' => $activeAdmins,
                    'label' => 'Active',
                    'icon' => 'fas fa-check-circle',
                ],
            ],
        ])

        <!-- Custom Tab Navigation -->
        <div class="glass-card mb-4">
            <div class="custom-tab-container">
                <div class="custom-tab-wrapper">
                    <button class="custom-tab-button active" data-tab="users">
                        <i class="fas fa-users"></i>
                        <span>Kelola Administrator</span>
                    </button>
                    <button class="custom-tab-button" data-tab="mail-settings">
                        <i class="fas fa-envelope-open-text"></i>
                        <span>Konfigurasi Email SMTP</span>
                    </button>
                </div>
                <div class="custom-tab-indicator"></div>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="tab-content" id="accountTabsContent">
            <!-- Users Tab -->
            <div class="tab-pane fade show active tab-content-animation" id="users" role="tabpanel">
                <div class="glass-card">
                    <div class="table-responsive">
                        <table class="table table-glass">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Avatar</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Last Login</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if ($user->avatar)
                                        <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}"
                                            style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
                                    @else
                                        <div
                                            style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--accent-color), var(--info-color)); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $user->name }}</div>
                                    <div style="font-size: 12px; color: var(--text-secondary);">{{ $user->role_label }}</div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if ($user->role === 'super_admin')
                                        <span class="badge-glass"
                                            style="background: rgba(255, 107, 107, 0.2); color: var(--danger-color);">
                                            Super Admin
                                        </span>
                                    @elseif($user->role === 'admin')
                                        <span class="badge-glass"
                                            style="background: rgba(0, 255, 136, 0.2); color: var(--accent-color);">
                                            Admin
                                        </span>
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if ($user->is_active)
                                        <span class="badge-glass"
                                            style="background: rgba(0, 255, 136, 0.2); color: var(--accent-color);">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="badge-glass"
                                            style="background: rgba(255, 107, 107, 0.2); color: var(--danger-color);">
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $user->last_login_at ? $user->last_login_at->format('d M Y, H:i') : 'Belum pernah login' }}
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-glass edit-user-btn"
                                            data-user="{{ json_encode($user) }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button
                                            class="btn btn-sm toggle-status-btn {{ $user->is_active ? 'btn-warning' : 'btn-success' }}"
                                            data-user-id="{{ $user->id }}"
                                            data-current-status="{{ $user->is_active ? 1 : 0 }}"
                                            style="background: rgba({{ $user->is_active ? '255, 215, 61' : '0, 255, 136' }}, 0.2); color: var(--{{ $user->is_active ? 'warning' : 'accent' }}-color);">
                                            <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                        </button>
                                        @if (!$user->isSuperAdmin() || \App\Models\User::where('role', 'super_admin')->count() > 1)
                                            <button class="btn btn-sm delete-user-btn" data-user-id="{{ $user->id }}"
                                                data-user-name="{{ $user->name }}"
                                                style="background: rgba(255, 107, 107, 0.2); color: var(--danger-color);">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                    </div>
                </div>
            </div>

            <!-- Mail Settings Tab -->
            <div class="tab-pane fade tab-content-animation" id="mail-settings" role="tabpanel">
                <div class="glass-card">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="mb-0"><i class="fas fa-server me-2"></i>Konfigurasi Server SMTP</h5>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="loadEnvSettings">
                                    <i class="fas fa-download me-2"></i>Load dari .env
                                </button>
                            </div>

                            <form action="{{ route('admin.mail-settings.store') }}" method="POST" id="mailSettingsForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Mail Driver</label>
                                        <div class="custom-dropdown" data-name="mail_mailer" data-value="{{ old('mail_mailer', $mailSettings->mail_mailer ?? 'smtp') }}" data-required="true">
                                            <div class="dropdown-selected">
                                                <span class="selected-text">{{ old('mail_mailer', $mailSettings->mail_mailer ?? 'smtp') == 'smtp' ? 'SMTP' : 'Sendmail' }}</span>
                                                <i class="fas fa-chevron-down dropdown-arrow"></i>
                                            </div>
                                            <div class="dropdown-options">
                                                <div class="dropdown-option" data-value="smtp">
                                                    <i class="fas fa-envelope me-2"></i>SMTP
                                                </div>
                                                <div class="dropdown-option" data-value="sendmail">
                                                    <i class="fas fa-paper-plane me-2"></i>Sendmail
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">SMTP Host</label>
                                        <input type="text" name="mail_host" class="form-control form-control-glass" id="mail_host"
                                               value="{{ old('mail_host', $mailSettings->mail_host ?? env('MAIL_HOST', 'smtp.gmail.com')) }}"
                                               placeholder="smtp.gmail.com" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">SMTP Port</label>
                                        <div class="custom-dropdown" data-name="mail_port" data-value="{{ old('mail_port', $mailSettings->mail_port ?? env('MAIL_PORT', 587)) }}" data-required="true">
                                            <div class="dropdown-selected">
                                                <span class="selected-text">
                                                    @php
                                                        $currentPort = old('mail_port', $mailSettings->mail_port ?? env('MAIL_PORT', 587));
                                                        $portText = $currentPort == 587 ? '587 (TLS)' : ($currentPort == 465 ? '465 (SSL)' : '25 (Non-encrypted)');
                                                    @endphp
                                                    {{ $portText }}
                                                </span>
                                                <i class="fas fa-chevron-down dropdown-arrow"></i>
                                            </div>
                                            <div class="dropdown-options">
                                                <div class="dropdown-option" data-value="587">
                                                    <i class="fas fa-shield-alt me-2 text-success"></i>587 (TLS)
                                                </div>
                                                <div class="dropdown-option" data-value="465">
                                                    <i class="fas fa-lock me-2 text-warning"></i>465 (SSL)
                                                </div>
                                                <div class="dropdown-option" data-value="25">
                                                    <i class="fas fa-unlock me-2 text-danger"></i>25 (Non-encrypted)
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Encryption</label>
                                        <div class="custom-dropdown" data-name="mail_encryption" data-value="{{ old('mail_encryption', $mailSettings->mail_encryption ?? env('MAIL_ENCRYPTION', 'tls')) }}">
                                            <div class="dropdown-selected">
                                                <span class="selected-text">
                                                    @php
                                                        $currentEncryption = old('mail_encryption', $mailSettings->mail_encryption ?? env('MAIL_ENCRYPTION', 'tls'));
                                                        $encText = $currentEncryption == 'tls' ? 'TLS' : ($currentEncryption == 'ssl' ? 'SSL' : 'None');
                                                    @endphp
                                                    {{ $encText }}
                                                </span>
                                                <i class="fas fa-chevron-down dropdown-arrow"></i>
                                            </div>
                                            <div class="dropdown-options">
                                                <div class="dropdown-option" data-value="tls">
                                                    <i class="fas fa-shield-alt me-2 text-success"></i>TLS
                                                </div>
                                                <div class="dropdown-option" data-value="ssl">
                                                    <i class="fas fa-lock me-2 text-warning"></i>SSL
                                                </div>
                                                <div class="dropdown-option" data-value="">
                                                    <i class="fas fa-unlock me-2 text-muted"></i>None
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Username/Email</label>
                                        <input type="email" name="mail_username" class="form-control form-control-glass" id="mail_username"
                                               value="{{ old('mail_username', $mailSettings->mail_username ?? env('MAIL_USERNAME', '')) }}"
                                               placeholder="your-email@gmail.com" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Password/App Password</label>
                                        <div class="input-group">
                                            <input type="password" name="mail_password" id="mailPassword" class="form-control form-control-glass"
                                                   value="{{ old('mail_password', $mailSettings->mail_password ?? env('MAIL_PASSWORD', '')) }}"
                                                   placeholder="App Password atau Password Email">
                                            <button class="btn btn-glass" type="button" id="togglePassword">
                                                <i class="fas fa-eye" id="eyeIcon"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">Untuk Gmail, gunakan App Password bukan password biasa</small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">From Address</label>
                                        <input type="email" name="mail_from_address" class="form-control form-control-glass" id="mail_from_address"
                                               value="{{ old('mail_from_address', $mailSettings->mail_from_address ?? env('MAIL_FROM_ADDRESS', '')) }}"
                                               placeholder="noreply@dinaskoperasi.go.id" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">From Name</label>
                                        <input type="text" name="mail_from_name" class="form-control form-control-glass" id="mail_from_name"
                                               value="{{ old('mail_from_name', $mailSettings->mail_from_name ?? env('MAIL_FROM_NAME', 'Dinas Koperasi')) }}"
                                               placeholder="Dinas Koperasi" required>
                                    </div>
                                </div>

                                <div class="alert alert-info mb-4">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Info:</strong> Konfigurasi akan disimpan ke database dan file .env secara bersamaan untuk memastikan konsistensi.
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <button type="button" class="btn btn-warning" id="testEmailBtn">
                                        <i class="fas fa-paper-plane me-2"></i>Test Email
                                    </button>
                                    <button type="submit" class="btn btn-primary-glass" id="saveConfigBtn">
                                        <i class="fas fa-save me-2"></i>Simpan ke Database & .env
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="col-lg-4">
                            <div class="info-card">
                                <h6 class="mb-3"><i class="fas fa-info-circle me-2"></i>Informasi SMTP</h6>

                                <div class="mb-3">
                                    <strong>Status Konfigurasi:</strong>
                                    <div class="mt-1">
                                        @if(isset($mailSettings) && $mailSettings->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-warning">Menggunakan .env</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <strong>Gmail Setup:</strong>
                                    <ol class="small mt-2">
                                        <li>Aktifkan 2-Factor Authentication</li>
                                        <li>Generate App Password</li>
                                        <li>Gunakan App Password, bukan password Gmail</li>
                                        <li>Host: smtp.gmail.com</li>
                                        <li>Port: 587 (TLS) atau 465 (SSL)</li>
                                    </ol>
                                </div>

                                <div class="alert alert-info">
                                    <i class="fas fa-lightbulb me-2"></i>
                                    <strong>Tips:</strong> Setelah menyimpan konfigurasi, gunakan tombol "Test Email" untuk memastikan pengaturan bekerja dengan baik.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #00ff88, #6bcf7f);">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <div class="stat-number">{{ $totalAdmins }}</div>
                    <div class="stat-label">Total Admin</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #ffd93d, #f39c12);">
                        <i class="fas fa-user-check text-white"></i>
                    </div>
                    <div class="stat-number">{{ $activeAdmins }}</div>
                    <div class="stat-label">Admin Aktif</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #ff6b6b, #ee5a24);">
                        <i class="fas fa-user-times text-white"></i>
                    </div>
                    <div class="stat-number">{{ $inactiveAdmins }}</div>
                    <div class="stat-label">Admin Tidak Aktif</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #a8e6cf, #88d8c0);">
                        <i class="fas fa-sign-in-alt text-white"></i>
                    </div>
                    <div class="stat-number">{{ $todayLogins }}</div>
                    <div class="stat-label">Login Hari Ini</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content"
                style="background: var(--glass-bg); backdrop-filter: blur(20px); border: 1px solid var(--glass-border); border-radius: 16px;">
                <div class="modal-header" style="border: none;">
                    <h5 class="modal-title" style="color: var(--text-primary);">
                        <i class="fas fa-user-plus"></i> Tambah Administrator
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.accounts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control form-control-glass"
                                placeholder="Masukkan nama lengkap" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control form-control-glass"
                                placeholder="email@dinaskoperasi.go.id" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control form-control-glass"
                                placeholder="Password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-control form-control-glass" required>
                                <option value="admin">Admin</option>
                                <option value="super_admin">Super Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Avatar (Optional)</label>
                            <input type="file" name="avatar" class="form-control form-control-glass"
                                accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer" style="border: none;">
                        <button type="button" class="btn btn-glass" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary-glass">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content"
                style="background: var(--glass-bg); backdrop-filter: blur(20px); border: 1px solid var(--glass-border); border-radius: 16px;">
                <div class="modal-header" style="border: none;">
                    <h5 class="modal-title" style="color: var(--text-primary);">
                        <i class="fas fa-user-edit"></i> Edit Administrator
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="editUserForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" id="edit_name" class="form-control form-control-glass"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="edit_email" class="form-control form-control-glass"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password (Kosongkan jika tidak ingin mengubah)</label>
                            <input type="password" name="password" class="form-control form-control-glass"
                                placeholder="Password baru">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" id="edit_role" class="form-control form-control-glass" required>
                                <option value="admin">Admin</option>
                                <option value="super_admin">Super Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Avatar</label>
                            <div id="current_avatar" class="mb-2"></div>
                            <input type="file" name="avatar" class="form-control form-control-glass"
                                accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer" style="border: none;">
                        <button type="button" class="btn btn-glass" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary-glass">
                            <i class="fas fa-save"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Custom Manual Tab Navigation - No Bootstrap Dependencies */
            .custom-tab-container {
                position: relative;
                background: transparent;
                border-bottom: 2px solid rgba(79, 172, 254, 0.15);
                overflow: hidden;
            }

            .custom-tab-wrapper {
                display: flex;
                position: relative;
                gap: 0;
            }

            .custom-tab-button {
                position: relative;
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 16px 24px;
                background: transparent;
                border: none;
                color: rgba(255, 255, 255, 0.7);
                font-size: 15px;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
                border-radius: 0;
                outline: none;
                white-space: nowrap;
                z-index: 2;
            }

            .custom-tab-button i {
                font-size: 16px;
                opacity: 0.8;
                transition: all 0.25s ease;
            }

            .custom-tab-button span {
                transition: all 0.25s ease;
            }

            /* Hover State */
            .custom-tab-button:hover {
                color: rgba(255, 255, 255, 0.9);
                background: rgba(79, 172, 254, 0.08);
            }

            .custom-tab-button:hover i {
                opacity: 1;
                transform: translateY(-1px);
            }

            /* Active State */
            .custom-tab-button.active {
                color: #4facfe;
                font-weight: 600;
                background: rgba(79, 172, 254, 0.12);
            }

            .custom-tab-button.active i {
                opacity: 1;
                color: #4facfe;
                text-shadow: 0 0 8px rgba(79, 172, 254, 0.4);
            }

            .custom-tab-button.active span {
                color: #4facfe;
            }

            /* Moving Indicator */
            .custom-tab-indicator {
                position: absolute;
                bottom: 0;
                left: 0;
                height: 3px;
                background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
                border-radius: 2px 2px 0 0;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 0 0 12px rgba(79, 172, 254, 0.5);
                z-index: 3;
            }

            /* Focus States for Accessibility */
            .custom-tab-button:focus {
                outline: 2px solid rgba(79, 172, 254, 0.5);
                outline-offset: -2px;
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .custom-tab-button {
                    padding: 12px 16px;
                    font-size: 14px;
                }

                .custom-tab-button span {
                    display: none;
                }

                .custom-tab-button i {
                    font-size: 18px;
                }
            }

            @media (max-width: 480px) {
                .custom-tab-wrapper {
                    justify-content: space-around;
                }

                .custom-tab-button {
                    flex: 1;
                    justify-content: center;
                    padding: 14px 8px;
                }
            }

            /* Animation for tab content */
            .tab-content-animation {
                opacity: 0;
                transform: translateY(20px);
                transition: all 0.3s ease;
            }

            .tab-content-animation.active {
                opacity: 1;
                transform: translateY(0);
            }

            .info-card {
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid rgba(79, 172, 254, 0.1);
                border-radius: 12px;
                padding: 1.5rem;
                backdrop-filter: blur(10px);
            }

            .input-group .btn-glass {
                border: 1px solid rgba(79, 172, 254, 0.2);
                border-left: none;
            }

            /* Custom Dropdown Styles */
            .custom-dropdown {
                position: relative;
                width: 100%;
            }

            .dropdown-selected {
                background: rgba(30, 41, 59, 0.8);
                border: 1px solid rgba(79, 172, 254, 0.3);
                border-radius: 12px;
                padding: 0.75rem 1rem;
                cursor: pointer;
                display: flex;
                justify-content: space-between;
                align-items: center;
                transition: all 0.3s ease;
                backdrop-filter: blur(15px);
                color: rgba(255, 255, 255, 0.9);
                font-weight: 500;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            }

            .dropdown-selected:hover {
                border-color: rgba(79, 172, 254, 0.5);
                background: rgba(79, 172, 254, 0.15);
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(79, 172, 254, 0.2);
                color: #4facfe;
            }

            .dropdown-selected.active {
                border-color: #4facfe;
                background: rgba(79, 172, 254, 0.2);
                box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.2);
                color: #4facfe;
            }

            .selected-text {
                flex: 1;
                text-align: left;
            }

            .dropdown-arrow {
                transition: transform 0.3s ease;
                color: rgba(255, 255, 255, 0.6);
                font-size: 0.9rem;
            }

            .dropdown-selected:hover .dropdown-arrow {
                color: #4facfe;
            }

            .dropdown-selected.active .dropdown-arrow {
                transform: rotate(180deg);
                color: #4facfe;
                text-shadow: 0 0 10px rgba(79, 172, 254, 0.5);
            }

            .dropdown-options {
                position: absolute;
                top: calc(100% + 5px);
                left: 0;
                right: 0;
                background: rgba(30, 41, 59, 0.95);
                border: 1px solid rgba(79, 172, 254, 0.3);
                border-radius: 12px;
                backdrop-filter: blur(20px);
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
                z-index: 1000;
                opacity: 0;
                visibility: hidden;
                transform: translateY(-10px);
                transition: all 0.3s ease;
                max-height: 250px;
                overflow-y: auto;
            }

            .dropdown-options.show {
                opacity: 1;
                visibility: visible;
                transform: translateY(0);
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(79, 172, 254, 0.2);
            }

            .dropdown-option {
                padding: 0.75rem 1rem;
                cursor: pointer;
                display: flex;
                align-items: center;
                transition: all 0.2s ease;
                color: rgba(255, 255, 255, 0.9);
                border-bottom: 1px solid rgba(79, 172, 254, 0.15);
            }

            .dropdown-option:last-child {
                border-bottom: none;
            }

            .dropdown-option:hover {
                background: rgba(79, 172, 254, 0.2);
                color: #4facfe;
                padding-left: 1.2rem;
                box-shadow: inset 3px 0 0 #4facfe;
            }

            .dropdown-option.selected {
                background: rgba(79, 172, 254, 0.25);
                color: #4facfe;
                font-weight: 600;
                position: relative;
                box-shadow: inset 3px 0 0 #4facfe;
            }

            .dropdown-option.selected::after {
                content: '\f00c';
                font-family: 'Font Awesome 6 Free';
                font-weight: 900;
                position: absolute;
                right: 1rem;
                color: #4facfe;
                text-shadow: 0 0 10px rgba(79, 172, 254, 0.5);
            }

            /* Custom scrollbar for dropdown */
            .dropdown-options::-webkit-scrollbar {
                width: 6px;
            }

            .dropdown-options::-webkit-scrollbar-track {
                background: rgba(79, 172, 254, 0.1);
                border-radius: 6px;
            }

            .dropdown-options::-webkit-scrollbar-thumb {
                background: linear-gradient(135deg, #4facfe, #00f2fe);
                border-radius: 6px;
                box-shadow: 0 0 10px rgba(79, 172, 254, 0.3);
            }

            .dropdown-options::-webkit-scrollbar-thumb:hover {
                background: linear-gradient(135deg, #00f2fe, #4facfe);
                box-shadow: 0 0 15px rgba(79, 172, 254, 0.5);
            }

            /* Animation untuk icon */
            .dropdown-option i {
                transition: transform 0.2s ease;
            }

            .dropdown-option:hover i {
                transform: scale(1.1);
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Ensure jQuery is loaded and DOM is ready
            $(document).ready(function() {
                console.log('jQuery loaded and DOM ready');

                // Initialize Custom Tabs
                initCustomTabs();

                // Test if buttons are found
                console.log('Edit buttons found:', $('.edit-user-btn').length);
                console.log('Delete buttons found:', $('.delete-user-btn').length);
                console.log('Toggle buttons found:', $('.toggle-status-btn').length);

                // Handle edit user modal with event delegation
                $(document).off('click', '.edit-user-btn').on('click', '.edit-user-btn', function(e) {
                    e.preventDefault();
                    console.log('Edit button clicked');

                    try {
                        const userData = $(this).data('user');
                        console.log('User data:', userData);

                        const user = typeof userData === 'string' ? JSON.parse(userData) : userData;

                        $('#edit_name').val(user.name);
                        $('#edit_email').val(user.email);
                        $('#edit_role').val(user.role);

                        // Set form action
                        $('#editUserForm').attr('action', `/admin/users/${user.id}`);

                        // Show current avatar
                        if (user.avatar) {
                            $('#current_avatar').html(`
                    <div>
                        <img src="/storage/${user.avatar}" alt="Current Avatar" style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;">
                        <p class="small mt-1">Avatar saat ini</p>
                    </div>
                `);
                        } else {
                            $('#current_avatar').html('<p class="small text-muted">Tidak ada avatar</p>');
                        }

                        // Show modal
                        $('#editUserModal').modal('show');
                    } catch (error) {
                        console.error('Error in edit button handler:', error);
                        showNotification('error', 'Terjadi kesalahan saat membuka modal edit');
                    }
                });

                // Handle toggle status with event delegation
                $(document).off('click', '.toggle-status-btn').on('click', '.toggle-status-btn', function(e) {
                    e.preventDefault();
                    console.log('Toggle status button clicked');

                    const userId = $(this).data('user-id');
                    const currentStatus = $(this).data('current-status');
                    const btn = $(this);

                    console.log('Toggle status for user ID:', userId, 'Current status:', currentStatus);

                    $.ajax({
                        url: `/admin/users/${userId}/toggle-status`,
                        method: 'PATCH',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content') ||
                                '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log('Toggle status response:', response);
                            if (response.success) {
                                // Update button appearance
                                if (response.status) {
                                    btn.removeClass('btn-success').addClass('btn-warning');
                                    btn.find('i').removeClass('fa-check').addClass('fa-ban');
                                    btn.css('background', 'rgba(255, 215, 61, 0.2)');
                                    btn.css('color', 'var(--warning-color)');
                                    btn.data('current-status', 1);
                                } else {
                                    btn.removeClass('btn-warning').addClass('btn-success');
                                    btn.find('i').removeClass('fa-ban').addClass('fa-check');
                                    btn.css('background', 'rgba(0, 255, 136, 0.2)');
                                    btn.css('color', 'var(--accent-color)');
                                    btn.data('current-status', 0);
                                }

                                // Update status badge
                                const statusCell = btn.closest('tr').find('td:nth-child(6)');
                                if (response.status) {
                                    statusCell.html(
                                        '<span class="badge-glass" style="background: rgba(0, 255, 136, 0.2); color: var(--accent-color);">Aktif</span>'
                                    );
                                } else {
                                    statusCell.html(
                                        '<span class="badge-glass" style="background: rgba(255, 107, 107, 0.2); color: var(--danger-color);">Tidak Aktif</span>'
                                    );
                                }

                                showNotification('success', response.message);
                            }
                        },
                        error: function(xhr) {
                            console.error('Toggle status error:', xhr);
                            showNotification('error', 'Terjadi kesalahan saat mengubah status');
                        }
                    });
                });

                // Handle delete user with event delegation
                $(document).off('click', '.delete-user-btn').on('click', '.delete-user-btn', function(e) {
                    e.preventDefault();
                    console.log('Delete button clicked');

                    const userId = $(this).data('user-id');
                    const userName = $(this).data('user-name');
                    const deleteBtn = $(this);

                    console.log('Delete user ID:', userId, 'Name:', userName);

                    if (confirm(`Apakah Anda yakin ingin menghapus user "${userName}"?`)) {
                        // Disable button during request
                        deleteBtn.prop('disabled', true);

                        $.ajax({
                            url: `/admin/users/${userId}`,
                            method: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content') ||
                                    '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                console.log('Delete response:', response);
                                if (response.success) {
                                    showNotification('success', response.message);

                                    // Remove row from table with animation
                                    deleteBtn.closest('tr').fadeOut(300, function() {
                                        $(this).remove();
                                        updateStatistics();
                                    });
                                } else {
                                    showNotification('error', response.message ||
                                        'Gagal menghapus user');
                                    deleteBtn.prop('disabled', false);
                                }
                            },
                            error: function(xhr) {
                                console.error('Delete error:', xhr);
                                const response = xhr.responseJSON;
                                showNotification('error', response?.message ||
                                    'Terjadi kesalahan saat menghapus user');
                                deleteBtn.prop('disabled', false);
                            }
                        });
                    }
                });

                // Toggle password visibility
                $('#togglePassword').click(function() {
                    const passwordField = $('#mailPassword');
                    const eyeIcon = $('#eyeIcon');

                    if (passwordField.attr('type') === 'password') {
                        passwordField.attr('type', 'text');
                        eyeIcon.removeClass('fa-eye').addClass('fa-eye-slash');
                    } else {
                        passwordField.attr('type', 'password');
                        eyeIcon.removeClass('fa-eye-slash').addClass('fa-eye');
                    }
                });

                // Test Email functionality
                $('#testEmailBtn').click(function() {
                    const btn = $(this);
                    const originalText = btn.html();

                    // Validate form first
                    const form = $('#mailSettingsForm')[0];
                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return;
                    }

                    btn.prop('disabled', true);
                    btn.html('<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...');

                    $.ajax({
                        url: '{{ route("admin.mail-settings.test") }}',
                        method: 'POST',
                        data: $('#mailSettingsForm').serialize(),
                        success: function(response) {
                            if (response.success) {
                                showNotification('success', 'Test email berhasil dikirim! Periksa inbox Anda.');
                            } else {
                                showNotification('error', response.message || 'Gagal mengirim test email');
                            }
                        },
                        error: function(xhr) {
                            const response = xhr.responseJSON;
                            showNotification('error', response?.message || 'Terjadi kesalahan saat mengirim test email');
                        },
                        complete: function() {
                            btn.prop('disabled', false);
                            btn.html(originalText);
                        }
                    });
                });

                // Handle tab switching from URL parameter
                const urlParams = new URLSearchParams(window.location.search);
                const tabParam = urlParams.get('tab');
                if (tabParam === 'mail-settings') {
                    $('#mail-settings-tab').click();
                }

                // Initialize Custom Dropdowns
                initCustomDropdowns();

                // Load .env settings button
                $('#loadEnvSettings').click(function() {
                    if (confirm('Apakah Anda yakin ingin memuat konfigurasi dari file .env? Ini akan menimpa pengaturan yang sedang diisi.')) {
                        loadEnvSettings();
                    }
                });
            });

            function updateStatistics() {
                setTimeout(() => {
                    location.reload();
                }, 500);
            }

            function showNotification(type, message) {
                $('.notification-alert').remove();

                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const notification = $(`
        <div class="alert ${alertClass} alert-dismissible fade show position-fixed notification-alert"
             style="top: 80px; right: 20px; z-index: 9999; max-width: 300px;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);

                $('body').append(notification);

                setTimeout(() => {
                    notification.alert('close');
                }, 3000);
            }

            function initCustomDropdowns() {
                // Handle dropdown click
                $(document).on('click', '.dropdown-selected', function(e) {
                    e.stopPropagation();
                    const dropdown = $(this).parent();
                    const options = dropdown.find('.dropdown-options');
                    const selected = $(this);

                    // Close other dropdowns
                    $('.custom-dropdown').not(dropdown).find('.dropdown-selected').removeClass('active');
                    $('.custom-dropdown').not(dropdown).find('.dropdown-options').removeClass('show');

                    // Toggle current dropdown
                    selected.toggleClass('active');
                    options.toggleClass('show');
                });

                // Handle option selection
                $(document).on('click', '.dropdown-option', function(e) {
                    e.stopPropagation();
                    const option = $(this);
                    const dropdown = option.closest('.custom-dropdown');
                    const selected = dropdown.find('.dropdown-selected');
                    const options = dropdown.find('.dropdown-options');
                    const selectedText = dropdown.find('.selected-text');

                    const value = option.data('value');
                    const text = option.html();

                    // Update selected text and value
                    selectedText.html(text);
                    dropdown.attr('data-value', value);

                    // Update visual state
                    dropdown.find('.dropdown-option').removeClass('selected');
                    option.addClass('selected');

                    // Close dropdown
                    selected.removeClass('active');
                    options.removeClass('show');

                    // Create hidden input for form submission
                    const name = dropdown.data('name');
                    let hiddenInput = $(`input[name="${name}"]`);
                    if (hiddenInput.length === 0) {
                        hiddenInput = $('<input type="hidden">').attr('name', name);
                        dropdown.after(hiddenInput);
                    }
                    hiddenInput.val(value);

                    // Trigger change event for validation
                    hiddenInput.trigger('change');
                });

                // Close dropdowns when clicking outside
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.custom-dropdown').length) {
                        $('.dropdown-selected').removeClass('active');
                        $('.dropdown-options').removeClass('show');
                    }
                });

                // Initialize hidden inputs for existing values
                $('.custom-dropdown').each(function() {
                    const dropdown = $(this);
                    const name = dropdown.data('name');
                    const value = dropdown.data('value');
                    const required = dropdown.data('required');

                    // Create hidden input
                    let hiddenInput = $(`input[name="${name}"]`);
                    if (hiddenInput.length === 0) {
                        hiddenInput = $('<input type="hidden">').attr('name', name);
                        if (required) {
                            hiddenInput.attr('required', 'required');
                        }
                        dropdown.after(hiddenInput);
                    }
                    hiddenInput.val(value);

                    // Mark selected option
                    dropdown.find('.dropdown-option').each(function() {
                        if ($(this).data('value') == value) {
                            $(this).addClass('selected');
                        }
                    });
                });

                // Keyboard navigation
                $(document).on('keydown', '.custom-dropdown', function(e) {
                    const dropdown = $(this);
                    const options = dropdown.find('.dropdown-option');
                    const selected = dropdown.find('.dropdown-option.selected');

                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        dropdown.find('.dropdown-selected').click();
                    } else if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        const next = selected.next('.dropdown-option');
                        if (next.length) {
                            next.click();
                        } else {
                            options.first().click();
                        }
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        const prev = selected.prev('.dropdown-option');
                        if (prev.length) {
                            prev.click();
                        } else {
                            options.last().click();
                        }
                    } else if (e.key === 'Escape') {
                        dropdown.find('.dropdown-selected').removeClass('active');
                        dropdown.find('.dropdown-options').removeClass('show');
                    }
                });

                // Make dropdowns focusable for keyboard navigation
                $('.custom-dropdown').attr('tabindex', '0');
            }

            function loadEnvSettings() {
                // Set values from .env
                const envSettings = {
                    mail_mailer: '{{ env("MAIL_MAILER", "smtp") }}',
                    mail_host: '{{ env("MAIL_HOST", "smtp.gmail.com") }}',
                    mail_port: '{{ env("MAIL_PORT", "587") }}',
                    mail_username: '{{ env("MAIL_USERNAME", "") }}',
                    mail_password: '{{ env("MAIL_PASSWORD", "") }}',
                    mail_encryption: '{{ env("MAIL_ENCRYPTION", "tls") }}',
                    mail_from_address: '{{ str_replace('"', '', env("MAIL_FROM_ADDRESS", "")) }}',
                    mail_from_name: '{{ str_replace('"', '', env("MAIL_FROM_NAME", "Dinas Koperasi")) }}'
                };

                // Update form inputs
                $('#mail_host').val(envSettings.mail_host);
                $('#mail_username').val(envSettings.mail_username);
                $('#mailPassword').val(envSettings.mail_password);
                $('#mail_from_address').val(envSettings.mail_from_address);
                $('#mail_from_name').val(envSettings.mail_from_name);

                // Update dropdowns
                updateDropdown('mail_mailer', envSettings.mail_mailer);
                updateDropdown('mail_port', envSettings.mail_port);
                updateDropdown('mail_encryption', envSettings.mail_encryption);

                showNotification('success', 'Konfigurasi dari .env berhasil dimuat');
            }

            function updateDropdown(name, value) {
                const dropdown = $(`.custom-dropdown[data-name="${name}"]`);
                const option = dropdown.find(`.dropdown-option[data-value="${value}"]`);

                if (option.length) {
                    // Update selected text
                    dropdown.find('.selected-text').html(option.html());
                    dropdown.attr('data-value', value);

                    // Update visual state
                    dropdown.find('.dropdown-option').removeClass('selected');
                    option.addClass('selected');

                    // Update hidden input
                    let hiddenInput = $(`input[name="${name}"]`);
                    if (hiddenInput.length === 0) {
                        hiddenInput = $('<input type="hidden">').attr('name', name);
                        dropdown.after(hiddenInput);
                    }
                    hiddenInput.val(value);
                }
            }

            // Custom Tab Functionality - Manual Implementation
            function initCustomTabs() {
                const tabButtons = $('.custom-tab-button');
                const tabContents = $('.tab-pane');
                const indicator = $('.custom-tab-indicator');

                // Set initial indicator position
                updateIndicatorPosition();

                // Tab button click handler
                tabButtons.on('click', function() {
                    const targetTab = $(this).data('tab');

                    // Remove active class from all buttons and contents
                    tabButtons.removeClass('active');
                    tabContents.removeClass('show active').addClass('fade');

                    // Add active class to clicked button
                    $(this).addClass('active');

                    // Show target content with animation
                    const targetContent = $(`#${targetTab}`);
                    setTimeout(() => {
                        targetContent.removeClass('fade').addClass('show active');
                    }, 50);

                    // Update indicator position
                    updateIndicatorPosition();

                    // Update URL without page reload
                    const newUrl = new URL(window.location);
                    newUrl.searchParams.set('tab', targetTab === 'users' ? '' : targetTab);
                    if (targetTab === 'users') {
                        newUrl.searchParams.delete('tab');
                    }
                    window.history.pushState({}, '', newUrl);
                });

                // Update indicator position function
                function updateIndicatorPosition() {
                    const activeButton = $('.custom-tab-button.active');
                    if (activeButton.length) {
                        const buttonOffset = activeButton.offset();
                        const containerOffset = $('.custom-tab-wrapper').offset();
                        const leftPosition = buttonOffset.left - containerOffset.left;
                        const buttonWidth = activeButton.outerWidth();

                        indicator.css({
                            'left': leftPosition + 'px',
                            'width': buttonWidth + 'px'
                        });
                    }
                }

                // Handle browser back/forward
                window.addEventListener('popstate', function() {
                    const urlParams = new URLSearchParams(window.location.search);
                    const tabParam = urlParams.get('tab') || 'users';

                    // Activate correct tab
                    tabButtons.removeClass('active');
                    $(`.custom-tab-button[data-tab="${tabParam}"]`).addClass('active');

                    // Show correct content
                    tabContents.removeClass('show active').addClass('fade');
                    setTimeout(() => {
                        $(`#${tabParam}`).removeClass('fade').addClass('show active');
                    }, 50);

                    updateIndicatorPosition();
                });

                // Initialize from URL parameter
                const urlParams = new URLSearchParams(window.location.search);
                const initialTab = urlParams.get('tab');
                if (initialTab && initialTab !== 'users') {
                    $(`.custom-tab-button[data-tab="${initialTab}"]`).click();
                }

                // Handle window resize
                $(window).on('resize', function() {
                    setTimeout(updateIndicatorPosition, 100);
                });
            }
        </script>
    @endpush

@endsection
