<?php

namespace App\Http\Controllers;

use App\Models\Lhpd;
use App\Models\SPT;
use App\Models\SPD;
use App\Models\Pegawai;
use App\Models\Daerah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LhpdController extends Controller
{
    /**
     * Get ID and Nama Pelaihari dari database
     */
    private function getPelaihari()
    {
        $pelaihari = Daerah::where('nama', 'Pelaihari')
            ->where('tingkat', 'kecamatan')
            ->first();
        
        if (!$pelaihari) {
            $pelaihari = Daerah::where('kode', '630101')->first();
        }
        
        return $pelaihari;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Lhpd::with(['daerahTujuan', 'tempatDikeluarkan']);

            // Search (termasuk pencarian di JSON dasar dan hasil)
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('tujuan', 'like', "%{$search}%")
                      ->orWhereJsonContains('hasil', $search)  // hasil JSON
                      ->orWhere('tempat_tujuan_snapshot', 'like', "%{$search}%")
                      ->orWhere('tempat_dikeluarkan_snapshot', 'like', "%{$search}%")
                      ->orWhereJsonContains('dasar', $search)
                      ->orWhereHas('daerahTujuan', function($daerahQuery) use ($search) {
                          $daerahQuery->where('nama', 'like', "%{$search}%");
                      })
                      ->orWhereHas('tempatDikeluarkan', function($daerahQuery) use ($search) {
                          $daerahQuery->where('nama', 'like', "%{$search}%");
                      });
                });
            }

            // Filter berdasarkan tanggal LHPD
            if ($request->has('tanggal_lhpd') && $request->tanggal_lhpd != '') {
                $query->whereDate('tanggal_lhpd', $request->tanggal_lhpd);
            }

            // Filter berdasarkan bulan/tahun
            if ($request->has('bulan') && $request->bulan != '') {
                $query->whereMonth('tanggal_lhpd', $request->bulan);
            }

            if ($request->has('tahun') && $request->tahun != '') {
                $query->whereYear('tanggal_lhpd', $request->tahun);
            }

            // Filter berdasarkan daerah tujuan
            if ($request->has('id_daerah') && $request->id_daerah != '') {
                $query->where('id_daerah', $request->id_daerah);
            }

            // Filter berdasarkan tempat dikeluarkan
            if ($request->has('tempat_dikeluarkan') && $request->tempat_dikeluarkan != '') {
                $query->where('tempat_dikeluarkan', $request->tempat_dikeluarkan);
            }

            $query->orderBy('id_lhpd', 'desc');

            $lhpdList = $query->paginate(10);

            $daerahList = Daerah::where('tingkat', 'kabupaten')
                ->orWhere('tingkat', 'kota')
                ->orderBy('nama')
                ->get();

        } catch (\Exception $e) {
            Log::error('Error di LhpdController@index: ' . $e->getMessage());
            $lhpdList = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            $daerahList = collect([]);
        }

        return view('admin.lhpd', compact('lhpdList', 'daerahList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $daerahList = Daerah::where('tingkat', 'kabupaten')
            ->orWhere('tingkat', 'kota')
            ->orderBy('nama')
            ->get();
        
        $sptList = SPT::with(['pegawai_list'])->orderBy('id_spt', 'desc')->get();
        
        return view('admin.lhpd-create', compact('daerahList', 'sptList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'spt_id' => 'required|exists:spt,id_spt',
            'hasil' => 'required|array',  // hasil wajib array/JSON
            'hasil.*' => 'nullable|string|max:500',
            'tanggal_lhpd' => 'required|date',
            'fotos' => 'nullable|array',
            'fotos.*' => 'image|mimes:jpeg,png,jpg|max:10240'
        ]);

        try {
            DB::beginTransaction();

            $spt = SPT::findOrFail($request->spt_id);
            $spd = SPD::where('spt_id', $spt->id_spt)->first();

            $fotoPaths = [];
            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $foto) {
                    $path = $foto->store('lhpd', 'public');
                    $fotoPaths[] = $path;
                }
            }

            // DASAR DIAMBIL DARI SPT (snapshot)
            $dasarSnapshot = $spt->dasar ?? [];

            $pegawaiSnapshot = $spt->pegawai_snapshot;
            
            if (empty($pegawaiSnapshot)) {
                $pegawaiSnapshot = $this->createPegawaiSnapshot($spt->pegawai);
            }
            
            $tempatTujuanSnapshot = null;
            if ($spd && $spd->tempatTujuan) {
                $tempatTujuanSnapshot = $spd->tempatTujuan->nama;
            }
            
            $pelaihari = $this->getPelaihari();
            $tempatDikeluarkanId = $pelaihari ? $pelaihari->id : null;
            $tempatDikeluarkanSnapshot = $pelaihari ? $pelaihari->nama : 'Pelaihari';

            $data = [
                'spt_id' => $spt->id_spt,
                'dasar' => $dasarSnapshot,  // LANGSUNG DARI SPT
                'tujuan' => $spt->tujuan,
                'pegawai_snapshot' => $pegawaiSnapshot,
                'tanggal_berangkat' => $spd ? $spd->tanggal_berangkat : $spt->tanggal,
                'id_daerah' => $spd ? $spd->tempat_tujuan : null,
                'tempat_tujuan_snapshot' => $tempatTujuanSnapshot,
                'hasil' => $validated['hasil'],  // JSON ARRAY
                'tempat_dikeluarkan' => $tempatDikeluarkanId,
                'tempat_dikeluarkan_snapshot' => $tempatDikeluarkanSnapshot,
                'tanggal_lhpd' => $validated['tanggal_lhpd'],
                'foto' => json_encode($fotoPaths),
                'uang_harian_snapshot' => $spd->uang_harian ?? 0,
                'uang_transport_snapshot' => $spd->uang_transport ?? 0,
                'total_biaya_snapshot' => ($spd->uang_harian ?? 0) + ($spd->uang_transport ?? 0)
            ];

            Lhpd::create($data);
            DB::commit();

            return redirect()->route('lhpd.index')
                ->with('success', 'Data LHPD berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan LHPD: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data LHPD: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $lhpd = Lhpd::with(['daerahTujuan', 'tempatDikeluarkan'])->findOrFail($id);
            
            $pegawaiList = $lhpd->pegawai_list;
            $dasarList = $lhpd->dasar_list;
            $hasilList = $lhpd->hasil_list;  // HASIL LIST
            $fotoUrls = $lhpd->foto_urls;
            
            return view('admin.lhpd-show', compact('lhpd', 'pegawaiList', 'dasarList', 'hasilList', 'fotoUrls'));
        } catch (\Exception $e) {
            return redirect()->route('lhpd.index')
                ->with('error', 'Data LHPD tidak ditemukan.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $lhpd = Lhpd::findOrFail($id);
            
            $daerahList = Daerah::where('tingkat', 'kabupaten')
                ->orWhere('tingkat', 'kota')
                ->orderBy('nama')
                ->get();
            
            $fotoUrls = $lhpd->foto_urls;
            $existingFotos = is_array($lhpd->foto) ? $lhpd->foto : json_decode($lhpd->foto, true) ?? [];
            $dasarList = $lhpd->dasar_list;
            $hasilList = $lhpd->hasil_list;  // HASIL LIST EDIT
            
            $pelaihari = Daerah::where('nama', 'Pelaihari')
                ->where('tingkat', 'kecamatan')
                ->first();
            
            $pelaihariId = $pelaihari ? $pelaihari->id : null;
            
            return view('admin.lhpd-edit', compact('lhpd', 'daerahList', 'fotoUrls', 'existingFotos', 'dasarList', 'hasilList', 'pelaihariId'));
            
        } catch (\Exception $e) {
            return redirect()->route('lhpd.index')
                ->with('error', 'Data LHPD tidak ditemukan.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'hasil' => 'required|array',  // hasil wajib array JSON
            'hasil.*' => 'nullable|string|max:500',
            'tanggal_lhpd' => 'required|date',
            'fotos' => 'nullable|array',
            'fotos.*' => 'image|mimes:jpeg,png,jpg|max:10240',
            'delete_fotos' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $lhpd = Lhpd::findOrFail($id);
            
            // DASAR TIDAK DIUPDATE (tetap dari SPT snapshot)
            
            $existingFotos = is_array($lhpd->foto) ? $lhpd->foto : json_decode($lhpd->foto, true) ?? [];
            
            if ($request->has('delete_fotos') && !empty($request->delete_fotos)) {
                $deleteFotos = json_decode($request->delete_fotos, true);
                if (is_array($deleteFotos)) {
                    foreach ($deleteFotos as $fotoToDelete) {
                        if (in_array($fotoToDelete, $existingFotos)) {
                            if (Storage::disk('public')->exists($fotoToDelete)) {
                                Storage::disk('public')->delete($fotoToDelete);
                            }
                            $existingFotos = array_values(array_diff($existingFotos, [$fotoToDelete]));
                        }
                    }
                }
            }

            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $foto) {
                    $path = $foto->store('lhpd', 'public');
                    $existingFotos[] = $path;
                }
            }

            $pelaihari = $this->getPelaihari();
            $tempatDikeluarkanId = $pelaihari ? $pelaihari->id : null;
            $tempatDikeluarkanSnapshot = $pelaihari ? $pelaihari->nama : 'Pelaihari';

            $updateData = [
                'hasil' => $validated['hasil'],  // UPDATE HASIL JSON
                'tempat_dikeluarkan' => $tempatDikeluarkanId,
                'tempat_dikeluarkan_snapshot' => $tempatDikeluarkanSnapshot,
                'tanggal_lhpd' => $validated['tanggal_lhpd'],
                'foto' => json_encode($existingFotos)
            ];

            $lhpd->update($updateData);
            DB::commit();

            return redirect()->route('lhpd.index')
                ->with('success', 'Data LHPD berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui LHPD: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data LHPD: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $lhpd = Lhpd::findOrFail($id);
            
            $fotos = is_array($lhpd->foto) ? $lhpd->foto : json_decode($lhpd->foto, true);
            if (is_array($fotos)) {
                foreach ($fotos as $foto) {
                    if (Storage::disk('public')->exists($foto)) {
                        Storage::disk('public')->delete($foto);
                    }
                }
            }
            
            $lhpd->delete();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data LHPD berhasil dihapus.'
                ]);
            }

            return redirect()->route('lhpd.index')
                ->with('success', 'Data LHPD berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Gagal menghapus LHPD: ' . $e->getMessage());
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus data LHPD: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('lhpd.index')
                ->with('error', 'Gagal menghapus data LHPD: ' . $e->getMessage());
        }
    }

    /**
     * Get photos for gallery
     */
    public function getFotos($id)
    {
        try {
            $lhpd = Lhpd::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'fotos' => $lhpd->foto_urls->map(function($url, $index) use ($lhpd) {
                    $fotos = is_array($lhpd->foto) ? $lhpd->foto : json_decode($lhpd->foto, true) ?? [];
                    return [
                        'url' => $url,
                        'path' => $fotos[$index] ?? null
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper untuk membuat snapshot pegawai dari array ID pegawai (FALLBACK)
     */
    private function createPegawaiSnapshot($pegawaiIds)
    {
        if (empty($pegawaiIds)) {
            return [];
        }
        
        $ids = is_array($pegawaiIds) ? $pegawaiIds : json_decode($pegawaiIds, true);
        
        if (empty($ids)) {
            return [];
        }
        
        $pegawaiList = Pegawai::whereIn('id_pegawai', $ids)->get();
        
        $snapshot = [];
        foreach ($pegawaiList as $pegawai) {
            $snapshot[] = [
                'id_pegawai' => $pegawai->id_pegawai,
                'nama' => $pegawai->nama,
                'nip' => $pegawai->nip ?? '-',
                'jabatan' => $pegawai->jabatan ?? '-',
                'pangkat' => $pegawai->pangkat ?? '-',
                'golongan' => $pegawai->golongan ?? '-',
            ];
        }
        
        return $snapshot;
    }

    /**
     * OTOMATIS BUAT LHPD SAAT SPT DITAMBAH
     */
    public function createLhpdFromSpt(SPT $spt)
    {
        try {
            $existingLhpd = Lhpd::where('spt_id', $spt->id_spt)->first();
            
            if ($existingLhpd) {
                Log::info('LHPD sudah ada untuk SPT ID: ' . $spt->id_spt);
                return $existingLhpd;
            }
            
            $spd = SPD::where('spt_id', $spt->id_spt)->first();
            
            // DASAR LANGSUNG DARI SPT
            $dasarSnapshot = $spt->dasar ?? [];
            
            $pegawaiSnapshot = $spt->pegawai_snapshot;
            
            if (empty($pegawaiSnapshot)) {
                $pegawaiSnapshot = $this->createPegawaiSnapshot($spt->pegawai);
            }
            
            $tempatTujuanSnapshot = null;
            if ($spd && $spd->tempatTujuan) {
                $tempatTujuanSnapshot = $spd->tempatTujuan->nama;
            }
            
            $pelaihari = $this->getPelaihari();
            $tempatDikeluarkanId = $pelaihari ? $pelaihari->id : null;
            $tempatDikeluarkanSnapshot = $pelaihari ? $pelaihari->nama : 'Pelaihari';
            
            $data = [
                'spt_id' => $spt->id_spt,
                'dasar' => $dasarSnapshot,  // DARI SPT
                'tujuan' => $spt->tujuan,
                'pegawai_snapshot' => $pegawaiSnapshot,
                'tanggal_berangkat' => $spd ? $spd->tanggal_berangkat : $spt->tanggal,
                'id_daerah' => $spd ? $spd->tempat_tujuan : null,
                'tempat_tujuan_snapshot' => $tempatTujuanSnapshot,
                'hasil' => null,
                'tempat_dikeluarkan' => $tempatDikeluarkanId,
                'tempat_dikeluarkan_snapshot' => $tempatDikeluarkanSnapshot,
                'tanggal_lhpd' => null,
                'foto' => json_encode([]),
                'uang_harian_snapshot' => $spd->uang_harian ?? 0,
                'uang_transport_snapshot' => $spd->uang_transport ?? 0,
                'total_biaya_snapshot' => ($spd->uang_harian ?? 0) + ($spd->uang_transport ?? 0)
            ];
            
            $lhpd = Lhpd::create($data);
            
            Log::info('LHPD berhasil dibuat dari SPT ID: ' . $spt->id_spt);
            
            return $lhpd;
            
        } catch (\Exception $e) {
            Log::error('Gagal membuat LHPD dari SPT: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * OTOMATIS UPDATE LHPD SAAT SPD DIUPDATE
     */
    public function updateLhpdFromSpd(SPD $spd)
    {
        try {
            $spt = $spd->spt;
            
            if (!$spt) {
                Log::warning('Tidak ada SPT terkait untuk SPD ID: ' . $spd->id_spd);
                return null;
            }
            
            $lhpd = Lhpd::where('spt_id', $spt->id_spt)->first();
            
            if (!$lhpd) {
                return $this->createLhpdFromSpt($spt);
            }
            
            $updateData = [];
            $isUpdated = false;
            
            // Update DASAR dari SPT (bisa berubah)
            if (json_encode($lhpd->dasar) != json_encode($spt->dasar)) {
                $updateData['dasar'] = $spt->dasar ?? [];
                $isUpdated = true;
            }
            
            if ($lhpd->tanggal_berangkat != $spd->tanggal_berangkat) {
                $updateData['tanggal_berangkat'] = $spd->tanggal_berangkat;
                $isUpdated = true;
            }
            
            if ($lhpd->id_daerah != $spd->tempat_tujuan) {
                $updateData['id_daerah'] = $spd->tempat_tujuan;
                $isUpdated = true;
                
                $daerah = Daerah::find($spd->tempat_tujuan);
                $updateData['tempat_tujuan_snapshot'] = $daerah ? $daerah->nama : null;
            }
            
            if ($lhpd->tujuan != $spt->tujuan) {
                $updateData['tujuan'] = $spt->tujuan;
                $isUpdated = true;
            }
            
            if (json_encode($lhpd->pegawai_snapshot) != json_encode($spt->pegawai_snapshot)) {
                $updateData['pegawai_snapshot'] = $spt->pegawai_snapshot;
                $isUpdated = true;
            }
            
            if ($lhpd->uang_harian_snapshot != ($spd->uang_harian ?? 0)) {
                $updateData['uang_harian_snapshot'] = $spd->uang_harian ?? 0;
                $isUpdated = true;
            }
            
            if ($lhpd->uang_transport_snapshot != ($spd->uang_transport ?? 0)) {
                $updateData['uang_transport_snapshot'] = $spd->uang_transport ?? 0;
                $isUpdated = true;
            }
            
            $totalBiaya = ($spd->uang_harian ?? 0) + ($spd->uang_transport ?? 0);
            if ($lhpd->total_biaya_snapshot != $totalBiaya) {
                $updateData['total_biaya_snapshot'] = $totalBiaya;
                $isUpdated = true;
            }
            
            if ($isUpdated) {
                $lhpd->update($updateData);
                Log::info('LHPD berhasil diupdate dari SPD ID: ' . $spd->id_spd);
            }
            
            return $lhpd;
            
        } catch (\Exception $e) {
            Log::error('Gagal mengupdate LHPD dari SPD: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get LHPD by SPT ID
     */
    public function getBySptId($sptId)
    {
        try {
            $lhpd = Lhpd::where('spt_id', $sptId)->first();
            
            if ($lhpd) {
                return response()->json([
                    'success' => true,
                    'data' => $lhpd,
                    'dasar_list' => $lhpd->dasar_list,
                    'hasil_list' => $lhpd->hasil_list,
                    'foto_urls' => $lhpd->foto_urls
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'LHPD tidak ditemukan untuk SPT ini'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get LHPD by SPD ID
     */
    public function getBySpdId($spdId)
    {
        try {
            $spd = SPD::with('spt')->findOrFail($spdId);
            
            if (!$spd->spt) {
                return response()->json([
                    'success' => false,
                    'message' => 'SPT tidak ditemukan untuk SPD ini'
                ], 404);
            }
            
            $lhpd = Lhpd::where('spt_id', $spd->spt->id_spt)->first();
            
            if ($lhpd) {
                return response()->json([
                    'success' => true,
                    'data' => $lhpd,
                    'dasar_list' => $lhpd->dasar_list,
                    'hasil_list' => $lhpd->hasil_list,
                    'foto_urls' => $lhpd->foto_urls
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'LHPD tidak ditemukan untuk SPD ini'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Print LHPD (PDF)
     */
    public function print($id)
    {
        try {
            $lhpd = Lhpd::with(['daerahTujuan', 'tempatDikeluarkan'])->findOrFail($id);
            
            $pegawaiList = $lhpd->pegawai_list;
            $dasarList = $lhpd->dasar_list;
            $hasilList = $lhpd->hasil_list;
            $fotoUrls = $lhpd->foto_urls;
            
            $pdf = Pdf::loadView('admin.lhpd-pdf', compact('lhpd', 'pegawaiList', 'dasarList', 'hasilList', 'fotoUrls'));
            $pdf->setPaper('A4', 'portrait');
            
            $namaFile = $this->generatePdfFilename($lhpd, 'LHPD-', '.pdf');
            return $pdf->download($namaFile);
            
        } catch (\Exception $e) {
            Log::error('Gagal mencetak LHPD: ' . $e->getMessage());
            return redirect()->route('lhpd.index')
                ->with('error', 'Gagal mencetak LHPD: ' . $e->getMessage());
        }
    }

    /**
     * Preview LHPD PDF
     */
    public function previewPdf($id)
    {
        try {
            $lhpd = Lhpd::with(['daerahTujuan', 'tempatDikeluarkan'])->findOrFail($id);
            
            $pegawaiList = $lhpd->pegawai_list;
            $dasarList = $lhpd->dasar_list;
            $hasilList = $lhpd->hasil_list;
            $fotoUrls = $lhpd->foto_urls;
            
            $pdf = Pdf::loadView('admin.lhpd-pdf', compact('lhpd', 'pegawaiList', 'dasarList', 'hasilList', 'fotoUrls'));
            $pdf->setPaper('A4', 'portrait');
            
            $namaFile = $this->generatePdfFilename($lhpd, 'LHPD-', '.pdf');
            return $pdf->stream($namaFile);
            
        } catch (\Exception $e) {
            Log::error('Gagal preview LHPD: ' . $e->getMessage());
            return redirect()->route('lhpd.index')
                ->with('error', 'Gagal preview LHPD: ' . $e->getMessage());
        }
    }

    /**
     * Export LHPD ke Excel
     */
    public function export(Request $request)
    {
        try {
            $query = Lhpd::with(['daerahTujuan', 'tempatDikeluarkan']);
            
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('tujuan', 'like', "%{$search}%")
                      ->orWhereJsonContains('hasil', $search)
                      ->orWhere('tempat_tujuan_snapshot', 'like', "%{$search}%")
                      ->orWhere('tempat_dikeluarkan_snapshot', 'like', "%{$search}%")
                      ->orWhereJsonContains('dasar', $search)
                      ->orWhereHas('daerahTujuan', function($daerahQuery) use ($search) {
                          $daerahQuery->where('nama', 'like', "%{$search}%");
                      });
                });
            }
            
            if ($request->has('bulan') && $request->bulan != '') {
                $query->whereMonth('tanggal_lhpd', $request->bulan);
            }
            
            if ($request->has('tahun') && $request->tahun != '') {
                $query->whereYear('tanggal_lhpd', $request->tahun);
            }
            
            $lhpdList = $query->orderBy('tanggal_lhpd', 'desc')->get();
            
            if ($lhpdList->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada data LHPD untuk diexport.');
            }
            
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Data LHPD');
            
            $headers = [
                'A1' => 'NO',
                'B1' => 'DASAR PERJALANAN',
                'C1' => 'TUJUAN',
                'D1' => 'TANGGAL BERANGKAT',
                'E1' => 'DAERAH TUJUAN',
                'F1' => 'HASIL LHPD',
                'G1' => 'TEMPAT DIKELUARKAN',
                'H1' => 'TANGGAL LHPD',
                'I1' => 'JUMLAH FOTO'
            ];
            
            foreach ($headers as $cell => $value) {
                $sheet->setCellValue($cell, $value);
                $sheet->getStyle($cell)->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E0E0E0']],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ]);
            }
            
            $row = 2;
            $no = 1;
            foreach ($lhpdList as $lhpd) {
                $dasarText = $lhpd->dasar_list->implode("\n");
                $hasilText = $lhpd->hasil_list->implode("\n");
                
                $sheet->setCellValue('A' . $row, $no);
                $sheet->setCellValue('B' . $row, $dasarText);
                $sheet->setCellValue('C' . $row, $lhpd->tujuan);
                $sheet->setCellValue('D' . $row, $lhpd->tanggal_berangkat ? date('d-m-Y', strtotime($lhpd->tanggal_berangkat)) : '-');
                
                $daerahTujuan = $lhpd->tempat_tujuan_snapshot ?? ($lhpd->daerahTujuan?->nama ?? '-');
                $sheet->setCellValue('E' . $row, $daerahTujuan);
                
                $sheet->setCellValue('F' . $row, $hasilText);
                
                $tempatDikeluarkan = $lhpd->tempat_dikeluarkan_snapshot ?? 'Pelaihari';
                $sheet->setCellValue('G' . $row, $tempatDikeluarkan);
                
                $sheet->setCellValue('H' . $row, $lhpd->tanggal_lhpd ? date('d-m-Y', strtotime($lhpd->tanggal_lhpd)) : '-');
                $sheet->setCellValue('I' . $row, $lhpd->foto_count);
                
                $sheet->getStyle('B' . $row)->getAlignment()->setWrapText(true);
                $sheet->getStyle('F' . $row)->getAlignment()->setWrapText(true);
                $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    'alignment' => ['vertical' => Alignment::VERTICAL_TOP]
                ]);
                
                $row++;
                $no++;
            }
            
            foreach (range('A', 'I') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }
            
            $sheet->getRowDimension(1)->setRowHeight(30);
            
            $filename = 'Data_LHPD_' . date('Y-m-d_His') . '.xlsx';
            
            if (ob_get_length()) {
                ob_end_clean();
            }
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
            
        } catch (\Exception $e) {
            Log::error('Export Excel LHPD Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal export data: ' . $e->getMessage());
        }
    }

    private function generatePdfFilename($lhpd, $prefix = 'LHPD-', $suffix = '.pdf')
    {
        $nomorBersih = $this->sanitizeFilename('LHPD-' . $lhpd->id_lhpd);
        return $prefix . $nomorBersih . '-' . $lhpd->id_lhpd . $suffix;
    }

    private function sanitizeFilename($filename)
    {
        $filename = str_replace(
            ['/', '\\', ':', '*', '?', '"', '<', '>', '|', ' ', '(', ')', '[', ']', '{', '}', '!', '@', '#', '$', '%', '^', '&', '=', '+', ',', ';', "'"],
            '-',
            $filename
        );
        $filename = preg_replace('/[^a-zA-Z0-9.-]/', '', $filename);
        $filename = preg_replace('/-+/', '-', $filename);
        $filename = trim($filename, '-');
        if (empty($filename)) $filename = 'lhpd';
        return $filename;
    }
}