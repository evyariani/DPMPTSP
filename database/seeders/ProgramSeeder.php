<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan data pegawai sudah ada
        $cekPegawai = DB::table('tb_pegawai')->count();
        
        if ($cekPegawai == 0) {
            $this->command->warn('Tidak ada data pegawai. Silakan jalankan PegawaiSeeder terlebih dahulu.');
            return;
        }

        // Data program sesuai yang Anda berikan
        $programs = [
            [
                'program' => 'Program Pelayanan Penanaman Modal',
                'kegiatan' => 'Pelayanan Perizinan dan Non Perizinan Secara Terpadu Satu Pintu di Bidang Penanaman Modal yang menjadi kewenangan daerah Kabupaten/Kota',
                'sub_kegiatan' => 'Penyediaan Pelayanan Perizinan Berusaha Melalui Sistem Perizinan Berusaha Berbasis Resiko Terintegrasi Secara elektronik',
                'kode_rekening' => '5.1.02.04.01.0003',
                'id_pegawai' => 4, // BUDI ANDRIAN SUTANTO
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'program' => 'Program Penunjang Urusan Pemerintahan Daerah Kab/Kota',
                'kegiatan' => 'Kegiatan Administrasi Umum Perangkat Daerah',
                'sub_kegiatan' => 'Penyelenggaraan Rapat Koordinasi dan Konsultasi SKPD',
                'kode_rekening' => '5.1.02.04.01.0001',
                'id_pegawai' => 8, // RINNI AULIA
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'program' => 'Program Penunjang Urusan Pemerintahan Daerah Kab / Kota',
                'kegiatan' => 'Kegiatan Administrasi Umum Perangkat Daerah',
                'sub_kegiatan' => 'Penyelenggaraan Rapat Koordinasi dan Konsultasi SKPD',
                'kode_rekening' => '5.1.02.04.01.0003',
                'id_pegawai' => 8, // RINNI AULIA
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'program' => 'Program Pelayanan Penanaman Modal',
                'kegiatan' => 'Pelayanan Perizinan dan Non Perizinan Secara Terpadu Satu Pintu di Bidang Penanaman Modal yang menjadi kewenangan daerah Kabupaten/Kota',
                'sub_kegiatan' => 'Penyediaan Pelayanan Perizinan Berusaha Melalui Sistem Perizinan Berusaha Berbasis Resiko Terintegrasi Secara elektronik',
                'kode_rekening' => '5.1.02.04.01.0003',
                'id_pegawai' => 5, // M. HAYAT
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Masukkan data program
        foreach ($programs as $program) {
            DB::table('tb_program')->insert($program);
        }

        // Hitung statistik
        $totalPrograms = count($programs);
        $rekening0001 = count(array_filter($programs, function($p) {
            return $p['kode_rekening'] === '5.1.02.04.01.0001';
        }));
        $rekening0003 = count(array_filter($programs, function($p) {
            return $p['kode_rekening'] === '5.1.02.04.01.0003';
        }));

        $this->command->info('==========================================');
        $this->command->info('Seeder Program berhasil dijalankan!');
        $this->command->info('==========================================');
        $this->command->info('Jumlah total data: ' . $totalPrograms);
        $this->command->info('Kode Rekening 5.1.02.04.01.0001: ' . $rekening0001 . ' data');
        $this->command->info('Kode Rekening 5.1.02.04.01.0003: ' . $rekening0003 . ' data');
        $this->command->info('------------------------------------------');
        
        // Tampilkan detail per pegawai
        $this->command->info('Distribusi per Pegawai:');
        foreach ($programs as $program) {
            $pegawai = DB::table('tb_pegawai')
                ->where('id_pegawai', $program['id_pegawai'])
                ->first();
            
            if ($pegawai) {
                $namaSingkat = $this->singkatNama($pegawai->nama);
                $this->command->info("• {$namaSingkat}: {$program['program']}");
            }
        }
        $this->command->info('==========================================');
    }

    /**
     * Fungsi untuk menyingkat nama
     */
    private function singkatNama($nama)
    {
        // Ambil bagian pertama sebelum koma
        $parts = explode(',', $nama);
        $namaUtama = trim($parts[0]);
        
        // Ambil inisial
        $words = explode(' ', $namaUtama);
        $inisial = '';
        
        foreach ($words as $word) {
            if (preg_match('/^[A-Z]/', $word) && !preg_match('/\./', $word)) {
                $inisial .= substr($word, 0, 1);
            }
        }
        
        return $inisial . ' (' . $namaUtama . ')';
    }
}