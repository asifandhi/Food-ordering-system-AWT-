@extends('layouts.hotelier')
@section('title', 'Dashboard')

@section('content')

{{-- Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card p-3" style="border-color:#2e86c1;">
            <div class="text-muted small">Total Orders</div>
            <div class="fs-2 fw-bold text-primary">{{ $totalOrders }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3" style="border-color:#e74c3c;">
            <div class="text-muted small">Pending Orders</div>
            <div class="fs-2 fw-bold text-danger" id="pending-count">{{ $pendingOrders }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3" style="border-color:#27ae60;">
            <div class="text-muted small">Today's Orders</div>
            <div class="fs-2 fw-bold text-success">{{ $todayOrders }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3" style="border-color:#f39c12;">
            <div class="text-muted small">Today's Revenue</div>
            <div class="fs-2 fw-bold text-warning">₹{{ number_format($todayRevenue, 2) }}</div>
        </div>
    </div>
</div>

{{-- Revenue Summary --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card p-4">
            <h6 class="fw-bold mb-3">💰 Revenue Summary</h6>
            <table class="table table-sm mb-0">
                <tr>
                    <td class="text-muted">Total Revenue (All Time)</td>
                    <td class="fw-bold text-success">₹{{ number_format($totalRevenue, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Today's Revenue</td>
                    <td class="fw-bold">₹{{ number_format($todayRevenue, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Total Delivered Orders</td>
                    <td class="fw-bold">{{ $deliveredOrders }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-4">
            <h6 class="fw-bold mb-3">🏨 Restaurant Status</h6>
            <table class="table table-sm mb-0">
                <tr>
                    <td class="text-muted">Restaurant Name</td>
                    <td class="fw-bold">{{ $profile->hotel_name }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Status</td>
                    <td>
                        <span class="badge {{ $profile->is_open ? 'bg-success' : 'bg-danger' }}">
                            {{ $profile->is_open ? 'Open' : 'Closed' }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="text-muted">Timings</td>
                    <td>{{ $profile->opening_time }} – {{ $profile->closing_time }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Rating</td>
                    <td>⭐ {{ $profile->rating ?? '0.00' }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>

{{-- Recent Orders --}}
<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">🕐 Recent Orders</h6>
        <a href="{{ route('hotelier.orders') }}" class="btn btn-sm btn-primary-custom">View All</a>
    </div>

    @if($recentOrders->isEmpty())
        <p class="text-muted text-center py-3">No orders yet.</p>
    @else
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#Order</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Time</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentOrders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->customer->name }}</td>
                        <td>₹{{ number_format($order->grand_total, 2) }}</td>
                        <td>
                            <span class="badge badge-{{ $order->status }}">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->diffForHumans() }}</td>
                        <td>
                            <a href="{{ route('hotelier.orders.show', $order->id) }}"
                                class="btn btn-sm btn-outline-primary">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@push('scripts')
    <script>
        // Check for new orders every 10 seconds
        const hotelierId = {{ Auth::user()->hotelierProfile->id }};
        let lastOrderId = {{ Auth::user()->hotelierProfile->orders()->latest()->first()?->id ?? 0 }};
        let pendingCount = {{ $pendingOrders }};

        setInterval(() => {
            fetch('/api/check-new-orders?hotelier_id=' + hotelierId + '&last_order_id=' + lastOrderId)
                .then(res => res.json())
                .then(data => {
                    if (data.has_new_order) {
                        lastOrderId = data.latest_order_id;
                        showNewOrderAlert(data);
                        // Update pending count badge
                        const badge = document.getElementById('pending-count');
                        if (badge) badge.textContent = data.pending_count;
                    }
                })
                .catch(err => console.log('Polling error:', err));
        }, 10000);

        function showNewOrderAlert(data) {
            // Remove existing alert if any
            const existing = document.getElementById('new-order-alert');
            if (existing) existing.remove();

            const alert = document.createElement('div');
            alert.id = 'new-order-alert';
            alert.style.cssText = `
                position: fixed; top: 20px; right: 20px;
                background: #ff6b35; color: white;
                padding: 20px 24px; border-radius: 12px;
                font-weight: bold; z-index: 9999;
                box-shadow: 0 6px 20px rgba(0,0,0,0.3);
                min-width: 280px; cursor: pointer;
            `;
            alert.innerHTML = `
                🔔 New Order Received!<br>
                <span style="font-size:0.85rem; font-weight:normal;">
                    Order #${data.latest_order_id} — Click to view orders
                </span><br>
                <span style="font-size:0.75rem; opacity:0.8;">Click to dismiss</span>
            `;
            alert.onclick = () => {
                window.location.href = '/hotelier/orders';
            };
            document.body.appendChild(alert);

            // Auto remove after 8 seconds
            setTimeout(() => {
                if (document.getElementById('new-order-alert')) {
                    alert.remove();
                }
            }, 8000);
        }
    </script>
@endpush