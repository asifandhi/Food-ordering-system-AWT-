@extends('admin.layouts.app')
@section('title', 'Dashboard')
@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-2 gap-md-3 p-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 p-md-3 flex-shrink-0">
                        <i class="bi bi-people fs-5 fs-md-4 text-primary"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size:0.75rem;">Customers</div>
                        <div class="fs-4 fw-bold">{{ $stats['total_customers'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-2 gap-md-3 p-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-2 p-md-3 flex-shrink-0">
                        <i class="bi bi-shop fs-5 text-success"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size:0.75rem;">Hoteliers</div>
                        <div class="fs-4 fw-bold">{{ $stats['total_hoteliers'] }}</div>
                        @if($stats['pending_hoteliers'] > 0)
                            <span class="badge bg-warning text-dark" style="font-size:0.65rem;">
                                {{ $stats['pending_hoteliers'] }} pending
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-2 gap-md-3 p-3">
                    <div class="rounded-circle bg-info bg-opacity-10 p-2 p-md-3 flex-shrink-0">
                        <i class="bi bi-bag-check fs-5 text-info"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size:0.75rem;">Orders</div>
                        <div class="fs-4 fw-bold">{{ $stats['total_orders'] }}</div>
                        <div class="text-muted" style="font-size:0.7rem;">{{ $stats['today_orders'] }} today</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-2 gap-md-3 p-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-2 p-md-3 flex-shrink-0">
                        <i class="bi bi-currency-rupee fs-5 text-warning"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size:0.75rem;">Revenue</div>
                        <div class="fs-5 fw-bold">₹{{ number_format($stats['total_revenue'], 0) }}</div>
                        <div class="text-muted" style="font-size:0.7rem;">₹{{ number_format($stats['today_revenue'], 0) }}
                            today</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        {{-- Chart --}}
        <div class="col-12 col-md-8">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-bar-chart me-2 text-primary"></i>Revenue — Last 7 Days
                    </h6>
                    <canvas id="revenueChart" height="120"></canvas>
                </div>
            </div>
        </div>
        {{-- Quick Actions --}}
        <div class="col-12 col-md-4">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-lightning me-2 text-warning"></i>Quick Actions
                    </h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.hoteliers.index', ['status' => 'pending']) }}"
                            class="btn btn-outline-warning btn-sm text-start">
                            <i class="bi bi-clock me-2"></i>Pending Approvals
                            @if($stats['pending_hoteliers'] > 0)
                                <span class="badge bg-danger float-end">{{ $stats['pending_hoteliers'] }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-info btn-sm text-start">
                            <i class="bi bi-bag me-2"></i>All Orders
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
    </div>

    {{-- Recent Orders --}}
    <div class="card stat-card">
        <div class="card-body">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-clock-history me-2 text-info"></i>Recent Orders
            </h6>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th class="hide-mobile">Restaurant</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th class="hide-mobile">Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr>
                                <td><span class="badge bg-secondary">#{{ $order->id }}</span></td>
                                <td>
                                    <div class="fw-semibold small">{{ $order->customer_name }}</div>
                                    <div class="d-md-none text-muted" style="font-size:0.72rem;">{{ $order->hotel_name }}</div>
                                </td>
                                <td class="hide-mobile small">{{ $order->hotel_name }}</td>
                                <td class="small fw-semibold">₹{{ number_format($order->grand_total, 0) }}</td>
                                <td>
                                    <span class="badge rounded-pill
                                        @if($order->status === 'delivered') bg-success
                                        @elseif($order->status === 'pending') bg-warning text-dark
                                        @elseif($order->status === 'cancelled') bg-danger
                                        @else bg-info @endif" style="font-size:0.7rem;">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td class="hide-mobile text-muted small">
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('d M, h:i A') }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">No orders yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        new Chart(document.getElementById('revenueChart'), {
            type: 'bar',
            data: {
                labels: @json($revenueChart->pluck('date')),
                datasets: [{
                    label: 'Revenue (₹)',
                    data: @json($revenueChart->pluck('revenue')),
                    backgroundColor: 'rgba(13,110,253,0.7)',
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