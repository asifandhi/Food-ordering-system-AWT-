@extends('layouts.app')
@section('title', 'Customer Login')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="text-center mb-4">
                <div style="font-size:3rem;">🧑‍💼</div>
                <h3 class="fw-bold" style="color:#1e3a5f;">Customer Login</h3>
                <p class="text-muted">Welcome back! Enter your details to continue.</p>
            </div>

            <div class="card p-4">

                {{-- Validation Errors --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login.customer.post') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="you@email.com" required autofocus>
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

                    <button type="submit" class="btn btn-orange w-100 py-2 fw-bold">
                        <i class="fa fa-sign-in-alt me-2"></i> Login
                    </button>
                </form>

                <hr>
                <p class="text-center mb-0">
                    Don't have an account?
                    <a href="{{ route('register.customer') }}" style="color:#ff6b35;">Register here</a>
                </p>
                <p class="text-center mt-2 mb-0">
                    <a href="{{ route('landing') }}" class="text-muted small">← Back to Home</a>
                </p>

            </div>
        </div>
    </div>
</div>
@endsection