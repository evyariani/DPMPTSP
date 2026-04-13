<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Cek login
        if (!session()->has('user')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu!');
        }

        // Hanya admin yang bisa akses
        if (session('user')['level'] !== 'admin') {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses!');
        }
        
        $query = User::query();
        
        if ($request->has('search') && $request->search != '') {
            $query->where('username', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('level') && $request->level != '') {
            $query->where('level', $request->level);
        }
        
        $users = $query->paginate(10);
        
        return view('admin.user', compact('users'));
    }
    
    public function create()
    {
        if (!session()->has('user')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu!');
        }

        if (session('user')['level'] !== 'admin') {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses!');
        }

        // Cek apakah sudah ada admin
        $adminExists = User::where('level', 'admin')->exists();
        
        // Cek apakah sudah ada kadis
        $kadisExists = User::where('level', 'kadis')->exists();

        return view('admin.user-create', compact('adminExists', 'kadisExists'));
    }
    
    public function store(Request $request)
    {
        if (!session()->has('user')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu!');
        }

        if (session('user')['level'] !== 'admin') {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses!');
        }

        $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|min:3',
            'level' => 'required|in:admin,pegawai,kadis',
        ]);
        
        // Cek apakah sudah ada admin
        $adminExists = User::where('level', 'admin')->exists();
        
        // Cek apakah sudah ada kadis
        $kadisExists = User::where('level', 'kadis')->exists();
        
        // Logika pembatasan level
        $level = $request->level;
        
        // Jika memilih admin tapi admin sudah ada
        if ($level == 'admin' && $adminExists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Akun Admin sudah ada. Tidak dapat menambahkan admin lagi!');
        }
        
        // Jika memilih kadis tapi kadis sudah ada
        if ($level == 'kadis' && $kadisExists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Akun Kepala Dinas (Kadis) sudah ada. Tidak dapat menambahkan kadis lagi!');
        }
        
        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'level' => $level,
        ]);
        
        return redirect('/user')->with('success', 'User berhasil ditambahkan!');
    }
    
    public function edit($id)
    {
        if (!session()->has('user')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu!');
        }

        if (session('user')['level'] !== 'admin') {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses!');
        }

        try {
            $user = User::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return redirect('/user')->with('error', 'Data user tidak ditemukan!');
        }
        
        // Cek apakah sudah ada admin (selain user yang sedang diedit)
        $adminExists = User::where('level', 'admin')
            ->where('id', '!=', $id)
            ->exists();
        
        // Cek apakah sudah ada kadis (selain user yang sedang diedit)
        $kadisExists = User::where('level', 'kadis')
            ->where('id', '!=', $id)
            ->exists();
        
        return view('admin.user-edit', compact('user', 'adminExists', 'kadisExists'));
    }
    
    public function update(Request $request, $id)
    {
        if (!session()->has('user')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu!');
        }

        if (session('user')['level'] !== 'admin') {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses!');
        }

        $request->validate([
            'username' => 'required|unique:users,username,' . $id,
            'level' => 'required|in:admin,pegawai,kadis',
        ]);
        
        try {
            $user = User::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return redirect('/user')->with('error', 'Data user tidak ditemukan!');
        }
        
        // Cek apakah sudah ada admin (selain user ini)
        $adminExists = User::where('level', 'admin')
            ->where('id', '!=', $id)
            ->exists();
        
        // Cek apakah sudah ada kadis (selain user ini)
        $kadisExists = User::where('level', 'kadis')
            ->where('id', '!=', $id)
            ->exists();
        
        // Logika pembatasan level saat update
        $level = $request->level;
        
        // Jika mengubah ke admin tapi admin sudah ada (dan user ini bukan admin)
        if ($level == 'admin' && $adminExists && $user->level != 'admin') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Akun Admin sudah ada. Tidak dapat mengubah user ini menjadi admin!');
        }
        
        // Jika mengubah ke kadis tapi kadis sudah ada (dan user ini bukan kadis)
        if ($level == 'kadis' && $kadisExists && $user->level != 'kadis') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Akun Kepala Dinas (Kadis) sudah ada. Tidak dapat mengubah user ini menjadi kadis!');
        }
        
        $data = [
            'username' => $request->username,
            'level' => $level,
        ];
        
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:3'
            ]);
            $data['password'] = Hash::make($request->password);
        }
        
        $user->update($data);
        
        return redirect('/user')->with('success', 'User berhasil diperbarui!');
    }
    
    /**
     * PERBAIKAN UTAMA: Method destroy dengan dukungan AJAX
     */
    public function destroy($id)
    {
        // Cek login
        if (!session()->has('user')) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan login terlebih dahulu!'
                ], 401);
            }
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu!');
        }

        // Cek level akses
        if (session('user')['level'] !== 'admin') {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses!'
                ], 403);
            }
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses!');
        }

        // Cek tidak menghapus akun sendiri
        if ($id == session('user')['id']) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus akun sendiri!'
                ], 403);
            }
            return redirect('/user')->with('error', 'Tidak dapat menghapus akun sendiri!');
        }
        
        try {
            $user = User::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data user tidak ditemukan!'
                ], 404);
            }
            return redirect('/user')->with('error', 'Data user tidak ditemukan!');
        }
        
        // Cegah menghapus admin terakhir
        if ($user->level == 'admin') {
            $adminCount = User::where('level', 'admin')->count();
            if ($adminCount <= 1) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak dapat menghapus admin terakhir!'
                    ], 403);
                }
                return redirect('/user')->with('error', 'Tidak dapat menghapus admin terakhir!');
            }
        }
        
        // Cegah menghapus kadis terakhir
        if ($user->level == 'kadis') {
            $kadisCount = User::where('level', 'kadis')->count();
            if ($kadisCount <= 1) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak dapat menghapus Kepala Dinas terakhir!'
                    ], 403);
                }
                return redirect('/user')->with('error', 'Tidak dapat menghapus Kepala Dinas terakhir!');
            }
        }
        
        $username = $user->username;
        $user->delete();
        
        // Response untuk AJAX request
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "User {$username} berhasil dihapus!",
                'data' => [
                    'id' => $id,
                    'username' => $username
                ]
            ]);
        }
        
        // Response untuk request biasa
        return redirect('/user')->with('success', "User {$username} berhasil dihapus!");
    }
}