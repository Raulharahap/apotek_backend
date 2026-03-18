<!-- Navbar & Hero Start -->
<div class="container-fluid position-relative p-0">
    <nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0">
        <a href="{{ url('/') }}" class="navbar-brand p-0 d-flex align-items-center">
            <img src="{{ asset('landing-page/img/logo.png') }}" alt="Logo" style="height: 40px;" class="me-2 rounded">
            <h1 class="m-0">APOTEK</h1>
            <!-- <img src="{{ asset('landing-page/img/logo.png') }}" alt="Logo"> -->
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="fa fa-bars"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto py-0">
                <a href="{{ url('/') }}" class="nav-item nav-link active">Beranda</a>
                <a href="#about" class="nav-item nav-link">Tentang</a>
                <a href="#service" class="nav-item nav-link">Layanan</a>
                <a href="#feature" class="nav-item nav-link">Fitur</a>

                <a href="#contact" class="nav-item nav-link">Kontak</a>
            </div>
            <a href="{{ route('admin.login') }}" class="btn btn-light rounded-pill text-primary py-2 px-4 ms-lg-5">Masuk</a>
        </div>
    </nav>