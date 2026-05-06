<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- TITLE UTAMA -->
    <title>@yield('title', 'Dokumen Manajemen UNLA')</title>

    <!-- SEO -->
    <meta name="description" content="Sistem Dokumen Manajemen Universitas Langlangbuana berbasis digital untuk pengelolaan SOP dan arsip.">

    <!-- OPTIONAL (biar Google makin jelas) -->
    <meta name="keywords" content="UNLA, SOP, Dokumen, Manajemen, Siste Informasi">
    <meta name="author" content="Universitas Langlangbuana">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Admin Style -->
    <style>

        body {
    margin: 0;
    background: #f4f6f9;
    font-family: 'Segoe UI', sans-serif;
}

.app-layout {
    display: flex;
    min-height: 100vh;
}

.main-sidebar {
    width: 250px;
    min-height: 100vh;
    background: #1e293b;
    color: white;
    flex-shrink: 0;
}

.main-area {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.main-header {
    background: white;
    min-height: 60px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    padding: 0 20px;
}

.content-wrapper {
    flex: 1;
    padding: 20px;
}

.main-footer {
    padding: 15px 20px;
    background: white;
    border-top: 1px solid #e5e7eb;
}

    </style>

    @stack('styles')

</head>

<body>
    <div class="app-layout">
        @include('layouts.sidebar')

        <div class="main-area">
            <nav class="main-header mb-4">
                <h5>@yield('page-title','Dashboard')</h5>
            </nav>

            <main class="content-wrapper">
                @yield('content')
            </main>

            <footer class="main-footer">
                <div class="d-flex justify-content-between flex-wrap gap-2">
                    <div>
                        <strong>Sistem Informasi Manajemen Dokumen SOP</strong><br>
                        Universitas Langlangbuana
                    </div>
                    <div>Versi 1.0</div>
                </div>
            </footer>
        </div>
    </div>
</body>
</html>