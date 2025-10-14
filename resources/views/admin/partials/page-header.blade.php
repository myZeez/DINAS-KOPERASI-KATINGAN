<!-- Modern Page Header Component -->
@unless (View::hasSection('hide_chrome'))
    <div class="page-header-container mb-4">
        <div class="page-header-background">
            <div class="header-gradient"></div>
            <div class="header-pattern"></div>
            <div class="floating-particles">
                <div class="particle particle-1"></div>
                <div class="particle particle-2"></div>
                <div class="particle particle-3"></div>
                <div class="particle particle-4"></div>
                <div class="particle particle-5"></div>
            </div>
        </div>

        <div class="page-header-content">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-info">
                        <div class="page-breadcrumb">
                            <i class="fas fa-home"></i>
                            <span>Admin</span>
                            <i class="fas fa-chevron-right"></i>
                            @if (isset($breadcrumb) && is_array($breadcrumb))
                                @foreach ($breadcrumb as $index => $crumb)
                                    @if (isset($crumb['route']))
                                        <a href="{{ route($crumb['route']) }}"
                                            class="breadcrumb-link">{{ $crumb['label'] }}</a>
                                    @else
                                        <span
                                            class="{{ isset($crumb['active']) && $crumb['active'] ? 'current-page' : '' }}">{{ $crumb['label'] }}</span>
                                    @endif
                                    @if (!$loop->last)
                                        <i class="fas fa-chevron-right"></i>
                                    @endif
                                @endforeach
                            @else
                                <span class="current-page">{{ $breadcrumb ?? 'Halaman' }}</span>
                            @endif
                        </div>

                        <h1 class="page-main-title">
                            <div class="title-icon-wrapper">
                                <i class="{{ $icon ?? 'fas fa-cog' }} title-icon"></i>
                            </div>
                            <span class="title-text">{{ $title ?? 'Judul Halaman' }}</span>
                            @if (isset($badge))
                                <span class="title-badge">{{ $badge }}</span>
                            @endif
                        </h1>

                        @if (isset($subtitle))
                            <p class="page-subtitle">{{ $subtitle }}</p>
                        @endif

                        <div class="page-meta">
                            <div class="meta-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span id="header-date"></span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-clock"></i>
                                <span id="header-time"></span>
                            </div>
                            @if (isset($stats))
                                <div class="meta-item">
                                    <i class="fas fa-chart-bar"></i>
                                    <span>{{ $stats }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="page-header-actions">
                        @if (isset($primaryAction))
                            <div class="action-group primary">
                                @if (isset($primaryAction['modal']))
                                    <button type="button" class="btn btn-primary-glow" data-bs-toggle="modal"
                                        data-bs-target="#{{ $primaryAction['modal'] }}">
                                        <div class="btn-ripple"></div>
                                        <i class="{{ $primaryAction['icon'] ?? 'fas fa-plus' }}"></i>
                                        <span>{{ $primaryAction['text'] ?? 'Aksi Utama' }}</span>
                                    </button>
                                @else
                                    <a href="{{ $primaryAction['url'] ?? '#' }}" class="btn btn-primary-glow">
                                        <div class="btn-ripple"></div>
                                        <i class="{{ $primaryAction['icon'] ?? 'fas fa-plus' }}"></i>
                                        <span>{{ $primaryAction['text'] ?? 'Aksi Utama' }}</span>
                                    </a>
                                @endif
                            </div>
                        @endif

                        @if (isset($secondaryActions))
                            <div class="action-group secondary">
                                @foreach ($secondaryActions as $action)
                                    <button class="btn btn-glass-action" onclick="{{ $action['onclick'] ?? '' }}"
                                        title="{{ $action['title'] ?? '' }}">
                                        <i class="{{ $action['icon'] ?? 'fas fa-cog' }}"></i>
                                        <span class="btn-text">{{ $action['text'] ?? '' }}</span>
                                    </button>
                                @endforeach
                            </div>
                        @endif

                        <!-- Quick Stats Mini Cards -->
                        @if (isset($quickStats))
                            <div class="quick-stats-mini d-flex flex-wrap justify-content-end gap-2">
                                @foreach ($quickStats as $stat)
                                    <div class="mini-stat-card d-flex align-items-center">
                                        <div class="mini-stat-icon me-2">
                                            <i class="{{ $stat['icon'] ?? 'fas fa-chart-line' }}"></i>
                                        </div>
                                        <div class="mini-stat-content">
                                            <div class="mini-stat-value">{{ $stat['value'] ?? '0' }}</div>
                                            <div class="mini-stat-label">{{ $stat['label'] ?? 'Data' }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Bar (optional) -->
        @if (isset($showProgress) && $showProgress)
            <div class="page-progress-bar">
                <div class="progress-fill" style="width: {{ $progressValue ?? '0' }}%"></div>
            </div>
        @endif
    </div>
@endunless

<style>
    /* ================================
PAGE HEADER STYLES
================================ */
    .page-header-container {
        position: relative;
        background: linear-gradient(135deg,
                rgba(255, 255, 255, 0.08) 0%,
                rgba(255, 255, 255, 0.04) 50%,
                rgba(255, 255, 255, 0.08) 100%);
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 25px;
        padding: 30px;
        margin-bottom: 30px;
        overflow: hidden;
        box-shadow:
            0 8px 32px rgba(0, 0, 0, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.2),
            inset 0 -1px 0 rgba(255, 255, 255, 0.1);
    }

    .page-header-background {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
        overflow: hidden;
    }

    .header-gradient {
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle at 30% 30%,
                rgba(0, 255, 136, 0.1) 0%,
                rgba(0, 123, 255, 0.05) 50%,
                transparent 70%);
        animation: headerGradientRotate 20s linear infinite;
    }

    .header-pattern {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image:
            radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.08) 1px, transparent 1px),
            radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.06) 1px, transparent 1px);
        background-size: 50px 50px, 30px 30px;
        animation: patternMove 30s linear infinite;
    }

    /* Floating Particles */
    .floating-particles {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
    }

    .particle {
        position: absolute;
        width: 6px;
        height: 6px;
        background: rgba(255, 255, 255, 0.4);
        border-radius: 50%;
        animation: particleFloat 8s ease-in-out infinite;
    }

    .particle-1 {
        top: 20%;
        left: 10%;
        animation-delay: 0s;
    }

    .particle-2 {
        top: 60%;
        left: 80%;
        animation-delay: 2s;
    }

    .particle-3 {
        top: 80%;
        left: 20%;
        animation-delay: 4s;
    }

    .particle-4 {
        top: 30%;
        left: 70%;
        animation-delay: 6s;
    }

    .particle-5 {
        top: 70%;
        left: 50%;
        animation-delay: 1s;
    }

    /* Header Content */
    .page-header-content {
        position: relative;
        z-index: 2;
    }

    .page-breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9em;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 15px;
    }

    .page-breadcrumb i.fa-chevron-right {
        font-size: 0.7em;
    }

    .page-breadcrumb .current-page {
        color: var(--accent-color);
        font-weight: 600;
    }

    .page-breadcrumb .breadcrumb-link {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .page-breadcrumb .breadcrumb-link:hover {
        color: var(--accent-color);
        text-decoration: none;
    }

    .page-main-title {
        display: flex;
        align-items: center;
        gap: 15px;
        font-size: 2.2em;
        font-weight: 700;
        margin: 0 0 10px 0;
        color: #ffffff;
    }

    .title-icon-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--accent-color) 0%, #00d4ff 100%);
        border-radius: 18px;
        box-shadow: 0 8px 20px rgba(0, 255, 136, 0.3);
        position: relative;
        overflow: hidden;
    }

    .title-icon-wrapper::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        animation: iconShine 3s ease-in-out infinite;
    }

    .title-icon {
        font-size: 1.5rem;
        color: #000;
        z-index: 2;
        position: relative;
    }

    .title-text {
        background: linear-gradient(135deg, #ffffff 0%, var(--accent-color) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .title-badge {
        padding: 6px 16px;
        background: rgba(255, 107, 107, 0.2);
        color: #ff6b6b;
        border: 1px solid rgba(255, 107, 107, 0.3);
        border-radius: 20px;
        font-size: 0.4em;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .page-subtitle {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1.1em;
        margin: 0 0 20px 0;
        line-height: 1.5;
    }

    .page-meta {
        display: flex;
        gap: 25px;
        align-items: center;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.95em;
        padding: 8px 16px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .meta-item i {
        color: var(--accent-color);
    }

    /* Header Actions */
    .page-header-actions {
        display: flex;
        flex-direction: column;
        gap: 20px;
        align-items: flex-end;
    }

    .action-group {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .btn-primary-glow {
        background: linear-gradient(135deg, var(--accent-color) 0%, #00d4ff 100%);
        border: none;
        border-radius: 16px;
        padding: 12px 24px;
        color: #000;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 10px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 255, 136, 0.4);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-primary-glow:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(0, 255, 136, 0.6);
        color: #000;
    }

    .btn-ripple {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.3s, height 0.3s;
    }

    .btn-primary-glow:active .btn-ripple {
        width: 200px;
        height: 200px;
    }

    .btn-glass-action {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        padding: 10px 16px;
        color: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-glass-action:hover {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.3);
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-text {
        font-size: 0.9em;
        font-weight: 500;
    }

    /* Quick Stats Mini */
    .quick-stats-mini {
        display: flex;
        gap: 15px;
    }

    .mini-stat-card {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 16px;
        padding: 15px;
        min-width: 80px;
        text-align: center;
        backdrop-filter: blur(10px);
    }

    .mini-stat-icon {
        color: var(--accent-color);
        font-size: 1.5em;
        margin-bottom: 8px;
    }

    .mini-stat-value {
        display: block;
        font-size: 1.2em;
        font-weight: 700;
        color: #fff;
        margin-bottom: 4px;
    }

    .mini-stat-label {
        font-size: 0.8em;
        color: rgba(255, 255, 255, 0.7);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Progress Bar */
    .page-progress-bar {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 0 0 25px 25px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--accent-color) 0%, #00d4ff 100%);
        border-radius: 0 0 25px 25px;
        transition: width 0.5s ease;
    }

    /* Animations */
    @keyframes headerGradientRotate {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    @keyframes patternMove {
        0% {
            transform: translate(0, 0);
        }

        100% {
            transform: translate(50px, 50px);
        }
    }

    @keyframes particleFloat {

        0%,
        100% {
            transform: translateY(0px) rotate(0deg);
            opacity: 0.4;
        }

        50% {
            transform: translateY(-20px) rotate(180deg);
            opacity: 0.8;
        }
    }

    @keyframes iconShine {
        0% {
            transform: translateX(-100%) translateY(-100%) rotate(45deg);
        }

        50% {
            transform: translateX(100%) translateY(100%) rotate(45deg);
        }

        100% {
            transform: translateX(200%) translateY(200%) rotate(45deg);
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .page-header-container {
            padding: 20px;
        }

        .page-main-title {
            font-size: 1.8em;
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .title-icon-wrapper {
            width: 50px;
            height: 50px;
        }

        .title-icon {
            font-size: 1.2rem;
        }

        .page-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .page-header-actions {
            align-items: flex-start;
            margin-top: 20px;
        }

        .quick-stats-mini {
            flex-direction: column;
            gap: 10px;
        }

        .action-group {
            flex-wrap: wrap;
        }
    }

    @media (max-width: 576px) {
        .page-main-title {
            font-size: 1.5em;
        }

        .btn-primary-glow {
            padding: 10px 20px;
            font-size: 0.9em;
        }

        .btn-text {
            display: none;
        }
    }
</style>

<script>
    // Update time and date in header
    function updateHeaderDateTime() {
        const now = new Date();
        const timeElement = document.getElementById('header-time');
        const dateElement = document.getElementById('header-date');

        if (timeElement) {
            timeElement.textContent = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        if (dateElement) {
            dateElement.textContent = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'long'
            });
        }
    }

    // Initialize and update every minute
    document.addEventListener('DOMContentLoaded', function() {
        updateHeaderDateTime();
        setInterval(updateHeaderDateTime, 60000);
    });
</script>
