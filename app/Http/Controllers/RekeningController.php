<?php

namespace App\Http\Controllers;

use App\Models\Rekening;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class RekeningController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Rekening::query();
            
            // Search
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('kode_rek', 'like', "%{$search}%")
                      ->orWhere('nomor_rek', 'like', "%{$search}%")
                      ->orWhere('uraian', 'like', "%{$search}%");
                });
            }
            
            // Filter kode rekening
            if ($request->has('kode_rek') && $request->kode_rek != '') {
                $query->where('kode_rek', $request->kode_rek);
            }
            
            // Order by kode_rek
            $query->orderBy('kode_rek', 'asc');
            
            // Paginate
            $rekenings = $query->paginate(10);
            
            // Get unique values for filters
            $kodeRekList = Rekening::select('kode_rek')
                ->whereNotNull('kode_rek')
                ->where('kode_rek', '!=', '')
                ->distinct()
                ->orderBy('kode_rek')
                ->pluck('kode_rek')
                ->toArray();
                
        } catch (\Exception $e) {
            // Jika tabel belum ada atau error
            $rekenings = new LengthAwarePaginator([], 0, 10);
            $kodeRekList = [];
        }
        
        return view('admin.rekening', compact('rekenings', 'kodeRekList'));
    }
    
    public function create()
    {
        return view('admin.rekening-create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_rek' => 'required|string|max:20|unique:tb_rekening,kode_rek',
            'nomor_rek' => 'required|string|max:50|unique:tb_rekening,nomor_rek',
            'uraian' => 'required|string|max:255'
        ], [
            'kode_rek.required' => 'Kode Rekening harus diisi',
            'kode_rek.unique' => 'Kode Rekening sudah terdaftar',
            'kode_rek.max' => 'Kode Rekening maksimal 20 karakter',
            'nomor_rek.required' => 'Nomor Rekening harus diisi',
            'nomor_rek.unique' => 'Nomor Rekening sudah terdaftar',
            'nomor_rek.max' => 'Nomor Rekening maksimal 50 karakter',
            'uraian.required' => 'Uraian harus diisi',
            'uraian.max' => 'Uraian maksimal 255 karakter'
        ]);
        
        // Format nomor rekening (hapus spasi dan karakter khusus)
        if ($request->nomor_rek) {
            $validated['nomor_rek'] = preg_replace('/\s+/', '', $request->nomor_rek);
        }
        
        Rekening::create($validated);
        
        return redirect('/rekening')->with('success', 'Data rekening berhasil ditambahkan');
    }
    
    public function edit($id)
    {
        $rekening = Rekening::findOrFail($id);
        return view('admin.rekening-edit', compact('rekening'));
    }
    
    public function update(Request $request, $id)
    {
        $rekening = Rekening::findOrFail($id);
        
        $validated = $request->validate([
            'kode_rek' => 'required|string|max:20|unique:tb_rekening,kode_rek,' . $id . ',id_rekening',
            'nomor_rek' => 'required|string|max:50|unique:tb_rekening,nomor_rek,' . $id . ',id_rekening',
            'uraian' => 'required|string|max:255'
        ], [
            'kode_rek.required' => 'Kode Rekening harus diisi',
            'kode_rek.unique' => 'Kode Rekening sudah terdaftar',
            'kode_rek.max' => 'Kode Rekening maksimal 20 karakter',
            'nomor_rek.required' => 'Nomor Rekening harus diisi',
            'nomor_rek.unique' => 'Nomor Rekening sudah terdaftar',
            'nomor_rek.max' => 'Nomor Rekening maksimal 50 karakter',
            'uraian.required' => 'Uraian harus diisi',
            'uraian.max' => 'Uraian maksimal 255 karakter'
        ]);
        
        // Format nomor rekening (hapus spasi dan karakter khusus)
        if ($request->nomor_rek) {
            $validated['nomor_rek'] = preg_replace('/\s+/', '', $request->nomor_rek);
        }
        
        $rekening->update($validated);
        
        return redirect('/rekening')->with('success', 'Data rekening berhasil diperbarui');
    }
    
    public function destroy($id)
    {
        $rekening = Rekening::findOrFail($id);
        $uraian = $rekening->uraian;
        
        $rekening->delete();
        
        return response()->json([
            'success' => true,
            'message' => "Rekening '{$uraian}' berhasil dihapus"
        ]);
    }
} 