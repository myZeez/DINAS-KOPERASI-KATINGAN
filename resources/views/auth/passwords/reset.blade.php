@extends('auth.layouts.app')

@section('title', 'Reset Password')

@push('styles')
    <style>
        body {
            background: url('{{ asset('Img/BackroudAdmin.jpg') }}') center/cover no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .auth-container {
            width: 100%;
            max-width: 450px;
            margin: 0 auto;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .auth-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: var(--accent-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
        }

        .auth-title {
            color: var(--text-primary);
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .auth-subtitle {
            color: var(--text-secondary);
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            color: var(--text-primary);
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }

        .form-control-auth {
            width: 100%;
            padding: 14px 18px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: var(--text-primary);
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control-auth::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .form-control-auth:focus {
            background: rgba(255, 255, 255, 0.12);
            border-color: var(--accent-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 255, 136, 0.1);
        }

        .btn-auth {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--accent-color), #00d4ff);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 255, 136, 0.3);
        }

        .auth-links {
            text-align: center;
        }

        .auth-link {
            color: var(--accent-color);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .auth-link:hover {
            color: #00d4ff;
            text-decoration: underline;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-danger {
            background: rgba(255, 107, 107, 0.1);
            border: 1px solid rgba(255, 107, 107, 0.3);
            color: #ff6b6b;
        }

        .invalid-feedback {
            color: #ff6b6b;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        .form-control-auth.is-invalid {
            border-color: #ff6b6b;
        }

        .password-requirements {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 12px;
            margin-top: 8px;
            font-size: 12px;
            color: var(--text-secondary);
        }

        .password-requirements ul {
            margin: 0;
            padding-left: 16px;
        }

        .password-requirements li {
            margin-bottom: 4px;
        }
    </style>
@endpush

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    @php
                        $profile = \App\Models\Profile::first();
                        $logoPath = $profile && $profile->logo ? 'storage/' . $profile->logo : 'favicon.ico';
                    @endphp
                    <img src="{{ asset($logoPath) }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain; border-radius: 50%;">
                </div>
                <h1 class="auth-title">Reset Password</h1>
                <p class="auth-subtitle">Masukkan password baru untuk akun Anda</p>
            </div>

            <form method="POST" action="{{ route('password.reset.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope me-2"></i>
                        Email Address
                    </label>
                    <input id="email" type="email" class="form-control-auth @error('email') is-invalid @enderror"
                        name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" readonly>
                    @error('email')
                        <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock me-2"></i>
                        Password Baru
                    </label>
                    <div style="position: relative;">
                        <input id="password" type="password" class="form-control-auth password-input @error('password') is-invalid @enderror"
                            name="password" required autocomplete="new-password" placeholder="Masukkan password baru">
                        <button type="button" class="password-toggle" onclick="togglePassword('password', 'passwordToggleIcon')">
                            <i id="passwordToggleIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                    <div class="password-requirements">
                        <strong>Persyaratan Password:</strong>
                        <ul>
                            <li>Minimal 8 karakter</li>
                            <li>Kombinasi huruf dan angka direkomendasikan</li>
                            <li>Hindari menggunakan informasi pribadi</li>
                        </ul>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password-confirm" class="form-label">
                        <i class="fas fa-lock me-2"></i>
                        Konfirmasi Password
                    </label>
                    <div style="position: relative;">
                        <input id="password-confirm" type="password" class="form-control-auth password-input" name="password_confirmation"
                            required autocomplete="new-password" placeholder="Ulangi password baru">
                        <button type="button" class="password-toggle" onclick="togglePassword('password-confirm', 'passwordConfirmToggleIcon')">
                            <i id="passwordConfirmToggleIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-auth">
                    <i class="fas fa-key me-2"></i>
                    Reset Password
                </button>

                <div class="auth-links">
                    <a href="{{ route('login') }}" class="auth-link">
                        <i class="fas fa-arrow-left me-1"></i>
                        Kembali ke Login
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Form submission loading state
        document.querySelector('form').addEventListener('submit', function(e) {
            const submitBtn = document.querySelector('.btn-auth');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
            submitBtn.disabled = true;
        });
    </script>
@endsection
