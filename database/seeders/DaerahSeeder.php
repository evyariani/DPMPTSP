<?php
// database/seeders/DaerahSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DaerahSeeder extends Seeder
{
    public function run()
    {
        // Nonaktifkan foreign key checks sementara
        Schema::disableForeignKeyConstraints();
        DB::table('tb_daerah')->truncate();
        Schema::enableForeignKeyConstraints();
        
        echo "🚀 Mulai seeding tb_daerah...\n\n";
        
        // ===========================================
        // 1. Semua Provinsi di Indonesia
        // ===========================================
        $provinsi = [
            ['kode' => '11', 'nama' => 'Aceh'],
            ['kode' => '12', 'nama' => 'Sumatera Utara'],
            ['kode' => '13', 'nama' => 'Sumatera Barat'],
            ['kode' => '14', 'nama' => 'Riau'],
            ['kode' => '15', 'nama' => 'Jambi'],
            ['kode' => '16', 'nama' => 'Sumatera Selatan'],
            ['kode' => '17', 'nama' => 'Bengkulu'],
            ['kode' => '18', 'nama' => 'Lampung'],
            ['kode' => '19', 'nama' => 'Kepulauan Bangka Belitung'],
            ['kode' => '21', 'nama' => 'Kepulauan Riau'],
            ['kode' => '31', 'nama' => 'Dki Jakarta'],
            ['kode' => '32', 'nama' => 'Jawa Barat'],
            ['kode' => '33', 'nama' => 'Jawa Tengah'],
            ['kode' => '34', 'nama' => 'Di Yogyakarta'],
            ['kode' => '35', 'nama' => 'Jawa Timur'],
            ['kode' => '36', 'nama' => 'Banten'],
            ['kode' => '51', 'nama' => 'Bali'],
            ['kode' => '52', 'nama' => 'Nusa Tenggara Barat'],
            ['kode' => '53', 'nama' => 'Nusa Tenggara Timur'],
            ['kode' => '61', 'nama' => 'Kalimantan Barat'],
            ['kode' => '62', 'nama' => 'Kalimantan Tengah'],
            ['kode' => '63', 'nama' => 'Kalimantan Selatan'],
            ['kode' => '64', 'nama' => 'Kalimantan Timur'],
            ['kode' => '65', 'nama' => 'Kalimantan Utara'],
            ['kode' => '71', 'nama' => 'Sulawesi Utara'],
            ['kode' => '72', 'nama' => 'Sulawesi Tengah'],
            ['kode' => '73', 'nama' => 'Sulawesi Selatan'],
            ['kode' => '74', 'nama' => 'Sulawesi Tenggara'],
            ['kode' => '75', 'nama' => 'Gorontalo'],
            ['kode' => '76', 'nama' => 'Sulawesi Barat'],
            ['kode' => '81', 'nama' => 'Maluku'],
            ['kode' => '82', 'nama' => 'Maluku Utara'],
            ['kode' => '91', 'nama' => 'Papua'],
            ['kode' => '92', 'nama' => 'Papua Barat'],
            ['kode' => '93', 'nama' => 'Papua Selatan'],
            ['kode' => '94', 'nama' => 'Papua Tengah'],
            ['kode' => '95', 'nama' => 'Papua Pegunungan'],
        ];
        
        foreach ($provinsi as $p) {
            DB::table('tb_daerah')->insert([
                'kode' => $p['kode'],
                'nama' => $p['nama'],
                'tingkat' => 'provinsi',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        echo "✅ " . count($provinsi) . " Provinsi (Seluruh Indonesia)\n";
        
        // ===========================================
        // 2. Seluruh Kabupaten/Kota di Kalimantan Selatan
        // ===========================================
        $kabupaten = [
            ['kode' => '6301', 'nama' => 'Tanah Laut', 'provinsi_kode' => '63'],
            ['kode' => '6302', 'nama' => 'Kotabaru', 'provinsi_kode' => '63'],
            ['kode' => '6303', 'nama' => 'Banjar', 'provinsi_kode' => '63'],
            ['kode' => '6304', 'nama' => 'Barito Kuala', 'provinsi_kode' => '63'],
            ['kode' => '6305', 'nama' => 'Tapin', 'provinsi_kode' => '63'],
            ['kode' => '6306', 'nama' => 'Hulu Sungai Selatan', 'provinsi_kode' => '63'],
            ['kode' => '6307', 'nama' => 'Hulu Sungai Tengah', 'provinsi_kode' => '63'],
            ['kode' => '6308', 'nama' => 'Hulu Sungai Utara', 'provinsi_kode' => '63'],
            ['kode' => '6309', 'nama' => 'Tabalong', 'provinsi_kode' => '63'],
            ['kode' => '6310', 'nama' => 'Tanah Bumbu', 'provinsi_kode' => '63'],
            ['kode' => '6311', 'nama' => 'Balangan', 'provinsi_kode' => '63'],
            ['kode' => '6371', 'nama' => 'Banjarmasin', 'provinsi_kode' => '63'],
            ['kode' => '6372', 'nama' => 'Banjarbaru', 'provinsi_kode' => '63'],
        ];
        
        $kabupaten_count = 0;
        foreach ($kabupaten as $k) {
            $parent = DB::table('tb_daerah')
                ->where('kode', $k['provinsi_kode'])
                ->where('tingkat', 'provinsi')
                ->first();
            
            if ($parent) {
                DB::table('tb_daerah')->insert([
                    'kode' => $k['kode'],
                    'nama' => $k['nama'],
                    'tingkat' => 'kabupaten',
                    'parent_id' => $parent->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $kabupaten_count++;
            }
        }
        
        echo "✅ " . $kabupaten_count . " Kabupaten/Kota (Se-Kalimantan Selatan)\n";
        
        // ===========================================
        // 3. Kecamatan - Hanya di Kabupaten Tanah Laut
        // ===========================================
        $kecamatan = [
            ['kode' => '630101', 'nama' => 'Pelaihari', 'kabupaten_kode' => '6301'],
            ['kode' => '630102', 'nama' => 'Bati-Bati', 'kabupaten_kode' => '6301'],
            ['kode' => '630103', 'nama' => 'Jorong', 'kabupaten_kode' => '6301'],
            ['kode' => '630104', 'nama' => 'Kurau', 'kabupaten_kode' => '6301'],
            ['kode' => '630105', 'nama' => 'Takisung', 'kabupaten_kode' => '6301'],
            ['kode' => '630106', 'nama' => 'Bumi Makmur', 'kabupaten_kode' => '6301'],
            ['kode' => '630107', 'nama' => 'Kintap', 'kabupaten_kode' => '6301'],
            ['kode' => '630108', 'nama' => 'Tambang Ulang', 'kabupaten_kode' => '6301'],
            ['kode' => '630109', 'nama' => 'Bajuin', 'kabupaten_kode' => '6301'],
        ];
        
        $kecamatan_count = 0;
        foreach ($kecamatan as $k) {
            $parent = DB::table('tb_daerah')
                ->where('kode', $k['kabupaten_kode'])
                ->where('tingkat', 'kabupaten')
                ->first();
            
            if ($parent) {
                DB::table('tb_daerah')->insert([
                    'kode' => $k['kode'],
                    'nama' => $k['nama'],
                    'tingkat' => 'kecamatan',
                    'parent_id' => $parent->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $kecamatan_count++;
            }
        }
        
        echo "✅ " . $kecamatan_count . " Kecamatan (Kabupaten Tanah Laut)\n";
        
        $total = DB::table('tb_daerah')->count();
        echo "\n🎉 Total: " . $total . " data tb_daerah\n";
        echo "   - " . count($provinsi) . " Provinsi (Seluruh Indonesia)\n";
        echo "   - 13 Kabupaten/Kota (Se-Kalimantan Selatan)\n";
        echo "   - 9 Kecamatan (Kabupaten Tanah Laut)\n";
    }
}