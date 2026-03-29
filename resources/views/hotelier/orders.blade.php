@extends('layouts.hotelier')
@section('title', 'Orders')

@section('content')

<div class="card p-3 p-md-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h6 class="fw-bold mb-0">
            <i class="bi bi-bag-check text-primary me-2"></i>All Orders
        </h6>
        <span class="badge bg-primary rounded-pill">{{ $orders->total() }} total</span>
    </div>

    @if($orders->isEmpty())
        <div class="text-center text-muted py-5">
            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
            No orders received yet.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th class="hide-mobile">Items</th>
                        <th>Total</th>
                        <th class="hide-mobile">Payment</th>
                        <th>Status</th>
                        <th class="hide-mobile">Time</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td><b>#{{ $order->id }}</b></td>
                        <td>
                            <div class="fw-semibold">{{ $order->customer->name }}</div>
                            {{-- Show extra info on mobile under name --}}
                            <div class="d-md-none text-muted small">
                                {{ $order->orderItems->count() }} items •
                                {{ $order->created_at->diffForHumans() }}
                            </div>
                            <div class="d-md-none small">
                                <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ strtoupper($order->payment_method) }}
                                </span>
                            </div>
                        </td>
                        <td class="hide-mobile">
                            {{ $order->orderItems->count() }} items
                        </td>
                        <td class="fw-semibold">
                            ₹{{ number_format($order->grand_total, 2) }}
                        </td>
                        <td class="hide-mobile">
                            <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ strtoupper($order->payment_method) }}
                                — {{ ucfirst($order->payment_status) }}
                            </span>
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
                               class="btn btn-sm btn-primary-custom">
                                <i class="bi bi-eye"></i>
                                <span class="d-none d-md-inline ms-1">View</span>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
let countdown = 15;
const counter = document.createElement('div');
counter.style.cssText = `
    position:fixed; bottom:20px; right:20px;
    background:#1e3a5f; color:white;
    padding:8px 16px; border-radius:20px;
    font-size:0.8rem; z-index:999;
    box-shadow:0 2px 10px rgba(0,0,0,0.2);
`;
document.body.appendChild(counter);

setInterval(() => {
    countdown--;
    counter.textContent = '🔄 Refresh in ' + countdown + 's';
    if (countdown <= 0) {
        counter.textContent = '🔄 Refreshing...';
        location.reload();
    }
}, 1000);
counter.textContent = '🔄 Refresh in ' + countdown + 's';
</script>
@endpush

@endsection