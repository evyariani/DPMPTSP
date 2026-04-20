<?php

namespace App\Http\Controllers;

use App\Models\RincianBidang;
<<<<<<< HEAD
=======
use App\Models\SPD;
>>>>>>> db0c50f6a0cf3864408bbf4a141a91bc52fa8d2b
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Barryvdh\DomPDF\Facade\Pdf;

class RincianBidangController extends Controller
{
    public function index()
    {
        try {
<<<<<<< HEAD
            $rincian = RincianBidang::latest()->paginate(10);
=======
            $rincian = RincianBidang::with('spd', 'tempatTujuan')->latest()->paginate(10);
>>>>>>> db0c50f6a0cf3864408bbf4a141a91bc52fa8d2b
        } catch (\Exception $e) {
            $rincian = new LengthAwarePaginator([], 0, 10);
        }

        return view('admin.rincian-bidang', compact('rincian'));
    }

    public function create()
    {
<<<<<<< HEAD
        // Untuk create manual, kita tetap kirim data pegawai (opsional)
        $pegawai = Pegawai::all();
        return view('admin.rincian-create', compact('pegawai'));
=======
        // Untuk create manual dari SPD yang sudah ada
        $spdList = SPD::with('tempatTujuan', 'pelaksanaPerjadin')->get();
        $pegawai = Pegawai::all();
        
        // Dapatkan default bendahara
        $defaultBendahara = RincianBidang::getDefaultBendahara();
        
        return view('admin.rincian-create', compact('spdList', 'pegawai', 'defaultBendahara'));
>>>>>>> db0c50f6a0cf3864408bbf4a141a91bc52fa8d2b
    }

    public function store(Request $request)
    {
<<<<<<< HEAD
        // Validasi untuk input MANUAL
        $request->validate([
            'nomor' => 'required|string',
            'tanggal' => 'required|date',
            'tujuan' => 'required|string',
            'jumlah_pegawai' => 'required|integer|min:1',
            'nominal_per_pegawai' => 'required|numeric|min:0',
            'hari' => 'required|integer|min:1',
            'transport' => 'nullable|numeric|min:0',
            'terbilang' => 'nullable|string',
            'nama_pegawai' => 'required|array',
            'nama_pegawai.*' => 'required|string',
            'nip_pegawai' => 'nullable|array',
        ]);

        // Buat array pegawai dari input manual
        $pegawaiData = [];
        $totalUangHarian = 0;
        
        $jumlahPegawai = (int)$request->jumlah_pegawai;
        $nominalPerPegawai = (int)$request->nominal_per_pegawai;
        $hari = (int)$request->hari;
        
        for ($i = 0; $i < $jumlahPegawai; $i++) {
            $nama = $request->nama_pegawai[$i] ?? "Pegawai " . ($i + 1);
            $nip = $request->nip_pegawai[$i] ?? '-';
            
            $pegawaiData[] = [
                'id_pegawai' => $i + 1,
                'nama' => $nama,
                'nip' => $nip,
                'nominal' => $nominalPerPegawai,
                'hari' => $hari
            ];
            
            $totalUangHarian += $nominalPerPegawai * $hari;
        }
        
        $transport = (int)$request->transport;
        $total = $totalUangHarian + $transport;
        
        // Jika terbilang kosong, generate otomatis
        $terbilang = $request->terbilang;
        if (empty($terbilang)) {
            $terbilang = $this->convertToTerbilang($total);
        }
        
        RincianBidang::create([
            'nomor' => $request->nomor,
            'tanggal' => $request->tanggal,
            'tujuan' => $request->tujuan,
            'pegawai' => $pegawaiData,
            'transport' => $transport,
            'total' => $total,
            'terbilang' => $terbilang
        ]);

        return redirect()->route('rincian.index')->with('success', 'Berhasil tambah rincian biaya');
=======
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
>>>>>>> db0c50f6a0cf3864408bbf4a141a91bc52fa8d2b
    }

    public function show($id)
    {
<<<<<<< HEAD
        $rincian = RincianBidang::findOrFail($id);
=======
        $rincian = RincianBidang::with('spd', 'tempatTujuan', 'bendaharaPengeluaran', 'uangHarian')
            ->findOrFail($id);
        
>>>>>>> db0c50f6a0cf3864408bbf4a141a91bc52fa8d2b
        return view('admin.rincian-detail', compact('rincian'));
    }

