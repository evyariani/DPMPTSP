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
        // 1. PROVINSI
        // ===========================================
        $provinsi = [
            ['kode' => '11', 'nama' => 'ACEH'],
            ['kode' => '12', 'nama' => 'SUMATERA UTARA'],
            ['kode' => '13', 'nama' => 'SUMATERA BARAT'],
            ['kode' => '14', 'nama' => 'RIAU'],
            ['kode' => '15', 'nama' => 'JAMBI'],
            ['kode' => '16', 'nama' => 'SUMATERA SELATAN'],
            ['kode' => '17', 'nama' => 'BENGKULU'],
            ['kode' => '18', 'nama' => 'LAMPUNG'],
            ['kode' => '19', 'nama' => 'KEPULAUAN BANGKA BELITUNG'],
            ['kode' => '21', 'nama' => 'KEPULAUAN RIAU'],
            ['kode' => '31', 'nama' => 'DKI JAKARTA'],
            ['kode' => '32', 'nama' => 'JAWA BARAT'],
            ['kode' => '33', 'nama' => 'JAWA TENGAH'],
            ['kode' => '34', 'nama' => 'DI YOGYAKARTA'],
            ['kode' => '35', 'nama' => 'JAWA TIMUR'],
            ['kode' => '36', 'nama' => 'BANTEN'],
            ['kode' => '51', 'nama' => 'BALI'],
            ['kode' => '52', 'nama' => 'NUSA TENGGARA BARAT'],
            ['kode' => '53', 'nama' => 'NUSA TENGGARA TIMUR'],
            ['kode' => '61', 'nama' => 'KALIMANTAN BARAT'],
            ['kode' => '62', 'nama' => 'KALIMANTAN TENGAH'],
            ['kode' => '63', 'nama' => 'KALIMANTAN SELATAN'],
            ['kode' => '64', 'nama' => 'KALIMANTAN TIMUR'],
            ['kode' => '65', 'nama' => 'KALIMANTAN UTARA'],
            ['kode' => '71', 'nama' => 'SULAWESI UTARA'],
            ['kode' => '72', 'nama' => 'SULAWESI TENGAH'],
            ['kode' => '73', 'nama' => 'SULAWESI SELATAN'],
            ['kode' => '74', 'nama' => 'SULAWESI TENGGARA'],
            ['kode' => '75', 'nama' => 'GORONTALO'],
            ['kode' => '76', 'nama' => 'SULAWESI BARAT'],
            ['kode' => '81', 'nama' => 'MALUKU'],
            ['kode' => '82', 'nama' => 'MALUKU UTARA'],
            ['kode' => '91', 'nama' => 'PAPUA'],
            ['kode' => '92', 'nama' => 'PAPUA BARAT'],
            ['kode' => '93', 'nama' => 'PAPUA SELATAN'],
            ['kode' => '94', 'nama' => 'PAPUA TENGAH'],
            ['kode' => '95', 'nama' => 'PAPUA PEGUNUNGAN'],
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
        
        echo "✅ " . count($provinsi) . " PROVINSI\n";
        
        // ===========================================
        // 2. KABUPATEN
        // ===========================================
        $kabupaten = [
            // KALIMANTAN SELATAN (63)
            ['kode' => '6301', 'nama' => 'TANAH LAUT', 'provinsi_kode' => '63'],
            ['kode' => '6302', 'nama' => 'KOTABARU', 'provinsi_kode' => '63'],
            ['kode' => '6303', 'nama' => 'BANJAR', 'provinsi_kode' => '63'],
            ['kode' => '6304', 'nama' => 'BARITO KUALA', 'provinsi_kode' => '63'],
            ['kode' => '6305', 'nama' => 'TAPIN', 'provinsi_kode' => '63'],
            ['kode' => '6306', 'nama' => 'HULU SUNGAI SELATAN', 'provinsi_kode' => '63'],
            ['kode' => '6307', 'nama' => 'HULU SUNGAI TENGAH', 'provinsi_kode' => '63'],
            ['kode' => '6308', 'nama' => 'HULU SUNGAI UTARA', 'provinsi_kode' => '63'],
            ['kode' => '6309', 'nama' => 'TABALONG', 'provinsi_kode' => '63'],
            ['kode' => '6310', 'nama' => 'TANAH BUMBU', 'provinsi_kode' => '63'],
            ['kode' => '6311', 'nama' => 'BALANGAN', 'provinsi_kode' => '63'],
            ['kode' => '6371', 'nama' => 'BANJARMASIN', 'provinsi_kode' => '63'],
            ['kode' => '6372', 'nama' => 'BANJARBARU', 'provinsi_kode' => '63'],
            
            // KALIMANTAN BARAT (61)
            ['kode' => '6101', 'nama' => 'SAMBAS', 'provinsi_kode' => '61'],
            ['kode' => '6102', 'nama' => 'MEMPAWAH', 'provinsi_kode' => '61'],
            ['kode' => '6103', 'nama' => 'SANGGAU', 'provinsi_kode' => '61'],
            ['kode' => '6104', 'nama' => 'KETAPANG', 'provinsi_kode' => '61'],
            ['kode' => '6105', 'nama' => 'SINTANG', 'provinsi_kode' => '61'],
            ['kode' => '6106', 'nama' => 'KAPUAS HULU', 'provinsi_kode' => '61'],
            ['kode' => '6107', 'nama' => 'BENGKAYANG', 'provinsi_kode' => '61'],
            ['kode' => '6108', 'nama' => 'LANDAK', 'provinsi_kode' => '61'],
            ['kode' => '6109', 'nama' => 'SEKADAU', 'provinsi_kode' => '61'],
            ['kode' => '6110', 'nama' => 'MELAWI', 'provinsi_kode' => '61'],
            ['kode' => '6111', 'nama' => 'KAYONG UTARA', 'provinsi_kode' => '61'],
            ['kode' => '6112', 'nama' => 'KUBU RAYA', 'provinsi_kode' => '61'],
            ['kode' => '6171', 'nama' => 'PONTIANAK', 'provinsi_kode' => '61'],
            ['kode' => '6172', 'nama' => 'SINGKAWANG', 'provinsi_kode' => '61'],
            
            // KALIMANTAN TENGAH (62)
            ['kode' => '6201', 'nama' => 'KOTAWARINGIN BARAT', 'provinsi_kode' => '62'],
            ['kode' => '6202', 'nama' => 'KOTAWARINGIN TIMUR', 'provinsi_kode' => '62'],
            ['kode' => '6203', 'nama' => 'KAPUAS', 'provinsi_kode' => '62'],
            ['kode' => '6204', 'nama' => 'BARITO SELATAN', 'provinsi_kode' => '62'],
            ['kode' => '6205', 'nama' => 'BARITO UTARA', 'provinsi_kode' => '62'],
            ['kode' => '6206', 'nama' => 'KATINGAN', 'provinsi_kode' => '62'],
            ['kode' => '6207', 'nama' => 'SERUYAN', 'provinsi_kode' => '62'],
            ['kode' => '6208', 'nama' => 'SUKAMARA', 'provinsi_kode' => '62'],
            ['kode' => '6209', 'nama' => 'LAMANDAU', 'provinsi_kode' => '62'],
            ['kode' => '6210', 'nama' => 'GUNUNG MAS', 'provinsi_kode' => '62'],
            ['kode' => '6211', 'nama' => 'PULANG PISAU', 'provinsi_kode' => '62'],
            ['kode' => '6212', 'nama' => 'MURUNG RAYA', 'provinsi_kode' => '62'],
            ['kode' => '6213', 'nama' => 'BARITO TIMUR', 'provinsi_kode' => '62'],
            ['kode' => '6271', 'nama' => 'PALANGKA RAYA', 'provinsi_kode' => '62'],
            
            // KALIMANTAN TIMUR (64)
            ['kode' => '6401', 'nama' => 'PASER', 'provinsi_kode' => '64'],
            ['kode' => '6402', 'nama' => 'KUTAI KARTANEGARA', 'provinsi_kode' => '64'],
            ['kode' => '6403', 'nama' => 'BERAU', 'provinsi_kode' => '64'],
            ['kode' => '6407', 'nama' => 'KUTAI BARAT', 'provinsi_kode' => '64'],
            ['kode' => '6408', 'nama' => 'KUTAI TIMUR', 'provinsi_kode' => '64'],
            ['kode' => '6409', 'nama' => 'PENAJAM PASER UTARA', 'provinsi_kode' => '64'],
            ['kode' => '6411', 'nama' => 'MAHAKAM ULU', 'provinsi_kode' => '64'],
            ['kode' => '6471', 'nama' => 'BALIKPAPAN', 'provinsi_kode' => '64'],
            ['kode' => '6472', 'nama' => 'SAMARINDA', 'provinsi_kode' => '64'],
            ['kode' => '6474', 'nama' => 'BONTANG', 'provinsi_kode' => '64'],
            
            // KALIMANTAN UTARA (65)
            ['kode' => '6501', 'nama' => 'MALINAU', 'provinsi_kode' => '65'],
            ['kode' => '6502', 'nama' => 'BULUNGAN', 'provinsi_kode' => '65'],
            ['kode' => '6503', 'nama' => 'TANA TIDUNG', 'provinsi_kode' => '65'],
            ['kode' => '6504', 'nama' => 'NUNUKAN', 'provinsi_kode' => '65'],
            ['kode' => '6571', 'nama' => 'TARAKAN', 'provinsi_kode' => '65'],
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
        
        echo "✅ " . $kabupaten_count . " KABUPATEN/KOTA\n";
        
        // ===========================================
        // 3. KECAMATAN
        // ===========================================
        $kecamatan = [
            // TANAH LAUT (6301)
            ['kode' => '630101', 'nama' => 'PELAIHARI', 'kabupaten_kode' => '6301'],
            ['kode' => '630102', 'nama' => 'BATI-BATI', 'kabupaten_kode' => '6301'],
            ['kode' => '630103', 'nama' => 'JORONG', 'kabupaten_kode' => '6301'],
            ['kode' => '630104', 'nama' => 'KURAU', 'kabupaten_kode' => '6301'],
            ['kode' => '630105', 'nama' => 'TAKISUNG', 'kabupaten_kode' => '6301'],
            ['kode' => '630106', 'nama' => 'BUMI MAKMUR', 'kabupaten_kode' => '6301'],
            ['kode' => '630107', 'nama' => 'KINTAP', 'kabupaten_kode' => '6301'],
            ['kode' => '630108', 'nama' => 'TAMBANG ULANG', 'kabupaten_kode' => '6301'],
            ['kode' => '630109', 'nama' => 'BAJUIN', 'kabupaten_kode' => '6301'],
            
            // BANJARMASIN (6371)
            ['kode' => '637101', 'nama' => 'BANJARMASIN SELATAN', 'kabupaten_kode' => '6371'],
            ['kode' => '637102', 'nama' => 'BANJARMASIN TIMUR', 'kabupaten_kode' => '6371'],
            ['kode' => '637103', 'nama' => 'BANJARMASIN BARAT', 'kabupaten_kode' => '6371'],
            ['kode' => '637104', 'nama' => 'BANJARMASIN UTARA', 'kabupaten_kode' => '6371'],
            ['kode' => '637105', 'nama' => 'BANJARMASIN TENGAH', 'kabupaten_kode' => '6371'],
            
            // BANJARBARU (6372)
            ['kode' => '637201', 'nama' => 'BANJARBARU UTARA', 'kabupaten_kode' => '6372'],
            ['kode' => '637202', 'nama' => 'BANJARBARU SELATAN', 'kabupaten_kode' => '6372'],
            ['kode' => '637203', 'nama' => 'CEMPAKA', 'kabupaten_kode' => '6372'],
            ['kode' => '637204', 'nama' => 'LANDASAN ULIN', 'kabupaten_kode' => '6372'],
            ['kode' => '637205', 'nama' => 'LIANG ANGGANG', 'kabupaten_kode' => '6372'],
            
            // BANJAR (6303)
            ['kode' => '630301', 'nama' => 'ALUH-ALUH', 'kabupaten_kode' => '6303'],
            ['kode' => '630302', 'nama' => 'KERTAK HANYAR', 'kabupaten_kode' => '6303'],
            ['kode' => '630303', 'nama' => 'GAMBUT', 'kabupaten_kode' => '6303'],
            ['kode' => '630304', 'nama' => 'SUNGAI TABUK', 'kabupaten_kode' => '6303'],
            ['kode' => '630305', 'nama' => 'MARTAPURA', 'kabupaten_kode' => '6303'],
            ['kode' => '630306', 'nama' => 'KARANG INTAN', 'kabupaten_kode' => '6303'],
            
            // PONTIANAK (6171)
            ['kode' => '617101', 'nama' => 'PONTIANAK SELATAN', 'kabupaten_kode' => '6171'],
            ['kode' => '617102', 'nama' => 'PONTIANAK TIMUR', 'kabupaten_kode' => '6171'],
            ['kode' => '617103', 'nama' => 'PONTIANAK BARAT', 'kabupaten_kode' => '6171'],
            ['kode' => '617104', 'nama' => 'PONTIANAK UTARA', 'kabupaten_kode' => '6171'],
            ['kode' => '617105', 'nama' => 'PONTIANAK KOTA', 'kabupaten_kode' => '6171'],
            
            // SAMARINDA (6472)
            ['kode' => '647201', 'nama' => 'PALARAN', 'kabupaten_kode' => '6472'],
            ['kode' => '647202', 'nama' => 'SAMARINDA SEBERANG', 'kabupaten_kode' => '6472'],
            ['kode' => '647203', 'nama' => 'SAMARINDA ULU', 'kabupaten_kode' => '6472'],
            ['kode' => '647204', 'nama' => 'SAMARINDA ILIR', 'kabupaten_kode' => '6472'],
            ['kode' => '647205', 'nama' => 'SAMARINDA UTARA', 'kabupaten_kode' => '6472'],
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
        
        echo "✅ " . $kecamatan_count . " KECAMATAN\n";
        
        $total = DB::table('tb_daerah')->count();
        echo "\n🎉 TOTAL: " . $total . " data tb_daerah\n";
    }
}