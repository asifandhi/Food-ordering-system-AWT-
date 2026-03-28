@extends('layouts.app')
@section('title', 'Customer Register')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="text-center mb-4">
                <div style="font-size:3rem;">🧑‍💼</div>
                <h3 class="fw-bold" style="color:#1e3a5f;">Create Customer Account</h3>
                <p class="text-muted">Join us and order from the best restaurants near you.</p>
            </div>

            <div class="card p-4">

                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('register.customer.post') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Full Name</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Asif Khan" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   placeholder="9876543210" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="you@email.com" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">City</label>
                            <input type="text" name="city" value="{{ old('city') }}"
                                   class="form-control @error('city') is-invalid @enderror"
                                   placeholder="Surat" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Pincode</label>
                            <input type="text" name="pincode" value="{{ old('pincode') }}"
                                   class="form-control @error('pincode') is-invalid @enderror"
                                   placeholder="395001" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Min 6 characters" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Confirm Password</label>
                            <input type="password" name="password_confirmation"
                                   class="form-control"
                                   placeholder="Repeat password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-orange w-100 py-2 fw-bold">
                        <i class="fa fa-user-plus me-2"></i> Create Account
                    </button>
                </form>

                <hr>
                <p class="text-center mb-0">
                    Already have an account?
                    <a href="{{ route('login.customer') }}" style="color:#ff6b35;">Login here</a>
                </p>
                <p class="text-center mt-2 mb-0">
                    <a href="{{ route('landing') }}" class="text-muted small">← Back to Home</a>
                </p>

            </div>
        </div>
    </div>
</div>
@endsection