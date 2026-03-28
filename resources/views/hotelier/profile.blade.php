@extends('layouts.hotelier')
@section('title', 'My Profile')

@section('content')

<div class="row g-4">

    {{-- Profile Form --}}
    <div class="col-md-8">
        <div class="card p-4">
            <h6 class="fw-bold mb-4">🏨 Restaurant Profile</h6>

            <form method="POST" action="{{ route('hotelier.profile.update') }}"
                  enctype="multipart/form-data">
                @csrf

                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $err)
                            <div>{{ $err }}</div>
                        @endforeach
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Restaurant Name</label>
                        <input type="text" name="hotel_name"
                               value="{{ old('hotel_name', $profile->hotel_name ?? '') }}"
                               class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Cuisine Type</label>
                        <select name="cuisine_type" class="form-select" required>
                            @foreach(['Indian','Chinese','Pizza','Burger','Biryani','Gujarati','South Indian','Fast Food','Other'] as $c)
                                <option value="{{ $c }}"
                                    {{ old('cuisine_type', $profile->cuisine_type ?? '') == $c ? 'selected' : '' }}>
                                    {{ $c }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" rows="2" class="form-control">{{ old('description', $profile->description ?? '') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Address</label>
                    <textarea name="address" rows="2" class="form-control" required>{{ old('address', $profile->address ?? '') }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">City</label>
                        <input type="text" name="city"
                               value="{{ old('city', $profile->city ?? '') }}"
                               class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Pincode</label>
                        <input type="text" name="pincode"
                               value="{{ old('pincode', $profile->pincode ?? '') }}"
                               class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Latitude (GPS)</label>
                        <input type="text" name="latitude"
                               value="{{ old('latitude', $profile->latitude ?? '') }}"
                               class="form-control" placeholder="e.g. 21.1702">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Longitude (GPS)</label>
                        <input type="text" name="longitude"
                               value="{{ old('longitude', $profile->longitude ?? '') }}"
                               class="form-control" placeholder="e.g. 72.8311">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Opening Time</label>
                        <input type="time" name="opening_time"
                               value="{{ old('opening_time', $profile->opening_time ?? '') }}"
                               class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Closing Time</label>
                        <input type="time" name="closing_time"
                               value="{{ old('closing_time', $profile->closing_time ?? '') }}"
                               class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Avg Delivery Time (min)</label>
                        <input type="number" name="avg_delivery_time"
                               value="{{ old('avg_delivery_time', $profile->avg_delivery_time ?? 30) }}"
                               class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Delivery Radius (km)</label>
                        <input type="number" name="delivery_radius_km" step="0.5"
                               value="{{ old('delivery_radius_km', $profile->delivery_radius_km ?? 10) }}"
                               class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Minimum Order (₹)</label>
                        <input type="number" name="minimum_order" step="0.01"
                               value="{{ old('minimum_order', $profile->minimum_order ?? 0) }}"
                               class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Free Delivery Above (₹)</label>
                        <input type="number" name="free_delivery_above" step="0.01"
                               value="{{ old('free_delivery_above', $profile->free_delivery_above ?? '') }}"
                               class="form-control" placeholder="Optional">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Hotel Logo</label>
                        <input type="file" name="hotel_logo" class="form-control" accept="image/*">
                        @if($profile && $profile->hotel_logo)
                            <img src="{{ asset($profile->hotel_logo) }}" height="50" class="mt-2 rounded">
                        @endif
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Hotel Banner</label>
                        <input type="file" name="hotel_banner" class="form-control" accept="image/*">
                        @if($profile && $profile->hotel_banner)
                            <img src="{{ asset($profile->hotel_banner) }}" height="50" class="mt-2 rounded">
                        @endif
                    </div>
                </div>

                <button type="submit" class="btn btn-primary-custom px-4">
                    <i class="fa fa-save me-2"></i> Save Profile
                </button>
            </form>
        </div>
    </div>

    {{-- Delivery Slabs --}}
    <div class="col-md-4">
        <div class="card p-4">
            <h6 class="fw-bold mb-3">🚚 Delivery Pricing Slabs</h6>

            @if($slabs->isNotEmpty())
                <table class="table table-sm table-bordered mb-3">
                    <thead class="table-light">
                        <tr><th>Min km</th><th>Max km</th><th>Charge</th><th>ETA</th></tr>
                    </thead>
                    <tbody>
                        @foreach($slabs as $slab)
                        <tr>
                            <td>{{ $slab->min_km }}</td>
                            <td>{{ $slab->max_km }}</td>
                            <td>₹{{ $slab->delivery_charge }}</td>
                            <td>{{ $slab->estimated_time_min }}m</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted small">No slabs added yet.</p>
            @endif

            <form method="POST" action="{{ route('hotelier.slabs.store') }}" id="slabForm">
                @csrf
                <div id="slabs-container">
                    <div class="slab-row border rounded p-2 mb-2">
                        <div class="row g-1">
                            <div class="col-6">
                                <input type="number" name="slabs[0][min_km]" placeholder="Min km"
                                       step="0.1" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-6">
                                <input type="number" name="slabs[0][max_km]" placeholder="Max km"
                                       step="0.1" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-6">
                                <input type="number" name="slabs[0][delivery_charge]" placeholder="₹ Charge"
                                       step="0.01" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-6">
                                <input type="number" name="slabs[0][estimated_time_min]" placeholder="Min ETA"
                                       class="form-control form-control-sm" required>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-secondary btn-sm w-100 mb-2"
                        onclick="addSlab()">+ Add Another Slab</button>
                <button type="submit" class="btn btn-primary-custom btn-sm w-100">
                    Save Slabs
                </button>
            </form>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
let slabCount = 1;
function addSlab() {
    const container = document.getElementById('slabs-container');
    const div = document.createElement('div');
    div.className = 'slab-row border rounded p-2 mb-2';
    div.innerHTML = `
        <div class="row g-1">
            <div class="col-6">
                <input type="number" name="slabs[${slabCount}][min_km]" placeholder="Min km"
                       step="0.1" class="form-control form-control-sm" required>
            </div>
            <div class="col-6">
                <input type="number" name="slabs[${slabCount}][max_km]" placeholder="Max km"
                       step="0.1" class="form-control form-control-sm" required>
            </div>
            <div class="col-6">
                <input type="number" name="slabs[${slabCount}][delivery_charge]" placeholder="₹ Charge"
                       step="0.01" class="form-control form-control-sm" required>
            </div>
            <div class="col-6">
                <input type="number" name="slabs[${slabCount}][estimated_time_min]" placeholder="Min ETA"
                       class="form-control form-control-sm" required>
            </div>
        </div>`;
    container.appendChild(div);
    slabCount++;
}
</script>
@endpush