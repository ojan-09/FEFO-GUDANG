<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'DOKUMEN PREVIEW') ?></title>
    <style>
        @page { margin: 15mm 20mm; }
        body { font-family: "Times New Roman", Times, serif; font-size: 10pt; line-height: 1.3; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 16pt; font-weight: bold; margin: 0 0 5px 0; text-transform: uppercase; text-decoration: underline; }
        .doc-no { font-size: 11pt; font-weight: bold; margin-bottom: 20px; }
        .data-table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 15px; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 6px 8px; font-size: 10pt; }
        .data-table th { background-color: #f2f2f2; text-align: center; font-weight: bold; }
        .ketentuan { margin-top: 20px; font-size: 10pt; }
        .ketentuan ol { margin: 5px 0 0 0; padding-left: 20px; }
        .footer { margin-top: 40px; width: 100%; text-align: right; font-size: 10pt; }
    </style>
</head>
<body>
    <div class="header">
        <h1>PREVIEW DOKUMEN <?= esc($title ?? 'DOKUMEN FEFO GUDANG') ?></h1>
        <div class="doc-no">Nomor Dokumen: <?= esc($document_number ?? '401/SEM2/8/26') ?></div>
    </div>

    <p>Tanggal Diterbitkan: <strong><?= esc($date_str ?? date('d F Y')) ?></strong></p>
    <p>Dokumen ini dibuat secara otomatis dari sistem FEFO Gudang menggunakan template baku sistem.</p>

    <?php if (!empty($provisions) && is_array($provisions)): ?>
    <div class="ketentuan">
        <p><strong>KETENTUAN BERLAKU (VERSI AKTIF):</strong></p>
        <ol>
            <?php foreach ($provisions as $prov): ?>
                <li><?= esc($prov) ?></li>
            <?php endforeach; ?>
        </ol>
    </div>
    <?php endif; ?>

    <div class="footer">
        <p>Jakarta, <?= date('d F Y') ?></p>
        <p style="margin-top: 50px;"><strong>( Foodbank of Indonesia )</strong></p>
    </div>
</body>
</html>
