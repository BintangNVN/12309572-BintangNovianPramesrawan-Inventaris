<?php

namespace App\Http\Controllers;

use App\Models\Lending;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LendingExport;

class LendingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Lending::with('items');

        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('date', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $lendings = $query->latest('date')->get();
        return view('staff.lending.index', compact('lendings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $items = Item::all();
        return view('staff.lending.create', compact('items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'items' => 'required|array',
            'items.*' => 'exists:items,id',
            'total' => 'required|array',
            'total.*' => 'integer|min:1',
            'date' => 'nullable|date',
            'borrow_sign' => 'required|string',
        ]);

        DB::beginTransaction();

        try {

            $lending = Lending::create([
                'name' => $request->name,
                'keterangan' => $request->keterangan,
                'date' => $request->date ?? now(),
                'returned' => false,
                'edited_by' => Auth::user()?->name ?? null,
                'borrow_sign' => $request->borrow_sign,
            ]);


            foreach ($request->items as $index => $itemId) {

                $item = Item::findOrFail($itemId);
                $qty = $request->total[$index];
                $available = max(0, ($item->total - ($item->repair ?? 0) - ($item->lending ?? 0)));


                if ($qty > $available) {
                    DB::rollBack();
                    return back()->withErrors([
                        'stock' => "Stock {$item->name} tidak cukup!"
                    ])->withInput();
                }


                $item->lending += $qty;
                $item->save();


                $lending->items()->attach($itemId, [
                    'total' => $qty,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return redirect()->route('lendings.index')
                ->with('success', 'Lending berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Lending $lending)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lending $lending)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lending $lending)
    {
        if ($request->has('return_item_id')) {
            $request->validate([
                'return_item_id' => 'required|exists:items,id',
                'qty_return' => 'required|integer|min:1',
                'return_sign' => 'required|string'
            ]);

            DB::beginTransaction();

            try {
                $itemId = $request->return_item_id;
                $qtyReturn = $request->qty_return;

                $pivot = DB::table('lending_items')
                    ->where('lending_id', $lending->id)
                    ->where('item_id', $itemId)
                    ->first();

                if (!$pivot) {
                    throw new \Exception("Item tidak ditemukan dalam peminjaman ini.");
                }

                if ($qtyReturn > $pivot->total) {
                    throw new \Exception("Jumlah kembali melebihi jumlah yang dipinjam.");
                }

                $item = Item::find($itemId);
                $item->lending = max(0, $item->lending - $qtyReturn);
                $item->save();

                $newTotal = $pivot->total - $qtyReturn;
                DB::table('lending_items')
                    ->where('lending_id', $lending->id)
                    ->where('item_id', $itemId)
                    ->update(['total' => $newTotal]);

                $lending->update([
                    'return_sign' => $request->return_sign,
                ]);

                $remainingItems = DB::table('lending_items')->where('lending_id', $lending->id)->sum('total');
                if ($remainingItems == 0) {
                    $lending->update([
                        'returned' => true,
                        'return_date' => now(),
                    ]);
                }

                DB::commit();

                return redirect()->route('lendings.index')
                    ->with('success', 'Barang berhasil dikembalikan (' . $qtyReturn . ' ' . $item->name . ')');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withErrors(['error' => $e->getMessage()]);
            }
        }

        // Fallback or full mark returned if needed
        if ($request->has('mark_returned')) {
            if ($lending->returned) {
                return redirect()->route('lendings.index')
                    ->with('info', 'Lending sudah dikembalikan.');
            }

            DB::beginTransaction();

            try {
                foreach ($lending->items as $item) {
                    $qty = $item->pivot->total;
                    $item->lending = max(0, $item->lending - $qty);
                    $item->save();
                    
                    DB::table('lending_items')
                        ->where('lending_id', $lending->id)
                        ->where('item_id', $item->id)
                        ->update(['total' => 0]);
                }

                $lending->update([
                    'returned' => true,
                    'return_date' => now(),
                    // Optionally ask for signature here too if we wanted full return button, but we replace the button so this fallback is just in case
                ]);

                DB::commit();

                return redirect()->route('lendings.index')
                    ->with('success', 'Data peminjaman berhasil dikembalikan seluruhnya.');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withErrors(['error' => $e->getMessage()]);
            }
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lending $lending)
    {
        DB::beginTransaction();

        try {

            if (!$lending->returned) {
                foreach ($lending->items as $item) {
                    $item->total += $item->pivot->total;
                    $item->save();
                }
            }


            $lending->items()->detach();


            $lending->delete();

            DB::commit();

            return redirect()->route('lendings.index')
                ->with('success', 'Lending berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menghapus data');
        }
    }

        public function showByItem($id)
    {
        $item = Item::findOrFail($id);


        $lendings = Lending::with(['items' => function ($query) use ($id) {
            $query->where('items.id', $id);
        }])->whereHas('items', function ($query) use ($id) {
            $query->where('items.id', $id);
        })->get();

        return view('staff.lending.by-item', compact('lendings', 'item'));
    }

    public function export()
    {
        return Excel::download(new LendingExport, 'lendings.xlsx');
    }
}
