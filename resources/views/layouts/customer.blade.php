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
        body { font-family:'Segoe UI',sans-serif; background:#f8f9fa; }
        .navbar-custom { background:#1e3a5f; }
        .navbar-custom .nav-link { color:rgba(255,255,255,0.8) !important; }
        .navbar-custom .nav-link:hover { color:#fff !important; }
        .navbar-brand { color:#fff !important; font-weight:700; font-size:1.3rem; }
        .btn-orange { background:#ff6b35; color:#fff; border:none; }
        .btn-orange:hover { background:#e55a26; color:#fff; }
        .card { border:none; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,0.07); }
        .badge-pending      { background:#fff3cd; color:#856404; }
        .badge-confirmed    { background:#cce5ff; color:#004085; }
        .badge-preparing    { background:#d4edda; color:#155724; }
        .badge-out_for_delivery { background:#d1ecf1; color:#0c5460; }
        .badge-delivered    { background:#d4edda; color:#155724; }
        .badge-cancelled    { background:#f8d7da; color:#721c24; }
        .cart-count { position:relative; }
        .cart-badge {
            position:absolute; top:-8px; right:-8px;
            background:#ff6b35; color:#fff;
            border-radius:50%; width:18px; height:18px;
            font-size:10px; display:flex;
            align-items:center; justify-content:center;
        }
    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <a class="navbar-brand" href="{{ route('customer.browse') }}">🍽️ FoodOrder</a>
        <div class="ms-auto d-flex align-items-center gap-3">
            <a href="{{ route('customer.browse') }}"
               class="nav-link {{ request()->routeIs('customer.browse') ? 'text-white' : '' }}">
                <i class="fa fa-search"></i> Browse
            </a>
            <a href="{{ route('customer.orders') }}"
               class="nav-link {{ request()->routeIs('customer.orders*') ? 'text-white' : '' }}">
                <i class="fa fa-list"></i> My Orders
            </a>
            <a href="{{ route('customer.cart') }}" class="nav-link cart-count">
                <i class="fa fa-shopping-cart"></i> Cart
                @php
                    $cartCount = Auth::user()->cartItems()->count();
                @endphp
                @if($cartCount > 0)
                    <span class="cart-badge">{{ $cartCount }}</span>
                @endif
            </a>
            <a href="{{ route('customer.profile') }}"
               class="nav-link {{ request()->routeIs('customer.profile') ? 'text-white' : '' }}">
                <i class="fa fa-user"></i> {{ Auth::user()->name }}
            </a>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button class="btn btn-outline-light btn-sm">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container mt-4">
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
{{-- Laravel Echo + Reverb WebSocket --}}
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    window.Pusher = Pusher;
    window.Echo = new window.LaravelEcho({
        broadcaster: 'reverb',
        key:         '{{ env("REVERB_APP_KEY") }}',
        wsHost:      '{{ env("REVERB_HOST") }}',
        wsPort:       {{ env("REVERB_PORT", 8081) }},
        forceTLS:    false,
        enabledTransports: ['ws'],
    });
</script>

@stack('scripts')

</body>
</html>