    public function edit($id)
    {
<<<<<<< HEAD
        $rincian = RincianBidang::findOrFail($id);
        $pegawai = Pegawai::all();
        return view('admin.rincian-edit', compact('rincian', 'pegawai'));
=======
        $rincian = RincianBidang::with('spd')->findOrFail($id);
        $spdList = SPD::with('tempatTujuan', 'pelaksanaPerjadin')->get();
        $pegawai = Pegawai::all();
        
        // Dapatkan default bendahara
        $defaultBendahara = RincianBidang::getDefaultBendahara();
        
        return view('admin.rincian-edit', compact('rincian', 'spdList', 'pegawai', 'defaultBendahara'));
>>>>>>> db0c50f6a0cf3864408bbf4a141a91bc52fa8d2b
    }

    public function update(Request $request, $id)
    {
        $rincian = RincianBidang::findOrFail($id);

<<<<<<< HEAD
        // Validasi untuk input MANUAL
        $request->validate([
            'nomor' => 'required|string',
            'tanggal' => 'required|date',
            'tujuan' => 'required|string',
            'jumlah_pegawai' => 'required|integer|min:1',
            'nominal_per_pegawai' => 'required|numeric|min:0',
            'hari' => 'required|integer|min:1',
            'transport' => 'nullable|numeric|min:0',
            'terbilang' => 'nullable|string',
            'nama_pegawai' => 'required|array',
            'nama_pegawai.*' => 'required|string',
            'nip_pegawai' => 'nullable|array',
        ]);

        // Buat array pegawai dari input manual
        $pegawaiData = [];
        $totalUangHarian = 0;
        
        $jumlahPegawai = (int)$request->jumlah_pegawai;
        $nominalPerPegawai = (int)$request->nominal_per_pegawai;
        $hari = (int)$request->hari;
        
        for ($i = 0; $i < $jumlahPegawai; $i++) {
            $nama = $request->nama_pegawai[$i] ?? "Pegawai " . ($i + 1);
            $nip = $request->nip_pegawai[$i] ?? '-';
            
            $pegawaiData[] = [
                'id_pegawai' => $i + 1,
                'nama' => $nama,
                'nip' => $nip,
                'nominal' => $nominalPerPegawai,
                'hari' => $hari
            ];
            
            $totalUangHarian += $nominalPerPegawai * $hari;
        }
        
        $transport = (int)$request->transport;
        $total = $totalUangHarian + $transport;
        
        // Jika terbilang kosong, generate otomatis
        $terbilang = $request->terbilang;
        if (empty($terbilang)) {
            $terbilang = $this->convertToTerbilang($total);
        }
        
        $rincian->update([
            'nomor' => $request->nomor,
            'tanggal' => $request->tanggal,
            'tujuan' => $request->tujuan,
            'pegawai' => $pegawaiData,
            'transport' => $transport,
            'total' => $total,
            'terbilang' => $terbilang
=======
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
>>>>>>> db0c50f6a0cf3864408bbf4a141a91bc52fa8d2b
        ]);

        return redirect()->route('rincian.index')->with('success', 'Berhasil update rincian biaya');
    }

    public function destroy($id)
    {
        $rincian = RincianBidang::findOrFail($id);
        $rincian->delete();
<<<<<<< HEAD
=======
        
>>>>>>> db0c50f6a0cf3864408bbf4a141a91bc52fa8d2b
        return redirect()->route('rincian.index')->with('success', 'Berhasil hapus rincian biaya');
    }

