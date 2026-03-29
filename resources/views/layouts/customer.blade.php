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
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f8f9fa;
        }

        /* ── Navbar ── */
        .navbar-custom {
            background: #1e3a5f;
            padding: 10px 0;
        }

        .navbar-custom .navbar-brand {
            color: #fff !important;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .navbar-custom .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            font-size: 0.9rem;
            padding: 6px 10px;
        }

        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link.active {
            color: #fff !important;
        }

        .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.3);
        }

        .navbar-toggler-icon {
            filter: invert(1);
        }

        /* ── Cart Badge ── */
        .cart-wrap {
            position: relative;
            display: inline-block;
        }

        .cart-badge {
            position: absolute;
            top: -6px;
            right: -8px;
            background: #ff6b35;
            color: #fff;
            border-radius: 50%;
            width: 17px;
            height: 17px;
            font-size: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        /* ── Buttons ── */
        .btn-orange {
            background: #ff6b35;
            color: #fff;
            border: none;
        }

        .btn-orange:hover {
            background: #e55a26;
            color: #fff;
        }

        /* ── Cards ── */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.07);
        }

        /* ── Status Badges ── */
        .badge-pending {
            background: #fff3cd;
            color: #856404;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.78rem;
        }

        .badge-confirmed {
            background: #cce5ff;
            color: #004085;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.78rem;
        }

        .badge-preparing {
            background: #d4edda;
            color: #155724;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.78rem;
        }

        .badge-out_for_delivery {
            background: #d1ecf1;
            color: #0c5460;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.78rem;
        }

        .badge-delivered {
            background: #d4edda;
            color: #155724;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.78rem;
        }

        .badge-cancelled {
            background: #f8d7da;
            color: #721c24;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.78rem;
        }

        /* ── Content ── */
        .content-wrap {
            padding: 20px 0 40px;
        }

        /* ── Mobile ── */
        @media (max-width: 768px) {
            .content-wrap {
                padding: 14px 0 30px;
            }

            .hide-mobile {
                display: none !important;
            }

            .table-responsive {
                font-size: 0.85rem;
            }
        }
    </style>
    @stack('styles')
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('customer.browse') }}">
                🍽️ FoodOrder
            </a>

            {{-- Mobile: cart icon + hamburger --}}
            <div class="d-flex align-items-center gap-2 d-lg-none">
                <a href="{{ route('customer.cart') }}" class="nav-link cart-wrap p-1">
                    <i class="bi bi-cart3 fs-5 text-white"></i>
                    @php $cartCount = Auth::user()->cartItems()->count(); @endphp
                    @if($cartCount > 0)
                        <span class="cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                    <i class="bi bi-list text-white fs-4"></i>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1 mt-2 mt-lg-0">
                    <li class="nav-item">
                        <a href="{{ route('customer.browse') }}"
                            class="nav-link {{ request()->routeIs('customer.browse') ? 'active' : '' }}">
                            <i class="bi bi-search me-1"></i>Browse
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('customer.orders') }}"
                            class="nav-link {{ request()->routeIs('customer.orders*') ? 'active' : '' }}">
                            <i class="bi bi-bag me-1"></i>My Orders
                        </a>
                    </li>
                    {{-- Cart (desktop only — mobile shown above) --}}
                    <li class="nav-item d-none d-lg-block">
                        <a href="{{ route('customer.cart') }}" class="nav-link cart-wrap">
                            <i class="bi bi-cart3 me-1"></i>Cart
                            @if($cartCount > 0)
                                <span class="cart-badge">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('customer.profile') }}"
                            class="nav-link {{ request()->routeIs('customer.profile') ? 'active' : '' }}">
                            <i class="bi bi-person me-1"></i>
                            <span class="d-none d-lg-inline">{{ Auth::user()->name }}</span>
                            <span class="d-lg-none">Profile</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-outline-light btn-sm mt-1 mt-lg-0">
                                <i class="bi bi-box-arrow-right me-1"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container content-wrap">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        window.Pusher = Pusher;
        window.Echo = new window.LaravelEcho({
            broadcaster: 'reverb',
            key: '{{ env("REVERB_APP_KEY") }}',
            wsHost: '{{ env("REVERB_HOST") }}',
            wsPort:       {{ env("REVERB_PORT", 8081) }},
            forceTLS: false,
            enabledTransports: ['ws'],
        });
    </script>
    @stack('scripts')
</body>

</html>