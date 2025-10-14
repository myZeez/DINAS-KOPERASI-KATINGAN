<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Dinas Koperasi</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #00ff88, #00d4ff);
            padding: 30px;
            text-align: center;
            color: white;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }

        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }

        .content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .message {
            font-size: 16px;
            margin-bottom: 30px;
            color: #555;
        }

        .button-container {
            text-align: center;
            margin: 30px 0;
        }

        .reset-button {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(135deg, #00ff88, #00d4ff);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .reset-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 255, 136, 0.3);
        }

        .alternative-link {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #00ff88;
        }

        .alternative-link p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }

        .alternative-link a {
            color: #00ff88;
            word-break: break-all;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }

        .footer p {
            margin: 0;
            font-size: 14px;
            color: #6c757d;
        }

        .security-notice {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }

        .security-notice p {
            margin: 0;
            font-size: 14px;
            color: #856404;
        }

        .security-notice strong {
            color: #856404;
        }

        @media (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 5px;
            }

            .header {
                padding: 20px;
            }

            .header h1 {
                font-size: 24px;
            }

            .content {
                padding: 30px 20px;
            }

            .reset-button {
                padding: 12px 25px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Reset Password</h1>
            <p>Dinas Koperasi Katingan</p>
        </div>

        <div class="content">
            <div class="greeting">
                Halo,
            </div>

            <div class="message">
                Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda di sistem Dinas
                Koperasi.
            </div>

            <div class="button-container">
                <a href="{{ $actionUrl }}" class="reset-button">
                    Reset Password Sekarang
                </a>
            </div>

            <div class="alternative-link">
                <p><strong>Tidak bisa mengklik tombol di atas?</strong></p>
                <p>Salin dan tempel URL berikut ke browser Anda:</p>
                <p><a href="{{ $actionUrl }}">{{ $actionUrl }}</a></p>
            </div>

            <div class="security-notice">
                <p><strong>Catatan Keamanan:</strong></p>
                <p>Link reset password ini akan kedaluwarsa dalam {{ $count }} menit. Jika Anda tidak meminta
                    reset password, abaikan email ini dan password Anda tidak akan diubah.</p>
            </div>

            <div class="message">
                <p>Jika Anda mengalami masalah, silakan hubungi administrator sistem.</p>
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Dinas Koperasi Katingan. Semua hak dilindungi.</p>
            <p>Email ini dikirim secara otomatis, mohon jangan membalas email ini.</p>
        </div>
    </div>
</body>

</html>
