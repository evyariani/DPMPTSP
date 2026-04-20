<?php

namespace App\Http\Controllers;

use App\Models\RincianBidang;
use App\Models\SPD;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Barryvdh\DomPDF\Facade\Pdf;

class RincianBidangController extends Controller
{
    public function index()
    {
        try {
            $rincian = RincianBidang::with('spd', 'tempatTujuan')->latest()->paginate(10);
        } catch (\Exception $e) {
            $rincian = new LengthAwarePaginator([], 0, 10);
        }

        return view('admin.rincian-bidang', compact('rincian'));
    }

    public function create()
    {
        // Untuk create manual dari SPD yang sudah ada
        $spdList = SPD::with('tempatTujuan', 'pelaksanaPerjadin')->get();
        $pegawai = Pegawai::all();
        
        // Dapatkan default bendahara
        $defaultBendahara = RincianBidang::getDefaultBendahara();
        
        return view('admin.rincian-create', compact('spdList', 'pegawai', 'defaultBendahara'));
    }

    public function store(Request $request)
    {
        // Validasi untuk create dari SPD
        $request->validate([
            'spd_id' => 'required|exists:spd,id_spd',
            'bendahara_pengeluaran_id' => 'nullable|exists:tb_pegawai,id_pegawai',
            'transport' => 'nullable|numeric|min:0',
        ]);

        $spd = SPD::with('tempatTujuan', 'pelaksanaPerjadin')->findOrFail($request->spd_id);
        
        // Sync RincianBidang dari SPD
        $rincianBidang = RincianBidang::syncFromSpd($spd, [
            'bendahara_pengeluaran_id' => $request->bendahara_pengeluaran_id,
            'transport' => $request->transport ?? 0,
        ]);

        return redirect()->route('rincian.index')->with('success', 'Berhasil tambah rincian biaya dari SPD');
    }

public function show($id)
{
    $rincian = RincianBidang::with('spd', 'tempatTujuan', 'uangHarian')
        ->findOrFail($id);
    
    // Gunakan snapshot bendahara jika ada
    $bendahara = $rincian->bendahara_snapshot;
    
    return view('admin.rincian-detail', compact('rincian', 'bendahara'));
}

    public function edit($id)
    {
        $rincian = RincianBidang::with('spd')->findOrFail($id);
        $spdList = SPD::with('tempatTujuan', 'pelaksanaPerjadin')->get();
        $pegawai = Pegawai::all();
        
        // Dapatkan default bendahara
        $defaultBendahara = RincianBidang::getDefaultBendahara();
        
        return view('admin.rincian-edit', compact('rincian', 'spdList', 'pegawai', 'defaultBendahara'));
    }

    public function update(Request $request, $id)
    {
        $rincian = RincianBidang::findOrFail($id);

        // Validasi untuk update
        $request->validate([
            'spd_id' => 'required|exists:spd,id_spd',
            'bendahara_pengeluaran_id' => 'nullable|exists:tb_pegawai,id_pegawai',
            'transport' => 'nullable|numeric|min:0',
        ]);

        $spd = SPD::with('tempatTujuan', 'pelaksanaPerjadin')->findOrFail($request->spd_id);
        
        // Sync ulang RincianBidang dari SPD
        $updatedRincian = RincianBidang::syncFromSpd($spd, [
            'bendahara_pengeluaran_id' => $request->bendahara_pengeluaran_id,
            'transport' => $request->transport ?? 0,
        ]);

        return redirect()->route('rincian.index')->with('success', 'Berhasil update rincian biaya');
    }

    public function destroy($id)
    {
        $rincian = RincianBidang::findOrFail($id);
        $rincian->delete();
        
        return redirect()->route('rincian.index')->with('success', 'Berhasil hapus rincian biaya');
    }

    /**
     * Cetak PDF Rincian Biaya - Download PDF
     */
    public function cetak($id)
    {
        $rincian = RincianBidang::with('spd', 'tempatTujuan', 'uangHarian')
            ->findOrFail($id);

            $bendahara = $rincian->bendahara_snapshot;
        
        // Load view khusus PDF
        $pdf = Pdf::loadView('admin.rincian-pdf', compact('rincian','bendahara'));
        
        // Set kertas A4
        $pdf->setPaper('A4', 'portrait');
        
        // Nama file
        $namaFile = 'Rincian_Biaya_' . ($rincian->nomor_sppd ?? $rincian->id) . '_' . date('Ymd') . '.pdf';
        $namaFile = str_replace('/', '_', $namaFile);
        
        // Download PDF
        return $pdf->download($namaFile);
    }

