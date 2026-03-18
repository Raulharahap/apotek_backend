<!-- Footer Start -->
<div class="container-fluid bg-dark text-light footer pt-5 wow fadeIn" data-wow-delay="0.1s" style="margin-top: 6rem;">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-md-6 col-lg-3">
                <h5 class="text-white mb-4">Hubungi Kami</h5>
                <p><i class="fa fa-map-marker-alt me-3"></i>Jl. Kesehatan No. 10, Jakarta</p>
                <p><i class="fa fa-phone-alt me-3"></i>+62 21 1234 5678</p>
                <p><i class="fa fa-envelope me-3"></i>info@remedis.id</p>
                <div class="d-flex pt-2">
                    <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-instagram"></i></a>
                    <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-youtube"></i></a>
                    <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <h5 class="text-white mb-4">Menu Utama</h5>
                <a class="btn btn-link" href="#about">Tentang Kami</a>
                <a class="btn btn-link" href="#service">Layanan</a>
                <a class="btn btn-link" href="#feature">Keunggulan</a>
                <a class="btn btn-link" href="#contact">Kontak</a>
            </div>
            <div class="col-md-6 col-lg-3">
                <h5 class="text-white mb-4">Modul REMEDIS</h5>
                <a class="btn btn-link" href="{{ url('/admin/pasien') }}">Data Pasien</a>
                <a class="btn btn-link" href="{{ url('/admin/rekam-medis') }}">Rekam Medis</a>
                <a class="btn btn-link" href="{{ url('/admin/kunjungan') }}">Kunjungan</a>
                <a class="btn btn-link" href="{{ url('/admin/farmasi') }}">Farmasi</a>
                <a class="btn btn-link" href="{{ url('/admin/laporan') }}">Laporan</a>
            </div>
            <div class="col-md-6 col-lg-3">
                <h5 class="text-white mb-4">Info Terbaru</h5>
                <p>Dapatkan informasi terbaru tentang pembaruan fitur dan tips penggunaan sistem REMEDIS.</p>
                <div class="position-relative w-100 mt-3">
                    <input class="form-control border-0 rounded-pill w-100 ps-4 pe-5" type="text"
                        placeholder="Email Anda" style="height: 48px;">
                    <button type="button" class="btn shadow-none position-absolute top-0 end-0 mt-1 me-2"><i
                            class="fa fa-paper-plane text-primary fs-4"></i></button>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="copyright">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; {{ date('Y') }} <a class="border-bottom" href="#">REMEDIS</a>, Hak Cipta Dilindungi.
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="footer-menu">
                        <a href="{{ url('/') }}">Beranda</a>
                        <a href="{{ url('/login') }}">Masuk</a>
                        <a href="#contact">Kontak</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer End -->