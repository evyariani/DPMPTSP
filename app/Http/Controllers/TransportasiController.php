<?php

namespace App\Http\Controllers;

use App\Models\Transportasi;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TransportasiController extends Controller
{
    // Data dropdown jenis transportasi (STATIS sesuai gambar)
    private $jenisTransportasiOptions = [
        'Transportasi Darat dan Udara',
        'Transportasi Udara',
        'Transportasi Darat', 
        'Angkutan Darat',
        'Kendaraan Dinas',
        'Angkutan Umum'
    ];

    public function index(Request $request)
    {
        try {
            $query = Transportasi::query();
            
            // Search
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('jenis_transportasi', 'like', "%{$search}%")
                      ->orWhere('lama_perjalanan', 'like', "%{$search}%");
                });
            }
            
            // Filter jenis
            if ($request->has('jenis') && $request->jenis != '') {
                $query->where('jenis_transportasi', $request->jenis);
            }
            
            // Order by jenis_transportasi
            $query->orderBy('jenis_transportasi', 'asc');
            
            // Paginate
            $transportasis = $query->paginate(10);
            
            // Get unique values for filters
            $jenisList = Transportasi::select('jenis_transportasi')
                ->whereNotNull('jenis_transportasi')
                ->where('jenis_transportasi', '!=', '')
                ->distinct()
                ->pluck('jenis_transportasi')
                ->toArray();
                
        } catch (\Exception $e) {
            // Jika tabel belum ada atau error
            $transportasis = new LengthAwarePaginator([], 0, 10);
            $jenisList = [];
        }
        
        // Tambahkan data dropdown untuk view
        $jenisTransportasiOptions = $this->jenisTransportasiOptions;
        
        return view('admin.transportasi', compact('transportasis', 'jenisList', 'jenisTransportasiOptions'));
    }
    
    public function create()
    {
        // Kirim data dropdown ke view
        $jenisTransportasiOptions = $this->jenisTransportasiOptions;
        
        return view('admin.transportasi-create', compact('jenisTransportasiOptions'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_transportasi' => 'required|string|max:100',
            'lama_perjalanan' => 'required|numeric|min:0|max:365'
        ], [
            'jenis_transportasi.required' => 'Jenis transportasi harus dipilih',
            'lama_perjalanan.required' => 'Lama perjalanan harus diisi',
            'lama_perjalanan.numeric' => 'Lama perjalanan harus berupa angka',
            'lama_perjalanan.min' => 'Lama perjalanan minimal 0 hari',
            'lama_perjalanan.max' => 'Lama perjalanan maksimal 365 hari'
        ]);
        
        // Format lama perjalanan: angka + "hari"
        if (is_numeric($validated['lama_perjalanan'])) {
            $validated['lama_perjalanan'] = $validated['lama_perjalanan'] . ' hari';
        }
        
        Transportasi::create($validated);
        
        return redirect('/transportasi')->with('success', 'Data transportasi berhasil ditambahkan');
    }
    
    public function edit($id)
    {
        $transportasi = Transportasi::findOrFail($id);
        
        // Ekstrak angka dari lama perjalanan (contoh: "5 hari" -> "5")
        $lamaAngka = '';
        if (!empty($transportasi->lama_perjalanan)) {
            // Ambil angka dari string (contoh: "5 hari" -> "5")
            preg_match('/(\d+)/', $transportasi->lama_perjalanan, $matches);
            $lamaAngka = $matches[1] ?? '';
        }
        
        // Kirim data dropdown ke view
        $jenisTransportasiOptions = $this->jenisTransportasiOptions;
        
        return view('admin.transportasi-edit', compact('transportasi', 'jenisTransportasiOptions', 'lamaAngka'));
    }
    
    public function update(Request $request, $id)
    {
        $transportasi = Transportasi::findOrFail($id);
        
        $validated = $request->validate([
            'jenis_transportasi' => 'required|string|max:100',
            'lama_perjalanan' => 'required|numeric|min:0|max:365'
        ], [
            'jenis_transportasi.required' => 'Jenis transportasi harus dipilih',
            'lama_perjalanan.required' => 'Lama perjalanan harus diisi',
            'lama_perjalanan.numeric' => 'Lama perjalanan harus berupa angka',
            'lama_perjalanan.min' => 'Lama perjalanan minimal 0 hari',
            'lama_perjalanan.max' => 'Lama perjalanan maksimal 365 hari'
        ]);
        
        // Format lama perjalanan: angka + "hari"
        if (is_numeric($validated['lama_perjalanan'])) {
            $validated['lama_perjalanan'] = $validated['lama_perjalanan'] . ' hari';
        }
        
        $transportasi->update($validated);
        
        return redirect('/transportasi')->with('success', 'Data transportasi berhasil diperbarui');
    }
    
    public function destroy($id)
    {
        $transportasi = Transportasi::findOrFail($id);
        $jenis = $transportasi->jenis_transportasi;
        
        $transportasi->delete();
        
        return response()->json([
            'success' => true,
            'message' => "Transportasi '{$jenis}' berhasil dihapus"
        ]);
    }
}