<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>403 Forbidden</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            text-align: center;
            padding: 50px;
        }
        h1 {
            font-size: 50px;
            color: #e74c3c;
            margin-bottom: 10px;
        }
        p {
            font-size: 18px;
            margin-bottom: 20px;
        }
        a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #e74c3c;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        a:hover {
            background-color: #c0392b;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>403</h1>
        <h2>Akses Ditolak</h2>
        <p><?= esc($message ?? 'Maaf, Anda tidak memiliki hak akses (role) yang cukup untuk membuka halaman ini.') ?></p>
        <a href="<?= site_url('/') ?>">Kembali ke Dashboard</a>
    </div>
</body>
</html>

