# Sistem Pendataan Siswa - Latis Education & Tutor Indonesia

Aplikasi web CRUD pendataan siswa terintegrasi untuk lembaga **Latiseducation** dan **Tutorindonesia** yang dibangun menggunakan **Laravel 12 (11++)**, **Tailwind CSS**, **MySQL**, **DataTables**, dan **PhpSpreadsheet**.

Aplikasi ini mencakup fitur autentikasi, session management, prepared statement pada seluruh query database, DataTables dengan custom search & filter, ekspor laporan Excel dinamis, serta manajemen profil kandidat.

---

## Kredensial Login Demo

Gunakan akun administrator / profil kandidat berikut untuk login ke aplikasi:

- **URL Login**: `/login`
- **Email**: `admin@latiseducation.com`
- **Password**: `admin123`
- **Role / Posisi**: IT Fullstack Developer

---

## Panduan Instalasi Lokal

### 1. Kebutuhan Sistem
- PHP >= 8.2 (ekstensi `pdo_mysql`, `gd`, `zip`, `mbstring` aktif)
- Composer >= 2.x
- Node.js >= 18.x & NPM
- MySQL Server (XAMPP, Laragon, Docker, dsb.)

### 2. Langkah Instalasi

1. **Clone repositori**:
   ```bash
   git clone https://github.com/sandervenz/latisedu-fullstack-test.git
   cd latisedu-fullstack-test
   ```

2. **Install dependensi PHP & JavaScript**:
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**:
   Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Buka file `.env` dan sesuaikan koneksi database MySQL:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=latisedu_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Migrasi Database & Seeding Data Awal**:
   Jalankan migrasi dan seeder untuk membuat tabel lembaga, 20 data siswa siap pakai, dan akun admin:
   ```bash
   php artisan migrate --seed
   ```
   *(Atau alternatif: buat database `latisedu_db` di phpMyAdmin lalu import file `database/latisedu_db.sql`)*.

5. **Build Aset Frontend**:
   ```bash
   npm run build
   ```

6. **Jalankan Aplikasi**:
   ```bash
   php artisan serve
   ```
   Buka browser di `http://localhost:8000`.

---

## Menjalankan Automated Tests

Aplikasi dilengkapi suite pengujian otomatis untuk memvalidasi kriteria input (NIS angka murni, penolakan simbol/huruf, format email valid):

```bash
php artisan test
```

---

## Panduan Deployment ke InfinityFree (Free Hosting)

1. **Build Aset**:
   Pastikan telah menjalankan `npm run build` di komputer lokal sehingga folder `public/build` terisi aset terkompilasi.
2. **Setup Database**:
   - Di vPanel InfinityFree, buat database baru pada menu **MySQL Databases**.
   - Buka **phpMyAdmin** database tersebut, lalu **Import** berkas `database/latisedu_db.sql`.
3. **Struktur Folder di Server**:
   - Letakkan isi dari folder `public/` lokal ke dalam folder `htdocs/` di hosting.
   - Buat folder baru `laravel_core/` (sejajar dengan `htdocs`), lalu upload seluruh file proyek lainnya ke dalam `laravel_core/` (termasuk folder `vendor` dan `.env`).
   - Edit file `htdocs/index.php` pada baris require:
     ```php
     require __DIR__.'/../laravel_core/vendor/autoload.php';
     $app = require_once __DIR__.'/../laravel_core/bootstrap/app.php';
     ```
4. **Konfigurasi `.env` di Hosting**:
   Sesuaikan `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` dengan data dari vPanel InfinityFree.

---

