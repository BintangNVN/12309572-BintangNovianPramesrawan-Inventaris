@extends('layouts.app')

@section('title', 'Tambah Item')
@section('pageTitle', 'Welcome Back, ' . auth()->user()->name)

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-4">
                <h2 class="h4">Add Item Form</h2>
                <p class="text-muted">Please complete every field with the correct value.</p>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('items.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Masukkan Nama Item" required>
                            @error('name') <div class="form-text text-danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Pilih Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="form-text text-danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Total</label>
                            <input type="number" name="total" value="{{ old('total') }}" class="form-control" placeholder="Masukkan total" required>
                            @error('total') <div class="form-text text-danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('items.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
