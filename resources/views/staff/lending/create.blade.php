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
