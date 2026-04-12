<?php

namespace App\Http\Controllers;

use App\Models\SPT;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class SPTController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = SPT::with('penandaTangan');
            
            // Cek level user
            $userLevel = session('user')['level'] ?? 'guest';
            
            // PERBAIKAN: Jika Kadis, tampilkan SPT pending dan resubmitted
            if ($userLevel == 'kadis') {
                $query->where(function($q) {
                    $q->where('status_approval', 'pending')
                      ->orWhere('status_approval', 'resubmitted');
                });
            }
            
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
            
            // Filter berdasarkan status approval (untuk admin)
            if ($userLevel == 'admin' && $request->has('status_approval') && $request->status_approval != '') {
                $query->where('status_approval', $request->status_approval);
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
            $spts = new LengthAwarePaginator([], 0, 10);
            $pegawais = collect([]);
        }
        
        return view('admin.spt', compact('spts', 'pegawais'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userLevel = session('user')['level'] ?? 'guest';
        if ($userLevel == 'kadis') {
            return redirect()->route('spt.index')
                ->with('error', 'Kadis tidak dapat membuat SPT baru.');
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
        
        return view('admin.spt-create', compact('penandaTangans', 'semuaPegawai', 'pegawaiData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
        ]);
        
        try {
            $validated['status_approval'] = 'pending';
            SPT::create($validated);
            
            return redirect()->route('spt.index')
                ->with('success', 'Data SPT berhasil ditambahkan dan menunggu persetujuan Kadis.');
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
            $spt = SPT::with(['penandaTangan', 'approvedBy'])->findOrFail($id);
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
        try {
            $spt = SPT::findOrFail($id);
            
            if ($spt->isApproved()) {
                return redirect()->route('spt.index')
                    ->with('error', 'Surat yang sudah disetujui tidak dapat diedit.');
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
            
            if ($spt->isApproved()) {
                return redirect()->route('spt.index')
                    ->with('error', 'Surat yang sudah disetujui tidak dapat diubah.');
            }
            
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
            ]);
            
            // Jika SPT ditolak dan diedit, set last_edited_at
            if ($spt->isRejected()) {
                $validated['last_edited_at'] = now();
            }
            
            $spt->update($validated);
            
            if ($spt->isRejected()) {
                return redirect()->route('spt.index')
                    ->with('warning', 'Data SPT berhasil diperbarui. Jangan lupa klik tombol "Ajukan Ulang" untuk mengirimkan ke Kadis.');
            }
            
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
            
            if ($spt->isApproved()) {
                if (request()->ajax() || request()->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Surat yang sudah disetujui tidak dapat dihapus.'
                    ], 403);
                }
                return redirect()->route('spt.index')
                    ->with('error', 'Surat yang sudah disetujui tidak dapat dihapus.');
            }
            
            $nomorSurat = $spt->nomor_surat;
            $spt->delete();
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Data SPT dengan nomor '{$nomorSurat}' berhasil dihapus."
                ]);
            }
            
            return redirect()->route('spt.index')
                ->with('success', "Data SPT dengan nomor '{$nomorSurat}' berhasil dihapus.");
        } catch (\Exception $e) {
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
     * Ajukan Ulang SPT yang Ditolak
     */
    public function resubmit(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $spt = SPT::findOrFail($id);
            
            // Validasi: hanya SPT yang ditolak yang bisa diajukan ulang
            if (!$spt->isRejected()) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Hanya SPT yang ditolak yang dapat diajukan ulang.'
                    ], 400);
                }
                return redirect()->back()
                    ->with('error', 'Hanya SPT yang ditolak yang dapat diajukan ulang.');
            }
            
            // Cek apakah SPT sudah pernah diajukan ulang sebelumnya
            if ($spt->status_approval == 'resubmitted') {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'SPT ini sudah diajukan ulang dan sedang menunggu persetujuan.'
                    ], 400);
                }
                return redirect()->back()
                    ->with('warning', 'SPT ini sudah diajukan ulang dan sedang menunggu persetujuan.');
            }
            
            // Update status ke 'resubmitted'
            $spt->update([
                'status_approval' => 'resubmitted',
                'rejection_reason' => null,
                'resubmitted_at' => now(),
                'resubmitted_by' => session('user')['id'] ?? Auth::id(),
                'last_edited_at' => now()
            ]);
            
            DB::commit();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "SPT dengan nomor '{$spt->nomor_surat}' berhasil diajukan ulang ke Kadis.",
                    'data' => [
                        'id' => $spt->id_spt,
                        'nomor_surat' => $spt->nomor_surat,
                        'status' => $spt->status_approval
                    ]
                ]);
            }
            
            return redirect()->route('spt.index')
                ->with('success', "SPT dengan nomor '{$spt->nomor_surat}' berhasil diajukan ulang ke Kadis.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengajukan ulang SPT: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Gagal mengajukan ulang SPT: ' . $e->getMessage());
        }
    }

    // ========== METHOD UNTUK APPROVAL KADIS ==========

    /**
     * Display list SPT for Kadis approval
     */
    public function approvalList(Request $request)
    {
        try {
            $query = SPT::with(['penandaTangan', 'approvedBy'])
                ->where(function($q) {
                    $q->where('status_approval', 'pending')
                      ->orWhere('status_approval', 'resubmitted');
                });
            
            // Search
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nomor_surat', 'like', "%{$search}%")
                      ->orWhere('tujuan', 'like', "%{$search}%")
                      ->orWhereHas('penandaTangan', function ($pegawaiQuery) use ($search) {
                          $pegawaiQuery->where('nama', 'like', "%{$search}%");
                      });
                });
            }
            
            // Prioritaskan yang resubmitted (diajukan ulang) muncul lebih dulu
            $spts = $query->orderByRaw("FIELD(status_approval, 'resubmitted', 'pending')")
                         ->orderBy('resubmitted_at', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);
            
            return view('admin.spt-pending', compact('spts'));
        } catch (\Exception $e) {
            $spts = new LengthAwarePaginator([], 0, 10);
            return view('admin.spt-pending', compact('spts'))
                ->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    /**
     * Approve SPT
     * ===== PERBAIKAN: TANPA PARAMETER PATH GAMBAR =====
     * Method ini akan memanggil $spt->approve($userId) di Model
     * Yang secara otomatis akan:
     * 1. Set status_approval = 'approved'
     * 2. Generate verification_code (contoh: SPT-1-ABC123) sebagai Tanda Tangan Digital
     * 3. Generate document_hash untuk deteksi perubahan
     */
    public function approve($id)
    {
        try {
            DB::beginTransaction();
            
            $spt = SPT::findOrFail($id);
            
            // Cek apakah sudah disetujui
            if ($spt->isApproved()) {
                return redirect()->back()
                    ->with('warning', 'Surat ini sudah disetujui sebelumnya.');
            }
            
            // Cek apakah ditolak
            if ($spt->isRejected()) {
                return redirect()->back()
                    ->with('warning', 'Surat ini sudah ditolak. Silahkan minta pegawai untuk mengajukan ulang.');
            }
            
            // Ambil ID user yang login (Kadis)
            $userId = session('user')['id'] ?? Auth::id();
            
            // ===== APPROVE TANPA PARAMETER PATH GAMBAR =====
            // QR Code akan otomatis menjadi Tanda Tangan Digital
            $spt->approve($userId);
            
            DB::commit();
            
            // Redirect dengan pesan sukses termasuk informasi QR Code
            return redirect()->route('kadis.spt.approval')
                ->with('success', "SPT dengan nomor '{$spt->nomor_surat}' berhasil disetujui. Tanda Tangan Digital (QR Code) telah dibuat dengan kode: {$spt->verification_code}");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menyetujui SPT: ' . $e->getMessage());
        }
    }

    /**
     * Reject SPT
     */
    public function reject(Request $request, $id)
    {
        try {
            $request->validate([
                'rejection_reason' => 'required|string|min:5|max:500'
            ]);
            
            DB::beginTransaction();
            
            $spt = SPT::findOrFail($id);
            
            if ($spt->isApproved()) {
                return redirect()->back()
                    ->with('warning', 'Surat yang sudah disetujui tidak dapat ditolak.');
            }
            
            $userId = session('user')['id'] ?? Auth::id();
            $spt->reject($userId, $request->rejection_reason);
            
            DB::commit();
            
            return redirect()->route('kadis.spt.approval')
                ->with('info', "SPT dengan nomor '{$spt->nomor_surat}' telah ditolak.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menolak SPT: ' . $e->getMessage());
        }
    }

    /**
     * Reset approval (kembalikan ke pending) - KHUSUS ADMIN
     */
    public function resetApproval($id)
    {
        try {
            DB::beginTransaction();
            
            $spt = SPT::findOrFail($id);
            
            $userLevel = session('user')['level'] ?? 'guest';
            if ($userLevel != 'admin') {
                return redirect()->back()
                    ->with('error', 'Hanya admin yang dapat mereset status approval.');
            }
            
            $spt->resetApproval();
            
            DB::commit();
            
            return redirect()->route('spt.index')
                ->with('success', "Status approval SPT '{$spt->nomor_surat}' telah direset ke pending.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal mereset approval: ' . $e->getMessage());
        }
    }

    // ========== METHOD PDF ==========

    /**
     * Print SPT (cetak PDF)
     */
    public function print($id)
    {
        try {
            $spt = SPT::with(['penandaTangan', 'approvedBy'])->findOrFail($id);
            $pegawaiList = $spt->pegawai_list;
            $dasarList = $spt->dasar_list;
            
            $pdf = Pdf::loadView('admin.spt-pdf', compact('spt', 'pegawaiList', 'dasarList'));
            $pdf->setPaper('A4', 'portrait');
            
            $namaFile = $this->generatePdfFilename($spt, 'SPT-', '.pdf');
            
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
            $spt = SPT::with(['penandaTangan', 'approvedBy'])->findOrFail($id);
            $pegawaiList = $spt->pegawai_list;
            $dasarList = $spt->dasar_list;
            
            $pdf = Pdf::loadView('admin.spt-pdf', compact('spt', 'pegawaiList', 'dasarList'));
            $pdf->setPaper('A4', 'portrait');
            
            $namaFile = $this->generatePdfFilename($spt, 'SPT-', '.pdf');
            
            return $pdf->stream($namaFile);
            
        } catch (\Exception $e) {
            return redirect()->route('spt.index')
                ->with('error', 'Gagal preview PDF: ' . $e->getMessage());
        }
    }

    /**
     * Helper untuk menghasilkan nama file PDF yang aman
     */
    private function generatePdfFilename($spt, $prefix = 'SPT-', $suffix = '.pdf')
    {
        $nomorBersih = $this->sanitizeFilename($spt->nomor_surat);
        return $prefix . $nomorBersih . '-' . $spt->id_spt . $suffix;
    }

    /**
     * Helper untuk membersihkan nama file dari karakter ilegal
     */
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
        
        if (empty($filename)) {
            $filename = 'spt';
        }
        
        return $filename;
    }
}