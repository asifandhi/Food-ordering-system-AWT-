@extends('layouts.customer')
@section('title', 'Browse Restaurants')

@section('content')

{{-- Location Bar --}}
<div class="card p-3 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <form method="GET" action="{{ route('customer.browse') }}" class="d-flex gap-2" id="locationForm">
                <input type="hidden" name="lat" id="lat" value="{{ $customerLat }}">
                <input type="hidden" name="lng" id="lng" value="{{ $customerLng }}">
                <button type="button" class="btn btn-orange btn-sm" onclick="detectLocation()">
                    <i class="fa fa-map-marker-alt me-1"></i>
                    {{ $customerLat ? 'Update Location' : 'Detect My Location' }}
                </button>
                @if($customerLat)
                    <span class="text-muted small align-self-center">
                        📍 Location set ({{ round($customerLat,4) }}, {{ round($customerLng,4) }})
                    </span>
                @endif
            </form>
        </div>
        <div class="col-md-6">
            <form method="GET" action="{{ route('customer.browse') }}" class="d-flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="form-control form-control-sm" placeholder="Search restaurant or cuisine...">
                <select name="cuisine" class="form-select form-select-sm" style="width:150px;">
                    <option value="">All Cuisines</option>
                    @foreach($cuisines as $c)
                        <option value="{{ $c }}" {{ request('cuisine') == $c ? 'selected':'' }}>{{ $c }}</option>
                    @endforeach
                </select>
                <button class="btn btn-outline-secondary btn-sm">Filter</button>
            </form>
        </div>
    </div>
</div>

{{-- Restaurant Cards --}}
@if($restaurantsWithDistance->isEmpty())
    <div class="text-center py-5">
        <div style="font-size:4rem;">🍽️</div>
        <h4 class="text-muted mt-3">No restaurants found near you.</h4>
        <p class="text-muted">Try detecting your location or changing filters.</p>
    </div>
@else
    <div class="row g-3">
        @foreach($restaurantsWithDistance as $restaurant)
        <div class="col-md-4">
            <div class="card h-100">
                {{-- Banner --}}
                @if($restaurant->hotel_banner)
                    <img src="{{ asset($restaurant->hotel_banner) }}"
                         class="card-img-top" height="140" style="object-fit:cover;">
                @else
                    <div class="card-img-top d-flex align-items-center justify-content-center"
                         style="height:140px; background:#f4f6f9; font-size:3rem;">🍽️</div>
                @endif

                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        @if($restaurant->hotel_logo)
                            <img src="{{ asset($restaurant->hotel_logo) }}"
                                 width="36" height="36" class="rounded-circle" style="object-fit:cover;">
                        @endif
                        <h6 class="fw-bold mb-0">{{ $restaurant->hotel_name }}</h6>
                    </div>

                    <p class="text-muted small mb-2">{{ $restaurant->cuisine_type }}</p>

                    <div class="d-flex gap-3 small text-muted mb-3">
                        @if($restaurant->distance !== null)
                            <span><i class="fa fa-map-marker-alt text-danger"></i>
                                {{ $restaurant->distance }} km</span>
                        @endif
                        <span><i class="fa fa-clock text-warning"></i>
                            {{ $restaurant->avg_delivery_time }} min</span>
                        <span><i class="fa fa-star text-warning"></i>
                            {{ $restaurant->rating ?? '—' }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            @if($restaurant->deliveryInfo['deliverable'])
                                <span class="small fw-bold text-success">
                                    Delivery: ₹{{ $restaurant->deliveryInfo['charge'] }}
                                </span>
                            @else
                                <span class="small text-danger">Not deliverable to your location</span>
                            @endif
                        </div>
                        <span class="badge {{ $restaurant->is_open ? 'bg-success' : 'bg-secondary' }}">
                            {{ $restaurant->is_open ? 'Open' : 'Closed' }}
                        </span>
                    </div>
                </div>

                <div class="card-footer bg-transparent border-0 pb-3">
                    @if($restaurant->is_open && $restaurant->deliveryInfo['deliverable'])
                        <a href="{{ route('customer.restaurant', $restaurant->id) }}"
                           class="btn btn-orange w-100">View Menu</a>
                    @else
                        <button class="btn btn-secondary w-100" disabled>Unavailable</button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif

@endsection

@push('scripts')
<script>
function detectLocation() {
    if (!navigator.geolocation) {
        alert('Geolocation is not supported by your browser.');
        return;
    }
    navigator.geolocation.getCurrentPosition(function(position) {
        document.getElementById('lat').value = position.coords.latitude;
        document.getElementById('lng').value = position.coords.longitude;
        document.getElementById('locationForm').submit();
    }, function() {
        alert('Could not detect location. Please allow location access.');
    });
}
</script>
@endpush