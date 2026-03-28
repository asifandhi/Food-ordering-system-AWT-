<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
            margin: 0;
        }

        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: #1e3a5f;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            overflow-y: auto;
        }

        .sidebar .brand {
            padding: 20px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
            padding: 10px 16px;
            border-radius: 6px;
            margin: 2px 8px;
            font-size: 0.9rem;
            display: block;
            text-decoration: none;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
        }

        .sidebar .nav-link i {
            width: 20px;
        }

        .main-content {
            margin-left: 240px;
            padding: 24px;
            min-width: 0;
            width: calc(100% - 240px);
            overflow-x: hidden;
        }

        .topbar {
            background: #fff;
            padding: 12px 24px;
            margin: -24px -24px 24px -24px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.07);
        }

        .stat-card {
            border-left: 4px solid;
        }

        .badge-pending {
            background: #fff3cd;
            color: #856404;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .badge-confirmed {
            background: #cce5ff;
            color: #004085;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .badge-preparing {
            background: #d4edda;
            color: #155724;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .badge-out_for_delivery {
            background: #d1ecf1;
            color: #0c5460;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .badge-delivered {
            background: #d4edda;
            color: #155724;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .badge-cancelled {
            background: #f8d7da;
            color: #721c24;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .btn-primary-custom {
            background: #1e3a5f;
            color: #fff;
            border: none;
        }

        .btn-primary-custom:hover {
            background: #16304f;
            color: #fff;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                min-height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
    @stack('styles')
</head>

<body>

    {{-- Sidebar --}}
    <div class="sidebar">
        <div class="brand">
            <div class="text-white fw-bold fs-5">🍽️ FoodOrder</div>
            <div class="text-white-50 small">Hotelier Panel</div>
        </div>
        <nav class="mt-3">
            <a href="{{ route('hotelier.dashboard') }}"
                class="nav-link {{ request()->routeIs('hotelier.dashboard') ? 'active' : '' }}">
                <i class="fa fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="{{ route('hotelier.profile') }}"
                class="nav-link {{ request()->routeIs('hotelier.profile') ? 'active' : '' }}">
                <i class="fa fa-store"></i> My Profile
            </a>
            <a href="{{ route('hotelier.menu') }}"
                class="nav-link {{ request()->routeIs('hotelier.menu') ? 'active' : '' }}">
                <i class="fa fa-utensils"></i> Menu Management
            </a>
            <a href="{{ route('hotelier.orders') }}"
                class="nav-link {{ request()->routeIs('hotelier.orders*') ? 'active' : '' }}">
                <i class="fa fa-list-alt"></i> Orders
            </a>
            <hr style="border-color:rgba(255,255,255,0.1); margin: 10px 16px;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link border-0 w-100 text-start" style="background:transparent;">
                    <i class="fa fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </nav>
    </div>

    {{-- Main Content --}}
    <div class="main-content">
        <div class="topbar">
            <div class="fw-bold text-dark">@yield('title')</div>
            <div class="d-flex align-items-center gap-3">
                @php $profile = Auth::user()->hotelierProfile; @endphp
                @if($profile)
                    <form method="POST" action="{{ route('hotelier.toggle.open') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm {{ $profile->is_open ? 'btn-success' : 'btn-danger' }}">
                            {{ $profile->is_open ? '🟢 Open' : '🔴 Closed' }}
                        </button>
                    </form>
                @endif
                <span class="text-muted small">{{ Auth::user()->name }}</span>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    @stack('scripts')
</body>

</html>