<?php

namespace App\Http\Controllers;

use App\Models\SPT;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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
        try {
            $query = SPT::with('penandaTangan');
            
            // Search
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nomor_surat', 'like', "%{$search}%")
                      ->orWhere('tujuan', 'like', "%{$search}%")
                      ->orWhere('lokasi', 'like', "%{$search}%")
                      ->orWhere('penanda_tangan_nama', 'like', "%{$search}%");
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
        
        $penandaTangans = Pegawai::whereIn('jabatan', $jabatanPenandaTangan)
            ->orderBy('nama')
            ->get();
        
        $semuaPegawai = Pegawai::orderBy('nama')->get();
        
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
        
        $nomorSuratTemplate = '800.1.11.1/           /DPMPTSP/' . date('Y');
        
        return view('admin.spt-create', compact('penandaTangans', 'semuaPegawai', 'pegawaiData', 'nomorSuratTemplate'));
    }

    private function generateNomorSurat($nomorUrut, $tahun = null)
    {
        if (!$tahun) {
            $tahun = date('Y');
        }
        
        $nomorUrutFormatted = str_pad($nomorUrut, 3, '0', STR_PAD_LEFT);
        
        return "800.1.11.1/{$nomorUrutFormatted}/DPMPTSP/{$tahun}";
    }
    
    public function getNextNomorUrut($tahun = null)
    {
        if (!$tahun) {
            $tahun = date('Y');
        }
        
        $lastSPT = SPT::whereYear('tanggal', $tahun)
            ->orderBy('id_spt', 'desc')
            ->first();
        
        if ($lastSPT && $lastSPT->nomor_surat) {
            preg_match('/800\.1\.11\.1\/(\d+)\/DPMPTSP\/\d{4}/', $lastSPT->nomor_surat, $matches);
            if (isset($matches[1])) {
                return (int)$matches[1] + 1;
            }
        }
        
        return 1;
    }

    public function store(Request $request)
    {
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
        ]);
        
        $tahun = date('Y', strtotime($request->tanggal));
        $nomorSurat = $this->generateNomorSurat($request->nomor_urut, $tahun);
        
        $exists = SPT::where('nomor_surat', $nomorSurat)->exists();
        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Nomor surat dengan urutan {$request->nomor_urut} untuk tahun {$tahun} sudah ada.");
        }
        
        try {
            DB::beginTransaction();
            
            $data = $validated;
            $data['nomor_surat'] = $nomorSurat;
            unset($data['nomor_urut']);
            
            $spt = SPT::create($data);
            
            // ========== OTOMATIS BUAT SPD DARI SPT ==========
            $spdController = new SPDController();
            $spd = $spdController->createSpdFromSpt($spt);
            
            // ========== OTOMATIS BUAT LHPD DARI SPT ==========
            $lhpdController = new LhpdController();
            $lhpd = $lhpdController->createLhpdFromSpt($spt);
            
            DB::commit();
            
            $message = "Data SPT berhasil ditambahkan. Nomor Surat: {$nomorSurat}";
            if ($spd) {
                $message .= " SPD juga telah dibuat otomatis.";
            }
            if ($lhpd) {
                $message .= " LHPD juga telah dibuat otomatis.";
            }
            
            return redirect()->route('spt.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan SPT: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data SPT: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $spt = SPT::findOrFail($id);
            $pegawaiList = $spt->pegawai_list_from_snapshot;
            $dasarList = $spt->dasar_list;
            $penandaTangan = $spt->penanda_tangan_snapshot;
            
            return view('admin.spt-show', compact('spt', 'pegawaiList', 'dasarList', 'penandaTangan'));
        } catch (\Exception $e) {
            return redirect()->route('spt.index')
                ->with('error', 'Data SPT tidak ditemukan.');
        }
    }

    public function edit($id)
    {
        try {
            $spt = SPT::findOrFail($id);
            
            $nomorUrut = null;
            if ($spt->nomor_surat) {
                preg_match('/800\.1\.11\.1\/(\d+)\/DPMPTSP\/\d{4}/', $spt->nomor_surat, $matches);
                if (isset($matches[1])) {
                    $nomorUrut = (int)$matches[1];
                }
            }
            
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
            
            $penandaTangans = Pegawai::whereIn('jabatan', $jabatanPenandaTangan)
                ->orderBy('nama')
                ->get();
            
            $semuaPegawai = Pegawai::orderBy('nama')->get();
            
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
            ]);
            
            $tahun = date('Y', strtotime($request->tanggal));
            $nomorSuratBaru = $this->generateNomorSurat($request->nomor_urut, $tahun);
            
            $exists = SPT::where('nomor_surat', $nomorSuratBaru)
                ->where('id_spt', '!=', $id)
                ->exists();
            
            if ($exists) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Nomor surat dengan urutan {$request->nomor_urut} untuk tahun {$tahun} sudah ada.");
            }
            
            DB::beginTransaction();
            
            $data = $validated;
            $data['nomor_surat'] = $nomorSuratBaru;
            unset($data['nomor_urut']);
            
            $spt->update($data);
            
            $spdController = new SPDController();
            $existingSpd = \App\Models\SPD::where('spt_id', $spt->id_spt)->first();
            
            if ($existingSpd) {
                $existingSpd->update([
                    'maksud_perjadin' => $spt->tujuan,
                    'tanggal_berangkat' => $spt->tanggal,
                    'tempat_berangkat' => $spt->lokasi ?? 'Pelaihari',
                    'keterangan' => "Diperbarui dari SPT Nomor: {$spt->nomor_surat}"
                ]);
                
                $pelaksanaIds = $request->pegawai;
                if (!empty($pelaksanaIds)) {
                    $existingSpd->syncPelaksana($pelaksanaIds);
                }
            } else {
                $spdController->createSpdFromSpt($spt);
            }
            
            $lhpdController = new LhpdController();
            if ($existingSpd) {
                $lhpdController->updateLhpdFromSpd($existingSpd);
            } else {
                $lhpdController->createLhpdFromSpt($spt);
            }
            
            DB::commit();
            
            $message = "Data SPT berhasil diperbarui. Nomor Surat: {$nomorSuratBaru}";
            if ($existingSpd) {
                $message .= " SPD juga telah diperbarui.";
            }
            $message .= " LHPD juga telah diperbarui.";
            
            return redirect()->route('spt.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui SPT: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data SPT: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $spt = SPT::findOrFail($id);
            $nomorSurat = $spt->nomor_surat;
            
            DB::beginTransaction();
            
            $spd = \App\Models\SPD::where('spt_id', $id)->first();
            if ($spd) {
                $spd->pelaksanaPerjadin()->detach();
                $spd->delete();
            }
            
            $lhpdController = new LhpdController();
           $lhpd = \App\Models\Lhpd::where('spt_id', $spt->id_spt)->first();
            if ($lhpd) {
                $lhpd->delete();
            }
            
            $spt->delete();
            
            DB::commit();
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Data SPT dengan nomor '{$nomorSurat}' berhasil dihapus."
                ]);
            }
            
            return redirect()->route('spt.index')
                ->with('success', "Data SPT dengan nomor '{$nomorSurat}' berhasil dihapus.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus SPT: ' . $e->getMessage());
            
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

    public function export(Request $request)
    {
        try {
            $query = SPT::with('penandaTangan');
            
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
            
            if ($request->has('bulan') && $request->bulan != '') {
                $query->whereMonth('tanggal', $request->bulan);
            }
            
            if ($request->has('tahun') && $request->tahun != '') {
                $query->whereYear('tanggal', $request->tahun);
            }
            
            if ($request->has('penanda_tangan') && $request->penanda_tangan != '') {
                $query->where('penanda_tangan', $request->penanda_tangan);
            }
            
            $spts = $query->orderBy('tanggal', 'desc')->get();
            
            if ($spts->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada data SPT untuk diexport.');
            }
            
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Data SPT');
            
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
            
            foreach ($headers as $cell => $value) {
                $sheet->setCellValue($cell, $value);
                $sheet->getStyle($cell)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E0E0E0']],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ]);
            }
            
            $row = 2;
            $no = 1;
            foreach ($spts as $spt) {
                $dasarList = $spt->dasar_list;
                $dasarString = is_array($dasarList) ? implode("\n", $dasarList) : (string)$dasarList;
                
                $pegawaiSnapshot = $spt->pegawai_snapshot ?? [];
                $pegawaiString = '';
                if (!empty($pegawaiSnapshot)) {
                    $pegawaiNames = [];
                    foreach ($pegawaiSnapshot as $pegawai) {
                        $pegawaiNames[] = trim($pegawai['nama'] ?? '-') . (isset($pegawai['nip']) && $pegawai['nip'] ? ' (' . $pegawai['nip'] . ')' : '');
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
                $sheet->setCellValue('H' . $row, $spt->penanda_tangan_nama ?? '-');
                $sheet->setCellValue('I' . $row, $spt->penanda_tangan_nip ?? '-');
                $sheet->setCellValue('J' . $row, $spt->penanda_tangan_jabatan ?? '-');
                
                $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    'alignment' => ['vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true]
                ]);
                
                $sheet->getRowDimension($row)->setRowHeight(-1);
                
                $row++;
                $no++;
            }
            
            foreach (range('A', 'J') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }
            
            $sheet->getColumnDimension('C')->setWidth(40);
            $sheet->getColumnDimension('D')->setWidth(45);
            $sheet->getColumnDimension('E')->setWidth(50);
            
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
            
            if (ob_get_length()) {
                ob_end_clean();
            }
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Expires: 0');
            header('Pragma: public');
            
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
            
        } catch (\Exception $e) {
            Log::error('Export Excel SPT Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal export data: ' . $e->getMessage());
        }
    }

    private function getNamaBulan($bulan)
    {
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $namaBulan[(int)$bulan] ?? 'Bulan_' . $bulan;
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
        if (empty($filename)) $filename = 'spt';
        return $filename;
    }

    private function generatePdfFilename($spt, $prefix = 'SPT-', $suffix = '.pdf')
    {
        $nomorBersih = $this->sanitizeFilename($spt->nomor_surat);
        return $prefix . $nomorBersih . '-' . $spt->id_spt . $suffix;
    }

    public function print($id)
    {
        try {
            $spt = SPT::findOrFail($id);
            $pegawaiList = $spt->pegawai_list_from_snapshot;
            $dasarList = $spt->dasar_list;
            $penandaTangan = $spt->penanda_tangan_snapshot;
            
            $pdf = Pdf::loadView('admin.spt-pdf', compact('spt', 'pegawaiList', 'dasarList', 'penandaTangan'));
            $pdf->setPaper('A4', 'portrait');
            $namaFile = $this->generatePdfFilename($spt, 'SPT-', '.pdf');
            
            return $pdf->download($namaFile);
            
        } catch (\Exception $e) {
            return redirect()->route('spt.index')
                ->with('error', 'Gagal mencetak SPT: ' . $e->getMessage());
        }
    }

    public function previewPdf($id)
    {
        try {
            $spt = SPT::findOrFail($id);
            // PERBAIKAN: Gunakan snapshot untuk preview
            $pegawaiList = $spt->pegawai_list_from_snapshot;
            $dasarList = $spt->dasar_list;
            $penandaTangan = $spt->penanda_tangan_snapshot;
            
            $pdf = Pdf::loadView('admin.spt-pdf', compact('spt', 'pegawaiList', 'dasarList', 'penandaTangan'));
            $pdf->setPaper('A4', 'portrait');
            $namaFile = $this->generatePdfFilename($spt, 'SPT-', '.pdf');
            
            return $pdf->stream($namaFile);
            
        } catch (\Exception $e) {
            return redirect()->route('spt.index')
                ->with('error', 'Gagal preview PDF: ' . $e->getMessage());
        }
    }

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