<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Log Aktivitas - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #4facfe;
            --secondary-color: #00f2fe;
            --accent-color: #00ff88;
            --danger-color: #ff6b6b;
            --warning-color: #ffd93d;
            --success-color: #28a745;
            --info-color: #17a2b8;
            --text-primary: rgba(255, 255, 255, 0.95);
            --text-secondary: rgba(255, 255, 255, 0.7);
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --success-color-rgb: 40, 167, 69;
            --warning-color-rgb: 255, 193, 7;
            --danger-color-rgb: 220, 53, 69;
            --info-color-rgb: 13, 202, 240;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: url('{{ asset('Img/BackroudAdmin.jpg') }}') center/cover no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            color: var(--text-primary);
            overflow-x: hidden;
        }

        .main-content {
            margin-top: 80px;
            margin-bottom: 120px;
            padding: 20px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Page Header Styles */
        .page-header {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: var(--text-primary);
            padding: 12px 16px;
            font-size: 1.125rem;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 48px;
            height: 48px;
            text-decoration: none;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 172, 254, 0.3);
            text-decoration: none;
        }

        .page-title {
            color: var(--text-primary);
            font-size: 1.75rem;
            font-weight: 600;
            margin: 0;
            line-height: 1.2;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin: 0.25rem 0 0 0;
            line-height: 1.4;
        }

    /* Detail Card Styles */
    .detail-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .detail-header {
        background: rgba(255, 255, 255, 0.05);
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px 16px 0 0;
    }

    .detail-title {
        color: rgba(255, 255, 255, 0.95);
        font-size: 1.125rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .detail-title i {
        color: #4facfe;
        font-size: 1.25rem;
    }

    .detail-body {
        padding: 1.5rem;
    }

    .detail-row {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
    }

    .detail-row:last-child {
        margin-bottom: 0;
    }

    .detail-label {
        color: rgba(255, 255, 255, 0.7);
        font-weight: 500;
        font-size: 0.875rem;
        min-width: 120px;
        flex: 0 0 auto;
    }

    .detail-value {
        color: rgba(255, 255, 255, 0.95);
        font-size: 0.9rem;
        flex: 1;
        min-width: 0;
    }

    /* Badge Styles */
    .badge-glass {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        border: 1px solid transparent;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .badge-glass.bg-success {
        background: rgba(40, 167, 69, 0.2);
        color: #28a745;
        border-color: rgba(40, 167, 69, 0.3);
    }

    .badge-glass.bg-primary {
        background: rgba(13, 110, 253, 0.2);
        color: #4facfe;
        border-color: rgba(13, 110, 253, 0.3);
    }

    .badge-glass.bg-warning {
        background: rgba(255, 193, 7, 0.2);
        color: #ffd93d;
        border-color: rgba(255, 193, 7, 0.3);
    }

    .badge-glass.bg-info {
        background: rgba(13, 202, 240, 0.2);
        color: #17a2b8;
        border-color: rgba(13, 202, 240, 0.3);
    }

    .badge-glass.bg-danger {
        background: rgba(220, 53, 69, 0.2);
        color: #ff6b6b;
        border-color: rgba(220, 53, 69, 0.3);
    }

    .badge-glass.bg-secondary {
        background: rgba(108, 117, 125, 0.2);
        color: rgba(255, 255, 255, 0.8);
        border-color: rgba(108, 117, 125, 0.3);
    }

    /* Model Code Style */
    .model-code {
        background: rgba(79, 172, 254, 0.1);
        color: #4facfe;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-family: 'Courier New', monospace;
        font-size: 0.8rem;
        border: 1px solid rgba(79, 172, 254, 0.2);
    }

    .model-id {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.8rem;
        margin-left: 0.5rem;
    }

    /* Time Display */
    .timestamp-main {
        color: rgba(255, 255, 255, 0.95);
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .timestamp-relative {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.8rem;
        font-style: italic;
    }

    /* User Profile */
    .user-profile {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .avatar-circle {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1.5rem;
        border: 2px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 4px 12px rgba(79, 172, 254, 0.3);
    }

    .user-info {
        flex: 1;
    }

    .user-name {
        color: rgba(255, 255, 255, 0.95);
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 0.25rem;
    }

    .user-email {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.875rem;
    }

    /* Technical Info */
    .tech-row {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 1.25rem;
    }

    .tech-row:last-child {
        margin-bottom: 0;
    }

    .tech-label {
        color: rgba(255, 255, 255, 0.7);
        font-weight: 500;
        font-size: 0.875rem;
    }

    .ip-code {
        background: rgba(255, 255, 255, 0.08);
        color: rgba(255, 255, 255, 0.95);
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-family: 'Courier New', monospace;
        font-size: 0.875rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .user-agent {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.8125rem;
        line-height: 1.4;
        word-break: break-all;
    }

    .timestamp-iso {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.8125rem;
        font-family: 'Courier New', monospace;
    }

    /* Data Changes Styles */
    .data-comparison {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .data-section {
        border-radius: 8px;
        overflow: hidden;
    }

    .data-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0 0 1rem 0;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 6px;
    }

    .data-title.success {
        background: rgba(40, 167, 69, 0.15);
        color: #28a745;
        border: 1px solid rgba(40, 167, 69, 0.25);
    }

    .data-title.warning {
        background: rgba(255, 193, 7, 0.15);
        color: #ffd93d;
        border: 1px solid rgba(255, 193, 7, 0.25);
    }

    .data-title.danger {
        background: rgba(220, 53, 69, 0.15);
        color: #ff6b6b;
        border: 1px solid rgba(220, 53, 69, 0.25);
    }

    .data-content {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 6px;
        overflow: hidden;
    }

    .data-json {
        margin: 0;
        padding: 1rem;
        font-size: 0.8125rem;
        line-height: 1.5;
        color: rgba(255, 255, 255, 0.95);
        background: transparent;
        border: none;
        font-family: 'Courier New', monospace;
        white-space: pre-wrap;
        word-wrap: break-word;
        overflow-x: auto;
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .data-comparison {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .detail-row {
            flex-direction: column;
            gap: 0.5rem;
        }

        .detail-label {
            flex: none;
        }
    }

    @media (max-width: 768px) {
        .detail-header,
        .detail-body {
            padding: 1rem;
        }

        .user-profile {
            flex-direction: column;
            text-align: center;
            gap: 0.75rem;
        }

        .avatar-circle {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }

        .tech-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }
</style>
</head>
<body>
    <main class="main-content">
        <!-- Page Header with Back Button -->
        <div class="page-header">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.activity-logs.index') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="page-title">Detail Log Aktivitas</h1>
                    <p class="page-subtitle">Informasi lengkap aktivitas admin dalam sistem</p>
                </div>
            </div>
        </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Activity Information -->
            <div class="detail-card mb-4">
                <div class="detail-header">
                    <h6 class="detail-title">
                        <i class="fas fa-info"></i>
                        <span>Informasi Aktivitas</span>
                    </h6>
                </div>
                <div class="detail-body">
                    <div class="detail-row">
                        <div class="detail-label">Aksi:</div>
                        <div class="detail-value">
                            <span class="badge-glass {{ $activityLog->action_badge_class }}">
                                {{ ucfirst($activityLog->action) }}
                            </span>
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Model:</div>
                        <div class="detail-value">
                            <code class="model-code">{{ class_basename($activityLog->model_type) }}</code>
                            <span class="model-id">ID: {{ $activityLog->model_id }}</span>
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Deskripsi:</div>
                        <div class="detail-value">{{ $activityLog->description }}</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Waktu:</div>
                        <div class="detail-value">
                            <div class="timestamp-main">{{ $activityLog->created_at->format('d F Y, H:i:s') }}</div>
                            <div class="timestamp-relative">{{ $activityLog->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Changes -->
            @if($activityLog->old_values || $activityLog->new_values)
            <div class="detail-card mb-4">
                <div class="detail-header">
                    <h6 class="detail-title">
                        <i class="fas fa-exchange-alt"></i>
                        <span>Perubahan Data</span>
                    </h6>
                </div>
                <div class="detail-body">
                    @if($activityLog->action === 'create')
                        <div class="data-section data-new">
                            <h6 class="data-title success">
                                <i class="fas fa-plus-circle"></i>
                                Data Baru
                            </h6>
                            @if($activityLog->new_values)
                                <div class="data-content">
                                    <pre class="data-json">{{ json_encode($activityLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            @endif
                        </div>
                    @elseif($activityLog->action === 'update')
                        <div class="data-comparison">
                            @if($activityLog->old_values)
                                <div class="data-section data-old">
                                    <h6 class="data-title warning">
                                        <i class="fas fa-minus-circle"></i>
                                        Data Lama
                                    </h6>
                                    <div class="data-content">
                                        <pre class="data-json">{{ json_encode($activityLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                </div>
                            @endif
                            @if($activityLog->new_values)
                                <div class="data-section data-new">
                                    <h6 class="data-title success">
                                        <i class="fas fa-plus-circle"></i>
                                        Data Baru
                                    </h6>
                                    <div class="data-content">
                                        <pre class="data-json">{{ json_encode($activityLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @elseif($activityLog->action === 'delete')
                        <div class="data-section data-deleted">
                            <h6 class="data-title danger">
                                <i class="fas fa-trash-alt"></i>
                                Data yang Dihapus
                            </h6>
                            @if($activityLog->old_values)
                                <div class="data-content">
                                    <pre class="data-json">{{ json_encode($activityLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Admin Information -->
            <div class="detail-card mb-4">
                <div class="detail-header">
                    <h6 class="detail-title">
                        <i class="fas fa-user-shield"></i>
                        <span>Informasi Admin</span>
                    </h6>
                </div>
                <div class="detail-body">
                    @if($activityLog->user)
                        <div class="user-profile">
                            <div class="avatar-circle">
                                {{ strtoupper(substr($activityLog->user->name, 0, 1)) }}
                            </div>
                            <div class="user-info">
                                <div class="user-name">{{ $activityLog->user->name }}</div>
                                <div class="user-email">{{ $activityLog->user->email }}</div>
                            </div>
                        </div>
                    @else
                        <div class="user-profile" style="justify-content: center; flex-direction: column; text-align: center; padding: 1rem 0;">
                            <div class="avatar-circle">
                                <i class="fas fa-user-slash" style="font-size: 2rem; color: rgba(255, 255, 255, 0.7);"></i>
                            </div>
                            <div class="user-info">
                                <div class="user-name">Admin tidak diketahui</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Technical Information -->
            <div class="detail-card">
                <div class="detail-header">
                    <h6 class="detail-title">
                        <i class="fas fa-cog"></i>
                        <span>Informasi Teknis</span>
                    </h6>
                </div>
                <div class="detail-body">
                    <div class="tech-row">
                        <div class="tech-label">IP Address:</div>
                        <div class="tech-value">
                            <code class="ip-code">{{ $activityLog->ip_address ?? '127.0.0.1' }}</code>
                        </div>
                    </div>

                    @if($activityLog->user_agent)
                    <div class="tech-row">
                        <div class="tech-label">User Agent:</div>
                        <div class="tech-value">
                            <div class="user-agent">{{ $activityLog->user_agent }}</div>
                        </div>
                    </div>
                    @endif

                    <div class="tech-row">
                        <div class="tech-label">Timestamp:</div>
                        <div class="tech-value">
                            <div class="timestamp-iso">{{ $activityLog->created_at->toISOString() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Back button functionality with keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // ESC key to go back
            if (e.key === 'Escape') {
                window.location.href = '{{ route("admin.activity-logs.index") }}';
            }
            // Alt + Left Arrow to go back
            if (e.altKey && e.key === 'ArrowLeft') {
                e.preventDefault();
                window.location.href = '{{ route("admin.activity-logs.index") }}';
            }
        });
    });
    </script>
</body>
</html>
