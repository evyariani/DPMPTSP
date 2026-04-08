<?php

namespace App\Http\Controllers;

use App\Models\SPD;
use App\Models\Pegawai;
use App\Models\Daerah;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SPDController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = SPD::with(['penggunaAnggaran', 'tempatTujuan']);

            // Search
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nomor_surat', 'like', "%{$search}%")
                      ->orWhere('maksud_perjadin', 'like', "%{$search}%")
                      ->orWhere('skpd', 'like', "%{$search}%")
                      ->orWhere('kode_rek', 'like', "%{$search}%")
                      ->orWhereHas('penggunaAnggaran', function ($pegawaiQuery) use ($search) {
                          $pegawaiQuery->where('nama', 'like', "%{$search}%")
                                       ->orWhere('nip', 'like', "%{$search}%");
                      })
                      ->orWhereHas('tempatTujuan', function ($daerahQuery) use ($search) {
                          $daerahQuery->where('nama', 'like', "%{$search}%");
                      });
                });
            }

            // Filter berdasarkan bulan/tahun berangkat
            if ($request->has('bulan') && $request->bulan != '') {
                $query->whereMonth('tanggal_berangkat', $request->bulan);
            }

            if ($request->has('tahun') && $request->tahun != '') {
                $query->whereYear('tanggal_berangkat', $request->tahun);
            }

            // Filter berdasarkan pengguna anggaran
            if ($request->has('pengguna_anggaran') && $request->pengguna_anggaran != '') {
                $query->where('pengguna_anggaran', $request->pengguna_anggaran);
            }

            // Filter berdasarkan SKPD
            if ($request->has('skpd') && $request->skpd != '') {
                $query->where('skpd', $request->skpd);
            }

            // Filter berdasarkan alat transportasi
            if ($request->has('alat_transportasi') && $request->alat_transportasi != '') {
                $query->where('alat_transportasi', $request->alat_transportasi);
            }

            // Order by id_spd descending (terbaru)
            $query->orderBy('id_spd', 'desc');

            // Paginate
            $spds = $query->paginate(10);

            // Ambil data pegawai untuk filter dropdown (pengguna anggaran)
            $pegawais = Pegawai::orderBy('nama')->get();

            // Ambil data daerah untuk filter
            $daerahs = Daerah::orderBy('nama')->get();

            // Daftar SKPD untuk filter
            $skpdList = SPD::select('skpd')->distinct()->whereNotNull('skpd')->pluck('skpd');

            // Daftar alat transportasi untuk filter
            $alatTransportasiList = [
                'transportasi_darat' => 'Transportasi Darat',
                'transportasi_udara' => 'Transportasi Udara',
                'transportasi_darat_udara' => 'Transportasi Darat & Udara',
                'angkutan_darat' => 'Angkutan Darat',
                'kendaraan_dinas' => 'Kendaraan Dinas',
                'angkutan_umum' => 'Angkutan Umum'
            ];

        } catch (\Exception $e) {
            // Jika ada error, berikan data kosong
            $spds = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            $pegawais = collect([]);
            $daerahs = collect([]);
            $skpdList = collect([]);
            $alatTransportasiList = [];
        }

        return view('admin.spd', compact('spds', 'pegawais', 'daerahs', 'skpdList', 'alatTransportasiList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 1. Ambil semua pegawai untuk dropdown pengguna anggaran
        $pegawais = Pegawai::orderBy('nama')->get();

        // 2. Ambil semua daerah untuk dropdown tempat tujuan
        $daerahs = Daerah::orderBy('nama')->get();

        // Daftar alat transportasi
        $alatTransportasiList = [
            'transportasi_darat' => 'Transportasi Darat',
            'transportasi_udara' => 'Transportasi Udara',
            'transportasi_darat_udara' => 'Transportasi Darat & Udara',
            'angkutan_darat' => 'Angkutan Darat',
            'kendaraan_dinas' => 'Kendaraan Dinas',
            'angkutan_umum' => 'Angkutan Umum'
        ];

        // Siapkan data pegawai untuk JavaScript (format array asosiatif)
        $pegawaiData = [];
        foreach ($pegawais as $pegawai) {
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

        // Siapkan data daerah untuk JavaScript
        $daerahData = [];
        foreach ($daerahs as $daerah) {
            $daerahData[$daerah->id] = [
                'nama' => $daerah->nama,
                'kode_daerah' => $daerah->kode_daerah ?? '-'
            ];
        }

        return view('admin.spd-create', compact('pegawais', 'daerahs', 'pegawaiData', 'daerahData', 'alatTransportasiList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'nomor_surat' => 'required|string|max:100',
            'pengguna_anggaran' => 'required|exists:tb_pegawai,id_pegawai',
            'maksud_perjadin' => 'required|string',
            'alat_transportasi' => 'required|in:transportasi_darat,transportasi_udara,transportasi_darat_udara,angkutan_darat,kendaraan_dinas,angkutan_umum',
            'tempat_berangkat' => 'required|string|max:255',
            'tempat_tujuan' => 'required|exists:tb_daerah,id',
            'tanggal_berangkat' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_berangkat',
            'lama_perjadin' => 'nullable|integer|min:1',
            'skpd' => 'nullable|string|max:100',
            'kode_rek' => 'nullable|string|max:50',
            'keterangan' => 'nullable|string',
            'tempat_dikeluarkan' => 'nullable|string|max:100',
            'tanggal_dikeluarkan' => 'nullable|date'
        ], [
            'nomor_surat.required' => 'Nomor surat harus diisi',
            'nomor_surat.max' => 'Nomor surat maksimal 100 karakter',
            'pengguna_anggaran.required' => 'Pengguna anggaran harus dipilih',
            'pengguna_anggaran.exists' => 'Pengguna anggaran tidak valid',
            'maksud_perjadin.required' => 'Maksud perjalanan dinas harus diisi',
            'alat_transportasi.required' => 'Alat transportasi harus dipilih',
            'alat_transportasi.in' => 'Pilihan alat transportasi tidak valid',
            'tempat_berangkat.required' => 'Tempat berangkat harus diisi',
            'tempat_berangkat.max' => 'Tempat berangkat maksimal 255 karakter',
            'tempat_tujuan.required' => 'Tempat tujuan harus dipilih',
            'tempat_tujuan.exists' => 'Tempat tujuan tidak valid',
            'tanggal_berangkat.required' => 'Tanggal berangkat harus diisi',
            'tanggal_berangkat.date' => 'Format tanggal berangkat tidak valid',
            'tanggal_kembali.required' => 'Tanggal kembali harus diisi',
            'tanggal_kembali.date' => 'Format tanggal kembali tidak valid',
            'tanggal_kembali.after_or_equal' => 'Tanggal kembali harus setelah atau sama dengan tanggal berangkat',
            'lama_perjadin.integer' => 'Lama perjalanan dinas harus berupa angka',
            'lama_perjadin.min' => 'Lama perjalanan dinas minimal 1 hari',
            'skpd.max' => 'SKPD maksimal 100 karakter',
            'kode_rek.max' => 'Kode rekening maksimal 50 karakter',
            'tempat_dikeluarkan.max' => 'Tempat dikeluarkan maksimal 100 karakter',
            'tanggal_dikeluarkan.date' => 'Format tanggal dikeluarkan tidak valid'
        ]);

        // Hitung lama perjadin jika tidak diisi
        if (empty($validated['lama_perjadin'])) {
            $start = new \DateTime($validated['tanggal_berangkat']);
            $end = new \DateTime($validated['tanggal_kembali']);
            $interval = $start->diff($end);
            $validated['lama_perjadin'] = $interval->days + 1; // +1 karena termasuk hari berangkat
        }

        // Simpan data
        try {
            SPD::create($validated);

            return redirect()->route('spd.index')
                ->with('success', 'Data SPD berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data SPD: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $spd = SPD::with(['penggunaAnggaran', 'tempatTujuan'])->findOrFail($id);

            return view('admin.spd-show', compact('spd'));
        } catch (\Exception $e) {
            return redirect()->route('spd.index')
                ->with('error', 'Data SPD tidak ditemukan.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $spd = SPD::findOrFail($id);

            // 1. Ambil semua pegawai untuk dropdown pengguna anggaran
            $pegawais = Pegawai::orderBy('nama')->get();

            // 2. Ambil semua daerah untuk dropdown tempat tujuan
            $daerahs = Daerah::orderBy('nama')->get();

            // Daftar alat transportasi
            $alatTransportasiList = [
                'transportasi_darat' => 'Transportasi Darat',
                'transportasi_udara' => 'Transportasi Udara',
                'transportasi_darat_udara' => 'Transportasi Darat & Udara',
                'angkutan_darat' => 'Angkutan Darat',
                'kendaraan_dinas' => 'Kendaraan Dinas',
                'angkutan_umum' => 'Angkutan Umum'
            ];

            // Siapkan data pegawai untuk JavaScript
            $pegawaiData = [];
            foreach ($pegawais as $pegawai) {
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

            // Siapkan data daerah untuk JavaScript
            $daerahData = [];
            foreach ($daerahs as $daerah) {
                $daerahData[$daerah->id] = [
                    'nama' => $daerah->nama,
                    'kode_daerah' => $daerah->kode_daerah ?? '-'
                ];
            }

            return view('admin.spd-edit', compact('spd', 'pegawais', 'daerahs', 'pegawaiData', 'daerahData', 'alatTransportasiList'));
        } catch (\Exception $e) {
            return redirect()->route('spd.index')
                ->with('error', 'Data SPD tidak ditemukan.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $spd = SPD::findOrFail($id);

            $validated = $request->validate([
                'nomor_surat' => 'required|string|max:100',
                'pengguna_anggaran' => 'required|exists:tb_pegawai,id_pegawai',
                'maksud_perjadin' => 'required|string',
                'alat_transportasi' => 'required|in:transportasi_darat,transportasi_udara,transportasi_darat_udara,angkutan_darat,kendaraan_dinas,angkutan_umum',
                'tempat_berangkat' => 'required|string|max:255',
                'tempat_tujuan' => 'required|exists:tb_daerah,id',
                'tanggal_berangkat' => 'required|date',
                'tanggal_kembali' => 'required|date|after_or_equal:tanggal_berangkat',
                'lama_perjadin' => 'nullable|integer|min:1',
                'skpd' => 'nullable|string|max:100',
                'kode_rek' => 'nullable|string|max:50',
                'keterangan' => 'nullable|string',
                'tempat_dikeluarkan' => 'nullable|string|max:100',
                'tanggal_dikeluarkan' => 'nullable|date'
            ], [
                'nomor_surat.required' => 'Nomor surat harus diisi',
                'nomor_surat.max' => 'Nomor surat maksimal 100 karakter',
                'pengguna_anggaran.required' => 'Pengguna anggaran harus dipilih',
                'pengguna_anggaran.exists' => 'Pengguna anggaran tidak valid',
                'maksud_perjadin.required' => 'Maksud perjalanan dinas harus diisi',
                'alat_transportasi.required' => 'Alat transportasi harus dipilih',
                'alat_transportasi.in' => 'Pilihan alat transportasi tidak valid',
                'tempat_berangkat.required' => 'Tempat berangkat harus diisi',
                'tempat_berangkat.max' => 'Tempat berangkat maksimal 255 karakter',
                'tempat_tujuan.required' => 'Tempat tujuan harus dipilih',
                'tempat_tujuan.exists' => 'Tempat tujuan tidak valid',
                'tanggal_berangkat.required' => 'Tanggal berangkat harus diisi',
                'tanggal_berangkat.date' => 'Format tanggal berangkat tidak valid',
                'tanggal_kembali.required' => 'Tanggal kembali harus diisi',
                'tanggal_kembali.date' => 'Format tanggal kembali tidak valid',
                'tanggal_kembali.after_or_equal' => 'Tanggal kembali harus setelah atau sama dengan tanggal berangkat',
                'lama_perjadin.integer' => 'Lama perjalanan dinas harus berupa angka',
                'lama_perjadin.min' => 'Lama perjalanan dinas minimal 1 hari',
                'skpd.max' => 'SKPD maksimal 100 karakter',
                'kode_rek.max' => 'Kode rekening maksimal 50 karakter',
                'tempat_dikeluarkan.max' => 'Tempat dikeluarkan maksimal 100 karakter',
                'tanggal_dikeluarkan.date' => 'Format tanggal dikeluarkan tidak valid'
            ]);

            // Hitung lama perjadin jika tidak diisi
            if (empty($validated['lama_perjadin'])) {
                $start = new \DateTime($validated['tanggal_berangkat']);
                $end = new \DateTime($validated['tanggal_kembali']);
                $interval = $start->diff($end);
                $validated['lama_perjadin'] = $interval->days + 1;
            }

            $spd->update($validated);

            return redirect()->route('spd.index')
                ->with('success', 'Data SPD berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data SPD: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $spd = SPD::findOrFail($id);
            $nomorSurat = $spd->nomor_surat;
            $spd->delete();

            // Jika request AJAX
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Data SPD dengan nomor '{$nomorSurat}' berhasil dihapus."
                ]);
            }

            return redirect()->route('spd.index')
                ->with('success', "Data SPD dengan nomor '{$nomorSurat}' berhasil dihapus.");
        } catch (\Exception $e) {
            // Jika request AJAX
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus data SPD: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('spd.index')
                ->with('error', 'Gagal menghapus data SPD: ' . $e->getMessage());
        }
    }

    /**
     * Print SPD (cetak PDF)
     */
    public function print($id)
    {
        try {
            $spd = SPD::with(['penggunaAnggaran', 'tempatTujuan'])->findOrFail($id);

            // Generate PDF
            $pdf = Pdf::loadView('admin.spd-pdf', compact('spd'));

            // Set ukuran kertas
            $pdf->setPaper('A4', 'portrait');

            // Generate nama file yang aman
            $namaFile = $this->generatePdfFilename($spd, 'SPD-', '.pdf');

            // Download PDF
            return $pdf->download($namaFile);

        } catch (\Exception $e) {
            return redirect()->route('spd.index')
                ->with('error', 'Gagal mencetak SPD: ' . $e->getMessage());
        }
    }

    /**
     * Preview SPD PDF di browser
     */
    public function previewPdf($id)
    {
        try {
            $spd = SPD::with(['penggunaAnggaran', 'tempatTujuan'])->findOrFail($id);

            $pdf = Pdf::loadView('admin.spd-pdf', compact('spd'));
            $pdf->setPaper('A4', 'portrait');

            // Generate nama file yang aman
            $namaFile = $this->generatePdfFilename($spd, 'SPD-', '.pdf');

            // Tampilkan di browser
            return $pdf->stream($namaFile);

        } catch (\Exception $e) {
            return redirect()->route('spd.index')
                ->with('error', 'Gagal preview PDF: ' . $e->getMessage());
        }
    }

    /**
     * Export data SPD (contoh fitur tambahan)
     */
    public function export(Request $request)
    {
        try {
            $query = SPD::with(['penggunaAnggaran', 'tempatTujuan']);

            // Filter berdasarkan bulan/tahun jika ada
            if ($request->has('bulan') && $request->bulan != '') {
                $query->whereMonth('tanggal_berangkat', $request->bulan);
            }

            if ($request->has('tahun') && $request->tahun != '') {
                $query->whereYear('tanggal_berangkat', $request->tahun);
            }

            $spds = $query->orderBy('tanggal_berangkat', 'desc')->get();

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
        $filename = str_replace(
            ['/', '\\', ':', '*', '?', '"', '<', '>', '|', ' ', '(', ')', '[', ']', '{', '}', '!', '@', '#', '$', '%', '^', '&', '=', '+', ',', ';', "'"],
            '-',
            $filename
        );

        // Hapus karakter selain huruf, angka, titik, dan dash
        $filename = preg_replace('/[^a-zA-Z0-9.-]/', '', $filename);

        // Hapus dash berulang
        $filename = preg_replace('/-+/', '-', $filename);

        // Hapus dash di awal dan akhir
        $filename = trim($filename, '-');

        // Jika hasil kosong, beri nama default
        if (empty($filename)) {
            $filename = 'spd';
        }

        return $filename;
    }

    /**
     * Helper untuk menghasilkan nama file PDF yang aman
     */
    private function generatePdfFilename($spd, $prefix = 'SPD-', $suffix = '.pdf')
    {
        $nomorBersih = $this->sanitizeFilename($spd->nomor_surat);
        return $prefix . $nomorBersih . '-' . $spd->id_spd . $suffix;
    }

    /**
     * Hitung otomatis lama perjadin berdasarkan tanggal berangkat dan kembali
     */
    public function calculateLamaPerjadin(Request $request)
    {
        try {
            $request->validate([
                'tanggal_berangkat' => 'required|date',
                'tanggal_kembali' => 'required|date'
            ]);

            $start = new \DateTime($request->tanggal_berangkat);
            $end = new \DateTime($request->tanggal_kembali);
            $interval = $start->diff($end);
            $lama = $interval->days + 1; // +1 karena termasuk hari berangkat

            return response()->json([
                'success' => true,
                'lama_perjadin' => $lama
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghitung lama perjalanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get data pegawai untuk API
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

    /**
     * Get data daerah untuk API
     */
    public function getDaerahData($id)
    {
        try {
            $daerah = Daerah::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'nama' => $daerah->nama,
                    'kode_daerah' => $daerah->kode_daerah ?? '-'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data daerah tidak ditemukan'
            ], 404);
        }
    }
}

