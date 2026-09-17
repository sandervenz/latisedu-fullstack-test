<?php

namespace Database\Seeders;

use App\Models\Lembaga;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Lembaga (Wajib dari Database: Latiseducation dan Tutorindonesia)
        $latis = Lembaga::firstOrCreate(['nama' => 'Latiseducation']);
        $tutor = Lembaga::firstOrCreate(['nama' => 'Tutorindonesia']);

        // 2. Seed Akun Admin / Profil Kandidat
        User::updateOrCreate(
            ['email' => 'admin@latiseducation.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'position' => 'IT Fullstack Developer',
                'image' => null,
            ]
        );

        // 3. Seed Contoh Data Siswa untuk Testing DataTables & Export
        $sampleSiswa = [
            [
                'lembaga_id' => $latis->id,
                'nis' => '1001',
                'nama' => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@example.com',
                'foto' => null,
            ],
            [
                'lembaga_id' => $latis->id,
                'nis' => '1002',
                'nama' => 'Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'foto' => null,
            ],
            [
                'lembaga_id' => $latis->id,
                'nis' => '1003',
                'nama' => 'Citra Lestari',
                'email' => 'citra.lestari@example.com',
                'foto' => null,
            ],
            [
                'lembaga_id' => $tutor->id,
                'nis' => '2001',
                'nama' => 'Dewi Anggraini',
                'email' => 'dewi.anggraini@example.com',
                'foto' => null,
            ],
            [
                'lembaga_id' => $tutor->id,
                'nis' => '2002',
                'nama' => 'Eko Prasetyo',
                'email' => 'eko.prasetyo@example.com',
                'foto' => null,
            ],
            [
                'lembaga_id' => $tutor->id,
                'nis' => '2003',
                'nama' => 'Fani Rahmawati',
                'email' => 'fani.rahma@example.com',
                'foto' => null,
            ],
        ];

        foreach ($sampleSiswa as $siswa) {
            Siswa::firstOrCreate(['nis' => $siswa['nis']], $siswa);
        }
    }
}
