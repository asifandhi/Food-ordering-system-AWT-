@extends('admin.layouts.app')
@section('title', 'Manage Hoteliers')

@section('content')

    <div class="card mb-3 stat-card">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-12 col-md-5">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Hotel name, owner or email..." value="{{ request('search') }}">
                </div>
                <div class="col-6 col-md-3">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Hoteliers</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
                <div class="col-3 col-md-2">
                    <button class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
                <div class="col-3 col-md-2">
                    <a href="{{ route('admin.hoteliers.index') }}" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-shop me-2 text-success"></i>Hoteliers ({{ $hoteliers->total() }})
            </h6>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Hotel</th>
                            <th class="hide-mobile">Owner</th>
                            <th class="hide-mobile">Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hoteliers as $h)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $h->hotel_name }}</div>
                                    {{-- Show owner + city on mobile under name --}}
                                    <div class="d-md-none text-muted small">{{ $h->owner_name }}</div>
                                    <div class="text-muted small">{{ $h->city ?? '—' }}</div>
                                </td>
                                <td class="hide-mobile">
                                    <div>{{ $h->owner_name }}</div>
                                    <div class="text-muted small">{{ $h->city ?? '—' }}</div>
                                </td>
                                <td class="hide-mobile small text-muted">{{ $h->owner_email }}</td>
                                <td>
                                    @if($h->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($h->status === 'suspended')
                                        <span class="badge bg-danger">Suspended</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1 flex-wrap">
                                        @if($h->status !== 'approved')
                                            <form action="{{ route('admin.hoteliers.approve', $h->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-success" title="Approve">
                                                    <i class="bi bi-check-lg"></i>
                                                    <span class="d-none d-lg-inline ms-1">Approve</span>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.hoteliers.reject', $h->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-warning text-dark" title="Suspend">
                                                    <i class="bi bi-pause-circle"></i>
                                                    <span class="d-none d-lg-inline ms-1">Suspend</span>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.hoteliers.destroy', $h->id) }}" method="POST"
                                            onsubmit="return confirm('Delete {{ $h->hotel_name }}?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>No hoteliers found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $hoteliers->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection