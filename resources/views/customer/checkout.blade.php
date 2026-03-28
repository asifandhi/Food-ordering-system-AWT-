@extends('layouts.customer')
@section('title', 'Checkout')

@section('content')

<div class="row g-4">

    {{-- Delivery & Payment --}}
    <div class="col-md-7">
        <div class="card p-4">
            <h5 class="fw-bold mb-4">📦 Checkout</h5>

            <form method="POST" action="{{ route('customer.order.place') }}" id="checkoutForm">
                @csrf

                <input type="hidden" name="delivery_lat" id="checkout_lat" value="{{ $customerLat }}">
                <input type="hidden" name="delivery_lng" id="checkout_lng" value="{{ $customerLng }}">

                {{-- Delivery Address --}}
                <h6 class="fw-bold mb-3">📍 Delivery Address</h6>

                @if($addresses->isNotEmpty())
                    <div class="mb-3">
                        @foreach($addresses as $addr)
                        <div class="form-check border rounded p-3 mb-2
                            {{ $addr->is_default ? 'border-warning' : '' }}">
                            <input class="form-check-input" type="radio"
                                   name="saved_address_id"
                                   value="{{ $addr->id }}"
                                   id="addr{{ $addr->id }}"
                                   {{ $addr->is_default ? 'checked' : '' }}
                                   onchange="selectAddress(
                                       '{{ $addr->address_line }}, {{ $addr->city }} - {{ $addr->pincode }}',
                                       '{{ $addr->latitude }}', '{{ $addr->longitude }}'
                                   )">
                            <label class="form-check-label w-100" for="addr{{ $addr->id }}">
                                <span class="badge bg-warning text-dark me-1">
                                    {{ ucfirst($addr->label) }}
                                </span>
                                {{ $addr->address_line }}, {{ $addr->city }} — {{ $addr->pincode }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label fw-semibold">Delivery Address</label>
                    <textarea name="delivery_address" id="delivery_address"
                              class="form-control" rows="2" required
                              placeholder="Enter your full delivery address">{{ old('delivery_address', $addresses->where('is_default',1)->first()?->address_line . ', ' . $addresses->where('is_default',1)->first()?->city) }}</textarea>
                </div>

                <hr>

                {{-- Payment Method --}}
                <h6 class="fw-bold mb-3">💳 Payment Method</h6>

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <label class="d-block border rounded p-3 text-center cursor-pointer">
                            <input type="radio" name="payment_method" value="cod" checked class="me-2">
                            💵 Cash on Delivery
                        </label>
                    </div>
                    <div class="col-6">
                        <label class="d-block border rounded p-3 text-center cursor-pointer">
                            <input type="radio" name="payment_method" value="online" class="me-2">
                            💳 Online Payment
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-orange w-100 py-3 fw-bold fs-5">
                    🛵 Place Order — ₹{{ number_format($grandTotal, 2) }}
                </button>

            </form>
        </div>
    </div>

    {{-- Order Summary --}}
    <div class="col-md-5">
        <div class="card p-4">
            <h6 class="fw-bold mb-3">🏨 {{ $hotelier->hotel_name }}</h6>

            @foreach($cartItems as $item)
            <div class="d-flex justify-content-between mb-2 small">
                <span>{{ $item->foodItem->name }} × {{ $item->quantity }}</span>
                <span>₹{{ number_format($item->quantity * $item->foodItem->price, 2) }}</span>
            </div>
            @endforeach

            <hr>

            <div class="d-flex justify-content-between mb-1">
                <span class="text-muted">Subtotal</span>
                <span>₹{{ number_format($subtotal, 2) }}</span>
            </div>
            <div class="d-flex justify-content-between mb-1">
                <span class="text-muted">Delivery Charge</span>
                <span id="delivery_charge_display">
                    ₹{{ number_format($deliveryInfo['charge'], 2) }}
                </span>
            </div>
            @if($distance)
            <div class="d-flex justify-content-between mb-1">
                <span class="text-muted">Distance</span>
                <span>{{ $distance }} km</span>
            </div>
            <div class="d-flex justify-content-between mb-1">
                <span class="text-muted">Est. Delivery Time</span>
                <span>{{ $deliveryInfo['estimated_time'] }} min</span>
            </div>
            @endif

            <hr>

            <div class="d-flex justify-content-between fw-bold fs-5">
                <span>Grand Total</span>
                <span class="text-success">₹{{ number_format($grandTotal, 2) }}</span>
            </div>

            @if($hotelier->minimum_order > 0)
            <p class="text-muted small mt-2">
                Min. order: ₹{{ $hotelier->minimum_order }}
            </p>
            @endif
            @if($hotelier->free_delivery_above)
            <p class="text-success small">
                🎉 Free delivery on orders above ₹{{ $hotelier->free_delivery_above }}
            </p>
            @endif
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
function selectAddress(address, lat, lng) {
    document.getElementById('delivery_address').value = address;
    if (lat) document.getElementById('checkout_lat').value = lat;
    if (lng) document.getElementById('checkout_lng').value = lng;
}
</script>
@endpush