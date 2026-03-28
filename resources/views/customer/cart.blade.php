@extends('layouts.customer')
@section('title', 'My Cart')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card p-4">
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
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Quantity</th>
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
                                <div class="small text-muted">
                                    {{ $item->foodItem->hotelier->hotel_name }}
                                </div>
                            </td>
                            <td>₹{{ number_format($item->foodItem->price, 2) }}</td>
                            <td>
                                <form method="POST"
                                      action="{{ route('customer.cart.update', $item->id) }}"
                                      class="d-flex gap-1">
                                    @csrf
                                    <input type="number" name="quantity"
                                           value="{{ $item->quantity }}"
                                           min="1" max="20"
                                           class="form-control form-control-sm"
                                           style="width:65px;">
                                    <button class="btn btn-sm btn-outline-secondary">↻</button>
                                </form>
                            </td>
                            <td class="fw-bold">
                                ₹{{ number_format($item->quantity * $item->foodItem->price, 2) }}
                            </td>
                            <td>
                                <form method="POST"
                                      action="{{ route('customer.cart.remove', $item->id) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Subtotal</td>
                            <td class="fw-bold text-success fs-5">
                                ₹{{ number_format($subtotal, 2) }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

                <div class="d-flex justify-content-between mt-3">
                    <form method="POST" action="{{ route('customer.cart.clear') }}">
                        @csrf
                        <button class="btn btn-outline-danger">Clear Cart</button>
                    </form>
                    <a href="{{ route('customer.checkout') }}" class="btn btn-orange px-5">
                        Proceed to Checkout →
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection