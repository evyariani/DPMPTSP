<?php

namespace App\Http\Controllers;

use App\Models\Kwitansi;
use App\Models\SPD;
use App\Models\Pegawai;
use App\Models\RincianBidang;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class KwitansiController extends Controller
{
    /**
     * Display a listing of the resource.
     * Halaman depan kwitansi - otomatis sync kwitansi yang belum ada
     */
    public function index()
    {
        try {
            // 🔥 AUTO SYNC: Buat kwitansi untuk SPD yang belum punya kwitansi
            $this->autoSyncMissingKwitansi();
            
            // Tampilkan semua kwitansi
            $kwitansi = Kwitansi::with('spd')->latest()->paginate(10);
        } catch (\Exception $e) {
            Log::error('Error di KwitansiController@index: ' . $e->getMessage());
            $kwitansi = new LengthAwarePaginator([], 0, 10);
        }

        return view('admin.kwitansi', compact('kwitansi'));
    }

    /**
     * 🔥 AUTO SYNC: Buat kwitansi untuk SPD yang belum punya kwitansi
     * Method ini dipanggil setiap kali halaman kwitansi dibuka
     */
    private function autoSyncMissingKwitansi()
    {
        try {
            // Ambil semua SPD yang belum punya kwitansi
            $spdsWithoutKwitansi = SPD::whereDoesntHave('kwitansi')->get();
            
            if ($spdsWithoutKwitansi->isEmpty()) {
                return; // Tidak ada yang perlu di-sync
            }
            
            Log::info('Auto-sync kwitansi untuk ' . $spdsWithoutKwitansi->count() . ' SPD yang belum punya kwitansi');
            
            foreach ($spdsWithoutKwitansi as $spd) {
                try {
                    Kwitansi::syncFromSpd($spd);
                    Log::info('Auto-sync kwitansi sukses untuk SPD ID: ' . $spd->id_spd);
                } catch (\Exception $e) {
                    Log::warning('Gagal auto-sync kwitansi untuk SPD ID: ' . $spd->id_spd . ' - ' . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            Log::error('Error di autoSyncMissingKwitansi: ' . $e->getMessage());
        }
    }

    public function create()
    {
        // Untuk create manual dari SPD yang sudah ada
        $spdList = SPD::with('tempatTujuan', 'pelaksanaPerjadin', 'penggunaAnggaran')->get();
        
        // Dapatkan default bendahara
        $defaultBendahara = Kwitansi::getDefaultBendahara();
        
        return view('admin.kwitansi-create', compact('spdList', 'defaultBendahara'));
    }

    public function store(Request $request)
    {
        // Validasi untuk create dari SPD
        $request->validate([
            'spd_id' => 'required|exists:spd,id_spd',
            'no_bku' => 'nullable|string|max:100',
            'no_brpp' => 'nullable|string|max:100',
            'terbilang' => 'nullable|string|max:255',
            'nominal' => 'nullable|numeric|min:0',
            'tanggal_kwitansi' => 'nullable|date',
            'penerima' => 'nullable|string|max:255',
            'nip_penerima' => 'nullable|string|max:50',
            'untuk_pembayaran' => 'nullable|string',
            'bendahara_pengeluaran' => 'nullable|string',
            'nip_bendahara' => 'nullable|string',
        ]);

        $spd = SPD::with('penggunaAnggaran', 'pelaksanaPerjadin')->findOrFail($request->spd_id);
        
        // Sync Kwitansi dari SPD
        $kwitansi = Kwitansi::syncFromSpd($spd, [
            'no_bku' => $request->no_bku,
            'no_brpp' => $request->no_brpp,
            'terbilang' => $request->terbilang,
            'nominal' => $request->nominal,
            'tanggal_kwitansi' => $request->tanggal_kwitansi,
            'penerima' => $request->penerima,
            'nip_penerima' => $request->nip_penerima,
            'untuk_pembayaran' => $request->untuk_pembayaran,
            'bendahara_pengeluaran' => $request->bendahara_pengeluaran,
            'nip_bendahara' => $request->nip_bendahara,
        ]);

        return redirect()->route('kwitansi.index')->with('success', 'Berhasil tambah kwitansi dari SPD');
    }

    public function show($id)
    {
        $kwitansi = Kwitansi::with('spd')->findOrFail($id);
        
        return view('admin.kwitansi-detail', compact('kwitansi'));
    }

    public function edit($id)
    {
        $kwitansi = Kwitansi::with('spd')->findOrFail($id);
        $spdList = SPD::with('tempatTujuan', 'pelaksanaPerjadin', 'penggunaAnggaran')->get();
        
        // Dapatkan default bendahara
        $defaultBendahara = Kwitansi::getDefaultBendahara();
        
        return view('admin.kwitansi-edit', compact('kwitansi', 'spdList', 'defaultBendahara'));
    }

    public function update(Request $request, $id)
    {
        $kwitansi = Kwitansi::findOrFail($id);

        $request->validate([
            'spd_id' => 'required|exists:spd,id_spd',
            'no_bku' => 'nullable|string|max:100',
            'no_brpp' => 'nullable|string|max:100',
            'terbilang' => 'nullable|string|max:255',
            'nominal' => 'nullable|numeric|min:0',
            'tanggal_kwitansi' => 'nullable|date',
            'penerima' => 'nullable|string|max:255',
            'nip_penerima' => 'nullable|string|max:50',
            'untuk_pembayaran' => 'nullable|string',
        ]);

        $spd = SPD::with('penggunaAnggaran', 'pelaksanaPerjadin')->findOrFail($request->spd_id);
        
        // Sync ulang Kwitansi dari SPD
        $updatedKwitansi = Kwitansi::syncFromSpd($spd, [
            'no_bku' => $request->no_bku,
            'no_brpp' => $request->no_brpp,
            'terbilang' => $request->terbilang,
            'nominal' => $request->nominal,
            'tanggal_kwitansi' => $request->tanggal_kwitansi,
            'penerima' => $request->penerima,
            'nip_penerima' => $request->nip_penerima,
            'untuk_pembayaran' => $request->untuk_pembayaran,
        ], $kwitansi);

        return redirect()->route('kwitansi.index')->with('success', 'Berhasil update kwitansi');
    }

    public function destroy($id)
    {
        $kwitansi = Kwitansi::findOrFail($id);
        $kwitansi->delete();
        
        return redirect()->route('kwitansi.index')->with('success', 'Berhasil hapus kwitansi');
    }

    /**
     * Cetak PDF Kwitansi - Download PDF
     */
    public function cetak($id)
    {
        $kwitansi = Kwitansi::with('spd')->findOrFail($id);
        
        // Load view khusus PDF
        $pdf = Pdf::loadView('admin.kwitansi-pdf', compact('kwitansi'));
        
        // Set kertas A4
        $pdf->setPaper('A4', 'portrait');
        
        // Nama file
        $namaFile = 'Kwitansi_' . ($kwitansi->no_bku ?? $kwitansi->id) . '_' . date('Ymd') . '.pdf';
        $namaFile = str_replace('/', '_', $namaFile);
        
        // Download PDF
        return $pdf->download($namaFile);
    }

    /**
     * Preview PDF Kwitansi di Browser (opsional)
     */
    public function previewPdf($id)
    {
        $kwitansi = Kwitansi::with('spd')->findOrFail($id);
        
        $pdf = Pdf::loadView('admin.kwitansi-preview', compact('kwitansi'));
        $pdf->setPaper('A4', 'portrait');
        
        $namaFile = 'Kwitansi_' . ($kwitansi->no_bku ?? $kwitansi->id) . '.pdf';
        $namaFile = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $namaFile);
        
        // Tampilkan di browser
        return $pdf->stream($namaFile);
    }

    /**
     * Cetak berdasarkan SPD ID
     */
    public function cetakBySpd($spdId)
    {
        $kwitansi = Kwitansi::with('spd')->where('spd_id', $spdId)->firstOrFail();
        
        $pdf = Pdf::loadView('admin.kwitansi-pdf', compact('kwitansi'));
        $pdf->setPaper('A4', 'portrait');
        
        $namaFile = 'Kwitansi_SPD_' . $spdId . '_' . date('Ymd') . '.pdf';
        $namaFile = str_replace('/', '_', $namaFile);
        
        return $pdf->download($namaFile);
    }

    /**
     * API/JSON: Get Kwitansi by SPD ID
     */
    public function getBySpd($spdId)
    {
        $kwitansi = Kwitansi::with('spd')->where('spd_id', $spdId)->first();
        
        if (!$kwitansi) {
            return response()->json([
                'success' => false,
                'message' => 'Kwitansi tidak ditemukan untuk SPD ini'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $kwitansi->id,
                'spd_id' => $kwitansi->spd_id,
                'tahun_anggaran' => $kwitansi->tahun_anggaran,
                'kode_rekening' => $kwitansi->kode_rekening,
                'sub_kegiatan' => $kwitansi->sub_kegiatan,
                'no_bku' => $kwitansi->no_bku,
                'no_brpp' => $kwitansi->no_brpp,
                'terbilang' => $kwitansi->terbilang,
                'untuk_pembayaran' => $kwitansi->untuk_pembayaran,
                'nominal' => $kwitansi->nominal,
                'nominal_rupiah' => 'Rp ' . number_format($kwitansi->nominal, 0, ',', '.'),
                'tanggal_kwitansi' => $kwitansi->tanggal_kwitansi,
                'tanggal_kwitansi_formatted' => $kwitansi->tanggal_kwitansi ? $kwitansi->tanggal_kwitansi->translatedFormat('d F Y') : '-',
                'pengguna_anggaran' => $kwitansi->pengguna_anggaran,
                'nip_pengguna_anggaran' => $kwitansi->nip_pengguna_anggaran,
                'bendahara_pengeluaran' => $kwitansi->bendahara_pengeluaran,
                'nip_bendahara' => $kwitansi->nip_bendahara,
                'penerima' => $kwitansi->penerima,
                'nip_penerima' => $kwitansi->nip_penerima,
            ]
        ]);
    }

    /**
     * Sync all Kwitansi dari semua SPD (untuk maintenance)
     */
    public function syncAll()
    {
        try {
            $spds = SPD::with(['penggunaAnggaran', 'pelaksanaPerjadin'])->get();
            $synced = 0;
            $errors = [];
            
            foreach ($spds as $spd) {
                try {
                    Kwitansi::syncFromSpd($spd);
                    $synced++;
                } catch (\Exception $e) {
                    $errors[] = "SPD ID {$spd->id_spd}: " . $e->getMessage();
                }
            }
            
            if (count($errors) > 0) {
                return redirect()->route('kwitansi.index')
                    ->with('warning', "Berhasil sync {$synced} data, namun ada error: " . implode('; ', $errors));
            }
            
            return redirect()->route('kwitansi.index')
                ->with('success', "Berhasil sync {$synced} kwitansi dari SPD");
                
        } catch (\Exception $e) {
            Log::error('Error di syncAll: ' . $e->getMessage());
            return redirect()->route('kwitansi.index')
                ->with('error', 'Gagal sync data: ' . $e->getMessage());
        }
    }
}