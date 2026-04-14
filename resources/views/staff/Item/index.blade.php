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
                        <th class="text-center">Available</th>
                        <th class="text-center">Lending Total</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($items as $index => $item)
                <tr>
                    <td class="text-center text-muted">{{ $index + 1 }}</td>

                    <td class="fw-semibold">{{ $item->category->name ?? '-' }}</td>

                    <td>{{ $item->name }}</td>

                    <td>{{ $item->total }}</td>


                    <td class="text-center">
                        {{ $item->available }}
                    </td>

                    <td class="text-center">
                        {{ $item->lending_total }}
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        No items found.
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
