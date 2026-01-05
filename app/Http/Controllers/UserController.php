<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
{
    $query = User::query();
    
    // Search
    if ($request->has('search')) {
        $query->where('username', 'like', '%' . $request->search . '%');
    }
    
    // Filter Level
    if ($request->has('level') && $request->level != '') {
        $query->where('level', $request->level);
    }
    
    $users = $query->paginate(10)->withQueryString();
    
    return view('admin.user', compact('users'));
}

    public function create()
    {
        return view('admin.user-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'level' => 'required|in:admin,pegawai,pemimpin,admin_keuangan',
        ]);

        User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'level' => $request->level,
        ]);

        return redirect('/user')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user-edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'username' => 'required|unique:users,username,' . $id,
            'level' => 'required|in:admin,pegawai,pemimpin,admin_keuangan',
        ]);

        $data = ['level' => $request->level];
        
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect('/user')->with('success', 'User berhasil diperbarui');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        
        return redirect('/user')->with('success', 'User berhasil dihapus');
    }
}