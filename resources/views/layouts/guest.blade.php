<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ================= TITLE ================= -->
    <title>@yield('title', 'Dokumen Manajemen Sistem UNLA')</title>

    <!-- ================= SEO ================= -->
    <meta name="description" content="Sistem Dokumen Manajemen Universitas Langlangbuana berbasis digital untuk pengelolaan SOP dan arsip.">
    <meta name="keywords" content="UNLA, SOP, Dokumen, Manajemen, Sistem Informasi">
    <meta name="author" content="Universitas Langlangbuana">

    <!-- PENTING: HALAMAN UTAMA -->
    <link rel="canonical" href="https://dms.unla.ac.id/">

    <!-- ================= BOOTSTRAP ================= -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ================= FONTAWESOME ================= -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- ================= CUSTOM STYLE ================= -->
    <style>

        body {
            background: #f5f8fc;
            font-family: 'Segoe UI', sans-serif;
        }

        /* ================= HERO ================= */
        .hero {
            padding: 80px 0;
        }

        .hero h1 {
            font-size: 42px;
            font-weight: 700;
        }

        .hero span {
            color: #0d6efd;
        }

        /* ================= CARD ================= */
        .card {
            border-radius: 12px;
        }

        /* ================= LOGIN PAGE ================= */
        .login-bg {
            min-height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url('https://www.transparenttextures.com/patterns/cubes.png');
        }

        .login-card {
            width: 420px;
            background: white;
            border-radius: 14px;
            padding: 35px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }

        .login-title {
            font-weight: 700;
            font-size: 20px;
        }

        .btn-login {
            border-radius: 30px;
            padding: 10px;
            font-weight: 600;
        }

        .captcha-box {
            background: #f5f5f5;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
            font-size: 20px;
            letter-spacing: 5px;
        }

        /* ================= LAYOUT FLEX ================= */
        html, body {
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

        /* ================= FOOTER ================= */
        .site-footer {
            background: #0d6efd;
            color: white;
            padding: 20px 0;
            margin-top: 40px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
        }

        .site-footer small {
            opacity: 0.9;
        }

    </style>

</head>

<body>

    <!-- ================= NAVBAR ================= -->
    @include('layouts.header')

    <!-- ================= CONTENT ================= -->
    <main>
        @yield('content')
    </main>

    <!-- ================= FOOTER ================= -->
    @include('layouts.footer')

    <!-- ================= BOOTSTRAP JS ================= -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>