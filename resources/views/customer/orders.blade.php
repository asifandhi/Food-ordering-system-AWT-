@extends('layouts.customer')
@section('title', 'My Orders')

@section('content')

<div class="card p-4">
    <h5 class="fw-bold mb-4">📋 My Orders</h5>

    @if($orders->isEmpty())
        <div class="text-center py-5">
            <div style="font-size:4rem;">📦</div>
            <h5 class="text-muted mt-3">No orders yet</h5>
            <a href="{{ route('customer.browse') }}" class="btn btn-orange mt-2">
                Order Now
            </a>
        </div>
    @else
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>#Order</th>
                    <th>Restaurant</th>
                    <th>Amount</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td><b>#{{ $order->id }}</b></td>
                    <td>{{ $order->hotelier->hotel_name }}</td>
                    <td>₹{{ number_format($order->grand_total, 2) }}</td>
                    <td>
                        <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ strtoupper($order->payment_method) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $order->status }}">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </td>
                    <td class="small text-muted">
                        {{ $order->created_at->format('d M Y') }}
                    </td>
                    <td class="d-flex gap-1 align-items-center">
                        <a href="{{ route('customer.orders.show', $order->id) }}"
                           class="btn btn-sm btn-orange">Track</a>

                        @if($order->status === 'delivered')
                            @php
                                $alreadyReviewed = \Illuminate\Support\Facades\DB::table('reviews')
                                    ->where('user_id', auth()->id())
                                    ->where('hotelier_id', $order->hotelier_id)
                                    ->exists();
                            @endphp
                            @if(!$alreadyReviewed)
                                <a href="{{ route('customer.review.create', $order->id) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-star me-1"></i>Review
                                </a>
                            @else
                                <button class="btn btn-sm btn-outline-success" disabled>
                                    <i class="bi bi-star-fill me-1"></i>Done
                                </button>
                            @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">{{ $orders->links() }}</div>
    @endif
</div>

@endsection