@extends('admin.layouts.app')
@section('title', 'Manage Customers')

@section('content')

    <div class="card mb-3 stat-card">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-12 col-sm-7 col-md-8">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Search by name or email..." value="{{ request('search') }}">
                </div>
                <div class="col-6 col-sm-3 col-md-2">
                    <button class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-search me-1"></i>Search
                    </button>
                </div>
                <div class="col-6 col-sm-2 col-md-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-people me-2 text-primary"></i>Customers ({{ $users->total() }})
            </h6>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th class="hide-mobile">Email</th>
                            <th class="hide-mobile">Phone</th>
                            <th>Status</th>
                            <th class="hide-mobile">Registered</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $user->name }}</div>
                                    <div class="d-md-none text-muted small">{{ $user->email }}</div>
                                </td>
                                <td class="hide-mobile small">{{ $user->email }}</td>
                                <td class="hide-mobile small">{{ $user->phone ?? '—' }}</td>
                                <td>
                                    <span class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td class="hide-mobile text-muted small">
                                    {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}
                                </td>
                                <td>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Delete this customer?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>No customers found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection