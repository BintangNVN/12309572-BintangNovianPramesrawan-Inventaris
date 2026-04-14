@extends('layouts.app')
@section('title', 'Detail Lending Item')
@section('pageTitle', 'Detail Lending: ' . $item->name)

@section('content')
<div class="container-fluid">

    {{-- Header + Back Button --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Lending Table</h4>
            <p class="text-muted mb-0">
                Data of <span class="text-danger">.lendings</span>
            </p>
        </div>

        <a href="{{ route('items.index') }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">#</th>
                        <th>Item</th>
                        <th>Total</th>
                        <th>Name</th>
                        <th class="text-center">Ket.</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Returned</th>
                        <th class="text-center">Created By</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lendings as $index => $lending)
                        @foreach($lending->items as $itemDetail)
                            <tr>
                                @if($loop->first)
                                    <td class="text-center text-muted" rowspan="{{ $lending->items->count() }}">
                                        {{ $index + 1 }}
                                    </td>
                                @endif

                                <td>{{ $itemDetail->name }}</td>
                                <td>{{ $itemDetail->pivot->total }}</td>

                                @if($loop->first)
                                    <td rowspan="{{ $lending->items->count() }}">
                                        {{ $lending->name }}
                                    </td>

                                    <td class="text-center" rowspan="{{ $lending->items->count() }}">
                                        {{ $lending->keterangan ?? '-' }}
                                    </td>

                                    <td class="text-center" rowspan="{{ $lending->items->count() }}">
                                        {{ $lending->date ? $lending->date->format('Y-m-d') : '-' }}
                                    </td>

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

                                    <td class="text-center" rowspan="{{ $lending->items->count() }}">
                                        {{ $lending->edited_by ?? '-' }}
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                Tidak ada data lending untuk item ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
