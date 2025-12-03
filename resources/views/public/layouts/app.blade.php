@php
    use Illuminate\Support\Facades\Storage;
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $profile->name ?? 'Dinas Koperasi')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    {{-- Responsive Styles - All Media Queries Organized --}}
    <link href="{{ asset('css/public-responsive.css') }}" rel="stylesheet">

    @if (isset($profile) && $profile->logo && Storage::disk('public')->exists($profile->logo))
        <link rel="icon" type="image/png" href="{{ Storage::url($profile->logo) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @endif

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

        :root {
            --primary: #4F46E5;
            --primary-light: #8B5CF6;
            --secondary: #06B6D4;
            --accent: #F59E0B;
            --success: #10B981;
            --dark: #0F172A;
            --gray-50: #F8FAFC;
            --gray-100: #F1F5F9;
            --gray-200: #E2E8F0;
            --gray-300: #CBD5E1;
            --gray-500: #64748B;
            --gray-900: #0F172A;
            --gradient-primary: linear-gradient(135deg, #4F46E5 0%, #8B5CF6 50%, #06B6D4 100%);
            --gradient-secondary: linear-gradient(135deg, #F59E0B 0%, #EF4444 100%);
            --gradient-hero: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: var(--gray-900);
            overflow-x: hidden;
            padding-top: 0;
            /* Removed padding for overlay effect */
            background: var(--gray-50);
            max-width: 100vw;
            position: relative;
        }

        /* Prevent horizontal scroll from any element */
        html {
            overflow-x: hidden;
            max-width: 100%;
        }

        /* Ensure all containers respect viewport width */
        .container,
        .container-fluid,
        .row,
        section {
            max-width: 100%;
        }

        /* Only prevent horizontal scroll on body and html */
        body,
        html {
            overflow-x: hidden;
        }

        /* Container centering for all sections */
        .container {
            margin-left: auto;
            margin-right: auto;
            padding-left: 15px;
            padding-right: 15px;
        }

        .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }

        .row {
            margin-left: 0;
            margin-right: 0;
            padding: 10px 0;
        }

        .row > * {
            padding-left: 15px;
            padding-right: 15px;
        }

        /* Fix for elements that might overflow */
        * {
            max-width: 100%;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        /* Ensure no unwanted spacing on any device */
        html,
        body {
            margin: 0;
            padding: 0;
        }

        @media (max-width: 768px) {
            body {
                padding-top: 0;
                /* Keep no padding for overlay */
                padding-bottom: 0;
                /* Minimal space for bottom nav */
            }

            /* Remove any unwanted top margins/paddings on mobile */
            .container,
            .container-fluid {
                margin-top: 0 !important;
                padding-top: 0 !important;
            }

            /* Ensure first elements don't have excessive top margin */
            .main-content>*:first-child,
            .container>*:first-child,
            .container-fluid>*:first-child {
                margin-top: 0 !important;
            }

            /* Specific fix for pages with card layouts */
            .card:first-child {
                margin-top: 0 !important;
            }

            /* Fix for gallery and other hero sections */
            .gallery-hero-section,
            .profile-hero-section,
            .layanan-hero-section,
            .struktur-hero-section,
            .berita-hero-section,
            .download-hero-section,
            .ulasan-hero-section {
                margin-top: 0 !important;
                padding-top: 20px !important;
            }

            /* Ensure content sections start properly */
            section:first-child,
            .section:first-child {
                margin-top: 0 !important;
                padding-top: 20px !important;
            }
        }

        /* ===== FLOATING NAVBAR (Desktop & Tablet Only) ===== */
        .floating-navbar {
            position: fixed;
            top: 50px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 40px;
            box-shadow:
                0 15px 40px rgba(0, 0, 0, 0.08),
                0 6px 20px rgba(0, 0, 0, 0.04);
            padding: 5px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: auto;
            max-width: calc(100vw - 40px);
            display: block;
            /* Always visible on desktop */
        }

        .floating-navbar.scrolled {
            background: rgba(255, 255, 255, 0.95);
            box-shadow:
                0 20px 50px rgba(0, 0, 0, 0.12),
                0 8px 25px rgba(0, 0, 0, 0.06);
            border-color: rgba(255, 255, 255, 0.4);
            top: 20px;
            /* Move closer to top when scrolled */
        }

        .navbar-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: var(--gray-900);
            font-weight: 700;
            font-size: 1rem;
            gap: 10px;
            margin-right: 25px;
        }

        .navbar-brand img {
            width: 50px;
            height: 50px;
            min-width: 50px;
            min-height: 50px;
            border-radius: 50%;
            object-fit: contain;
            border: 2px solid rgba(79, 70, 229, 0.2);
            transition: all 0.3s ease;
            margin-right: 20px;
            background: white;
            padding: 3px;
            aspect-ratio: 1 / 1;
        }

        .navbar-brand:hover {
            color: var(--primary);
        }

        .navbar-brand:hover img {
            border-color: var(--primary);
            transform: scale(1.05);
        }

        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 6px;
            list-style: none;
            margin: 0;
            padding: 0;
            flex-direction: row;
        }

        .nav-item {
            position: relative;
            flex-shrink: 0;
        }

        .nav-link {
            color: var(--gray-700);
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            position: relative;
            white-space: nowrap;
            display: flex;
            align-items: center;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--primary);
            background: rgba(79, 70, 229, 0.1);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.15);
        }

        .nav-link i {
            margin-right: 5px;
            font-size: 0.8rem;
        }

        /* Hide mobile elements on desktop */
        .navbar-toggle,
        .mobile-menu {
            display: none;
        }

        /* ===== BOTTOM NAVIGATION - STANDARDIZED ===== */
        .bottom-nav {
            position: fixed !important;
            bottom: 20px !important;
            left: 50% !important;
            right: auto !important;
            transform: translateX(-50%) !important;
            width: calc(100% - 40px) !important;
            max-width: 600px !important;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            box-shadow:
                0 10px 40px rgba(0, 0, 0, 0.1),
                0 4px 15px rgba(0, 0, 0, 0.06);
            padding: 12px 16px;
            z-index: 999;
            display: none !important;
            box-sizing: border-box;
            opacity: 1;
            transition: none;
            margin: 0 !important;
            top: auto !important;
            visibility: visible !important;
        }

        .bottom-nav.loaded {
            opacity: 1;
        }

        .bottom-nav-container {
            display: flex;
            justify-content: space-around;
            align-items: center;
            gap: 8px;
            position: relative;
            width: 100%;
        }

        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: var(--gray-500);
            transition: all 0.3s ease;
            padding: 8px 12px;
            border-radius: 14px;
            min-width: 70px;
            flex: 1;
            text-align: center;
            position: relative;
        }

        .bottom-nav-item:hover,
        .bottom-nav-item.active {
            color: var(--primary);
            background: rgba(79, 70, 229, 0.1);
            transform: translateY(-2px);
        }

        .bottom-nav-item i {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 4px;
        }

        .bottom-nav-item span {
            font-size: 13px;
            font-weight: 500;
            line-height: 1.2;
        }

        /* Dropdown Styles */
        .bottom-nav-dropdown {
            position: relative;
        }

        .dropdown-menu-custom {
            position: absolute;
            bottom: 100%;
            right: 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            padding: 8px;
            margin-bottom: 10px;
            min-width: 150px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px) scale(0.95);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
        }

        .dropdown-menu-custom.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
        }

        .dropdown-item-custom {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            text-decoration: none;
            color: var(--gray-700);
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s ease;
            margin-bottom: 2px;
        }

        .dropdown-item-custom:hover,
        .dropdown-item-custom.active {
            background: rgba(79, 70, 229, 0.1);
            color: var(--primary);
        }

        .dropdown-item-custom i {
            margin-right: 10px;
            font-size: 1rem;
            width: 18px;
            text-align: center;
        }

        /* Highlight dropdown trigger when any dropdown item is active */
        .bottom-nav-dropdown .bottom-nav-item.has-active {
            color: var(--primary);
            background: rgba(79, 70, 229, 0.1);
        }

        /* Arrow pointer for dropdown */
        .dropdown-menu-custom::after {
            content: '';
            position: absolute;
            top: 100%;
            right: 20px;
            border: 6px solid transparent;
            border-top-color: rgba(255, 255, 255, 0.98);
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        /* ===== TABLET BREAKPOINTS - CONSISTENT SIZING ===== */
        /* All tablets use same icon size (60px) and padding */

        /* Show bottom nav only on smartphones */
        @media (max-width: 767px) {
            .bottom-nav {
                display: block !important;
                padding: 10px 12px;
                bottom: 15px !important;
            }

            .bottom-nav-item i {
                width: 30px;
                height: 30px;
                font-size: 16px;
            }

            .bottom-nav-item span {
                font-size: 12px;
            }

            body {
                padding-bottom: 100px;
            }
        }

        /* Large Tablet / Small Desktop (1024px - 1400px) */
        @media (max-width: 1400px) and (min-width: 1024px) {
            .bottom-nav {
                display: block !important;
                max-width: 620px !important;
                width: calc(100% - 60px) !important;
                padding: 12px 16px;
                bottom: 20px !important;
            }

            .bottom-nav-container {
                gap: 12px;
            }

            .bottom-nav-item {
                min-width: 80px;
                padding: 8px 12px;
            }

            .bottom-nav-item i {
                width: 38px;
                height: 38px;
                font-size: 20px;
            }

            .bottom-nav-item span {
                font-size: 13px;
            }

            body {
                padding-bottom: 120px;
            }
        }

        /* iPad Air & Tablet (768px - 1023px) */
        @media (max-width: 1023px) and (min-width: 768px) {
            .bottom-nav {
                display: block !important;
                max-width: 580px !important;
                width: calc(100% - 50px) !important;
                padding: 12px 16px;
                bottom: 20px !important;
            }

            .bottom-nav-container {
                gap: 10px;
            }

            .bottom-nav-item {
                min-width: 75px;
                padding: 8px 12px;
            }

            .bottom-nav-item i {
                width: 38px;
                height: 38px;
                font-size: 20px;
            }

            .bottom-nav-item span {
                font-size: 13px;
            }

            body {
                padding-bottom: 120px;
            }
        }

        /* ===== SMARTPHONE BREAKPOINTS - CONSISTENT SIZING ===== */
        /* All smartphones use same icon size (50px) and padding */

        /* Optimize for smaller smartphones */
        @media (max-width: 576px) {
            .bottom-nav {
                bottom: 15px !important;
                width: calc(100% - 30px) !important;
                max-width: 400px !important;
                padding: 10px 12px;
            }

            .bottom-nav-container {
                gap: 6px;
            }

            .bottom-nav-item {
                min-width: 65px;
                padding: 6px 8px;
            }

            .bottom-nav-item i {
                width: 30px;
                height: 30px;
                font-size: 16px;
            }

            .bottom-nav-item span {
                font-size: 12px;
            }

            body {
                padding-bottom: 100px;
            }
        }

        /* Extra small screens */
        @media (max-width: 375px) {
            .bottom-nav {
                bottom: 15px !important;
                width: calc(100% - 20px) !important;
                max-width: 350px !important;
                padding: 10px 12px;
            }

            .bottom-nav-container {
                gap: 4px;
            }

            .bottom-nav-item {
                min-width: 60px;
                padding: 6px 6px;
            }

            .bottom-nav-item i {
                width: 30px;
                height: 30px;
                font-size: 16px;
            }

            .bottom-nav-item span {
                font-size: 11px;
            }

            body {
                padding-bottom: 100px;
            }
        }
                font-size: 0.55rem;
            }

            body {
                padding-bottom: 75px;
            }
        }

        /* Hide bottom nav on large desktop only */
        @media (min-width: 1401px) {
            .bottom-nav {
                display: none !important;
            }

            body {
                padding-bottom: 0 !important;
            }

            .floating-navbar {
                display: block !important;
            }
        }

        /* Show bottom nav on tablet/small desktop (1024-1400px) - handled above */
        @media (max-width: 1400px) and (min-width: 1024px) {
            .floating-navbar {
                display: none !important;
            }

            .bottom-nav {
                display: block !important;
            }

            /* Reduce top spacing on tablet */
            .main-content {
                padding-top: 10px;
            }

            /* Center container on tablet - EQUAL spacing */
            .container {
                width: 100%;
                max-width: 100%;
                padding-left: 40px;
                padding-right: 40px;
                margin-left: auto;
                margin-right: auto;
            }

            .row {
                margin-left: 0;
                margin-right: 0;
            }

            /* Center all content on tablet */
            .section .container {
                text-align: center;
            }

            /* Headings center */
            .section h1,
            .section h2,
            .section h3,
            .section h4,
            .section h5,
            .section h6 {
                text-align: center !important;
            }

            /* Paragraphs left-aligned */
            .section p,
            .section .lead,
            .section ul,
            .section ol,
            .section li {
                text-align: left !important;
            }

            /* Override text-start to center only for headings */
            .section .text-start h1,
            .section .text-start h2,
            .section .text-start h3,
            .section .text-left h1,
            .section .text-left h2,
            .section .text-left h3 {
                text-align: center !important;
            }

            .section .row {
                justify-content: center;
            }
        }

        /* Tablet medium (768px - 1023px) */
        @media (max-width: 1023px) and (min-width: 768px) {
            /* Reduce top spacing on tablet */
            .main-content {
                padding-top: 10px;
            }

            .container {
                width: 100%;
                max-width: 100%;
                padding-left: 30px;
                padding-right: 30px;
                margin-left: auto;
                margin-right: auto;
            }

            .row {
                margin-left: 0;
                margin-right: 0;
            }

            /* Center all content on tablet */
            .section .container {
                text-align: center;
            }

            /* Headings center */
            .section h1,
            .section h2,
            .section h3,
            .section h4,
            .section h5,
            .section h6 {
                text-align: center !important;
            }

            /* Paragraphs left-aligned */
            .section p,
            .section .lead,
            .section ul,
            .section ol,
            .section li {
                text-align: left !important;
            }

            /* Override text-start to center only for headings */
            .section .text-start h1,
            .section .text-start h2,
            .section .text-start h3,
            .section .text-left h1,
            .section .text-left h2,
            .section .text-left h3 {
                text-align: center !important;
            }

            .section .row {
                justify-content: center;
            }
        }

        /* Mobile (< 768px) */
        @media (max-width: 767px) {
            .container {
                width: 100%;
                max-width: 100%;
                padding-left: 20px;
                padding-right: 20px;
                margin-left: auto;
                margin-right: auto;
            }

            .row {
                margin-left: 0;
                margin-right: 0;
            }

            /* Center all content on mobile */
            .section .container {
                text-align: center;
            }

            /* Headings center */
            .section h1,
            .section h2,
            .section h3,
            .section h4,
            .section h5,
            .section h6 {
                text-align: center !important;
            }

            /* Paragraphs left-aligned */
            .section p,
            .section .lead,
            .section ul,
            .section ol,
            .section li {
                text-align: left !important;
            }

            /* Override text-start to center only for headings */
            .section .text-start h1,
            .section .text-start h2,
            .section .text-start h3,
            .section .text-left h1,
            .section .text-left h2,
            .section .text-left h3 {
                text-align: center !important;
            }

            .section .row {
                justify-content: center;
            }
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            min-height: 100vh;
            padding-top: 20px;
        }

        /* Mobile responsive adjustments */
        @media (max-width: 820px) {
            /* === NAVIGATION - Force bottom nav to show === */
            .floating-navbar {
                display: none !important;
            }

            .bottom-nav {
                display: block !important;
            }

            /* === CONTENT === */
            .main-content {
                padding-top: 20px;
                /* Consistent top spacing for mobile */
            }

            .hero {
                min-height: calc(100vh - 20px);
                /* Adjust hero height to account for top spacing */
                margin-top: 0;
            }

            /* Ensure content starts right at the top with minimal spacing */
            .container-fluid,
            .container {
                padding-top: 0;
            }

            /* Remove any extra spacing from sections on mobile */
            .section {
                padding: 40px 0;
            }

            /* Universal hero sections for mobile */
            .page-hero-section,
            .gallery-hero-section,
            .profile-hero-section,
            .layanan-hero-section,
            .struktur-hero-section,
            .structure-hero-section,
            .berita-hero-section,
            .download-hero-section,
            .ulasan-hero-section,
            .reviews-hero-section,
            .services-hero-section,
            .news-hero-section {
                min-height: 40vh !important;
                margin: 20px 10px 20px 10px !important;
                border-radius: 20px !important;
                padding: 25px 0 !important;
            }

            .page-hero-section::before,
            .gallery-hero-section::before,
            .profile-hero-section::before,
            .layanan-hero-section::before,
            .struktur-hero-section::before,
            .structure-hero-section::before,
            .berita-hero-section::before,
            .download-hero-section::before,
            .ulasan-hero-section::before,
            .reviews-hero-section::before,
            .services-hero-section::before,
            .news-hero-section::before {
                border-radius: 20px !important;
            }

            .hero-title {
                font-size: 1.8rem !important;
            }

            .hero-subtitle {
                font-size: 0.95rem !important;
            }

            /* Specifically for pages without hero sections */
            .main-content:not(.hero-page) {
                padding-top: 20px;
            }

            /* Ensure news hero section has proper margin and border radius */
            .news-hero-section {
                margin: 20px 10px 20px 10px !important;
                border-radius: 20px !important;
            }
        }

        @media (max-width: 576px) {
            .main-content {
                padding-top: 15px;
            }

            .hero {
                min-height: calc(100vh - 15px);
            }

            .section {
                padding: 30px 0;
            }

            /* Universal hero sections for smaller mobile */
            .page-hero-section,
            .gallery-hero-section,
            .profile-hero-section,
            .layanan-hero-section,
            .struktur-hero-section,
            .structure-hero-section,
            .berita-hero-section,
            .download-hero-section,
            .ulasan-hero-section,
            .reviews-hero-section,
            .services-hero-section,
            .news-hero-section {
                min-height: 35vh !important;
                margin: 15px 8px 15px 8px !important;
                border-radius: 18px !important;
                padding: 20px 0 !important;
            }

            .page-hero-section::before,
            .gallery-hero-section::before,
            .profile-hero-section::before,
            .layanan-hero-section::before,
            .struktur-hero-section::before,
            .structure-hero-section::before,
            .berita-hero-section::before,
            .download-hero-section::before,
            .ulasan-hero-section::before,
            .reviews-hero-section::before,
            .services-hero-section::before,
            .news-hero-section::before {
                border-radius: 18px !important;
            }

            .hero-title {
                font-size: 1.6rem !important;
            }

            .hero-subtitle {
                font-size: 0.9rem !important;
            }

            /* Additional fixes for smaller screens */
            .gallery-hero-section,
            .profile-hero-section,
            .layanan-hero-section,
            .struktur-hero-section,
            .berita-hero-section,
            .download-hero-section,
            .ulasan-hero-section,
            .news-hero-section {
                padding-top: 15px !important;
            }

            /* Specific fix for news hero section on small mobile */
            .news-hero-section {
                margin: 15px 8px 15px 8px !important;
                border-radius: 18px !important;
            }

            section:first-child,
            .section:first-child {
                padding-top: 15px !important;
            }
        }

        @media (max-width: 375px) {
            .main-content {
                padding-top: 10px;
            }

            .hero {
                min-height: calc(100vh - 10px);
             }

            .section {
                padding: 25px 0;
            }

            /* Universal hero sections for extra small screens */
            .page-hero-section,
            .gallery-hero-section,
            .profile-hero-section,
            .layanan-hero-section,
            .struktur-hero-section,
            .structure-hero-section,
            .berita-hero-section,
            .download-hero-section,
            .ulasan-hero-section,
            .reviews-hero-section,
            .services-hero-section,
            .news-hero-section {
                min-height: 32vh !important;
                margin: 0 5px 10px 5px !important;
                border-radius: 15px !important;
                padding: 15px 0 !important;
            }

            .page-hero-section::before,
            .gallery-hero-section::before,
            .profile-hero-section::before,
            .layanan-hero-section::before,
            .struktur-hero-section::before,
            .structure-hero-section::before,
            .berita-hero-section::before,
            .download-hero-section::before,
            .ulasan-hero-section::before,
            .reviews-hero-section::before,
            .services-hero-section::before,
            .news-hero-section::before {
                border-radius: 15px !important;
            }

            .hero-title {
                font-size: 1.4rem !important;
            }

            .hero-subtitle {
                font-size: 0.85rem !important;
            }

            /* Additional fixes for extra small screens */
            .gallery-hero-section,
            .profile-hero-section,
            .layanan-hero-section,
            .struktur-hero-section,
            .berita-hero-section,
            .download-hero-section,
            .ulasan-hero-section {
                padding-top: 10px !important;
            }

            section:first-child,
            .section:first-child {
                padding-top: 10px !important;
            }
        }

        /* ===== UTILITIES ===== */
        .section {
            padding: 80px 60px;
        }

        @media (max-width: 1200px) {
            .section {
                padding: 70px 40px;
            }
        }

        @media (max-width: 991px) {
            .section {
                padding: 60px 30px;
            }
        }

        @media (max-width: 767px) {
            .section {
                padding: 40px 20px;
            }
        }

        @media (max-width: 576px) {
            .section {
                padding: 30px 15px;
            }
        }

        /* Container padding utilities for consistent spacing */
        .container.px-lg-5 {
            padding-left: 3rem !important;
            padding-right: 3rem !important;
        }

        @media (max-width: 1199px) {
            .container.px-lg-5 {
                padding-left: 1.5rem !important;
                padding-right: 1.5rem !important;
            }
        }

        @media (max-width: 991px) {
            .container.px-lg-5,
            .container.px-md-4 {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
        }

        @media (max-width: 767px) {
            .container.px-lg-5,
            .container.px-md-4 {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }
        }

        /* ===== UNIVERSAL HERO SECTIONS ===== */
        .page-hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 50vh;
            margin: 20px 15px 30px 15px;
            border-radius: 25px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding: 40px 0;
        }

        .page-hero-section::before,
        .gallery-hero-section::before,
        .profile-hero-section::before,
        .layanan-hero-section::before,
        .struktur-hero-section::before,
        .structure-hero-section::before,
        .berita-hero-section::before,
        .download-hero-section::before,
        .ulasan-hero-section::before,
        .reviews-hero-section::before,
        .services-hero-section::before,
        .news-hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.9), rgba(139, 92, 246, 0.8));
            z-index: 1;
            border-radius: 25px;
        }

        .page-hero-section .container,
        .gallery-hero-section .container,
        .profile-hero-section .container,
        .layanan-hero-section .container,
        .struktur-hero-section .container,
        .structure-hero-section .container,
        .berita-hero-section .container,
        .download-hero-section .container,
        .ulasan-hero-section .container,
        .reviews-hero-section .container,
        .services-hero-section .container,
        .news-hero-section .container {
            position: relative;
            z-index: 2;
        }

        /* Universal hero content styling */
        .hero-content {
            text-align: center;
            color: white;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            padding: 8px 16px;
            font-size: 0.85rem;
            font-weight: 500;
            backdrop-filter: blur(10px);
            margin-bottom: 16px;
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 16px;
            color: white;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 24px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Search form in hero */
        .search-form {
            max-width: 500px;
            margin: 0 auto;
        }

        .search-container {
            position: relative;
            display: flex;
            background: white;
            border-radius: 50px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .search-input {
            flex: 1;
            border: none;
            padding: 12px 20px;
            font-size: 1rem;
            outline: none;
            background: transparent;
        }

        .search-btn {
            background: var(--primary);
            border: none;
            padding: 12px 20px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-btn:hover {
            background: var(--primary-light);
        }

        /* Remove incorrect override for hero sections */
        /* Hero sections should use their specific styling defined later in the CSS */

        /* ===== HERO SECTION ===== */
        .hero {
            background: var(--gradient-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.9), rgba(139, 92, 246, 0.7));
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            color: white;
            text-align: center;
        }

        /* ===== HERO SLIDESHOW ===== */
        .hero-slideshow {
            position: relative;
            height: 0;
            padding-bottom: 50%;
            display: flex;
            align-items: center;
            overflow: hidden;
            margin: 0;
            border-radius: 30px;
        }

        .slideshow-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            border-radius: 30px;
            overflow: hidden;
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            border-radius: 30px;
        }

        .slide.active {
            opacity: 1;
        }

        .slide-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 2;
            border-radius: 30px;
        }

        .hero-slideshow .container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 3;
            width: 100%;
            max-width: 100%;
            padding: 0;
        }

        .hero-slideshow .row {
            margin: 0;
            width: 100%;
        }

        .hero-slideshow .hero-content {
            position: relative;
            color: white;
            text-align: center;
            width: 100%;
            max-width: 1400px;
            padding: 0 40px;
            margin: 0 auto;
        }

        .hero-slideshow .hero-content h1 {
            font-weight: 700;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
            margin-bottom: 15px;
            font-size: 3rem;
        }

        .hero-slideshow .hero-content .lead {
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);
            margin-bottom: 20px;
            font-size: 1.25rem;
        }

        /* ===== RESPONSIVE HERO ===== */
        /* Large Tablets & Small Desktops (1024px - 1400px) */
        @media (max-width: 1400px) and (min-width: 1024px) {
            .hero-slideshow {
                padding-bottom: 48%;
                margin-top: 10px !important;
                margin-bottom: 20px;
            }

            .hero-slideshow .hero-content h1 {
                font-size: 2.75rem;
            }

            .hero-slideshow .hero-content .lead {
                font-size: 1.15rem;
            }
        }

        /* iPad Air & Medium Tablets (768px - 1023px) */
        @media (max-width: 1023px) and (min-width: 768px) {
            .hero-slideshow {
                padding-bottom: 52%;
                margin-top: 10px !important;
                margin-bottom: 20px;
            }

            .hero-slideshow .hero-content {
                padding: 0 30px;
            }

            .hero-slideshow .hero-content h1 {
                font-size: 2.5rem;
            }

            .hero-slideshow .hero-content .lead {
                font-size: 1.1rem;
            }
        }

        /* Mobile Devices */
        @media (max-width: 767px) {
            .hero-slideshow {
                padding-bottom: 60%;
                margin-top: 0;
            }

            .hero-slideshow .hero-content {
                padding: 0 20px;
            }

            .hero-slideshow .hero-content h1 {
                font-size: 2rem !important;
            }

            .hero-slideshow .hero-content .lead {
                font-size: 1rem !important;
            }

            .btn-gradient,
            .btn-outline-light {
                padding: 12px 25px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .hero-slideshow {
                padding-bottom: 70%;
                margin: 0px 15px 0px 15px !important;
            }

            .hero-slideshow .hero-content {
                padding: 0 15px;
            }

            .hero-slideshow .hero-content h1 {
                font-size: 1.75rem !important;
            }

            .d-flex.gap-3 {
                gap: 0.75rem !important;
            }
        }

        /* ===== BUTTONS ===== */
        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-gradient {
            background: var(--gradient-primary);
            border: none;
            border-radius: 50px;
            padding: 15px 35px;
            font-weight: 600;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
        }

        .btn-gradient:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(79, 70, 229, 0.4);
            color: white;
        }

        .btn-outline-light {
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            padding: 13px 35px;
            font-weight: 600;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-3px);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(79, 70, 229, 0.3);
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-on-scroll {
            animation: fadeInUp 0.8s ease-out;
        }

        /* ===== TESTIMONIAL CARDS ===== */
        .testimonial-card {
            transition: all 0.3s ease;
            border-radius: 15px;
            overflow: hidden;
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
        }

        .testimonial-card .card-body {
            background: white;
            position: relative;
        }

        .testimonial-card .star-rating {
            font-size: 1rem;
        }

        .testimonial-card .star-rating i {
            margin: 0 2px;
        }

        .testimonial-card blockquote p {
            font-size: 0.95rem;
            line-height: 1.6;
            min-height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-circle {
            width: 50px;
            height: 50px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            margin-bottom: 0.75rem;
        }

        .testimonial-card .card-title {
            color: var(--gray-900);
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            .testimonial-card blockquote p {
                min-height: auto;
                font-size: 0.9rem;
            }

            .avatar-circle {
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
            }
        }

        /* ===== SERVICE CARDS ===== */
        .service-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 0;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
            border-color: rgba(79, 70, 229, 0.3);
        }

        .service-card-header {
            position: relative;
            overflow: hidden;
        }

        .service-image {
            position: relative;
            height: 280px;
            overflow: hidden;
        }

        .service-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .service-card:hover .service-img {
            transform: scale(1.05);
        }

        .service-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            z-index: 2;
        }

        .badge-text {
            background: var(--gradient-primary);
            color: white;
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .service-icon-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 120px;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        .service-icon {
            width: 80px;
            height: 80px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            transition: all 0.3s ease;
        }

        .service-card:hover .service-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .service-card-body {
            padding: 24px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .service-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .service-description {
            color: var(--gray-600);
            line-height: 1.6;
            margin-bottom: 20px;
            flex: 1;
        }

        .service-action {
            margin-top: auto;
        }

        .service-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            padding: 8px 0;
        }

        .service-btn:hover {
            color: var(--primary-dark);
            gap: 12px;
        }

        .service-btn i {
            transition: transform 0.3s ease;
        }

        .service-btn:hover i {
            transform: translateX(4px);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 2rem;
        }

        .empty-title {
            color: var(--gray-700);
            margin-bottom: 8px;
        }

        .empty-subtitle {
            color: var(--gray-500);
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .service-card-body {
                padding: 20px;
            }

            .service-image {
                height: 240px;
            }

            .service-icon-container {
                height: 100px;
            }

            .service-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
        }

        /* ===== ABOUT SECTION ===== */
        .about-content {
            padding-right: 1.5rem;
        }

        .about-image-section {
            padding-left: 1.5rem;
        }

        .about-content .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.2;
        }

        .about-description p {
            font-size: 1.1rem;
            line-height: 1.7;
        }

        .vision-mission h5 {
            font-size: 1.2rem;
        }

        .vision-mission p {
            font-size: 1rem;
            line-height: 1.6;
        }

        .leader-photo-container {
            width: 200px;
            height: 200px;
            margin: 0 auto;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid rgba(79, 70, 229, 0.2);
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(79, 70, 229, 0.15);
        }

        .leader-photo-container:hover {
            border-color: var(--primary);
            transform: scale(1.05);
        }

        .leader-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .leader-photo-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            color: #9ca3af;
        }

        .leader-info h4 {
            font-size: 1.5rem;
            color: var(--gray-900);
        }

        .leader-info .text-primary {
            color: var(--primary) !important;
        }

        .leader-quote {
            background: rgba(79, 70, 229, 0.05) !important;
            border-left: 4px solid var(--primary);
        }

        .leader-quote .fa-quote-left {
            font-size: 1.5rem;
            color: var(--primary);
        }

        .placeholder-content {
            background: var(--gray-100) !important;
            min-height: 250px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        @media (max-width: 991.98px) {
            .about-content {
                padding-right: 0;
                margin-bottom: 2rem;
            }

            .about-image-section {
                padding-left: 0;
            }

            .about-content .section-title {
                font-size: 2rem;
                text-align: center !important;
            }

            .leader-photo-container {
                width: 150px;
                height: 150px;
            }
        }

        @media (max-width: 768px) {
            .about-content .section-title {
                font-size: 1.75rem;
            }

            .about-description p {
                font-size: 1rem;
            }

            .leader-photo-container {
                width: 120px;
                height: 120px;
            }

            .leader-info h4 {
                font-size: 1.25rem;
            }
        }

        /* ===== FOOTER ===== */
        footer {
            background: var(--dark);
            color: var(--gray-300);
            padding: 4rem 0 2rem;
            margin-top: 0;
            position: relative;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--gradient-primary);
        }

        /* Gallery Cards */
        .gallery-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
        }

        .gallery-card:hover {
            /* Removed excessive hover effects */
        }

        .gallery-item {
            display: block;
            text-decoration: none;
            color: inherit;
        }

        .gallery-image {
            position: relative;
            overflow: hidden;
            aspect-ratio: 1;
        }

        .gallery-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Removed transform transition */
        }

        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .gallery-card:hover .gallery-overlay {
            opacity: 1;
        }

        .gallery-card:hover .gallery-img {
            /* Removed scale transform */
        }

        .gallery-icon {
            color: white;
            font-size: 2rem;
            transform: scale(0.8);
            transition: transform 0.3s ease;
        }

        .gallery-card:hover .gallery-icon {
            transform: scale(1);
        }

        .gallery-info {
            padding: 15px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        .gallery-title {
            margin: 0;
            font-size: 0.9rem;
            font-weight: 500;
            color: #374151;
            line-height: 1.4;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 1rem;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .empty-icon {
            font-size: 3rem;
            color: #9CA3AF;
            margin-bottom: 1rem;
        }

        .empty-title {
            color: #6B7280;
            margin-bottom: 0.5rem;
        }

        .empty-subtitle {
            color: #9CA3AF;
            margin: 0;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .gallery-card {
                border-radius: 8px;
            }

            .gallery-info {
                padding: 10px;
            }

            .gallery-title {
                font-size: 0.8rem;
            }

            .gallery-icon {
                font-size: 1.5rem;
            }
        }

        /* News Cards */
        .news-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .news-card-header {
            position: relative;
            overflow: hidden;
        }

        .news-image {
            position: relative;
            aspect-ratio: 16/9;
            overflow: hidden;
        }

        .news-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .news-card:hover .news-img {
            transform: scale(1.05);
        }

        .news-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--primary);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .news-date {
            position: absolute;
            bottom: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.95);
            color: #374151;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 500;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .news-card-body {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .news-title {
            margin: 0 0 12px 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: #1F2937;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .news-description {
            color: #6B7280;
            font-size: 0.9rem;
            line-height: 1.6;
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .news-action {
            margin-top: auto;
        }

        .news-btn {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .news-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(29, 78, 216, 0.3);
            color: white;
            text-decoration: none;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .news-card {
                border-radius: 8px;
            }

            .news-card-body {
                padding: 15px;
            }

            .news-title {
                font-size: 1rem;
            }

            .news-description {
                font-size: 0.85rem;
            }

            .news-badge {
                top: 10px;
                left: 10px;
                padding: 4px 8px;
                font-size: 0.7rem;
            }

            .news-date {
                bottom: 10px;
                right: 10px;
                padding: 6px 10px;
                font-size: 0.7rem;
            }
        }

        /* ===== PROFILE PAGE STYLES ===== */
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 55vh;
            margin: 120px 30px 30px 30px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding: 50px 0;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.9), rgba(139, 92, 246, 0.8));
            z-index: 1;
            border-radius: 50px;
        }

        .hero-section .container {
            position: relative;
            z-index: 2;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: white;
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
            max-width: 80%;
            margin: 0 auto;
        }

        .hero-quote {
            background: rgba(255, 255, 255, 0.1);
            border-left: 4px solid rgba(255, 255, 255, 0.5);
            padding: 20px;
            border-radius: 10px;
            color: rgba(255, 255, 255, 0.95);
            font-style: italic;
            backdrop-filter: blur(10px);
            margin-top: 2rem;
        }

        .hero-quote i {
            color: rgba(255, 255, 255, 0.7);
            margin: 0 8px;
        }

        .hero-image {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .profile-card {
            position: relative;
            width: 500px;
            height: 500px;
            border-radius: 20px;
            overflow: hidden;
            background: white;
            backdrop-filter: blur(20px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .profile-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            color: rgba(255, 255, 255, 0.8);
            font-size: 4rem;
        }

        .profile-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.9));
            padding: 40px 20px 15px;
            color: white;
        }

        .profile-info h5 {
            margin: 0 0 5px 0;
            font-weight: 600;
            font-size: 0.95rem;
            line-height: 1.3;
        }

        .profile-info p {
            margin: 0;
            font-size: 0.75rem;
            opacity: 0.9;
        }

        .section-modern {
            padding: 100px 0;
            background: #f8fafc;
        }

        .section-alternate {
            padding: 100px 0;
            background: white;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            text-align: center;
            color: #1f2937;
            margin-bottom: 1rem;
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: #6b7280;
            text-align: center;
            max-width: 600px;
            margin: 0 auto;
        }

        .vision-mission-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 40px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
        }

        .vision-mission-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .vision-mission-card .card-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 20px;
        }

        .vision-mission-card .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px auto; /* Center icon horizontally */
            font-size: 1.5rem;
            color: white;
        }

        .vision-icon {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .mission-icon {
            background: linear-gradient(135deg, #f093fb, #f5576c);
        }

        .vision-mission-card h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            text-align: center;
        }

        .vision-mission-card .card-body p {
            color: #6b7280;
            line-height: 1.7;
            font-size: 1rem;
            margin: 0;
            text-align: center;
        }

        /* ===== INFO CARD STYLES ===== */
        .info-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 32px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
        }

        .info-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .info-card-header {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 24px;
        }

        .info-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .info-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0 0 8px 0;
        }

        .info-subtitle {
            font-size: 0.9rem;
            color: #6b7280;
            margin: 0;
        }

        .info-content {
            line-height: 1.7;
            color: #374151;
        }

        .info-content p {
            margin: 0;
            text-align: justify;
        }

        /* ===== FEATURE CARD STYLES ===== */
        .feature-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px 24px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        }

        .feature-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 20px;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .feature-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 16px;
        }

        .feature-description {
            color: #6b7280;
            line-height: 1.6;
            margin: 0;
            text-align: justify;
        }

        .contact-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
        }

        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .contact-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 1.8rem;
        }

        .contact-card h5 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 15px;
        }

        .contact-card p {
            color: #6b7280;
            margin-bottom: 20px;
            font-size: 1rem;
        }

        .contact-btn {
            display: inline-flex;
            align-items: center;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
        }

        .contact-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {

            .hero-section,
            .news-hero-section,
            .gallery-hero-section,
            .services-hero-section,
            .download-hero-section,
            .reviews-hero-section,
            .structure-hero-section {
                margin: 100px 20px 20px 20px;
                border-radius: 40px;
                min-height: 50vh;
                padding: 40px 0;
            }

            .hero-section::before,
            .news-hero-section::before,
            .gallery-hero-section::before,
            .services-hero-section::before,
            .download-hero-section::before,
            .reviews-hero-section::before,
            .structure-hero-section::before {
                border-radius: 40px;
            }
        }

        @media (max-width: 768px) {
            .hero-section {
                min-height: 45vh;
                text-align: center;
                margin: 80px 15px 15px 15px;
                border-radius: 30px;
                padding: 40px 0;
            }

            .hero-section::before {
                border-radius: 30px;
            }

            .hero-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
                max-width: 100%;
            }

            .profile-card {
                width: 350px;
                height: 350px;
                margin: 2rem auto 0;
            }

            .profile-info h5 {
                font-size: 0.85rem;
            }

            .profile-info p {
                font-size: 0.7rem;
            }

            .section-modern,
            .section-alternate {
                padding: 60px 0;
            }

            .section-title {
                font-size: 2rem;
            }

            .vision-mission-card {
                padding: 30px 20px;
                margin-bottom: 20px;
            }

            .info-card {
                padding: 24px 20px;
                margin-bottom: 20px;
            }

            .info-card-header {
                flex-direction: row;
                text-align: left;
                gap: 16px;
            }

            .info-icon {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }

            .info-title {
                font-size: 1.2rem;
                text-align: left;
            }

            .feature-card {
                padding: 24px 20px;
                margin-bottom: 20px;
            }

            .feature-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .feature-title {
                font-size: 1.1rem;
            }

            .contact-card {
                padding: 25px 20px;
                margin-bottom: 20px;
            }

            .hero-quote {
                margin-top: 1.5rem;
                padding: 15px;
            }

            /* Apply to all hero sections */
            .news-hero-section,
            .gallery-hero-section,
            .services-hero-section,
            .download-hero-section,
            .reviews-hero-section,
            .structure-hero-section {
                min-height: 45vh;
                margin: 80px 15px 15px 15px;
                border-radius: 30px;
                text-align: center;
                padding: 40px 0;
            }

            .news-hero-section::before,
            .gallery-hero-section::before,
            .services-hero-section::before,
            .download-hero-section::before,
            .reviews-hero-section::before,
            .structure-hero-section::before {
                border-radius: 30px;
            }
        }

        @media (max-width: 576px) {

            /* ===== GENERAL MOBILE OPTIMIZATIONS ===== */
            body {
                font-size: 14px;
                line-height: 1.5;
            }

            .container {
                padding-left: 15px;
                padding-right: 15px;
            }

            /* ===== HERO SECTIONS ===== */
            .hero-title {
                font-size: 1.75rem;
                line-height: 1.3;
            }

            .hero-subtitle {
                font-size: 1rem;
                max-width: 100%;
                margin: 0;
                text-align: center;
            }

            .hero-badge {
                font-size: 0.8rem;
                padding: 6px 12px;
            }

            .hero-quote {
                margin-top: 1.5rem;
                padding: 15px;
                font-size: 0.9rem;
            }

            .hero-section,
            .news-hero-section,
            .gallery-hero-section,
            .services-hero-section,
            .download-hero-section,
            .reviews-hero-section,
            .structure-hero-section {
                margin: 70px 10px 10px 10px;
                border-radius: 25px;
                min-height: 35vh;
                padding: 25px 15px;
                text-align: center;
            }

            .hero-section::before,
            .news-hero-section::before,
            .gallery-hero-section::before,
            .services-hero-section::before,
            .download-hero-section::before,
            .reviews-hero-section::before,
            .structure-hero-section::before {
                border-radius: 25px;
            }

            /* ===== PROFILE CARDS ===== */
            .profile-card {
                width: 320px;
                height: 320px;
                margin: 0 auto;
            }

            .profile-placeholder {
                font-size: 2.5rem;
            }

            .profile-overlay {
                padding: 30px 15px 12px;
            }

            .profile-info h5 {
                font-size: 0.8rem;
                line-height: 1.2;
            }

            .profile-info p {
                font-size: 0.65rem;
            }

            /* ===== MODERN CARDS ===== */
            .modern-card {
                padding: 20px 15px;
                margin-bottom: 20px;
                border-radius: 15px;
            }

            .card-header {
                margin-bottom: 15px !important;
            }

            .card-header h3,
            .card-header h4,
            .card-header h5 {
                font-size: 1.1rem;
                line-height: 1.3;
            }

            .card-icon {
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
            }

            .card-body p {
                font-size: 0.9rem;
                line-height: 1.5;
            }

            /* ===== VISION MISSION CARDS ===== */
            .vision-mission-card {
                padding: 25px 20px;
                margin-bottom: 20px;
            }

            .vision-mission-card .card-header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .vision-mission-card .card-header h3 {
                font-size: 1.2rem;
            }

            .vision-mission-card .card-icon {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
                margin: 0 auto;
            }

            /* ===== INFO CARDS ===== */
            .info-card {
                padding: 20px 15px;
                margin-bottom: 20px;
            }

            .info-card-header {
                flex-direction: row;
                text-align: left;
                gap: 15px;
            }

            .info-icon {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }

            .info-title {
                font-size: 1.1rem;
                text-align: left;
            }

            /* ===== FEATURE CARDS ===== */
            .feature-card {
                padding: 20px 15px;
                margin-bottom: 20px;
            }

            .feature-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .feature-title {
                font-size: 1.1rem;
            }

            .feature-description {
                text-align: center;
            }

            /* ===== CONTACT CARDS ===== */
            .contact-card {
                padding: 20px 15px;
                text-align: center;
                margin-bottom: 20px;
            }

            .contact-card h5 {
                font-size: 1rem;
                margin: 15px 0 10px;
            }

            .contact-card p {
                font-size: 0.9rem;
                margin-bottom: 15px;
            }

            .contact-icon {
                width: 60px;
                height: 60px;
                font-size: 1.3rem;
                margin: 0 auto 15px;
            }

            .contact-btn {
                font-size: 0.85rem;
                padding: 10px 18px;
            }

            /* ===== SERVICE CARDS ===== */
            .service-card {
                margin-bottom: 20px;
                border-radius: 15px;
            }

            .service-card-body {
                padding: 20px 15px;
            }

            .service-image {
                height: 200px;
            }

            .service-icon-container {
                height: 80px;
            }

            .service-icon {
                width: 50px;
                height: 50px;
                font-size: 1.3rem;
            }

            .service-title {
                font-size: 1.1rem;
                text-align: center;
            }

            .service-description {
                text-align: center;
                font-size: 0.85rem;
            }

            /* ===== NEWS CARDS ===== */
            .news-card {
                margin-bottom: 20px;
                border-radius: 15px;
            }

            .news-card-body {
                padding: 20px 15px;
            }

            .news-title {
                font-size: 1rem;
                text-align: center;
            }

            .news-description {
                font-size: 0.85rem;
                text-align: center;
            }

            .news-badge {
                top: 10px;
                left: 10px;
                padding: 4px 8px;
                font-size: 0.7rem;
            }

            .news-date {
                bottom: 10px;
                right: 10px;
                padding: 6px 10px;
                font-size: 0.7rem;
            }

            /* ===== GALLERY CARDS ===== */
            .gallery-card {
                margin-bottom: 20px;
                border-radius: 12px;
            }

            .gallery-info {
                padding: 12px;
            }

            .gallery-title {
                font-size: 0.85rem;
                text-align: center;
            }

            /* ===== DOWNLOAD CARDS ===== */
            .download-card {
                margin-bottom: 20px;
            }

            .download-card-header {
                padding: 20px 15px;
            }

            .file-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .download-card-body {
                padding: 20px 15px;
            }

            .download-title {
                font-size: 1rem;
                text-align: center;
            }

            .download-description {
                text-align: center;
                font-size: 0.85rem;
            }

            /* ===== REVIEW CARDS ===== */
            .review-card {
                padding: 20px 15px;
                margin-bottom: 20px;
            }

            .reviewer-avatar {
                width: 50px;
                height: 50px;
            }

            .reviewer-name {
                font-size: 1rem;
            }

            .review-content {
                padding: 15px;
            }

            .review-text {
                font-size: 0.85rem;
                text-align: center;
            }

            /* ===== SECTIONS ===== */
            .section-title {
                font-size: 1.6rem;
                margin-bottom: 15px;
                text-align: center;
            }

            .section-subtitle {
                font-size: 0.9rem;
                margin-bottom: 30px;
                text-align: center;
            }

            .section,
            .section-modern,
            .section-alternate {
                padding: 40px 0;
            }

            /* ===== BUTTONS ===== */
            .btn {
                font-size: 0.85rem;
                padding: 10px 20px;
            }

            .btn-lg {
                font-size: 0.9rem;
                padding: 12px 24px;
            }

            .btn-gradient,
            .btn-outline-light {
                padding: 12px 20px;
                font-size: 0.85rem;
            }

            .d-flex.gap-3 {
                gap: 0.75rem !important;
                flex-direction: column;
                align-items: center;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }

            .cta-btn {
                width: 100%;
                max-width: 280px;
                justify-content: center;
            }

            /* ===== MAP CONTAINER ===== */
            .public-map-container {
                height: 250px;
                border-radius: 15px;
            }

            .custom-map .map-badge {
                font-size: 0.8rem;
                padding: 5px 8px;
            }

            /* ===== SEARCH FORM ===== */
            .search-container {
                margin: 0 10px;
                padding: 6px;
            }

            .search-input {
                padding: 12px 15px;
                font-size: 0.9rem;
            }

            .search-btn {
                padding: 12px 18px;
                font-size: 0.9rem;
            }

            /* ===== ORGANIZATION CHART ===== */
            .org-chart {
                padding: 20px 10px;
            }

            .org-card {
                padding: 15px;
                margin: 10px 5px;
                min-width: 100%;
                max-width: 300px;
            }

            .org-card h6 {
                font-size: 0.9rem;
                margin-bottom: 8px;
            }

            .org-card p {
                font-size: 0.8rem;
            }

            .org-photo {
                width: 50px;
                height: 50px;
            }

            .level2-container,
            .kabid-container {
                flex-direction: column;
                align-items: center;
                gap: 15px;
            }

            .level3-container,
            .staff-container {
                grid-template-columns: 1fr;
                gap: 12px;
                padding: 0 10px;
            }

            .org-connector.horizontal {
                display: none;
            }

            /* ===== CARDS GRID ADJUSTMENTS ===== */
            .row.g-4 {
                --bs-gutter-x: 1rem;
                --bs-gutter-y: 1rem;
            }

            .row.g-5 {
                --bs-gutter-x: 1rem;
                --bs-gutter-y: 1rem;
            }

            /* ===== TEXT UTILITIES ===== */
            .text-justify {
                text-align: center;
            }

            /* ===== HERO CONTENT ALIGNMENT ===== */
            .hero-content {
                text-align: center;
                padding: 0 15px;
            }

            .hero-content .row {
                text-align: center;
            }

            .hero-content .col-lg-6 {
                text-align: center;
                margin-bottom: 20px;
            }

            /* Bottom nav styles handled in main media queries above */

            /* ===== FOOTER ADJUSTMENTS ===== */
            footer {
                padding: 3rem 0 1.5rem;
                margin-bottom: 0;
            }

            footer .row {
                text-align: center;
            }

            footer .col-md-6:first-child {
                margin-bottom: 20px;
            }

            /* ===== MODAL ADJUSTMENTS ===== */
            .modal-dialog {
                margin: 20px 15px;
            }

            .modal-content {
                border-radius: 15px;
            }

            .modal-gallery-img {
                max-height: 50vh;
            }

            /* ===== EMPTY STATE ===== */
            .empty-state {
                padding: 2rem 1rem;
            }

            .empty-icon {
                font-size: 2rem;
            }

            .empty-title {
                font-size: 1.1rem;
            }

            .empty-subtitle {
                font-size: 0.9rem;
            }
        }

        .hero-section::before,
        .news-hero-section::before,
        .gallery-hero-section::before,
        .services-hero-section::before,
        .download-hero-section::before,
        .reviews-hero-section::before,
        .structure-hero-section::before {
            border-radius: 25px;
        }

        .profile-card {
            width: 280px;
            height: 280px;
        }

        .vision-mission-card .card-icon {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
            margin: 0 auto 15px auto; /* Center icon */
        }

        .contact-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
        }

        /* ===== NEWS PAGE STYLES ===== */
        .news-hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            min-height: 55vh !important;
            margin: 120px 30px 30px 30px !important;
            border-radius: 50px !important;
            display: flex !important;
            align-items: center !important;
            position: relative !important;
            overflow: hidden !important;
            padding: 50px 0 !important;
        }

        /* Ensure desktop styling for news hero section */
        @media (min-width: 769px) {
            .news-hero-section {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
                min-height: 55vh !important;
                margin: 120px 30px 30px 30px !important;
                border-radius: 50px !important;
                display: flex !important;
                align-items: center !important;
                position: relative !important;
                overflow: hidden !important;
                padding: 50px 0 !important;
            }
        }

        .news-hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.9), rgba(139, 92, 246, 0.8));
            z-index: 1;
            border-radius: 50px;
        }

        .news-hero-section .container {
            position: relative;
            z-index: 2;
        }

        /* ===== GALLERY PAGE STYLES ===== */
        .gallery-hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            min-height: 55vh !important;
            margin: 120px 30px 30px 30px !important;
            border-radius: 50px !important;
            display: flex !important;
            align-items: center !important;
            position: relative !important;
            overflow: hidden !important;
            padding: 50px 0 !important;
        }

        /* Ensure desktop styling for gallery hero section */
        @media (min-width: 769px) {
            .gallery-hero-section {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
                min-height: 55vh !important;
                margin: 120px 30px 30px 30px !important;
                border-radius: 50px !important;
                display: flex !important;
                align-items: center !important;
                position: relative !important;
                overflow: hidden !important;
                padding: 50px 0 !important;
            }
        }

        .gallery-hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.9), rgba(139, 92, 246, 0.8));
            z-index: 1;
            border-radius: 50px;
        }

        .gallery-hero-section .container {
            position: relative;
            z-index: 2;
        }

        /* ===== SERVICES PAGE STYLES ===== */
        .services-hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            min-height: 55vh !important;
            margin: 120px 30px 30px 30px !important;
            border-radius: 50px !important;
            display: flex !important;
            align-items: center !important;
            position: relative !important;
            overflow: hidden !important;
            padding: 50px 0 !important;
        }

        /* Ensure desktop styling for services hero section */
        @media (min-width: 769px) {
            .services-hero-section {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
                min-height: 55vh !important;
                margin: 120px 30px 30px 30px !important;
                border-radius: 50px !important;
                display: flex !important;
                align-items: center !important;
                position: relative !important;
                overflow: hidden !important;
                padding: 50px 0 !important;
            }
        }

        .services-hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.9), rgba(139, 92, 246, 0.8));
            z-index: 1;
            border-radius: 50px;
        }

        .services-hero-section .container {
            position: relative;
            z-index: 2;
        }

        /* ===== DOWNLOAD PAGE STYLES ===== */
        .download-hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 55vh;
            margin: 120px 30px 30px 30px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding: 50px 0;
        }

        .download-hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.9), rgba(139, 92, 246, 0.8));
            z-index: 1;
            border-radius: 50px;
        }

        .download-hero-section .container {
            position: relative;
            z-index: 2;
        }

        /* ===== REVIEWS PAGE STYLES ===== */
        .reviews-hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 55vh;
            margin: 120px 30px 30px 30px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding: 50px 0;
        }

        .reviews-hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.9), rgba(139, 92, 246, 0.8));
            z-index: 1;
            border-radius: 50px;
        }

        .reviews-hero-section .container {
            position: relative;
            z-index: 2;
        }

        .search-form {
            margin-top: 2rem;
        }

        .search-container {
            max-width: 500px;
            margin: 0 auto;
            position: relative;
            display: flex;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 50px;
            padding: 8px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .search-input {
            flex: 1;
            border: none;
            outline: none;
            background: transparent;
            padding: 15px 20px;
            font-size: 1rem;
            color: #374151;
        }

        .search-input::placeholder {
            color: #9CA3AF;
        }

        .search-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 50px;
            padding: 15px 25px;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .news-placeholder {
            height: 200px;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 2.5rem;
            position: relative;
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 3rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .search-container {
                max-width: 100%;
                margin: 0 20px;
            }

            .search-input {
                font-size: 0.9rem;
                padding: 12px 15px;
            }

            .search-btn {
                padding: 12px 20px;
                font-size: 0.9rem;
            }
        }

        /* ===== GALLERY PAGE STYLES ===== */
        .gallery-hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 55vh;
            margin: 120px 30px 30px 30px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding: 50px 0;
        }

        .gallery-hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.9), rgba(139, 92, 246, 0.8));
            z-index: 1;
            border-radius: 50px;
        }

        .gallery-hero-section .container {
            position: relative;
            z-index: 2;
        }

        .gallery-modal .modal-content {
            background: rgba(26, 32, 44, 0.95);
            backdrop-filter: blur(20px);
            border: none;
            border-radius: 15px;
        }

        .gallery-modal .modal-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
        }

        .gallery-modal .modal-title {
            color: white;
            font-weight: 600;
        }

        .gallery-modal .modal-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
        }

        .modal-gallery-img {
            width: 100%;
            height: auto;
            max-height: 70vh;
            object-fit: contain;
            border-radius: 0;
        }

        .gallery-description {
            color: rgba(255, 255, 255, 0.9);
            margin: 0;
            font-size: 1rem;
            line-height: 1.6;
        }

        .btn-close-white {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .gallery-modal .modal-dialog {
                margin: 10px;
            }

            .modal-gallery-img {
                max-height: 60vh;
            }
        }

        /* ===== SERVICES PAGE STYLES ===== */
        .services-hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 55vh;
            margin: 120px 30px 30px 30px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding: 50px 0;
        }

        .services-hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.9), rgba(139, 92, 246, 0.8));
            z-index: 1;
            border-radius: 50px;
        }

        .services-hero-section .container {
            position: relative;
            z-index: 2;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .services-hero-section {
                min-height: 70vh;
                text-align: center;
            }
        }

        /* ===== DOWNLOAD PAGE STYLES ===== */
        .download-hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 55vh;
            margin: 120px 30px 30px 30px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding: 50px 0;
        }

        .download-hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.9), rgba(139, 92, 246, 0.8));
            z-index: 1;
            border-radius: 50px;
        }

        .download-hero-section .container {
            position: relative;
            z-index: 2;
        }

        .download-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .download-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .download-card-header {
            position: relative;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .file-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            color: white;
            font-size: 2rem;
            transition: all 0.3s ease;
        }

        .download-card:hover .file-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .download-badge {
            position: absolute;
            top: 15px;
            right: 15px;
        }

        .download-card-body {
            padding: 25px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .download-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 15px;
            line-height: 1.4;
        }

        .download-description {
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 20px;
            flex: 1;
        }

        .file-meta {
            background: #f8fafc;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .meta-item:last-child {
            margin-bottom: 0;
        }

        .meta-item i {
            color: #9ca3af;
            width: 16px;
        }

        .download-action {
            margin-top: auto;
        }

        .download-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            text-decoration: none;
            padding: 15px 25px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }

        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }

        /* File type specific colors */
        .download-card[data-type="pdf"] .file-icon {
            background: linear-gradient(135deg, #dc2626, #ef4444);
        }

        .download-card[data-type="doc"] .file-icon,
        .download-card[data-type="docx"] .file-icon {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
        }

        .download-card[data-type="xls"] .file-icon,
        .download-card[data-type="xlsx"] .file-icon {
            background: linear-gradient(135deg, #059669, #10b981);
        }

        .download-card[data-type="ppt"] .file-icon,
        .download-card[data-type="pptx"] .file-icon {
            background: linear-gradient(135deg, #d97706, #f59e0b);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .download-card-header {
                padding: 25px 15px;
            }

            .file-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .download-card-body {
                padding: 20px;
            }

            .download-title {
                font-size: 1.1rem;
            }
        }

        /* ===== REVIEWS PAGE STYLES ===== */
        .reviews-hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 55vh;
            margin: 120px 30px 30px 30px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding: 50px 0;
        }

        .reviews-hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.9), rgba(139, 92, 246, 0.8));
            z-index: 1;
            border-radius: 50px;
        }

        .reviews-hero-section .container {
            position: relative;
            z-index: 2;
        }

        .hero-actions {
            margin-top: 2rem;
        }

        .hero-btn {
            display: inline-flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            padding: 15px 30px;
            font-weight: 600;
            text-decoration: none;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .hero-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
            color: white;
        }

        /* Review Cards */
        .review-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .review-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .reviewer-info {
            display: flex;
            align-items: center;
        }

        .reviewer-avatar {
            width: 60px;
            height: 60px;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(102, 126, 234, 0.2);
        }

        .avatar-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .reviewer-details {
            flex: 1;
        }

        .reviewer-name {
            margin: 0 0 5px 0;
            font-size: 1.1rem;
            font-weight: 700;
            color: #1f2937;
        }

        .reviewer-role {
            margin: 0 0 8px 0;
            color: #6b7280;
            font-size: 0.9rem;
        }

        .review-rating {
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .star-filled {
            color: #fbbf24;
            font-size: 0.9rem;
        }

        .star-empty {
            color: #d1d5db;
            font-size: 0.9rem;
        }

        .rating-text {
            margin-left: 8px;
            color: #6b7280;
            font-size: 0.85rem;
        }

        .review-date {
            color: #9ca3af;
            font-size: 0.8rem;
            white-space: nowrap;
        }

        .review-content {
            position: relative;
            background: #f8fafc;
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
        }

        .quote-icon {
            position: absolute;
            color: rgba(102, 126, 234, 0.3);
            font-size: 1.5rem;
        }

        .quote-icon:first-child {
            top: 10px;
            left: 15px;
        }

        .quote-right {
            bottom: 10px;
            right: 15px;
        }

        .review-text {
            margin: 0;
            color: #374151;
            line-height: 1.6;
            font-style: italic;
            padding: 10px 20px;
        }

        /* Alert Styles */
        .alert-success-modern,
        .alert-danger-modern {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: none;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .alert-success-modern {
            border-left: 4px solid #10b981;
        }

        .alert-danger-modern {
            border-left: 4px solid #ef4444;
        }

        .alert-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.2rem;
        }

        .alert-success-modern .alert-icon {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .alert-danger-modern .alert-icon {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .alert-content {
            flex: 1;
            color: #374151;
        }

        .btn-close-modern {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: #9ca3af;
            cursor: pointer;
            padding: 5px;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .btn-close-modern:hover {
            background: rgba(156, 163, 175, 0.1);
            color: #6b7280;
        }

        /* CTA Buttons */
        .cta-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }

        .cta-btn {
            display: inline-flex;
            align-items: center;
            padding: 15px 25px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .cta-btn.primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: 2px solid transparent;
        }

        .cta-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }

        .cta-btn.secondary {
            background: rgba(255, 255, 255, 0.9);
            color: #667eea;
            border: 2px solid rgba(102, 126, 234, 0.3);
        }

        .cta-btn.secondary:hover {
            background: rgba(102, 126, 234, 0.1);
            border-color: #667eea;
            transform: translateY(-2px);
            color: #667eea;
            text-decoration: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .reviews-hero-section {
                min-height: 70vh;
                text-align: center;
            }

            .review-card {
                padding: 20px;
            }

            .reviewer-avatar {
                width: 50px;
                height: 50px;
                margin-right: 12px;
            }

            .avatar-placeholder {
                font-size: 1.2rem;
            }

            .review-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .review-date {
                align-self: flex-end;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .cta-btn {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .review-content {
                padding: 15px;
            }

            .review-text {
                padding: 5px 15px;
                font-size: 0.9rem;
            }

            .quote-icon {
                font-size: 1.2rem;
            }
        }

        /* ===== ORGANIZATION STRUCTURE STYLES ===== */
        .structure-hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            min-height: 55vh !important;
            margin: 120px 30px 30px 30px !important;
            border-radius: 50px !important;
            display: flex !important;
            align-items: center !important;
            position: relative !important;
            overflow: hidden !important;
            padding: 50px 0 !important;
        }

        /* Ensure desktop styling for structure hero section */
        @media (min-width: 769px) {
            .structure-hero-section {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
                min-height: 55vh !important;
                margin: 120px 30px 30px 30px !important;
                border-radius: 50px !important;
                display: flex !important;
                align-items: center !important;
                position: relative !important;
                overflow: hidden !important;
                padding: 50px 0 !important;
            }
        }

        .structure-hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.9), rgba(139, 92, 246, 0.8));
            z-index: 1;
            border-radius: 50px;
        }

        .structure-hero-section .container {
            position: relative;
            z-index: 2;
        }

        /* Organization Chart Layout */
        .org-chart {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            position: relative;
        }

        .org-level {
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
            position: relative;
        }

        /* Organization Cards - Modern Design with Full Cover Photo */
        .org-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            text-align: center;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            border: 2px solid #f3f4f6;
            transition: all 0.3s ease;
            position: relative;
            width: 100%;
            max-width: 340px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
        }

        /* Remove any edit icons and other unwanted elements */
        .org-card::after,
        .org-card::before,
        .org-card .edit-icon,
        .org-card .fa-edit,
        .org-card .fa-pencil-alt,
        .org-card .fas.fa-edit,
        .org-card .fas.fa-pencil-alt,
        .org-card [class*="edit"],
        .org-card [class*="pencil"] {
            display: none !important;
        }

        .org-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        /* Card Types - Consistent Sizing - All cards same width */
        .org-card.kepala,
        .org-card.sekretaris,
        .org-card.kabid,
        .org-card.staff,
        .org-card.level2,
        .org-card.level3 {
            max-width: 340px;
            width: 340px;
        }

        /* Card Photo - Full Cover Design */
        .card-photo {
            width: 100%;
            height: 280px;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
            border-bottom: 3px solid #f3f4f6;
        }

        .card-photo::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 80px;
            background: linear-gradient(to top, rgba(0,0,0,0.5), transparent);
            pointer-events: none;
        }

        .card-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .photo-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 5rem;
            opacity: 0.5;
        }

        /* Card Info */
        .card-info {
            padding: 24px 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .card-info h4 {
            color: #dc2626;
            font-size: 0.7rem;
            font-weight: 700;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.5;
        }

        .card-info h5 {
            color: #1f2937;
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 6px;
            line-height: 1.3;
        }

        .card-info h6 {
            color: #374151;
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 6px;
            line-height: 1.3;
        }

        .card-info .nip {
            color: #6b7280;
            font-size: 0.75rem;
            margin: 0 0 14px 0;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        /* PLT Badge Styling */
        .plt-badge {
            display: inline-block;
            background: linear-gradient(135deg, #ff9800, #ff5722);
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 700;
            margin: 0 0 10px 0;
            box-shadow: 0 2px 8px rgba(255, 152, 0, 0.25);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .plt-badge i {
            margin-right: 4px;
            font-size: 0.65rem;
        }

        .plt-info {
            background: #fef3c7;
            border-left: 3px solid #f59e0b;
            border-radius: 8px;
            padding: 12px;
            margin: 8px 0 0 0;
            text-align: left;
        }

        .plt-info small {
            color: #92400e;
            font-size: 0.75rem;
            line-height: 1.6;
            display: block;
        }

        .plt-info strong {
            color: #78350f;
            font-weight: 700;
        }



        /* Containers for Multiple Items */

        /* Sekretaris container - centered below Kepala Dinas */
        .sekretaris-container {
            display: flex;
            justify-content: center;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .sekretaris-container .org-card {
            max-width: 340px;
            width: 340px;
        }

        /* Level 2b container - horizontal grid for Kepala Bidang */
        .level2-container,
        .kabid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, 340px);
            gap: 24px;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            justify-content: center;
        }

        /* Special handling for JPT Fungsional (5th item in level 2) to always be centered alone */
        .level2-container .org-card.level2:nth-child(5) {
            grid-column: 1 / -1;
            max-width: 340px;
            margin: 0 auto;
        }

        .level3-container,
        .staff-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, 340px);
            gap: 24px;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            justify-content: center;
        }

        /* Organization Connectors */
        .org-connector {
            background: linear-gradient(90deg, #667eea, #764ba2);
            position: relative;
        }

        .org-connector.vertical {
            width: 4px;
            height: 40px;
            margin: 0 auto;
            border-radius: 2px;
        }

        .org-connector.horizontal {
            height: 4px;
            border-radius: 2px;
            margin: 20px auto;
        }

        .org-connector.kabid-connector {
            width: 60%;
            max-width: 600px;
        }

        .org-connector.staff-connector {
            width: 80%;
            max-width: 800px;
        }

        /* Color Coding */
        .org-card.kepala .card-photo {
            border-color: rgba(239, 68, 68, 0.4);
        }

        .org-card.kepala .card-info h4 {
            color: #dc2626;
        }

        .org-card.level2 .card-photo,
        .org-card.sekretaris .card-photo {
            border-color: rgba(34, 197, 94, 0.4);
        }

        .org-card.level2 .card-info h4,
        .org-card.sekretaris .card-info h4 {
            color: #16a34a;
        }

        .org-card.kabid .card-photo {
            border-color: rgba(251, 191, 36, 0.4);
        }

        .org-card.kabid .card-info h4 {
            color: #d97706;
        }

        .org-card.level3 .card-photo,
        .org-card.staff .card-photo {
            border-color: rgba(139, 192, 216, 0.4);
        }

        .org-card.level3 .card-info h4,
        .org-card.staff .card-info h4 {
            color: #0369a1;
        }

        /* Structure Table Section */
        .structure-table-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .structure-table-section .section-header {
            margin-bottom: 30px;
        }

        .structure-table-section .section-title {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }

        .structure-table-section .section-subtitle {
            color: #6b7280;
            font-size: 1rem;
        }

        .table-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .table-gradient th {
            font-weight: 600;
            padding: 15px 12px;
            border: none;
            vertical-align: middle;
        }

        .table-hover tbody tr {
            transition: all 0.3s ease;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.1);
            transform: scale(1.01);
        }

        .table tbody td {
            padding: 12px;
            vertical-align: middle;
        }

        /* Stat Cards */
        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            flex-shrink: 0;
        }

        .stat-icon.bg-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .stat-icon.bg-success {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        }

        .stat-icon.bg-info {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        .stat-icon.bg-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stat-content h4 {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
            color: #1f2937;
        }

        .stat-content p {
            font-size: 0.9rem;
            color: #6b7280;
            margin: 0;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .org-chart {
                padding: 30px 15px;
            }

            .level2-container,
            .kabid-container {
                gap: 20px;
            }

            /* Maintain centered JPT card on tablets */
            .level2-container .org-card.level2:nth-child(5) {
                flex-basis: 100%;
                max-width: 400px;
            }

            .level3-container,
            .staff-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
                padding: 0 20px;
            }

            .org-card {
                min-width: unset;
                margin: 0;
            }

            .org-card.level3,
            .org-card.staff {
                min-width: unset;
            }

            .structure-table-section {
                padding: 20px;
            }

            .stat-card {
                padding: 15px;
            }

            .stat-icon {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }

            .stat-content h4 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .structure-hero-section {
                min-height: 70vh;
                text-align: center;
            }

            .org-chart {
                padding: 20px 10px;
            }

            /* Force single column layout on mobile */
            .sekretaris-container {
                flex-direction: column;
                align-items: center;
                padding: 0 15px;
            }

            .sekretaris-container .org-card {
                width: 100%;
                max-width: 300px;
            }

            .level2-container,
            .kabid-container {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 20px;
                padding: 0 15px;
            }

            /* All level 2 cards single column on mobile */
            .level2-container .org-card,
            .kabid-container .org-card {
                width: 100%;
                max-width: 300px;
                flex-basis: auto;
            }

            .level2-container .org-card.level2:nth-child(5) {
                flex-basis: auto;
                max-width: 300px;
            }

            .level3-container,
            .staff-container {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 20px;
                padding: 0 15px;
            }

            .level3-container .org-card,
            .staff-container .org-card {
                width: 100%;
                max-width: 300px;
            }

            .org-card {
                min-width: unset;
                width: 100%;
                max-width: 300px;
                margin: 0 auto;
            }

            /* Card Photo - Keep full cover design on mobile */
            .card-photo {
                width: 100%;
                height: 220px;
            }

            .photo-placeholder {
                font-size: 3.5rem;
            }

            /* Card Info - Slightly smaller padding on mobile */
            .card-info {
                padding: 18px 16px;
            }

            .card-info h4 {
                font-size: 0.65rem;
                margin-bottom: 8px;
            }

            .card-info h5 {
                font-size: 1rem;
            }

            .structure-table-section {
                padding: 15px;
            }

            .structure-table-section .section-title {
                font-size: 1.4rem;
            }

            .structure-table-section .section-subtitle {
                font-size: 0.9rem;
            }

            .table {
                font-size: 0.85rem;
            }

            .table thead th {
                padding: 10px 8px;
                font-size: 0.8rem;
            }

            .table tbody td {
                padding: 8px;
            }

            .stat-card {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }

            .stat-icon {
                width: 60px;
                height: 60px;
            }

            .org-card.staff {
                min-width: 240px;
            }

            .org-connector.horizontal {
                display: none;
            }

            .org-level {
                margin-bottom: 20px;
            }
        }

        @media (max-width: 576px) {
            /* Force single column on small mobile */
            .sekretaris-container,
            .level2-container,
            .kabid-container,
            .level3-container,
            .staff-container {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 16px;
                padding: 0 10px;
            }

            .org-card {
                min-width: 100%;
                max-width: 280px;
            }

            .sekretaris-container .org-card,
            .level2-container .org-card,
            .kabid-container .org-card,
            .level3-container .org-card,
            .staff-container .org-card {
                width: 100%;
                max-width: 280px;
            }

            .org-card.staff {
                max-width: 280px;
            }

            /* Card Photo - Maintain full cover on small mobile */
            .card-photo {
                width: 100%;
                height: 200px;
            }

            .photo-placeholder {
                font-size: 3rem;
            }

            /* Card Info - Compact on small mobile */
            .card-info {
                padding: 16px 14px;
            }

            .card-info h4 {
                font-size: 0.6rem;
                margin-bottom: 6px;
            }

            .card-info h5 {
                font-size: 0.95rem;
            }

            .card-info h6 {
                font-size: 0.85rem;
            }
        }

        /* Animation Effects */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .org-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .org-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .org-card:nth-child(3) {
            animation-delay: 0.2s;
        }

        .org-card:nth-child(4) {
            animation-delay: 0.3s;
        }

        .org-card:nth-child(5) {
            animation-delay: 0.4s;
        }

        .org-card:nth-child(6) {
            animation-delay: 0.5s;
        }

        /* ===== SCROLL REVEAL ANIMATIONS ===== */
        .scroll-reveal {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.6s ease-out;
        }

        .scroll-reveal.revealed {
            opacity: 1;
            transform: translateY(0);
        }

        /* Animation variants */
        .scroll-reveal-left {
            opacity: 0;
            transform: translateX(-50px);
            transition: all 0.6s ease-out;
        }

        .scroll-reveal-left.revealed {
            opacity: 1;
            transform: translateX(0);
        }

        .scroll-reveal-right {
            opacity: 0;
            transform: translateX(50px);
            transition: all 0.6s ease-out;
        }

        .scroll-reveal-right.revealed {
            opacity: 1;
            transform: translateX(0);
        }

        .scroll-reveal-fade {
            opacity: 0;
            transition: all 0.6s ease-out;
        }

        .scroll-reveal-fade.revealed {
            opacity: 1;
        }

        .scroll-reveal-scale {
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.6s ease-out;
        }

        .scroll-reveal-scale.revealed {
            opacity: 1;
            transform: scale(1);
        }

        /* Staggered animation delays */
        .scroll-reveal:nth-child(1) {
            transition-delay: 0.1s;
        }

        .scroll-reveal:nth-child(2) {
            transition-delay: 0.2s;
        }

        .scroll-reveal:nth-child(3) {
            transition-delay: 0.3s;
        }

        .scroll-reveal:nth-child(4) {
            transition-delay: 0.4s;
        }

        .scroll-reveal:nth-child(5) {
            transition-delay: 0.5s;
        }

        .scroll-reveal:nth-child(6) {
            transition-delay: 0.6s;
        }

        .scroll-stagger>* {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.5s ease-out;
        }

        .scroll-stagger.revealed>*:nth-child(1) {
            animation: slideUp 0.6s ease-out 0.1s forwards;
        }

        .scroll-stagger.revealed>*:nth-child(2) {
            animation: slideUp 0.6s ease-out 0.2s forwards;
        }

        .scroll-stagger.revealed>*:nth-child(3) {
            animation: slideUp 0.6s ease-out 0.3s forwards;
        }

        .scroll-stagger.revealed>*:nth-child(4) {
            animation: slideUp 0.6s ease-out 0.4s forwards;
        }

        .scroll-stagger.revealed>*:nth-child(5) {
            animation: slideUp 0.6s ease-out 0.5s forwards;
        }

        .scroll-stagger.revealed>*:nth-child(6) {
            animation: slideUp 0.6s ease-out 0.6s forwards;
        }

        @keyframes slideUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Reduce motion for accessibility */
        @media (prefers-reduced-motion: reduce) {

            .scroll-reveal,
            .scroll-reveal-left,
            .scroll-reveal-right,
            .scroll-reveal-fade,
            .scroll-reveal-scale,
            .scroll-stagger>* {
                transition: none;
                animation: none;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Floating Navbar -->
    <nav class="floating-navbar" id="navbar">
        <div class="navbar-content">
            <a class="navbar-brand" href="{{ route('public.home') }}">
                @if (isset($profile) && $profile->logo && Storage::disk('public')->exists($profile->logo))
                    <img src="{{ Storage::url($profile->logo) }}" alt="Logo {{ $profile->name ?? 'Dinas Koperasi' }}">
                @else
                    <i class="fas fa-building" style="font-size: 1.5rem; color: var(--primary);"></i>
                @endif
                {{-- <span>{{ $profile->name ?? 'Dinas Koperasi' }}</span> --}}
            </a>

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.home') ? 'active' : '' }}"
                        href="{{ route('public.home') }}">
                        <i class="fas fa-home"></i>Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.profile') ? 'active' : '' }}"
                        href="{{ route('public.profile') }}">
                        <i class="fas fa-info-circle"></i>Profil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.struktur') ? 'active' : '' }}"
                        href="{{ route('public.struktur') }}">
                        <i class="fas fa-sitemap"></i>Struktur
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.layanan') ? 'active' : '' }}"
                        href="{{ route('public.layanan') }}">
                        <i class="fas fa-concierge-bell"></i>Layanan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.berita*') ? 'active' : '' }}"
                        href="{{ route('public.berita') }}">
                        <i class="fas fa-newspaper"></i>Berita
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.galeri') ? 'active' : '' }}"
                        href="{{ route('public.galeri') }}">
                        <i class="fas fa-images"></i>Galeri
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.downloads*') ? 'active' : '' }}"
                        href="{{ route('public.downloads') }}">
                        <i class="fas fa-download"></i>Download
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.ulasan') ? 'active' : '' }}"
                        href="{{ route('public.ulasan') }}">
                        <i class="fas fa-star"></i>Ulasan
                    </a>
                </li>
            </ul>

            <button class="navbar-toggle" id="navToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div class="mobile-menu" id="mobileMenu">
            <a class="nav-link {{ request()->routeIs('public.home') ? 'active' : '' }}"
                href="{{ route('public.home') }}">
                <i class="fas fa-home"></i>Beranda
            </a>
            <a class="nav-link {{ request()->routeIs('public.profile') ? 'active' : '' }}"
                href="{{ route('public.profile') }}">
                <i class="fas fa-info-circle"></i>Profil
            </a>
            <a class="nav-link {{ request()->routeIs('public.struktur') ? 'active' : '' }}"
                href="{{ route('public.struktur') }}">
                <i class="fas fa-sitemap"></i>Struktur
            </a>
            <a class="nav-link {{ request()->routeIs('public.layanan') ? 'active' : '' }}"
                href="{{ route('public.layanan') }}">
                <i class="fas fa-concierge-bell"></i>Layanan
            </a>
            <a class="nav-link {{ request()->routeIs('public.berita*') ? 'active' : '' }}"
                href="{{ route('public.berita') }}">
                <i class="fas fa-newspaper"></i>Berita
            </a>
            <a class="nav-link {{ request()->routeIs('public.galeri') ? 'active' : '' }}"
                href="{{ route('public.galeri') }}">
                <i class="fas fa-images"></i>Galeri
            </a>
            <a class="nav-link {{ request()->routeIs('public.downloads*') ? 'active' : '' }}"
                href="{{ route('public.downloads') }}">
                <i class="fas fa-download"></i>Download
            </a>
            <a class="nav-link {{ request()->routeIs('public.ulasan') ? 'active' : '' }}"
                href="{{ route('public.ulasan') }}">
                <i class="fas fa-star"></i>Ulasan
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Bottom Navigation (Mobile) -->
    <nav class="bottom-nav">
        <div class="bottom-nav-container">
            <!-- Menu Utama 1: Beranda -->
            <a href="{{ route('public.home') }}"
                class="bottom-nav-item {{ request()->routeIs('public.home') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Beranda</span>
            </a>

            <!-- Menu Utama 2: Profil -->
            <a href="{{ route('public.profile') }}"
                class="bottom-nav-item {{ request()->routeIs('public.profile') ? 'active' : '' }}">
                <i class="fas fa-info-circle"></i>
                <span>Profil</span>
            </a>

            <!-- Menu Utama 3: Struktur -->
            <a href="{{ route('public.struktur') }}"
                class="bottom-nav-item {{ request()->routeIs('public.struktur') ? 'active' : '' }}">
                <i class="fas fa-sitemap"></i>
                <span>Struktur</span>
            </a>

            <!-- Menu Utama 4: Layanan -->
            <a href="{{ route('public.layanan') }}"
                class="bottom-nav-item {{ request()->routeIs('public.layanan') ? 'active' : '' }}">
                <i class="fas fa-concierge-bell"></i>
                <span>Layanan</span>
            </a>

            <!-- Menu Dropdown: Lainnya -->
            <div class="bottom-nav-dropdown">
                <a href="#"
                    class="bottom-nav-item {{ request()->routeIs('public.galeri') || request()->routeIs('public.berita*') || request()->routeIs('public.ulasan') || request()->routeIs('public.downloads*') ? 'has-active' : '' }}"
                    id="moreMenuTrigger">
                    <i class="fas fa-ellipsis-h"></i>
                    <span>Lainnya</span>
                </a>

                <div class="dropdown-menu-custom" id="moreMenuDropdown">
                    <a href="{{ route('public.galeri') }}"
                        class="dropdown-item-custom {{ request()->routeIs('public.galeri') ? 'active' : '' }}">
                        <i class="fas fa-images"></i>
                        <span>Galeri</span>
                    </a>
                    <a href="{{ route('public.berita') }}"
                        class="dropdown-item-custom {{ request()->routeIs('public.berita*') ? 'active' : '' }}">
                        <i class="fas fa-newspaper"></i>
                        <span>Berita</span>
                    </a>
                    <a href="{{ route('public.ulasan') }}"
                        class="dropdown-item-custom {{ request()->routeIs('public.ulasan') ? 'active' : '' }}">
                        <i class="fas fa-star"></i>
                        <span>Ulasan</span>
                    </a>
                    <a href="{{ route('public.downloads') }}"
                        class="dropdown-item-custom {{ request()->routeIs('public.downloads*') ? 'active' : '' }}">
                        <i class="fas fa-download"></i>
                        <span>Download</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            @if (isset($profile) && $profile->logo && Storage::disk('public')->exists($profile->logo))
                                <img src="{{ Storage::url($profile->logo) }}"
                                    alt="Logo {{ $profile->name ?? 'Dinas Koperasi' }}"
                                    style="width: 48px; height: 48px; object-fit: cover; border-radius: 12px; border: 2px solid rgba(255, 255, 255, 0.2);">
                            @else
                                <div
                                    style="width: 48px; height: 48px; background: var(--gradient-primary); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-building text-white"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h5 class="mb-0 text-white">{{ $profile->name ?? 'Dinas Koperasi' }}</h5>
                            <small class="text-gray-400">Melayani dengan Integritas</small>
                        </div>
                    </div>
                    <p class="mb-0">&copy; {{ date('Y') }} {{ $profile->name ?? 'Dinas Koperasi' }}. All rights
                        reserved.</p>
                </div>
                <div class="col-md-6">
                    @if (isset($profile) && $profile->address)
                        <div class="d-flex align-items-start mb-2">
                            <i class="fas fa-map-marker-alt me-2 mt-1" style="color: var(--primary);"></i>
                            <span>{{ $profile->address }}</span>
                        </div>
                    @endif
                    @if (isset($profile) && $profile->phone)
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-phone me-2" style="color: var(--primary);"></i>
                            <span>{{ $profile->phone }}</span>
                        </div>
                    @endif
                    @if (isset($profile) && $profile->email)
                        <div class="d-flex align-items-center">
                            <i class="fas fa-envelope me-2" style="color: var(--primary);"></i>
                            <span>{{ $profile->email }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Navbar Scroll Effect & Responsive Navigation
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.querySelector('.floating-navbar');
            const bottomNav = document.querySelector('.bottom-nav');

            // Function to handle responsive navigation visibility
            function handleNavVisibility() {
                const screenWidth = window.innerWidth;

                if (screenWidth <= 768) {
                    // Smartphone: Hide floating nav, show bottom nav
                    if (navbar) navbar.style.display = 'none';
                    if (bottomNav) {
                        // Set positioning immediately
                        bottomNav.style.position = 'fixed';
                        bottomNav.style.left = '50%';
                        bottomNav.style.right = 'auto';
                        bottomNav.style.transform = 'translateX(-50%)';
                        bottomNav.style.bottom = '15px';
                        bottomNav.style.opacity = '1';
                        bottomNav.style.visibility = 'visible';
                        bottomNav.style.display = 'block';
                        // Add loaded class immediately
                        bottomNav.classList.add('loaded');
                    }
                } else {
                    // Desktop/Tablet: Show floating nav, hide bottom nav
                    if (navbar) navbar.style.display = 'flex';
                    if (bottomNav) {
                        bottomNav.style.display = 'none';
                        bottomNav.classList.remove('loaded');
                    }
                }
            }

            // Initial setup
            handleNavVisibility();

            // Handle window resize
            window.addEventListener('resize', handleNavVisibility);

            // Scroll effect for floating navbar
            window.addEventListener('scroll', function() {
                if (navbar && window.innerWidth > 768) {
                    if (window.scrollY > 50) {
                        navbar.classList.add('scrolled');
                    } else {
                        navbar.classList.remove('scrolled');
                    }
                }
            });

            // Mobile navigation toggle (for future use if needed)
            const navToggle = document.querySelector('#navToggle');
            const mobileMenu = document.querySelector('#mobileMenu');

            if (navToggle && mobileMenu) {
                navToggle.addEventListener('click', function() {
                    mobileMenu.classList.toggle('show');

                    // Change icon
                    const icon = this.querySelector('i');
                    if (mobileMenu.classList.contains('show')) {
                        icon.className = 'fas fa-times';
                    } else {
                        icon.className = 'fas fa-bars';
                    }
                });

                // Close menu when clicking on links
                const mobileLinks = mobileMenu.querySelectorAll('.nav-link');
                mobileLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        mobileMenu.classList.remove('show');
                        navToggle.querySelector('i').className = 'fas fa-bars';
                    });
                });

                // Close menu when clicking outside
                document.addEventListener('click', function(e) {
                    if (!navToggle.contains(e.target) && !mobileMenu.contains(e.target)) {
                        mobileMenu.classList.remove('show');
                        navToggle.querySelector('i').className = 'fas fa-bars';
                    }
                });
            }

            // Active link handling for both navigations
            const allNavLinks = document.querySelectorAll('.nav-link, .bottom-nav-item, .dropdown-item-custom');
            allNavLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');

                    // If it's an anchor link, handle smooth scrolling
                    if (href && href.startsWith('#')) {
                        e.preventDefault();
                        const target = document.querySelector(href);

                        if (target) {
                            // Remove active class from all links
                            allNavLinks.forEach(navLink => {
                                navLink.classList.remove('active');
                            });

                            // Add active class to clicked link
                            this.classList.add('active');

                            // Smooth scroll to target
                            target.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    }
                });
            });

            // Bottom navigation dropdown functionality
            const moreMenuTrigger = document.getElementById('moreMenuTrigger');
            const moreMenuDropdown = document.getElementById('moreMenuDropdown');

            if (moreMenuTrigger && moreMenuDropdown) {
                let isDropdownOpen = false;

                // Toggle dropdown on click
                moreMenuTrigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    isDropdownOpen = !isDropdownOpen;

                    if (isDropdownOpen) {
                        moreMenuDropdown.classList.add('show');
                    } else {
                        moreMenuDropdown.classList.remove('show');
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!moreMenuTrigger.contains(e.target) && !moreMenuDropdown.contains(e.target)) {
                        isDropdownOpen = false;
                        moreMenuDropdown.classList.remove('show');
                    }
                });

                // Close dropdown when clicking on dropdown items
                const dropdownItems = moreMenuDropdown.querySelectorAll('.dropdown-item-custom');
                dropdownItems.forEach(item => {
                    item.addEventListener('click', function() {
                        isDropdownOpen = false;
                        moreMenuDropdown.classList.remove('show');
                    });
                });

                // Close dropdown on window resize
                window.addEventListener('resize', function() {
                    isDropdownOpen = false;
                    moreMenuDropdown.classList.remove('show');
                });

                // Close dropdown on scroll
                window.addEventListener('scroll', function() {
                    if (isDropdownOpen) {
                        isDropdownOpen = false;
                        moreMenuDropdown.classList.remove('show');
                    }
                });
            }

            // Animate on scroll
            const animateElements = document.querySelectorAll('.animate-on-scroll');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-on-scroll');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1
            });

            animateElements.forEach(el => {
                observer.observe(el);
            });

            // Smooth scrolling for anchor links (general)
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Hero Slideshow functionality
            const slides = document.querySelectorAll('.slide');
            if (slides.length > 1) {
                let currentSlide = 0;

                function showSlide(index) {
                    slides.forEach((slide, i) => {
                        slide.classList.toggle('active', i === index);
                    });
                }

                function nextSlide() {
                    currentSlide = (currentSlide + 1) % slides.length;
                    showSlide(currentSlide);
                }

                // Auto-advance slides every 5 seconds
                setInterval(nextSlide, 5000);

                // Initialize first slide
                showSlide(0);
            }

            // Scroll Reveal Animation
            function initScrollReveal() {
                const revealElements = document.querySelectorAll(
                    '.scroll-reveal, .scroll-reveal-left, .scroll-reveal-right, .scroll-reveal-fade, .scroll-reveal-scale, .scroll-stagger'
                );

                const revealObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('revealed');
                            revealObserver.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                });

                revealElements.forEach(element => {
                    revealObserver.observe(element);
                });
            }

            // Initialize scroll reveal
            initScrollReveal();
        });
    </script>

    @stack('scripts')
</body>

</html>
