# SOP Pemulihan Darurat (Disaster Recovery)

Jika terjadi insiden, misalnya salah hapus data atau _deploy_ gagal, Administrator dapat mengembalikan data ke titik yang sehat (Restore).

## Syarat Utama
Anda harus memiliki sebuah file berekstensi `.sql` yang valid dari sistem ini (FEFO Gudang). Pastikan file `.sha256` juga tersedia jika Anda menyalinnya secara manual (opsional namun direkomendasikan).

## Proses Pemulihan via Antarmuka (Web)
1. Login sebagai **Administrator**.
2. Masuk ke menu **Pengaturan > Backup & Restore**.
3. Jika file berada di komputer Anda:
   - Pilih *Upload File SQL* di _Card_ sebelah kanan.
   - Klik **Unggah & Restore**.
4. Jika file sudah ada di tabel *Riwayat Backup*:
   - Klik ikon "Restore" (Berwarna Merah dengan ikon panah melingkar).
5. Sistem akan menampilkan _pop-up_ peringatan. Klik persetujuan.
6. Aplikasi akan otomatis membuat satu **Restore Point** baru sebagai pengaman (sebelum meniban data lama).
7. Jika sukses, data akan kembali seperti sedia kala.

## Proses Pemulihan via Terminal (Darurat)
Jika antarmuka web lumpuh, Administrator dapat memulihkan *database* melalui terminal (SSH):
```bash
# Pindah ke direktori instalasi aplikasi
cd /path/to/fefo_core/

# Jalankan perintah pemulihan (Pastikan file berada di writable/backups/)
php spark backup:restore backup_20260720_0200.sql
```
