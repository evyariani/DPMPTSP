<?php

namespace App\Http\Controllers;

use App\Models\SPD;
use App\Models\SPT;
use App\Models\Pegawai;
use App\Models\Daerah;
use App\Models\Program;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SPDController extends Controller
{
    /**
     * Display a listing of the resource (Halaman Depan SPD)
     */
    public function index(Request $request)
    {
        try {
            $query = SPD::with(['penggunaAnggaran', 'tempatTujuan', 'pelaksanaPerjadin', 'pejabatTeknis', 'pejabatTeknisPegawai', 'spt']);

            // Search
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nomor_surat', 'like', "%{$search}%")
                      ->orWhere('maksud_perjadin', 'like', "%{$search}%")
                      ->orWhere('skpd', 'like', "%{$search}%")
                      ->orWhere('kode_rek', 'like', "%{$search}%")
                      ->orWhere('pejabat_teknis_program', 'like', "%{$search}%")
                      ->orWhere('pejabat_teknis_kegiatan', 'like', "%{$search}%")
                      ->orWhere('penanda_tangan_nama', 'like', "%{$search}%")
                      ->orWhereHas('penggunaAnggaran', function ($pegawaiQuery) use ($search) {
                          $pegawaiQuery->where('nama', 'like', "%{$search}%")
                                       ->orWhere('nip', 'like', "%{$search}%");
                      })
                      ->orWhereHas('tempatTujuan', function ($daerahQuery) use ($search) {
                          $daerahQuery->where('nama', 'like', "%{$search}%");
                      })
                      ->orWhereHas('pelaksanaPerjadin', function ($pegawaiQuery) use ($search) {
                          $pegawaiQuery->where('nama', 'like', "%{$search}%")
                                       ->orWhere('nip', 'like', "%{$search}%");
                      })
                      ->orWhereHas('spt', function ($sptQuery) use ($search) {
                          $sptQuery->where('nomor_surat', 'like', "%{$search}%");
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
            Log::error('Error di SPDController@index: ' . $e->getMessage());
            $spds = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            $pegawais = collect([]);
            $daerahs = collect([]);
            $skpdList = collect([]);
            $alatTransportasiList = [];
        }

        return view('admin.spd', compact('spds', 'pegawais', 'daerahs', 'skpdList', 'alatTransportasiList'));
    }

    /**
     * Show the form for creating a new resource (Halaman Depan SPD)
     * Catatan: Method ini TIDAK digunakan karena SPD dibuat otomatis dari SPT
     */
    public function create()
    {
        return redirect()->route('spd.index')->with('info', 'SPD dibuat otomatis dari SPT.');
    }

    /**
     * Show halaman belakang SPD (Program, Kode Rekening, Penanda Tangan)
     */
    public function belakang($id)
    {
        try {
            $spd = SPD::where('id_spd', $id)->first();

            if (!$spd) {
                return redirect()->route('spd.index')
                    ->with('error', 'Data SPD tidak ditemukan.');
            }

            $spd->load(['pejabatTeknisPegawai', 'spt', 'tempatTujuan']);

            Log::info('BELAKANG - Data akan ditampilkan:', [
                'penanda_tangan_nama' => $spd->penanda_tangan_nama,
                'penanda_tangan_nip' => $spd->penanda_tangan_nip,
                'penanda_tangan_jabatan' => $spd->penanda_tangan_jabatan,
                'penanda_tangan_instansi' => $spd->penanda_tangan_instansi,
            ]);

            return view('admin.spd-belakang', compact('spd'));
        } catch (\Exception $e) {
            Log::error('Error di SPDController@belakang: ' . $e->getMessage());
            return redirect()->route('spd.index')
                ->with('error', 'Data SPD tidak ditemukan.');
        }
    }

    private function generateNomorSurat($nomorUrut, $tahun = null)
    {
        if (!$tahun) {
            $tahun = date('Y');
        }

        $nomorUrutFormatted = str_pad($nomorUrut, 3, '0', STR_PAD_LEFT);

        return "000.1.2.3/{$nomorUrutFormatted}/DPMPTSP/{$tahun}";
    }

    public function getNextNomorUrut($tahun = null)
    {
        if (!$tahun) {
            $tahun = date('Y');
        }

        $lastSPD = SPD::whereYear('tanggal_berangkat', $tahun)
            ->orderBy('id_spd', 'desc')
            ->first();

        if ($lastSPD && $lastSPD->nomor_surat) {
            preg_match('/000\.1\.2\.3\/(\d+)\/DPMPTSP\/\d{4}/', $lastSPD->nomor_surat, $matches);
            if (isset($matches[1])) {
                return (int)$matches[1] + 1;
            }
        }

        return 1;
    }

    public function store(Request $request)
    {
        return redirect()->route('spd.index')->with('info', 'SPD dibuat otomatis dari SPT.');
    }

    public function updateBelakang(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'penanda_tangan_nama' => 'nullable|string|max:150',
                'penanda_tangan_nip' => 'nullable|string|max:50',
                'penanda_tangan_jabatan' => 'nullable|string|max:150',
                'penanda_tangan_instansi' => 'nullable|string|max:200',
            ]);

            Log::info('UPDATE BELAKANG - Data dari request:', $validated);

            $before = DB::table('spd')->where('id_spd', $id)->first();
            Log::info('UPDATE BELAKANG - Data SEBELUM update:', [
                'penanda_tangan_nama' => $before->penanda_tangan_nama ?? 'NULL',
                'penanda_tangan_nip' => $before->penanda_tangan_nip ?? 'NULL',
            ]);

            $updated = DB::table('spd')
                ->where('id_spd', $id)
                ->update([
                    'penanda_tangan_nama' => $validated['penanda_tangan_nama'],
                    'penanda_tangan_nip' => $validated['penanda_tangan_nip'],
                    'penanda_tangan_jabatan' => $validated['penanda_tangan_jabatan'],
                    'penanda_tangan_instansi' => $validated['penanda_tangan_instansi'],
                    'updated_at' => now(),
                ]);

            Log::info('UPDATE BELAKANG - Jumlah baris terupdate: ' . $updated);

            $after = DB::table('spd')->where('id_spd', $id)->first();
            Log::info('UPDATE BELAKANG - Data SETELAH update:', [
                'penanda_tangan_nama' => $after->penanda_tangan_nama ?? 'NULL',
                'penanda_tangan_nip' => $after->penanda_tangan_nip ?? 'NULL',
                'penanda_tangan_jabatan' => $after->penanda_tangan_jabatan ?? 'NULL',
                'penanda_tangan_instansi' => $after->penanda_tangan_instansi ?? 'NULL',
            ]);

            if ($updated > 0) {
                return redirect()->route('spd.belakang', $id)
                    ->with('success', "Data penanda tangan SPD berhasil diperbarui.");
            } else {
                return redirect()->route('spd.belakang', $id)
                    ->with('warning', "Tidak ada perubahan data penanda tangan.");
            }

        } catch (\Exception $e) {
            Log::error('Error di SPDController@updateBelakang: ' . $e->getMessage());

            return redirect()->route('spd.belakang', $id)
                ->with('error', 'Gagal memperbarui data penanda tangan SPD: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $spd = SPD::with([
                'penggunaAnggaran',
                'tempatTujuan',
                'pelaksanaPerjadin',
                'pejabatTeknis',
                'pejabatTeknisPegawai',
                'spt'
            ])->findOrFail($id);

            return view('admin.spd-show', compact('spd'));
        } catch (\Exception $e) {
            Log::error('Error di SPDController@show: ' . $e->getMessage());
            return redirect()->route('spd.index')
                ->with('error', 'Data SPD tidak ditemukan.');
        }
    }

    public function edit($id)
    {
        try {
            $spd = SPD::with(['pelaksanaPerjadin', 'spt'])->findOrFail($id);

            $nomorUrut = null;
            if ($spd->nomor_surat) {
                preg_match('/000\.1\.2\.3\/(\d+)\/DPMPTSP\/\d{4}/', $spd->nomor_surat, $matches);
                if (isset($matches[1])) {
                    $nomorUrut = (int)$matches[1];
                }
            }

            $kepalaDinas = Pegawai::where('jabatan', 'Kepala Dinas')->first();
            if (!$kepalaDinas) {
                $kepalaDinas = Pegawai::first();
            }

            $semuaPegawai = Pegawai::orderBy('nama')->get();
            $programs = Program::with('pegawai')->orderBy('program')->get();
            $daerahs = Daerah::orderBy('nama')->get();

            $alatTransportasiList = [
                'transportasi_darat' => 'Transportasi Darat',
                'transportasi_udara' => 'Transportasi Udara',
                'transportasi_darat_udara' => 'Transportasi Darat & Udara',
                'angkutan_darat' => 'Angkutan Darat',
                'kendaraan_dinas' => 'Kendaraan Dinas',
                'angkutan_umum' => 'Angkutan Umum'
            ];

            $selectedPelaksana = $spd->pelaksanaPerjadin->pluck('id_pegawai')->toArray();
            $nomorSuratTemplate = '000.1.2.3/           /DPMPTSP/' . date('Y', strtotime($spd->tanggal_berangkat));

            return view('admin.spd-edit', compact(
                'spd',
                'kepalaDinas',
                'semuaPegawai',
                'programs',
                'daerahs',
                'alatTransportasiList',
                'nomorUrut',
                'nomorSuratTemplate',
                'selectedPelaksana'
            ));
        } catch (\Exception $e) {
            Log::error('Error di SPDController@edit: ' . $e->getMessage());
            return redirect()->route('spd.index')
                ->with('error', 'Data SPD tidak ditemukan.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $spd = SPD::findOrFail($id);

            $validated = $request->validate([
                'nomor_urut' => 'required|integer|min:1|max:999',
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
                'tempat_dikeluarkan' => 'nullable|string|max:100',
                'tanggal_dikeluarkan' => 'nullable|date',
                'pelaksana_perjadin' => 'required|array|min:1',
                'pelaksana_perjadin.*' => 'required|exists:tb_pegawai,id_pegawai',
                'pejabat_teknis_id' => 'required|exists:tb_program,id_program',
            ]);

            if (empty($validated['lama_perjadin'])) {
                $start = new \DateTime($validated['tanggal_berangkat']);
                $end = new \DateTime($validated['tanggal_kembali']);
                $interval = $start->diff($end);
                $validated['lama_perjadin'] = $interval->days + 1;
            }

            $tahun = date('Y', strtotime($request->tanggal_berangkat));
            $nomorSuratBaru = $this->generateNomorSurat($request->nomor_urut, $tahun);

            $exists = SPD::where('nomor_surat', $nomorSuratBaru)
                ->where('id_spd', '!=', $id)
                ->exists();

            if ($exists) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Nomor surat dengan urutan {$request->nomor_urut} untuk tahun {$tahun} sudah ada. Gunakan nomor urut lain.");
            }

            $program = Program::with('pegawai')->find($request->pejabat_teknis_id);

            if (!$program) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Data program tidak ditemukan');
            }

            DB::beginTransaction();

            $data = [
                'nomor_surat' => $nomorSuratBaru,
                'pengguna_anggaran' => $validated['pengguna_anggaran'],
                'maksud_perjadin' => $validated['maksud_perjadin'],
                'alat_transportasi' => $validated['alat_transportasi'],
                'tempat_berangkat' => $validated['tempat_berangkat'],
                'tempat_tujuan' => $validated['tempat_tujuan'],
                'tanggal_berangkat' => $validated['tanggal_berangkat'],
                'tanggal_kembali' => $validated['tanggal_kembali'],
                'lama_perjadin' => $validated['lama_perjadin'],
                'skpd' => $validated['skpd'] ?? null,
                'kode_rek' => $validated['kode_rek'] ?? null,
                'tempat_dikeluarkan' => $validated['tempat_dikeluarkan'] ?? null,
                'tanggal_dikeluarkan' => $validated['tanggal_dikeluarkan'] ?? null,
                'pejabat_teknis_id' => $validated['pejabat_teknis_id'],
                'pejabat_teknis_pegawai_id' => $program->id_pegawai,
                'pejabat_teknis_kode_rekening' => $program->kode_rekening,
                'pejabat_teknis_program' => $program->program,
                'pejabat_teknis_kegiatan' => $program->kegiatan,
                'pejabat_teknis_sub_kegiatan' => $program->sub_kegiatan,
            ];

            $spd->update($data);
            $spd->syncPelaksana($request->pelaksana_perjadin);

            // ========== UPDATE RINCIAN BIDANG OTOMATIS ==========
            try {
                $spd->syncRincianBidang();
            } catch (\Exception $e) {
                Log::warning('Gagal sync RincianBidang: ' . $e->getMessage());
            }

            // ========== UPDATE LHPD OTOMATIS ==========
            $lhpdController = new LhpdController();
            $lhpdController->updateLhpdFromSpd($spd);

            DB::commit();

            return redirect()->route('spd.index')
                ->with('success', "Data SPD halaman depan berhasil diperbarui. Nomor Surat: {$nomorSuratBaru}, Rincian Bidang dan LHPD juga telah diperbarui.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error di SPDController@update: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data SPD: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $spd = SPD::findOrFail($id);
            $nomorSurat = $spd->nomor_surat;

            DB::beginTransaction();

            $spd->pelaksanaPerjadin()->detach();

            if ($spd->spt) {
                $lhpd = \App\Models\Lhpd::where('tujuan', $spd->spt->tujuan)
                    ->where('tanggal_berangkat', $spd->spt->tanggal)
                    ->first();
                if ($lhpd) {
                    $lhpd->delete();
                    Log::info('LHPD terkait SPD dihapus, ID LHPD: ' . $lhpd->id_lhpd);
                }
            }

            $spd->delete();
            DB::commit();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Data SPD dengan nomor '{$nomorSurat}' dan LHPD terkait berhasil dihapus."
                ]);
            }

            return redirect()->route('spd.index')
                ->with('success', "Data SPD dengan nomor '{$nomorSurat}' dan LHPD terkait berhasil dihapus.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error di SPDController@destroy: ' . $e->getMessage());

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
     * Create SPD from SPT (otomatis dari SPT saat SPT disimpan)
     * Method ini TIDAK menggunakan transaction sendiri karena sudah ditangani oleh SPTController
     */
    public function createSpdFromSpt(SPT $spt)
    {
        try {
            $existingSpd = SPD::where('spt_id', $spt->id_spt)->first();
            if ($existingSpd) {
                Log::info('SPD sudah ada untuk SPT ID: ' . $spt->id_spt);
                return $existingSpd;
            }

            $maksudPerjadin = $spt->tujuan;

            // Gunakan SNAPSHOT dari SPT untuk mengambil data pegawai
            $pegawaiSnapshot = $spt->pegawai_snapshot ?? [];
            $pelaksanaIds = [];
            if (!empty($pegawaiSnapshot)) {
                foreach ($pegawaiSnapshot as $pegawai) {
                    $pelaksanaIds[] = $pegawai['id_pegawai'] ?? null;
                }
            }
            // Filter null values
            $pelaksanaIds = array_filter($pelaksanaIds);

            $kepalaDinas = Pegawai::where('jabatan', 'Kepala Dinas')->first();
            if (!$kepalaDinas) {
                $kepalaDinas = Pegawai::first();
                Log::warning('Tidak ada pegawai dengan jabatan Kepala Dinas, menggunakan pegawai pertama sebagai fallback');
            }

            $tahun = date('Y', strtotime($spt->tanggal));
            $nextNomorUrut = $this->getNextNomorUrut($tahun);
            $nomorSurat = $this->generateNomorSurat($nextNomorUrut, $tahun);

            $data = [
                'nomor_surat' => $nomorSurat,
                'pengguna_anggaran' => $kepalaDinas->id_pegawai ?? null,
                'maksud_perjadin' => $maksudPerjadin,
                'alat_transportasi' => null,
                'tempat_berangkat' => $spt->lokasi ?? 'Pelaihari',
                'tempat_tujuan' => null,
                'tanggal_berangkat' => $spt->tanggal,
                'tanggal_kembali' => $spt->tanggal,
                'lama_perjadin' => 1,
                'skpd' => 'Dinas Penanaman Modal dan PTSP Kabupaten Tanah Laut',
                'kode_rek' => null,
                'tempat_dikeluarkan' => 'Pelaihari',
                'tanggal_dikeluarkan' => date('Y-m-d'),
                'spt_id' => $spt->id_spt,
            ];

            // LANGSUNG CREATE tanpa transaction (transaction sudah di SPTController)
            $spd = SPD::create($data);

            if (!empty($pelaksanaIds)) {
                $spd->syncPelaksana($pelaksanaIds);
            }

            // CATATAN: LHPD dibuat di SPTController, TIDAK di sini

            Log::info('SPD berhasil dibuat dari SPT ID: ' . $spt->id_spt . ', Nomor Surat: ' . $nomorSurat);
            return $spd;

        } catch (\Exception $e) {
            Log::error('Gagal membuat SPD dari SPT: ' . $e->getMessage());
            throw $e; // Lempar exception agar ditangani oleh SPTController
        }
    }

    public function createFromSpt($sptId)
    {
        try {
            $spt = SPT::with(['pegawai_list', 'penandaTangan'])->findOrFail($sptId);
            $spd = $this->createSpdFromSpt($spt);

            if ($spd) {
                return redirect()->route('spd.belakang', $spd->id_spd)
                    ->with('success', "SPD berhasil dibuat dari SPT. Silakan lengkapi data penanda tangan. LHPD juga telah dibuat otomatis.");
            } else {
                return redirect()->route('spt.index')
                    ->with('error', 'Gagal membuat SPD dari SPT.');
            }

        } catch (\Exception $e) {
            Log::error('Error di SPDController@createFromSpt: ' . $e->getMessage());
            return redirect()->route('spt.index')
                ->with('error', 'Gagal membuat SPD dari SPT: ' . $e->getMessage());
        }
    }

    public function apiDetail($id)
    {
        try {
            $spd = SPD::with(['pejabatTeknisPegawai', 'spt'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'program' => $spd->pejabat_teknis_program,
                'kegiatan' => $spd->pejabat_teknis_kegiatan,
                'sub_kegiatan' => $spd->pejabat_teknis_sub_kegiatan,
                'kode_rekening' => $spd->pejabat_teknis_kode_rekening,
                'pejabat_teknis_nama' => $spd->pejabatTeknisPegawai?->nama,
                'pejabat_teknis_nip' => $spd->pejabatTeknisPegawai?->nip,
                'pejabat_teknis_jabatan' => $spd->pejabatTeknisPegawai?->jabatan,
                'penanda_tangan_nama' => $spd->penanda_tangan_nama,
                'penanda_tangan_nip' => $spd->penanda_tangan_nip,
                'penanda_tangan_jabatan' => $spd->penanda_tangan_jabatan,
                'penanda_tangan_instansi' => $spd->penanda_tangan_instansi,
                'spt_nomor' => $spd->spt?->nomor_surat,
                'spt_tanggal' => $spd->spt?->tanggal ? $spd->spt->tanggal->format('d/m/Y') : null,
            ]);
        } catch (\Exception $e) {
            Log::error('Error di SPDController@apiDetail: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    public function printDepan($id)
    {
        try {
            $spd = SPD::with([
                'penggunaAnggaran',
                'tempatTujuan',
                'pelaksanaPerjadin',
                'pejabatTeknis',
                'pejabatTeknisPegawai',
                'spt'
            ])->findOrFail($id);

            $pdf = Pdf::loadView('admin.spd-pdf-depan', compact('spd'));
            $pdf->setPaper('A4', 'portrait');
            $namaFile = $this->generatePdfFilename($spd, 'SPD-DEPAN-', '.pdf');
            return $pdf->download($namaFile);

        } catch (\Exception $e) {
            Log::error('Error di SPDController@printDepan: ' . $e->getMessage());
            return redirect()->route('spd.index')
                ->with('error', 'Gagal mencetak SPD Halaman Depan: ' . $e->getMessage());
        }
    }

    public function printBelakang($id)
    {
        try {
            $spd = SPD::with([
                'penggunaAnggaran',
                'tempatTujuan',
                'pelaksanaPerjadin',
                'pejabatTeknis',
                'pejabatTeknisPegawai',
                'spt'
            ])->findOrFail($id);

            $pdf = Pdf::loadView('admin.spd-pdf-belakang', compact('spd'));
            $pdf->setPaper('A4', 'portrait');
            $namaFile = $this->generatePdfFilename($spd, 'SPD-BELAKANG-', '.pdf');
            return $pdf->download($namaFile);

        } catch (\Exception $e) {
            Log::error('Error di SPDController@printBelakang: ' . $e->getMessage());
            return redirect()->route('spd.index')
                ->with('error', 'Gagal mencetak SPD Halaman Belakang: ' . $e->getMessage());
        }
    }

    public function previewDepan($id)
    {
        try {
            $spd = SPD::with([
                'penggunaAnggaran',
                'tempatTujuan',
                'pelaksanaPerjadin',
                'pejabatTeknis',
                'pejabatTeknisPegawai',
                'spt'
            ])->findOrFail($id);

            $pdf = Pdf::loadView('admin.spd-pdf-depan', compact('spd'));
            $pdf->setPaper('A4', 'portrait');
            $namaFile = $this->generatePdfFilename($spd, 'SPD-DEPAN-', '.pdf');
            return $pdf->stream($namaFile);

        } catch (\Exception $e) {
            Log::error('Error di SPDController@previewDepan: ' . $e->getMessage());
            return redirect()->route('spd.index')
                ->with('error', 'Gagal preview SPD Halaman Depan: ' . $e->getMessage());
        }
    }

    public function previewBelakang($id)
    {
        try {
            $spd = SPD::with([
                'penggunaAnggaran',
                'tempatTujuan',
                'pelaksanaPerjadin',
                'pejabatTeknis',
                'pejabatTeknisPegawai',
                'spt'
            ])->findOrFail($id);

            $pdf = Pdf::loadView('admin.spd-pdf-belakang', compact('spd'));
            $pdf->setPaper('A4', 'portrait');
            $namaFile = $this->generatePdfFilename($spd, 'SPD-BELAKANG-', '.pdf');
            return $pdf->stream($namaFile);

        } catch (\Exception $e) {
            Log::error('Error di SPDController@previewBelakang: ' . $e->getMessage());
            return redirect()->route('spd.index')
                ->with('error', 'Gagal preview SPD Halaman Belakang: ' . $e->getMessage());
        }
    }

    public function apiGetNextNomorUrut(Request $request)
    {
        try {
            $tahun = $request->input('tahun', date('Y'));
            $nextNomorUrut = $this->getNextNomorUrut($tahun);

            return response()->json([
                'success' => true,
                'nomor_urut' => $nextNomorUrut,
                'nomor_surat' => $this->generateNomorSurat($nextNomorUrut, $tahun)
            ]);
        } catch (\Exception $e) {
            Log::error('Error di SPDController@apiGetNextNomorUrut: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan nomor urut: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProgramData($id)
    {
        try {
            $program = Program::with('pegawai')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id_program' => $program->id_program,
                    'program' => $program->program,
                    'kegiatan' => $program->kegiatan,
                    'sub_kegiatan' => $program->sub_kegiatan,
                    'kode_rekening' => $program->kode_rekening,
                    'pegawai_id' => $program->id_pegawai,
                    'pegawai_nama' => $program->pegawai?->nama,
                    'pegawai_nip' => $program->pegawai?->nip,
                    'pegawai_jabatan' => $program->pegawai?->jabatan,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error di SPDController@getProgramData: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Data program tidak ditemukan'
            ], 404);
        }
    }

    public function export(Request $request)
    {
        return redirect()->back()->with('error', 'Export belum diimplementasikan');
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
        if (empty($filename)) $filename = 'spd';
        return $filename;
    }

    private function generatePdfFilename($spd, $prefix = 'SPD-', $suffix = '.pdf')
    {
        $nomorBersih = $this->sanitizeFilename($spd->nomor_surat);
        return $prefix . $nomorBersih . '-' . $spd->id_spd . $suffix;
    }

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
            $lama = $interval->days + 1;

            return response()->json([
                'success' => true,
                'lama_perjadin' => $lama
            ]);
        } catch (\Exception $e) {
            Log::error('Error di SPDController@calculateLamaPerjadin: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghitung lama perjalanan: ' . $e->getMessage()
            ], 500);
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
            Log::error('Error di SPDController@getPegawaiData: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Data pegawai tidak ditemukan'
            ], 404);
        }
    }

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
            Log::error('Error di SPDController@getDaerahData: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Data daerah tidak ditemukan'
            ], 404);
        }
    }

    public function getSptDataForSpd($id)
    {
        try {
            $spt = SPT::with(['pegawai_list', 'penandaTangan'])->findOrFail($id);

            $maksudPerjadin = $spt->tujuan;

            // Gunakan snapshot SPT jika ada
            $pegawaiSnapshot = $spt->pegawai_snapshot ?? [];
            $pelaksana = [];
            if (!empty($pegawaiSnapshot)) {
                foreach ($pegawaiSnapshot as $pegawai) {
                    $pelaksana[] = [
                        'id' => $pegawai['id_pegawai'] ?? null,
                        'nama' => $pegawai['nama'] ?? '-',
                        'nip' => $pegawai['nip'] ?? '-',
                        'jabatan' => $pegawai['jabatan'] ?? '-',
                    ];
                }
            } else {
                // Fallback ke pegawai_list
                $pegawaiList = $spt->pegawai_list;
                if ($pegawaiList && count($pegawaiList) > 0) {
                    foreach ($pegawaiList as $pegawai) {
                        $pelaksana[] = [
                            'id' => $pegawai->id_pegawai,
                            'nama' => $pegawai->nama,
                            'nip' => $pegawai->nip,
                            'jabatan' => $pegawai->jabatan,
                        ];
                    }
                }
            }

            $existingSpd = SPD::where('spt_id', $spt->id_spt)->first();

            return response()->json([
                'success' => true,
                'data' => [
                    'id_spt' => $spt->id_spt,
                    'nomor_surat_spt' => $spt->nomor_surat,
                    'tanggal' => $spt->tanggal,
                    'tujuan' => $spt->tujuan,
                    'maksud_perjadin' => $maksudPerjadin,
                    'lokasi' => $spt->lokasi,
                    'penanda_tangan' => $spt->penanda_tangan_nama ?? $spt->penandaTangan?->nama,
                    'pelaksana' => $pelaksana,
                    'has_existing_spd' => !is_null($existingSpd),
                    'existing_spd_id' => $existingSpd?->id_spd,
                    'existing_spd_nomor' => $existingSpd?->nomor_surat,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error di SPDController@getSptDataForSpd: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Data SPT tidak ditemukan'
            ], 404);
        }
    }
}
