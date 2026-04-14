<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = User::where('role', 'admin')->get();
        return view('admin.user.adminPage.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->query('type', 'admin');
        return view('admin.user.create', compact('type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'type' => 'required|in:admin,staff',
        ]);

        $type = $request->type;
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => 'temp',
            'role' => $type,
            'is_default_password' => true,
        ]);

        $plainPassword = substr($request->email, 0, 4) . $user->id;
        $user->update([
            'password' => Hash::make($plainPassword),
        ]);

        $redirectRoute = $type === 'admin' ? 'admins.index' : 'operators.index';
        $successMessage = $type === 'admin' ? 'Admin berhasil ditambahkan.' : 'Operator berhasil ditambahkan.';

        return redirect()->route($redirectRoute)->with('success', $successMessage)
            ->with('plainPassword', $plainPassword);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.adminPage.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, string $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'password' => 'nullable|min:4'
    ]);

    $data = [
        'name' => $request->name,
        'email' => $request->email,
    ];


    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
        $data['is_default_password'] = false;
    }

    $user->update($data);

    return redirect()->route('admins.index')
        ->with('success', 'Data berhasil diupdate.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id)
        ->delete();

        return redirect()->route('admins.index')
        ->with('success', 'Admin berhasil dihapus.');
    }
}
