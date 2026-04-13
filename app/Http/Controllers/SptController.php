<?php

namespace App\Http\Controllers;

use App\Models\SPT;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SPTController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // ... kode index tetap sama ...
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
        
        // Template nomor surat untuk ditampilkan di view
        $nomorSuratTemplate = '800.1.11.1/           /DPMPTSP/' . date('Y');
        
        return view('admin.spt-create', compact('penandaTangans', 'semuaPegawai', 'pegawaiData', 'nomorSuratTemplate'));
    }

    /**
     * Generate nomor surat lengkap dari input user
     * Format: 800.1.11.1/{nomor_urut}/DPMPTSP/{tahun}
     */
    private function generateNomorSurat($nomorUrut, $tahun = null)
    {
        if (!$tahun) {
            $tahun = date('Y');
        }
        
        // Format nomor surat dengan padding 3 digit (001, 002, dst)
        $nomorUrutFormatted = str_pad($nomorUrut, 3, '0', STR_PAD_LEFT);
        
        return "800.1.11.1/{$nomorUrutFormatted}/DPMPTSP/{$tahun}";
    }
    
    /**
     * Get next nomor urut untuk SPT
     */
    public function getNextNomorUrut($tahun = null)
    {
        if (!$tahun) {
            $tahun = date('Y');
        }
        
        // Cari nomor surat terakhir di tahun yang sama
        $lastSPT = SPT::whereYear('tanggal', $tahun)
            ->orderBy('id_spt', 'desc')
            ->first();
        
        if ($lastSPT && $lastSPT->nomor_surat) {
            // Ekstrak nomor urut dari nomor surat
            // Format: 800.1.11.1/XXX/DPMPTSP/YYYY
            preg_match('/800\.1\.11\.1\/(\d+)\/DPMPTSP\/\d{4}/', $lastSPT->nomor_surat, $matches);
            if (isset($matches[1])) {
                return (int)$matches[1] + 1;
            }
        }
        
        // Jika belum ada, mulai dari 1
        return 1;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'nomor_urut' => 'required|integer|min:1|max:999',
            'dasar' => 'required|array',
            'dasar.*' => 'required|string',
            'pegawai' => 'required|array',
            'pegawai.*' => 'required|exists:tb_pegawai,id_pegawai',
            'tujuan' => 'required|string',
            'tanggal' => 'required|date',
            'lokasi' => 'required|string|max:255',
            'penanda_tangan' => 'required|exists:tb_pegawai,id_pegawai'
        ], [
            'nomor_urut.required' => 'Nomor urut surat harus diisi',
            'nomor_urut.integer' => 'Nomor urut harus berupa angka',
            'nomor_urut.min' => 'Nomor urut minimal 1',
            'nomor_urut.max' => 'Nomor urut maksimal 999',
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
        
        // Generate nomor surat lengkap dari nomor urut dan tahun dari tanggal
        $tahun = date('Y', strtotime($request->tanggal));
        $nomorSurat = $this->generateNomorSurat($request->nomor_urut, $tahun);
        
        // Cek apakah nomor surat sudah ada
        $exists = SPT::where('nomor_surat', $nomorSurat)->exists();
        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Nomor surat dengan urutan {$request->nomor_urut} untuk tahun {$tahun} sudah ada. Gunakan nomor urut lain.");
        }
        
        // Simpan data
        try {
            $data = $validated;
            $data['nomor_surat'] = $nomorSurat;
            unset($data['nomor_urut']); // Hapus nomor_urut karena tidak ada di tabel
            
            SPT::create($data);
            
            return redirect()->route('spt.index')
                ->with('success', "Data SPT berhasil ditambahkan. Nomor Surat: {$nomorSurat}");
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
        // ... kode show tetap sama ...
        try {
            $spt = SPT::with('penandaTangan')->findOrFail($id);
            $pegawaiList = $spt->pegawai_list;
            $dasarList = $spt->dasar_list;
            
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
        // ... kode edit tetap sama dengan tambahan data nomor urut ...
        try {
            $spt = SPT::findOrFail($id);
            
            // Ekstrak nomor urut dari nomor surat untuk ditampilkan di form edit
            $nomorUrut = null;
            if ($spt->nomor_surat) {
                preg_match('/800\.1\.11\.1\/(\d+)\/DPMPTSP\/\d{4}/', $spt->nomor_surat, $matches);
                if (isset($matches[1])) {
                    $nomorUrut = (int)$matches[1];
                }
            }
            
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
            
            $nomorSuratTemplate = '800.1.11.1/           /DPMPTSP/' . date('Y', strtotime($spt->tanggal));
            
            return view('admin.spt-edit', compact('spt', 'penandaTangans', 'semuaPegawai', 'pegawaiData', 'nomorUrut', 'nomorSuratTemplate'));
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
                'nomor_urut' => 'required|integer|min:1|max:999',
                'dasar' => 'required|array',
                'dasar.*' => 'required|string',
                'pegawai' => 'required|array',
                'pegawai.*' => 'required|exists:tb_pegawai,id_pegawai',
                'tujuan' => 'required|string',
                'tanggal' => 'required|date',
                'lokasi' => 'required|string|max:255',
                'penanda_tangan' => 'required|exists:tb_pegawai,id_pegawai'
            ], [
                'nomor_urut.required' => 'Nomor urut surat harus diisi',
                'nomor_urut.integer' => 'Nomor urut harus berupa angka',
                'nomor_urut.min' => 'Nomor urut minimal 1',
                'nomor_urut.max' => 'Nomor urut maksimal 999',
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
            
            // Generate nomor surat baru dari nomor urut dan tahun dari tanggal
            $tahun = date('Y', strtotime($request->tanggal));
            $nomorSuratBaru = $this->generateNomorSurat($request->nomor_urut, $tahun);
            
            // Cek apakah nomor surat sudah ada (kecuali untuk data ini sendiri)
            $exists = SPT::where('nomor_surat', $nomorSuratBaru)
                ->where('id_spt', '!=', $id)
                ->exists();
            
            if ($exists) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Nomor surat dengan urutan {$request->nomor_urut} untuk tahun {$tahun} sudah ada. Gunakan nomor urut lain.");
            }
            
            // Update data
            $data = $validated;
            $data['nomor_surat'] = $nomorSuratBaru;
            unset($data['nomor_urut']);
            
            $spt->update($data);
            
            return redirect()->route('spt.index')
                ->with('success', "Data SPT berhasil diperbarui. Nomor Surat: {$nomorSuratBaru}");
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
        // ... kode destroy tetap sama ...
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
     * API: Get next nomor urut untuk SPT via API
     */
    public function apiGetNextNomorUrut(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $nextNomorUrut = $this->getNextNomorUrut($tahun);
        
        return response()->json([
            'success' => true,
            'nomor_urut' => $nextNomorUrut,
            'nomor_surat' => $this->generateNomorSurat($nextNomorUrut, $tahun)
        ]);
    }

    /**
     * Export data SPT ke Excel (SATU METHOD UNTUK SEMUA FILTER)
     */
    public function export(Request $request)
    {
        // ... kode export tetap sama ...
        try {
            $query = SPT::with('penandaTangan');
            
            // Filter berdasarkan search
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
            
            // Filter berdasarkan bulan
            if ($request->has('bulan') && $request->bulan != '') {
                $query->whereMonth('tanggal', $request->bulan);
            }
            
            // Filter berdasarkan tahun
            if ($request->has('tahun') && $request->tahun != '') {
                $query->whereYear('tanggal', $request->tahun);
            }
            
            // Filter berdasarkan penanda tangan
            if ($request->has('penanda_tangan') && $request->penanda_tangan != '') {
                $query->where('penanda_tangan', $request->penanda_tangan);
            }
            
            $spts = $query->orderBy('tanggal', 'desc')->get();
            
            if ($spts->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada data SPT untuk diexport.');
            }
            
            // Buat spreadsheet baru
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set judul sheet
            $sheet->setTitle('Data SPT');
            
            // Header kolom
            $headers = [
                'A1' => 'NO',
                'B1' => 'NOMOR SURAT',
                'C1' => 'DASAR',
                'D1' => 'PEGAWAI YANG DITUGASKAN',
                'E1' => 'TUJUAN',
                'F1' => 'TANGGAL',
                'G1' => 'LOKASI',
                'H1' => 'PENANDA TANGAN',
                'I1' => 'NIP PENANDA TANGAN',
                'J1' => 'JABATAN PENANDA TANGAN'
            ];
            
            // Apply header style
            foreach ($headers as $cell => $value) {
                $sheet->setCellValue($cell, $value);
                $sheet->getStyle($cell)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E0E0E0']],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ]);
            }
            
            // Isi data
            $row = 2;
            $no = 1;
            foreach ($spts as $spt) {
                // Format dasar menjadi string
                $dasarList = $spt->dasar_list;
                $dasarString = is_array($dasarList) ? implode("\n", $dasarList) : (string)$dasarList;
                
                // Format pegawai menjadi string
                $pegawaiList = $spt->pegawai_list;
                $pegawaiString = '';
                if ($pegawaiList && count($pegawaiList) > 0) {
                    $pegawaiNames = [];
                    foreach ($pegawaiList as $pegawai) {
                        $pegawaiNames[] = trim($pegawai->nama) . ($pegawai->nip ? ' (' . $pegawai->nip . ')' : '');
                    }
                    $pegawaiString = implode("\n", $pegawaiNames);
                }
                
                $sheet->setCellValue('A' . $row, $no);
                $sheet->setCellValue('B' . $row, $spt->nomor_surat);
                $sheet->setCellValue('C' . $row, $dasarString);
                $sheet->setCellValue('D' . $row, $pegawaiString);
                $sheet->setCellValue('E' . $row, $spt->tujuan);
                $sheet->setCellValue('F' . $row, date('d-m-Y', strtotime($spt->tanggal)));
                $sheet->setCellValue('G' . $row, $spt->lokasi);
                $sheet->setCellValue('H' . $row, $spt->penandaTangan ? $spt->penandaTangan->nama : '-');
                $sheet->setCellValue('I' . $row, $spt->penandaTangan ? $spt->penandaTangan->nip : '-');
                $sheet->setCellValue('J' . $row, $spt->penandaTangan ? $spt->penandaTangan->jabatan : '-');
                
                // Apply style untuk data
                $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    'alignment' => ['vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true]
                ]);
                
                // Set tinggi baris otomatis
                $sheet->getRowDimension($row)->setRowHeight(-1);
                
                $row++;
                $no++;
            }
            
            // Auto size untuk kolom
            foreach (range('A', 'J') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }
            
            // Set lebar khusus untuk kolom yang panjang
            $sheet->getColumnDimension('C')->setWidth(40);
            $sheet->getColumnDimension('D')->setWidth(45);
            $sheet->getColumnDimension('E')->setWidth(50);
            
            // Buat nama file berdasarkan filter yang dipilih
            $filterInfo = '';
            if ($request->has('bulan') && $request->bulan != '') {
                $namaBulan = $this->getNamaBulan($request->bulan);
                $filterInfo .= '_' . $namaBulan;
            }
            if ($request->has('tahun') && $request->tahun != '') {
                $filterInfo .= '_' . $request->tahun;
            }
            if ($request->has('search') && $request->search != '') {
                $filterInfo .= '_Filtered';
            }
            if ($request->has('penanda_tangan') && $request->penanda_tangan != '') {
                $filterInfo .= '_ByPenandaTangan';
            }
            if (empty($filterInfo)) {
                $filterInfo = '_Semua_Data';
            }
            
            $filename = 'Data_SPT' . $filterInfo . '_' . date('Y-m-d_His') . '.xlsx';
            $filename = $this->sanitizeFilename($filename);
            
            // Hapus buffer output yang mungkin ada
            if (ob_get_length()) {
                ob_end_clean();
            }
            
            // Set header untuk download
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Expires: 0');
            header('Pragma: public');
            
            // Tulis file ke output
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
            
        } catch (\Exception $e) {
            Log::error('Export Excel SPT Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal export data: ' . $e->getMessage());
        }
    }

    /**
     * Helper untuk mendapatkan nama bulan
     */
    private function getNamaBulan($bulan)
    {
        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        
        return $namaBulan[(int)$bulan] ?? 'Bulan_' . $bulan;
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
        // ... kode print tetap sama ...
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
        // ... kode previewPdf tetap sama ...
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
        // ... kode getPegawaiData tetap sama ...
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