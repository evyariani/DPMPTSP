<?php
// app/Http/Controllers/UangHarianController.php

namespace App\Http\Controllers;

use App\Models\Daerah;
use App\Models\UangHarian;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class UangHarianController extends Controller
{
    /**
     * Menampilkan daftar uang harian
     */
    public function index(Request $request)
    {
        try {
            $query = UangHarian::with('daerah');
            
            // ========== SEARCH ==========
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('tempat_tujuan', 'like', "%{$search}%")
                      ->orWhere('uang_harian', 'like', "%{$search}%")
                      ->orWhere('uang_transport', 'like', "%{$search}%")
                      ->orWhereHas('daerah', function($q2) use ($search) {
                          $q2->where('nama', 'like', "%{$search}%");
                      });
                });
            }
            
            // ========== FILTER TINGKAT DAERAH ==========
            if ($request->has('tingkat') && $request->tingkat != '') {
                $query->whereHas('daerah', function($q) use ($request) {
                    $q->where('tingkat', $request->tingkat);
                });
            }
            
            // ========== FILTER PROVINSI ==========
            if ($request->has('provinsi_id') && $request->provinsi_id != '') {
                $query->whereHas('daerah', function($q) use ($request) {
                    $q->where('id', $request->provinsi_id);
                });
            }
            
            // ========== FILTER KABUPATEN ==========
            if ($request->has('kabupaten_id') && $request->kabupaten_id != '') {
                $query->whereHas('daerah', function($q) use ($request) {
                    $q->where('id', $request->kabupaten_id);
                });
            }
            
            // ========== FILTER KECAMATAN ==========
            if ($request->has('kecamatan_id') && $request->kecamatan_id != '') {
                $query->whereHas('daerah', function($q) use ($request) {
                    $q->where('id', $request->kecamatan_id);
                });
            }
            
            // ========== FILTER TEMPAT TUJUAN ==========
            if ($request->has('tempat') && $request->tempat != '') {
                $query->where('tempat_tujuan', $request->tempat);
            }
            
            // ========== ORDER BY ==========
            // Prioritas 1: Tingkat daerah (Kecamatan > Kabupaten > Provinsi)
            $query->orderByRaw("
                CASE 
                    WHEN EXISTS (
                        SELECT 1 FROM tb_daerah d 
                        WHERE d.id = tb_uang_harian.daerah_id 
                        AND d.tingkat = 'kecamatan'
                    ) THEN 1
                    WHEN EXISTS (
                        SELECT 1 FROM tb_daerah d 
                        WHERE d.id = tb_uang_harian.daerah_id 
                        AND d.tingkat = 'kabupaten'
                    ) THEN 2
                    ELSE 3
                END ASC
            ");
            
            // Prioritas 2: Created_at terbaru
            $query->orderBy('created_at', 'desc');
            
            // Paginate dengan query string
            $uangHarians = $query->paginate(10)->withQueryString();
            
            // ========== DATA UNTUK FILTER DROPDOWN ==========
            // Ambil semua provinsi
            $provinsiList = Daerah::where('tingkat', 'provinsi')
                ->orderBy('nama')
                ->pluck('nama', 'id')
                ->toArray();
            
            // Ambil semua kabupaten
            $kabupatenList = Daerah::where('tingkat', 'kabupaten')
                ->orderBy('nama')
                ->pluck('nama', 'id')
                ->toArray();
            
            // Ambil semua kecamatan
            $kecamatanList = Daerah::where('tingkat', 'kecamatan')
                ->orderBy('nama')
                ->pluck('nama', 'id')
                ->toArray();
            
            // Ambil tempat tujuan unik
            $tempatList = UangHarian::distinct()
                ->orderBy('tempat_tujuan')
                ->pluck('tempat_tujuan')
                ->toArray();
            
            // ========== STATISTIK ==========
            $totalData = UangHarian::count();
            $totalKecamatan = UangHarian::whereHas('daerah', function($q) {
                $q->where('tingkat', 'kecamatan');
            })->count();
            $totalKabupaten = UangHarian::whereHas('daerah', function($q) {
                $q->where('tingkat', 'kabupaten');
            })->count();
            $totalProvinsi = UangHarian::whereHas('daerah', function($q) {
                $q->where('tingkat', 'provinsi');
            })->count();
                
        } catch (\Exception $e) {
            // Jika terjadi error, buat paginator kosong
            $uangHarians = new LengthAwarePaginator([], 0, 10);
            $provinsiList = [];
            $kabupatenList = [];
            $kecamatanList = [];
            $tempatList = [];
            $totalData = 0;
            $totalKecamatan = 0;
            $totalKabupaten = 0;
            $totalProvinsi = 0;
        }
        
        return view('admin.uang-harian', compact(
            'uangHarians',
            'provinsiList',
            'kabupatenList',
            'kecamatanList',
            'tempatList',
            'totalData',
            'totalKecamatan',
            'totalKabupaten',
            'totalProvinsi'
        ));
    }

    /**
     * Menampilkan form tambah data
     */
    public function create()
    {
        // Ambil semua provinsi untuk pilihan pertama
        $provinsis = Daerah::where('tingkat', 'provinsi')
            ->orderBy('nama')
            ->get();
        
        return view('admin.uang-harian-create', compact('provinsis'));
    }

    /**
     * API: Get kabupaten/kota berdasarkan provinsi
     */
    public function getKabupaten(Request $request)
    {
        $provinsiId = $request->provinsi_id;
        
        if (!$provinsiId) {
            return response()->json([
                'success' => false,
                'message' => 'Provinsi ID tidak ditemukan',
                'data' => [],
                'can_select_kabupaten' => false
            ]);
        }
        
        $provinsi = Daerah::find($provinsiId);
        
        if (!$provinsi) {
            return response()->json([
                'success' => false,
                'message' => 'Provinsi tidak ditemukan',
                'data' => [],
                'can_select_kabupaten' => false
            ]);
        }
        
        // Cek apakah ini Kalimantan Selatan (kode 63)
        $isKalsel = trim($provinsi->kode) == '63';
        
        // HANYA KIRIM DATA KABUPATEN JIKA KALSEL
        $kabupatens = [];
        if ($isKalsel) {
            $kabupatens = Daerah::where('parent_id', $provinsiId)
                ->where('tingkat', 'kabupaten')
                ->orderBy('nama')
                ->get();
        }
        
        return response()->json([
            'success' => true,
            'data' => $kabupatens,
            'can_select_kabupaten' => $isKalsel,
            'is_kalsel' => $isKalsel
        ]);
    }

    /**
     * API: Get kecamatan berdasarkan kabupaten
     */
    public function getKecamatan(Request $request)
    {
        $kabupatenId = $request->kabupaten_id;
        
        if (!$kabupatenId) {
            return response()->json([
                'success' => false,
                'message' => 'Kabupaten ID tidak ditemukan',
                'data' => [],
                'can_select_kecamatan' => false
            ]);
        }
        
        $kabupaten = Daerah::find($kabupatenId);
        
        if (!$kabupaten) {
            return response()->json([
                'success' => false,
                'message' => 'Kabupaten tidak ditemukan',
                'data' => [],
                'can_select_kecamatan' => false
            ]);
        }
        
        // Cek apakah ini Tanah Laut
        $isTanahLaut = strtolower(trim($kabupaten->nama)) == 'tanah laut';
        
        // HANYA KIRIM DATA JIKA TANAH LAUT
        $kecamatans = [];
        if ($isTanahLaut) {
            $kecamatans = Daerah::where('parent_id', $kabupatenId)
                ->where('tingkat', 'kecamatan')
                ->orderBy('nama')
                ->get();
        }
        
        return response()->json([
            'success' => true,
            'data' => $kecamatans,
            'can_select_kecamatan' => $isTanahLaut,
            'is_tanah_laut' => $isTanahLaut
        ]);
    }

    /**
     * Menyimpan data baru ke database
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'provinsi_id' => 'required|exists:tb_daerah,id',
            'kabupaten_id' => 'nullable|exists:tb_daerah,id',
            'kecamatan_id' => 'nullable|exists:tb_daerah,id',
            'uang_harian' => 'required|numeric|min:0',
            'uang_transport' => 'required|numeric|min:0',
        ], [
            'provinsi_id.required' => 'Provinsi tujuan harus dipilih',
            'provinsi_id.exists' => 'Provinsi tidak valid',
            'kabupaten_id.exists' => 'Kabupaten tidak valid',
            'kecamatan_id.exists' => 'Kecamatan tidak valid',
            'uang_harian.required' => 'Uang harian harus diisi',
            'uang_harian.numeric' => 'Uang harian harus berupa angka',
            'uang_harian.min' => 'Uang harian tidak boleh negatif',
            'uang_transport.required' => 'Uang transport harus diisi',
            'uang_transport.numeric' => 'Uang transport harus berupa angka',
            'uang_transport.min' => 'Uang transport tidak boleh negatif',
        ]);
        
        // VALIDASI KHUSUS UNTUK KALSEL DAN TANAH LAUT
        $provinsi = Daerah::find($request->provinsi_id);
        
        // Cek Kalimantan Selatan (kode 63)
        if ($provinsi && trim($provinsi->kode) == '63') {
            if (!$request->kabupaten_id) {
                return back()
                    ->withInput()
                    ->withErrors(['kabupaten_id' => 'Untuk provinsi Kalimantan Selatan, WAJIB memilih kabupaten/kota']);
            }
            
            // Cek Tanah Laut
            $kabupaten = Daerah::find($request->kabupaten_id);
            if ($kabupaten && strtolower(trim($kabupaten->nama)) == 'tanah laut') {
                if (!$request->kecamatan_id) {
                    return back()
                        ->withInput()
                        ->withErrors(['kecamatan_id' => 'Untuk kabupaten Tanah Laut, WAJIB memilih kecamatan']);
                }
            }
        }
        
        // TENTUKAN DAERAH TUJUAN
        // Prioritas: Kecamatan > Kabupaten > Provinsi
        $daerahId = null;
        $daerah = null;
        
        if ($request->kecamatan_id) {
            $daerahId = $request->kecamatan_id;
            $daerah = Daerah::find($request->kecamatan_id);
        } elseif ($request->kabupaten_id) {
            $daerahId = $request->kabupaten_id;
            $daerah = Daerah::find($request->kabupaten_id);
        } else {
            $daerahId = $request->provinsi_id;
            $daerah = Daerah::find($request->provinsi_id);
        }
        
        // Ambil nama daerah sebagai tempat_tujuan
        $tempatTujuan = $daerah ? $daerah->nama : '';
        
        // Hitung total
        $total = $request->uang_harian + $request->uang_transport;
        
        // Simpan ke database
        try {
            $uangHarian = UangHarian::create([
                'daerah_id' => $daerahId,
                'tempat_tujuan' => $tempatTujuan,
                'uang_harian' => $request->uang_harian,
                'uang_transport' => $request->uang_transport,
                'total' => $total
            ]);
            
            return redirect('/uang-harian')
                ->with('success', "Data uang harian untuk '{$tempatTujuan}' berhasil ditambahkan");
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form edit data
     */
    public function edit($id)
    {
        $uangHarian = UangHarian::with('daerah')->findOrFail($id);
        
        // Ambil semua provinsi
        $provinsis = Daerah::where('tingkat', 'provinsi')
            ->orderBy('nama')
            ->get();
        
        // Ambil data daerah yang dipilih beserta parentnya
        $selectedDaerah = $uangHarian->daerah;
        $selectedProvinsi = null;
        $selectedKabupaten = null;
        $selectedKecamatan = null;
        
        if ($selectedDaerah) {
            if ($selectedDaerah->tingkat == 'kecamatan') {
                $selectedKecamatan = $selectedDaerah;
                $selectedKabupaten = $selectedDaerah->parent;
                $selectedProvinsi = $selectedKabupaten ? $selectedKabupaten->parent : null;
            } elseif ($selectedDaerah->tingkat == 'kabupaten') {
                $selectedKabupaten = $selectedDaerah;
                $selectedProvinsi = $selectedDaerah->parent;
            } elseif ($selectedDaerah->tingkat == 'provinsi') {
                $selectedProvinsi = $selectedDaerah;
            }
        }
        
        // Ambil kabupaten jika provinsi terpilih adalah Kalimantan Selatan
        $kabupatens = [];
        if ($selectedProvinsi && trim($selectedProvinsi->kode) == '63') {
            $kabupatens = Daerah::where('parent_id', $selectedProvinsi->id)
                ->where('tingkat', 'kabupaten')
                ->orderBy('nama')
                ->get();
        }
        
        // Ambil kecamatan jika kabupaten terpilih adalah Tanah Laut
        $kecamatans = [];
        if ($selectedKabupaten && strtolower(trim($selectedKabupaten->nama)) == 'tanah laut') {
            $kecamatans = Daerah::where('parent_id', $selectedKabupaten->id)
                ->where('tingkat', 'kecamatan')
                ->orderBy('nama')
                ->get();
        }
        
        return view('admin.uang-harian-edit', compact(
            'uangHarian', 
            'provinsis', 
            'selectedProvinsi',
            'selectedKabupaten',
            'selectedKecamatan',
            'kabupatens',
            'kecamatans'
        ));
    }

    /**
     * Mengupdate data di database
     */
    public function update(Request $request, $id)
    {
        $uangHarian = UangHarian::findOrFail($id);
        
        // Validasi input
        $validated = $request->validate([
            'provinsi_id' => 'required|exists:tb_daerah,id',
            'kabupaten_id' => 'nullable|exists:tb_daerah,id',
            'kecamatan_id' => 'nullable|exists:tb_daerah,id',
            'uang_harian' => 'required|numeric|min:0',
            'uang_transport' => 'required|numeric|min:0',
        ], [
            'provinsi_id.required' => 'Provinsi tujuan harus dipilih',
            'provinsi_id.exists' => 'Provinsi tidak valid',
            'kabupaten_id.exists' => 'Kabupaten tidak valid',
            'kecamatan_id.exists' => 'Kecamatan tidak valid',
            'uang_harian.required' => 'Uang harian harus diisi',
            'uang_harian.numeric' => 'Uang harian harus berupa angka',
            'uang_harian.min' => 'Uang harian tidak boleh negatif',
            'uang_transport.required' => 'Uang transport harus diisi',
            'uang_transport.numeric' => 'Uang transport harus berupa angka',
            'uang_transport.min' => 'Uang transport tidak boleh negatif',
        ]);
        
        // VALIDASI KHUSUS UNTUK KALSEL DAN TANAH LAUT
        $provinsi = Daerah::find($request->provinsi_id);
        
        if ($provinsi && trim($provinsi->kode) == '63') {
            if (!$request->kabupaten_id) {
                return back()
                    ->withInput()
                    ->withErrors(['kabupaten_id' => 'Untuk provinsi Kalimantan Selatan, WAJIB memilih kabupaten/kota']);
            }
            
            $kabupaten = Daerah::find($request->kabupaten_id);
            if ($kabupaten && strtolower(trim($kabupaten->nama)) == 'tanah laut') {
                if (!$request->kecamatan_id) {
                    return back()
                        ->withInput()
                        ->withErrors(['kecamatan_id' => 'Untuk kabupaten Tanah Laut, WAJIB memilih kecamatan']);
                }
            }
        }
        
        // TENTUKAN DAERAH TUJUAN
        $daerahId = null;
        $daerah = null;
        
        if ($request->kecamatan_id) {
            $daerahId = $request->kecamatan_id;
            $daerah = Daerah::find($request->kecamatan_id);
        } elseif ($request->kabupaten_id) {
            $daerahId = $request->kabupaten_id;
            $daerah = Daerah::find($request->kabupaten_id);
        } else {
            $daerahId = $request->provinsi_id;
            $daerah = Daerah::find($request->provinsi_id);
        }
        
        $tempatTujuan = $daerah ? $daerah->nama : '';
        $total = $request->uang_harian + $request->uang_transport;
        
        // Update data
        try {
            $uangHarian->update([
                'daerah_id' => $daerahId,
                'tempat_tujuan' => $tempatTujuan,
                'uang_harian' => $request->uang_harian,
                'uang_transport' => $request->uang_transport,
                'total' => $total
            ]);
            
            return redirect('/uang-harian')
                ->with('success', "Data uang harian untuk '{$tempatTujuan}' berhasil diperbarui");
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data dari database
     */
    public function destroy($id)
    {
        try {
            $uangHarian = UangHarian::findOrFail($id);
            $tempatTujuan = $uangHarian->tempat_tujuan;
            
            $uangHarian->delete();
            
            return response()->json([
                'success' => true,
                'message' => "Uang harian untuk '{$tempatTujuan}' berhasil dihapus"
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}