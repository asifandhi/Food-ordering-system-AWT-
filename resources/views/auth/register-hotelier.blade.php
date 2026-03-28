@extends('layouts.app')
@section('title', 'Hotelier Register')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="text-center mb-4">
                <div style="font-size:3rem;">🏨</div>
                <h3 class="fw-bold" style="color:#1e3a5f;">Register Your Restaurant</h3>
                <p class="text-muted">Fill in your details. Admin will approve your account within 24 hours.</p>
            </div>

            <div class="card p-4">

                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('register.hotelier.post') }}">
                    @csrf

                    <h6 class="fw-bold text-muted mb-3 border-bottom pb-2">👤 Owner Details</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Owner Full Name</label>
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
                               placeholder="restaurant@email.com" required>
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
                                   class="form-control" placeholder="Repeat password" required>
                        </div>
                    </div>

                    <h6 class="fw-bold text-muted mb-3 border-bottom pb-2 mt-2">🏨 Restaurant Details</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Restaurant Name</label>
                            <input type="text" name="hotel_name" value="{{ old('hotel_name') }}"
                                   class="form-control @error('hotel_name') is-invalid @enderror"
                                   placeholder="Asif's Kitchen" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Cuisine Type</label>
                            <select name="cuisine_type"
                                    class="form-select @error('cuisine_type') is-invalid @enderror" required>
                                <option value="">-- Select Cuisine --</option>
                                <option value="Indian"    {{ old('cuisine_type')=='Indian'    ? 'selected' : '' }}>Indian</option>
                                <option value="Chinese"   {{ old('cuisine_type')=='Chinese'   ? 'selected' : '' }}>Chinese</option>
                                <option value="Pizza"     {{ old('cuisine_type')=='Pizza'     ? 'selected' : '' }}>Pizza</option>
                                <option value="Burger"    {{ old('cuisine_type')=='Burger'    ? 'selected' : '' }}>Burger</option>
                                <option value="Biryani"   {{ old('cuisine_type')=='Biryani'   ? 'selected' : '' }}>Biryani</option>
                                <option value="Gujarati"  {{ old('cuisine_type')=='Gujarati'  ? 'selected' : '' }}>Gujarati</option>
                                <option value="South Indian" {{ old('cuisine_type')=='South Indian' ? 'selected' : '' }}>South Indian</option>
                                <option value="Fast Food" {{ old('cuisine_type')=='Fast Food' ? 'selected' : '' }}>Fast Food</option>
                                <option value="Other"     {{ old('cuisine_type')=='Other'     ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Restaurant Address</label>
                        <textarea name="address" rows="2"
                                  class="form-control @error('address') is-invalid @enderror"
                                  placeholder="Shop No., Street, Area" required>{{ old('address') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">City</label>
                            <input type="text" name="city" value="{{ old('city') }}"
                                   class="form-control @error('city') is-invalid @enderror"
                                   placeholder="Surat" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Pincode</label>
                            <input type="text" name="pincode" value="{{ old('pincode') }}"
                                   class="form-control @error('pincode') is-invalid @enderror"
                                   placeholder="395001" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label fw-semibold">GSTIN</label>
                            <input type="text" name="gstin" value="{{ old('gstin') }}"
                                   class="form-control" placeholder="Optional">
                        </div>
                    </div>

                    <div class="alert alert-warning py-2 small">
                        ⚠️ After registration your account will be <strong>reviewed by admin</strong>
                        before you can login. This usually takes up to 24 hours.
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                        <i class="fa fa-store me-2"></i> Submit Registration
                    </button>
                </form>

                <hr>
                <p class="text-center mb-0">
                    Already registered?
                    <a href="{{ route('login.hotelier') }}" class="text-success">Login here</a>
                </p>
                <p class="text-center mt-2 mb-0">
                    <a href="{{ route('landing') }}" class="text-muted small">← Back to Home</a>
                </p>

            </div>
        </div>
    </div>
</div>
@endsection