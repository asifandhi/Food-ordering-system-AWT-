@extends('layouts.customer')
@section('title', 'Order #' . $order->id)

@section('content')

{{-- Tracking Card --}}
<div class="card p-3 p-md-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h5 class="fw-bold mb-0">Order #{{ $order->id }}</h5>
        <span class="badge badge-{{ $order->status }} fs-6" id="status-badge">
            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
        </span>
    </div>

    @php
        $steps = ['pending','confirmed','preparing','out_for_delivery','delivered'];
        $currentStep = array_search($order->status, $steps);
        if($order->status === 'cancelled') $currentStep = -1;
    @endphp

    @if($order->status !== 'cancelled')
    {{-- Progress Steps --}}
    <div class="position-relative mb-4 mt-2">
        <div class="position-absolute w-100"
             style="height:4px; background:#e0e0e0; top:18px; left:0; z-index:0;"></div>
        <div id="progress-bar" class="position-absolute"
             style="height:4px; background:#ff6b35; top:18px; left:0; z-index:1;
                    width:{{ $currentStep >= 0 ? ($currentStep/(count($steps)-1))*100 : 0 }}%;
                    transition: width 0.5s ease;">
        </div>
        <div class="d-flex justify-content-between position-relative" style="z-index:2;">
            @foreach($steps as $i => $step)
            <div class="text-center" style="flex:1;">
                <div id="step-{{ $i }}"
                     class="rounded-circle mx-auto mb-1 d-flex align-items-center justify-content-center fw-bold"
                     style="width:36px; height:36px; font-size:0.8rem;
                            background:{{ $i <= $currentStep ? '#ff6b35':'#e0e0e0' }};
                            color:{{ $i <= $currentStep ? '#fff':'#999' }};">
                    {{ $i <= $currentStep ? '✓' : ($i+1) }}
                </div>
                <div class="text-muted" style="font-size:0.65rem; line-height:1.2;">
                    {{ ucfirst(str_replace('_',' ',$step)) }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
        <div class="alert alert-danger mb-0">❌ This order was cancelled.</div>
    @endif
</div>

<div class="row g-3">

    {{-- Order Info + Items --}}
    <div class="col-12 col-md-7">

        <div class="card p-3 p-md-4 mb-3">
            <h6 class="fw-bold mb-3">🏨 {{ $order->hotelier->hotel_name }}</h6>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <tr>
                        <td class="text-muted" style="width:40%">Delivery Address</td>
                        <td>{{ $order->delivery_address }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Distance</td>
                        <td>{{ $order->distance_km }} km</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Est. Time</td>
                        <td>{{ $order->estimated_delivery_time }} min</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Payment</td>
                        <td>
                            {{ strtoupper($order->payment_method) }} —
                            <span class="badge {{ $order->payment_status==='paid'?'bg-success':'bg-warning text-dark' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Placed At</td>
                        <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card p-3 p-md-4">
            <h6 class="fw-bold mb-3">🍽️ Items Ordered</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead class="table-light">
                        <tr><th>Item</th><th>Qty</th><th class="hide-mobile">Price</th><th>Total</th></tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                        <tr>
                            <td>{{ $item->foodItem->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="hide-mobile">₹{{ number_format($item->unit_price,2) }}</td>
                            <td>₹{{ number_format($item->subtotal,2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr><td colspan="2" class="text-end text-muted">Subtotal</td>
                            <td class="hide-mobile"></td><td>₹{{ number_format($order->total_amount,2) }}</td></tr>
                        <tr><td colspan="2" class="text-end text-muted">Delivery</td>
                            <td class="hide-mobile"></td><td>₹{{ number_format($order->delivery_charge,2) }}</td></tr>
                        <tr class="table-success fw-bold">
                            <td colspan="2" class="text-end">Grand Total</td>
                            <td class="hide-mobile"></td><td>₹{{ number_format($order->grand_total,2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- Review + Actions --}}
    <div class="col-12 col-md-5">
        @if($canReview)
        <div class="card p-3 p-md-4 mb-3">
            <h6 class="fw-bold mb-3">⭐ Leave a Review</h6>
            <form method="POST"  action="{{ route('customer.review.store', ['order_id' => $order->id]) }}">
                @csrf
                <input type="hidden" name="hotelier_id" value="{{ $order->hotelier_id }}">
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Rating</label>
                    <div class="d-flex gap-2 flex-wrap">
                        @for($i=1; $i<=5; $i++)
                        <div class="form-check">
                            <input type="radio" name="rating" value="{{ $i }}"
                                   id="star{{ $i }}" class="form-check-input">
                            <label for="star{{ $i }}" class="form-check-label small">{{ $i }}⭐</label>
                        </div>
                        @endfor
                    </div>
                </div>
                <div class="mb-3">
                    <textarea name="comment" class="form-control form-control-sm" rows="3"
                              placeholder="How was your experience?"></textarea>
                </div>
                <button type="submit" class="btn btn-orange w-100">Submit Review</button>
            </form>
        </div>
        @endif

        <div class="card p-3">
            <a href="{{ route('customer.orders') }}" class="btn btn-outline-secondary w-100 mb-2">
                ← Back to My Orders
            </a>
            <a href="{{ route('customer.browse') }}" class="btn btn-orange w-100">
                Order Again 🍽️
            </a>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
const orderId = {{ $order->id }};
let currentStatus = '{{ $order->status }}';

if (currentStatus !== 'delivered' && currentStatus !== 'cancelled') {
    setInterval(() => {
        fetch('/api/order-status?order_id=' + orderId)
            .then(r => r.json())
            .then(data => {
                if (data.status !== currentStatus) {
                    currentStatus = data.status;
                    const badge = document.getElementById('status-badge');
                    if (badge) { badge.textContent = data.label; badge.className = 'badge badge-' + data.status + ' fs-6'; }
                    showToast('Order status: ' + data.label);
                    updateProgress(data.status);
                    if (data.status === 'delivered' || data.status === 'cancelled') {
                        setTimeout(() => location.reload(), 2000);
                    }
                }
            }).catch(() => {});
    }, 8000);
}

function updateProgress(status) {
    const steps = ['pending','confirmed','preparing','out_for_delivery','delivered'];
    const idx = steps.indexOf(status);
    const pct = idx >= 0 ? (idx / (steps.length-1)) * 100 : 0;
    const bar = document.getElementById('progress-bar');
    if (bar) bar.style.width = pct + '%';
    steps.forEach((s, i) => {
        const el = document.getElementById('step-' + i);
        if (!el) return;
        if (i <= idx) { el.style.background='#ff6b35'; el.style.color='#fff'; el.textContent='✓'; }
    });
}

function showToast(msg) {
    const old = document.getElementById('status-toast');
    if (old) old.remove();
    const t = document.createElement('div');
    t.id = 'status-toast';
    t.style.cssText = 'position:fixed;bottom:20px;right:20px;background:#1e3a5f;color:white;padding:12px 18px;border-radius:10px;font-weight:bold;z-index:9999;box-shadow:0 4px 15px rgba(0,0,0,.3);max-width:280px;';
    t.textContent = '🔔 ' + msg;
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 4000);
}
</script>
@endpush