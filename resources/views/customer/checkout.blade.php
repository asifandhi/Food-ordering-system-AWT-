@extends('layouts.customer')
@section('title', 'Checkout')

@section('content')

    <div class="row g-4">

        {{-- Order Summary (mobile: top, desktop: right) --}}
        <div class="col-12 col-md-5 order-1 order-md-2">
            <div class="card p-3 p-md-4" style="position:sticky; top:70px;">
                <h6 class="fw-bold mb-3">🏨 {{ $hotelier->hotel_name }}</h6>
                @foreach($cartItems as $item)
                    <div class="d-flex justify-content-between mb-2 small">
                        <span>{{ $item->foodItem->name }} × {{ $item->quantity }}</span>
                        <span>₹{{ number_format($item->quantity * $item->foodItem->price, 2) }}</span>
                    </div>
                @endforeach
                <hr>
                <div class="d-flex justify-content-between mb-1 small">
                    <span class="text-muted">Subtotal</span>
                    <span>₹{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1 small">
                    <span class="text-muted">Delivery Charge</span>
                    <span>₹{{ number_format($deliveryInfo['charge'], 2) }}</span>
                </div>
                @if($distance)
                    <div class="d-flex justify-content-between mb-1 small">
                        <span class="text-muted">Distance</span>
                        <span>{{ $distance }} km</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1 small">
                        <span class="text-muted">Est. Time</span>
                        <span>{{ $deliveryInfo['estimated_time'] }} min</span>
                    </div>
                @endif
                <hr>
                <div class="d-flex justify-content-between fw-bold">
                    <span>Grand Total</span>
                    <span id="grandTotalValue" class="text-success fs-5">
                        ₹{{ number_format($grandTotal, 2) }}
                    </span>
                </div>
                @if($hotelier->minimum_order > 0)
                    <p class="text-muted small mt-2 mb-0">Min. order: ₹{{ $hotelier->minimum_order }}</p>
                @endif
                @if($hotelier->free_delivery_above)
                    <p class="text-success small mb-0">🎉 Free delivery above ₹{{ $hotelier->free_delivery_above }}</p>
                @endif
            </div>
        </div>

        {{-- Form --}}
        <div class="col-12 col-md-7 order-2 order-md-1">
            <div class="card p-3 p-md-4">
                <h5 class="fw-bold mb-4">📦 Checkout</h5>

                <form method="POST" action="{{ route('customer.order.place') }}" id="checkoutForm">
                    @csrf
                    <input type="hidden" name="delivery_lat" id="checkout_lat" value="{{ $customerLat }}">
                    <input type="hidden" name="delivery_lng" id="checkout_lng" value="{{ $customerLng }}">

                    {{-- Saved Addresses --}}
                    <h6 class="fw-semibold mb-2">📍 Delivery Address</h6>
                    @if($addresses->isNotEmpty())
                        <div class="mb-3">
                            @foreach($addresses as $addr)
                                <div
                                    class="form-check border rounded p-2 mb-2 {{ $addr->is_default ? 'border-warning bg-light' : '' }}">
                                    <input class="form-check-input" type="radio" name="saved_address_id" value="{{ $addr->id }}"
                                        id="addr{{ $addr->id }}" {{ $addr->is_default ? 'checked' : '' }} onchange="selectAddress(
                                                   '{{ $addr->address_line }}, {{ $addr->city }} - {{ $addr->pincode }}',
                                                   '{{ $addr->latitude }}', '{{ $addr->longitude }}'
                                               )">
                                    <label class="form-check-label small w-100" for="addr{{ $addr->id }}">
                                        <span class="badge bg-warning text-dark me-1">{{ ucfirst($addr->label) }}</span>
                                        {{ $addr->address_line }}, {{ $addr->city }} — {{ $addr->pincode }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="mb-3">
                        <textarea name="delivery_address" id="delivery_address" class="form-control" rows="2" required
                            placeholder="Enter your full delivery address">{{ old('delivery_address', $addresses->where('is_default', 1)->first()?->address_line . ', ' . $addresses->where('is_default', 1)->first()?->city) }}</textarea>
                    </div>

                    <hr>

                    {{-- Payment --}}
                    <h6 class="fw-semibold mb-2">💳 Payment Method</h6>
                    <div class="d-flex gap-3 mb-3 flex-wrap">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked
                                onchange="togglePayment('cod')">
                            <label class="form-check-label" for="cod">💵 Cash on Delivery</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="online" value="online"
                                onchange="togglePayment('online')">
                            <label class="form-check-label" for="online">📱 Online Payment</label>
                        </div>
                    </div>

                    {{-- QR Payment Panel --}}
                    <div id="onlinePaymentPanel" style="display:none;"
                        class="card bg-light border-0 rounded-3 p-3 mb-3 text-center">
                        <h6 class="fw-bold mb-1">Scan QR to Pay</h6>
                        <p class="text-muted small mb-2">Use GPay, PhonePe, or Paytm</p>
                        <img id="qrImage"
                            src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=upi://pay?pa=demo@upi"
                            class="mx-auto d-block mb-2 rounded border" style="width:160px; height:160px;" alt="QR">
                        <p class="small mb-2">Amount: <strong class="text-success" id="qrAmount">₹0.00</strong></p>
                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                            <button type="button" onclick="confirmPayment()" class="btn btn-success btn-sm px-3">
                                <i class="bi bi-check-circle me-1"></i>Payment Done
                            </button>
                            <button type="button" onclick="cancelPayment()" class="btn btn-outline-danger btn-sm px-3">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </button>
                        </div>
                        <input type="hidden" name="payment_status" id="paymentStatusInput" value="pending">
                        <div id="paymentConfirmedMsg" style="display:none;"
                            class="alert alert-success mt-2 mb-0 py-2 small">
                            ✅ Payment confirmed! Click Place Order.
                        </div>
                        <div id="paymentCancelledMsg" style="display:none;" class="alert alert-danger mt-2 mb-0 py-2 small">
                            ❌ Payment cancelled.
                        </div>
                    </div>

                    <button type="submit" class="btn btn-orange w-100 py-3 fw-bold fs-5 mt-2">
                        🛵 Place Order — ₹{{ number_format($grandTotal, 2) }}
                    </button>
                </form>
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
        function togglePayment(method) {
            const panel = document.getElementById('onlinePaymentPanel');
            if (method === 'online') { panel.style.display = 'block'; updateQR(); }
            else { panel.style.display = 'none'; document.getElementById('paymentStatusInput').value = 'pending'; }
        }
        function updateQR() {
            const el = document.getElementById('grandTotalValue');
            const amount = el ? el.textContent.replace(/[^0-9.]/g, '') : '0';
            document.getElementById('qrAmount').textContent = '₹' + parseFloat(amount).toFixed(2);
            document.getElementById('qrImage').src =
                'https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=upi://pay?pa=demo@upi%26am=' + amount + '%26cu=INR';
        }
        function confirmPayment() {
            document.getElementById('paymentStatusInput').value = 'paid';
            document.getElementById('paymentConfirmedMsg').style.display = 'block';
            document.getElementById('paymentCancelledMsg').style.display = 'none';
        }
        function cancelPayment() {
            document.getElementById('paymentStatusInput').value = 'pending';
            document.getElementById('paymentCancelledMsg').style.display = 'block';
            document.getElementById('paymentConfirmedMsg').style.display = 'none';
            document.getElementById('cod').checked = true;
            document.getElementById('onlinePaymentPanel').style.display = 'none';
        }
    </script>
@endpush