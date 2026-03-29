@extends('admin.layouts.app')
@section('title', 'Manage Hoteliers')

@section('content')
<div class="card mb-4 stat-card">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Search by hotel name, owner name or email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Hoteliers</option>
                    <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
                    <option value="approved"  {{ request('status') === 'approved'  ? 'selected' : '' }}>Approved</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.hoteliers.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card stat-card">
    <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="bi bi-shop me-2 text-success"></i>Hoteliers ({{ $hoteliers->total() }})</h6>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th><th>Hotel Name</th><th>Owner</th><th>Email</th>
                        <th>City</th><th>Status</th><th>Registered</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($hoteliers as $h)
                    <tr>
                        <td>{{ $h->id }}</td>
                        <td class="fw-semibold">{{ $h->hotel_name }}</td>
                        <td>{{ $h->owner_name }}</td>
                        <td>{{ $h->owner_email }}</td>
                        <td class="text-muted small">{{ $h->city ?? '—' }}</td>
                        <td>
                            @if($h->status === 'approved')
                                <span class="badge rounded-pill bg-success">Approved</span>
                            @elseif($h->status === 'suspended')
                                <span class="badge rounded-pill bg-danger">Suspended</span>
                            @else
                                <span class="badge rounded-pill bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ \Carbon\Carbon::parse($h->created_at)->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                @if($h->status !== 'approved')
                                    <form action="{{ route('admin.hoteliers.approve', $h->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-success" title="Approve">
                                            <i class="bi bi-check-lg"></i> Approve
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.hoteliers.reject', $h->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-warning text-dark" title="Suspend">
                                            <i class="bi bi-pause-circle"></i> Suspend
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.hoteliers.destroy', $h->id) }}" method="POST"
                                      onsubmit="return confirm('Delete {{ $h->hotel_name }}? This cannot be undone.')">
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
                        <td colspan="8" class="text-center text-muted py-5">
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