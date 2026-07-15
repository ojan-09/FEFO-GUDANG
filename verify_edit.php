<?php
/**
 * Transaction Update Runtime Verification Script
 */

// Disable output buffering
ob_implicit_flush(true);

// 1. Boot CodeIgniter 4
define('FCPATH', __DIR__ . '/public/');
require __DIR__ . '/app/Config/Paths.php';
$paths = new Config\Paths();
$bootstrap = rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
$app = require $bootstrap;

use App\Modules\Transactions\Models\BarangMasukModel;
use App\Modules\Transactions\Models\BatchModel;

$bmModel = new BarangMasukModel();
$batchModel = new BatchModel();

// Find a test transaction
$bm = $bmModel->orderBy('id', 'DESC')->first();
if (!$bm) {
    echo "ERROR: No transactions found in database to test.\n";
    exit(1);
}

$id = $bm['id'];
$batchesBefore = $batchModel->where('id_barang_masuk', $id)->findAll();
if (empty($batchesBefore)) {
    echo "ERROR: Test transaction has no batches.\n";
    exit(1);
}

echo "Testing Transaction ID: {$id} (Nomor: {$bm['nomor_transaksi']})\n";
echo "Initial Batches:\n";
foreach ($batchesBefore as $b) {
    echo " - ID: {$b['id']}, Nama: {$b['nama_barang']}, Kategori: {$b['kategori']}, Qty: {$b['jumlah_awal']}, Exp: {$b['tanggal_kedaluwarsa']}\n";
}

// Record total records count before
$bmCountBefore = $bmModel->countAllResults();
$batchCountBefore = $batchModel->countAllResults();

// Prepare Mock Request
$_POST['id_donatur'] = $bm['id_donatur'];
$_POST['tanggal_masuk'] = $bm['tanggal_masuk'];
$_POST['eta'] = $bm['eta'];
$_POST['keterangan'] = $bm['keterangan'] . ' (Edited Test)';

// Mock items:
// 1. Change Kategori of the first item
// 2. Change Qty (jumlah) of the first item by +5
$items = [];
$firstBatch = $batchesBefore[0];
$oldQty = (int)$firstBatch['jumlah_awal'];
$newQty = $oldQty + 5;
$newKategori = $firstBatch['kategori'] === 'Makanan' ? 'Minuman' : 'Makanan'; // Toggle kategori

foreach ($batchesBefore as $index => $b) {
    if ($index === 0) {
        $items[1] = [
            'id' => $b['id'],
            'nama_barang' => $b['nama_barang'],
            'kategori' => $newKategori,
            'jumlah_ctn' => $b['jumlah_ctn'],
            'jumlah' => $newQty,
            'satuan' => $b['satuan'],
            'berat_per_satuan' => $b['berat_per_satuan'],
            'satuan_berat' => $b['satuan_berat'],
            'tanggal_kedaluwarsa' => $b['tanggal_kedaluwarsa']
        ];
    } else {
        $items[$index + 1] = [
            'id' => $b['id'],
            'nama_barang' => $b['nama_barang'],
            'kategori' => $b['kategori'],
            'jumlah_ctn' => $b['jumlah_ctn'],
            'jumlah' => $b['jumlah_awal'],
            'satuan' => $b['satuan'],
            'berat_per_satuan' => $b['berat_per_satuan'],
            'satuan_berat' => $b['satuan_berat'],
            'tanggal_kedaluwarsa' => $b['tanggal_kedaluwarsa']
        ];
    }
}

// Set request object post variables
$request = \Config\Services::request();
$request->setGlobal('post', $_POST);

$controller = new \App\Modules\Transactions\Controllers\BarangMasuk();
$controller->initController($request, \Config\Services::response(), \Config\Services::logger());

// Call update
echo "\nRunning update()...\n";
$response = $controller->update($id);

// Check database results
$bmAfter = $bmModel->find($id);
$batchesAfter = $batchModel->where('id_barang_masuk', $id)->findAll();

