<?php

namespace App\Http\Controllers;

use App\Models\Kwitansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KwitansiController extends Controller
{
    public function index()
    {
        $kwitansi = Kwitansi::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.kwitansi', compact('kwitansi'));
    }

    public function create()
    {
        return view('admin.kwitansi-create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun_anggaran' => 'required|string|max:4',
            'kode_rekening' => 'required|string|max:50',
            'sub_kegiatan' => 'required|string|max:255',
            'no_bku' => 'required|string|max:100',
            'no_brpp' => 'nullable|string|max:100',
            'terbilang' => 'required|string|max:255',
            'untuk_pembayaran' => 'required|string',
            'nominal' => 'required|numeric|min:0',
            'tanggal_kwitansi' => 'required|date',
            'pengguna_anggaran' => 'required|string|max:255',
            'nip_pengguna_anggaran' => 'required|string|max:50',
            'bendahara_pengeluaran' => 'required|string|max:255',
            'nip_bendahara' => 'required|string|max:50',
            'penerima' => 'required|string|max:255',
            'nip_penerima' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            $kwitansi = Kwitansi::create($request->all());
            
            DB::commit();
            
            // ✅ PERUBAHAN: redirect ke INDEX (halaman depan) bukan ke show
            return redirect()->route('kwitansi.index')
                ->with('success', 'Kwitansi berhasil disimpan!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal membuat kwitansi: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $kwitansi = Kwitansi::findOrFail($id);
        return view('admin.kwitansi-detail', compact('kwitansi'));
    }

    public function edit($id)
    {
        $kwitansi = Kwitansi::findOrFail($id);
        return view('admin.kwitansi-edit', compact('kwitansi'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tahun_anggaran' => 'required|string|max:4',
            'kode_rekening' => 'required|string|max:50',
            'sub_kegiatan' => 'required|string|max:255',
            'no_bku' => 'required|string|max:100',
            'no_brpp' => 'nullable|string|max:100',
            'terbilang' => 'required|string|max:255',
            'untuk_pembayaran' => 'required|string',
            'nominal' => 'required|numeric|min:0',
            'tanggal_kwitansi' => 'required|date',
            'pengguna_anggaran' => 'required|string|max:255',
            'nip_pengguna_anggaran' => 'required|string|max:50',
            'bendahara_pengeluaran' => 'required|string|max:255',
            'nip_bendahara' => 'required|string|max:50',
            'penerima' => 'required|string|max:255',
            'nip_penerima' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            $kwitansi = Kwitansi::findOrFail($id);
            $kwitansi->update($request->all());
            
            DB::commit();
            
            return redirect()->route('admin.kwitansi')
                ->with('success', 'Kwitansi berhasil diperbarui!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui kwitansi: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $kwitansi = Kwitansi::findOrFail($id);
            $kwitansi->delete();
            
            DB::commit();
            
            return redirect()->route('kwitansi.index')
                ->with('success', 'Kwitansi berhasil dihapus!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus kwitansi: ' . $e->getMessage());
        }
    }

    public function cetak($id)
    {
        $kwitansi = Kwitansi::findOrFail($id);
        return view('admin.kwitansi-pdf', compact('kwitansi'));
    }
    
    public function previewPdf($id)
    {
        $kwitansi = Kwitansi::findOrFail($id);
        return view('admin.kwitansi-preview', compact('kwitansi'));
    }
    
    public function print($id)
    {
    $kwitansi = Kwitansi::findOrFail($id);
    return view('kwitansi.pdf', compact('kwitansi'));
    }
    
    public function exportPdf($id)
    {
        $kwitansi = Kwitansi::findOrFail($id);
        return view('admin.kwitansi-print', compact('kwitansi'));
    }
}