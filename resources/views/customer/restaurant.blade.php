@extends('layouts.customer')
@section('title', $restaurant->hotel_name)

@section('content')

{{-- Restaurant Header --}}
<div class="card mb-4 overflow-hidden">
    @if($restaurant->hotel_banner)
        <img src="{{ asset($restaurant->hotel_banner) }}" class="w-100"
             style="height:160px; object-fit:cover;">
    @endif
    <div class="card-body">
        <div class="d-flex align-items-start gap-3 flex-wrap">
            @if($restaurant->hotel_logo)
                <img src="{{ asset($restaurant->hotel_logo) }}" width="55" height="55"
                     class="rounded-circle flex-shrink-0" style="object-fit:cover;">
            @endif
            <div class="flex-grow-1">
                <h5 class="fw-bold mb-0">{{ $restaurant->hotel_name }}</h5>
                <p class="text-muted small mb-1">
                    {{ $restaurant->cuisine_type }} • ⭐ {{ $restaurant->rating ?? '—' }}
                    • {{ $restaurant->avg_delivery_time }} min
                    @if($distance) • 📍 {{ $distance }} km @endif
                </p>
                <span class="badge {{ $restaurant->is_open ? 'bg-success':'bg-secondary' }}">
                    {{ $restaurant->is_open ? 'Open':'Closed' }}
                </span>
            </div>
            <div class="text-end flex-shrink-0">
                <div class="fw-bold text-success small">
                    Delivery: ₹{{ $deliveryInfo['charge'] }}
                </div>
                <div class="text-muted small">Min: ₹{{ $restaurant->minimum_order }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- Menu --}}
    <div class="col-12 col-md-8">
        @forelse($restaurant->categories as $category)
            @if($category->foodItems->where('is_available', 1)->isNotEmpty())
            <div class="mb-4">
                <h6 class="fw-bold border-bottom pb-2 mb-3">
                    📂 {{ $category->name }}
                </h6>
                @foreach($category->foodItems->where('is_available', 1) as $item)
                <div class="card mb-2 p-3">
                    <div class="d-flex gap-3">
                        @if($item->image)
                            <img src="{{ asset($item->image) }}"
                                 class="rounded flex-shrink-0"
                                 style="width:70px;height:70px;object-fit:cover;">
                        @else
                            <div class="rounded flex-shrink-0 d-flex align-items-center justify-content-center"
                                 style="width:70px;height:70px;background:#f4f6f9;font-size:1.8rem;">🍽️</div>
                        @endif
                        <div class="flex-grow-1 min-width-0">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div>
                                    <span class="{{ $item->is_veg?'text-success':'text-danger' }} me-1">
                                        {{ $item->is_veg?'🟢':'🔴' }}
                                    </span>
                                    <span class="fw-semibold">{{ $item->name }}</span>
                                </div>
                                <span class="fw-bold text-nowrap">₹{{ number_format($item->price,2) }}</span>
                            </div>
                            @if($item->description)
                                <p class="text-muted small mb-2 mt-1">{{ $item->description }}</p>
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
    </div>

    {{-- Cart Sidebar --}}
    <div class="col-12 col-md-4">
        <div class="card p-3" style="position:sticky; top:70px;">
            <h6 class="fw-bold mb-3">🛒 Your Cart</h6>
            @if($cartItems->isEmpty())
                <p class="text-muted text-center small py-3">
                    Cart is empty.<br>Add items from the menu.
                </p>
            @else
                @foreach($cartItems as $ci)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="small">
                        <div class="fw-semibold">{{ $ci->foodItem->name }}</div>
                        <div class="text-muted">× {{ $ci->quantity }}</div>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <span class="small fw-bold">
                            ₹{{ number_format($ci->quantity * $ci->foodItem->price,2) }}
                        </span>
                        <form method="POST" action="{{ route('customer.cart.remove', $ci->id) }}">
                            @csrf
                            <button class="btn btn-sm btn-outline-danger" style="padding:1px 6px;">×</button>
                        </form>
                    </div>
                </div>
                @endforeach
                <hr>
                <div class="d-flex justify-content-between fw-bold mb-3 small">
                    <span>Subtotal</span>
                    <span>₹{{ number_format($cartTotal,2) }}</span>
                </div>
                <a href="{{ route('customer.checkout') }}" class="btn btn-orange w-100 mb-2">
                    Checkout →
                </a>
                <form method="POST" action="{{ route('customer.cart.clear') }}">
                    @csrf
                    <button class="btn btn-outline-secondary btn-sm w-100">Clear Cart</button>
                </form>
            @endif
        </div>
    </div>

</div>

{{-- Reviews --}}
<div class="mt-4 mb-3">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h5 class="fw-bold mb-0">
            <i class="bi bi-star-fill text-warning me-2"></i>Reviews
            <span class="text-muted fs-6 fw-normal">({{ $reviews->count() }})</span>
        </h5>
        @if($restaurant->rating > 0)
        <span class="badge bg-success px-3 py-2">
            ⭐ {{ number_format($restaurant->rating,1) }} / 5
        </span>
        @endif
    </div>

    @forelse($reviews as $review)
    <div class="card border-0 shadow-sm mb-3 rounded-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div>
                    <span class="fw-semibold">{{ $review->customer->name ?? 'Anonymous' }}</span>
                    <div class="d-flex gap-1 mt-1">
                        @for($i=1;$i<=5;$i++)
                            <i class="bi bi-star{{ $i<=$review->rating?'-fill text-warning':' text-muted' }}"
                               style="font-size:0.8rem;"></i>
                        @endfor
                    </div>
                </div>
                <span class="text-muted small">
                    {{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}
                </span>
            </div>
            @if($review->comment)
                <p class="text-muted small mt-2 mb-0 border-top pt-2">"{{ $review->comment }}"</p>
            @endif
        </div>
    </div>
    @empty
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body text-center text-muted py-4">
            <i class="bi bi-star fs-3 d-block mb-2 text-warning"></i>
            No reviews yet — be the first!
        </div>
    </div>
    @endforelse
</div>

@endsection