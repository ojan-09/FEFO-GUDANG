<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
    $isEdit = isset($barang);
    $action = $isEdit ? site_url('masterdata/barang/update/' . $barang['id']) : site_url('masterdata/barang/store');
    $errors = session()->getFlashdata('errors') ?? [];
?>

<!-- Topbar -->
<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-cubes me-2"></i><?= $title ?></h1>
        <span class="subtle"><?= $isEdit ? 'Ubah data barang' : 'Tambah barang baru ke gudang' ?></span>
    </div>
    <a href="<?= site_url('masterdata/barang') ?>" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<!-- Form -->
<div class="panel-card">
    <form action="<?= $action ?>" method="POST" id="formBarang" class="loading-form" data-overlay="true">
        <?= csrf_field() ?>
        <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-md-6">
                <!-- Kode Barang -->
                <div class="mb-3">
                    <label for="kode_barang" class="form-label fw-semibold">Kode Barang</label>
                    <input type="text" class="form-control" id="kode_barang" name="kode_barang"
                        value="<?= old('kode_barang', $isEdit ? $barang['kode_barang'] : $kode_barang) ?>" 
                        readonly style="background: #e2e8f0; cursor: not-allowed;">
                    <div class="form-text">Dibuat otomatis oleh sistem.</div>
                </div>

                <!-- Nama Barang -->
                <div class="mb-3">
                    <label for="nama_barang" class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?= isset($errors['nama_barang']) ? 'is-invalid' : '' ?>" 
                        id="nama_barang" name="nama_barang" placeholder="Contoh: Beras Premium 5 Kg"
                        maxlength="100" required
                        value="<?= old('nama_barang', $isEdit ? $barang['nama_barang'] : '') ?>">
                    <?php if (isset($errors['nama_barang'])) : ?>
                        <div class="invalid-feedback"><?= esc($errors['nama_barang']) ?></div>
                    <?php endif; ?>
                </div>

                <!-- Kategori -->
                <div class="mb-3">
                    <label for="id_kategori" class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                    <select class="form-select <?= isset($errors['id_kategori']) ? 'is-invalid' : '' ?>" 
                        id="id_kategori" name="id_kategori" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach ($kategori as $k) : ?>
                            <option value="<?= $k['id'] ?>" <?= old('id_kategori', $isEdit ? $barang['id_kategori'] : '') == $k['id'] ? 'selected' : '' ?>>
                                <?= esc($k['nama_kategori']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['id_kategori'])) : ?>
                        <div class="invalid-feedback"><?= esc($errors['id_kategori']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-6">
                <!-- Satuan -->
                <div class="mb-3">
                    <label for="satuan" class="form-label fw-semibold">Satuan <span class="text-danger">*</span></label>
                    <select class="form-select <?= isset($errors['satuan']) ? 'is-invalid' : '' ?>" 
                        id="satuan" name="satuan" required>
                        <option value="">-- Pilih Satuan --</option>
                        <?php
                            $satuanOpsi = ['Karung', 'Dus', 'Box', 'Pack', 'Pcs', 'Botol', 'Kaleng', 'Sak', 'Tray', 'Pouch'];
                            foreach ($satuanOpsi as $s) :
                        ?>
                            <option value="<?= $s ?>" <?= old('satuan', $isEdit ? $barang['satuan'] : '') == $s ? 'selected' : '' ?>>
                                <?= $s ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['satuan'])) : ?>
                        <div class="invalid-feedback"><?= esc($errors['satuan']) ?></div>
                    <?php endif; ?>
                </div>

                <!-- Berat per Satuan & Satuan Berat -->
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="berat_per_satuan" class="form-label fw-semibold">Berat per Satuan <span class="text-danger">*</span></label>
                            <input type="number" step="0.001" min="0.001" 
                                class="form-control <?= isset($errors['berat_per_satuan']) ? 'is-invalid' : '' ?>" 
                                id="berat_per_satuan" name="berat_per_satuan" placeholder="Contoh: 0.25" required
                                value="<?= old('berat_per_satuan', $isEdit ? $barang['berat_per_satuan'] : '') ?>">
                            <?php if (isset($errors['berat_per_satuan'])) : ?>
                                <div class="invalid-feedback"><?= esc($errors['berat_per_satuan']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="satuan_berat" class="form-label fw-semibold">Satuan Berat <span class="text-danger">*</span></label>
                            <select class="form-select <?= isset($errors['satuan_berat']) ? 'is-invalid' : '' ?>" 
                                id="satuan_berat" name="satuan_berat" required>
                                <option value="">-- Pilih --</option>
                                <?php
                                    $beratOpsi = ['Gram', 'Kg'];
                                    foreach ($beratOpsi as $bo) :
                                ?>
                                    <option value="<?= $bo ?>" <?= old('satuan_berat', $isEdit ? $barang['satuan_berat'] : '') == $bo ? 'selected' : '' ?>>
                                        <?= $bo ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['satuan_berat'])) : ?>
                                <div class="invalid-feedback"><?= esc($errors['satuan_berat']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Minimum Stok -->
                <div class="mb-3">
                    <label for="minimum_stok" class="form-label fw-semibold">Minimum Stok <span class="text-danger">*</span></label>
                    <input type="number" min="0" step="1" 
                        class="form-control <?= isset($errors['minimum_stok']) ? 'is-invalid' : '' ?>" 
                        id="minimum_stok" name="minimum_stok" placeholder="Contoh: 10" required
                        value="<?= old('minimum_stok', $isEdit ? $barang['minimum_stok'] : '0') ?>">
                    <?php if (isset($errors['minimum_stok'])) : ?>
                        <div class="invalid-feedback"><?= esc($errors['minimum_stok']) ?></div>
                    <?php endif; ?>
                    <div class="form-text">Bilangan bulat non-negatif. Sistem menandai barang jika stok di bawah angka ini.</div>
                </div>

                <!-- Bisa Dipecah (Repack) -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Bisa Dipecah (Repack) <span class="text-danger">*</span></label>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bisa_dipecah" id="bisa_dipecah_ya" value="1" 
                                <?= old('bisa_dipecah', $isEdit ? $barang['bisa_dipecah'] : '') == '1' ? 'checked' : '' ?> required>
                            <label class="form-check-label" for="bisa_dipecah_ya">Ya (Repack ke Kg)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bisa_dipecah" id="bisa_dipecah_tidak" value="0" 
                                <?= old('bisa_dipecah', $isEdit ? $barang['bisa_dipecah'] : '0') == '0' ? 'checked' : '' ?> required>
                            <label class="form-check-label" for="bisa_dipecah_tidak">Tidak (Kemasan Utuh)</label>
                        </div>
                    </div>
                    <?php if (isset($errors['bisa_dipecah'])) : ?>
                        <div class="text-danger small mt-1"><?= esc($errors['bisa_dipecah']) ?></div>
                    <?php endif; ?>
                    <div class="form-text">Menentukan apakah barang ini dapat disalurkan dalam berat desimal (Kg) atau wajib utuh per kemasan.</div>
                </div>
            </div>
        </div>

        <!-- Section Preview Dinamis -->
        <div class="card mb-3 border-light-subtle shadow-sm" style="background-color: #f8fafc; border-radius: 12px;">
            <div class="card-body py-3 d-flex align-items-center">
                <i class="fa-solid fa-circle-info text-primary fs-4 me-3"></i>
                <div>
                    <small class="text-muted d-block uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.05em;">PREVIEW SATUAN BERAT</small>
                    <strong class="text-dark fs-5" id="previewSatuan">1 Satuan = 0 Kg</strong>
                </div>
            </div>
        </div>

        <hr>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary rounded-pill px-4">
                <i class="fa-solid fa-<?= $isEdit ? 'save' : 'plus' ?> me-1"></i> <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Barang' ?>
            </button>
            <a href="<?= site_url('masterdata/barang') ?>" class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Elements
        const selectSatuan = document.getElementById("satuan");
        const inputBerat = document.getElementById("berat_per_satuan");
        const selectSatuanBerat = document.getElementById("satuan_berat");
        const previewText = document.getElementById("previewSatuan");
        const radioBisaDipecahYa = document.getElementById("bisa_dipecah_ya");
        const radioBisaDipecahTidak = document.getElementById("bisa_dipecah_tidak");

        // Function to update preview
        function updatePreview() {
            const satuan = selectSatuan.value || "[Satuan]";
            const berat = parseFloat(inputBerat.value) || 0;
            const satuanBerat = selectSatuanBerat.value || "[Satuan Berat]";
            previewText.textContent = `1 ${satuan} = ${berat} ${satuanBerat}`;
        }

        // Function to handle Repack (Bisa Dipecah) limitation
        function syncRepack() {
            const isKarung = selectSatuan.value === 'Karung';
            radioBisaDipecahYa.disabled = !isKarung;
            
            if (!isKarung) {
                radioBisaDipecahTidak.checked = true;
                radioBisaDipecahYa.closest('.form-check').title = 'Repack hanya berlaku untuk kemasan Karung';
            } else {
                radioBisaDipecahYa.closest('.form-check').title = '';
            }
        }

        // Event listeners for preview and logic
        selectSatuan.addEventListener("change", () => {
            updatePreview();
            syncRepack();
        });
        inputBerat.addEventListener("input", updatePreview);
        selectSatuanBerat.addEventListener("change", updatePreview);

        // Initial run
        updatePreview();
        syncRepack();

        // Focus first invalid element automatically for better UX
        const firstInvalid = document.querySelector(".is-invalid");
        if (firstInvalid) {
            firstInvalid.focus();
        }
    });
</script>
<?= $this->endSection() ?>
