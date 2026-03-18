<div class="sidebar-wrapper active">
    <div class="sidebar-header position-relative">
        <div class="d-flex justify-content-between align-items-center">
            <div class="logo">
                <a href="{{ url('/admin/dashboard') }}" class="d-flex align-items-center">
                    <img src="{{ asset('landing-page/img/logo.png') }}" alt="Logo" style="height: 40px;">
                    <span class="fs-4 fw-bold text-primary ms-2">KASIR</span>
                </a>
            </div>

            <div class="sidebar-toggler x">
                <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
            </div>
        </div>
    </div>
    <div class="sidebar-menu">
        <ul class="menu">
            <li class="sidebar-title">Menu Utama</li>

            <li class="sidebar-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <a href="{{ url('/admin/dashboard') }}" class='sidebar-link'>
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->is('admin/pasien*') ? 'active' : '' }}">
                <a href="{{ url('/admin/category') }}" class='sidebar-link'>
                    <i class="bi bi-folder"></i>
                    <span>Kategori</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a href="{{ route('kasir.index') }}" class='sidebar-link' target="_blank">
                    <i class="bi bi-display"></i>
                    <span>Mode Kasir</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('kasir.history') ? 'active' : '' }}">
                <a href="{{ route('kasir.history') }}" class='sidebar-link'>
                    <i class="bi bi-clock-history"></i>
                    <span>Riwayat Transaksi</span>
                </a>
            </li>


            <li class="sidebar-item {{ request()->is('admin/product*') ? 'active' : '' }}">
                <a href="{{ url('/admin/product') }}" class='sidebar-link'>
                    <i class="bi bi-capsule"></i>
                    <span>Product</span>
                </a>
            </li>

            <li class="sidebar-title">Lainnya</li>

            <li class="sidebar-item {{ request()->is('admin/laporan*') ? 'active' : '' }}">
                <a href="{{ url('/admin/laporan') }}" class='sidebar-link'>
                    <i class="bi bi-file-earmark-bar-graph-fill"></i>
                    <span>Laporan</span>
                </a>
            </li>
        </ul>
    </div>
</div>