// Record total records count after
$bmCountAfter = $bmModel->countAllResults();
$batchCountAfter = $batchModel->countAllResults();

echo "\nVerification Results:\n";
$errorsCount = 0;

// Test 1: ID remains same
if ($bmAfter && $bmAfter['id'] == $id) {
    echo "[PASS] ID Transaksi tetap: {$id}\n";
} else {
    echo "[FAIL] ID Transaksi berubah!\n";
    $errorsCount++;
}

// Test 2: Nomor transaksi remains same
if ($bmAfter && $bmAfter['nomor_transaksi'] === $bm['nomor_transaksi']) {
    echo "[PASS] Nomor Transaksi tetap: {$bm['nomor_transaksi']}\n";
} else {
    echo "[FAIL] Nomor Transaksi berubah!\n";
    $errorsCount++;
}

// Test 3: Record counts did not increase
if ($bmCountBefore === $bmCountAfter) {
    echo "[PASS] Jumlah record tabel barang_masuk TIDAK bertambah (Tetap: {$bmCountBefore})\n";
} else {
    echo "[FAIL] Jumlah record tabel barang_masuk bertambah! Sebelum: {$bmCountBefore}, Sesudah: {$bmCountAfter}\n";
    $errorsCount++;
}

if ($batchCountBefore === $batchCountAfter) {
    echo "[PASS] Jumlah record tabel batch TIDAK bertambah (Tetap: {$batchCountBefore})\n";
} else {
    echo "[FAIL] Jumlah record tabel batch bertambah! Sebelum: {$batchCountBefore}, Sesudah: {$batchCountAfter}\n";
    $errorsCount++;
}

// Test 4: Batch IDs remain same
$batchesAfterMap = [];
foreach ($batchesAfter as $ba) {
    $batchesAfterMap[$ba['id']] = $ba;
}

foreach ($batchesBefore as $b) {
    if (isset($batchesAfterMap[$b['id']])) {
        echo "[PASS] Batch ID {$b['id']} tetap terdaftar.\n";
        
        // Test 5: Verify fields updated correctly
        $ba = $batchesAfterMap[$b['id']];
        if ($b['id'] == $firstBatch['id']) {
            // Check kategori changed
            if ($ba['kategori'] === $newKategori) {
                echo "       [PASS] Kategori berhasil diubah menjadi: {$newKategori}\n";
            } else {
                echo "       [FAIL] Kategori gagal diubah!\n";
                $errorsCount++;
            }
            
            // Check Qty delta
            $expectedStok = $b['stok_saat_ini'] + ($newQty - $oldQty);
            if ((int)$ba['jumlah_awal'] === $newQty && (int)$ba['stok_saat_ini'] === $expectedStok) {
                echo "       [PASS] Qty & Stok terupdate dengan delta benar (Qty: {$newQty}, Stok: {$expectedStok})\n";
            } else {
                echo "       [FAIL] Qty delta salah! Qty: {$ba['jumlah_awal']} (expected: {$newQty}), Stok: {$ba['stok_saat_ini']} (expected: {$expectedStok})\n";
                $errorsCount++;
            }
        }
    } else {
        echo "[FAIL] Batch ID {$b['id']} hilang/terbuat baru!\n";
        $errorsCount++;
    }
}

// Revert changes back for database cleanliness
echo "\nReverting database changes for cleanliness...\n";
$bmModel->update($id, [
    'keterangan' => $bm['keterangan']
]);
foreach ($batchesBefore as $b) {
    $batchModel->update($b['id'], [
        'kategori' => $b['kategori'],
        'jumlah_awal' => $b['jumlah_awal'],
        'stok_saat_ini' => $b['stok_saat_ini']
    ]);
}
echo "Database reverted successfully.\n";

if ($errorsCount === 0) {
    echo "\n=== ALL VERIFICATION TESTS PASSED SUCCESSFULLY! ===\n";
} else {
    echo "\n=== TESTS FAILED WITH {$errorsCount} ERRORS! ===\n";
}

unlink(__FILE__); // Self delete
