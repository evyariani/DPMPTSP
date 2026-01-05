<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        // Jika sudah login, redirect ke user
        if (session()->has('user')) {
            return redirect('/user');
        }
        
        return view('auth.login');
    }

    public function loginProcess(Request $request)
    {
        // Validasi
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Ambil user dari database
        $user = User::where('username', $request->username)->first();

        // Cek username & password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Username atau password salah!')->withInput();
        }

        // Simpan session sebagai array
        session([
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'level' => $user->level,
                'created_at' => $user->created_at,
            ]
        ]);

        // Redirect ke user management
        return redirect('/user');
    }

    public function logout()
    {
        session()->flush();
        return redirect('/login')->with('success', 'Anda telah logout!');
    }
}