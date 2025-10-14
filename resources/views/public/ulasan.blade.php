@extends('public.layouts.app')

@section('title', 'Ulasan & Testimoni')

@section('content')
    <!-- Hero Section -->
    <section class="reviews-hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <div class="hero-content">
                        <div class="hero-badge mb-3">
                            <i class="fas fa-comments me-2"></i>
                            Testimoni Masyarakat
                        </div>
                        <h1 class="hero-title mb-4">Ulasan & Testimoni</h1>
                        <p class="hero-subtitle mb-4">
                            Dengarkan pengalaman dan testimoni dari masyarakat yang telah merasakan layanan terbaik kami
                        </p>
                        <div class="hero-actions">
                            <button class="hero-btn" data-bs-toggle="modal" data-bs-target="#reviewModal">
                                <i class="fas fa-pen me-2"></i>
                                <span>Bagikan Pengalaman Anda</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="section-modern scroll-reveal">
        <div class="container">
            <!-- Alert Messages -->
            @if (session('success'))
                <div class="alert alert-success-modern alert-dismissible fade show" role="alert">
                    <div class="alert-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="alert-content">
                        <strong>Berhasil!</strong> {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close-modern" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger-modern alert-dismissible fade show" role="alert">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="alert-content">
                        <strong>Error!</strong> {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close-modern" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="text-center mb-5">
                <h2 class="section-title">Apa Kata Mereka?</h2>
                <p class="section-subtitle">Testimoni nyata dari masyarakat yang telah merasakan layanan terbaik kami</p>
            </div>

            @if ($reviews->count() > 0)
                <div class="row g-4">
                    @foreach ($reviews as $review)
                        <div class="col-lg-6">
                            <div class="review-card">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <div class="reviewer-avatar">
                                            @if ($review->photo)
                                                <img src="{{ asset('storage/' . $review->photo) }}"
                                                    alt="{{ $review->name }}" class="avatar-img">
                                            @else
                                                <div class="avatar-placeholder">
                                                    {{ strtoupper(substr($review->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="reviewer-details">
                                            <h6 class="reviewer-name">{{ $review->name }}</h6>
                                            <p class="reviewer-role">Pengguna Layanan</p>
                                            <div class="review-rating">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i
                                                        class="fas fa-star {{ $i <= $review->rating ? 'star-filled' : 'star-empty' }}"></i>
                                                @endfor
                                                <span class="rating-text">({{ $review->rating }}/5)</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="review-date">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ $review->created_at->format('d M Y') }}
                                    </div>
                                </div>

                                <div class="review-content">
                                    <div class="quote-icon">
                                        <i class="fas fa-quote-left"></i>
                                    </div>
                                    <p class="review-text">{{ $review->comment }}</p>
                                    <div class="quote-icon quote-right">
                                        <i class="fas fa-quote-right"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($reviews->hasPages())
                    <div class="pagination-container">
                        {{ $reviews->links('partials.custom-pagination') }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h5 class="empty-title">Belum Ada Ulasan</h5>
                    <p class="empty-subtitle">Jadilah yang pertama memberikan ulasan tentang layanan kami</p>
                    <button class="contact-btn" data-bs-toggle="modal" data-bs-target="#reviewModal">
                        <i class="fas fa-pen me-2"></i>Tulis Ulasan Pertama
                    </button>
                </div>
            @endif
        </div>
    </section>



    <!-- Call to Action -->
    <section class="section-alternate scroll-reveal">
        <div class="container text-center">
            <h3 class="section-title mb-4">Jelajahi Layanan Lainnya</h3>
            <p class="section-subtitle mb-4">Temukan berbagai layanan unggulan yang kami sediakan</p>
            <div class="cta-buttons">
                <a href="{{ route('public.layanan') }}" class="cta-btn primary">
                    <i class="fas fa-concierge-bell me-2"></i>
                    <span>Layanan Kami</span>
                </a>
                <a href="{{ route('public.berita') }}" class="cta-btn secondary">
                    <i class="fas fa-newspaper me-2"></i>
                    <span>Berita Terbaru</span>
                </a>
                <a href="{{ route('public.profile') }}" class="cta-btn secondary">
                    <i class="fas fa-info-circle me-2"></i>
                    <span>Tentang Kami</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Review Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-modern">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewModalLabel">
                        <i class="fas fa-pen me-2"></i>Bagikan Pengalaman Anda
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('public.ulasan.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-4">
                            <label for="name" class="form-label">Nama Lengkap *</label>
                            <input type="text" class="form-control form-modern" id="name" name="name"
                                placeholder="Masukkan nama lengkap Anda" required>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control form-modern" id="email" name="email"
                                placeholder="Masukkan email Anda" required>
                        </div>

                        <div class="mb-4">
                            <label for="rating" class="form-label">Rating *</label>
                            <div class="rating-input">
                                <div class="star-rating">
                                    <input type="radio" id="star5" name="rating" value="5" required>
                                    <label for="star5" class="star"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="star4" name="rating" value="4">
                                    <label for="star4" class="star"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="star3" name="rating" value="3">
                                    <label for="star3" class="star"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="star2" name="rating" value="2">
                                    <label for="star2" class="star"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="star1" name="rating" value="1">
                                    <label for="star1" class="star"><i class="fas fa-star"></i></label>
                                </div>
                                <small class="text-muted">Pilih rating dari 1-5 bintang</small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="comment" class="form-label">Ulasan Anda *</label>
                            <textarea class="form-control form-modern" id="comment" name="comment" rows="5"
                                placeholder="Ceritakan pengalaman Anda dengan layanan kami..." required></textarea>
                            <small class="text-muted">Minimal 10 karakter, maksimal 1000 karakter</small>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="agree_terms" required>
                            <label class="form-check-label" for="agree_terms">
                                Saya setuju bahwa ulasan ini berdasarkan pengalaman pribadi dan dapat dipublikasikan
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-gradient">
                            <i class="fas fa-paper-plane me-2"></i>Kirim Ulasan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-success-modern, .alert-danger-modern');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Star rating functionality
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star-rating .star');
            const inputs = document.querySelectorAll('.star-rating input');

            // Prevent body scroll when modal is open
            const reviewModal = document.getElementById('reviewModal');
            if (reviewModal) {
                reviewModal.addEventListener('show.bs.modal', function() {
                    document.body.style.overflow = 'hidden';
                    document.body.style.paddingRight = '15px'; // Prevent layout shift
                });

                reviewModal.addEventListener('hide.bs.modal', function() {
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                });
            }

            stars.forEach((star, index) => {
                star.addEventListener('click', function() {
                    const rating = 5 - index;
                    inputs[index].checked = true;
                    updateStarDisplay(rating);
                });

                star.addEventListener('mouseover', function() {
                    const rating = 5 - index;
                    updateStarDisplay(rating);
                });
            });

            document.querySelector('.star-rating').addEventListener('mouseleave', function() {
                const checkedInput = document.querySelector('.star-rating input:checked');
                const rating = checkedInput ? checkedInput.value : 0;
                updateStarDisplay(rating);
            });

            function updateStarDisplay(rating) {
                stars.forEach((star, index) => {
                    const starRating = 5 - index;
                    if (starRating <= rating) {
                        star.classList.add('active');
                    } else {
                        star.classList.remove('active');
                    }
                });
            }

            // Form validation
            const reviewForm = document.querySelector('#reviewModal form');
            const commentTextarea = document.querySelector('#comment');

            if (reviewForm) {
                reviewForm.addEventListener('submit', function(e) {
                    const name = document.querySelector('#name').value.trim();
                    const email = document.querySelector('#email').value.trim();
                    const rating = document.querySelector('input[name="rating"]:checked');
                    const comment = commentTextarea.value.trim();
                    const agreeTerms = document.querySelector('#agree_terms').checked;

                    let isValid = true;
                    let errorMessage = '';

                    if (!name) {
                        errorMessage += '- Nama lengkap wajib diisi\n';
                        isValid = false;
                    }

                    if (!email) {
                        errorMessage += '- Email wajib diisi\n';
                        isValid = false;
                    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                        errorMessage += '- Format email tidak valid\n';
                        isValid = false;
                    }

                    if (!rating) {
                        errorMessage += '- Rating wajib dipilih\n';
                        isValid = false;
                    }

                    if (!comment) {
                        errorMessage += '- Ulasan wajib diisi\n';
                        isValid = false;
                    } else if (comment.length < 10) {
                        errorMessage += '- Ulasan minimal 10 karakter\n';
                        isValid = false;
                    } else if (comment.length > 1000) {
                        errorMessage += '- Ulasan maksimal 1000 karakter\n';
                        isValid = false;
                    }

                    if (!agreeTerms) {
                        errorMessage += '- Anda harus menyetujui syarat dan ketentuan\n';
                        isValid = false;
                    }

                    if (!isValid) {
                        e.preventDefault();
                        alert('Mohon lengkapi data berikut:\n\n' + errorMessage);
                        return false;
                    }
                });
            }

            // Character counter for comment
            if (commentTextarea) {
                const charCounter = document.createElement('small');
                charCounter.className = 'text-muted d-block mt-1';
                charCounter.textContent = '0/1000 karakter';
                commentTextarea.parentNode.appendChild(charCounter);

                commentTextarea.addEventListener('input', function() {
                    const length = this.value.length;
                    charCounter.textContent = `${length}/1000 karakter`;

                    if (length > 1000) {
                        charCounter.className = 'text-danger d-block mt-1';
                    } else if (length < 10) {
                        charCounter.className = 'text-warning d-block mt-1';
                    } else {
                        charCounter.className = 'text-success d-block mt-1';
                    }
                });
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        /* Modal Styling */
        .modal {
            z-index: 99999 !important;
        }

        .modal-backdrop {
            z-index: 99998 !important;
            background-color: rgba(0, 0, 0, 0.6) !important;
        }

        .modal-dialog {
            z-index: 99999 !important;
            position: relative;
        }

        .modal-modern .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            position: relative;
            z-index: 99999 !important;
        }

        .modal-modern .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-bottom: none;
            padding: 20px 30px;
        }

        .modal-modern .modal-title {
            font-weight: 600;
            font-size: 1.2rem;
        }

        .modal-modern .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }

        .modal-modern .modal-body {
            padding: 30px;
        }

        .modal-modern .modal-footer {
            padding: 20px 30px;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            background: rgba(248, 249, 250, 0.5);
        }

        /* Form Styling */
        .form-modern {
            border: 2px solid rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }

        .form-modern:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
            background: white;
        }

        .form-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
        }

        /* Star Rating */
        .rating-input {
            text-align: center;
        }

        .star-rating {
            display: inline-flex;
            flex-direction: row-reverse;
            gap: 5px;
            margin-bottom: 10px;
        }

        .star-rating input {
            display: none;
        }

        .star-rating .star {
            cursor: pointer;
            font-size: 2rem;
            color: #ddd;
            transition: all 0.3s ease;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        .star-rating .star:hover,
        .star-rating .star.active {
            color: #ffc107;
            transform: scale(1.1);
        }

        .star-rating .star:hover {
            text-shadow: 2px 2px 4px rgba(255, 193, 7, 0.3);
        }

        /* Form Check */
        .form-check-input {
            border-radius: 6px;
            border: 2px solid rgba(0, 0, 0, 0.2);
        }

        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }

        .form-check-label {
            font-size: 0.9rem;
            color: #4a5568;
            line-height: 1.4;
        }

        /* Button Styling */
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .btn-outline-secondary {
            border: 2px solid #6c757d;
            color: #6c757d;
            background: transparent;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background: #6c757d;
            color: white;
            transform: translateY(-1px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .modal-dialog {
                margin: 10px;
            }

            .modal-modern .modal-body,
            .modal-modern .modal-footer {
                padding: 20px;
            }

            .star-rating .star {
                font-size: 1.5rem;
            }
        }
    </style>
@endpush
