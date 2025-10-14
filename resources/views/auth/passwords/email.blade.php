@extends('auth.layouts.app')

@section('title', 'Lupa Password')

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

        .alert-success {
            background: rgba(0, 255, 136, 0.1);
            border: 1px solid rgba(0, 255, 136, 0.3);
            color: var(--accent-color);
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
    </style>
@endpush

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <i class="fas fa-key"></i>
                </div>
                <h1 class="auth-title">Lupa Password</h1>
                <p class="auth-subtitle">Masukkan email Anda untuk menerima link reset password</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope me-2"></i>
                        Email Address
                    </label>
                    <input id="email" type="email" class="form-control-auth @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                        placeholder="Masukkan email Anda">
                    @error('email')
                        <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn-auth">
                    <i class="fas fa-paper-plane me-2"></i>
                    Kirim Link Reset Password
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
@endsection
