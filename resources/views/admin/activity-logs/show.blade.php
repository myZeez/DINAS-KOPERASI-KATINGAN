@extends('admin.layouts.app')

@section('title', 'Detail Log Aktivitas')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">
                        <i class="fas fa-info-circle me-2"></i>Detail Log Aktivitas
                    </h1>
                    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <!-- Activity Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-info me-2"></i>Informasi Aktivitas
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <strong>Aksi:</strong>
                                    </div>
                                    <div class="col-sm-9">
                                        <span class="badge {{ $activityLog->getActionBadgeClass() }}">
                                            {{ ucfirst($activityLog->action) }}
                                        </span>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <strong>Model:</strong>
                                    </div>
                                    <div class="col-sm-9">
                                        <code>{{ class_basename($activityLog->model_type) }}</code>
                                        <small class="text-muted">(ID: {{ $activityLog->model_id }})</small>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <strong>Deskripsi:</strong>
                                    </div>
                                    <div class="col-sm-9">
                                        {{ $activityLog->formatted_description }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <strong>Waktu:</strong>
                                    </div>
                                    <div class="col-sm-9">
                                        {{ $activityLog->created_at->format('d F Y, H:i:s') }}
                                        <small class="text-muted">({{ $activityLog->created_at->diffForHumans() }})</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Changes -->
                        @if ($activityLog->old_values || $activityLog->new_values)
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-exchange-alt me-2"></i>Perubahan Data
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @if ($activityLog->action === 'create')
                                        <h6 class="text-success">Data Baru:</h6>
                                        @if ($activityLog->new_values)
                                            <div class="bg-light p-3 rounded">
                                                <pre class="mb-0">{{ json_encode(json_decode($activityLog->new_values), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </div>
                                        @endif
                                    @elseif($activityLog->action === 'update')
                                        <div class="row">
                                            @if ($activityLog->old_values)
                                                <div class="col-md-6">
                                                    <h6 class="text-warning">Data Lama:</h6>
                                                    <div class="bg-light p-3 rounded">
                                                        <pre class="mb-0">{{ json_encode(json_decode($activityLog->old_values), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($activityLog->new_values)
                                                <div class="col-md-6">
                                                    <h6 class="text-success">Data Baru:</h6>
                                                    <div class="bg-light p-3 rounded">
                                                        <pre class="mb-0">{{ json_encode(json_decode($activityLog->new_values), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @elseif($activityLog->action === 'delete')
                                        <h6 class="text-danger">Data yang Dihapus:</h6>
                                        @if ($activityLog->old_values)
                                            <div class="bg-light p-3 rounded">
                                                <pre class="mb-0">{{ json_encode(json_decode($activityLog->old_values), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="col-lg-4">
                        <!-- User Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-user me-2"></i>Informasi Admin
                                </h6>
                            </div>
                            <div class="card-body">
                                @if ($activityLog->user)
                                    <div class="text-center mb-3">
                                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                                            style="width: 60px; height: 60px; font-size: 24px;">
                                            {{ substr($activityLog->user->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <h6 class="mb-1">{{ $activityLog->user->name }}</h6>
                                        <p class="text-muted mb-0">{{ $activityLog->user->email }}</p>
                                    </div>
                                @else
                                    <div class="text-center text-muted">
                                        <i class="fas fa-user-slash fa-2x mb-2"></i>
                                        <p class="mb-0">Admin tidak diketahui</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Technical Information -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-cogs me-2"></i>Informasi Teknis
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>IP Address:</strong><br>
                                    <code>{{ $activityLog->ip_address }}</code>
                                </div>

                                @if ($activityLog->user_agent)
                                    <div class="mb-3">
                                        <strong>User Agent:</strong><br>
                                        <small class="text-muted">{{ $activityLog->user_agent }}</small>
                                    </div>
                                @endif

                                <div class="mb-0">
                                    <strong>Timestamp:</strong><br>
                                    <small class="text-muted">{{ $activityLog->created_at->toISOString() }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
