# Panduan Instalasi FEFO Gudang

Aplikasi ini dibangun menggunakan CodeIgniter 4 (PHP 8.2+) dan MySQL 8.x.

## Persyaratan Server (Requirements)
- **PHP** versi 7.4 atau lebih baru (Disarankan 8.2+).
- **Ekstensi PHP:** `intl`, `mbstring`, `json`, `mysqlnd`, `curl`, `gd`.
- **Database:** MySQL 8.x atau MariaDB 10.x.
- **Web Server:** Apache atau Nginx dengan modul *rewrite* diaktifkan.

## Langkah Instalasi Lokal (Development)

1. **Unduh Repositori**
   Ekstrak file source code ke dalam folder *web server* lokal Anda (misal: `c:/xampp/htdocs/FEFOGUDANG/`).

2. **Konfigurasi Environment**
   - Salin file `env` menjadi `.env`.
   - Ubah konfigurasi database di dalam `.env`:
     ```env
     database.default.hostname = localhost
     database.default.database = fefodb
     database.default.username = root
     database.default.password = 
     database.default.DBDriver = MySQLi
     ```

3. **Install Dependensi (Composer)**
   Buka terminal di direktori proyek dan jalankan:
   ```bash
   composer install
   ```

4. **Restore Database**
   Anda dapat melakukan *restore* dari file `fefo_demo.sql` (jika tersedia) menggunakan phpMyAdmin, atau menggunakan sistem migrasi bawaan CI4 jika Anda memulai dari awal.

5. **Jalankan Server**
   Gunakan server internal CodeIgniter:
   ```bash
   php spark serve
   ```
   Akses `http://localhost:8080` melalui browser Anda.
