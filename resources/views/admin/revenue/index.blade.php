@extends('admin.layouts.app')
@section('title', 'Revenue Report')
@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card stat-card text-center">
                <div class="card-body py-4">
                    <i class="bi bi-currency-rupee fs-2 text-success d-block mb-1"></i>
                    <div class="fs-2 fw-bold text-success">₹{{ number_format($totals['total_revenue'], 2) }}</div>
                    <div class="text-muted small">Total Revenue (Delivered)</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card text-center">
                <div class="card-body py-4">
                    <i class="bi bi-bag-check fs-2 text-info d-block mb-1"></i>
                    <div class="fs-2 fw-bold text-info">{{ $totals['total_orders'] }}</div>
                    <div class="text-muted small">Total Orders</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card text-center">
                <div class="card-body py-4">
                    <i class="bi bi-calculator fs-2 text-warning d-block mb-1"></i>
                    <div class="fs-2 fw-bold text-warning">₹{{ number_format($totals['avg_order_value'], 2) }}</div>
                    <div class="text-muted small">Avg Order Value</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4 stat-card">
        <div class="card-body d-flex align-items-center gap-3">
            <span class="fw-semibold">View By:</span>
            <div class="btn-group">
                <a href="?period=daily"
                    class="btn btn-sm {{ $period === 'daily' ? 'btn-primary' : 'btn-outline-primary' }}">Daily (30d)</a>
                <a href="?period=weekly"
                    class="btn btn-sm {{ $period === 'weekly' ? 'btn-primary' : 'btn-outline-primary' }}">Weekly (12w)</a>
                <a href="?period=monthly"
                    class="btn btn-sm {{ $period === 'monthly' ? 'btn-primary' : 'btn-outline-primary' }}">Monthly (12m)</a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card stat-card">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="bi bi-graph-up me-2 text-primary"></i>Revenue Trend</h6>
                    <canvas id="revenueChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="bi bi-pie-chart me-2 text-info"></i>Order Status Breakdown</h6>
                    <canvas id="statusChart"></canvas>
                    <div class="mt-3">
                        @foreach($statusBreakdown as $s)
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small">{{ ucfirst(str_replace('_', ' ', $s->status)) }}</span>
                                <span class="badge bg-secondary">{{ $s->count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card stat-card">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="bi bi-trophy me-2 text-warning"></i>Top Restaurants by Revenue</h6>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Rank</th>
                                    <th>Restaurant</th>
                                    <th>Orders</th>
                                    <th>Revenue</th>
                                    <th>Share</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalRev = $topHoteliers->sum('revenue') ?: 1; @endphp
                                @forelse($topHoteliers as $i => $h)
                                    <tr>
                                        <td>
                                            @if($i === 0) 🥇
                                            @elseif($i === 1) 🥈
                                            @elseif($i === 2) 🥉
                                            @else {{ $i + 1 }}
                                            @endif
                                        </td>
                                        <td class="fw-semibold">{{ $h->hotel_name }}</td>
                                        <td>{{ $h->order_count }}</td>
                                        <td class="fw-bold text-success">₹{{ number_format($h->revenue, 2) }}</td>
                                        <td style="min-width:150px">
                                            @php $pct = round(($h->revenue / $totalRev) * 100, 1); @endphp
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="progress flex-grow-1" style="height:6px;">
                                                    <div class="progress-bar bg-success" style="width:{{ $pct }}%"></div>
                                                </div>
                                                <span class="small text-muted">{{ $pct }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No revenue data yet</td>
                                    </tr>
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
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: @json($revenueData->pluck('label')),
                datasets: [{
                    label: 'Revenue (₹)',
                    data: @json($revenueData->pluck('revenue')),
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13,110,253,0.08)',
                    fill: true, tension: 0.4, pointRadius: 4,
                }]
            },
            options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
        });

        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: @json($statusBreakdown->pluck('status')->map(fn($s) => ucfirst(str_replace('_', ' ', $s)))),
                datasets: [{ data: @json($statusBreakdown->pluck('count')), backgroundColor: ['#ffc107', '#198754', '#dc3545', '#0dcaf0', '#6c757d', '#0d6efd'] }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });
    </script>
@endpush