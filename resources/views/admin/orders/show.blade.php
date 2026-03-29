@extends('admin.layouts.app')
@section('title', 'Order #' . $order->id)

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Back to Orders
    </a>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="fw-bold mb-0">Order Items</h6>
                    <span class="badge rounded-pill fs-6
                        @if($order->status === 'delivered') bg-success
                        @elseif($order->status === 'pending') bg-warning text-dark
                        @elseif($order->status === 'cancelled') bg-danger
                        @else bg-primary @endif">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </div>
                <table class="table">
                    <thead class="table-light">
                        <tr><th>Item</th><th class="text-center">Qty</th><th class="text-end">Unit Price</th><th class="text-end">Subtotal</th></tr>
                    </thead>
                    <tbody>
                    @foreach($orderItems as $item)
                        <tr>
                            <td>{{ $item->item_name }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">₹{{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-end">₹{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end">Food Total</td>
                            <td class="text-end">₹{{ number_format($order->total_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end">Delivery Charge</td>
                            <td class="text-end">₹{{ number_format($order->delivery_charge, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Grand Total</td>
                            <td class="text-end fw-bold text-primary fs-5">₹{{ number_format($order->grand_total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
                @if($order->delivery_address)
                    <div class="mt-3 p-3 bg-light rounded">
                        <strong><i class="bi bi-geo-alt me-1 text-danger"></i>Delivery Address:</strong><br>
                        {{ $order->delivery_address }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-person me-2 text-primary"></i>Customer</h6>
                <p class="mb-1 fw-semibold">{{ $order->customer_name }}</p>
                <p class="mb-1 text-muted small">{{ $order->customer_email }}</p>
                <p class="mb-0 text-muted small">{{ $order->customer_phone ?? '—' }}</p>
            </div>
        </div>
        <div class="card stat-card mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-shop me-2 text-success"></i>Restaurant</h6>
                <p class="mb-1 fw-semibold">{{ $order->hotel_name }}</p>
                <p class="mb-0 text-muted small">{{ $order->hotelier_city ?? '—' }}</p>
            </div>
        </div>
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-info"></i>Order Info</h6>
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted">Order ID</td><td><strong>#{{ $order->id }}</strong></td></tr>
                    <tr><td class="text-muted">Date</td><td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, h:i A') }}</td></tr>
                    <tr><td class="text-muted">Payment</td><td>{{ strtoupper($order->payment_method) }}</td></tr>
                    <tr><td class="text-muted">Pay Status</td><td>
                        <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </td></tr>
                    <tr><td class="text-muted">Est. Time</td><td>{{ $order->estimated_delivery_time ? $order->estimated_delivery_time.' mins' : '—' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection