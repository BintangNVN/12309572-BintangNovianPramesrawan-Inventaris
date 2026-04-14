@extends('layouts.app')

@section('title', 'Admin Item')
@section('pageTitle', 'Welcome Back, ' . auth()->user()->name)

@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1">Items Table</h2>
            <p class="text-muted mb-0">Add, delete, and update <span class="text-danger">.items</span></p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('items.export') }}" class="btn btn-outline-success btn-sm">
                <i class="fa-solid fa-file-excel me-1"></i> Export Excel
            </a>
            <a href="{{ route('items.create') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus me-1"></i> Add Item
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 60px;">#</th>
                        <th>Category</th>
                        <th>Name</th>
                        <th>Total</th>
                        <th class="text-center">Repair</th>
                        <th class="text-center">Lending</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $index => $item)
                        <tr>
                            <td class="text-center text-muted">{{ $index + 1 }}</td>
                            <td class="fw-semibold">{{ $item->category->name ?? '-' }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->total }}</td>
                            <td class="text-center">{{ $item->repair ?? 0 }}</td>
                            <td class="text-center">
                                @if($item->lending_total > 0)
                                    <a href="{{ route('lendings.showByItem', $item->id) }}" class="text-decoration-none">
                                        {{ $item->lending_total }}
                                    </a>
                                @else
                                    0
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No items found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
