<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Lending</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f6f8;
        }
        .card-custom {
            border-radius: 12px;
        }
        .remove-btn {
            cursor: pointer;
            color: red;
            font-weight: bold;
        }
    .signature-pad {
        border: 1px solid #ced4da;
        border-radius: 8px;
        background: #fff;
        touch-action: none;
    }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="card card-custom shadow-sm p-4">

        <h5 class="fw-bold mb-3">Lending Form</h5>
        <p class="text-muted small">
            Please <span class="text-danger">.fill-all</span> input form with right value.
        </p>
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
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('lendings.store') }}" method="POST">
            @csrf

            <!-- NAME -->
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" placeholder="Name" value="{{ old('name') }}">
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- <div class="mb-3">
                <label>Tanggal Peminjaman</label>
                <input type="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}">
                @error('date')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div> --}}

            <!-- MAIN CONTAINER -->
            <div id="items-container">

                <!-- DEFAULT ITEM -->
                <div class="item-group border rounded p-3 mb-3">
                    <div class="mb-2">
                        <label>Items</label>
                        <select name="items[]" class="form-control" required>
                            <option value="">-- Select Items --</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" {{ old('items.0') == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label>Total</label>
                        <input type="number" name="total[]" class="form-control" placeholder="total item" value="{{ old('total.0') }}" min="1" required>
                    </div>
                </div>

            </div>

            <!-- MORE BUTTON -->
            <div class="mb-3">
                <span id="add-more" class="text-primary" style="cursor:pointer;">
                    ▼ More
                </span>
            </div>

            <!-- KETERANGAN -->
            <div class="mb-3">
                <label>Ket.</label>
                <textarea name="keterangan" class="form-control">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- BORROW SIGNATURE -->
            <div class="mb-4">
                <label class="form-label">Tanda Tangan Peminjam</label>
                <div class="mb-2">
                    <canvas id="signature-pad" width="600" height="200" class="w-100 signature-pad"></canvas>
                </div>
                <div class="d-flex gap-2 mb-2">
                    <button type="button" id="clear-signature" class="btn btn-secondary btn-sm">Clear</button>
                    <span class="text-muted align-self-center">Gambar tanda tangan di kotak di atas.</span>
                </div>
                <input type="hidden" name="borrow_sign" id="borrow_sign" value="{{ old('borrow_sign') }}">
                @error('borrow_sign')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-primary">Submit</button>
            <a href="{{ route('lendings.index') }}" class="btn btn-secondary">Kembali</a>
        </form>

    </div>
</div>

<!-- SCRIPT -->
<script>
let itemsOptions = `
    <option value="">-- Select Items --</option>
    @foreach($items as $item)
        <option value="{{ $item->id }}">
            {{ $item->name }}
        </option>
    @endforeach
`;

const oldItems = @json(old('items', []));
const oldTotals = @json(old('total', []));
const container = document.getElementById('items-container');

function addItemRow(selectedItem = '', quantity = '') {
    let newItem = document.createElement('div');
    newItem.classList.add('item-group', 'border', 'rounded', 'p-3', 'mb-3');

    newItem.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-2">
            <label class="mb-0">Items</label>
            <span class="remove-btn">✕</span>
        </div>

        <div class="mb-2">
            <select name="items[]" class="form-control" required>
                ${itemsOptions}
            </select>
        </div>

        <div>
            <label>Total</label>
            <input type="number" name="total[]" class="form-control" placeholder="total item" min="1" required>
        </div>
    `;

    container.appendChild(newItem);

    if (selectedItem) {
        newItem.querySelector('select').value = selectedItem;
    }
    if (quantity) {
        newItem.querySelector('input[name="total[]"]').value = quantity;
    }
}

document.getElementById('add-more').addEventListener('click', function () {
    addItemRow();
});

const canvas = document.getElementById('signature-pad');
const signatureInput = document.getElementById('borrow_sign');
const clearSignature = document.getElementById('clear-signature');
const ctx = canvas.getContext('2d');
let drawing = false;
let lastPoint = { x: 0, y: 0 };

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

canvas.addEventListener('pointerup', function () {
    drawing = false;
});

canvas.addEventListener('pointerleave', function () {
    drawing = false;
});

clearSignature.addEventListener('click', function () {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    signatureInput.value = '';
});

const form = document.querySelector('form');
form.addEventListener('submit', function (event) {
    if (!signatureInput.value) {
        const dataUrl = canvas.toDataURL('image/png');
        if (dataUrl === 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8Xw8AAn8B9Z0MyhAAAAAASUVORK5CYII=') {
            event.preventDefault();
            alert('Mohon isi tanda tangan peminjam terlebih dahulu.');
            return;
        }
        signatureInput.value = dataUrl;
    }
});

// Restore old inputs after validation error
if (oldItems.length > 0) {
    container.innerHTML = '';
    oldItems.forEach((itemId, index) => {
        addItemRow(itemId, oldTotals[index] ?? '');
    });
}

// REMOVE ITEM
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-btn')) {
        e.target.closest('.item-group').remove();
    }
});
</script>

</body>
</html>
