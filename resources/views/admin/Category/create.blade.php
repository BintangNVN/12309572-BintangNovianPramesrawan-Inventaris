@extends('layouts.app')

@section('title', 'Tambah Kategori')
@section('pageTitle', 'Welcome Back, ' . auth()->user()->name)

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-4">
                <h2 class="h4">Add Category Form</h2>
                <p class="text-muted">Please complete every field with the correct value.</p>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Masukkan nama kategori" required>
                            @error('name')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Division</label>
                            <select name="division" class="form-select" required>
                                <option value="">-- Pilih Division --</option>
                                <option value="Sarpras" {{ old('division') == 'Sarpras' ? 'selected' : '' }}>Sarpras</option>
                                <option value="Tata usaha" {{ old('division') == 'Tata usaha' ? 'selected' : '' }}>Tata usaha</option>
                                <option value="Tefa" {{ old('division') == 'Tefa' ? 'selected' : '' }}>Tefa</option>
                            </select>
                            @error('division')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
