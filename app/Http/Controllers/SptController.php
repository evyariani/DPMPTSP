<?php

namespace App\Http\Controllers;

use App\Models\SPT;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SPTController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = SPT::with('penandaTangan');
            
            // Search
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nomor_surat', 'like', "%{$search}%")
                      ->orWhere('tujuan', 'like', "%{$search}%")
                      ->orWhere('lokasi', 'like', "%{$search}%")
                      ->orWhereHas('penandaTangan', function ($pegawaiQuery) use ($search) {
                          $pegawaiQuery->where('nama', 'like', "%{$search}%")
                                       ->orWhere('nip', 'like', "%{$search}%");
                      });
                });
            }
            
            // Filter berdasarkan bulan/tahun
            if ($request->has('bulan') && $request->bulan != '') {
                $query->whereMonth('tanggal', $request->bulan);
            }
            
            if ($request->has('tahun') && $request->tahun != '') {
                $query->whereYear('tanggal', $request->tahun);
            }
            
            // Filter berdasarkan penanda tangan
            if ($request->has('penanda_tangan') && $request->penanda_tangan != '') {
                $query->where('penanda_tangan', $request->penanda_tangan);
            }
            
            // Order by id_spt descending (terbaru)
            $query->orderBy('id_spt', 'desc');
            
            // Paginate
            $spts = $query->paginate(10);
            
            // Ambil data pegawai untuk filter dropdown (hanya penanda tangan)
            $jabatanPenandaTangan = [
                'Kepala Dinas',
                'Sekretaris',
                'Kabid Data, Informasi dan Pengaduan',
                'Kabid Perizinan dan Non Perizinan Tertentu',
                'Kabid Perizinan dan Non Perizinan Jasa Usaha',
                'Kabid Penanaman Modal',
                'Kasubbag Perencanaan dan Pelaporan',
                'Kasubbag Umum dan Kepegawaian'
            ];
            
            $pegawais = Pegawai::whereIn('jabatan', $jabatanPenandaTangan)
                ->orderBy('nama')
                ->get();
            
        } catch (\Exception $e) {
            // Jika ada error, berikan data kosong
            $spts = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            $pegawais = collect([]);
        }
        
        return view('admin.spt', compact('spts', 'pegawais'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Daftar jabatan yang boleh menjadi penanda tangan
        $jabatanPenandaTangan = [
            'Kepala Dinas',
            'Sekretaris',
            'Kabid Data, Informasi dan Pengaduan',
            'Kabid Perizinan dan Non Perizinan Tertentu',
            'Kabid Perizinan dan Non Perizinan Jasa Usaha',
            'Kabid Penanaman Modal',
            'Kasubbag Perencanaan dan Pelaporan',
            'Kasubbag Umum dan Kepegawaian'
        ];
        
        // 1. Ambil pegawai untuk PENANDA TANGAN (hanya jabatan tertentu)
        $penandaTangans = Pegawai::whereIn('jabatan', $jabatanPenandaTangan)
            ->orderBy('nama')
            ->get();
        
        // 2. Ambil SEMUA pegawai untuk dropdown PEGAWAI YANG DITUGASKAN
        $semuaPegawai = Pegawai::orderBy('nama')->get();
        
        // Siapkan data pegawai untuk JavaScript (format array asosiatif)
        $pegawaiData = [];
        foreach ($semuaPegawai as $pegawai) {
            $initial = $pegawai->nama ? strtoupper(substr($pegawai->nama, 0, 1)) : '-';
            $pegawaiData[$pegawai->id_pegawai] = [
                'nama' => $pegawai->nama,
                'nip' => $pegawai->nip ?? '-',
                'pangkat' => $pegawai->pangkat ?? '-',
                'gol' => $pegawai->gol ?? '-',
                'jabatan' => $pegawai->jabatan ?? '-',
                'initial' => $initial
            ];
        }
        
        return view('admin.spt-create', compact('penandaTangans', 'semuaPegawai', 'pegawaiData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'nomor_surat' => 'required|string|max:100',
            'dasar' => 'required|array',
            'dasar.*' => 'required|string',
            'pegawai' => 'required|array',
            'pegawai.*' => 'required|exists:tb_pegawai,id_pegawai',
            'tujuan' => 'required|string',
            'tanggal' => 'required|date',
            'lokasi' => 'required|string|max:255',
            'penanda_tangan' => 'required|exists:tb_pegawai,id_pegawai'
        ], [
            'nomor_surat.required' => 'Nomor surat harus diisi',
            'nomor_surat.max' => 'Nomor surat maksimal 100 karakter',
            'dasar.required' => 'Dasar harus diisi minimal 1',
            'dasar.array' => 'Format dasar tidak valid',
            'dasar.*.required' => 'Setiap dasar harus diisi',
            'pegawai.required' => 'Pegawai harus dipilih minimal 1',
            'pegawai.array' => 'Format pegawai tidak valid',
            'pegawai.*.exists' => 'Pegawai tidak valid',
            'tujuan.required' => 'Tujuan harus diisi',
            'tanggal.required' => 'Tanggal harus diisi',
            'tanggal.date' => 'Format tanggal tidak valid',
            'lokasi.required' => 'Lokasi harus diisi',
            'lokasi.max' => 'Lokasi maksimal 255 karakter',
            'penanda_tangan.required' => 'Penanda tangan harus dipilih',
            'penanda_tangan.exists' => 'Penanda tangan tidak valid'
        ]);
        
        // Simpan data
        try {
            SPT::create($validated);
            
            return redirect()->route('spt.index')
                ->with('success', 'Data SPT berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data SPT: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $spt = SPT::with('penandaTangan')->findOrFail($id);
            $pegawaiList = $spt->pegawai_list; // Menggunakan accessor
            $dasarList = $spt->dasar_list; // Menggunakan accessor
            
            return view('admin.spt-show', compact('spt', 'pegawaiList', 'dasarList'));
        } catch (\Exception $e) {
            return redirect()->route('spt.index')
                ->with('error', 'Data SPT tidak ditemukan.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $spt = SPT::findOrFail($id);
            
            // Daftar jabatan yang boleh menjadi penanda tangan
            $jabatanPenandaTangan = [
                'Kepala Dinas',
                'Sekretaris',
                'Kabid Data, Informasi dan Pengaduan',
                'Kabid Perizinan dan Non Perizinan Tertentu',
                'Kabid Perizinan dan Non Perizinan Jasa Usaha',
                'Kabid Penanaman Modal',
                'Kasubbag Perencanaan dan Pelaporan',
                'Kasubbag Umum dan Kepegawaian'
            ];
            
            // 1. Ambil pegawai untuk PENANDA TANGAN (hanya jabatan tertentu)
            $penandaTangans = Pegawai::whereIn('jabatan', $jabatanPenandaTangan)
                ->orderBy('nama')
                ->get();
            
            // 2. Ambil SEMUA pegawai untuk dropdown PEGAWAI YANG DITUGASKAN
            $semuaPegawai = Pegawai::orderBy('nama')->get();
            
            // Siapkan data untuk JavaScript
            $pegawaiData = [];
            foreach ($semuaPegawai as $pegawai) {
                $initial = $pegawai->nama ? strtoupper(substr($pegawai->nama, 0, 1)) : '-';
                $pegawaiData[$pegawai->id_pegawai] = [
                    'nama' => $pegawai->nama,
                    'nip' => $pegawai->nip ?? '-',
                    'pangkat' => $pegawai->pangkat ?? '-',
                    'gol' => $pegawai->gol ?? '-',
                    'jabatan' => $pegawai->jabatan ?? '-',
                    'initial' => $initial
                ];
            }
            
            return view('admin.spt-edit', compact('spt', 'penandaTangans', 'semuaPegawai', 'pegawaiData'));
        } catch (\Exception $e) {
            return redirect()->route('spt.index')
                ->with('error', 'Data SPT tidak ditemukan.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $spt = SPT::findOrFail($id);
            
            $validated = $request->validate([
                'nomor_surat' => 'required|string|max:100',
                'dasar' => 'required|array',
                'dasar.*' => 'required|string',
                'pegawai' => 'required|array',
                'pegawai.*' => 'required|exists:tb_pegawai,id_pegawai',
                'tujuan' => 'required|string',
                'tanggal' => 'required|date',
                'lokasi' => 'required|string|max:255',
                'penanda_tangan' => 'required|exists:tb_pegawai,id_pegawai'
            ], [
                'nomor_surat.required' => 'Nomor surat harus diisi',
                'nomor_surat.max' => 'Nomor surat maksimal 100 karakter',
                'dasar.required' => 'Dasar harus diisi minimal 1',
                'dasar.array' => 'Format dasar tidak valid',
                'dasar.*.required' => 'Setiap dasar harus diisi',
                'pegawai.required' => 'Pegawai harus dipilih minimal 1',
                'pegawai.array' => 'Format pegawai tidak valid',
                'pegawai.*.exists' => 'Pegawai tidak valid',
                'tujuan.required' => 'Tujuan harus diisi',
                'tanggal.required' => 'Tanggal harus diisi',
                'tanggal.date' => 'Format tanggal tidak valid',
                'lokasi.required' => 'Lokasi harus diisi',
                'lokasi.max' => 'Lokasi maksimal 255 karakter',
                'penanda_tangan.required' => 'Penanda tangan harus dipilih',
                'penanda_tangan.exists' => 'Penanda tangan tidak valid'
            ]);
            
            $spt->update($validated);
            
            return redirect()->route('spt.index')
                ->with('success', 'Data SPT berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data SPT: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $spt = SPT::findOrFail($id);
            $nomorSurat = $spt->nomor_surat;
            $spt->delete();
            
            // Jika request AJAX
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Data SPT dengan nomor '{$nomorSurat}' berhasil dihapus."
                ]);
            }
            
            return redirect()->route('spt.index')
                ->with('success', "Data SPT dengan nomor '{$nomorSurat}' berhasil dihapus.");
        } catch (\Exception $e) {
            // Jika request AJAX
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus data SPT: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('spt.index')
                ->with('error', 'Gagal menghapus data SPT: ' . $e->getMessage());
        }
    }

    /**
     * Export data SPT (contoh fitur tambahan)
     */
    public function export(Request $request)
    {
        try {
            $query = SPT::with('penandaTangan');
            
            // Filter berdasarkan bulan/tahun jika ada
            if ($request->has('bulan') && $request->bulan != '') {
                $query->whereMonth('tanggal', $request->bulan);
            }
            
            if ($request->has('tahun') && $request->tahun != '') {
                $query->whereYear('tanggal', $request->tahun);
            }
            
            $spts = $query->orderBy('tanggal', 'desc')->get();
            
            // Logic export ke Excel/PDF disini
            // ...
            
            return redirect()->back()->with('success', 'Data berhasil diexport.');
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal export data: ' . $e->getMessage());
        }
    }

    /**
     * Helper untuk membersihkan nama file dari karakter ilegal
     */
    private function sanitizeFilename($filename)
    {
        // Daftar karakter yang tidak diperbolehkan dalam nama file
        // Ganti dengan karakter dash (-)
        $filename = str_replace(
            ['/', '\\', ':', '*', '?', '"', '<', '>', '|', ' ', '(', ')', '[', ']', '{', '}', '!', '@', '#', '$', '%', '^', '&', '=', '+', ',', ';', "'"], 
            '-', 
            $filename
        );
        
        // Hapus karakter selain huruf, angka, titik, dan dash
        $filename = preg_replace('/[^a-zA-Z0-9.-]/', '', $filename);
        
        // Hapus dash berulang (ganti dengan single dash)
        $filename = preg_replace('/-+/', '-', $filename);
        
        // Hapus dash di awal dan akhir
        $filename = trim($filename, '-');
        
        // Jika hasil kosong, beri nama default
        if (empty($filename)) {
            $filename = 'spt';
        }
        
        return $filename;
    }

    /**
     * Helper untuk menghasilkan nama file PDF yang aman
     */
    private function generatePdfFilename($spt, $prefix = 'SPT-', $suffix = '.pdf')
    {
        // Ambil nomor surat dan bersihkan
        $nomorBersih = $this->sanitizeFilename($spt->nomor_surat);
        
        // Gabungkan dengan ID untuk memastikan unique
        return $prefix . $nomorBersih . '-' . $spt->id_spt . $suffix;
    }

    /**
     * Print SPT (cetak PDF)
     */
    public function print($id)
    {
        try {
            $spt = SPT::with('penandaTangan')->findOrFail($id);
            $pegawaiList = $spt->pegawai_list;
            $dasarList = $spt->dasar_list;
            
            // Generate PDF
            $pdf = Pdf::loadView('admin.spt-pdf', compact('spt', 'pegawaiList', 'dasarList'));
            
            // Set ukuran kertas (opsional)
            $pdf->setPaper('A4', 'portrait');
            
            // Generate nama file yang aman
            $namaFile = $this->generatePdfFilename($spt, 'SPT-', '.pdf');
            
            // Download PDF
            return $pdf->download($namaFile);
            
        } catch (\Exception $e) {
            return redirect()->route('spt.index')
                ->with('error', 'Gagal mencetak SPT: ' . $e->getMessage());
        }
    }

    /**
     * Preview SPT PDF di browser
     */
    public function previewPdf($id)
    {
        try {
            $spt = SPT::with('penandaTangan')->findOrFail($id);
            $pegawaiList = $spt->pegawai_list;
            $dasarList = $spt->dasar_list;
            
            $pdf = Pdf::loadView('admin.spt-pdf', compact('spt', 'pegawaiList', 'dasarList'));
            $pdf->setPaper('A4', 'portrait');
            
            // Generate nama file yang aman
            $namaFile = $this->generatePdfFilename($spt, 'SPT-', '.pdf');
            
            // Tampilkan di browser
            return $pdf->stream($namaFile);
            
        } catch (\Exception $e) {
            return redirect()->route('spt.index')
                ->with('error', 'Gagal preview PDF: ' . $e->getMessage());
        }
    }

    /**
     * Get data pegawai untuk API (contoh fitur tambahan)
     */
    public function getPegawaiData($id)
    {
        try {
            $pegawai = Pegawai::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'nama' => $pegawai->nama,
                    'nip' => $pegawai->nip,
                    'pangkat' => $pegawai->pangkat,
                    'gol' => $pegawai->gol,
                    'jabatan' => $pegawai->jabatan
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data pegawai tidak ditemukan'
            ], 404);
        }
    }
}