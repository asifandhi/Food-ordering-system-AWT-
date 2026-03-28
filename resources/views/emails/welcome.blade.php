<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background:#f4f4f4; margin:0; padding:0; }
        .container { max-width:600px; margin:30px auto; background:#fff; border-radius:12px; overflow:hidden; }
        .header { background:#1e3a5f; padding:30px; text-align:center; }
        .header h1 { color:#fff; margin:0; font-size:24px; }
        .body { padding:30px; }
        .body h2 { color:#1e3a5f; }
        .btn { display:inline-block; background:#ff6b35; color:#fff;
               padding:12px 30px; border-radius:8px; text-decoration:none;
               font-weight:bold; margin-top:20px; }
        .footer { background:#f4f4f4; padding:16px; text-align:center; color:#999; font-size:12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🍽️ Food Ordering System</h1>
        </div>
        <div class="body">
            <h2>Welcome, {{ $user->name }}! 🎉</h2>
            <p>Your account is ready. Browse restaurants near you and order food in minutes.</p>
            <a href="{{ config('app.url') }}/customer/login" class="btn">Start Ordering →</a>
            <p style="margin-top:24px; color:#777;">
                Registered email: <b>{{ $user->email }}</b>
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Food Ordering System. All rights reserved.
        </div>
    </div>
</body>
</html>