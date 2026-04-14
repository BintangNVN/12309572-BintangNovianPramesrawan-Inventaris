@extends('layouts.app')
@section('title', 'Lending List')
@section('pageTitle', 'Welcome Back, ' . auth()->user()->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1">Lendings Table</h2>
            <p class="text-muted mb-0">Add, Returned, and Delete <span class="text-danger">.Lendings</span></p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('lendings.export') }}" class="btn btn-outline-success btn-sm">
                <i class="fa-solid fa-file-excel me-1"></i> Export Excel
            </a>
            <a href="{{ route('lendings.create') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus me-1"></i> Add Lendings
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 60px;">#</th>
                        <th>Item</th>
                        <th>Total</th>
                        <th>Name</th>
                        <th class="text-center">Ket.</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Returned</th>
                        <th class="text-center">Edited By</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lendings as $index => $lending)
                        @foreach($lending->items as $item)
                            <tr>
                                @if($loop->first)
                                    <td class="text-center text-muted" rowspan="{{ $lending->items->count() }}">{{ $index + 1 }}</td>
                                @endif
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->pivot->total }}</td>
                                @if($loop->first)
                                    <td rowspan="{{ $lending->items->count() }}">{{ $lending->name }}</td>
                                    <td class="text-center" rowspan="{{ $lending->items->count() }}">{{ $lending->keterangan ?? '-' }}</td>
                                    <td class="text-center" rowspan="{{ $lending->items->count() }}">{{ $lending->date ? $lending->date->format('Y-m-d') : '-' }}</td>
                                    <td class="text-center" rowspan="{{ $lending->items->count() }}">
                                        @if(!$lending->returned)
                                            <span class="badge bg-warning text-dark">
                                                not returned
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                {{ $lending->return_date ? $lending->return_date->format('d F, Y') : 'returned' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center font-weight-bold" rowspan="{{ $lending->items->count() }}">{{ $lending->edited_by ?? '-' }}</td>
                                    <td class="text-center d-flex gap-1 justify-content-center" rowspan="{{ $lending->items->count() }}">
                                        @if(!$lending->returned)
                                            <form action="{{ route('lendings.update', $lending->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" name="mark_returned" value="1" class="btn btn-sm btn-success">
                                                    Returned
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('lendings.destroy', $lending->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus peminjaman ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fa-solid fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">No items found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
