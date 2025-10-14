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

        <!-- Account List -->
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

    @push('scripts')
        <script>
            // Ensure jQuery is loaded and DOM is ready
            $(document).ready(function() {
                console.log('jQuery loaded and DOM ready');

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
        </script>
    @endpush

@endsection
