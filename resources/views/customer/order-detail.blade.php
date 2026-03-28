@extends('layouts.customer')
@section('title', 'Order #' . $order->id)

@section('content')

<div class="row g-4">

    {{-- Tracking --}}
    <div class="col-md-12">
        <div class="card p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Order #{{ $order->id }} Tracking</h5>
                <span class="badge badge-{{ $order->status }} fs-6" id="status-badge">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
            </div>

            {{-- Progress Bar --}}
            @php
                $steps = ['pending','confirmed','preparing','out_for_delivery','delivered'];
                $currentStep = array_search($order->status, $steps);
                if($order->status === 'cancelled') $currentStep = -1;
            @endphp

            @if($order->status !== 'cancelled')
            <div class="d-flex justify-content-between align-items-center mb-4 position-relative">
                <div class="position-absolute w-100" style="height:4px; background:#e0e0e0; top:20px; z-index:0;"></div>
                <div id="progress-bar" class="position-absolute" style="height:4px;
                     background:#ff6b35; top:20px; z-index:1;
                     width:{{ $currentStep >= 0 ? ($currentStep / (count($steps)-1)) * 100 : 0 }}%;">
                </div>
                @foreach($steps as $i => $step)
                <div class="text-center" style="z-index:2; flex:1;">
                    <div id="step-{{ $i }}" class="rounded-circle mx-auto mb-1 d-flex align-items-center justify-content-center fw-bold"
                         style="width:40px; height:40px;
                                background:{{ $i <= $currentStep ? '#ff6b35' : '#e0e0e0' }};
                                color:{{ $i <= $currentStep ? '#fff' : '#999' }};">
                        {{ $i <= $currentStep ? '✓' : ($i+1) }}
                    </div>
                    <div class="small text-muted" style="font-size:0.7rem;">
                        {{ ucfirst(str_replace('_',' ',$step)) }}
                    </div>
                </div>
                @endforeach
            </div>
            @else
                <div class="alert alert-danger">❌ This order was cancelled.</div>
            @endif
        </div>
    </div>

    {{-- Order Info --}}
    <div class="col-md-7">
        <div class="card p-4 mb-4">
            <h6 class="fw-bold mb-3">🏨 {{ $order->hotelier->hotel_name }}</h6>
            <table class="table table-sm">
                <tr><td class="text-muted">Delivery Address</td>
                    <td>{{ $order->delivery_address }}</td></tr>
                <tr><td class="text-muted">Distance</td>
                    <td>{{ $order->distance_km }} km</td></tr>
                <tr><td class="text-muted">Est. Time</td>
                    <td>{{ $order->estimated_delivery_time }} min</td></tr>
                <tr><td class="text-muted">Payment</td>
                    <td>{{ strtoupper($order->payment_method) }}
                        — <span class="badge {{ $order->payment_status==='paid' ? 'bg-success':'bg-warning text-dark' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </td>
                </tr>
                <tr><td class="text-muted">Placed At</td>
                    <td>{{ $order->created_at->format('d M Y, h:i A') }}</td></tr>
            </table>
        </div>

        <div class="card p-4">
            <h6 class="fw-bold mb-3">🍽️ Items Ordered</h6>
            <table class="table">
                <thead class="table-light">
                    <tr><th>Item</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->foodItem->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>₹{{ number_format($item->unit_price,2) }}</td>
                        <td>₹{{ number_format($item->subtotal,2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr><td colspan="3" class="text-end">Subtotal</td>
                        <td>₹{{ number_format($order->total_amount,2) }}</td></tr>
                    <tr><td colspan="3" class="text-end">Delivery</td>
                        <td>₹{{ number_format($order->delivery_charge,2) }}</td></tr>
                    <tr class="table-success fw-bold">
                        <td colspan="3" class="text-end">Grand Total</td>
                        <td>₹{{ number_format($order->grand_total,2) }}</td></tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Review --}}
    <div class="col-md-5">
        @if($canReview)
        <div class="card p-4">
            <h6 class="fw-bold mb-3">⭐ Leave a Review</h6>
            <form method="POST" action="{{ route('customer.review.store') }}">
                @csrf
                <input type="hidden" name="hotelier_id" value="{{ $order->hotelier_id }}">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Rating</label>
                    <div class="d-flex gap-2">
                        @for($i=1; $i<=5; $i++)
                        <div class="form-check">
                            <input type="radio" name="rating" value="{{ $i }}"
                                   id="star{{ $i }}" class="form-check-input">
                            <label for="star{{ $i }}" class="form-check-label">{{ $i }}⭐</label>
                        </div>
                        @endfor
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Comment (optional)</label>
                    <textarea name="comment" class="form-control" rows="3"
                              placeholder="How was your experience?"></textarea>
                </div>

                <button type="submit" class="btn btn-orange w-100">Submit Review</button>
            </form>
        </div>
        @endif

        <div class="card p-4 mt-3">
            <a href="{{ route('customer.orders') }}" class="btn btn-outline-secondary w-100">
                ← Back to My Orders
            </a>
            <a href="{{ route('customer.browse') }}" class="btn btn-orange w-100 mt-2">
                Order Again 🍽️
            </a>
        </div>
    </div>

</div>

@endsection
@push('scripts')
<script>
    const orderId     = {{ $order->id }};
    let currentStatus = '{{ $order->status }}';

    // Only poll if order is active
    if (currentStatus !== 'delivered' && currentStatus !== 'cancelled') {

        setInterval(() => {
            fetch('/api/order-status?order_id=' + orderId)
                .then(res => res.json())
                .then(data => {
                    if (data.status !== currentStatus) {
                        currentStatus = data.status;

                        // Update badge
                        const badge = document.getElementById('status-badge');
                        if (badge) {
                            badge.textContent = data.label;
                            badge.className   = 'badge badge-' + data.status + ' fs-6';
                        }

                        // Show toast
                        showToast('Order status: ' + data.label);

                        // Update progress bar
                        updateProgress(data.status);

                        // Reload if final status
                        if (data.status === 'delivered' || data.status === 'cancelled') {
                            setTimeout(() => location.reload(), 2000);
                        }
                    }
                })
                .catch(err => console.log('Polling error:', err));
        }, 8000);
    }

    function updateProgress(status) {
        const steps   = ['pending','confirmed','preparing','out_for_delivery','delivered'];
        const current = steps.indexOf(status);
        const pct     = current >= 0 ? (current / (steps.length - 1)) * 100 : 0;

        const bar = document.getElementById('progress-bar');
        if (bar) bar.style.width = pct + '%';

        steps.forEach((step, i) => {
            const circle = document.getElementById('step-' + i);
            if (!circle) return;
            if (i <= current) {
                circle.style.background = '#ff6b35';
                circle.style.color      = '#fff';
                circle.textContent      = '✓';
            }
        });
    }

    function showToast(message) {
        const existing = document.getElementById('status-toast');
        if (existing) existing.remove();

        const toast = document.createElement('div');
        toast.id = 'status-toast';
        toast.style.cssText = `
            position: fixed; bottom: 20px; right: 20px;
            background: #1e3a5f; color: white;
            padding: 14px 20px; border-radius: 10px;
            font-weight: bold; z-index: 9999;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        `;
        toast.textContent = '🔔 ' + message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    }
</script>
@endpush