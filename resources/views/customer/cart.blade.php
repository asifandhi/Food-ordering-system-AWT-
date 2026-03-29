@extends('layouts.customer')
@section('title', 'My Cart')

@section('content')

    <div class="row justify-content-center">
        <div class="col-12 col-md-9 col-lg-7">
            <div class="card p-3 p-md-4">
                <h5 class="fw-bold mb-4">🛒 My Cart</h5>

                @if($cartItems->isEmpty())
                    <div class="text-center py-5">
                        <div style="font-size:4rem;">🛒</div>
                        <h5 class="text-muted mt-3">Your cart is empty</h5>
                        <a href="{{ route('customer.browse') }}" class="btn btn-orange mt-2">
                            Browse Restaurants
                        </a>
                    </div>
                @else
                    {{-- Mobile: card style list --}}
                    <div class="d-md-none">
                        @foreach($cartItems as $item)
                            <div class="border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <span class="{{ $item->foodItem->is_veg ? 'text-success' : 'text-danger' }} me-1">
                                            {{ $item->foodItem->is_veg ? '🟢' : '🔴' }}
                                        </span>
                                        <span class="fw-semibold">{{ $item->foodItem->name }}</span>
                                        <div class="text-muted small">{{ $item->foodItem->hotelier->hotel_name }}</div>
                                    </div>
                                    <span class="fw-bold text-success">
                                        ₹{{ number_format($item->quantity * $item->foodItem->price, 2) }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <form method="POST" action="{{ route('customer.cart.update', $item->id) }}"
                                        class="d-flex gap-1">
                                        @csrf
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="20"
                                            class="form-control form-control-sm" style="width:60px;">
                                        <button class="btn btn-sm btn-outline-secondary">↻</button>
                                    </form>
                                    <form method="POST" action="{{ route('customer.cart.remove', $item->id) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Desktop: table style --}}
                    <div class="d-none d-md-block">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                        <tr>
                                            <td>
                                                <span class="{{ $item->foodItem->is_veg ? 'text-success' : 'text-danger' }}">
                                                    {{ $item->foodItem->is_veg ? '🟢' : '🔴' }}
                                                </span>
                                                {{ $item->foodItem->name }}
                                                <div class="small text-muted">{{ $item->foodItem->hotelier->hotel_name }}</div>
                                            </td>
                                            <td>₹{{ number_format($item->foodItem->price, 2) }}</td>
                                            <td>
                                                <form method="POST" action="{{ route('customer.cart.update', $item->id) }}"
                                                    class="d-flex gap-1">
                                                    @csrf
                                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                                        max="20" class="form-control form-control-sm" style="width:65px;">
                                                    <button class="btn btn-sm btn-outline-secondary">↻</button>
                                                </form>
                                            </td>
                                            <td class="fw-bold">₹{{ number_format($item->quantity * $item->foodItem->price, 2) }}
                                            </td>
                                            <td>
                                                <form method="POST" action="{{ route('customer.cart.remove', $item->id) }}">
                                                    @csrf
                                                    <button class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Subtotal</td>
                                        <td class="fw-bold text-success fs-5">₹{{ number_format($subtotal, 2) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    {{-- Subtotal on mobile --}}
                    <div class="d-md-none border-top pt-3 mb-3">
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Subtotal</span>
                            <span class="text-success">₹{{ number_format($subtotal, 2) }}</span>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-3">
                        <form method="POST" action="{{ route('customer.cart.clear') }}">
                            @csrf
                            <button class="btn btn-outline-danger w-100">
                                <i class="bi bi-trash me-1"></i>Clear Cart
                            </button>
                        </form>
                        <a href="{{ route('customer.checkout') }}" class="btn btn-orange px-4 fw-semibold">
                            Checkout →
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection