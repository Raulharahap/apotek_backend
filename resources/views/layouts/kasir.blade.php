<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir</title>
    <link rel="stylesheet" crossorigin href="{{ asset('admin/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" crossorigin href="{{ asset('admin/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --kasir-primary: #2563eb;
            --kasir-primary-light: #eff6ff;
            --kasir-bg: #F4F6F8;
            --kasir-border: #E5E7EB;
        }

        body {
            background-color: var(--kasir-bg);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            overflow: hidden;
        }

        .text-primary {
            color: var(--kasir-primary) !important;
        }

        .bg-primary {
            background-color: var(--kasir-primary) !important;
        }

        .btn-primary {
            background-color: var(--kasir-primary);
            border-color: var(--kasir-primary);
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
        }

        .text-black {
            color: #111827 !important;
        }

        .text-dark {
            color: #1e293b !important;
        }

        /* Navbar */
        .navbar-kasir {
            background: white;
            border-bottom: 1px solid var(--kasir-border);
            padding: 0 24px;
            min-height: 55px !important;
            height: 55px !important;
            backdrop-filter: blur(10px);
        }

        .form-check-input:checked {
            background-color: var(--kasir-primary);
            border-color: var(--kasir-primary);
        }

        /* --- HELPER ICON CENTER PRESISI --- */
        .btn-icon-center {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            padding: 0 !important;
        }

        .btn-icon-center i {
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 0 !important;
        }

        .btn-play-custom i {
            transform: translateX(1.5px);
        }

        .btn-close-custom i {
            font-size: 1.4rem;
            color: #64748b;
        }

        .btn-close-custom:hover i {
            color: #dc2626;
        }

        /* ========================================= */
        /* FULL SUPPORT DARK MODE & KUNCI UKURAN     */
        /* ========================================= */
        [data-bs-theme="dark"] .navbar-kasir {
            background-color: #1e1e2d !important;
            border-bottom: 1px solid #2d2d3e !important;
        }

        [data-bs-theme="dark"] body {
            background-color: #151521;
        }

        [data-bs-theme="dark"] .text-black,
        [data-bs-theme="dark"] .text-dark {
            color: #f8fafc !important;
        }

        [data-bs-theme="dark"] .text-muted {
            color: #9ca3af !important;
        }

        [data-bs-theme="dark"] hr {
            border-color: #4b5563 !important;
        }

        [data-bs-theme="dark"] .bg-white {
            background-color: #1e1e2d !important;
        }

        /* Tombol +/- di Keranjang Dikunci agar tidak membesar */
        [data-bs-theme="dark"] .btn-light {
            background-color: #2d2d3e !important;
            border: 1px solid #4b5563 !important;
            color: #f8fafc !important;
        }

        [data-bs-theme="dark"] .btn-light:hover {
            background-color: #4b5563 !important;
        }
    </style>
</head>

<body>
    <script src="{{ asset('admin/assets/static/js/initTheme.js') }}"></script>

    <nav class="navbar navbar-expand-lg navbar-kasir sticky-top">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary text-white d-flex align-items-center justify-content-center rounded-2 shadow-sm" style="width: 30px; height: 30px; font-weight: bold; font-size: 1rem;">
                    K
                </div>
                <h6 class="mb-0 fw-bold me-2 text-black" style="letter-spacing: 0.5px;">KASIR</h6>
                <div class="vr" style="height: 20px; opacity: 0.2;"></div>
                <div class="d-flex align-items-baseline gap-2 text-black">
                    <span class="fw-bold" id="realtime-clock-time" style="font-size: 1.15rem;">--.--</span>
                    <small class="fw-bold text-muted" id="realtime-clock-date" style="font-size: 0.8rem;">--, -- --- ----</small>
                </div>
            </div>

            <div class="collapse navbar-collapse justify-content-end align-items-center">
                <div class="d-flex align-items-center gap-4 me-4">
                    <a href="{{ url('/admin/dashboard') }}" class="text-decoration-none text-black fw-bold d-inline-flex align-items-center gap-2" style="font-size: 0.9rem;">
                        <i class="bi bi-house-door" style="font-size: 1.1rem; line-height: 0;"></i> Dashboard
                    </a>
                    <a href="{{ route('kasir.history') }}" class="text-decoration-none text-black fw-bold d-inline-flex align-items-center gap-2" style="font-size: 0.9rem;">
                        <i class="bi bi-clock-history" style="font-size: 1.1rem; line-height: 0;"></i> Riwayat
                    </a>
                </div>

                <div class="theme-toggle d-flex gap-2 align-items-center me-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 21 21" class="text-black">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4z" opacity=".3"></path>
                            <g transform="translate(-210 -1)">
                                <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                <circle cx="220.5" cy="11.5" r="4"></circle>
                                <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path>
                            </g>
                        </g>
                    </svg>
                    <div class="form-check form-switch fs-6 mb-0 d-flex align-items-center">
                        <input class="form-check-input me-0" type="checkbox" id="toggle-dark" style="cursor: pointer; margin-top: 0;">
                        <label class="form-check-label"></label>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" class="text-black">
                        <path fill="currentColor" d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z"></path>
                    </svg>
                </div>

                <div class="dropdown border-start ps-3 ms-2">
                    <a href="#" data-bs-toggle="dropdown" aria-expanded="false" class="text-decoration-none">
                        <div class="user-menu d-flex align-items-center gap-2">
                            <div class="user-name text-end d-none d-sm-block">
                                <h6 class="mb-0 text-black fw-bold" style="font-size: 0.85rem;">{{ Auth::user()->name ?? 'Arya Dwi Putra' }}</h6>
                                <p class="mb-0 text-muted" style="font-size: 0.75rem; line-height: 1;">Kasir</p>
                            </div>
                            <div class="user-img d-flex align-items-center">
                                <div class="avatar avatar-md bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-weight: 600; font-size: 0.85rem;">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'AP', 0, 2)) }}
                                </div>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm mt-2">
                        <li><a class="dropdown-item d-flex align-items-center gap-2" href="#"><i class="bi bi-person"></i> Profil</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ url('/logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                    <i class="bi bi-box-arrow-left"></i> Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid p-0">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('admin/assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('admin/assets/compiled/js/app.js') }}"></script>

    <script>
        function updateClock() {
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

            const timeStr = now.getHours().toString().padStart(2, '0') + '.' + now.getMinutes().toString().padStart(2, '0');
            const dateStr = days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();

            document.getElementById('realtime-clock-time').innerText = timeStr;
            document.getElementById('realtime-clock-date').innerText = dateStr;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
    @stack('scripts')
</body>

</html>