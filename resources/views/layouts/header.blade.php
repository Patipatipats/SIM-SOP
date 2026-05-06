<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">

    <div class="container">

        <!-- LOGO -->
        <a
            class="navbar-brand d-flex align-items-center"
            href="{{ route('landing') }}"
        >
            <img
                src="{{ asset('logo/logo_unla.png') }}"
                height="35"
                class="me-2"
                alt="Logo UNLA"
            >

            <strong>
                SIM SOP
            </strong>
        </a>


        <!-- TOGGLER -->
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarMenu"
        >
            <span class="navbar-toggler-icon"></span>
        </button>


        <!-- MENU -->
        <div
            class="collapse navbar-collapse"
            id="navbarMenu"
        >

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="{{ route('landing') }}"
                    >
                        Beranda
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="#"
                    >
                        Kategori SOP
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="#"
                    >
                        Unit Kerja
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="#"
                    >
                        Tentang
                    </a>

                </li>


                <!-- LOGIN BUTTON -->
                <li class="nav-item ms-lg-3">

                    <a
                        href="{{ route('login') }}"
                        class="btn btn-primary btn-sm"
                    >
                        Login
                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>