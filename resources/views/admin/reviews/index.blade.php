@extends('admin.layouts.app')

@section('title', 'Kelola Ulasan')

@section('content')
    <div class="container-fluid">
        @include('admin.partials.page-header', [
            'title' => 'Kelola Ulasan',
            'subtitle' => 'Monitor dan moderasi testimoni serta ulasan masyarakat tentang layanan Dinas Koperasi',
            'icon' => 'fas fa-star',
            'breadcrumb' => 'Reviews',
            'quickStats' => [
                ['value' => $averageRating ?? 0, 'label' => 'Avg Rating', 'icon' => 'fas fa-star'],
                ['value' => $totalReviews ?? 0, 'label' => 'Total Reviews', 'icon' => 'fas fa-comments'],
                ['value' => $pendingReviews ?? 0, 'label' => 'Pending', 'icon' => 'fas fa-clock'],
            ],
        ])

        <div class="row mb-4">
            <div class="col-md-8">
                <div class="glass-card">
                    <div class="d-flex gap-3 align-items-center flex-wrap">
                        <span class="btn btn-primary-glass">
                            <i class="fas fa-star"></i> Semua ({{ $totalReviews }})
                        </span>
                        <span class="btn btn-glass">
                            <i class="fas fa-eye"></i> Ditampilkan
                            ({{ \App\Models\Review::where('is_visible', true)->count() }})
                        </span>
                        <span class="btn btn-glass">
                            <i class="fas fa-eye-slash text-danger"></i> Tersembunyi
                            ({{ \App\Models\Review::where('is_visible', false)->count() }})
                        </span>
                        <span class="btn btn-glass">
                            <i class="fas fa-clock"></i> Pending ({{ $pendingReviews }})
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card text-center">
                    <div style="font-size: 24px; font-weight: 700; color: var(--accent-color);">
                        {{ number_format($averageRating, 1) }}</div>
                    <div class="mb-2">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($averageRating))
                                <i class="fas fa-star text-warning"></i>
                            @else
                                <i class="far fa-star" style="color: var(--text-secondary);"></i>
                            @endif
                        @endfor
                    </div>
                    <div style="font-size: 12px; color: var(--text-secondary);">Rating Rata-rata</div>
                </div>
            </div>
        </div>

        <!-- Reviews List -->
        <div class="row">
            @forelse($reviews as $review)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="glass-card h-100 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center">
                                <div
                                    style="width: 48px; height: 48px; background: linear-gradient(135deg, #00ff88, #6bcf7f); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; margin-right: 12px;">
                                    {{ strtoupper(substr($review->name ?: 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $review->name ?? 'Anonim' }}</div>
                                    <div style="font-size: 12px; color: var(--text-secondary);">
                                        {{ optional($review->created_at)->format('d M Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 flex-grow-1">
                            <div class="d-flex align-items-center mb-2">
                                @for ($j = 1; $j <= 5; $j++)
                                    @if ($j <= (int) $review->rating)
                                        <i class="fas fa-star text-warning me-1"></i>
                                    @else
                                        <i class="far fa-star me-1" style="color: var(--text-secondary);"></i>
                                    @endif
                                @endfor
                                <span class="ms-2"
                                    style="font-size: 14px; font-weight: 600;">{{ number_format((float) $review->rating, 1) }}</span>
                            </div>
                            <p
                                style="color: var(--text-secondary); line-height: 1.6; margin-bottom: 0; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical;">
                                {{ $review->comment }}</p>

                            @if ($review->service)
                                <div class="mt-2">
                                    <span class="badge bg-info">{{ $review->service }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="mt-auto">
                            <div class="d-flex flex-wrap gap-1 mb-2">
                                @if ($review->is_visible)
                                    <span class="badge-glass"
                                        style="background: rgba(0, 255, 136, 0.2); color: var(--accent-color); font-size: 10px;">
                                        <i class="fas fa-eye"></i> Ditampilkan
                                    </span>
                                @else
                                    <span class="badge-glass"
                                        style="background: rgba(255, 215, 61, 0.2); color: var(--warning-color); font-size: 10px;">
                                        <i class="fas fa-eye-slash"></i> Disembunyikan
                                    </span>
                                @endif

                                @if ($review->is_verified)
                                    <span class="badge-glass"
                                        style="background: rgba(108, 207, 127, 0.2); color: var(--info-color); font-size: 10px;">
                                        <i class="fas fa-certificate"></i> Verified
                                    </span>
                                @endif

                                @if ($review->status === 'pending')
                                    <span class="badge-glass"
                                        style="background: rgba(255, 215, 61, 0.2); color: var(--warning-color); font-size: 10px;">
                                        <i class="fas fa-clock"></i> Pending
                                    </span>
                                @endif
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                @if ($review->status === 'pending')
                                    <button class="btn btn-sm review-action" data-action="approve" data-method="PATCH"
                                        data-url="{{ route('admin.reviews.approve', $review) }}"
                                        style="background: rgba(0, 255, 136, 0.2); color: var(--accent-color); border: none; border-radius: 6px;"
                                        title="Setujui Ulasan">
                                        <i class="fas fa-check"></i>
                                    </button>
                                @endif

                                <button type="button" class="btn btn-sm email-reply-btn" data-email="{{ $review->email }}"
                                    data-name="{{ $review->name }}" data-comment="{{ $review->comment }}"
                                    style="background: rgba(108, 207, 127, 0.2); color: var(--info-color); border: none; border-radius: 6px;"
                                    title="Balas via Email">
                                    <i class="fas fa-reply"></i>
                                </button>

                                <button class="btn btn-sm review-action" data-action="toggle" data-method="PATCH"
                                    data-url="{{ route('admin.reviews.toggle-visibility', $review) }}"
                                    style="background: rgba(255, 215, 61, 0.2); color: var(--warning-color); border: none; border-radius: 6px;"
                                    title="{{ $review->is_visible ? 'Sembunyikan' : 'Tampilkan' }}">
                                    <i class="fas fa-eye{{ $review->is_visible ? '' : '-slash' }}"></i>
                                </button>

                                <button class="btn btn-sm review-action" data-action="delete" data-method="DELETE"
                                    data-url="{{ route('admin.reviews.destroy', $review) }}"
                                    style="background: rgba(255, 107, 107, 0.2); color: var(--danger-color); border: none; border-radius: 6px;"
                                    title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="glass-card text-center text-muted">Belum ada ulasan.</div>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-end mt-3">
            {{ $reviews->links() }}
        </div>
    </div>
    @push('scripts')
        <script>
            (function() {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                $(document).on('click', '.review-action', function(e) {
                    e.preventDefault();
                    const $btn = $(this);
                    const url = $btn.data('url');
                    const method = ($btn.data('method') || 'PATCH').toUpperCase();
                    const action = $btn.data('action');

                    if (method === 'DELETE') {
                        if (!confirm('Hapus ulasan ini?')) return;
                    } else if (action === 'approve') {
                        if (!confirm('Setujui ulasan ini? Ulasan akan ditampilkan di halaman public.')) return;
                    }

                    $btn.prop('disabled', true).addClass('disabled');
                    $.ajax({
                        url: url,
                        type: method,
                        headers: {
                            'X-CSRF-TOKEN': token
                        },
                        success: function() {
                            window.location.reload();
                        },
                        error: function(xhr) {
                            const msg = (xhr.responseJSON && xhr.responseJSON.message) || xhr
                                .statusText || 'Gagal memproses aksi';
                            alert(msg);
                        },
                        complete: function() {
                            $btn.prop('disabled', false).removeClass('disabled');
                        }
                    });
                });

                // Handle email reply buttons (open Gmail compose, fallback to mailto)
                $(document).on('click', '.email-reply-btn', function(e) {
                    e.preventDefault();
                    const $btn = $(this);
                    const email = ($btn.data('email') || '').toString();
                    const name = ($btn.data('name') || '').toString();
                    const comment = ($btn.data('comment') || '').toString();

                    const subject = 'Re: Ulasan Anda di Dinas Koperasi';
                    const body =
                        `Halo ${name},\n\nTerima kasih atas ulasan Anda: "${comment}"\n\nSalam hormat,\nDinas Koperasi`;

                    const gmailUrl =
                        `https://mail.google.com/mail/?view=cm&fs=1&to=${encodeURIComponent(email)}&su=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
                    const mailtoLink =
                        `mailto:${email}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;

                    // Prefer Gmail compose in a new tab
                    let win = null;
                    try {
                        win = window.open(gmailUrl, '_blank');
                    } catch (_) {}

                    // If blocked or failed, fallback to mailto
                    if (!win || win.closed || typeof win.closed === 'undefined') {
                        window.location.href = mailtoLink;
                    }
                });
            })();
        </script>
    @endpush

@endsection
