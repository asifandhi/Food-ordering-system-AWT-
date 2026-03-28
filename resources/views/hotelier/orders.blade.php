@extends('layouts.hotelier')
@section('title', 'Orders')

@section('content')

<div class="card p-4">
    <h6 class="fw-bold mb-4">📋 All Orders</h6>

    @if($orders->isEmpty())
        <p class="text-muted text-center py-4">No orders received yet.</p>
    @else
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>#Order</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td><b>#{{ $order->id }}</b></td>
                    <td>{{ $order->customer->name }}</td>
                    <td>{{ $order->orderItems->count() }} items</td>
                    <td>₹{{ number_format($order->grand_total, 2) }}</td>
                    <td>
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
                    <td class="small text-muted">{{ $order->created_at->diffForHumans() }}</td>
                    <td>
                        <a href="{{ route('hotelier.orders.show', $order->id) }}"
                           class="btn btn-sm btn-primary-custom">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    @endif
</div>

@endsection
@push('scripts')
<script>
    let countdown = 15;

    // Countdown display
    const counter = document.createElement('div');
    counter.id = 'refresh-counter';
    counter.style.cssText = `
        position: fixed; bottom: 20px; right: 20px;
        background: #1e3a5f; color: white;
        padding: 10px 18px; border-radius: 20px;
        font-size: 0.85rem; z-index: 999;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    `;
    document.body.appendChild(counter);

    // Countdown timer
    setInterval(() => {
        countdown--;
        counter.textContent = '🔄 Refreshing in ' + countdown + 's';
        if (countdown <= 0) {
            counter.textContent = '🔄 Refreshing...';
            location.reload();
        }
    }, 1000);

    counter.textContent = '🔄 Refreshing in ' + countdown + 's';
</script>
@endpush    