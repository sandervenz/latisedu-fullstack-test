<?php

namespace Database\Seeders;

use App\Models\Lembaga;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Create an attractive local avatar image using GD library
     */
    private function generateLocalAvatar(string $filename, array $rgb, string $initial): void
    {
        $dir = public_path('uploads/siswa');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $width = 200;
        $height = 200;
        $img = imagecreatetruecolor($width, $height);

        // Solid background
        $bg = imagecolorallocate($img, $rgb[0], $rgb[1], $rgb[2]);
        imagefilledrectangle($img, 0, 0, $width - 1, $height - 1, $bg);

        // Crisp white silhouette avatar
        $white = imagecolorallocate($img, 255, 255, 255);
        // Head circle
        imagefilledellipse($img, 100, 72, 68, 68, $white);
        // Shoulder curve
        imagefilledellipse($img, 100, 186, 134, 108, $white);

        // Save as clean JPEG (approx ~4-6 KB, well under 100 KB limit)
        imagejpeg($img, $dir . DIRECTORY_SEPARATOR . $filename, 90);
        imagedestroy($img);
    }

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

        // 3. Hapus data siswa lama agar fresh dan konsisten
        DB::table('siswas')->delete();

        // 4. Data 20 Siswa Lengkap (10 Latiseducation, 10 Tutorindonesia) dengan Gambar Lokal
        $students = [
            // --- Lembaga 1: Latiseducation (10 Siswa, NIS 1001-1010) ---
            [
                'lembaga_id' => $latis->id,
                'nis' => '1001',
                'nama' => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@latiseducation.com',
                'color' => [249, 115, 22], // Orange Brand
            ],
            [
                'lembaga_id' => $latis->id,
                'nis' => '1002',
                'nama' => 'Budi Santoso',
                'email' => 'budi.santoso@latiseducation.com',
                'color' => [59, 130, 246], // Blue
            ],
            [
                'lembaga_id' => $latis->id,
                'nis' => '1003',
                'nama' => 'Citra Lestari',
                'email' => 'citra.lestari@latiseducation.com',
                'color' => [16, 185, 129], // Emerald
            ],
            [
                'lembaga_id' => $latis->id,
                'nis' => '1004',
                'nama' => 'Dimas Pratama',
                'email' => 'dimas.pratama@latiseducation.com',
                'color' => [139, 92, 246], // Violet
            ],
            [
                'lembaga_id' => $latis->id,
                'nis' => '1005',
                'nama' => 'Eka Saputri',
                'email' => 'eka.saputri@latiseducation.com',
                'color' => [236, 72, 153], // Pink
            ],
            [
                'lembaga_id' => $latis->id,
                'nis' => '1006',
                'nama' => 'Farhan Maulana',
                'email' => 'farhan.maulana@latiseducation.com',
                'color' => [6, 182, 212], // Cyan
            ],
            [
                'lembaga_id' => $latis->id,
                'nis' => '1007',
                'nama' => 'Gita Nirmala',
                'email' => 'gita.nirmala@latiseducation.com',
                'color' => [245, 158, 11], // Amber
            ],
            [
                'lembaga_id' => $latis->id,
                'nis' => '1008',
                'nama' => 'Hendra Wijaya',
                'email' => 'hendra.wijaya@latiseducation.com',
                'color' => [99, 102, 241], // Indigo
            ],
            [
                'lembaga_id' => $latis->id,
                'nis' => '1009',
                'nama' => 'Indah Permata',
                'email' => 'indah.permata@latiseducation.com',
                'color' => [20, 184, 166], // Teal
            ],
            [
                'lembaga_id' => $latis->id,
                'nis' => '1010',
                'nama' => 'Joko Susilo',
                'email' => 'joko.susilo@latiseducation.com',
                'color' => [225, 29, 72], // Rose
            ],

            // --- Lembaga 2: Tutorindonesia (10 Siswa, NIS 2001-2010) ---
            [
                'lembaga_id' => $tutor->id,
                'nis' => '2001',
                'nama' => 'Kartika Sari',
                'email' => 'kartika.sari@tutorindonesia.co.id',
                'color' => [34, 197, 94], // Green
            ],
            [
                'lembaga_id' => $tutor->id,
                'nis' => '2002',
                'nama' => 'Lukman Hakim',
                'email' => 'lukman.hakim@tutorindonesia.co.id',
                'color' => [14, 165, 233], // Sky
            ],
            [
                'lembaga_id' => $tutor->id,
                'nis' => '2003',
                'nama' => 'Maya Anggraini',
                'email' => 'maya.anggraini@tutorindonesia.co.id',
                'color' => [168, 85, 247], // Purple
            ],
            [
                'lembaga_id' => $tutor->id,
                'nis' => '2004',
                'nama' => 'Naufal Rizky',
                'email' => 'naufal.rizky@tutorindonesia.co.id',
                'color' => [244, 63, 94], // Crimson
            ],
            [
                'lembaga_id' => $tutor->id,
                'nis' => '2005',
                'nama' => 'Olivia Putri',
                'email' => 'olivia.putri@tutorindonesia.co.id',
                'color' => [234, 88, 12], // Deep Orange
            ],
            [
                'lembaga_id' => $tutor->id,
                'nis' => '2006',
                'nama' => 'Panji Gumilang',
                'email' => 'panji.gumilang@tutorindonesia.co.id',
                'color' => [37, 99, 235], // Royal Blue
            ],
            [
                'lembaga_id' => $tutor->id,
                'nis' => '2007',
                'nama' => 'Qori Fatimah',
                'email' => 'qori.fatimah@tutorindonesia.co.id',
                'color' => [5, 150, 105], // Forest
            ],
            [
                'lembaga_id' => $tutor->id,
                'nis' => '2008',
                'nama' => 'Rian Hidayat',
                'email' => 'rian.hidayat@tutorindonesia.co.id',
                'color' => [124, 58, 237], // Deep Violet
            ],
            [
                'lembaga_id' => $tutor->id,
                'nis' => '2009',
                'nama' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@tutorindonesia.co.id',
                'color' => [217, 70, 239], // Fuchsia
            ],
            [
                'lembaga_id' => $tutor->id,
                'nis' => '2010',
                'nama' => 'Teguh Prasetya',
                'email' => 'teguh.prasetya@tutorindonesia.co.id',
                'color' => [13, 148, 136], // Deep Teal
            ],
        ];

        foreach ($students as $data) {
            $filename = 'siswa_' . $data['nis'] . '.jpg';
            $this->generateLocalAvatar($filename, $data['color'], substr($data['nama'], 0, 1));

            Siswa::create([
                'lembaga_id' => $data['lembaga_id'],
                'nis' => $data['nis'],
                'nama' => $data['nama'],
                'email' => $data['email'],
                'foto' => $filename,
            ]);
        }
    }
}
