@extends('layouts.customer')
@section('title', 'My Profile')

@section('content')

<div class="row g-4">

    {{-- Profile Info --}}
    <div class="col-md-5">
        <div class="card p-4 mb-4">
            <h6 class="fw-bold mb-3">👤 My Profile</h6>
            <form method="POST" action="{{ route('customer.profile.update') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Full Name</label>
                    <input type="text" name="name" value="{{ Auth::user()->name }}"
                           class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Phone</label>
                    <input type="text" name="phone" value="{{ Auth::user()->phone }}"
                           class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">City</label>
                    <input type="text" name="city"
                           value="{{ $profile->city ?? '' }}"
                           class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="text" value="{{ Auth::user()->email }}"
                           class="form-control" disabled>
                </div>
                <button type="submit" class="btn btn-orange w-100">Save Profile</button>
            </form>
        </div>

        {{-- Recent Orders --}}
        <div class="card p-4">
            <h6 class="fw-bold mb-3">📦 Recent Orders</h6>
            @if($orders->isEmpty())
                <p class="text-muted small">No orders yet.</p>
            @else
                @foreach($orders as $order)
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <div>
                        <span class="small fw-bold">#{{ $order->id }}</span>
                        <div class="small text-muted">{{ $order->created_at->format('d M Y') }}</div>
                    </div>
                    <div class="text-end">
                        <div class="small fw-bold">₹{{ number_format($order->grand_total,2) }}</div>
                        <span class="badge badge-{{ $order->status }}" style="font-size:0.65rem;">
                            {{ ucfirst(str_replace('_',' ',$order->status)) }}
                        </span>
                    </div>
                </div>
                @endforeach
                <a href="{{ route('customer.orders') }}" class="btn btn-outline-secondary btn-sm w-100 mt-2">
                    View All Orders
                </a>
            @endif
        </div>
    </div>

    {{-- Addresses --}}
    <div class="col-md-7">
        <div class="card p-4">
            <h6 class="fw-bold mb-3">📍 Saved Addresses</h6>

            @if($addresses->isNotEmpty())
                @foreach($addresses as $addr)
                <div class="border rounded p-3 mb-2 {{ $addr->is_default ? 'border-warning' : '' }}">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="badge bg-warning text-dark me-1">{{ ucfirst($addr->label) }}</span>
                            @if($addr->is_default)
                                <span class="badge bg-success">Default</span>
                            @endif
                            <p class="mb-0 mt-1">{{ $addr->address_line }}, {{ $addr->city }} — {{ $addr->pincode }}</p>
                        </div>
                        <div class="d-flex gap-1">
                            @if(!$addr->is_default)
                            <form method="POST" action="{{ route('customer.address.default', $addr->id) }}">
                                @csrf
                                <button class="btn btn-sm btn-outline-warning">Set Default</button>
                            </form>
                            @endif
                            <form method="POST"
                                  action="{{ route('customer.address.delete', $addr->id) }}"
                                  onsubmit="return confirm('Delete this address?')">
                                @csrf
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <p class="text-muted small">No saved addresses yet.</p>
            @endif

            <hr>
            <h6 class="fw-bold mb-3">➕ Add New Address</h6>
            <form method="POST" action="{{ route('customer.address.store') }}">
                @csrf
                <div class="row g-2">
                    <div class="col-md-3">
                        <select name="label" class="form-select" required>
                            <option value="home">🏠 Home</option>
                            <option value="work">💼 Work</option>
                            <option value="other">📍 Other</option>
                        </select>
                    </div>
                    <div class="col-md-9">
                        <input type="text" name="address_line" class="form-control"
                               placeholder="Full address" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="city" class="form-control"
                               placeholder="City" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="pincode" class="form-control"
                               placeholder="Pincode" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-orange w-100">Save</button>
                    </div>
                    <input type="hidden" name="latitude"  id="addr_lat">
                    <input type="hidden" name="longitude" id="addr_lng">
                </div>
            </form>
        </div>
    </div>

</div>

@endsection