    /**
     * Cetak PDF Rincian Biaya - Download PDF
     */
    public function cetak($id)
    {
<<<<<<< HEAD
    $rincian = RincianBidang::findOrFail($id);
    
    // Load view khusus PDF
    $pdf = Pdf::loadView('admin.rincian-pdf', compact('rincian'));
    
    // Set kertas A4
    $pdf->setPaper('A4', 'portrait');
    
    // Nama file
    $namaFile = 'Rincian_Biaya_' . $rincian->nomor . '_' . date('Ymd') . '.pdf';
    $namaFile = str_replace('/', '_', $namaFile);
    
    // Download PDF
    return $pdf->download($namaFile);
=======
        $rincian = RincianBidang::with('spd', 'tempatTujuan', 'bendaharaPengeluaran', 'uangHarian')
            ->findOrFail($id);
        
        // Load view khusus PDF
        $pdf = Pdf::loadView('admin.rincian-pdf', compact('rincian'));
        
        // Set kertas A4
        $pdf->setPaper('A4', 'portrait');
        
        // Nama file
        $namaFile = 'Rincian_Biaya_' . ($rincian->nomor_sppd ?? $rincian->id) . '_' . date('Ymd') . '.pdf';
        $namaFile = str_replace('/', '_', $namaFile);
        
        // Download PDF
        return $pdf->download($namaFile);
>>>>>>> db0c50f6a0cf3864408bbf4a141a91bc52fa8d2b
    }

    /**
     * Preview PDF Rincian Biaya di Browser (opsional)
     */
    public function previewPdf($id)
    {
<<<<<<< HEAD
        $rincian = RincianBidang::findOrFail($id);
=======
        $rincian = RincianBidang::with('spd', 'tempatTujuan', 'bendaharaPengeluaran', 'uangHarian')
            ->findOrFail($id);
>>>>>>> db0c50f6a0cf3864408bbf4a141a91bc52fa8d2b
        
        $pdf = Pdf::loadView('admin.rincian-cetak', compact('rincian'));
        $pdf->setPaper('A4', 'portrait');
        
<<<<<<< HEAD
        $namaFile = 'Rincian_Biaya_' . $rincian->nomor . '.pdf';
=======
        $namaFile = 'Rincian_Biaya_' . ($rincian->nomor_sppd ?? $rincian->id) . '.pdf';
>>>>>>> db0c50f6a0cf3864408bbf4a141a91bc52fa8d2b
        $namaFile = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $namaFile);
        
        // Tampilkan di browser
        return $pdf->stream($namaFile);
    }

    /**
<<<<<<< HEAD
     * Konversi angka ke terbilang (helper function)
     */
    private function convertToTerbilang($angka)
    {
        if ($angka == 0) return 'Nol Rupiah';
        
        $satuan = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan'];
        $ribuan = ['', 'Ribu', 'Juta', 'Miliar', 'Triliun'];
        
        $terbilangRibuan = function($n) use ($satuan, &$terbilangRibuan) {
            if ($n == 0) return '';
            if ($n < 10) return $satuan[$n];
            if ($n < 20) return $n == 10 ? 'Sepuluh' : $satuan[$n - 10] . ' Belas';
            if ($n < 100) return $satuan[floor($n / 10)] . ' Puluh ' . $terbilangRibuan($n % 10);
            return $satuan[floor($n / 100)] . ' Ratus ' . $terbilangRibuan($n % 100);
        };
        
        $result = '';
        $i = 0;
        $tempAngka = $angka;
        
        while ($tempAngka > 0) {
            $segment = $tempAngka % 1000;
            if ($segment > 0) {
                $segmentText = $terbilangRibuan($segment);
                if ($segment == 1 && $i == 1) $segmentText = 'Seribu';
                if ($segment == 1 && $i > 0) $segmentText = 'Satu';
                if ($segment > 0) $result = $segmentText . ' ' . $ribuan[$i] . ' ' . $result;
            }
            $tempAngka = floor($tempAngka / 1000);
            $i++;
        }
        
        return trim($result) . ' Rupiah';
=======
     * Cetak berdasarkan SPD ID
     */
    public function cetakBySpd($spdId)
    {
        $rincian = RincianBidang::with('spd', 'tempatTujuan', 'bendaharaPengeluaran', 'uangHarian')
            ->where('spd_id', $spdId)
            ->firstOrFail();
        
        $pdf = Pdf::loadView('admin.rincian-pdf', compact('rincian'));
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
                'bendahara' => $rincian->bendaharaPengeluaran ? [
                    'id' => $rincian->bendaharaPengeluaran->id_pegawai,
                    'nama' => $rincian->bendaharaPengeluaran->nama,
                    'nip' => $rincian->bendaharaPengeluaran->nip,
                    'jabatan' => $rincian->bendaharaPengeluaran->jabatan,
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
>>>>>>> db0c50f6a0cf3864408bbf4a141a91bc52fa8d2b
    }
}