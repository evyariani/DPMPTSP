<?php
// app/Http/Controllers/SptController.php

namespace App\Http\Controllers;

use App\Models\Spt;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SptController extends Controller
{
    /**
     * Display a listing of SPT.
     */
    public function index(Request $request)
    {
        try {
            // Ambil data SPT dengan relasi
            $query = Spt::with('penandatangan');
            
            // Search berdasarkan nomor surat atau keperluan
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nomor_surat', 'like', "%{$search}%")
                      ->orWhere('untuk_keperluan', 'like', "%{$search}%")
                      ->orWhere('kota', 'like', "%{$search}%");
                });
            }
            
            // Filter berdasarkan rentang tanggal
            if ($request->has('tanggal_awal') && $request->tanggal_awal != '') {
                $query->whereDate('tanggal_surat_dibuat', '>=', $request->tanggal_awal);
            }
            
            if ($request->has('tanggal_akhir') && $request->tanggal_akhir != '') {
                $query->whereDate('tanggal_surat_dibuat', '<=', $request->tanggal_akhir);
            }
            
            // Filter berdasarkan penandatangan
            if ($request->has('penandatangan') && $request->penandatangan != '') {
                $query->where('penandatangan_surat', $request->penandatangan);
            }
            
            // Order by tanggal terbaru
            $query->orderBy('tanggal_surat_dibuat', 'desc')
                  ->orderBy('id_spt', 'desc');
            
            // Paginate
            $spts = $query->paginate(10);
            
            // Data untuk filter dropdown
            $penandatanganList = Pegawai::select('tb_pegawai.id_pegawai', 'tb_pegawai.nama', 'tb_pegawai.nip', 'tb_pegawai.jabatan')
                ->whereIn('tb_pegawai.id_pegawai', function($query) {
                    $query->select('penandatangan_surat')
                          ->from('tb_spt')
                          ->whereNotNull('penandatangan_surat');
                })
                ->orderBy('tb_pegawai.nama')
                ->get();
            
            // Statistik ringkas
            $totalSpt = Spt::count();
            $sptBulanIni = Spt::whereMonth('tanggal_surat_dibuat', date('m'))
                ->whereYear('tanggal_surat_dibuat', date('Y'))
                ->count();
            
            return view('admin.spt', compact('spts', 'penandatanganList', 'totalSpt', 'sptBulanIni'));
            
        } catch (\Exception $e) {
            Log::error('Error di index SPT: ' . $e->getMessage());
            
            $spts = new LengthAwarePaginator([], 0, 10);
            $penandatanganList = collect([]);
            $totalSpt = 0;
            $sptBulanIni = 0;
            
            return view('admin.spt', compact('spts', 'penandatanganList', 'totalSpt', 'sptBulanIni'))
                ->with('error', 'Terjadi kesalahan saat memuat data. Silakan coba lagi.');
        }
    }

    /**
     * Show form for creating new SPT.
     */
    public function create()
    {
        try {
            // 🔥 DAFTAR JABATAN YANG DIIZINKAN UNTUK PENANDATANGAN
            $jabatanPenandatangan = [
                'Kepala Dinas',
                'Sekretaris',
                'Kabid Data, Informasi dan Pengaduan',
                'Kabid Perizinan dan Non Perizinan Tertentu',
                'Kabid Perizinan dan Non Perizinan Jasa Usaha',
                'Kabid Penanaman Modal',
                'Kasubbag Perencanaan dan Pelaporan',
                'Kasubbag Umum dan Kepegawaian'
            ];
            
            // Ambil daftar pegawai untuk dropdown penandatangan (hanya dengan jabatan tertentu)
            $penandatangan = Pegawai::select('id_pegawai', 'nama', 'nip', 'jabatan', 'pangkat', 'gol')
                ->whereIn('jabatan', $jabatanPenandatangan)
                ->orderBy('nama')
                ->get();
            
            // Ambil daftar pegawai untuk dropdown pegawai yang diperintahkan (semua pegawai)
            $pegawais = Pegawai::select('id_pegawai', 'nama', 'nip', 'jabatan', 'pangkat', 'gol')
                ->orderBy('nama')
                ->get();
            
            // Generate nomor surat otomatis
            $tahun = date('Y');
            $bulan = date('m');
            $lastSpt = Spt::whereYear('tanggal_surat_dibuat', $tahun)
                ->orderBy('id_spt', 'desc')
                ->first();
            
            if ($lastSpt && $lastSpt->nomor_surat) {
                $parts = explode('/', $lastSpt->nomor_surat);
                $lastNumber = isset($parts[0]) ? (int)$parts[0] : 0;
                $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '001';
            }
            
            $nomorSurat = "{$newNumber}/SPT/{$bulan}/{$tahun}";
            
            Log::info('SPT Create form loaded', [
                'penandatangan_count' => $penandatangan->count(),
                'pegawai_count' => $pegawais->count()
            ]);
            
            return view('admin.spt-create', compact('penandatangan', 'pegawais', 'nomorSurat'));
            
        } catch (\Exception $e) {
            Log::error('Error di create SPT: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat form: ' . $e->getMessage());
        }
    }

    /**
     * Store newly created SPT.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_surat' => 'required|string|max:100|unique:tb_spt,nomor_surat',
            'dasar_surat' => 'required|string',
            'untuk_keperluan' => 'required|string',
            'tanggal_surat_dibuat' => 'required|date',
            'kota' => 'required|string|max:100',
            'penandatangan_surat' => 'required|exists:tb_pegawai,id_pegawai',
            'pegawai_diperintahkan' => 'required|array|min:1',
            'pegawai_diperintahkan.*.id' => 'required|exists:tb_pegawai,id_pegawai',
        ]);

        try {
            DB::beginTransaction();

            // Format dasar_surat dari poin-poin
            $dasarPoins = [];
            if ($request->has('dasar_poins')) {
                foreach ($request->dasar_poins as $poin) {
                    if (!empty(trim($poin))) {
                        $dasarPoins[] = trim($poin);
                    }
                }
            }
            
            if (!empty($dasarPoins)) {
                $formattedDasar = '';
                foreach ($dasarPoins as $index => $poin) {
                    $formattedDasar .= ($index + 1) . '. ' . $poin . "\n";
                }
                $validated['dasar_surat'] = trim($formattedDasar);
            }

            // Format pegawai yang diperintahkan (tanpa uraian)
            $pegawaiDiperintahkan = [];
            if ($request->has('pegawai_diperintahkan')) {
                foreach ($request->pegawai_diperintahkan as $item) {
                    if (!empty($item['id'])) {
                        $pegawaiDiperintahkan[] = [
                            'id' => $item['id']
                        ];
                    }
                }
            }
            
            $validated['pegawai_yang_diperintahkan'] = $pegawaiDiperintahkan;

            // Simpan data
            $spt = Spt::create($validated);

            DB::commit();

            Log::info('SPT created', ['id_spt' => $spt->id_spt, 'nomor_surat' => $spt->nomor_surat]);

            return redirect()->route('spt.show', $spt->id_spt)
                ->with('success', 'SPT berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error store SPT: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menyimpan SPT: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified SPT.
     */
    public function show($id)
    {
        try {
            $spt = Spt::with('penandatangan')
                ->findOrFail($id);
            
            // Ambil data pegawai yang diperintahkan
            $pegawaiIds = [];
            $pegawaiDiperintahkanData = $spt->pegawai_yang_diperintahkan ?? [];
            
            foreach ($pegawaiDiperintahkanData as $item) {
                if (isset($item['id'])) {
                    $pegawaiIds[] = $item['id'];
                }
            }
            
            $pegawaiDiperintahkan = collect();
            if (!empty($pegawaiIds)) {
                $pegawaiDiperintahkan = Pegawai::whereIn('id_pegawai', $pegawaiIds)
                    ->select('id_pegawai', 'nama', 'nip', 'jabatan', 'pangkat', 'gol')
                    ->get();
            }
            
            // Parse dasar_surat menjadi array poin
            $dasarPoins = explode("\n", $spt->dasar_surat);
            $dasarPoins = array_filter($dasarPoins, function($poin) {
                return !empty(trim($poin));
            });
            
            return view('admin.spt-show', compact('spt', 'pegawaiDiperintahkan', 'dasarPoins'));
            
        } catch (\Exception $e) {
            Log::error('Error show SPT: ' . $e->getMessage());
            return redirect()->route('spt.index')
                ->with('error', 'SPT tidak ditemukan');
        }
    }

    /**
     * Show form for editing SPT.
     */
    public function edit($id)
    {
        try {
            $spt = Spt::findOrFail($id);
            
            // 🔥 DAFTAR JABATAN YANG DIIZINKAN UNTUK PENANDATANGAN
            $jabatanPenandatangan = [
                'Kepala Dinas',
                'Sekretaris',
                'Kabid Data, Informasi dan Pengaduan',
                'Kabid Perizinan dan Non Perizinan Tertentu',
                'Kabid Perizinan dan Non Perizinan Jasa Usaha',
                'Kabid Penanaman Modal',
                'Kasubbag Perencanaan dan Pelaporan',
                'Kasubbag Umum dan Kepegawaian'
            ];
            
            // Ambil daftar pegawai untuk dropdown penandatangan
            $penandatangan = Pegawai::select('id_pegawai', 'nama', 'nip', 'jabatan', 'pangkat', 'gol')
                ->whereIn('jabatan', $jabatanPenandatangan)
                ->orderBy('nama')
                ->get();
            
            // Ambil daftar pegawai untuk dropdown pegawai yang diperintahkan
            $pegawais = Pegawai::select('id_pegawai', 'nama', 'nip', 'jabatan', 'pangkat', 'gol')
                ->orderBy('nama')
                ->get();
            
            // Parse dasar_surat menjadi array poin
            $dasarLines = explode("\n", $spt->dasar_surat);
            $dasarPoins = [];
            foreach ($dasarLines as $line) {
                $cleanedLine = preg_replace('/^\d+\.\s*/', '', $line);
                if (!empty(trim($cleanedLine))) {
                    $dasarPoins[] = trim($cleanedLine);
                }
            }
            
            // Ambil data pegawai yang diperintahkan
            $selectedPegawai = [];
            $pegawaiDiperintahkanData = $spt->pegawai_yang_diperintahkan ?? [];
            foreach ($pegawaiDiperintahkanData as $item) {
                if (isset($item['id'])) {
                    $selectedPegawai[] = [
                        'id' => $item['id']
                    ];
                }
            }
            
            return view('admin.spt-edit', compact('spt', 'penandatangan', 'pegawais', 'dasarPoins', 'selectedPegawai'));
            
        } catch (\Exception $e) {
            Log::error('Error edit SPT: ' . $e->getMessage());
            return redirect()->route('spt.index')
                ->with('error', 'SPT tidak ditemukan');
        }
    }

    /**
     * Update the specified SPT.
     */
    public function update(Request $request, $id)
    {
        $spt = Spt::findOrFail($id);

        $validated = $request->validate([
            'nomor_surat' => 'required|string|max:100|unique:tb_spt,nomor_surat,' . $id . ',id_spt',
            'dasar_surat' => 'required|string',
            'untuk_keperluan' => 'required|string',
            'tanggal_surat_dibuat' => 'required|date',
            'kota' => 'required|string|max:100',
            'penandatangan_surat' => 'required|exists:tb_pegawai,id_pegawai',
            'pegawai_diperintahkan' => 'required|array|min:1',
            'pegawai_diperintahkan.*.id' => 'required|exists:tb_pegawai,id_pegawai',
        ]);

        try {
            DB::beginTransaction();

            // Format dasar_surat dari poin-poin
            $dasarPoins = [];
            if ($request->has('dasar_poins')) {
                foreach ($request->dasar_poins as $poin) {
                    if (!empty(trim($poin))) {
                        $dasarPoins[] = trim($poin);
                    }
                }
            }
            
            if (!empty($dasarPoins)) {
                $formattedDasar = '';
                foreach ($dasarPoins as $index => $poin) {
                    $formattedDasar .= ($index + 1) . '. ' . $poin . "\n";
                }
                $validated['dasar_surat'] = trim($formattedDasar);
            }

            // Format pegawai yang diperintahkan
            $pegawaiDiperintahkan = [];
            if ($request->has('pegawai_diperintahkan')) {
                foreach ($request->pegawai_diperintahkan as $item) {
                    if (!empty($item['id'])) {
                        $pegawaiDiperintahkan[] = [
                            'id' => $item['id']
                        ];
                    }
                }
            }
            
            $validated['pegawai_yang_diperintahkan'] = $pegawaiDiperintahkan;

            $spt->update($validated);

            DB::commit();

            Log::info('SPT updated', ['id_spt' => $spt->id_spt]);

            return redirect()->route('spt.show', $spt->id_spt)
                ->with('success', 'SPT berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error update SPT: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal memperbarui SPT: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified SPT.
     */
    public function destroy($id)
    {
        try {
            $spt = Spt::findOrFail($id);
            $spt->delete();
            
            Log::info('SPT deleted', ['id_spt' => $id]);
            
            return redirect()->route('spt.index')
                ->with('success', 'SPT berhasil dihapus');
                
        } catch (\Exception $e) {
            Log::error('Error destroy SPT: ' . $e->getMessage());
            return redirect()->route('spt.index')
                ->with('error', 'Gagal menghapus SPT');
        }
    }

    /**
     * Generate PDF/cetak SPT.
     */
    public function cetak($id)
    {
        try {
            $spt = Spt::with('penandatangan')
                ->findOrFail($id);
            
            // Ambil data pegawai yang diperintahkan
            $pegawaiIds = [];
            $pegawaiDiperintahkanData = $spt->pegawai_yang_diperintahkan ?? [];
            
            foreach ($pegawaiDiperintahkanData as $item) {
                if (isset($item['id'])) {
                    $pegawaiIds[] = $item['id'];
                }
            }
            
            $pegawaiDiperintahkan = collect();
            if (!empty($pegawaiIds)) {
                $pegawaiDiperintahkan = Pegawai::whereIn('id_pegawai', $pegawaiIds)
                    ->select('id_pegawai', 'nama', 'nip', 'jabatan', 'pangkat', 'gol')
                    ->get();
            }
            
            $dasarPoins = explode("\n", $spt->dasar_surat);
            $dasarPoins = array_filter($dasarPoins, function($poin) {
                return !empty(trim($poin));
            });
            
            return view('admin.spt-cetak', compact('spt', 'pegawaiDiperintahkan', 'dasarPoins'));
            
        } catch (\Exception $e) {
            Log::error('Error cetak SPT: ' . $e->getMessage());
            return redirect()->route('spt.index')
                ->with('error', 'SPT tidak ditemukan');
        }
    }

    /**
     * Get pegawai data for JSON response (for AJAX requests).
     */
    public function getPegawai(Request $request)
    {
        $search = $request->get('q', '');
        
        $pegawais = Pegawai::select('id_pegawai', 'nama', 'nip', 'jabatan', 'pangkat', 'gol')
            ->where(function($query) use ($search) {
                $query->where('nama', 'like', "%{$search}%")
                      ->orWhere('nip', 'like', "%{$search}%");
            })
            ->orderBy('nama')
            ->limit(20)
            ->get();
        
        return response()->json($pegawais);
    }
}