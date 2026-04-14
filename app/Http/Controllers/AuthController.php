<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
   public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $user = Auth::user();


            if ($user->role === 'admin') {
                $redirect = '/admin/dashboard';
            } elseif ($user->role === 'staff') {
                $redirect = '/staff/dashboard';
            } else {
                $redirect = '/';
            }

            return response()->json([
                'success' => true,
                'redirect' => $redirect
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email atau password salah'
        ]);
    }

     public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

}
