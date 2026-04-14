@extends('layouts.app')

@section('title', 'Edit Kategori')
@section('pageTitle', 'Welcome Back, ' . auth()->user()->name)

@section('content')

<div style="max-width: 600px; margin: auto;">

    <div style="background: #fff; padding: 24px; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">

        <form action="{{ route('categories.update', $category->id) }}" method="POST">
            @csrf
            @method("PUT")
            <!-- Nama -->
            <div style="margin-bottom: 16px;">
                <label style="display:block; margin-bottom:6px; font-weight:600;">Nama Kategori</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $category->name) }}"
                    style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc;"
                    required
                >

                @error('name')
                    <small style="color:red;">{{ $message }}</small>
                @enderror
            </div>

            <!-- Division -->
            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom:6px; font-weight:600;">Division</label>
                <select
                    name="division"
                    style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc;"
                    required
                >
                    <option value="Sarpras" {{ old('division', $category->division) == 'Sarpras' ? 'selected' : '' }}>Sarpras</option>
                    <option value="Tata usaha" {{ old('division', $category->division) == 'Tata usaha' ? 'selected' : '' }}>Tata usaha</option>
                    <option value="Tefa" {{ old('division', $category->division) == 'Tefa' ? 'selected' : '' }}>Tefa</option>
                </select>

                @error('division')
                    <small style="color:red;">{{ $message }}</small>
                @enderror
            </div>

            <!-- Button -->
            <div style="display:flex; justify-content:space-between;">
                <a href="{{ route('categories.index') }}"
                   style="padding:10px 16px; background:#e5e7eb; border-radius:8px; text-decoration:none; color:#111;">
                    Kembali
                </a>

                <button
                    type="submit"
                    style="padding:10px 18px; background:#3b82f6; color:#fff; border:none; border-radius:8px; cursor:pointer;">
                    Simpan
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
