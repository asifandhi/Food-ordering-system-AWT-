@extends('admin.layouts.app')
@section('title', 'Dashboard')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<!-- Stat Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                    <i class="bi bi-people fs-4 text-primary"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Customers</div>
                    <div class="fs-3 fw-bold">{{ $stats['total_customers'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-10 p-3">
                    <i class="bi bi-shop fs-4 text-success"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Hoteliers</div>
                    <div class="fs-3 fw-bold">{{ $stats['total_hoteliers'] }}</div>
                    @if($stats['pending_hoteliers'] > 0)
                        <span class="badge bg-warning text-dark small">{{ $stats['pending_hoteliers'] }} pending</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-info bg-opacity-10 p-3">
                    <i class="bi bi-bag-check fs-4 text-info"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Orders</div>
                    <div class="fs-3 fw-bold">{{ $stats['total_orders'] }}</div>
                    <div class="text-muted small">{{ $stats['today_orders'] }} today</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                    <i class="bi bi-currency-rupee fs-4 text-warning"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Revenue</div>
                    <div class="fs-3 fw-bold">₹{{ number_format($stats['total_revenue'], 2) }}</div>
                    <div class="text-muted small">₹{{ number_format($stats['today_revenue'], 2) }} today</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Revenue Chart -->
    <div class="col-md-8">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-bar-chart me-2 text-primary"></i>Revenue — Last 7 Days</h6>
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-lightning me-2 text-warning"></i>Quick Actions</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.hoteliers.index', ['status' => 'pending']) }}" class="btn btn-outline-warning btn-sm text-start">
                        <i class="bi bi-clock me-2"></i>Pending Approvals
                        @if($stats['pending_hoteliers'] > 0)
                            <span class="badge bg-danger float-end">{{ $stats['pending_hoteliers'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-info btn-sm text-start">
                        <i class="bi bi-bag me-2"></i>View All Orders
                    </a>
                    <a href="{{ route('admin.revenue.index') }}" class="btn btn-outline-success btn-sm text-start">
                        <i class="bi bi-graph-up me-2"></i>Revenue Report
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary btn-sm text-start">
                        <i class="bi bi-people me-2"></i>Manage Customers
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="col-12">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-2 text-info"></i>Recent Orders</h6>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#ID</th>
                                <th>Customer</th>
                                <th>Restaurant</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($recentOrders as $order)
                            <tr>
                                <td><span class="badge bg-secondary">#{{ $order->id }}</span></td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ $order->restaurant_name }}</td>
                                <td>₹{{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <span class="badge rounded-pill
                                        @if($order->status === 'delivered' || $order->status === 'completed') bg-success
                                        @elseif($order->status === 'pending') bg-warning text-dark
                                        @elseif($order->status === 'cancelled') bg-danger
                                        @else bg-info @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="text-muted small">{{ \Carbon\Carbon::parse($order->created_at)->format('d M, h:i A') }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-xs btn-outline-primary btn-sm">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted">No orders yet</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const labels = @json($revenueChart->pluck('date'));
const data   = @json($revenueChart->pluck('revenue'));

new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Revenue (₹)',
            data: data,
            backgroundColor: 'rgba(13, 110, 253, 0.7)',
            borderColor: '#0d6efd',
            borderWidth: 1,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>
@endpush