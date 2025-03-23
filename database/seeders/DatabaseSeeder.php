<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nrp' => '2372002',
                'nik' => null,
                'nama' => 'Jennifer',
                'role' => 'mahasiswa',
                'email' => 'jennifer@gmail.com',
                'password' => Hash::make('mahasiswa123'),
            ],
            [
                'nrp' => null,
                'nik' => '123456',
                'nama' => 'Drs. Agustria Empi',
                'role' => 'kaprodi',
                'email' => 'dosen@gmail.com',
                'password' => Hash::make('dosen123'),
            ],
            [
                'nrp' => null,
                'nik' => '789012',
                'nama' => 'Julianti',
                'role' => 'tu',
                'email' => 'tu@example.com',
                'password' => Hash::make('karyawan123'),
            ],
        ]);
    }
}
