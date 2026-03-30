<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        // Jika sudah login, redirect berdasarkan level
        if (session()->has('user')) {
            return $this->redirectBasedOnLevel();
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

        // Redirect berdasarkan level user
        return $this->redirectBasedOnLevel();
    }

    public function logout()
    {
        session()->flush();
        return redirect('/login')->with('success', 'Anda telah logout!');
    }

    /**
     * Redirect berdasarkan level user
     * Kadis -> User
     * Admin -> User
     * Pegawai -> SPT
     */
    private function redirectBasedOnLevel()
    {
        $level = session('user')['level'];
        
        switch ($level) {
            case 'pegawai':
                // Pegawai diarahkan ke SPT
                return redirect('/spt');
                break;
            case 'kadis':
                // Kadis diarahkan ke User
                return redirect('/user');
                break;
            case 'admin':
                // Admin diarahkan ke User
                return redirect('/user');
                break;
            default:
                return redirect('/user');
        }
    }
}