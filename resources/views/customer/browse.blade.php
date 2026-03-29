@extends('layouts.customer')
@section('title', 'Browse Restaurants')

@section('content')

    {{-- Location + Search Bar --}}
    <div class="card p-3 mb-4">
        <div class="row g-2">
            <div class="col-12 col-md-5">
                <form method="GET" action="{{ route('customer.browse') }}" id="locationForm">
                    <input type="hidden" name="lat" id="lat" value="{{ $customerLat }}">
                    <input type="hidden" name="lng" id="lng" value="{{ $customerLng }}">
                    <div class="d-flex gap-2 align-items-center flex-wrap">
                        <button type="button" class="btn btn-orange btn-sm" onclick="detectLocation()">
                            <i class="bi bi-geo-alt-fill me-1"></i>
                            {{ $customerLat ? 'Update Location' : 'Detect Location' }}
                        </button>
                        @if($customerLat)
                            <span class="text-muted small">📍 Location set</span>
                        @endif
                    </div>
                </form>
            </div>
            <div class="col-12 col-md-7">
                <form method="GET" action="{{ route('customer.browse') }}">
                    <div class="d-flex gap-2 flex-wrap">
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control form-control-sm flex-grow-1" placeholder="Search restaurant or cuisine...">
                        <select name="cuisine" class="form-select form-select-sm" style="min-width:120px; max-width:150px;">
                            <option value="">All Cuisines</option>
                            @foreach($cuisines as $c)
                                <option value="{{ $c }}" {{ request('cuisine') == $c ? 'selected' : '' }}>{{ $c }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-funnel"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Restaurant Cards --}}
    @if($restaurantsWithDistance->isEmpty())
        <div class="text-center py-5">
            <div style="font-size:4rem;">🍽️</div>
            <h5 class="text-muted mt-3">No restaurants found near you.</h5>
            <p class="text-muted small">Try detecting your location or changing filters.</p>
        </div>
    @else
        <div class="row g-3">
            @foreach($restaurantsWithDistance as $restaurant)
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card h-100">
                        @if($restaurant->hotel_banner)
                            <img src="{{ asset($restaurant->hotel_banner) }}" class="card-img-top"
                                style="height:140px; object-fit:cover;">
                        @else
                            <div class="card-img-top d-flex align-items-center justify-content-center"
                                style="height:140px; background:#f4f6f9; font-size:3rem;">🍽️</div>
                        @endif

                        <div class="card-body pb-2">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                @if($restaurant->hotel_logo)
                                    <img src="{{ asset($restaurant->hotel_logo) }}" width="32" height="32" class="rounded-circle"
                                        style="object-fit:cover; flex-shrink:0;">
                                @endif
                                <h6 class="fw-bold mb-0 text-truncate">{{ $restaurant->hotel_name }}</h6>
                            </div>
                            <p class="text-muted small mb-2">{{ $restaurant->cuisine_type }}</p>

                            <div class="d-flex gap-2 flex-wrap small text-muted mb-2">
                                @if($restaurant->distance !== null)
                                    <span><i class="bi bi-geo-alt text-danger"></i> {{ $restaurant->distance }} km</span>
                                @endif
                                <span><i class="bi bi-clock text-warning"></i> {{ $restaurant->avg_delivery_time }} min</span>
                                <span><i class="bi bi-star-fill text-warning"></i> {{ $restaurant->rating ?? '—' }}</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                @if($restaurant->deliveryInfo['deliverable'])
                                    <span class="small fw-bold text-success">
                                        Delivery: ₹{{ $restaurant->deliveryInfo['charge'] }}
                                    </span>
                                @else
                                    <span class="small text-danger">Not deliverable</span>
                                @endif
                                <span class="badge {{ $restaurant->is_open ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $restaurant->is_open ? 'Open' : 'Closed' }}
                                </span>
                            </div>
                        </div>

                        <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                            @if($restaurant->is_open && $restaurant->deliveryInfo['deliverable'])
                                <a href="{{ route('customer.restaurant', $restaurant->id) }}" class="btn btn-orange w-100">View Menu</a>
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
            if (!navigator.geolocation) { alert('Geolocation not supported.'); return; }
            navigator.geolocation.getCurrentPosition(function (pos) {
                document.getElementById('lat').value = pos.coords.latitude;
                document.getElementById('lng').value = pos.coords.longitude;
                document.getElementById('locationForm').submit();
            }, function () {
                alert('Could not detect location. Please allow location access.');
            });
        }
    </script>
@endpush