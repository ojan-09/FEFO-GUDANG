<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi FEFO Gudang FOI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #2563EB;
            --success: #15803D;
            --success-dark: #166534;
            --text-main: #0F172A;
            --text-muted: #64748B;
            --border-c: #E5E7EB;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body { height: 100%; overflow: hidden; }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(160deg, #F8FAFC 0%, #F1F5F9 100%);
            padding: 16px;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border: 1px solid var(--border-c);
            border-radius: 18px;
            padding: 24px 32px 20px;
            box-shadow: 0 10px 30px rgba(15,23,42,0.08);
            opacity: 0;
            transform: translateY(14px);
            animation: cardIn 0.4s ease-out forwards;
        }

        @keyframes cardIn {
            to { opacity: 1; transform: translateY(0); }
        }

        .card-header-custom {
            text-align: center;
            margin-bottom: 16px;
        }

        .card-header-custom img {
            width: 72px;
            height: auto;
            object-fit: contain;
            margin-bottom: 8px;
        }

        .card-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: -0.01em;
            margin-bottom: 4px;
        }

        .card-sub {
            font-size: 13.5px;
            color: var(--text-muted);
            line-height: 1.45;
        }

        .form-label-c {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 7px;
        }

        .input-wrap { position: relative; margin-bottom: 11px; }

        .input-wrap .input-icon {
            position: absolute;
            left: 16px; top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            color: #94a3b8;
            pointer-events: none;
            transition: color 0.15s;
        }

        .input-c {
            width: 100%;
            height: 48px;
            padding: 0 16px 0 44px;
            font-size: 15px;
            border: 1.5px solid var(--border-c);
            border-radius: 12px;
            background: #F8FAFC;
            color: var(--text-main);
            outline: none;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
        }

        .input-c:focus {
            border-color: var(--primary);
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.10);
        }

        .input-wrap:focus-within .input-icon { color: var(--primary); }

        .input-c::placeholder { color: #b6c0cc; }

        .input-c.is-invalid {
            border-color: #dc3545;
            background: #fff5f5;
        }

        .invalid-feedback {
            font-size: 12px;
            color: #dc3545;
            margin-top: 5px;
        }

        .toggle-pw {
            position: absolute;
            right: 12px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            cursor: pointer;
            color: #94a3b8;
            font-size: 15px;
            padding: 6px;
            display: flex; align-items: center;
            transition: color 0.15s;
        }

        .toggle-pw:hover { color: var(--primary); }

        .remember-row {
            display: flex;
            align-items: center;
            margin-bottom: 14px;
        }

        .remember-check {
            display: flex; align-items: center;
            gap: 7px;
            font-size: 14px;
            color: #475569;
            cursor: pointer;
            user-select: none;
        }

        .remember-check input {
            width: 15px; height: 15px;
            accent-color: var(--success);
            cursor: pointer;
        }

        .btn-login {
            width: 100%; height: 48px;
            background: var(--success);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            gap: 9px;
            font-family: 'Inter', sans-serif;
            box-shadow: 0 4px 10px rgba(21,128,61,0.18);
            transition: background 0.15s, transform 0.1s;
        }

        .btn-login:hover { background: var(--success-dark); }
        .btn-login:active { transform: scale(0.99); }

        .btn-login.is-loading .btn-login-label,
        .btn-login.is-loading .btn-login-icon { visibility: hidden; }

        .btn-spinner {
            display: none;
            position: absolute;
            width: 18px; height: 18px;
            border: 2.5px solid rgba(255,255,255,0.4);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        .btn-login.is-loading .btn-spinner { display: inline-block; }

        @keyframes spin { to { transform: rotate(360deg); } }

        .card-footer-custom {
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px solid #f1f5f9;
            text-align: center;
            font-size: 13px;
            color: #94a3b8;
        }

        .alert-custom {
            font-size: 13px;
            border-radius: 10px;
            padding: 10px 14px;
            margin-bottom: 18px;
            border: 1px solid #fca5a5;
            background: #fff5f5;
            color: #b91c1c;
            display: flex;
            align-items: flex-start;
            gap: 9px;
        }

        .alert-custom i { margin-top: 1px; flex-shrink: 0; }

        @media (prefers-reduced-motion: reduce) {
            .login-card { animation: none; opacity: 1; transform: none; }
            .btn-spinner { animation: none; }
        }

        @media (max-width: 480px) {
            .login-card { padding: 26px 22px; }
            .card-title { font-size: 26px; }
        }
    </style>
</head>
<body>

    <div class="login-card">

        <div class="card-header-custom">
            <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo Foodbank of Indonesia">
            <div class="card-title">Selamat Datang</div>
            <div class="card-sub">Masuk ke akun Anda untuk mengakses sistem Warehouse Management Foodbank Indonesia.</div>
        </div>

        <?= view('Myth\Auth\Views\_message_block') ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-custom">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= url_to('login') ?>" method="post" id="loginForm">
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
                       style="padding-right: 40px;"
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

            <button type="submit" class="btn-login" id="btnLogin">
                <span class="btn-spinner"></span>
                <i class="fa-solid fa-right-to-bracket btn-login-icon"></i>
                <span class="btn-login-label">Masuk</span>
            </button>
        </form>

        <div class="card-footer-custom">
            Lupa password? Hubungi administrator sistem Anda.
        </div>

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

        const loginForm = document.getElementById('loginForm');
        const btnLogin = document.getElementById('btnLogin');
        loginForm.addEventListener('submit', function () {
            btnLogin.classList.add('is-loading');
        });
    </script>
</body>
</html>