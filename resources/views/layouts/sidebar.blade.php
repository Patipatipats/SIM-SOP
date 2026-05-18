<style>
    .sidebar-wrapper {
        width: 280px;
        min-height: 100vh;
        background: #212529;
    }

    .sidebar-menu .nav-link {
        border-radius: 0.5rem;
        padding: 0.65rem 0.85rem;
        transition: 0.2s ease;
    }

    .sidebar-menu .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.08);
    }

    .sidebar-menu .nav-link.active {
        background-color: #0d6efd;
        color: #fff;
    }

    .btn-toggle {
        padding: 0.65rem 0.85rem;
        background: transparent;
        box-shadow: none !important;
    }

    .btn-toggle:hover {
        background-color: rgba(255, 255, 255, 0.08);
    }

    .btn-toggle-nav .nav-link {
        padding: 0.45rem 0.75rem;
        font-size: 0.95rem;
        border-radius: 0.4rem;
    }

    .btn-toggle-nav .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.08);
    }
</style>

@auth

<div class="sidebar-wrapper d-flex flex-column flex-shrink-0 p-3 text-bg-dark">
    <a href="#" class="d-flex align-items-center justify-content-center mb-3 mb-md-0 text-white text-decoration-none border-bottom pb-3">
        <span class="fs-4 fw-semibold">SIM SOP UNLA</span>
    </a>

    <div class="text-center py-3 border-bottom mb-3">
        <div class="mb-2">
            <i class="fas fa-user-circle fs-1 text-light"></i>
        </div>
        <div class="fw-semibold">{{ Auth::user()->name ?? Auth::user()->username }}</div>
        {{-- Menampilkan Nama Role Dinamis --}}
        <small class="text-secondary text-uppercase fw-bold" style="font-size: 11px;">
            {{ Auth::user()->role->nama_role ?? 'User' }}
        </small>
    </div>

    <ul class="nav nav-pills flex-column mb-auto sidebar-menu">

        <li class="nav-item mb-1">
            <a href="{{ route('dashboard') ?? '#' }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : 'text-white' }}">
                <i class="fas fa-house me-2"></i>
                Dashboard
            </a>
        </li>

        <li class="mt-3 mb-2 text-uppercase small text-secondary fw-bold px-2">
            Manajemen SOP
        </li>

        <li class="mb-1">
            <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 text-white w-100"
                    data-bs-toggle="collapse"
                    data-bs-target="#sop-collapse"
                    aria-expanded="true">
                <i class="fas fa-file-alt me-2"></i>
                <span class="flex-grow-1 text-start">SOP</span>
                <i class="fas fa-chevron-down small"></i>
            </button>

            <div class="collapse show" id="sop-collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ms-4 mt-2">
                    <li><a href="{{ route('sop.index') ?? '#' }}" class="nav-link text-white">Data SOP</a></li>
                    
                    {{-- MASTER DATA: Hanya Super Admin (ID 2) & Admin (ID 3) --}}
                    @if(in_array(auth()->user()->role_id, [2, 3]))
                        <li><a href="{{ route('kategori.index') ?? '#' }}" class="nav-link text-white">Kategori SOP</a></li>
                        <li><a href="{{ route('unit_kerja.index') ?? '#' }}" class="nav-link text-white">Unit Kerja</a></li>
                        <li><a href="{{ route('fakultas.index') ?? '#' }}" class="nav-link text-white">Fakultas</a></li>
                        <li><a href="{{ route('program_studi.index') ?? '#' }}" class="nav-link text-white">Program Studi</a></li>
                        <li><a href="{{ route('tag_sop.index') ?? '#' }}" class="nav-link text-white">Tag SOP</a></li>
                    @endif
                </ul>
            </div>
        </li>

        {{-- Hanya Super Admin (ID 2) --}}
        @if(auth()->user()->role_id == 2)
        <li class="mt-3 mb-2 text-uppercase small text-secondary fw-bold px-2">
            Manajemen Pengguna
        </li>

        <li class="mb-1">
            <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 text-white w-100"
                    data-bs-toggle="collapse"
                    data-bs-target="#pengguna-collapse"
                    aria-expanded="false">
                <i class="fas fa-users me-2"></i>
                <span class="flex-grow-1 text-start">Pengguna</span>
                <i class="fas fa-chevron-down small"></i>
            </button>

            <div class="collapse" id="pengguna-collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ms-4 mt-2">
                    <li><a href="{{ route('pengguna.index') ?? '#' }}" class="nav-link text-white">Pengguna</a></li>
                    <li><a href="{{ route('role.index') ?? '#' }}" class="nav-link text-white">Role</a></li>
                    <li><a href="{{ route('permission.index') ?? '#' }}" class="nav-link text-white">Permission</a></li>
                    <li><a href="{{ route('temp-password.index') ?? '#' }}" class="nav-link text-white">Temp Password</a></li>
                </ul>
            </div>
        </li>
        @endif

        {{-- Super Admin (ID 2), Admin (ID 3), Approver (ID 4) --}}
        @if(in_array(auth()->user()->role_id, [2, 3, 4]))
        <li class="mt-3 mb-2 text-uppercase small text-secondary fw-bold px-2">
            Manajemen Laporan
        </li>

        <li class="mb-1">
            <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 text-white w-100"
                    data-bs-toggle="collapse"
                    data-bs-target="#laporan-collapse"
                    aria-expanded="false">
                <i class="fas fa-chart-column me-2"></i>
                <span class="flex-grow-1 text-start">Laporan</span>
                <i class="fas fa-chevron-down small"></i>
            </button>

            <div class="collapse" id="laporan-collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ms-4 mt-2">
                    {{-- Super Admin (2) & Admin (3) --}}
                    @if(in_array(auth()->user()->role_id, [2, 3]))
                        <li><a href="{{ route('log-aktivitas.index') }}" class="nav-link text-white">Log Aktivitas</a></li>
                        <li>
                            <a href="{{ route('log-aktivitas.index') }}#download" class="nav-link text-white">Download SOP</a>
                        </li>
                        <li>
                            <a href="{{ route('log-aktivitas.index') }}#view" class="nav-link text-white">View SOP</a>
                        </li>
                    @endif
                    
                    {{-- Super Admin (2), Admin (3), Approver (4) --}}
                    @if(in_array(auth()->user()->role_id, [2, 3, 4]))
                        <li><a href="{{ route('log-aktivitas.index') }}#approval" class="nav-link text-white">Approval SOP</a></li>
                    @endif
                </ul>
            </div>
        </li>
        @endif

        {{-- Hanya Super Admin (ID 2) --}}
        @if(auth()->user()->role_id == 2)
        <li class="mt-3 mb-2 text-uppercase small text-secondary fw-bold px-2">
            Pengaturan Sistem
        </li>

        <li class="nav-item mb-1">
            <a href="{{ route('pengaturan-landing.index') }}" class="nav-link text-white">
                <i class="fas fa-gear me-2"></i>
                Landing Page
            </a>
        </li>
        @endif
    </ul>

    <div class="mt-4 pt-3 border-top">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-light w-100">
                <i class="fas fa-right-from-bracket me-2"></i>
                Logout
            </button>
        </form>
    </div>
</div>

@endauth