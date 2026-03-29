@extends('layouts.app') {{-- ⚠️ CHANGE THIS to match your customer layout --}}

@section('content')
<div class="container py-5" style="max-width:680px;">
    <div class="card border-0 shadow rounded-4">
        <div class="card-body p-4 p-md-5">

            <a href="{{ route('customer.orders') }}" class="text-muted small d-inline-flex align-items-center gap-1 mb-4">
                <i class="bi bi-arrow-left"></i> Back to Orders
            </a>

            <h5 class="fw-bold mb-1">
                <i class="bi bi-star-fill text-warning me-2"></i>Write a Review
            </h5>
            <p class="text-muted mb-4">
                Order <strong>#{{ $order->id }}</strong> from
                <strong>{{ $order->hotel_name }}</strong>
            </p>

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $e)
                        <div>{{ $e }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('customer.review.store', $order->id) }}" method="POST">
                @csrf

                {{-- Star Rating --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        Overall Rating <span class="text-danger">*</span>
                    </label>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star fs-1 text-muted star-icon"
                               data-value="{{ $i }}"
                               style="cursor:pointer; transition:color 0.1s;"></i>
                        @endfor
                        <span id="ratingText" class="text-muted ms-2 small">Select a rating</span>
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating') }}">
                    @error('rating')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Optional: specific food item --}}
                @if($orderItems->count() > 0)
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Reviewing a specific item? <span class="text-muted fw-normal">(optional)</span>
                    </label>
                    <select name="item_id" class="form-select">
                        <option value="">— Overall restaurant review —</option>
                        @foreach($orderItems as $item)
                            <option value="{{ $item->id }}"
                                {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->name }} × {{ $item->quantity }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Comment --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        Your Comments <span class="text-muted fw-normal">(optional)</span>
                    </label>
                    <textarea name="comment" class="form-control" rows="4"
                        placeholder="Share your experience — food quality, delivery speed, packaging..."
                        maxlength="1000" id="commentArea">{{ old('comment') }}</textarea>
                    <div class="d-flex justify-content-end mt-1">
                        <span class="text-muted small"><span id="charCount">0</span>/1000</span>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning fw-semibold px-4">
                        <i class="bi bi-star me-1"></i>Submit Review
                    </button>
                    <a href="{{ route('customer.orders') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.star-icon:hover { color: #ffc107 !important; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const stars      = document.querySelectorAll('.star-icon');
    const input      = document.getElementById('ratingInput');
    const text       = document.getElementById('ratingText');
    const textarea   = document.getElementById('commentArea');
    const charCount  = document.getElementById('charCount');
    const labels     = ['', 'Poor 😞', 'Fair 😐', 'Good 🙂', 'Very Good 😊', 'Excellent 🤩'];

    let selected = parseInt(input.value) || 0;
    if (selected) paint(selected);

    stars.forEach(star => {
        star.addEventListener('mouseenter', () => paint(star.dataset.value));
        star.addEventListener('mouseleave', () => paint(selected));
        star.addEventListener('click', () => {
            selected = parseInt(star.dataset.value);
            input.value = selected;
            paint(selected);
        });
    });

    function paint(val) {
        stars.forEach(s => {
            const filled = s.dataset.value <= val;
            s.classList.toggle('bi-star-fill', filled);
            s.classList.toggle('bi-star',      !filled);
            s.style.color = filled ? '#ffc107' : '';
        });
        text.textContent  = val ? labels[val] : 'Select a rating';
        text.style.color  = val ? '#ffc107'   : '';
        text.style.fontWeight = val ? '600' : '';
    }

    // Char counter
    charCount.textContent = textarea.value.length;
    textarea.addEventListener('input', () => {
        charCount.textContent = textarea.value.length;
    });
});
</script>
@endsection