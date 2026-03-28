<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Ordering System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
        }
        .hero {
            background: linear-gradient(135deg, #1e3a5f 0%, #2e86c1 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .role-card {
            background: #fff;
            border-radius: 16px;
            padding: 40px 30px;
            text-align: center;
            transition: transform 0.2s;
        }
        .role-card:hover { transform: translateY(-5px); }
        .btn-orange {
            background: #ff6b35;
            color: #fff;
            border: none;
            padding: 12px 0;
            font-weight: 600;
            border-radius: 8px;
            width: 100%;
            display: block;
            text-decoration: none;
            margin-bottom: 10px;
        }
        .btn-orange:hover { background: #e55a26; color: #fff; }
        .btn-outline-gray {
            border: 2px solid #ccc;
            color: #555;
            padding: 10px 0;
            font-weight: 500;
            border-radius: 8px;
            width: 100%;
            display: block;
            text-decoration: none;
            background: transparent;
        }
        .btn-outline-gray:hover { background: #f5f5f5; color: #333; }
        .btn-green {
            background: #1e8449;
            color: #fff;
            border: none;
            padding: 12px 0;
            font-weight: 600;
            border-radius: 8px;
            width: 100%;
            display: block;
            text-decoration: none;
            margin-bottom: 10px;
        }
        .btn-green:hover { background: #176038; color: #fff; }
    </style>
</head>
<body>

<div class="hero">
    <div class="container text-center text-white py-5">

        <div style="font-size: 5rem;">🍽️</div>
        <h1 class="fw-bold display-4 mt-2">Food Ordering System</h1>
        <p class="lead mt-2 mb-5" style="opacity: 0.8;">
            Order from the best restaurants near you — fast, fresh & delivered to your door.
        </p>

        <p class="fw-semibold fs-5 mb-4">Who are you?</p>

        <div class="row justify-content-center g-4">

            {{-- Customer Card --}}
            <div class="col-md-4">
                <div class="role-card" style="border-top: 5px solid #ff6b35;">
                    <div style="font-size: 3.5rem;">🧑‍💼</div>
                    <h4 class="fw-bold mt-3" style="color: #1e3a5f;">I am a Customer</h4>
                    <p class="text-muted mb-4">
                        Browse restaurants near you, order food, and track your delivery in real time.
                    </p>
                    <a href="{{ route('login.customer') }}" class="btn-orange">
                        <i class="fa fa-sign-in-alt me-2"></i> Customer Login
                    </a>
                    <a href="{{ route('register.customer') }}" class="btn-outline-gray">
                        New here? Register
                    </a>
                </div>
            </div>

            {{-- Hotelier Card --}}
            <div class="col-md-4">
                <div class="role-card" style="border-top: 5px solid #1e8449;">
                    <div style="font-size: 3.5rem;">🏨</div>
                    <h4 class="fw-bold mt-3" style="color: #1e3a5f;">I am a Hotelier</h4>
                    <p class="text-muted mb-4">
                        List your restaurant, manage your menu, and receive orders from nearby customers.
                    </p>
                    <a href="{{ route('login.hotelier') }}" class="btn-green">
                        <i class="fa fa-sign-in-alt me-2"></i> Hotelier Login
                    </a>
                    <a href="{{ route('register.hotelier') }}" class="btn-outline-gray">
                        New here? Register
                    </a>
                </div>
            </div>

        </div>

        <div class="mt-5">
            <small style="opacity: 0.5;">
                Are you an admin?
                <a href="/admin/login" class="text-white">Admin Login</a>
            </small>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>