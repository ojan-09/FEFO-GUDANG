# Panduan Deployment (Rilis ke VPS / Shared Hosting)

Aplikasi FEFO Gudang siap diunggah ke Production. Harap perhatikan struktur _folder_ agar keamanan tetap terjaga.

## Pre Go-Live Security Checklist
Sebelum aplikasi di-_online_-kan, pastikan:
1. `CI_ENVIRONMENT` diset ke `production` di dalam file `.env.production` (atau ubah `.env` di server) agar mematikan fungsi *Debug Toolbar* dan menyembunyikan pesan kesalahan sistem dari layar publik. Catatan: JANGAN ubah `CI_ENVIRONMENT` menjadi production di laptop development lokal Anda agar proses pengembangan tidak terganggu.
2. `app.baseURL` di dalam `.env` disesuaikan dengan nama domain Anda (Misal: `https://gudang.domain.com/`).
3. Folder `writable/` memiliki izin baca/tulis (`chmod 775` atau `777` tergantung konfigurasi VPS).

## Pre Go-Live Functional Checklist
Pastikan seluruh fitur kritis berjalan di lingkungan Production:
- [ ] Backup database terakhir dibuat (Gunakan nama `backup_demo.sql` jika masih perlu mempertahankan data dummy)
- [ ] Restore backup berhasil diuji (dari file lokal maupun *restore point*)
- [ ] Semua role telah diuji (Administrator, Kepala Gudang, dsb)
- [ ] Semua menu dapat diakses tanpa *error* 404/500
- [ ] Upload file berjalan (pastikan *permissions* di `writable/uploads` aman)
- [ ] Export PDF berfungsi dengan baik
- [ ] Export Excel berfungsi dengan baik
- [ ] Login
- [ ] Logout
- [ ] Password Reset
- [ ] System Health & Telemetri bekerja (Memory, Disk, & Cache terbaca)

## Manajemen Data Dummy (Untuk Kebutuhan Demo)
Jika aplikasi ini digunakan untuk sidang skripsi atau demo:
- **Jangan** langsung menghapus data dummy.
- Buat dan simpan salinan cadangan dengan nama khusus (contoh: `backup_demo.sql`) melalui fitur Backup & Restore.
- Jika Anda harus membersihkan *database* untuk production riil, Anda selalu bisa me-restore dari `backup_demo.sql` kapan pun sidang atau demo dibutuhkan kembali.

## Langkah Upload (Shared Hosting cPanel)
Sangat disarankan untuk tidak mengekspos direktori `app/` atau `system/` ke _public_html_.
1. Buat _folder_ baru bernama `fefo_core` sejajar dengan `public_html` (di luar root web).
2. Unggah seluruh _file_ aplikasi ke dalam `fefo_core` **KECUALI** folder `public/`.
3. Buka folder `public/` di komputer Anda, lalu unggah seluruh isinya (termasuk `.htaccess` dan `index.php`) ke dalam `public_html/`.
4. Edit file `index.php` di dalam `public_html/` untuk menyesuaikan jalur *paths*:
   ```php
   // Ubah:
   $pathsPath = realpath(FCPATH . '../app/Config/Paths.php');
   
   // Menjadi:
   $pathsPath = realpath(FCPATH . '../fefo_core/app/Config/Paths.php');
   ```

## Menjadwalkan Auto Backup (Cronjob)
Di Linux VPS atau cPanel Anda, daftarkan tugas berikut untuk dieksekusi otomatis:
```bash
0 2 * * * /usr/bin/php /path/to/fefo_core/spark backup:run daily
```
Ini akan mengeksekusi *backup* harian otomatis pada jam 02:00 pagi setiap hari.
