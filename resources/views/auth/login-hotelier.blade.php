@extends('layouts.app')
@section('title', 'Hotelier Login')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="text-center mb-4">
                <div style="font-size:3rem;">🏨</div>
                <h3 class="fw-bold" style="color:#1e3a5f;">Hotelier Login</h3>
                <p class="text-muted">Login to manage your restaurant and orders.</p>
            </div>

            <div class="card p-4">

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login.hotelier.post') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="restaurant@email.com" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Enter password" required>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                        <i class="fa fa-sign-in-alt me-2"></i> Login to Dashboard
                    </button>
                </form>

                <hr>
                <p class="text-center mb-0">
                    Don't have a restaurant account?
                    <a href="{{ route('register.hotelier') }}" class="text-success">Register here</a>
                </p>
                <p class="text-center mt-2 mb-0">
                    <a href="{{ route('landing') }}" class="text-muted small">← Back to Home</a>
                </p>

            </div>
        </div>
    </div>
</div>
@endsection