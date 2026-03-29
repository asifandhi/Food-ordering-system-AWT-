@extends('admin.layouts.app')
@section('title', 'All Reviews')

@section('content')
{{-- Stats --}}
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stat-card text-center">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-primary">{{ $stats['total'] }}</div>
                <div class="text-muted small">Total Reviews</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-center">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-warning">
                    {{ $stats['avg'] }} <i class="bi bi-star-fill fs-5"></i>
                </div>
                <div class="text-muted small">Average Rating</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-center">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-success">{{ $stats['five'] }}</div>
                <div class="text-muted small">5-Star Reviews</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-center">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-danger">{{ $stats['one_two'] }}</div>
                <div class="text-muted small">Low Ratings (1-2★)</div>
            </div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-4 stat-card">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control"
                       placeholder="Search by customer or restaurant..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="rating" class="form-select">
                    <option value="">All Ratings</option>
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                            {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card stat-card">
    <div class="card-body">
        <h6 class="fw-bold mb-3">
            <i class="bi bi-star me-2 text-warning"></i>Reviews ({{ $reviews->total() }})
        </h6>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Restaurant</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($reviews as $review)
                    <tr>
                        <td>{{ $review->id }}</td>
                        <td class="fw-semibold">{{ $review->customer_name }}</td>
                        <td>{{ $review->hotel_name }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : ' text-muted' }} small"></i>
                                @endfor
                                <span class="ms-1 small fw-semibold">{{ $review->rating }}/5</span>
                            </div>
                        </td>
                        <td>
                            @if($review->comment)
                                <span class="text-muted small" style="max-width:250px; display:block; overflow:hidden; white-space:nowrap; text-overflow:ellipsis;">
                                    {{ $review->comment }}
                                </span>
                            @else
                                <span class="text-muted small fst-italic">No comment</span>
                            @endif
                        </td>
                        <td class="text-muted small">
                            {{ \Carbon\Carbon::parse($review->created_at)->format('d M Y') }}
                        </td>
                        <td>
                            <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST"
                                  onsubmit="return confirm('Delete this review?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="bi bi-star fs-2 d-block mb-2"></i>No reviews found
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $reviews->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection