<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Program::with('pegawai');
            
            // Search
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('program', 'like', "%{$search}%")
                      ->orWhere('kegiatan', 'like', "%{$search}%")
                      ->orWhere('sub_kegiatan', 'like', "%{$search}%")
                      ->orWhere('kode_rekening', 'like', "%{$search}%")
                      ->orWhereHas('pegawai', function ($pegawaiQuery) use ($search) {
                          $pegawaiQuery->where('nama', 'like', "%{$search}%")
                                       ->orWhere('nip', 'like', "%{$search}%");
                      });
                });
            }
            
            // Filter kode rekening
            if ($request->has('kode_rekening') && $request->kode_rekening != '') {
                $query->where('kode_rekening', $request->kode_rekening);
            }
            
            // Order by id program descending
            $query->orderBy('id_program', 'desc');
            
            // Paginate
            $programs = $query->paginate(10);
            
        } catch (\Exception $e) {
            // Jika ada error, berikan data kosong
            $programs = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        }
        
        return view('admin.program', compact('programs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil data pegawai untuk dropdown
        $pegawais = Pegawai::orderBy('nama')->get();
        
        // Ambil pilihan rekening dari Model
        $rekenings = Program::getRekeningOptions();
        
        // Siapkan data pegawai untuk JavaScript (format array asosiatif)
        $pegawaiData = [];
        foreach ($pegawais as $pegawai) {
            $initial = $pegawai->nama ? strtoupper(substr($pegawai->nama, 0, 1)) : '-';
            $pegawaiData[$pegawai->id_pegawai] = [
                'nama' => $pegawai->nama,
                'nip' => $pegawai->nip ?? '-',
                'jabatan' => $pegawai->jabatan ?? '-',
                'initial' => $initial
            ];
        }
        
        return view('admin.program-create', compact('pegawais', 'rekenings', 'pegawaiData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi dengan kode rekening yang lebih fleksibel
        $validated = $request->validate([
            'program' => 'required|string|max:200',
            'kegiatan' => 'required|string|max:200',
            'sub_kegiatan' => 'required|string|max:200',
            'kode_rekening' => 'required|string|max:50',
            'id_pegawai' => 'required|exists:tb_pegawai,id_pegawai'
        ], [
            'program.required' => 'Nama program harus diisi',
            'program.max' => 'Nama program maksimal 200 karakter',
            'kegiatan.required' => 'Kegiatan harus diisi',
            'kegiatan.max' => 'Kegiatan maksimal 200 karakter',
            'sub_kegiatan.required' => 'Sub kegiatan harus diisi',
            'sub_kegiatan.max' => 'Sub kegiatan maksimal 200 karakter',
            'kode_rekening.required' => 'Kode rekening harus dipilih',
            'kode_rekening.max' => 'Kode rekening maksimal 50 karakter',
            'id_pegawai.required' => 'Pegawai penanggung jawab harus dipilih',
            'id_pegawai.exists' => 'Pegawai tidak valid'
        ]);
        
        // Simpan data
        try {
            Program::create($validated);
            
            return redirect()->route('program.index')
                ->with('success', 'Data program berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data program: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $program = Program::findOrFail($id);
            $pegawais = Pegawai::orderBy('nama')->get();
            $rekenings = Program::getRekeningOptions();
            
            // Siapkan data untuk JavaScript
            $pegawaiData = [];
            foreach ($pegawais as $pegawai) {
                $initial = $pegawai->nama ? strtoupper(substr($pegawai->nama, 0, 1)) : '-';
                $pegawaiData[$pegawai->id_pegawai] = [
                    'nama' => $pegawai->nama,
                    'nip' => $pegawai->nip ?? '-',
                    'jabatan' => $pegawai->jabatan ?? '-',
                    'initial' => $initial
                ];
            }
            
            return view('admin.program-edit', compact('program', 'pegawais', 'rekenings', 'pegawaiData'));
        } catch (\Exception $e) {
            return redirect()->route('program.index')
                ->with('error', 'Data program tidak ditemukan.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $program = Program::findOrFail($id);
            
            $validated = $request->validate([
                'program' => 'required|string|max:200',
                'kegiatan' => 'required|string|max:200',
                'sub_kegiatan' => 'required|string|max:200',
                'kode_rekening' => 'required|string|max:50',
                'id_pegawai' => 'required|exists:tb_pegawai,id_pegawai'
            ], [
                'program.required' => 'Nama program harus diisi',
                'program.max' => 'Nama program maksimal 200 karakter',
                'kegiatan.required' => 'Kegiatan harus diisi',
                'kegiatan.max' => 'Kegiatan maksimal 200 karakter',
                'sub_kegiatan.required' => 'Sub kegiatan harus diisi',
                'sub_kegiatan.max' => 'Sub kegiatan maksimal 200 karakter',
                'kode_rekening.required' => 'Kode rekening harus dipilih',
                'kode_rekening.max' => 'Kode rekening maksimal 50 karakter',
                'id_pegawai.required' => 'Pegawai penanggung jawab harus dipilih',
                'id_pegawai.exists' => 'Pegawai tidak valid'
            ]);
            
            $program->update($validated);
            
            return redirect()->route('program.index')
                ->with('success', 'Data program berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data program: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $program = Program::findOrFail($id);
            $programName = $program->program;
            $program->delete();
            
            // Jika request AJAX
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Data program '{$programName}' berhasil dihapus."
                ]);
            }
            
            return redirect()->route('program.index')
                ->with('success', "Data program '{$programName}' berhasil dihapus.");
        } catch (\Exception $e) {
            // Jika request AJAX
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus data program: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('program.index')
                ->with('error', 'Gagal menghapus data program: ' . $e->getMessage());
        }
    }
}