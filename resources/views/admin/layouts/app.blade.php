<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel — @yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --sidebar-w: 250px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: #f4f6f9;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .sidebar .brand {
            color: #fff;
            font-size: 1.1rem;
            font-weight: 700;
            padding: 18px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
        }

        .sidebar nav {
            flex: 1;
            padding: 6px 0;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.7);
            padding: 11px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s;
            border-left: 3px solid transparent;
            font-size: 0.9rem;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.08);
            border-left-color: #0dcaf0;
        }

        .sidebar-section {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.3);
            padding: 12px 20px 3px;
        }

        /* ── Overlay ── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1039;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* ── Main ── */
        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 99;
            gap: 10px;
        }

        .hamburger {
            display: none;
            background: none;
            border: none;
            font-size: 1.4rem;
            color: #1a1a2e;
            cursor: pointer;
            padding: 2px 6px;
            border-radius: 6px;
        }

        .hamburger:hover {
            background: #f0f0f0;
        }

        .content-area {
            padding: 20px;
            flex: 1;
        }

        /* ── Cards ── */
        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.07);
        }

        /* ── Mobile ── */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .hamburger {
                display: block;
            }

            .content-area {
                padding: 14px;
            }

            .hide-mobile {
                display: none !important;
            }

            .topbar-title {
                font-size: 0.9rem;
            }
        }
    </style>
    @stack('styles')
</head>

<body>

    {{-- Overlay --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    {{-- Sidebar --}}
    <div class="sidebar" id="sidebar">
        <div class="brand">
            <i class="bi bi-shield-fill-check text-info me-2"></i>Admin Panel
        </div>
        <nav>
            <div class="sidebar-section">Main</div>
            <a href="{{ route('admin.dashboard') }}"
                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <div class="sidebar-section">Management</div>
            <a href="{{ route('admin.hoteliers.index') }}"
                class="nav-link {{ request()->routeIs('admin.hoteliers.*') ? 'active' : '' }}">
                <i class="bi bi-shop"></i> Hoteliers
                @php
                    $pendingCount = \Illuminate\Support\Facades\DB::table('hotelier_profiles')
                        ->where('status', 'pending')->count();
                @endphp
                @if($pendingCount > 0)
                    <span class="badge bg-danger ms-auto">{{ $pendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.users.index') }}"
                class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Customers
            </a>
            <a href="{{ route('admin.orders.index') }}"
                class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-bag-check"></i> Orders
            </a>

            <div class="sidebar-section">Reports</div>
            <a href="{{ route('admin.revenue.index') }}"
                class="nav-link {{ request()->routeIs('admin.revenue.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line"></i> Revenue
            </a>
            <a href="{{ route('admin.reviews.index') }}"
                class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                <i class="bi bi-star"></i> Reviews
            </a>

            <div class="sidebar-section">Account</div>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link border-0 w-100 text-start"
                    style="background:transparent; cursor:pointer; color:rgba(255,255,255,0.7);">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </nav>
    </div>

    {{-- Main --}}
    <div class="main-content" id="mainContent">
        <div class="topbar">
            <div class="d-flex align-items-center gap-2">
                <button class="hamburger" onclick="openSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <h6 class="mb-0 fw-semibold topbar-title">@yield('title', 'Dashboard')</h6>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted small d-none d-sm-inline">
                    <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                </span>
                <form action="{{ route('admin.logout') }}" method="POST" class="mb-0">
                    @csrf
                    <button class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="d-none d-sm-inline ms-1">Logout</span>
                    </button>
                </form>
            </div>
        </div>

        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
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
        document.querySelectorAll('.sidebar .nav-link').forEach(l => {
            l.addEventListener('click', () => { if (window.innerWidth <= 768) closeSidebar(); });
        });
    </script>
    @stack('scripts')
</body>

</html>