<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'username' => 'admin1',
                'password' => Hash::make('admin1'),
                'level' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'kadis',
                'password' => Hash::make('kadis'),
                'level' => 'kadis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'aci',
                'password' => Hash::make('aci'),
                'level' => 'pegawai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
