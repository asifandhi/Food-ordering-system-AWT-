@extends('layouts.hotelier')
@section('title', 'Menu Management')

@section('content')

<div class="row">
    <div class="col-md-4">

        {{-- Add Category --}}
        <div class="card p-4 mb-4">
            <h6 class="fw-bold mb-3">📂 Add Category</h6>
            <form method="POST" action="{{ route('hotelier.category.store') }}">
                @csrf
                <div class="mb-2">
                    <input type="text" name="name" class="form-control"
                           placeholder="e.g. Starters, Biryani..." required>
                </div>
                <button type="submit" class="btn btn-primary-custom w-100">Add Category</button>
            </form>
        </div>

        {{-- Add Food Item --}}
        <div class="card p-4">
            <h6 class="fw-bold mb-3">🍽️ Add Food Item</h6>
            <form method="POST" action="{{ route('hotelier.item.store') }}"
                  enctype="multipart/form-data">
                @csrf

                <div class="mb-2">
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Select Category --</option>
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
                <div class="mb-2">
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary-custom w-100">Add Item</button>
            </form>
        </div>

    </div>

    {{-- Menu List --}}
    <div class="col-md-8">
        @if($categories->isEmpty())
            <div class="card p-5 text-center text-muted">
                No categories yet. Add a category first.
            </div>
        @else
            @foreach($categories as $category)
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center"
                     style="background:#f4f6f9;">
                    <span class="fw-bold">📂 {{ $category->name }}</span>
                    <div class="d-flex gap-2">
                        {{-- Edit Category --}}
                        <button class="btn btn-sm btn-outline-secondary"
                                data-bs-toggle="modal"
                                data-bs-target="#editCat{{ $category->id }}">
                            Edit
                        </button>
                        {{-- Delete Category --}}
                        <form method="POST"
                              action="{{ route('hotelier.category.delete', $category->id) }}"
                              onsubmit="return confirm('Delete this category and all its items?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($category->foodItems->isEmpty())
                        <p class="text-muted small p-3 mb-0">No items in this category.</p>
                    @else
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($category->foodItems as $item)
                                <tr>
                                    <td>
                                        @if($item->image)
                                            <img src="{{ asset($item->image) }}"
                                                 width="35" height="35"
                                                 class="rounded me-2"
                                                 style="object-fit:cover;">
                                        @endif
                                        {{ $item->name }}
                                    </td>
                                    <td>₹{{ number_format($item->price, 2) }}</td>
                                    <td>
                                        <span class="{{ $item->is_veg ? 'text-success' : 'text-danger' }}">
                                            {{ $item->is_veg ? '🟢 Veg' : '🔴 Non-Veg' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $item->is_available ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $item->is_available ? 'Available' : 'Unavailable' }}
                                        </span>
                                    </td>
                                    <td>
                                        {{-- Toggle --}}
                                        <form method="POST"
                                              action="{{ route('hotelier.item.toggle', $item->id) }}"
                                              class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm {{ $item->is_available ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                                {{ $item->is_available ? 'Hide' : 'Show' }}
                                            </button>
                                        </form>
                                        {{-- Delete --}}
                                        <form method="POST"
                                              action="{{ route('hotelier.item.delete', $item->id) }}"
                                              class="d-inline"
                                              onsubmit="return confirm('Delete this item?')">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger">Del</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            {{-- Edit Category Modal --}}
            <div class="modal fade" id="editCat{{ $category->id }}" tabindex="-1">
                <div class="modal-dialog">
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
                                <button type="submit" class="btn btn-primary-custom">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @endforeach
        @endif
    </div>
</div>

@endsection