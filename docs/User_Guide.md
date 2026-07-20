# Panduan Pengguna (User Guide)

Aplikasi FEFO Gudang mengatur tata kelola logistik masuk dan keluar berdasarkan skema First-Expired-First-Out (FEFO).

## Hak Akses (Role)
* **Operator:** Dapat mengakses Transaksi (Barang Masuk, Penyaluran, Penyesuaian, Repack) serta Laporan.
* **Petugas:** Hanya dapat menginput Barang Masuk. (Hak terbatas sesuai kebijakan).

## Transaksi
1. **Donasi Masuk:** Menginput barang yang diterima gudang beserta dengan tanggal kedaluwarsanya (*Expired Date*).
2. **Penyaluran (Keluar):** Mendistribusikan barang. Sistem akan secara otomatis memotong stok barang yang waktu kedaluwarsanya paling mendekati hari ini (FEFO).
3. **Penyesuaian Stok (Adjustment):** Jika terjadi selisih pencatatan atau kerusakan fisik barang, catat di sini agar stok *real-time* selaras dengan fisik di gudang.
4. **Repack (Kembali Ulang):** Pemecahan barang ukuran besar (Misal: 1 Karung Beras 50kg) menjadi barang eceran (50 pcs Beras 1kg). Repack memotong stok _parent_ dan menambah stok _child_.

## Laporan
Pilih rentang tanggal pada halaman Laporan untuk melihat mutasi barang. Anda juga bisa men-_download_ laporan dalam bentuk Excel maupun PDF.
