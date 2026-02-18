<?php
// database/seeders/UangHarianSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Daerah;

class UangHarianSeeder extends Seeder
{
    public function run()
    {
        // Nonaktifkan foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('tb_uang_harian')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        echo "🚀 Mulai seeding data uang harian...\n\n";

        $total = 0;

        // ===========================================
        // 1. KECAMATAN DI TANAH LAUT (KALSEL)
        // ===========================================
        echo "📍 KECAMATAN (Tanah Laut):\n";
        
        $kecamatanData = [
            ['nama' => 'KINTAP', 'uang_harian' => 75000, 'transport' => 200000],
            ['nama' => 'BUMI MAKMUR', 'uang_harian' => 75000, 'transport' => 150000],
            ['nama' => 'BATI-BATI', 'uang_harian' => 75000, 'transport' => 150000],
            ['nama' => 'KURAU', 'uang_harian' => 75000, 'transport' => 150000],
            ['nama' => 'JORONG', 'uang_harian' => 75000, 'transport' => 140000],
            ['nama' => 'PANYIPATAN', 'uang_harian' => 75000, 'transport' => 150000],
            ['nama' => 'TAMBANG ULANG', 'uang_harian' => 75000, 'transport' => 120000],
            ['nama' => 'TAKISUNG', 'uang_harian' => 75000, 'transport' => 120000],
            ['nama' => 'BATU AMPAR', 'uang_harian' => 75000, 'transport' => 120000],
            ['nama' => 'BAJUIN', 'uang_harian' => 75000, 'transport' => 80000],
            ['nama' => 'PELAIHARI', 'uang_harian' => 75000, 'transport' => 0],
        ];

        foreach ($kecamatanData as $data) {
            $kecamatan = Daerah::where('nama', $data['nama'])
                ->where('tingkat', 'kecamatan')
                ->first();

            if ($kecamatan) {
                DB::table('tb_uang_harian')->insert([
                    'daerah_id' => $kecamatan->id,
                    'tempat_tujuan' => $kecamatan->nama,
                    'uang_harian' => $data['uang_harian'],
                    'uang_transport' => $data['transport'],
                    'total' => $data['uang_harian'] + $data['transport'], // INI TETAP DIISI, TAPI TIDAK DITAMPILKAN DI ECHO
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $total++;
                echo "  ✅ {$kecamatan->nama}: Rp " . number_format($data['uang_harian']) . " | Transport: Rp " . number_format($data['transport']) . "\n";
            } else {
                echo "  ❌ Kecamatan {$data['nama']} tidak ditemukan\n";
            }
        }

        // ===========================================
        // 2. KABUPATEN/KOTA DI KALIMANTAN SELATAN
        // ===========================================
        echo "\n📍 KABUPATEN/KOTA (Kalimantan Selatan):\n";
        
        $kabupatenKalselData = [
            ['nama' => 'BANJARMASIN', 'uang_harian' => 300000, 'transport' => 250000],
            ['nama' => 'BANJARBARU', 'uang_harian' => 300000, 'transport' => 250000],
            ['nama' => 'BANJAR', 'uang_harian' => 300000, 'transport' => 250000],
            ['nama' => 'BARITO KUALA', 'uang_harian' => 300000, 'transport' => 300000],
            ['nama' => 'TAPIN', 'uang_harian' => 300000, 'transport' => 300000],
            ['nama' => 'HULU SUNGAI SELATAN', 'uang_harian' => 300000, 'transport' => 350000],
            ['nama' => 'HULU SUNGAI TENGAH', 'uang_harian' => 300000, 'transport' => 375000],
            ['nama' => 'HULU SUNGAI UTARA', 'uang_harian' => 300000, 'transport' => 400000],
            ['nama' => 'BALANGAN', 'uang_harian' => 300000, 'transport' => 425000],
            ['nama' => 'TABALONG', 'uang_harian' => 300000, 'transport' => 450000],
            ['nama' => 'TANAH BUMBU', 'uang_harian' => 300000, 'transport' => 350000],
            ['nama' => 'KOTABARU', 'uang_harian' => 300000, 'transport' => 450000],
            ['nama' => 'TANAH LAUT', 'uang_harian' => 300000, 'transport' => 200000],
        ];

        foreach ($kabupatenKalselData as $data) {
            $kabupaten = Daerah::where('nama', $data['nama'])
                ->where('tingkat', 'kabupaten')
                ->first();

            if ($kabupaten) {
                $exists = DB::table('tb_uang_harian')
                    ->where('daerah_id', $kabupaten->id)
                    ->exists();
                
                if (!$exists) {
                    DB::table('tb_uang_harian')->insert([
                        'daerah_id' => $kabupaten->id,
                        'tempat_tujuan' => $kabupaten->nama,
                        'uang_harian' => $data['uang_harian'],
                        'uang_transport' => $data['transport'],
                        'total' => $data['uang_harian'] + $data['transport'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    $total++;
                    echo "  ✅ {$kabupaten->nama}: Rp " . number_format($data['uang_harian']) . " | Transport: Rp " . number_format($data['transport']) . "\n";
                }
            } else {
                echo "  ❌ Kabupaten {$data['nama']} tidak ditemukan\n";
            }
        }

        // ===========================================
        // 3. PROVINSI DI LUAR KALSEL
        // ===========================================
        echo "\n📍 PROVINSI (Luar Kalimantan Selatan):\n";
        
        $provinsiData = [
            ['nama' => 'JAWA BARAT', 'uang_harian' => 430000, 'transport' => 0],
            ['nama' => 'DKI JAKARTA', 'uang_harian' => 530000, 'transport' => 160000],
            ['nama' => 'JAWA TIMUR', 'uang_harian' => 410000, 'transport' => 150000],
            ['nama' => 'KEPULAUAN RIAU', 'uang_harian' => 370000, 'transport' => 110000],
            ['nama' => 'DI YOGYAKARTA', 'uang_harian' => 420000, 'transport' => 0],
            ['nama' => 'BALI', 'uang_harian' => 480000, 'transport' => 0],
            ['nama' => 'JAWA TENGAH', 'uang_harian' => 370000, 'transport' => 0],
            ['nama' => 'KALIMANTAN TIMUR', 'uang_harian' => 430000, 'transport' => 0],
            ['nama' => 'BANTEN', 'uang_harian' => 430000, 'transport' => 0],
            ['nama' => 'SUMATERA UTARA', 'uang_harian' => 380000, 'transport' => 0],
            ['nama' => 'SUMATERA BARAT', 'uang_harian' => 380000, 'transport' => 0],
            ['nama' => 'RIAU', 'uang_harian' => 380000, 'transport' => 0],
            ['nama' => 'SUMATERA SELATAN', 'uang_harian' => 370000, 'transport' => 0],
            ['nama' => 'LAMPUNG', 'uang_harian' => 370000, 'transport' => 0],
            ['nama' => 'KALIMANTAN BARAT', 'uang_harian' => 400000, 'transport' => 0],
            ['nama' => 'KALIMANTAN TENGAH', 'uang_harian' => 400000, 'transport' => 0],
            ['nama' => 'KALIMANTAN UTARA', 'uang_harian' => 420000, 'transport' => 0],
            ['nama' => 'SULAWESI SELATAN', 'uang_harian' => 410000, 'transport' => 0],
            ['nama' => 'SULAWESI UTARA', 'uang_harian' => 400000, 'transport' => 0],
            ['nama' => 'PAPUA', 'uang_harian' => 550000, 'transport' => 0],
            ['nama' => 'PAPUA BARAT', 'uang_harian' => 550000, 'transport' => 0],
        ];

        foreach ($provinsiData as $data) {
            $provinsi = Daerah::where('nama', $data['nama'])
                ->where('tingkat', 'provinsi')
                ->first();

            if ($provinsi) {
                $exists = DB::table('tb_uang_harian')
                    ->where('daerah_id', $provinsi->id)
                    ->exists();
                
                if (!$exists) {
                    DB::table('tb_uang_harian')->insert([
                        'daerah_id' => $provinsi->id,
                        'tempat_tujuan' => $provinsi->nama,
                        'uang_harian' => $data['uang_harian'],
                        'uang_transport' => $data['transport'],
                        'total' => $data['uang_harian'] + $data['transport'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    $total++;
                    echo "  ✅ {$provinsi->nama}: Rp " . number_format($data['uang_harian']) . " | Transport: Rp " . number_format($data['transport']) . "\n";
                }
            } else {
                echo "  ❌ Provinsi {$data['nama']} tidak ditemukan\n";
            }
        }

        // ===========================================
        // 4. KOTA KHUSUS (BALIKPAPAN, DENPASAR)
        // ===========================================
        echo "\n📍 KOTA (Khusus):\n";
        
        $kotaData = [
            ['nama' => 'BALIKPAPAN', 'uang_harian' => 430000, 'transport' => 0],
            ['nama' => 'DENPASAR', 'uang_harian' => 480000, 'transport' => 0],
        ];

        foreach ($kotaData as $data) {
            $kota = Daerah::where('nama', $data['nama'])
                ->where('tingkat', 'kabupaten')
                ->first();

            if ($kota) {
                $exists = DB::table('tb_uang_harian')
                    ->where('daerah_id', $kota->id)
                    ->exists();
                
                if (!$exists) {
                    DB::table('tb_uang_harian')->insert([
                        'daerah_id' => $kota->id,
                        'tempat_tujuan' => $kota->nama,
                        'uang_harian' => $data['uang_harian'],
                        'uang_transport' => $data['transport'],
                        'total' => $data['uang_harian'] + $data['transport'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    $total++;
                    echo "  ✅ {$kota->nama}: Rp " . number_format($data['uang_harian']) . " | Transport: Rp " . number_format($data['transport']) . "\n";
                }
            } else {
                echo "  ❌ Kota {$data['nama']} tidak ditemukan\n";
            }
        }

        // ===========================================
        // SUMMARY
        // ===========================================
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "🎉 SEEDER UANG HARIAN SELESAI!\n";
        echo "📊 Total data: " . $total . " record\n";
        echo "📋 Tabel: tb_uang_harian\n";
        echo str_repeat("=", 60) . "\n";
    }
}