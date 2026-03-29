@extends('layouts.customer')
@section('title', $restaurant->hotel_name)

@section('content')

    {{-- Restaurant Header --}}
    <div class="card mb-4 overflow-hidden">
        @if($restaurant->hotel_banner)
            <img src="{{ asset($restaurant->hotel_banner) }}" class="w-100" height="200" style="object-fit:cover;">
        @endif
        <div class="card-body">
            <div class="d-flex align-items-center gap-3">
                @if($restaurant->hotel_logo)
                    <img src="{{ asset($restaurant->hotel_logo) }}" width="60" height="60" class="rounded-circle"
                        style="object-fit:cover;">
                @endif
                <div>
                    <h4 class="fw-bold mb-0">{{ $restaurant->hotel_name }}</h4>
                    <p class="text-muted mb-0">{{ $restaurant->cuisine_type }}
                        • ⭐ {{ $restaurant->rating ?? '—' }}
                        • {{ $restaurant->avg_delivery_time }} min
                        @if($distance) • 📍 {{ $distance }} km @endif
                    </p>
                </div>
                <div class="ms-auto text-end">
                    <div class="fw-bold text-success">
                        Delivery: ₹{{ $deliveryInfo['charge'] }}
                    </div>
                    <small class="text-muted">Min order: ₹{{ $restaurant->minimum_order }}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- Menu --}}
        <div class="col-md-8">
            @forelse($restaurant->categories as $category)
                @if($category->foodItems->where('is_available', 1)->isNotEmpty())
                    <div class="mb-4">
                        <h6 class="fw-bold border-bottom pb-2 mb-3">📂 {{ $category->name }}</h6>
                        @foreach($category->foodItems->where('is_available', 1) as $item)
                            <div class="card mb-2 p-3">
                                <div class="d-flex gap-3">
                                    @if($item->image)
                                        <img src="{{ asset($item->image) }}" width="80" height="80" class="rounded"
                                            style="object-fit:cover; flex-shrink:0;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center rounded"
                                            style="width:80px;height:80px;background:#f4f6f9;flex-shrink:0;font-size:2rem;">🍽️</div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <span class="{{ $item->is_veg ? 'text-success' : 'text-danger' }} me-1">
                                                    {{ $item->is_veg ? '🟢' : '🔴' }}
                                                </span>
                                                <span class="fw-semibold">{{ $item->name }}</span>
                                            </div>
                                            <span class="fw-bold">₹{{ number_format($item->price, 2) }}</span>
                                        </div>
                                        @if($item->description)
                                            <p class="text-muted small mb-2">{{ $item->description }}</p>
                                        @endif
                                        <form method="POST" action="{{ route('customer.cart.add') }}">
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                                            <button type="submit" class="btn btn-orange btn-sm">
                                                + Add to Cart
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @empty
                <p class="text-muted">No menu items available.</p>
            @endforelse

            {{-- Reviews --}}
            @if($reviews->isNotEmpty())
                <div class="card p-4 mt-4">
                    <h6 class="fw-bold mb-3">⭐ Customer Reviews</h6>
                    @foreach($reviews as $review)
                        <div class="border-bottom pb-2 mb-2">
                            <div class="d-flex justify-content-between">
                                <span class="fw-semibold">{{ $review->customer->name }}</span>
                                <span class="text-warning">
                                    @for($i = 1; $i <= $review->rating; $i++)⭐@endfor
                                </span>
                            </div>
                            @if($review->comment)
                                <p class="text-muted small mb-0">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Cart Summary --}}
        <div class="col-md-4">
            <div class="card p-4" style="position:sticky; top:20px;">
                <h6 class="fw-bold mb-3">🛒 Your Cart</h6>
                @if($cartItems->isEmpty())
                    <p class="text-muted text-center py-3">Cart is empty.<br>Add items from the menu.</p>
                @else
                    @foreach($cartItems as $cartItem)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <span class="small fw-semibold">{{ $cartItem->foodItem->name }}</span>
                                <div class="small text-muted">x{{ $cartItem->quantity }}</div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="small fw-bold">
                                    ₹{{ number_format($cartItem->quantity * $cartItem->foodItem->price, 2) }}
                                </span>
                                <form method="POST" action="{{ route('customer.cart.remove', $cartItem->id) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger" style="padding:2px 6px;">×</button>
                                </form>
                            </div>
                        </div>
                    @endforeach

                    <hr>
                    <div class="d-flex justify-content-between fw-bold mb-3">
                        <span>Subtotal</span>
                        <span>₹{{ number_format($cartTotal, 2) }}</span>
                    </div>
                    <a href="{{ route('customer.checkout') }}" class="btn btn-orange w-100">
                        Proceed to Checkout →
                    </a>
                    <form method="POST" action="{{ route('customer.cart.clear') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary btn-sm w-100">Clear Cart</button>
                    </form>
                @endif
            </div>
        </div>

    </div>
    {{-- ═══════════════════════════════════════════
    CUSTOMER REVIEWS SECTION
    ═══════════════════════════════════════════ --}}
    <div class="container mt-5 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-star-fill text-warning me-2"></i>
                Customer Reviews
                <span class="text-muted fs-6 fw-normal">({{ $reviews->count() }})</span>
            </h5>
            {{-- Overall rating badge --}}
            @if($restaurant->rating > 0)
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success fs-6 px-3 py-2">
                        ⭐ {{ number_format($restaurant->rating, 1) }}
                    </span>
                    <span class="text-muted small">out of 5</span>
                </div>
            @endif
        </div>

        @forelse($reviews as $review)
            <div class="card border-0 shadow-sm mb-3 rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="fw-semibold">
                                {{ $review->customer->name ?? 'Anonymous' }}
                            </span>
                            <div class="d-flex gap-1 mt-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : ' text-muted' }}"
                                        style="font-size:0.85rem;"></i>
                                @endfor
                                <span class="text-muted small ms-1">{{ $review->rating }}/5</span>
                            </div>
                        </div>
                        <span class="text-muted small">
                            {{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}
                        </span>
                    </div>
                    @if($review->comment)
                        <p class="text-muted small mt-2 mb-0 border-top pt-2">
                            "{{ $review->comment }}"
                        </p>
                    @endif
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body text-center text-muted py-5">
                    <i class="bi bi-star fs-2 d-block mb-2 text-warning"></i>
                    No reviews yet — be the first to share your experience!
                </div>
            </div>
        @endforelse
    </div>

@endsection