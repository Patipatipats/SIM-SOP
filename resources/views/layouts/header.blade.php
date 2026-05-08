<style>
    .navbar-transition {
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }
    .navbar-nav .nav-link {
        color: var(--bs-body-color);
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
    }
    .navbar-nav .nav-link:hover {
        color: #0d6efd !important;
        background-color: rgba(13, 110, 253, 0.05);
    }
    .navbar-nav .nav-link.active {
        color: #0d6efd !important;
        font-weight: 700;
    }
    .navbar-toggler:focus {
        box-shadow: none;
        outline: none;
    }
    
    /* Tambahan untuk Avatar & Dropdown */
    .user-avatar {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: linear-gradient(135deg, #0d6efd, #0dcaf0);
        color: white;
        font-weight: bold;
    }
    .dropdown-menu {
        border-radius: 12px;
        animation: fadeIn 0.2s ease-in-out;
        background-color: var(--bs-body-bg);
        border: 1px solid var(--bs-border-color);
    }
    .dropdown-item {
        color: var(--bs-body-color);
        padding: 8px 16px;
        transition: 0.2s;
    }
    .dropdown-item:hover {
        background-color: var(--bs-tertiary-bg);
        color: #0d6efd;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<nav class="navbar navbar-expand-lg bg-body-tertiary shadow-sm sticky-top navbar-transition">

    <div class="container py-1">

        <a class="navbar-brand d-flex align-items-center" href="{{ route('landing') }}">
            <img 
                src="{{ asset('logo/logo_unla.png') }}" 
                height="40" 
                class="me-2" 
                alt="Logo UNLA"
            >
            <span class="fw-bold fs-5 text-body" style="letter-spacing: -0.5px;">
                SIM SOP
            </span>
        </a>

        <button 
            class="navbar-toggler border-0" 
            type="button" 
            data-bs-toggle="collapse" 
            data-bs-target="#navbarMenu"
        >
            <i class="fas fa-bars text-body fs-4"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">

            <ul class="navbar-nav ms-auto align-items-lg-center fw-medium gap-1">

                <li class="nav-item">
                    <a class="nav-link px-3 {{ request()->routeIs('landing') ? 'active' : '' }}" href="{{ route('landing') }}">
                        Beranda
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link px-3" href="#">
                        Kategori SOP
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link px-3" href="#">
                        Unit Kerja
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link px-3" href="#">
                        Tentang
                    </a>
                </li>

                @auth
                    <li class="nav-item dropdown ms-lg-3 mt-3 mt-lg-0 mb-2 mb-lg-0 border-start ps-lg-3">
                        <a class="nav-link d-flex align-items-center p-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar me-2 shadow-sm">
                                {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}
                            </div>
                            <div class="d-flex flex-column lh-1 text-start">
                                <span class="fw-bold text-body" style="font-size: 0.9rem;">{{ auth()->user()->username }}</span>
                                <span class="text-muted" style="font-size: 0.75rem;">{{ auth()->user()->role->nama_role ?? 'User' }}</span>
                            </div>
                            <i class="fas fa-chevron-down ms-2 text-muted" style="font-size: 0.8rem;"></i>
                        </a>
                        
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm mt-3">
                            <li>
                                <a class="dropdown-item fw-semibold py-2" href="{{ route('dashboard') ?? '#' }}">
                                    <i class="fas fa-columns text-primary me-2 w-15px text-center"></i> Dashboard Admin
                                </a>
                            </li>
                            <li><hr class="dropdown-divider border-secondary opacity-25"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') ?? '#' }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item fw-semibold text-danger py-2">
                                        <i class="fas fa-right-from-bracket me-2 w-15px text-center"></i> Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item ms-lg-3 mt-3 mt-lg-0 mb-2 mb-lg-0 border-start ps-lg-3">
                        <a href="{{ route('login') ?? '#' }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold w-100">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
                @endauth

            </ul>

        </div>

    </div>

</nav>