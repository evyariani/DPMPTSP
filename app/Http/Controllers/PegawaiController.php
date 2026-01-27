<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Pegawai::query();
            
            // Search
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('nip', 'like', "%{$search}%")
                      ->orWhere('jabatan', 'like', "%{$search}%");
                });
            }
            
            // Filter pangkat
            if ($request->has('pangkat') && $request->pangkat != '') {
                $query->where('pangkat', $request->pangkat);
            }
            
            // Filter golongan
            if ($request->has('golongan') && $request->golongan != '') {
                $query->where('gol', $request->golongan);
            }
            
            // Order by nama
            $query->orderBy('nama', 'asc');
            
            // Paginate dengan penanganan error
            $pegawais = $query->paginate(10);
            
            // Get unique values for filters
            $pangkatList = Pegawai::select('pangkat')
                ->whereNotNull('pangkat')
                ->where('pangkat', '!=', '')
                ->distinct()
                ->pluck('pangkat')
                ->toArray();
                
            $golonganList = Pegawai::select('gol')
                ->whereNotNull('gol')
                ->where('gol', '!=', '')
                ->distinct()
                ->pluck('gol')
                ->toArray();
                
        } catch (\Exception $e) {
            // Jika ada error (misal tabel tidak ada), berikan data kosong
            $pegawais = new LengthAwarePaginator([], 0, 10);
            $pangkatList = [];
            $golonganList = [];
        }
        
        return view('admin.pegawai', compact('pegawais', 'pangkatList', 'golonganList'));
    }
    
    public function create()
    {
        return view('admin.create-pegawai');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'nip' => 'nullable|string|max:25|unique:tb_pegawai,nip',
            'pangkat' => 'nullable|string|max:50',
            'gol' => 'nullable|string|max:10',
            'jabatan' => 'nullable|string|max:100',
            'tk_jalan' => 'nullable|string|max:50'
        ], [
            'nama.required' => 'Nama pegawai harus diisi',
            'nip.unique' => 'NIP sudah terdaftar'
        ]);
        
        // Format NIP hapus spasi
        if ($request->nip) {
            $validated['nip'] = str_replace(' ', '', $request->nip);
        }
        
        // Format tk_jalan (uppercase jika huruf)
        if ($request->tk_jalan) {
            $validated['tk_jalan'] = trim($request->tk_jalan);
            if (!is_numeric($validated['tk_jalan'])) {
                $validated['tk_jalan'] = strtoupper($validated['tk_jalan']);
            }
        }
        
        Pegawai::create($validated);
        
        return redirect('/pegawai')->with('success', 'Pegawai berhasil ditambahkan');
    }
    
    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        return view('admin.edit-pegawai', compact('pegawai')); // UBAH INI
    }
    
    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);
        
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'nip' => 'nullable|string|max:25|unique:tb_pegawai,nip,' . $id . ',id_pegawai',
            'pangkat' => 'nullable|string|max:50',
            'gol' => 'nullable|string|max:10',
            'jabatan' => 'nullable|string|max:100',
            'tk_jalan' => 'nullable|string|max:50'
        ], [
            'nama.required' => 'Nama pegawai harus diisi',
            'nip.unique' => 'NIP sudah terdaftar'
        ]);
        
        // Format NIP hapus spasi
        if ($request->nip) {
            $validated['nip'] = str_replace(' ', '', $request->nip);
        }
        
        // Format tk_jalan (uppercase jika huruf)
        if ($request->tk_jalan) {
            $validated['tk_jalan'] = trim($request->tk_jalan);
            if (!is_numeric($validated['tk_jalan'])) {
                $validated['tk_jalan'] = strtoupper($validated['tk_jalan']);
            }
        }
        
        $pegawai->update($validated);
        
        return redirect('/pegawai')->with('success', 'Pegawai berhasil diperbarui');
    }
    
    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        
        $pegawai->delete();
        
        return redirect('/pegawai')->with('success', 'Pegawai berhasil dihapus');
    }
}