<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Admin APOTEK</title>

    <link rel="shortcut icon" href="{{ asset('admin/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: #f0fdf4;
        }

        .login-container {
            display: flex;
            min-height: 100vh;
        }

        .login-left {
            flex: 0 0 480px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 3.5rem;
            background: #ffffff;
            position: relative;
            z-index: 2;
            box-shadow: 4px 0 30px rgba(0, 0, 0, 0.08);
        }

        .brand {
            margin-bottom: 2.5rem;
        }

        .brand a {
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
        }

        .brand-text {
            font-size: 1.6rem;
            font-weight: 800;
            color: #15803d;
            letter-spacing: -0.5px;
        }

        .login-heading h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 0.5rem;
        }

        .login-heading p {
            font-size: 0.95rem;
            color: #94a3b8;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i.main-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.1rem;
            transition: color 0.3s;
            pointer-events: none;
        }

        /* Style untuk Tombol Mata */
        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            z-index: 10;
        }

        .toggle-password:hover {
            color: #15803d;
        }

        .form-input {
            width: 100%;
            padding: 0.85rem 2.8rem 0.85rem 2.8rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            background: #f8fafc;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-input:focus {
            border-color: #22c55e;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
        }

        .form-input:focus+i.main-icon {
            color: #22c55e;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .checkbox-wrapper label {
            font-size: 0.88rem;
            color: #64748b;
            cursor: pointer;
        }

        .btn-login {
            width: 100%;
            padding: 0.9rem;
            background: linear-gradient(135deg, #22c55e, #15803d);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #16a34a, #15803d);
            box-shadow: 0 8px 25px rgba(34, 197, 94, 0.35);
            transform: translateY(-1px);
        }

        .alert-error {
            padding: 0.75rem 1rem;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            color: #dc2626;
            font-size: 0.88rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .login-right {
            flex: 1;
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 50%, #15803d 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .right-content {
            text-align: center;
            color: white;
            z-index: 1;
            padding: 2rem;
            max-width: 500px;
        }

        .right-content .hero-icon {
            width: 90px;
            height: 90px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .right-content .hero-icon i {
            font-size: 2.5rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            text-align: left;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.8rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        @media (max-width: 768px) {
            .login-left {
                flex: none;
                width: 100%;
                min-height: 100vh;
            }

            .login-right {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-left">
            <div class="brand">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('landing-page/img/logo.png') }}" alt="Logo" style="height: 50px;">
                    <span class="brand-text ms-2">APOTEK</span>
                </a>
            </div>

            <div class="login-heading">
                <h1>Selamat Datang</h1>
                <p>Masuk ke panel administrator untuk mengelola sistem apotek.</p>
            </div>

            @if ($errors->any())
            <div class="alert-error">
                <i class="bi bi-exclamation-circle"></i>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <form action="{{ route('admin.login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email Admin</label>
                    <div class="input-wrapper">
                        <input type="email" class="form-input" name="email" placeholder="admin@mail.com"
                            value="{{ old('email') }}" required autofocus>
                        <i class="bi bi-envelope main-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="passwordInput" class="form-input" name="password" placeholder="Masukkan password" required>
                        <i class="bi bi-lock main-icon"></i>
                        <i class="bi bi-eye toggle-password" id="eyeIcon"></i>
                    </div>
                </div>

                <div class="form-options">
                    <div class="checkbox-wrapper">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Ingat Saya</label>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Masuk Sekarang
                </button>
            </form>

            <div class="login-footer">
                <a href="{{ url('/') }}" style="color: #15803d; text-decoration: none; font-size: 0.9rem; display: block; margin-top: 1rem;">&larr; Kembali ke halaman utama</a>
            </div>
        </div>

        <div class="login-right">
            <div class="right-content">
                <div class="hero-icon">
                    <i class="bi bi-capsule"></i>
                </div>
                <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.75rem;">Sistem Informasi Apotek</h2>
                <p style="margin-bottom: 2rem; opacity: 0.85;">Kelola persediaan obat, transaksi penjualan, dan laporan keuangan apotek Anda secara cepat dan akurat.</p>

                <div class="features-grid">
                    <div class="feature-item"><i class="bi bi-box-seam"></i><span>Manajemen Stok</span></div>
                    <div class="feature-item"><i class="bi bi-cart-check"></i><span>Point of Sale</span></div>
                    <div class="feature-item"><i class="bi bi-graph-up-arrow"></i><span>Laporan Penjualan</span></div>
                    <div class="feature-item"><i class="bi bi-shield-check"></i><span>Keamanan Data</span></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Script untuk Toggle Password (Mata)
        const passwordInput = document.getElementById('passwordInput');
        const eyeIcon = document.getElementById('eyeIcon');

        eyeIcon.addEventListener('click', function() {
            // Cek tipe input
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Ganti ikon
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
    </script>
</body>

</html>