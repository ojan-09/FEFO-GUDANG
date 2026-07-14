<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi FEFO Gudang FOI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f3f5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 40px 36px 32px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }

        .card-header-custom {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-circle {
            width: 52px; height: 52px;
            border-radius: 12px;
            background: #0f4c35;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
        }

        .logo-circle i { font-size: 22px; color: #ffffff; }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 5px;
        }

        .card-sub {
            font-size: 12.5px;
            color: #6b7280;
        }

        .form-label-c {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
            letter-spacing: 0.02em;
        }

        .input-wrap { position: relative; margin-bottom: 16px; }

        .input-wrap .input-icon {
            position: absolute;
            left: 11px; top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            color: #9ca3af;
            pointer-events: none;
        }

        .input-c {
            width: 100%;
            height: 40px;
            padding: 0 12px 0 34px;
            font-size: 13.5px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: #f9fafb;
            color: #111827;
            outline: none;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .input-c:focus {
            border-color: #1d9e75;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(29,158,117,0.1);
        }

        .input-c::placeholder { color: #9ca3af; }

        .input-c.is-invalid {
            border-color: #dc3545;
            background: #fff5f5;
        }

        .invalid-feedback {
            font-size: 11.5px;
            color: #dc3545;
            margin-top: 4px;
        }

        .toggle-pw {
            position: absolute;
            right: 9px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            cursor: pointer;
            color: #9ca3af;
            font-size: 14px;
            padding: 4px;
            display: flex; align-items: center;
        }

        .toggle-pw:hover { color: #6b7280; }

        .remember-row {
            display: flex; align-items: center;
            margin-bottom: 20px;
        }

        .remember-check {
            display: flex; align-items: center;
            gap: 7px;
            font-size: 12px;
            color: #6b7280;
            cursor: pointer;
            user-select: none;
        }

        .remember-check input { accent-color: #0f4c35; }

        .btn-login {
            width: 100%; height: 42px;
            background: #0f4c35;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 500;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            gap: 8px;
            font-family: 'Inter', sans-serif;
            transition: background 0.15s;
            letter-spacing: 0.02em;
        }

        .btn-login:hover { background: #0c3d2a; }
        .btn-login:active { background: #092e1f; }

        .card-footer-custom {
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid #f3f4f6;
            text-align: center;
            font-size: 11.5px;
            color: #9ca3af;
            line-height: 1.6;
        }

        .foi-tag {
            display: flex; align-items: center;
            justify-content: center; gap: 6px;
            margin-top: 20px;
            font-size: 11px;
            color: #9ca3af;
        }

        .foi-dot {
            width: 3px; height: 3px;
            border-radius: 50%;
            background: #d1d5db;
            display: inline-block;
        }

        .alert-custom {
            font-size: 12.5px;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 20px;
            border: 1px solid #fca5a5;
            background: #fff5f5;
            color: #b91c1c;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .alert-custom i { margin-top: 1px; flex-shrink: 0; }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="card-header-custom">
        <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo FOI" style="max-width: 120px; height: auto; object-fit: contain; margin-bottom: 16px;">
            <div class="card-title">Masuk ke akun Anda</div>
            <div class="card-sub">Sistem Informasi Manajemen Stok Donasi — FEFO</div>
        </div>

        <?= view('Myth\Auth\Views\_message_block') ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-custom">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= url_to('login') ?>" method="post">
            <?= csrf_field() ?>

            <label class="form-label-c">Email</label>
            <div class="input-wrap">
                <i class="fa-regular fa-envelope input-icon"></i>
                <input type="email"
                       class="input-c <?= session('errors.login') ? 'is-invalid' : '' ?>"
                       name="login"
                       placeholder="nama@foodbank.id"
                       value="<?= old('login') ?>"
                       required autofocus>
                <?php if (session('errors.login')): ?>
                    <div class="invalid-feedback"><?= session('errors.login') ?></div>
                <?php endif; ?>
            </div>

            <label class="form-label-c">Password</label>
            <div class="input-wrap">
                <i class="fa-solid fa-lock input-icon"></i>
                <input type="password"
                       id="password"
                       name="password"
                       class="input-c <?= session('errors.password') ? 'is-invalid' : '' ?>"
                       placeholder="Masukkan password"
                       style="padding-right: 36px;"
                       required>
                <button class="toggle-pw" type="button" id="togglePassword" aria-label="Tampilkan password">
                    <i class="fa-regular fa-eye" id="toggleIcon"></i>
                </button>
                <?php if (session('errors.password')): ?>
                    <div class="invalid-feedback"><?= session('errors.password') ?></div>
                <?php endif; ?>
            </div>

            <?php if (config('Auth')->allowRemembering): ?>
                <div class="remember-row">
                    <label class="remember-check">
                        <input type="checkbox" name="remember" <?= old('remember') ? 'checked' : '' ?>>
                        Ingat saya
                    </label>
                </div>
            <?php endif; ?>

            <button type="submit" class="btn-login">
                <i class="fa-solid fa-right-to-bracket"></i>
                Masuk
            </button>
        </form>

        <div class="card-footer-custom">
            Lupa password? Hubungi administrator sistem Anda.
        </div>
    </div>

    <div class="foi-tag">
        <span>Foodbank of Indonesia</span>
        <span class="foi-dot"></span>
        <span>v1.0</span>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            toggleIcon.classList.toggle('fa-eye');
            toggleIcon.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>