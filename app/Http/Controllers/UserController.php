<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // HAPUS CONSTRUCTOR - tidak perlu middleware() di sini
    
    public function index(Request $request)
    {
        // Query users dengan filter
        $query = User::query();
        
        // Filter search
        if ($request->has('search') && $request->search != '') {
            $query->where('username', 'like', '%' . $request->search . '%');
        }
        
        // Filter level
        if ($request->has('level') && $request->level != '') {
            $query->where('level', $request->level);
        }
        
        // Pagination
        $users = $query->paginate(10);
        
        return view('admin.user', compact('users'));
    }
    
    public function create()
    {
        return view('admin.user.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|min:3',
            'level' => 'required|in:admin,pegawai,pemimpin,admin_keuangan',
        ]);
        
        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'level' => $request->level,
        ]);
        
        return redirect('/user')->with('success', 'User berhasil ditambahkan!');
    }
    
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.edit', compact('user'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|unique:users,username,' . $id,
            'level' => 'required|in:admin,pegawai,pemimpin,admin_keuangan',
        ]);
        
        $user = User::findOrFail($id);
        $data = [
            'username' => $request->username,
            'level' => $request->level,
        ];
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        
        $user->update($data);
        
        return redirect('/user')->with('success', 'User berhasil diperbarui!');
    }
    
    public function destroy($id)
    {
        // Cegah hapus diri sendiri
        if ($id == session('user')['id']) {
            return redirect('/user')->with('error', 'Tidak dapat menghapus akun sendiri!');
        }
        
        User::destroy($id);
        
        return redirect('/user')->with('success', 'User berhasil dihapus!');
    }
}