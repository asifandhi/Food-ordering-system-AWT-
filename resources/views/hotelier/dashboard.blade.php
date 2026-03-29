@extends('layouts.hotelier')
@section('title', 'Dashboard')

@section('content')

{{-- Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3 stat-col">
        <div class="card stat-card p-3 h-100" style="border-color:#2e86c1;">
            <div class="text-muted small mb-1">
                <i class="bi bi-bag me-1"></i>Total Orders
            </div>
            <div class="fs-2 fw-bold text-primary">{{ $totalOrders }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3 stat-col">
        <div class="card stat-card p-3 h-100" style="border-color:#e74c3c;">
            <div class="text-muted small mb-1">
                <i class="bi bi-clock me-1"></i>Pending
            </div>
            <div class="fs-2 fw-bold text-danger" id="pending-count">{{ $pendingOrders }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3 stat-col">
        <div class="card stat-card p-3 h-100" style="border-color:#27ae60;">
            <div class="text-muted small mb-1">
                <i class="bi bi-calendar-check me-1"></i>Today's Orders
            </div>
            <div class="fs-2 fw-bold text-success">{{ $todayOrders }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3 stat-col">
        <div class="card stat-card p-3 h-100" style="border-color:#f39c12;">
            <div class="text-muted small mb-1">
                <i class="bi bi-currency-rupee me-1"></i>Today's Revenue
            </div>
            <div class="fs-2 fw-bold text-warning">₹{{ number_format($todayRevenue, 2) }}</div>
        </div>
    </div>
</div>

{{-- Revenue + Restaurant Status --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card p-4 h-100">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-currency-rupee text-success me-2"></i>Revenue Summary
            </h6>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <tr>
                        <td class="text-muted">Total Revenue (All Time)</td>
                        <td class="fw-bold text-success text-end">
                            ₹{{ number_format($totalRevenue, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Today's Revenue</td>
                        <td class="fw-bold text-end">₹{{ number_format($todayRevenue, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Delivered Orders</td>
                        <td class="fw-bold text-end">{{ $deliveredOrders }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-4 h-100">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-shop text-primary me-2"></i>Restaurant Status
            </h6>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <tr>
                        <td class="text-muted">Restaurant Name</td>
                        <td class="fw-bold text-end">{{ $profile->hotel_name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td class="text-end">
                            <span class="badge {{ $profile->is_open ? 'bg-success' : 'bg-danger' }}">
                                {{ $profile->is_open ? 'Open' : 'Closed' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Timings</td>
                        <td class="fw-bold text-end small">
                            {{ \Carbon\Carbon::parse($profile->opening_time)->format('h:i A') }}
                            –
                            {{ \Carbon\Carbon::parse($profile->closing_time)->format('h:i A') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Rating</td>
                        <td class="fw-bold text-end">
                            ⭐ {{ number_format($profile->rating, 1) }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Recent Orders --}}
<div class="card p-3 p-md-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h6 class="fw-bold mb-0">
            <i class="bi bi-clock-history text-info me-2"></i>Recent Orders
        </h6>
        <a href="{{ route('hotelier.orders') }}" class="btn btn-sm btn-primary-custom">
            View All
        </a>
    </div>

    @if($recentOrders->isEmpty())
        <div class="text-center text-muted py-4">
            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
            No orders yet.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th class="hide-mobile">Amount</th>
                        <th>Status</th>
                        <th class="hide-mobile">Time</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                    <tr>
                        <td><b>#{{ $order->id }}</b></td>
                        <td>
                            <div>{{ $order->customer->name }}</div>
                            {{-- Show amount on mobile under name --}}
                            <div class="d-md-none text-muted small">
                                ₹{{ number_format($order->grand_total, 2) }}
                            </div>
                        </td>
                        <td class="hide-mobile">
                            ₹{{ number_format($order->grand_total, 2) }}
                        </td>
                        <td>
                            <span class="badge badge-{{ $order->status }}">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </td>
                        <td class="hide-mobile text-muted small">
                            {{ $order->created_at->diffForHumans() }}
                        </td>
                        <td>
                            <a href="{{ route('hotelier.orders.show', $order->id) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                                <span class="d-none d-md-inline ms-1">View</span>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@push('scripts')
<script>
// Polling — check new orders every 10s
const hotelierId = {{ Auth::user()->hotelierProfile->id ?? 0 }};
let lastOrderId  = {{ $recentOrders->first()?->id ?? 0 }};

@if(Auth::user()->hotelierProfile)
setInterval(() => {
    fetch('/api/check-new-orders?hotelier_id=' + hotelierId + '&last_order_id=' + lastOrderId)
        .then(res => res.json())
        .then(data => {
            if (data.has_new_order) {
                lastOrderId = data.latest_order_id;
                showAlert(data);
                const badge = document.getElementById('pending-count');
                if (badge) badge.textContent = data.pending_count;
            }
        })
        .catch(() => {});
}, 10000);
@endif

function showAlert(data) {
    const old = document.getElementById('new-order-alert');
    if (old) old.remove();

    const el = document.createElement('div');
    el.id = 'new-order-alert';
    el.style.cssText = `
        position:fixed; bottom:20px; right:20px;
        background:#ff6b35; color:#fff;
        padding:16px 20px; border-radius:12px;
        font-weight:bold; z-index:9999;
        box-shadow:0 6px 20px rgba(0,0,0,0.25);
        max-width:300px; cursor:pointer;
        animation: slideIn 0.3s ease;
    `;
    el.innerHTML = `
        <div class="d-flex align-items-start gap-2">
            <span style="font-size:1.5rem;">🔔</span>
            <div>
                <div>New Order Received!</div>
                <div style="font-size:0.8rem; opacity:0.9; font-weight:normal;">
                    Order #${data.latest_order_id} — tap to view
                </div>
            </div>
        </div>
    `;
    el.onclick = () => window.location.href = '/hotelier/orders';
    document.body.appendChild(el);
    setTimeout(() => { if (el.parentNode) el.remove(); }, 8000);
}
</script>
<style>
@keyframes slideIn {
    from { transform: translateX(100px); opacity: 0; }
    to   { transform: translateX(0);    opacity: 1; }
}
</style>
@endpush

@endsection