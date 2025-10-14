@extends('admin.layouts.app')

@section('title', 'Profil Dinas')

@section('content')
    <div class="container-fluid">
        @include('admin.partials.page-header', [
            'title' => 'Profil Dinas',
            'subtitle' => 'Kelola informasi profil dan identitas dinas',
            'icon' => 'fas fa-building',
            'actions' => [
                [
                    'label' => 'Edit Profil',
                    'icon' => 'fas fa-edit',
                    'class' => 'btn-primary-glass',
                    'attributes' => 'data-bs-toggle="modal" data-bs-target="#editProfileModal"',
                ],
            ],
        ])

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h6><i class="fas fa-exclamation-circle me-2"></i>Terjadi kesalahan:</h6>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Container Utama -->
        <div class="row">
            <!-- Div Informasi Profil -->
            <div class="col-md-8">
                <div class="glass-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">
                            <i class="fas fa-edit"></i> Informasi Profil
                        </h5>
                        <button type="button" class="btn btn-primary-glass btn-sm" data-bs-toggle="modal"
                            data-bs-target="#editProfileModal">
                            <i class="fas fa-edit"></i> Edit Profil
                        </button>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Nama Dinas</label>
                            <div class="profile-info">{{ $profile->name ?? 'Belum diatur' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Kepala Dinas</label>
                            <div class="profile-info">
                                @if ($profile->head_name)
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <strong>{{ $profile->head_name }}</strong>
                                            @if ($profile->head_position)
                                                <br><small class="text-muted">{{ $profile->head_position }}</small>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">Belum ada data di struktur organisasi</span>
                                    <br><small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Tambahkan "Kepala Dinas" di halaman Struktur Organisasi
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Alamat Lengkap</label>
                        <div class="profile-info">{{ $profile->address ?? 'Belum diatur' }}</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Nomor Telepon</label>
                            <div class="profile-info">{{ $profile->phone ?? 'Belum diatur' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Email Resmi</label>
                            <div class="profile-info">{{ $profile->email ?? 'Belum diatur' }}</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Visi</label>
                        <div class="profile-info">{{ $profile->vision ?? 'Belum diatur' }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Misi</label>
                        <div class="profile-info">{{ $profile->mission ?? 'Belum diatur' }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Detail Profil</label>
                        <div class="profile-info">{{ $profile->detail ?? 'Belum diatur' }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Quotes/Motto</label>
                        <div class="profile-info">{{ $profile->quotes ?? 'Belum diatur' }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Tujuan</label>
                        <div class="profile-info">{{ $profile->tujuan ?? 'Belum diatur' }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Tentang</label>
                        <div class="profile-info">{{ Str::limit($profile->tentang ?? 'Belum diatur', 200) }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Tugas Pokok</label>
                        <div class="profile-info">{{ Str::limit($profile->tugas_pokok ?? 'Belum diatur', 200) }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Peran</label>
                        <div class="profile-info">{{ Str::limit($profile->peran ?? 'Belum diatur', 200) }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Fokus Utama</label>
                        <div class="profile-info">{{ Str::limit($profile->fokus_utama ?? 'Belum diatur', 200) }}</div>
                    </div>
                </div>
            </div>

            <!-- Div More Informasi -->
            <div class="col-md-4">
                <!-- Card Logo Dinas -->
                <div class="glass-card text-center mb-3">
                    <h6 class="mb-3">
                        <i class="fas fa-image"></i> Logo Dinas
                    </h6>

                    <div class="logo-display mb-3">
                        @if ($profile->logo && Storage::disk('public')->exists($profile->logo))
                            <img src="{{ Storage::url($profile->logo) }}" alt="Logo Dinas" class="img-fluid rounded shadow"
                                style="max-width: 120px; max-height: 120px; object-fit: cover;"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <div class="text-center text-muted py-3" style="display: none;">
                                <i class="fas fa-exclamation-triangle fa-2x mb-2 text-warning"></i>
                                <p class="mb-0">Logo tidak dapat dimuat</p>
                            </div>
                        @elseif($profile->logo)
                            <div class="text-center text-muted py-3">
                                <i class="fas fa-exclamation-triangle fa-2x mb-2 text-warning"></i>
                                <p class="mb-0">Logo tidak ditemukan</p>
                                <small>{{ $profile->logo }}</small>
                            </div>
                        @else
                            <div class="text-center text-muted py-3">
                                <i class="fas fa-image fa-2x mb-2"></i>
                                <p class="mb-0">Logo belum diupload</p>
                            </div>
                        @endif
                    </div>

                    <form action="{{ route('admin.profile.logo.upload') }}" method="POST" enctype="multipart/form-data"
                        style="display: inline-block;" id="logoUploadForm">
                        @csrf
                        <input type="file" name="logo" class="d-none" accept="image/*" id="logoInput"
                            onchange="submitLogoForm()">
                        <button type="button" class="btn btn-primary-glass btn-sm"
                            onclick="document.getElementById('logoInput').click()">
                            <i class="fas fa-upload"></i> {{ $profile->logo ? 'Ganti Logo' : 'Upload Logo' }}
                        </button>
                    </form>
                </div>

                <!-- Card Informasi Tambahan -->
                <div class="glass-card mb-3">
                    <h6 class="mb-3">
                        <i class="fas fa-info-circle"></i> Informasi Tambahan
                    </h6>
                    <div class="text-muted">
                        <p class="mb-2"><i class="fas fa-clock me-2"></i>Terakhir diperbarui:
                            {{ $profile->updated_at ? $profile->updated_at->format('d M Y H:i') : 'Belum pernah' }}
                        </p>
                        <p class="mb-2"><i class="fas fa-calendar me-2"></i>Dibuat:
                            {{ $profile->created_at ? $profile->created_at->format('d M Y') : 'Tidak diketahui' }}
                        </p>
                        @if ($profile->latitude && $profile->longitude)
                            <p class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Koordinat tersedia</p>
                        @else
                            <p class="mb-0"><i class="fas fa-exclamation-triangle me-2 text-warning"></i>Koordinat belum
                                diatur</p>
                        @endif
                    </div>
                </div>

                <!-- Card Lokasi Dinas -->
                <div class="glass-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">
                            <i class="fas fa-map-marker-alt"></i> Lokasi Dinas
                        </h6>
                        @if ($profile->latitude && $profile->longitude)
                            <button type="button" class="btn btn-primary-glass btn-sm" data-bs-toggle="modal"
                                data-bs-target="#locationModal">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        @endif
                    </div>

                    @if ($profile->latitude && $profile->longitude)
                        <div class="map-container mb-3">
                            <div class="admin-map-container custom-map" style="height: 200px;">
                                <span class="map-badge"><i class="fas fa-map-marker-alt me-1"></i>Lokasi</span>
                                <iframe
                                    src="https://www.google.com/maps?q={{ $profile->latitude }},{{ $profile->longitude }}&hl=id&z=15&output=embed"
                                    allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                            <div class="text-center mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-map-pin me-1"></i>
                                    {{ number_format($profile->latitude, 6) }},
                                    {{ number_format($profile->longitude, 6) }}
                                    <a href="https://www.google.com/maps?q={{ $profile->latitude }},{{ $profile->longitude }}"
                                        target="_blank" class="ms-2">Buka di Google Maps</a>
                                </small>
                            </div>
                        </div>
                    @elseif ($profile->address)
                        <div class="map-container mb-3">
                            <div class="admin-map-container custom-map" style="height: 200px;">
                                <iframe
                                    src="https://www.google.com/maps?q={{ urlencode($profile->address) }}&hl=id&z=15&output=embed"
                                    allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                            <div class="text-center mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Menampilkan berdasarkan alamat. Simpan koordinat untuk akurasi maksimal.
                                    <a href="https://www.google.com/maps?q={{ urlencode($profile->address) }}"
                                        target="_blank" class="ms-2">Buka di Google Maps</a>
                                </small>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-info-glass btn-sm flex-fill"
                                onclick="getCurrentLocation(event)">
                                <i class="fas fa-location-arrow"></i> Lokasi Sekarang
                            </button>
                            <button type="button" class="btn btn-warning-glass btn-sm flex-fill" data-bs-toggle="modal"
                                data-bs-target="#manualLocationModal">
                                <i class="fas fa-edit"></i> Input Manual
                            </button>
                        </div>
                    @else
                        <div class="text-center text-muted py-3 mb-3">
                            <i class="fas fa-map fa-2x mb-2"></i>
                            <p class="mb-0">Lokasi belum diatur</p>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-info-glass btn-sm flex-fill"
                                onclick="getCurrentLocation(event)">
                                <i class="fas fa-location-arrow"></i> Lokasi Sekarang
                            </button>
                            <button type="button" class="btn btn-primary-glass btn-sm flex-fill" data-bs-toggle="modal"
                                data-bs-target="#manualLocationModal">
                                <i class="fas fa-map-pin"></i> Input Manual
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Modal Edit Profile -->
        <div class="modal fade" id="editProfileModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content modal-glass">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-edit"></i> Edit Profil Dinas
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Nama Dinas</label>
                                    <input type="text" name="name" class="form-control form-control-glass"
                                        value="{{ old('name', $profile->name) }}" required>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Info:</strong> Data Kepala Dinas akan otomatis diambil dari halaman Struktur
                                Organisasi.
                                Pastikan sudah menambahkan posisi "Kepala Dinas" di halaman struktur.
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Alamat Lengkap</label>
                                <textarea name="address" class="form-control form-control-glass" rows="3">{{ old('address', $profile->address) }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nomor Telepon</label>
                                    <input type="tel" name="phone" class="form-control form-control-glass"
                                        value="{{ old('phone', $profile->phone) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email Resmi</label>
                                    <input type="email" name="email" class="form-control form-control-glass"
                                        value="{{ old('email', $profile->email) }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Visi</label>
                                <textarea name="vision" class="form-control form-control-glass" rows="3">{{ old('vision', $profile->vision) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Misi</label>
                                <textarea name="mission" class="form-control form-control-glass" rows="4">{{ old('mission', $profile->mission) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Detail Profil</label>
                                <textarea name="detail" class="form-control form-control-glass" rows="5"
                                    placeholder="Masukkan detail lengkap tentang dinas, sejarah, tugas pokok, fungsi, dan informasi penting lainnya...">{{ old('detail', $profile->detail) }}</textarea>
                                <div class="form-text">Informasi detail tentang dinas yang akan ditampilkan di halaman
                                    utama
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Quotes/Motto</label>
                                <textarea name="quotes" class="form-control form-control-glass" rows="3"
                                    placeholder="Masukkan quotes, motto, atau kata-kata inspiratif dinas...">{{ old('quotes', $profile->quotes) }}</textarea>
                                <div class="form-text">Quotes atau motto yang akan ditampilkan sebagai inspirasi
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tujuan</label>
                                <textarea name="tujuan" class="form-control form-control-glass" rows="4"
                                    placeholder="Masukkan tujuan utama organisasi...">{{ old('tujuan', $profile->tujuan) }}</textarea>
                                <div class="form-text">Tujuan utama yang ingin dicapai oleh organisasi</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tentang</label>
                                <textarea name="tentang" class="form-control form-control-glass" rows="5"
                                    placeholder="Masukkan informasi tentang organisasi secara detail...">{{ old('tentang', $profile->tentang) }}</textarea>
                                <div class="form-text">Informasi lengkap tentang sejarah, profil, dan karakteristik
                                    organisasi</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tugas Pokok</label>
                                <textarea name="tugas_pokok" class="form-control form-control-glass" rows="4"
                                    placeholder="Masukkan tugas pokok dan fungsi organisasi...">{{ old('tugas_pokok', $profile->tugas_pokok) }}</textarea>
                                <div class="form-text">Tugas pokok dan fungsi utama yang dijalankan</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Peran</label>
                                <textarea name="peran" class="form-control form-control-glass" rows="4"
                                    placeholder="Masukkan peran organisasi dalam masyarakat...">{{ old('peran', $profile->peran) }}</textarea>
                                <div class="form-text">Peran strategis organisasi dalam pembangunan dan masyarakat</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Fokus Utama</label>
                                <textarea name="fokus_utama" class="form-control form-control-glass" rows="4"
                                    placeholder="Masukkan fokus utama dan prioritas organisasi...">{{ old('fokus_utama', $profile->fokus_utama) }}</textarea>
                                <div class="form-text">Area fokus utama dan prioritas kerja saat ini</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-glass" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary-glass">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Lokasi -->
        <div class="modal fade" id="locationModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content modal-glass">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-map-marker-alt"></i> Edit Lokasi Kantor
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Hidden fields to preserve existing data -->
                        <input type="hidden" name="name" value="{{ $profile->name }}">
                        <input type="hidden" name="address" value="{{ $profile->address }}">
                        <input type="hidden" name="phone" value="{{ $profile->phone }}">
                        <input type="hidden" name="email" value="{{ $profile->email }}">
                        <input type="hidden" name="vision" value="{{ $profile->vision }}">
                        <input type="hidden" name="mission" value="{{ $profile->mission }}">
                        <input type="hidden" name="detail" value="{{ $profile->detail }}">
                        @if ($profile->operating_hours)
                            @foreach ($profile->operating_hours as $key => $value)
                                <input type="hidden" name="operating_hours[{{ $key }}]"
                                    value="{{ $value }}">
                            @endforeach
                        @endif

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Latitude</label>
                                <input type="number" name="latitude" step="any"
                                    class="form-control form-control-glass"
                                    value="{{ old('latitude', $profile->latitude) }}" placeholder="-6.2000">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Longitude</label>
                                <input type="number" name="longitude" step="any"
                                    class="form-control form-control-glass"
                                    value="{{ old('longitude', $profile->longitude) }}" placeholder="106.8000">
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                Gunakan Google Maps untuk mendapatkan koordinat yang akurat
                            </small>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-glass" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary-glass">
                                <i class="fas fa-save"></i> Simpan Lokasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Input Manual Lokasi -->
        <div class="modal fade" id="manualLocationModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content modal-glass">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-map-pin"></i> Input Koordinat Manual
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Hidden fields to preserve existing data -->
                        <input type="hidden" name="name" value="{{ $profile->name }}">
                        <input type="hidden" name="address" value="{{ $profile->address }}">
                        <input type="hidden" name="phone" value="{{ $profile->phone }}">
                        <input type="hidden" name="email" value="{{ $profile->email }}">
                        <input type="hidden" name="vision" value="{{ $profile->vision }}">
                        <input type="hidden" name="mission" value="{{ $profile->mission }}">
                        <input type="hidden" name="detail" value="{{ $profile->detail }}">
                        @if ($profile->operating_hours)
                            @foreach ($profile->operating_hours as $key => $value)
                                <input type="hidden" name="operating_hours[{{ $key }}]"
                                    value="{{ $value }}">
                            @endforeach
                        @endif

                        <div class="modal-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Tips:</strong> Gunakan Google Maps atau GPS untuk mendapatkan koordinat yang akurat
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Latitude</label>
                                <input type="number" name="latitude" step="any"
                                    class="form-control form-control-glass"
                                    value="{{ old('latitude', $profile->latitude) }}" placeholder="Contoh: -6.2087634"
                                    required>
                                <small class="text-muted">Koordinat lintang (utara/selatan)</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Longitude</label>
                                <input type="number" name="longitude" step="any"
                                    class="form-control form-control-glass"
                                    value="{{ old('longitude', $profile->longitude) }}" placeholder="Contoh: 106.8456231"
                                    required>
                                <small class="text-muted">Koordinat bujur (timur/barat)</small>
                            </div>

                            <div class="text-center">
                                <button type="button" class="btn btn-info-glass btn-sm" onclick="getCurrentLocation()">
                                    <i class="fas fa-location-arrow"></i> Gunakan Lokasi Saat Ini
                                </button>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-glass" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary-glass">
                                <i class="fas fa-save"></i> Simpan Koordinat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

    @push('styles')
        <style>
            .profile-info {
                background: rgba(255, 255, 255, 0.1);
                padding: 12px 15px;
                border-radius: 8px;
                border: 1px solid rgba(255, 255, 255, 0.1);
                min-height: 45px;
                color: var(--text-primary);
            }

            .logo-display {
                min-height: 150px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            /* Map containers */
            .map-container {
                padding: 0 !important;
                min-height: 0 !important;
                overflow: hidden;
                border-radius: 12px;
            }

            .admin-map-container {
                position: relative;
                width: 100%;
                height: 225px;
                /* match public page height for parity */
                overflow: hidden;
                border-radius: 12px;
            }

            .admin-map-container iframe {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                border: 0;
            }

            /* Reuse custom map visual */
            .custom-map {
                background: linear-gradient(135deg, rgba(13, 110, 253, .08), rgba(25, 135, 84, .08));
                box-shadow: 0 10px 30px rgba(0, 0, 0, .12), inset 0 0 0 1px rgba(255, 255, 255, .06);
                border: 1px solid rgba(13, 110, 253, .25);
            }

            .custom-map::after {
                content: "";
                position: absolute;
                inset: 0;
                border-radius: inherit;
                pointer-events: none;
                background: radial-gradient(80% 50% at 10% 10%, rgba(13, 110, 253, .12), transparent 60%),
                    radial-gradient(60% 40% at 90% 20%, rgba(25, 135, 84, .12), transparent 60%);
            }

            .custom-map .map-badge {
                position: absolute;
                top: 10px;
                left: 10px;
                z-index: 2;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 6px 10px;
                font-size: .8rem;
                color: #0d6efd;
                background: rgba(255, 255, 255, .9);
                border: 1px solid rgba(13, 110, 253, .25);
                border-radius: 999px;
                backdrop-filter: blur(6px);
                box-shadow: 0 4px 16px rgba(0, 0, 0, .08);
            }

            @media (max-width: 576px) {
                .admin-map-container {
                    height: 320px;
                }
            }

            /* Ensure glass buttons have white text for readability */
            .btn-primary-glass,
            .btn-warning-glass,
            .btn-info-glass,
            .btn-glass,
            .btn-primary-glass:hover,
            .btn-warning-glass:hover,
            .btn-info-glass:hover,
            .btn-glass:hover,
            .btn-primary-glass:focus,
            .btn-warning-glass:focus,
            .btn-info-glass:focus,
            .btn-glass:focus {
                color: #fff !important;
                fill: #fff !important;
            }

            .btn-primary-glass:disabled,
            .btn-warning-glass:disabled,
            .btn-info-glass:disabled,
            .btn-glass:disabled,
            .btn-primary-glass.disabled,
            .btn-warning-glass.disabled,
            .btn-info-glass.disabled,
            .btn-glass.disabled {
                color: rgba(255, 255, 255, 0.8) !important;
                fill: rgba(255, 255, 255, 0.8) !important;
            }

            .btn-primary-glass .fa,
            .btn-primary-glass .fas,
            .btn-warning-glass .fa,
            .btn-warning-glass .fas,
            .btn-info-glass .fa,
            .btn-info-glass .fas,
            .btn-glass .fa,
            .btn-glass .fas {
                color: inherit !important;
                fill: inherit !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            function getCurrentLocation(evt) {
                const btn = (evt && evt.target) ? evt.target : document.querySelector('[onclick*="getCurrentLocation"]');
                if (!btn) return;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengambil lokasi...';
                btn.disabled = true;

                if (!navigator.geolocation) {
                    showToast('Geolocation tidak didukung oleh browser ini.', 'error');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        // Update inputs in both modals if present
                        document.querySelectorAll('input[name="latitude"]').forEach(i => i.value = lat.toFixed(6));
                        document.querySelectorAll('input[name="longitude"]').forEach(i => i.value = lng.toFixed(6));

                        updateLocationCoordinates(lat, lng, btn, originalText);
                    },
                    function(error) {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                        let msg = 'Gagal mengambil lokasi: ';
                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                msg += 'Izin lokasi ditolak.';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                msg += 'Informasi lokasi tidak tersedia.';
                                break;
                            case error.TIMEOUT:
                                msg += 'Waktu habis saat mengambil lokasi.';
                                break;
                            default:
                                msg += 'Terjadi kesalahan yang tidak diketahui.';
                                break;
                        }
                        showToast(msg, 'error');
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 60000
                    }
                );
            }

            function updateLocationCoordinates(lat, lng, btn, originalText) {
                fetch('{{ route('admin.profile.update-location') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            latitude: lat,
                            longitude: lng
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                        if (data.success) {
                            showToast('Lokasi berhasil diperbarui!\nLatitude: ' + lat.toFixed(6) + '\nLongitude: ' + lng
                                .toFixed(6), 'success');
                            setTimeout(() => window.location.reload(), 1200);
                        } else {
                            showToast('Gagal menyimpan lokasi: ' + (data.message || 'Unknown error'), 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                        showToast('Terjadi kesalahan saat menyimpan lokasi.', 'error');
                    });
            }

            function submitLogoForm() {
                const form = document.getElementById('logoUploadForm');
                const formData = new FormData(form);

                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message, 'success');
                            location.reload();
                        } else {
                            showToast('Error: ' + data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Terjadi kesalahan saat upload logo.', 'error');
                    });
            }
        </script>
    @endpush
