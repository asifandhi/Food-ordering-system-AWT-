<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 240px;
            --sidebar-bg: #1e3a5f;
            --accent: #ff6b35;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; margin: 0; }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }
        .sidebar .brand {
            padding: 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            flex-shrink: 0;
        }
        .sidebar nav { flex: 1; padding: 8px 0; overflow-y: auto; }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.75);
            padding: 11px 16px;
            border-radius: 8px;
            margin: 2px 8px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            transition: all 0.2s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: #fff;
        }
        .sidebar .nav-link i { font-size: 1rem; width: 18px; text-align: center; }
        .sidebar-divider {
            border-color: rgba(255,255,255,0.1);
            margin: 8px 16px;
        }

        /* ── Overlay (mobile) ── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1039;
        }
        .sidebar-overlay.show { display: block; }

        /* ── Main Content ── */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Topbar ── */
        .topbar {
            background: #fff;
            padding: 12px 20px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            gap: 12px;
        }
        .topbar-title { font-weight: 700; color: #1e3a5f; font-size: 1rem; }
        .hamburger {
            display: none;
            background: none;
            border: none;
            font-size: 1.4rem;
            color: #1e3a5f;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 6px;
        }
        .hamburger:hover { background: #f0f0f0; }

        /* ── Content Area ── */
        .content-area { padding: 20px; flex: 1; }

        /* ── Cards ── */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        }
        .stat-card { border-left: 4px solid; }

        /* ── Status Badges ── */
        .badge-pending       { background:#fff3cd; color:#856404; padding:5px 10px; border-radius:20px; font-size:0.8rem; }
        .badge-confirmed     { background:#cce5ff; color:#004085; padding:5px 10px; border-radius:20px; font-size:0.8rem; }
        .badge-preparing     { background:#d4edda; color:#155724; padding:5px 10px; border-radius:20px; font-size:0.8rem; }
        .badge-out_for_delivery { background:#d1ecf1; color:#0c5460; padding:5px 10px; border-radius:20px; font-size:0.8rem; }
        .badge-delivered     { background:#d4edda; color:#155724; padding:5px 10px; border-radius:20px; font-size:0.8rem; }
        .badge-cancelled     { background:#f8d7da; color:#721c24; padding:5px 10px; border-radius:20px; font-size:0.8rem; }

        .btn-primary-custom { background: #1e3a5f; color:#fff; border:none; }
        .btn-primary-custom:hover { background:#16304f; color:#fff; }

        /* ── Mobile ── */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                min-height: 100vh;
            }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .hamburger { display: block; }
            .content-area { padding: 14px; }
            .topbar { padding: 10px 14px; }

            /* Stack stat cards 2x2 on mobile */
            .stat-col { flex: 0 0 50%; max-width: 50%; }

            /* Make tables scrollable on mobile */
            .table-responsive-mobile { overflow-x: auto; -webkit-overflow-scrolling: touch; }

            /* Hide less important table columns on mobile */
            .hide-mobile { display: none !important; }

            /* Full width buttons on mobile */
            .btn-mobile-full { width: 100%; margin-bottom: 6px; }
        }

        @media (max-width: 400px) {
            .stat-col { flex: 0 0 100%; max-width: 100%; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- Mobile Overlay --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- Sidebar --}}
<div class="sidebar" id="sidebar">
    <div class="brand">
        <div class="text-white fw-bold fs-5">🍽️ FoodOrder</div>
        <div class="text-white-50 small">Hotelier Panel</div>
    </div>
    <nav>
        <a href="{{ route('hotelier.dashboard') }}"
           class="nav-link {{ request()->routeIs('hotelier.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ route('hotelier.profile') }}"
           class="nav-link {{ request()->routeIs('hotelier.profile') ? 'active' : '' }}">
            <i class="bi bi-shop"></i> My Profile
        </a>
        <a href="{{ route('hotelier.menu') }}"
           class="nav-link {{ request()->routeIs('hotelier.menu') ? 'active' : '' }}">
            <i class="bi bi-menu-button-wide"></i> Menu Management
        </a>
        <a href="{{ route('hotelier.orders') }}"
           class="nav-link {{ request()->routeIs('hotelier.orders*') ? 'active' : '' }}">
            <i class="bi bi-bag-check"></i> Orders
            @php
                $pendingBadge = \Illuminate\Support\Facades\DB::table('orders')
                    ->where('hotelier_id', Auth::user()->hotelierProfile->id ?? 0)
                    ->where('status', 'pending')
                    ->count();
            @endphp
            @if($pendingBadge > 0)
                <span class="badge bg-danger ms-auto">{{ $pendingBadge }}</span>
            @endif
        </a>
        <hr class="sidebar-divider">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="nav-link border-0 w-100 text-start"
                    style="background:transparent; cursor:pointer;">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </nav>
</div>

{{-- Main --}}
<div class="main-content" id="mainContent">

    {{-- Topbar --}}
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="hamburger" onclick="openSidebar()" aria-label="Menu">
                <i class="bi bi-list"></i>
            </button>
            <span class="topbar-title">@yield('title')</span>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
            @php $hp = Auth::user()->hotelierProfile; @endphp
            @if($hp)
                <form method="POST" action="{{ route('hotelier.toggle.open') }}" class="d-inline">
                    @csrf
                    <button type="submit"
                            class="btn btn-sm {{ $hp->is_open ? 'btn-success' : 'btn-danger' }}">
                        {{ $hp->is_open ? '🟢 Open' : '🔴 Closed' }}
                    </button>
                </form>
            @endif
            <span class="text-muted small d-none d-sm-inline">
                <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
            </span>
        </div>
    </div>

    {{-- Flash Messages --}}
    <div class="content-area" style="padding-bottom:0; padding-top:14px;">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <div class="content-area pt-2">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.add('open');
        document.getElementById('sidebarOverlay').classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('show');
        document.body.style.overflow = '';
    }
    // Close sidebar on nav link click (mobile)
    document.querySelectorAll('.sidebar .nav-link').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 768) closeSidebar();
        });
    });
</script>
@stack('scripts')
</body>
</html>