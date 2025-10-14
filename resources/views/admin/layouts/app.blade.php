@php
    use Illuminate\Support\Facades\Storage;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - {{ $profile->name ?? 'Dinas Koperasi' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    @if (isset($profile) && $profile->logo && Storage::disk('public')->exists($profile->logo))
        <link rel="icon" type="image/png" href="{{ Storage::url($profile->logo) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @endif

    <style>
        /* ================================
        CSS VARIABLES
        ================================ */
        :root {
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --glass-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            --text-primary: #ffffff;
            --text-secondary: rgba(255, 255, 255, 0.8);
            --accent-color: #00ff88;
            --danger-color: #ff6b6b;
            --warning-color: #ffd93d;
            --info-color: #6bcf7f;
        }

        /* ================================
        GLOBAL STYLES
        ================================ */
        * {
            box-sizing: border-box;
        }

        /* ================================
        GLASS MODAL (CONSISTENT STYLE)
        ================================ */
        .modal-content.modal-glass {
            background: var(--glass-bg) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            border: 1px solid var(--glass-border) !important;
            border-radius: 16px !important;
            color: var(--text-primary) !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
        }

        .modal-content.modal-glass .modal-header,
        .modal-content.modal-glass .modal-footer {
            border: none !important;
            background: transparent !important;
        }

        .modal-content.modal-glass .modal-title {
            color: var(--text-primary) !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-content.modal-glass .modal-body {
            color: var(--text-primary) !important;
        }

        .modal-content.modal-glass .btn-close {
            filter: invert(1) brightness(2);
            opacity: 0.8;
        }

        .modal-content.modal-glass .btn-close:hover {
            opacity: 1;
        }

        /* ================================
        USER INFO BAR
        ================================ */
        .user-info-bar {
            /* Make bar transparent; inner wrapper will get glass treatment */
            background: transparent;
            padding: 12px 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .user-info-bar .container-fluid {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 16px;
        }

        .user-info-wrapper {
            position: relative;
            background: linear-gradient(135deg,
                    rgba(255, 255, 255, 0.08) 0%,
                    rgba(255, 255, 255, 0.04) 50%,
                    rgba(255, 255, 255, 0.08) 100%);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            padding: 10px 16px;
            box-shadow:
                0 8px 24px rgba(0, 0, 0, 0.12),
                inset 0 1px 0 rgba(255, 255, 255, 0.2),
                inset 0 -1px 0 rgba(255, 255, 255, 0.1);
        }

        .user-info-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--accent-color) 0%, #00d4ff 100%);
            border-radius: 16px 16px 0 0;
        }

        .user-greeting {
            font-size: 14px;
            color: var(--text-primary);
        }

        .user-greeting strong {
            background: linear-gradient(135deg, #ffffff 0%, var(--accent-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .role-badge {
            padding: 6px 12px !important;
            border-radius: 16px !important;
            font-size: 12px !important;
        }

        /* ================================
        BODY & BACKGROUND
        ================================ */
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


        /* ================================
        GLASS CARD COMPONENT
        ================================ */
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            /* Removed box-shadow: var(--glass-shadow); */
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            background: rgba(255, 255, 255, 0.08);
            /* Removed transform: translateY(-5px); */
            /* Removed box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.5); */
        }

        /* ================================
        MAIN CONTENT AREA
        ================================ */
        .main-content {
            margin-top: 80px;
            margin-bottom: 120px;
            padding: 20px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        /* ================================
        BOTTOM NAVIGATION
        ================================ */
        .bottom-nav {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            justify-content: center;
            border-radius: 45.5px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            align-items: center;
            z-index: 1000;
            padding: 5px;
            transition: all 0.3s ease;
            /* Removed box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2); */
            max-width: 90vw;
            overflow: hidden;
        }

        .nav-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
            flex-wrap: nowrap;
        }

        .nav-item {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: var(--text-primary);
            padding: 12px 16px;
            border-radius: 25px;
            min-width: 50px;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            white-space: nowrap;
        }

        .nav-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, var(--accent-color), var(--info-color));
            border-radius: 25px;
            opacity: 0;
            transform: scale(0.7);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: -1;
        }

        .nav-item:hover::before,
        .nav-item.active::before {
            opacity: 1;
            transform: scale(1);
        }

        .nav-item:hover,
        .nav-item.active {
            color: #000;
            /* Removed transform: translateY(-6px) scale(1.02); */
            /* Removed box-shadow: 0 12px 25px rgba(0, 255, 136, 0.35); */
        }

        .nav-item:hover {
            color: #000;
        }

        .nav-item.active {
            color: #000;
            font-weight: 600;
        }

        .nav-item i {
            font-size: 18px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
            flex-shrink: 0;
        }

        .nav-item span {
            font-size: 12px;
            font-weight: 600;
            transition: all 0.2s ease;
            position: relative;
            z-index: 1;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-left: 8px;
            opacity: 1;
            width: auto;
            overflow: visible;
            transform: none;
        }

        .nav-item.active span {
            font-weight: 700;
            opacity: 1;
        }


        /* ================================
        STATISTICS CARD
        ================================ */
        .stat-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
        }

        .stat-card:hover {
            /* Removed transform: translateY(-5px); */
            background: rgba(255, 255, 255, 0.08);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 24px;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--text-primary);
        }

        .stat-label {
            font-size: 14px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        /* ================================
        BUTTONS
        ================================ */
        .btn-glass {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            color: var(--text-primary);
            border-radius: 12px;
            padding: 10px 20px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-glass:hover {
            background: rgba(255, 255, 255, 0.08);
            /* Removed transform: translateY(-2px); */
            color: var(--text-primary);
        }

        .btn-primary-glass {
            background: linear-gradient(135deg, var(--accent-color), var(--info-color));
            border: none;
            color: #000;
            font-weight: 600;
        }

        .btn-primary-glass:hover {
            background: linear-gradient(135deg, #00e67a, #5bb970);
            color: #000;
        }

        /* ================================
        TABLE COMPONENT
        ================================ */
        .table-glass {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            overflow: hidden;
        }

        .table-glass th {
            background: rgba(255, 255, 255, 0.05);
            border: none;
            color: var(--text-primary);
            font-weight: 600;
            padding: 15px;
        }

        .table-glass td {
            border: none;
            color: var(--text-primary);
            padding: 15px;
            border-bottom: 1px solid var(--glass-border);
        }

        /* ================================
        BADGE COMPONENT
        ================================ */
        .badge-glass {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            color: var(--text-primary);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        .badge-glass i {
            font-size: 12px;
        }

        .status-published {
            background: rgba(0, 255, 136, 0.15) !important;
            border-color: rgba(0, 255, 136, 0.3) !important;
        }

        .status-draft {
            background: rgba(255, 215, 61, 0.15) !important;
            border-color: rgba(255, 215, 61, 0.3) !important;
        }

        /* ================================
        FORM CONTROLS
        ================================ */
        .form-control-glass {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            color: var(--text-primary);
            border-radius: 12px;
            padding: 12px 15px;
        }

        .form-control-glass::placeholder {
            color: var(--text-primary);
            opacity: 0.6;
        }

        .form-control-glass:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 255, 136, 0.25);
            color: var(--text-primary);
        }

        /* ================================
        DROPDOWN COMPONENTS
        ================================ */
        .dropdown-menu {
            background: var(--glass-bg) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            border: 1px solid var(--glass-border) !important;
            border-radius: 12px !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25) !important;
            color: var(--text-primary) !important;
            z-index: 1050 !important;
            min-width: 160px;
        }

        .dropdown-item {
            color: var(--text-primary) !important;
            padding: 8px 16px;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover,
        .dropdown-item:focus {
            background: rgba(255, 255, 255, 0.1) !important;
            color: var(--accent-color) !important;
        }

        .dropdown-item.active {
            background: var(--accent-color) !important;
            color: #000 !important;
        }

        .dropdown-divider {
            border-color: var(--glass-border) !important;
            opacity: 0.5;
        }

        .dropdown-toggle::after {
            color: var(--text-primary) !important;
        }

        /* ================================
        PAGE ELEMENTS
        ================================ */
        .page-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 30px;
            color: var(--text-primary);
            text-align: center;
        }

        /* ================================
        ALERT COMPONENT
        ================================ */
        .alert-glass {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            color: var(--text-primary);
        }

        /* ================================
        ADDITIONAL TEXT OVERRIDES
        ================================ */
        .text-dark,
        .text-muted,
        .text-secondary {
            color: var(--text-primary) !important;
        }

        .card,
        .card-body,
        .card-header,
        .card-footer {
            background: var(--glass-bg) !important;
            color: var(--text-primary) !important;
            border: 1px solid var(--glass-border) !important;
        }

        .table,
        .table th,
        .table td {
            color: var(--text-primary) !important;
            background: transparent !important;
        }

        .badge {
            background: var(--glass-bg) !important;
            color: var(--text-primary) !important;
            border: 1px solid var(--glass-border) !important;
        }

        .list-group-item {
            background: var(--glass-bg) !important;
            color: var(--text-primary) !important;
            border: 1px solid var(--glass-border) !important;
        }

        /* Force white text everywhere */
        p,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        span,
        div,
        td,
        th,
        li,
        label {
            color: var(--text-primary) !important;
        }

        /* ================================
        MODERN WELCOME SECTION
        ================================ */
        .welcome-section {
            position: relative;
            margin-top: -20px;
            margin-bottom: 40px;
        }

        .welcome-container {
            background: linear-gradient(135deg,
                    rgba(255, 255, 255, 0.08) 0%,
                    rgba(255, 255, 255, 0.03) 100%);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 24px;
            padding: 40px;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 280px;
        }

        .welcome-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg,
                    var(--accent-color) 0%,
                    var(--info-color) 50%,
                    var(--accent-color) 100%);
            border-radius: 24px 24px 0 0;
        }

        .welcome-content {
            flex: 1;
            z-index: 2;
            position: relative;
        }

        .welcome-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, var(--accent-color), var(--info-color));
            color: #000;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
            animation: pulse-glow 2s ease-in-out infinite;
        }

        .welcome-badge i {
            font-size: 16px;
            color: #000;
        }

        .welcome-title {
            font-size: 42px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 16px;
            background: linear-gradient(135deg, #ffffff 0%, rgba(255, 255, 255, 0.7) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .highlight-text {
            background: linear-gradient(135deg, var(--accent-color), var(--info-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
        }

        .welcome-subtitle {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
            margin-bottom: 30px;
            max-width: 600px;
        }

        .welcome-stats-mini {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .mini-stat {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.05);
            padding: 12px 20px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .mini-stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--accent-color), var(--info-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000;
            font-size: 16px;
        }

        .mini-stat-info {
            display: flex;
            flex-direction: column;
        }

        .mini-stat-value {
            font-size: 16px;
            font-weight: 700;
            color: #ffffff;
            line-height: 1;
        }

        .mini-stat-label {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .welcome-decoration {
            position: absolute;
            right: 40px;
            top: 50%;
            transform: translateY(-50%);
            width: 200px;
            height: 200px;
            z-index: 1;
        }

        .floating-element {
            position: absolute;
            width: 50px;
            height: 50px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.6);
            font-size: 20px;
            animation: float-around 6s ease-in-out infinite;
        }

        .element-1 {
            top: 0;
            left: 0;
            animation-delay: 0s;
        }

        .element-2 {
            top: 0;
            right: 0;
            animation-delay: 1.5s;
        }

        .element-3 {
            bottom: 0;
            left: 0;
            animation-delay: 3s;
        }

        .element-4 {
            bottom: 0;
            right: 0;
            animation-delay: 4.5s;
        }

        @keyframes pulse-glow {

            0%,
            100% {
                box-shadow: 0 0 20px rgba(0, 255, 136, 0.3);
            }

            50% {
                box-shadow: 0 0 30px rgba(0, 255, 136, 0.5);
            }
        }

        @keyframes float-around {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            25% {
                transform: translateY(-10px) rotate(90deg);
            }

            50% {
                transform: translateY(-5px) rotate(180deg);
            }

            75% {
                transform: translateY(-15px) rotate(270deg);
            }
        }

        /* Mobile responsiveness for welcome section */
        @media (max-width: 768px) {
            .welcome-container {
                flex-direction: column;
                text-align: center;
                padding: 30px 20px;
                min-height: auto;
            }

            .welcome-title {
                font-size: 32px;
            }

            .welcome-subtitle {
                font-size: 16px;
            }

            .welcome-decoration {
                position: relative;
                right: auto;
                top: auto;
                transform: none;
                margin-top: 20px;
                width: 150px;
                height: 150px;
                margin: 20px auto 0;
            }

            .welcome-stats-mini {
                justify-content: center;
                gap: 15px;
            }

            .mini-stat {
                flex-direction: column;
                text-align: center;
                gap: 8px;
                padding: 16px;
            }

            /* Hide badge text on mobile, keep only icons */
            .badge-glass {
                display: flex !important;
                align-items: center;
                justify-content: center;
                gap: 6px;
                transition: all 0.3s ease;
            }

            .badge-glass .status-text {
                display: none !important;
            }

            .badge-glass {
                padding: 8px !important;
                min-width: 32px;
                height: 32px;
                border-radius: 50% !important;
            }

            .badge-glass i {
                font-size: 14px !important;
                margin: 0 !important;
            }

            /* Make table more mobile friendly */
            .table-responsive {
                font-size: 12px;
            }

            .table th,
            .table td {
                padding: 8px 4px !important;
                vertical-align: middle;
            }

            /* Hide some columns on mobile */
            .table th:nth-child(6),
            .table td:nth-child(6) {
                display: none;
            }

            /* Compress action buttons */
            .btn-group {
                display: flex;
                gap: 2px;
            }

            .btn-sm {
                padding: 4px 6px !important;
                font-size: 12px !important;
            }
        }

        @media (max-width: 480px) {

            /* Even more compressed for very small screens */
            .badge-glass {
                min-width: 28px !important;
                height: 28px !important;
                padding: 6px !important;
            }

            .badge-glass i {
                font-size: 12px !important;
            }

            /* Hide more columns on very small screens */
            .table th:nth-child(2),
            .table td:nth-child(2),
            .table th:nth-child(4),
            .table td:nth-child(4) {
                display: none;
            }

            .table th,
            .table td {
                padding: 6px 2px !important;
            }
        }

        /* ================================
        RESPONSIVE DESIGN
        ================================ */
        @media (max-width: 768px) {

            /* Main Content - Tablet */
            .main-content {
                padding: 15px;
                margin-bottom: 140px;
            }

            /* Bottom Navigation - Tablet */
            .bottom-nav {
                max-width: 95vw;
                padding: 10px 15px;
                bottom: 15px;
            }

            .nav-item {
                padding: 10px 8px;
                min-width: 50px;
            }

            .nav-item span {
                font-size: 10px;
                opacity: 1;
                width: auto;
            }

            .nav-item i {
                font-size: 18px;
                flex-shrink: 0;
            }

            /* Header - Tablet */
            .header-content {
                padding: 0 15px;
            }

            .admin-greeting .admin-name {
                display: none;
            }
        }

        @media (max-width: 480px) {

            /* Bottom Navigation - Mobile */
            .nav-item span {
                display: inline !important;
                opacity: 1 !important;
                width: auto !important;
            }

            .nav-item {
                padding: 12px 10px;
                min-width: 45px;
            }

            .bottom-nav {
                padding: 8px 12px;
                bottom: 10px;
                max-width: 95vw;
            }
        }

        /* ================================
        CUSTOM PAGINATION STYLES
        ================================ */
        .custom-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin: 2rem 0;
            flex-wrap: wrap;
        }

        .pagination-info {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin: 0 1rem;
            background: var(--glass-bg);
            padding: 8px 16px;
            border-radius: 20px;
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .pagination-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 8px 12px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            cursor: pointer;
        }

        .pagination-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--accent-color);
            color: var(--accent-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 255, 136, 0.2);
        }

        .pagination-btn.active {
            background: var(--accent-color);
            border-color: var(--accent-color);
            color: #000;
            font-weight: 700;
        }

        .pagination-btn.active:hover {
            background: var(--accent-color);
            color: #000;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 255, 136, 0.4);
        }

        .pagination-btn:disabled,
        .pagination-btn.disabled {
            opacity: 0.4;
            cursor: not-allowed;
            background: var(--glass-bg);
            border-color: var(--glass-border);
            color: var(--text-secondary);
        }

        .pagination-btn:disabled:hover,
        .pagination-btn.disabled:hover {
            transform: none;
            box-shadow: none;
            background: var(--glass-bg);
            border-color: var(--glass-border);
            color: var(--text-secondary);
        }

        .pagination-arrow {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .pagination-arrow.prev:before {
            content: "‹";
            margin-right: 6px;
            font-size: 1.2rem;
        }

        .pagination-arrow.next:after {
            content: "›";
            margin-left: 6px;
            font-size: 1.2rem;
        }

        .pagination-ellipsis {
            color: var(--text-secondary);
            padding: 8px 4px;
            font-weight: bold;
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .custom-pagination {
                gap: 4px;
                margin: 1.5rem 0;
            }

            .pagination-btn {
                min-width: 36px;
                height: 36px;
                padding: 6px 8px;
                font-size: 0.9rem;
            }

            .pagination-info {
                font-size: 0.8rem;
                padding: 6px 12px;
                margin: 0 0.5rem;
            }

            .pagination-arrow {
                padding: 6px 12px;
                font-size: 0.8rem;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <script>
        // Theme bootstrap: force glass theme and apply saved accent color
        (function() {
            try {
                localStorage.setItem('adminTheme', 'glass');
                document.documentElement.classList.remove('theme-glass', 'theme-classic', 'theme-retro');
                document.documentElement.classList.add('theme-glass');
                const accent = localStorage.getItem('adminAccentColor');
                if (accent) {
                    document.documentElement.style.setProperty('--accent-color', accent);
                }
            } catch (e) {
                /* no-op */
            }
        })();
    </script>
    <style>
        /* ================================
        THEMES
        - Use body/html class: theme-glass | theme-classic | theme-retro
        ================================ */
        /* Default (glass) already defined via variables; ensure class exists */
        .theme-glass body {
            background-attachment: fixed;
        }

        /* Classic (clean white admin with balanced colors) */
        .theme-classic body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
            color: #212529 !important;
        }

        .theme-classic .glass-card {
            background: #ffffff !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            border: 1px solid #dee2e6 !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
            border-radius: 8px !important;
            color: #212529 !important;
        }

        .theme-classic .bottom-nav {
            background: #ffffff !important;
            border: 1px solid #dee2e6 !important;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1) !important;
        }

        .theme-classic .nav-item {
            color: #6c757d !important;
        }

        .theme-classic .nav-item.active,
        .theme-classic .nav-item:hover {
            color: #ffffff !important;
        }

        .theme-classic .nav-item::before {
            background: linear-gradient(135deg, #007bff, #0056b3) !important;
        }

        .theme-classic .page-title,
        .theme-classic .page-main-title,
        .theme-classic h1,
        .theme-classic h2,
        .theme-classic h3,
        .theme-classic h4,
        .theme-classic h5,
        .theme-classic h6 {
            color: #212529 !important;
        }

        .theme-classic .badge-glass {
            background: #f8f9fa !important;
            border: 1px solid #dee2e6 !important;
            color: #495057 !important;
        }

        .theme-classic .form-control-glass {
            background: #fff !important;
            border: 1px solid #ced4da !important;
            color: #212529 !important;
        }

        .theme-classic .form-control-glass::placeholder {
            color: #6c757d !important;
        }

        .theme-classic .user-info-bar {
            background: linear-gradient(135deg, #495057, #343a40) !important;
            border-bottom: 1px solid #dee2e6 !important;
        }

        .theme-classic .page-header-container {
            background: linear-gradient(135deg, #ffffff, #f8f9fa) !important;
            border: 1px solid #dee2e6 !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06) !important;
        }

        .theme-classic .stat-card {
            background: #ffffff !important;
            border: 1px solid #dee2e6 !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06) !important;
            color: #212529 !important;
        }

        .theme-classic .text-white,
        .theme-classic .text-white-50 {
            color: #212529 !important;
        }

        .theme-classic .btn-glass,
        .theme-classic .btn-primary-glow,
        .theme-classic .btn-accent {
            background: #007bff !important;
            color: #ffffff !important;
            border: 1px solid #0056b3 !important;
        }

        .theme-classic .btn-glass:hover,
        .theme-classic .btn-primary-glow:hover {
            background: #0056b3 !important;
        }

        .theme-classic .modal-content {
            background: #ffffff !important;
            color: #212529 !important;
            border: 1px solid #dee2e6 !important;
        }

        .theme-classic .table {
            background: #ffffff !important;
            color: #212529 !important;
        }

        .theme-classic .table th {
            background: #f8f9fa !important;
            color: #495057 !important;
            border-bottom: 1px solid #dee2e6 !important;
        }

        .theme-classic .table td {
            color: #212529 !important;
            border-bottom: 1px solid #f1f3f4 !important;
        }

        .theme-classic .fw-medium,
        .theme-classic .fw-bold {
            color: #212529 !important;
        }

        .theme-classic .text-muted {
            color: #6c757d !important;
        }

        .theme-classic .card-header {
            background: #f8f9fa !important;
            color: #495057 !important;
            border-bottom: 1px solid #dee2e6 !important;
        }

        .theme-classic .page-breadcrumb,
        .theme-classic .page-breadcrumb span {
            color: #6c757d !important;
        }

        .theme-classic .page-breadcrumb .current-page {
            color: #495057 !important;
        }

        .theme-classic .alert {
            color: #212529 !important;
        }

        /* Retro 90s (Authentic Windows 95 style) */
        .theme-retro body {
            background: #c0c0c0 !important;
            color: #000000 !important;
            font-family: 'MS Sans Serif', 'Segoe UI', sans-serif !important;
        }

        .theme-retro .glass-card {
            background: #c0c0c0 !important;
            border: 2px outset #c0c0c0 !important;
            box-shadow: none !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            border-radius: 0 !important;
        }

        .theme-retro .bottom-nav {
            background: #c0c0c0 !important;
            border: 2px outset #c0c0c0 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }

        .theme-retro .nav-item {
            color: #000000 !important;
            border-radius: 0 !important;
        }

        .theme-retro .nav-item.active,
        .theme-retro .nav-item:hover {
            background: #0000ff !important;
            color: #ffffff !important;
            border: 2px inset #c0c0c0 !important;
        }

        .theme-retro .nav-item::before {
            display: none !important;
        }

        .theme-retro .btn,
        .theme-retro .btn-primary-glow,
        .theme-retro .btn-outline-light,
        .theme-retro .btn-accent {
            background: #c0c0c0 !important;
            color: #000000 !important;
            border: 2px outset #c0c0c0 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            font-weight: normal !important;
        }

        .theme-retro .btn:hover,
        .theme-retro .btn:active {
            border: 2px inset #c0c0c0 !important;
        }

        .theme-retro .form-control-glass {
            background: #ffffff !important;
            color: #000000 !important;
            border: 2px inset #c0c0c0 !important;
            border-radius: 0 !important;
        }

        .theme-retro .badge-glass {
            background: #c0c0c0 !important;
            border: 1px solid #808080 !important;
            color: #000000 !important;
            border-radius: 0 !important;
        }

        .theme-retro .user-info-bar {
            background: #008080 !important;
            color: #ffffff !important;
            border-bottom: 2px outset #008080 !important;
        }

        .theme-retro .page-header-container {
            background: #c0c0c0 !important;
            border: 2px outset #c0c0c0 !important;
        }

        .theme-retro .page-main-title {
            color: #000000 !important;
            font-weight: bold !important;
        }

        .theme-retro .stat-card {
            background: #c0c0c0 !important;
            border: 2px outset #c0c0c0 !important;
            box-shadow: none !important;
        }

        .theme-retro .modal-content {
            background: #c0c0c0 !important;
            border: 2px outset #c0c0c0 !important;
            border-radius: 0 !important;
        }

        .theme-retro .table {
            background: #ffffff !important;
            border: 2px inset #c0c0c0 !important;
        }

        .theme-retro .table th {
            background: #c0c0c0 !important;
            border-bottom: 1px solid #808080 !important;
            color: #000000 !important;
        }
    </style>
    <!-- User Info Bar -->
    @unless (View::hasSection('hide_chrome'))
        <div class="user-info-bar">
            <div class="container-fluid">
                <div class="user-info-wrapper d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        @if (isset($profile) && $profile->logo && Storage::disk('public')->exists($profile->logo))
                            <img src="{{ Storage::url($profile->logo) }}"
                                alt="Logo {{ $profile->name ?? 'Dinas Koperasi' }}" class="admin-logo me-3"
                                style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%; border: 2px solid rgba(255, 255, 255, 0.3);">
                        @else
                            <i class="fas fa-building me-3" style="font-size: 24px; color: var(--accent-color);"></i>
                        @endif
                        <div class="user-greeting">
                            <span class="text-white-50">Selamat datang,</span>
                            <strong>{{ auth()->user()->name ?? 'User' }}</strong>
                            <span class="badge-glass role-badge ms-2">
                                {{ auth()->user()->role_label ?? 'User' }}
                            </span>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-glass btn-sm">
                            <i class="fas fa-sign-out-alt me-1"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endunless

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Bottom Navigation -->
    @unless (View::hasSection('hide_chrome'))
        <nav class="bottom-nav">
            <div class="nav-container">
                <!-- Dashboard (semua role) -->
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Beranda</span>
                </a>

                <!-- News (admin dan super_admin) -->
                @if (auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))
                    <a href="{{ route('admin.news.index') }}"
                        class="nav-item {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                        <i class="fas fa-newspaper"></i>
                        <span>Berita</span>
                    </a>
                @endif

                <!-- Gallery (semua role) -->
                <a href="{{ route('admin.galleries.index') }}"
                    class="nav-item {{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}">
                    <i class="fas fa-images"></i>
                    <span>Galeri</span>
                </a>

                <!-- Profile (admin dan super_admin) -->
                @if (auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))
                    <a href="{{ route('admin.profile') }}"
                        class="nav-item {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                        <i class="fas fa-user-circle"></i>
                        <span>Profile</span>
                    </a>
                @endif

                <!-- Accounts (hanya super_admin) -->
                @if (auth()->check() && auth()->user()->role === 'super_admin')
                    <a href="{{ route('admin.accounts.index') }}"
                        class="nav-item {{ request()->routeIs('admin.accounts*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Akun</span>
                    </a>
                @endif

                <!-- Public Content (admin dan super_admin) -->
                @if (auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))
                    <a href="{{ route('admin.public-content.index') }}"
                        class="nav-item {{ request()->routeIs('admin.public-content.*') ? 'active' : '' }}">
                        <i class="fas fa-globe"></i>
                        <span>Konten</span>
                    </a>
                @endif

                <!-- Structure (admin dan super_admin) -->
                @if (auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))
                    <a href="{{ route('admin.structure') }}"
                        class="nav-item {{ request()->routeIs('admin.structure') ? 'active' : '' }}">
                        <i class="fas fa-sitemap"></i>
                        <span>Struktur</span>
                    </a>
                @endif

                <!-- Reviews (semua role) -->
                <a href="{{ route('admin.reviews') }}"
                    class="nav-item {{ request()->routeIs('admin.reviews') ? 'active' : '' }}">
                    <i class="fas fa-star"></i>
                    <span>Ulasan</span>
                </a>

                <!-- File Downloads (admin dan super_admin) -->
                @if (auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))
                    <a href="{{ route('admin.file-downloads.index') }}"
                        class="nav-item {{ request()->routeIs('admin.file-downloads.*') ? 'active' : '' }}">
                        <i class="fas fa-download"></i>
                        <span>Download</span>
                    </a>
                @endif

                <!-- Trash (admin dan super_admin) -->
                @if (auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))
                    <a href="{{ route('admin.trash.index') }}"
                        class="nav-item {{ request()->routeIs('admin.trash.*') ? 'active' : '' }}">
                        <i class="fas fa-trash"></i>
                        <span>Sampah</span>
                    </a>
                @endif

                <!-- Activity Logs (admin dan super_admin) -->
                @if (auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))
                    <a href="{{ route('admin.activity-logs.index') }}"
                        class="nav-item {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
                        <i class="fas fa-history"></i>
                        <span>Log</span>
                    </a>
                @endif

                <!-- Complaints link removed -->
            </div>
        </nav>
    @endunless

    <!-- Global Loading Overlay -->
    <div id="globalLoadingOverlay"
        style="position: fixed; inset: 0; display: none; align-items: center; justify-content: center; background: rgba(0,0,0,0.35); backdrop-filter: blur(4px); z-index: 2000;">
        <div
            style="display:flex; flex-direction:column; align-items:center; gap:12px; padding:20px 24px; border-radius:16px; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.2);">
            <i class="fas fa-spinner fa-spin" style="font-size:28px; color: var(--accent-color);"></i>
            <div style="color:#fff; font-weight:600; letter-spacing:.3px;">Memproses...</div>
        </div>
    </div> <!-- Confirm Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-glass">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-triangle-exclamation text-warning me-2"></i><span
                            id="confirmTitle">Konfirmasi</span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex align-items-start gap-3">
                    <i id="confirmIcon" class="fas fa-triangle-exclamation fa-shake text-warning"
                        style="font-size:22px;"></i>
                    <div id="confirmMessage">Apakah Anda yakin?</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-glass" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="confirmOkBtn" class="btn btn-primary-glass">Ya</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Global helpers to manage accent across admin pages (theme is fixed to glass)
        window.applyAdminTheme = function(_) {
            try {
                localStorage.setItem('adminTheme', 'glass');
                const root = document.documentElement;
                root.classList.remove('theme-glass', 'theme-classic', 'theme-retro');
                root.classList.add('theme-glass');
            } catch (e) {
                /* no-op */
            }
        };

        window.applyAdminAccent = function(color) {
            try {
                document.documentElement.style.setProperty('--accent-color', color);
                localStorage.setItem('adminAccentColor', color);
            } catch (e) {
                /* no-op */
            }
        };

        // Ensure saved accent is applied after full DOM load as well; theme locked to glass
        document.addEventListener('DOMContentLoaded', function() {
            window.applyAdminTheme('glass');
            const accent = localStorage.getItem('adminAccentColor');
            if (accent) window.applyAdminAccent(accent);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ===============
        // Loading Overlay
        // ===============
        (function() {
            const overlay = document.getElementById('globalLoadingOverlay');
            let active = 0;
            let showTimer;

            function show() {
                clearTimeout(showTimer);
                showTimer = setTimeout(() => {
                    overlay.style.display = 'flex';
                }, 120);
            }

            function hide() {
                clearTimeout(showTimer);
                if (active <= 0) overlay.style.display = 'none';
            }
            window.showLoading = () => {
                active++;
                show();
            };
            window.hideLoading = () => {
                active = Math.max(0, active - 1);
                if (active === 0) hide();
            };
            // Intercept fetch
            const origFetch = window.fetch;
            window.fetch = function() {
                active++;
                show();
                return origFetch.apply(this, arguments).finally(() => {
                    active = Math.max(0, active - 1);
                    if (active === 0) hide();
                });
            };
            // Show on navigation and form submit
            document.addEventListener('click', (e) => {
                const a = e.target.closest('a');
                if (!a) return;
                const href = a.getAttribute('href') || '';
                const target = a.getAttribute('target');
                const isAnchor = href.startsWith('#') || href === '';
                const isJs = href.startsWith('javascript:');
                if (!isAnchor && !isJs && (!target || target === '_self')) show();
            }, true);
            document.addEventListener('submit', () => {
                show();
            }, true);
            window.addEventListener('pageshow', () => {
                active = 0;
                hide();
            });
        })();

        // ===================
        // Toast Notifications
        // ===================
        function getToastIcon(type) {
            const icons = {
                success: 'circle-check',
                error: 'circle-xmark',
                warning: 'triangle-exclamation',
                info: 'circle-info'
            };
            return icons[type] || 'circle-info';
        }

        function getToastAnim(type) {
            const anim = {
                success: 'fa-bounce',
                error: 'fa-shake',
                warning: 'fa-beat',
                info: 'fa-fade'
            };
            return anim[type] || 'fa-fade';
        }

        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `toast-notification toast-${type}`;
            toast.innerHTML = `
                <div class="toast-content" style="display:flex;align-items:center;gap:10px">
                    <i class="fas fa-${getToastIcon(type)} ${getToastAnim(type)}" style="min-width:20px"></i>
                    <span>${message}</span>
                </div>
            `;
            toast.style.cssText = `
                position: fixed; top: 100px; right: 20px; background: rgba(255,255,255,0.1);
                backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.2);
                border-radius: 12px; padding: 14px 18px; color: white; z-index: 2100;
                transform: translateX(400px); transition: all .3s cubic-bezier(0.4,0,0.2,1);
                box-shadow: 0 8px 32px rgba(0,0,0,.2); max-width: 360px;
            `;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
            }, 50);
            setTimeout(() => {
                toast.style.transform = 'translateX(400px)';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function queueToast(message, type = 'info') {
            try {
                localStorage.setItem('queuedToast', JSON.stringify({
                    message,
                    type,
                    ts: Date.now()
                }));
            } catch (e) {}
        }
        (function() {
            try {
                const raw = localStorage.getItem('queuedToast');
                if (raw) {
                    const {
                        message,
                        type
                    } = JSON.parse(raw);
                    localStorage.removeItem('queuedToast');
                    setTimeout(() => showToast(message, type), 300);
                }
            } catch (e) {}
        })();

        // ==================
        // Confirm Modal (async)
        // ==================
        function confirmAction({
            title = 'Konfirmasi',
            message = 'Apakah Anda yakin?',
            confirmText = 'Ya',
            cancelText = 'Batal',
            icon = 'triangle-exclamation',
            variant = 'warning'
        } = {}) {
            return new Promise(resolve => {
                const modalEl = document.getElementById('confirmModal');
                modalEl.querySelector('#confirmTitle').textContent = title;
                modalEl.querySelector('#confirmMessage').textContent = message;
                const iconEl = modalEl.querySelector('#confirmIcon');
                iconEl.className =
                    `fas fa-${icon} ${variant==='warning'?'fa-shake':''} ${variant==='success'?'fa-bounce':''}`;
                const okBtn = modalEl.querySelector('#confirmOkBtn');
                okBtn.textContent = confirmText;
                const bsModal = new bootstrap.Modal(modalEl);

                function cleanup(v) {
                    modalEl.removeEventListener('hidden.bs.modal', onHide);
                    okBtn.removeEventListener('click', onOk);
                    resolve(v);
                }

                function onHide() {
                    cleanup(false);
                }

                function onOk() {
                    bsModal.hide();
                    cleanup(true);
                }
                modalEl.addEventListener('hidden.bs.modal', onHide, {
                    once: true
                });
                okBtn.addEventListener('click', onOk, {
                    once: true
                });
                bsModal.show();
            });
        }

        // Attach logout confirm
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('form[action$="logout"] button');
            if (!btn) return;
            e.preventDefault();
            const form = btn.closest('form');
            confirmAction({
                    title: 'Konfirmasi Logout',
                    message: 'Apakah Anda yakin ingin keluar?',
                    confirmText: 'Logout',
                    icon: 'right-from-bracket',
                    variant: 'warning'
                })
                .then(ok => {
                    if (ok) {
                        showLoading();
                        form.submit();
                    }
                });
        });

        // Smooth scrolling
        // Add smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add ripple effect to nav items
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                // Create ripple element
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.cssText = `
                    position: absolute;
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    background: rgba(255, 255, 255, 0.3);
                    border-radius: 50%;
                    transform: scale(0);
                    animation: ripple 0.6s ease-out;
                    pointer-events: none;
                    z-index: 0;
                `;

                this.appendChild(ripple);

                // Remove ripple after animation
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // Add CSS for ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(2);
                    opacity: 0;
                }
            }

            .nav-item {
                position: relative;
                overflow: hidden;
            }
        `;
        document.head.appendChild(style);

        // Add loading state to buttons
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (!this.classList.contains('no-loading')) {
                    this.style.opacity = '0.7';
                    setTimeout(() => {
                        this.style.opacity = '1';
                    }, 1000);
                }
            });
        });

        // Auto-hide alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.remove();
                }, 500);
            });
        }, 5000);

        // Basic helper functions
        function bulkSelect() {
            showToast('Bulk selection mode activated', 'info');
            // Add bulk selection logic here
        }

        // (Old toast replaced above with animated version)
    </script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>
