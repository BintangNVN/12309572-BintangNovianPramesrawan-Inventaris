@extends('layouts.app')

@section('title', 'Admin Item')
@section('pageTitle', 'Welcome Back, ' . auth()->user()->name)

@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1">Operator Table</h2>
            <p class="text-muted mb-0">Add, delete, and update <span class="text-danger">.users</span></p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('users.export') }}" class="btn btn-outline-success btn-sm">
                <i class="fa-solid fa-file-excel me-1"></i> Export Excel
            </a>
            <a href="{{ route('admins.create', ['type' => 'staff']) }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus me-1"></i> Add User
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 60px;">#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($operators as $index => $operator)
                        <tr>
                            <td class="text-center text-muted">{{ $index + 1 }}</td>
                            <td>{{ $operator->name }}</td>
                            <td>{{ $operator->email }}</td>
                            <td class="text-center">
                                <div class="d-inline-flex gap-2">
                                    <form action="{{ route('operators.resetPassword', $operator->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning text-white">
                                            <i class="fa-solid fa-pen"></i> Reset Password
                                        </button>
                                    </form>
                                    <form action="{{ route('operators.destroy', $operator->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No items found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
