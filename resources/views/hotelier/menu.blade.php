@extends('layouts.hotelier')
@section('title', 'Menu Management')

@section('content')

{{-- On mobile: forms go on top stacked, on desktop: side by side --}}
<div class="row g-3 mb-4">

    {{-- Add Category --}}
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card p-4 h-100">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-folder-plus text-primary me-2"></i>Add Category
            </h6>
            <form method="POST" action="{{ route('hotelier.category.store') }}">
                @csrf
                <div class="mb-2">
                    <input type="text" name="name" class="form-control"
                           placeholder="e.g. Starters, Biryani..." required>
                </div>
                <button type="submit" class="btn btn-primary-custom w-100">
                    <i class="bi bi-plus-circle me-1"></i>Add Category
                </button>
            </form>
        </div>
    </div>

    {{-- Add Food Item --}}
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card p-4 h-100">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-plus-square text-success me-2"></i>Add Food Item
            </h6>
            <form method="POST" action="{{ route('hotelier.item.store') }}"
                  enctype="multipart/form-data">
                @csrf
                <div class="mb-2">
                    <select name="category_id" class="form-select" required>
                        <option value="">— Select Category —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <input type="text" name="name" class="form-control"
                           placeholder="Food item name" required>
                </div>
                <div class="mb-2">
                    <textarea name="description" class="form-control" rows="2"
                              placeholder="Description (optional)"></textarea>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <input type="number" name="price" step="0.01"
                               class="form-control" placeholder="₹ Price" required>
                    </div>
                    <div class="col-6">
                        <select name="is_veg" class="form-select" required>
                            <option value="1">🟢 Veg</option>
                            <option value="0">🔴 Non-Veg</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary-custom w-100">
                    <i class="bi bi-plus-circle me-1"></i>Add Item
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Menu List --}}
@if($categories->isEmpty())
    <div class="card p-5 text-center text-muted">
        <i class="bi bi-folder2-open fs-2 d-block mb-2"></i>
        No categories yet. Add a category first.
    </div>
@else
    @foreach($categories as $category)
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2"
             style="background:#f4f6f9;">
            <span class="fw-bold">
                <i class="bi bi-folder me-1 text-warning"></i>{{ $category->name }}
                <span class="badge bg-secondary ms-1">
                    {{ $category->foodItems->count() }} items
                </span>
            </span>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-secondary"
                        data-bs-toggle="modal"
                        data-bs-target="#editCat{{ $category->id }}">
                    <i class="bi bi-pencil"></i>
                    <span class="d-none d-sm-inline ms-1">Edit</span>
                </button>
                <form method="POST"
                      action="{{ route('hotelier.category.delete', $category->id) }}"
                      onsubmit="return confirm('Delete this category and all its items?')">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash"></i>
                        <span class="d-none d-sm-inline ms-1">Delete</span>
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            @if($category->foodItems->isEmpty())
                <p class="text-muted small p-3 mb-0">No items in this category.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th>Price</th>
                                <th class="hide-mobile">Type</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($category->foodItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($item->image)
                                            <img src="{{ asset($item->image) }}"
                                                 width="38" height="38"
                                                 class="rounded"
                                                 style="object-fit:cover; flex-shrink:0;">
                                        @else
                                            <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                                 style="width:38px;height:38px;flex-shrink:0;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold small">{{ $item->name }}</div>
                                            {{-- Show type on mobile under name --}}
                                            <div class="d-md-none">
                                                <span class="{{ $item->is_veg ? 'text-success' : 'text-danger' }}" style="font-size:0.75rem;">
                                                    {{ $item->is_veg ? '🟢 Veg' : '🔴 Non-Veg' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="fw-semibold">₹{{ number_format($item->price, 2) }}</td>
                                <td class="hide-mobile">
                                    <span class="{{ $item->is_veg ? 'text-success' : 'text-danger' }}">
                                        {{ $item->is_veg ? '🟢 Veg' : '🔴 Non-Veg' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $item->is_available ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $item->is_available ? 'On' : 'Off' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1 flex-wrap">
                                        <form method="POST"
                                              action="{{ route('hotelier.item.toggle', $item->id) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm {{ $item->is_available ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                                    title="{{ $item->is_available ? 'Hide' : 'Show' }}">
                                                <i class="bi bi-{{ $item->is_available ? 'eye-slash' : 'eye' }}"></i>
                                            </button>
                                        </form>
                                        <form method="POST"
                                              action="{{ route('hotelier.item.delete', $item->id) }}"
                                              onsubmit="return confirm('Delete {{ $item->name }}?')">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Edit Category Modal --}}
    <div class="modal fade" id="editCat{{ $category->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST"
                      action="{{ route('hotelier.category.update', $category->id) }}">
                    @csrf
                    <div class="modal-body">
                        <input type="text" name="name" value="{{ $category->name }}"
                               class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary-custom">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endforeach
@endif

@endsection