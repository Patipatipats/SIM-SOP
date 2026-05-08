<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dokumen Manajemen UNLA')</title>

    <meta name="description" content="Sistem Dokumen Manajemen Universitas Langlangbuana berbasis digital untuk pengelolaan SOP dan arsip.">

    <meta name="keywords" content="UNLA, SOP, Dokumen, Manajemen, Sistem Informasi">
    <meta name="author" content="Universitas Langlangbuana">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <script>
        const theme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', theme);
    </script>

    <style>
        /* Menggunakan Variabel Bootstrap agar otomatis berubah warna */
        body {
            margin: 0;
            background-color: var(--bs-body-bg); /* Bunglon latar belakang */
            color: var(--bs-body-color); /* Bunglon teks */
            font-family: 'Segoe UI', sans-serif;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .app-layout {
            display: flex;
            min-height: 100vh;
        }

        .main-sidebar {
            width: 250px;
            min-height: 100vh;
            background: #1e293b !important; /* Sidebar biarkan tetap gelap */
            color: white;
            flex-shrink: 0;
        }

        .main-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        /* Header Dinamis */
        .main-header {
            background-color: var(--bs-tertiary-bg); /* Akan jadi putih di terang, abu gelap di dark mode */
            color: var(--bs-body-color);
            min-height: 60px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            padding: 0 20px;
            border-bottom: 1px solid var(--bs-border-color);
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .content-wrapper {
            flex: 1;
            padding: 20px;
        }

        /* Footer Dinamis */
        .main-footer {
            padding: 15px 20px;
            background-color: var(--bs-tertiary-bg); /* Akan otomatis ikut mode gelap */
            color: var(--bs-body-color);
            border-top: 1px solid var(--bs-border-color);
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
    </style>

    @stack('styles')

</head>

<body>
    <div class="app-layout">
        @include('layouts.sidebar')

        <div class="main-area">
            <nav class="main-header mb-4">
                <h5 class="mb-0 fw-bold">@yield('page-title', 'Dashboard')</h5>
            </nav>

            <main class="content-wrapper">
                @yield('content')
            </main>

            <footer class="main-footer">
                <div class="d-flex justify-content-between flex-wrap gap-2 small">
                    <div>
                        <strong>Sistem Informasi Manajemen Dokumen SOP</strong><br>
                        <span class="text-muted">Universitas Langlangbuana</span>
                    </div>
                    <div class="text-muted">Versi 1.0</div>
                </div>
            </footer>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>