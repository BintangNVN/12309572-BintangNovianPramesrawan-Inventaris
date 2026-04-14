@extends('layouts.app')

@section('title', 'Admin Category')
@section('pageTitle', 'Welcome Back, ' . auth()->user()->name)

@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1">Categories Table</h2>
            <p class="text-muted mb-0">Add, edit, and delete <span class="text-danger">.categories</span></p>
        </div>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Add Category
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 60px;">#</th>
                        <th>Name</th>
                        <th>Division</th>
                        <th class="text-center">Total Items</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $index => $category)
                        <tr>
                            <td class="text-center text-muted">{{ $index + 1 }}</td>
                            <td class="fw-semibold">{{ $category->name }}</td>
                            <td>{{ $category->division }}</td>
                            <td class="text-center">{{ $category->items_count ?? 0 }}</td>
                            <td class="text-center">
                                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