    /**
     * Preview PDF Rincian Biaya di Browser (opsional)
     */
    public function previewPdf($id)
    {
        $rincian = RincianBidang::with('spd', 'tempatTujuan', 'uangHarian')
            ->findOrFail($id);

        $bendahara = $rincian->bendahara_snapshot;
        
        $pdf = Pdf::loadView('admin.rincian-cetak', compact('rincian','bendahara'));
        $pdf->setPaper('A4', 'portrait');
        
        $namaFile = 'Rincian_Biaya_' . ($rincian->nomor_sppd ?? $rincian->id) . '.pdf';
        $namaFile = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $namaFile);
        
        // Tampilkan di browser
        return $pdf->stream($namaFile);
    }

    /**
     * Cetak berdasarkan SPD ID
     */
    public function cetakBySpd($spdId)
    {
        $rincian = RincianBidang::with('spd', 'tempatTujuan', 'uangHarian')
            ->where('spd_id', $spdId)
            ->firstOrFail();

    // Gunakan snapshot bendahara
    $bendahara = $rincian->bendahara_snapshot;
        
        $pdf = Pdf::loadView('admin.rincian-pdf', compact('rincian','bendahara'));
        $pdf->setPaper('A4', 'portrait');
        
        $namaFile = 'Rincian_Biaya_SPD_' . ($rincian->nomor_sppd ?? $spdId) . '_' . date('Ymd') . '.pdf';
        $namaFile = str_replace('/', '_', $namaFile);
        
        return $pdf->download($namaFile);
    }

    /**
     * API/JSON: Get RincianBidang by SPD ID
     */
    public function getBySpd($spdId)
    {
        $rincian = RincianBidang::with('spd', 'tempatTujuan', 'bendaharaPengeluaran')
            ->where('spd_id', $spdId)
            ->first();
        
        if (!$rincian) {
            return response()->json([
                'success' => false,
                'message' => 'Rincian Bidang tidak ditemukan untuk SPD ini'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $rincian->id,
                'nomor_sppd' => $rincian->nomor_sppd,
                'tanggal_berangkat' => $rincian->tanggal_berangkat,
                'tanggal_kembali' => $rincian->tanggal_kembali,
                'lama_perjadin' => $rincian->lama_perjadin,
                'tempat_tujuan' => $rincian->tempat_tujuan,
                'pegawai' => $rincian->pegawai,
                'daftar_pegawai_lengkap' => $rincian->daftar_pegawai_lengkap,
                'uang_harian' => $rincian->uang_harian,
                'uang_transport' => $rincian->uang_transport,
                'transport' => $rincian->transport,
                'total' => $rincian->total,
                'total_rupiah' => $rincian->total_rupiah,
                'total_keseluruhan' => $rincian->total_keseluruhan,
                'total_keseluruhan_rupiah' => $rincian->total_keseluruhan_rupiah,
                'terbilang' => $rincian->terbilang,
'bendahara' => $rincian->bendahara_snapshot ? [
    'id' => $rincian->bendahara_snapshot->id ?? null,
    'nama' => $rincian->bendahara_snapshot->nama ?? $rincian->bendahara_nama,
    'nip' => $rincian->bendahara_snapshot->nip ?? $rincian->bendahara_nip,
    'jabatan' => $rincian->bendahara_snapshot->jabatan ?? $rincian->bendahara_jabatan,
] : null,
            ]
        ]);
    }

    /**
     * Sync all RincianBidang dari semua SPD (untuk maintenance)
     */
    public function syncAll()
    {
        try {
            $spds = SPD::with(['tempatTujuan', 'pelaksanaPerjadin'])->get();
            $synced = 0;
            $errors = [];
            
            foreach ($spds as $spd) {
                try {
                    RincianBidang::syncFromSpd($spd);
                    $synced++;
                } catch (\Exception $e) {
                    $errors[] = "SPD ID {$spd->id_spd}: " . $e->getMessage();
                }
            }
            
            if (count($errors) > 0) {
                return redirect()->route('rincian.index')
                    ->with('warning', "Berhasil sync {$synced} data, namun ada error: " . implode('; ', $errors));
            }
            
            return redirect()->route('rincian.index')
                ->with('success', "Berhasil sync {$synced} rincian biaya dari SPD");
                
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error di syncAll: ' . $e->getMessage());
            return redirect()->route('rincian.index')
                ->with('error', 'Gagal sync data: ' . $e->getMessage());
        }
    }
}