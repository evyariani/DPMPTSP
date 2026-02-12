<?php

namespace Database\Seeders;

use App\Models\Daerah;
use App\Models\UangHarian;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,    // Tambahkan UserSeeder
            PegawaiSeeder::class, // PegawaiSeeder
            ProgramSeeder::class,
            DaerahSeeder::class,
            UangHarianSeeder::class,
        ]);
    }
}