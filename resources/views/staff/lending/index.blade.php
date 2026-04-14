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
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <form action="{{ route('lendings.index') }}" method="GET" class="d-flex gap-2 align-items-center mb-0">
                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}" title="Mulai Tanggal">
                <span class="text-muted">-</span>
                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}" title="Sampai Tanggal">
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                @if(request('start_date') || request('end_date'))
                    <a href="{{ route('lendings.index') }}" class="btn btn-outline-danger btn-sm" title="Clear Filter">
                        <i class="fa-solid fa-times"></i>
                    </a>
                @endif
            </form>
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
                        <th class="text-center">Borrow Sign</th>
                        <th class="text-center">Returned Sign</th>
                        <th class="text-center">Edited By</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp

                    @forelse ($lendings as $lending)
                        @foreach($lending->items as $item)
                            <tr>
                                <td class="text-center text-muted">{{ $no++ }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->pivot->total }}</td>
                                <td>{{ $lending->name }}</td>
                                <td class="text-center">{{ $lending->keterangan ?? '-' }}</td>
                                <td class="text-center">
                                    {{ $lending->date ? $lending->date->format('Y-m-d') : '-' }}
                                </td>
                                <td class="text-center">
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
                                <td class="text-center">
                                    @if($lending->borrow_sign)
                                        <img src="{{ $lending->borrow_sign }}" alt="Borrow signature" class="img-fluid img-thumbnail" style="max-width: 120px; max-height: 80px;" />
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($lending->return_sign)
                                        <img src="{{ $lending->return_sign }}" alt="Return signature" class="img-fluid img-thumbnail" style="max-width: 120px; max-height: 80px;" />
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center font-weight-bold">
                                    {{ $lending->edited_by ?? '-' }}
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">

                                        @if(!$lending->returned && $item->pivot->total > 0)
                                            <button type="button" class="btn btn-sm btn-success d-flex align-items-center gap-1"
                                                    data-bs-toggle="modal" data-bs-target="#returnModal"
                                                    data-lending="{{ $lending->id }}"
                                                    data-item="{{ $item->id }}"
                                                    data-item-name="{{ $item->name }}"
                                                    data-max-qty="{{ $item->pivot->total }}">
                                                <i class="fa-solid fa-check"></i> Return
                                            </button>
                                        @endif

                                        <form action="{{ route('lendings.destroy', $lending->id) }}" method="POST" class="m-0"
                                            onsubmit="return confirm('Hapus peminjaman ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1">
                                                <i class="fa-solid fa-trash"></i> Delete
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">No items found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Return Modal -->
<div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="returnForm" method="POST" action="">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="returnModalLabel">Pengembalian Barang</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="return_item_id" id="modal_item_id">

          <div class="mb-3">
              <label>Barang:</label>
              <input type="text" id="modal_item_name" class="form-control" readonly>
          </div>

          <div class="mb-3">
              <label>Jumlah Kembali:</label>
              <input type="number" name="qty_return" id="modal_qty_return" class="form-control" min="1" required>
              <small class="text-muted">Max: <span id="modal_max_qty"></span></small>
          </div>

          <div class="mb-3">
              <label>Returned Sign:</label>
              <canvas id="return-signature-pad" width="460" height="150" class="w-100 border rounded" style="touch-action: none; background: #fff;"></canvas>
              <div class="d-flex gap-2 mt-2">
                  <button type="button" id="clear-return-signature" class="btn btn-secondary btn-sm">Clear</button>
                  <span class="text-muted align-self-center small">Gambar tanda tangan di sini.</span>
              </div>
              <input type="hidden" name="return_sign" id="modal_return_sign">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary" id="btn-submit-return">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const returnModal = document.getElementById('returnModal');
    const returnForm = document.getElementById('returnForm');
    const modalItemId = document.getElementById('modal_item_id');
    const modalItemName = document.getElementById('modal_item_name');
    const modalQtyReturn = document.getElementById('modal_qty_return');
    const modalMaxQty = document.getElementById('modal_max_qty');
    const signatureInput = document.getElementById('modal_return_sign');

    const canvas = document.getElementById('return-signature-pad');
    const clearSignature = document.getElementById('clear-return-signature');
    const ctx = canvas.getContext('2d');
    let drawing = false;
    let lastPoint = { x: 0, y: 0 };

    if (returnModal) {
        returnModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const lendingId = button.getAttribute('data-lending');
            const itemId = button.getAttribute('data-item');
            const itemName = button.getAttribute('data-item-name');
            const maxQty = button.getAttribute('data-max-qty');

            returnForm.action = `/lendings/${lendingId}`;

            modalItemId.value = itemId;
            modalItemName.value = itemName;
            modalQtyReturn.max = maxQty;
            modalQtyReturn.value = maxQty;
            modalMaxQty.textContent = maxQty;

            ctx.clearRect(0, 0, canvas.width, canvas.height);
            signatureInput.value = '';
        });
    }

    canvas.addEventListener('pointerdown', function (event) {
        drawing = true;
        lastPoint = { x: event.offsetX, y: event.offsetY };
    });

    canvas.addEventListener('pointermove', function (event) {
        if (!drawing) return;
        ctx.strokeStyle = '#000';
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.beginPath();
        ctx.moveTo(lastPoint.x, lastPoint.y);
        ctx.lineTo(event.offsetX, event.offsetY);
        ctx.stroke();
        lastPoint = { x: event.offsetX, y: event.offsetY };
    });

    canvas.addEventListener('pointerup', function () { drawing = false; });
    canvas.addEventListener('pointerleave', function () { drawing = false; });

    clearSignature.addEventListener('click', function () {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        signatureInput.value = '';
    });

    returnForm.addEventListener('submit', function (event) {
        if (!signatureInput.value) {
            const dataUrl = canvas.toDataURL('image/png');
            const emptyCanvasData = document.createElement('canvas');
            emptyCanvasData.width = canvas.width;
            emptyCanvasData.height = canvas.height;
            if (dataUrl === emptyCanvasData.toDataURL('image/png')) {
                event.preventDefault();
                alert('Mohon isi tanda tangan pengembalian terlebih dahulu.');
                return;
            }
            signatureInput.value = dataUrl;
        }
    });
});
</script>

@endsection
