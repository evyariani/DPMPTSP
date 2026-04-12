<?php

namespace App\Http\Controllers;

use App\Models\SPT;
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    /**
     * Verifikasi keaslian SPT berdasarkan kode verifikasi
     */
    public function verify($code)
    {
        // Cari SPT berdasarkan verification_code
        $spt = SPT::where('verification_code', $code)
                  ->with(['penandaTangan', 'approvedBy'])
                  ->first();
        
        // Jika tidak ditemukan
        if (!$spt) {
            // PERUBAHAN: view di folder admin/verifikasi/invalid
            return view('admin.verifikasi.invalid', [
                'message' => 'Kode verifikasi tidak valid atau surat tidak ditemukan.'
            ]);
        }
        
        // Jika belum disetujui
        if (!$spt->isApproved()) {
            // PERUBAHAN: view di folder admin/verifikasi/invalid
            return view('admin.verifikasi.invalid', [
                'message' => 'Surat ini belum disetujui oleh Kepala Dinas.'
            ]);
        }
        
        // Catat verifikasi (untuk tracking)
        $spt->recordVerification();
        
        // Cek keaslian dokumen
        $isAuthentic = $spt->isDocumentAuthentic();
        
        // Ambil data untuk ditampilkan
        $pegawaiList = $spt->pegawai_list;
        $dasarList = $spt->dasar_list;
        
        // PERUBAHAN: view di folder admin/verifikasi/show
        return view('admin.verifikasi', compact('spt', 'pegawaiList', 'dasarList', 'isAuthentic'));
    }
    
    /**
     * API endpoint untuk verifikasi (opsional, untuk scan dari mobile app)
     */
    public function verifyApi($code)
    {
        $spt = SPT::where('verification_code', $code)->first();
        
        if (!$spt) {
            return response()->json([
                'success' => false,
                'message' => 'Kode verifikasi tidak valid'
            ], 404);
        }
        
        if (!$spt->isApproved()) {
            return response()->json([
                'success' => false,
                'message' => 'Surat belum disetujui',
                'status' => $spt->status_approval
            ], 400);
        }
        
        // Catat verifikasi
        $spt->recordVerification();
        
        return response()->json([
            'success' => true,
            'data' => [
                'nomor_surat' => $spt->nomor_surat,
                'tujuan' => $spt->tujuan,
                'tanggal' => $spt->tanggal->format('d/m/Y'),
                'lokasi' => $spt->lokasi,
                'penanda_tangan' => $spt->penandaTangan?->nama,
                'approved_at' => $spt->approved_at?->format('d/m/Y H:i:s'),
                'is_authentic' => $spt->isDocumentAuthentic(),
                'verification_count' => $spt->verification_count,
                'verified_at' => $spt->verified_at?->format('d/m/Y H:i:s')
            ]
        ]);
    }
}