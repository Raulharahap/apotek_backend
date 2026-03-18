<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'APOTEK')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="rekam medis elektronik, APOTEK, sistem informasi kesehatan, e-health, medical records"
        name="keywords">
    <meta
        content="APOTEK - Sistem Rekam Medis Elektronik modern dan terintegrasi untuk fasilitas kesehatan di Indonesia"
        name="description">

    <!-- Favicon -->
    <link href="{{ asset('landing-page/img/favicon.ico') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('landing-page/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('landing-page/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('landing-page/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('landing-page/css/style.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body>
    <div class="container-fluid bg-white p-0">

        @include('landing-page.partials._spinner')

        @yield('content')

        @include('landing-page.partials._footer')

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('landing-page/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('landing-page/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('landing-page/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('landing-page/lib/owlcarousel/owl.carousel.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('landing-page/js/landingPage.js') }}"></script>

    @stack('scripts')
</body>

</html>