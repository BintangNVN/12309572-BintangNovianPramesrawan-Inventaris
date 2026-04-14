    @extends('layouts.app')

@section('title', 'Tambah User')
@section('pageTitle', 'Welcome Back, ' . auth()->user()->name)

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-4">
                <h2 class="h4">Add User Form</h2>
                <p class="text-muted">Please complete every field with the correct value.</p>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admins.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Masukkan nama user" required>
                            @error('name') <div class="form-text text-danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Masukkan email" required>
                            @error('email') <div class="form-text text-danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Operator</option>
                            </select>
                            @error('role') <div class="form-text text-danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admins.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
