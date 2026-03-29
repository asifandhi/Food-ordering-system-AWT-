@extends('layouts.customer')
@section('title', 'My Orders')

@section('content')

    <div class="card p-3 p-md-4">
        <h5 class="fw-bold mb-4">📋 My Orders</h5>

        @if($orders->isEmpty())
            <div class="text-center py-5">
                <div style="font-size:4rem;">📦</div>
                <h5 class="text-muted mt-3">No orders yet</h5>
                <a href="{{ route('customer.browse') }}" class="btn btn-orange mt-2">Order Now</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Restaurant</th>
                            <th>Amount</th>
                            <th class="hide-mobile">Payment</th>
                            <th>Status</th>
                            <th class="hide-mobile">Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td><b>#{{ $order->id }}</b></td>
                                <td>
                                    <div class="fw-semibold">{{ $order->hotel_name }}</div>
                                    <div class="d-md-none text-muted small">
                                        {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}
                                    </div>
                                </td>
                                <td>₹{{ number_format($order->grand_total, 2) }}</td>
                                <td class="hide-mobile">
                                    <span
                                        class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ strtoupper($order->payment_method) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $order->status }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td class="hide-mobile text-muted small">
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}
                                </td>
                                <td>
                                    <div class="d-flex flex-column flex-sm-row gap-1">
                                        <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-sm btn-orange">
                                            <i class="bi bi-geo-alt"></i>
                                            <span class="d-none d-sm-inline ms-1">Track</span>
                                        </a>
                                        @if($order->status === 'delivered')
                                            @php
                                                $alreadyReviewed = \Illuminate\Support\Facades\DB::table('reviews')
                                                    ->where('user_id', auth()->id())
                                                    ->where('hotelier_id', $order->hotelier_id)
                                                    ->exists();
                                            @endphp
                                            @if(!$alreadyReviewed)
                                                <a href="{{ route('customer.review.create', $order->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="bi bi-star"></i>
                                                    <span class="d-none d-sm-inline ms-1">Review</span>
                                                </a>
                                            @else
                                                <button class="btn btn-sm btn-outline-success" disabled>
                                                    <i class="bi bi-star-fill"></i>
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3 d-flex justify-content-center">{{ $orders->links() }}</div>
        @endif
    </div>

@endsection