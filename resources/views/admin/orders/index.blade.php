@extends('admin.layouts.app')
@section('title', 'All Orders')

@section('content')

    <div class="card mb-3 stat-card">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-12 col-md-5">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Customer, restaurant, order ID..." value="{{ request('search') }}">
                </div>
                <div class="col-6 col-md-3">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        @foreach(['pending', 'confirmed', 'preparing', 'out_for_delivery', 'delivered', 'cancelled'] as $s)
                            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $s)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3 col-md-2">
                    <button class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
                <div class="col-3 col-md-2">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-bag-check me-2 text-info"></i>Orders ({{ $orders->total() }})
            </h6>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th class="hide-mobile">Restaurant</th>
                            <th>Amount</th>
                            <th class="hide-mobile">Payment</th>
                            <th>Status</th>
                            <th class="hide-mobile">Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td><span class="badge bg-secondary">#{{ $order->id }}</span></td>
                                <td>
                                    <div class="fw-semibold small">{{ $order->customer_name }}</div>
                                    <div class="d-md-none text-muted" style="font-size:0.72rem;">
                                        {{ $order->hotel_name }}
                                    </div>
                                </td>
                                <td class="hide-mobile small">{{ $order->hotel_name }}</td>
                                <td class="fw-semibold small">₹{{ number_format($order->grand_total, 2) }}</td>
                                <td class="hide-mobile">
                                    <span
                                        class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : ($order->payment_status === 'failed' ? 'bg-danger' : 'bg-secondary') }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill
                                        @if($order->status === 'delivered') bg-success
                                        @elseif($order->status === 'pending') bg-warning text-dark
                                        @elseif($order->status === 'cancelled') bg-danger
                                        @elseif($order->status === 'preparing') bg-info
                                        @else bg-primary @endif" style="font-size:0.7rem;">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td class="hide-mobile text-muted small">
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}
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
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>No orders found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $orders->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection