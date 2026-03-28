@extends('layouts.hotelier')
@section('title', 'Order #{{ $order->id }}')

@section('content')

<div class="row g-4">

    {{-- Order Info --}}
    <div class="col-md-7">
        <div class="card p-4 mb-4">
            <h6 class="fw-bold mb-3">📦 Order #{{ $order->id }} Details</h6>
            <table class="table table-sm">
                <tr><td class="text-muted">Customer</td><td>{{ $order->customer->name }}</td></tr>
                <tr><td class="text-muted">Phone</td><td>{{ $order->customer->phone }}</td></tr>
                <tr><td class="text-muted">Delivery Address</td><td>{{ $order->delivery_address }}</td></tr>
                <tr><td class="text-muted">Distance</td><td>{{ $order->distance_km }} km</td></tr>
                <tr><td class="text-muted">ETA</td><td>{{ $order->estimated_delivery_time }} minutes</td></tr>
                <tr><td class="text-muted">Payment</td>
                    <td>{{ strtoupper($order->payment_method) }}
                        — <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </td>
                </tr>
                <tr><td class="text-muted">Placed At</td><td>{{ $order->created_at->format('d M Y, h:i A') }}</td></tr>
            </table>
        </div>

        {{-- Items --}}
        <div class="card p-4">
            <h6 class="fw-bold mb-3">🍽️ Ordered Items</h6>
            <table class="table">
                <thead class="table-light">
                    <tr><th>Item</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->foodItem->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>₹{{ number_format($item->unit_price, 2) }}</td>
                        <td>₹{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Subtotal</td>
                        <td>₹{{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Delivery Charge</td>
                        <td>₹{{ number_format($order->delivery_charge, 2) }}</td>
                    </tr>
                    <tr class="table-success">
                        <td colspan="3" class="text-end fw-bold">Grand Total</td>
                        <td class="fw-bold">₹{{ number_format($order->grand_total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Update Status --}}
    <div class="col-md-5">
        <div class="card p-4">
            <h6 class="fw-bold mb-3">🔄 Update Order Status</h6>

            <div class="mb-4">
                <p class="text-muted small mb-1">Current Status:</p>
                <span class="badge badge-{{ $order->status }} fs-6">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
            </div>

            @if(!in_array($order->status, ['delivered', 'cancelled']))
                <form method="POST"
                      action="{{ route('hotelier.orders.status', $order->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Change Status To:</label>
                        <select name="status" class="form-select">
                            <option value="confirmed"        {{ $order->status == 'confirmed'        ? 'selected' : '' }}>✅ Confirmed</option>
                            <option value="preparing"        {{ $order->status == 'preparing'        ? 'selected' : '' }}>👨‍🍳 Preparing</option>
                            <option value="out_for_delivery" {{ $order->status == 'out_for_delivery' ? 'selected' : '' }}>🛵 Out for Delivery</option>
                            <option value="delivered"        {{ $order->status == 'delivered'        ? 'selected' : '' }}>✅ Delivered</option>
                            <option value="cancelled"        {{ $order->status == 'cancelled'        ? 'selected' : '' }}>❌ Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary-custom w-100">
                        Update Status
                    </button>
                </form>
            @else
                <div class="alert alert-info">
                    This order is {{ $order->status }}. No further status changes allowed.
                </div>
            @endif

            <a href="{{ route('hotelier.orders') }}"
               class="btn btn-outline-secondary w-100 mt-3">← Back to Orders</a>
        </div>
    </div>

</div>

@endsection