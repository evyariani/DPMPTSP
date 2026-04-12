<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pegawai berhasil ditambahkan'
            ]);
        }
        
        return redirect('/pegawai')->with('success', 'Pegawai berhasil ditambahkan');
    }
    
    public function edit($id)
    {
        try {
            $pegawai = Pegawai::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pegawai tidak ditemukan'
                ], 404);
            }
            return redirect('/pegawai')->with('error', 'Data pegawai tidak ditemukan');
        }
        
        return view('admin.edit-pegawai', compact('pegawai'));
    }
    
    public function update(Request $request, $id)
    {
        try {
            $pegawai = Pegawai::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pegawai tidak ditemukan'
                ], 404);
            }
            return redirect('/pegawai')->with('error', 'Data pegawai tidak ditemukan');
        }
        
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
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pegawai berhasil diperbarui'
            ]);
        }
        
        return redirect('/pegawai')->with('success', 'Pegawai berhasil diperbarui');
    }
    
    /**
     * PERBAIKAN UTAMA: Method destroy dengan dukungan AJAX
     */
    public function destroy($id)
    {
        try {
            $pegawai = Pegawai::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pegawai tidak ditemukan!'
                ], 404);
            }
            return redirect('/pegawai')->with('error', 'Data pegawai tidak ditemukan!');
        }
        
        // Simpan nama pegawai untuk pesan notifikasi
        $namaPegawai = $pegawai->nama;
        $nipPegawai = $pegawai->nip;
        
        // Cek apakah pegawai memiliki relasi dengan data lain (jika ada)
        // Misalnya: cek apakah pegawai memiliki surat, dll
        // if ($pegawai->surat()->count() > 0) {
        //     if (request()->ajax()) {
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'Tidak dapat menghapus pegawai karena memiliki data surat terkait!'
        //         ], 403);
        //     }
        //     return redirect('/pegawai')->with('error', 'Tidak dapat menghapus pegawai karena memiliki data surat terkait!');
        // }
        
        // Lakukan soft delete atau hard delete
        $pegawai->delete();
        
        // Response untuk AJAX request
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Pegawai {$namaPegawai}" . ($nipPegawai ? " (NIP: {$nipPegawai})" : "") . " berhasil dihapus!",
                'data' => [
                    'id' => $id,
                    'nama' => $namaPegawai,
                    'nip' => $nipPegawai
                ]
            ]);
        }
        
        // Response untuk request biasa
        return redirect('/pegawai')->with('success', "Pegawai {$namaPegawai} berhasil dihapus!");
    }
    
    /**
     * Method untuk restore data yang terhapus (jika menggunakan soft delete)
     */
    public function restore($id)
    {
        try {
            $pegawai = Pegawai::withTrashed()->findOrFail($id);
            
            if (!$pegawai->trashed()) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data pegawai tidak dalam keadaan terhapus'
                    ], 400);
                }
                return redirect('/pegawai')->with('error', 'Data pegawai tidak dalam keadaan terhapus');
            }
            
            $pegawai->restore();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Pegawai {$pegawai->nama} berhasil dipulihkan!"
                ]);
            }
            
            return redirect('/pegawai')->with('success', "Pegawai {$pegawai->nama} berhasil dipulihkan!");
            
        } catch (ModelNotFoundException $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pegawai tidak ditemukan'
                ], 404);
            }
            return redirect('/pegawai')->with('error', 'Data pegawai tidak ditemukan');
        }
    }
    
    /**
     * Method untuk force delete (hapus permanen)
     */
    public function forceDelete($id)
    {
        try {
            $pegawai = Pegawai::withTrashed()->findOrFail($id);
            $namaPegawai = $pegawai->nama;
            
            $pegawai->forceDelete();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Pegawai {$namaPegawai} berhasil dihapus permanen!"
                ]);
            }
            
            return redirect('/pegawai')->with('success', "Pegawai {$namaPegawai} berhasil dihapus permanen!");
            
        } catch (ModelNotFoundException $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pegawai tidak ditemukan'
                ], 404);
            }
            return redirect('/pegawai')->with('error', 'Data pegawai tidak ditemukan');
        }
    }
}