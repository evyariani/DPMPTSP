<?php

namespace App\Http\Controllers;

use App\Models\RincianBidang;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Barryvdh\DomPDF\Facade\Pdf;

class RincianBidangController extends Controller
{
    public function index()
    {
        try {
            $rincian = RincianBidang::latest()->paginate(10);
        } catch (\Exception $e) {
            $rincian = new LengthAwarePaginator([], 0, 10);
        }

        return view('admin.rincian-bidang', compact('rincian'));
    }

    public function create()
    {
        // Untuk create manual, kita tetap kirim data pegawai (opsional)
        $pegawai = Pegawai::all();
        return view('admin.rincian-create', compact('pegawai'));
    }

    public function store(Request $request)
    {
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
    }

    public function show($id)
    {
        $rincian = RincianBidang::findOrFail($id);
        return view('admin.rincian-detail', compact('rincian'));
    }

    public function edit($id)
    {
        $rincian = RincianBidang::findOrFail($id);
        $pegawai = Pegawai::all();
        return view('admin.rincian-edit', compact('rincian', 'pegawai'));
    }

    public function update(Request $request, $id)
    {
        $rincian = RincianBidang::findOrFail($id);

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
    }

    /**
     * Preview PDF Rincian Biaya di Browser (opsional)
     */
    public function previewPdf($id)
    {
        $rincian = RincianBidang::findOrFail($id);
        
        $pdf = Pdf::loadView('admin.rincian-cetak', compact('rincian'));
        $pdf->setPaper('A4', 'portrait');
        
        $namaFile = 'Rincian_Biaya_' . $rincian->nomor . '.pdf';
        $namaFile = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $namaFile);
        
        // Tampilkan di browser
        return $pdf->stream($namaFile);
    }

    /**
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
    }
}