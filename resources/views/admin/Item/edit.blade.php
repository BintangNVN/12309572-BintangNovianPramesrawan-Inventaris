@extends('layouts.app')

@section('title', 'Edit Kategori')
@section('pageTitle', 'Welcome Back, ' . auth()->user()->name)

@section('content')

<div style="max-width: 600px; margin: auto;">

    <div style="background: #fff; padding: 24px; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">

        <form action="{{ route('items.update', $item->id) }}" method="POST">
            @csrf
            @method("PUT")
            <!-- Nama -->
            <div style="margin-bottom: 16px;">
                <label style="display:block; margin-bottom:6px; font-weight:600;">name</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $item->name) }}"
                    style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc;"
                    required
                >

                @error('name')
                    <small style="color:red;">{{ $message }}</small>
                @enderror
            </div>

            <!-- Category -->
            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom:6px; font-weight:600;">Category</label>

                <select name="category_id" style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc;">
                    <option value="">-- Pilih Category --</option>

                    @foreach($categories as $cat)
                        <option
                            value="{{ $cat->id }}"
                            {{ old('category_id', $item->category_id) == $cat->id ? 'selected' : '' }}
                        >
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>

                @error('category_id')
                    <small style="color:red;">{{ $message }}</small>
                @enderror
            </div>

            <!-- Total -->
            <div style="margin-bottom: 16px;">
                <label style="display:block; margin-bottom:6px; font-weight:600;">Total</label>
                <input
                    type="number"
                    name="total"
                    value="{{ old('total', $item->total) }}"
                    style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc;"
                    required
                >

                @error('total')
                    <small style="color:red;">{{ $message }}</small>
                @enderror
            </div>

          {{-- Broke Item --}}
            <div style="margin-bottom: 16px;">
                <label style="display:block; margin-bottom:6px; font-weight:600;">
                    New Broke Item
                    <span style="color: rgb(193, 193, 0)">
                        (currently: {{ $item->repair ?? 0 }})
                    </span>
                </label>

                <input
                    type="number"
                    name="new_broke"
                    value="{{ old('new_broke', 0) }}"
                    min="0"
                    style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc;"
                >

                @error('new_broke')
                    <small style="color:red;">{{ $message }}</small>
                @enderror
            </div>

            <!-- Button -->
            <div style="display:flex; justify-content:space-between;">
                <a href="{{ route('items.index') }}"
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
