<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class OperatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $operators = User::where('role', 'staff')->get();
        return view('admin.user.operatorPage.index', compact('operators'));
    }

    /**
     * Redirect create ke admin create
     */
    public function create(Request $request)
    {
        return redirect()->route('admins.create', ['type' => 'staff']);
    }

    /**
     * Store new operator
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => 'temp',
            'role' => 'staff',
            'is_default_password' => true,
        ]);

        // generate password default
        $plainPassword = substr($request->email, 0, 4) . $user->id;

        $user->update([
            'password' => Hash::make($plainPassword)
        ]);

        return redirect()->route('operators.index')
            ->with('success', 'Operator berhasil ditambahkan. Password: ' . $plainPassword);
    }

    /**
     * Show edit form
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.operatorPage.edit', compact('user'));
    }

    /**
     * Update operator
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = User::findOrFail($id);


        $user->name = $request->name;
        $user->email = $request->email;


        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->is_default_password = false; // opsional (biar tahu sudah ganti)
        }

        $user->save();

        return redirect()->route('dashboard')
            ->with('success', 'Akun berhasil diperbarui.');
    }

    /**
     * Reset password operator
     */
    public function resetPassword(string $id)
    {
        $user = User::findOrFail($id);

        $orderedOperators = User::where('role', 'staff')
            ->orderBy('id')
            ->pluck('id')
            ->toArray();

        $rowNumber = array_search($user->id, $orderedOperators);
        $rowNumber = $rowNumber === false ? $user->id : $rowNumber + 1;

        $localPart = explode('@', $user->email)[0];
        $newPassword = substr($localPart, 0, 4) . $rowNumber;

        $user->password = Hash::make($newPassword);
        $user->is_default_password = true;
        $user->save();

        return redirect()->route('operators.index')
            ->with('success', 'Reset password berhasil. Password baru: ' . $newPassword);
    }

    /**
     * Delete operator
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('operators.index')
            ->with('success', 'Akun Operator Berhasil dihapus.');
    }
}
