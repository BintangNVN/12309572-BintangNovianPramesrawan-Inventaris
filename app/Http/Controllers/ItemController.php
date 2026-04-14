<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\Category;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.Item.index', [
            'items' => Item::with('category')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.Item.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'total' => 'required|integer|min:0',
        ]);

        Item::create($request->only('name', 'category_id', 'total'));

        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        $categories = Category::all();
        return view('admin.Item.edit', compact('item', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'total' => 'required|integer|min:0',
            'new_broke' => 'nullable|integer|min:0'
        ]);

        // ambil repair lama
        $currentRepair = $item->repair ?? 0;

        // ambil input baru
        $newBroke = $request->new_broke ?? 0;

        // jumlahkan
        $totalRepair = $currentRepair + $newBroke;

        // update data
        $item->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'total' => $request->total,
            'repair' => $totalRepair
        ]);

        return redirect()->route('items.index')->with('success', 'Item updated!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {

    }

    public function item(){
        return view('staff.Item.index', [
            'items' => Item::with('category')->get(),
        ]);
    }

}
