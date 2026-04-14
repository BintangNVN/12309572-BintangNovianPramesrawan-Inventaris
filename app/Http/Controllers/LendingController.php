<?php

namespace App\Http\Controllers;

use App\Models\Lending;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LendingExport;

class LendingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

       $lendings = Lending::with('items')->get();
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
        ]);

        DB::beginTransaction();

        try {

            $lending = Lending::create([
                'name' => $request->name,
                'keterangan' => $request->keterangan,
                'date' => $request->date ?? now(),
                'returned' => false,
                'edited_by' => auth()->user()->name ?? null,
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
                }

                $lending->update([
                    'returned' => true,
                    'return_date' => now(),
                ]);

                DB::commit();

                return redirect()->route('lendings.index')
                    ->with('success', 'Data peminjaman berhasil dikembalikan.');
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
