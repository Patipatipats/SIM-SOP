@extends('layouts.guest')

@section('title')
    Login SIM SOP
@endsection

@section('content')

<div class="login-bg">

    <div class="login-card">

        <!-- ================= HEADER ================= -->
        <div class="text-center mb-4">

            <h5 class="login-title">
                Sistem Informasi Manajemen SOP
            </h5>

            <p class="text-muted small mb-0">
                Universitas Langlangbuana
            </p>

        </div>


        <!-- ================= FORM LOGIN ================= -->
        <form method="POST" action="{{ route('login') }}">

            @csrf


            <!-- EMAIL / USERNAME -->
            <div class="mb-3">

                <label class="form-label">
                    Email / Username
                </label>

                <input
                    type="text"
                    name="email"
                    class="form-control"
                    placeholder="Masukkan email atau username"
                    required
                >

            </div>


            <!-- PASSWORD -->
            <div class="mb-3">

                <label class="form-label">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Masukkan password"
                    required
                >

            </div>


            <!-- CAPTCHA -->
            <div class="mb-3">

                <div class="captcha-box mb-2">
                    {{ session('captcha') }}
                </div>

                <input
                    type="text"
                    name="captcha"
                    class="form-control"
                    placeholder="Masukkan kode captcha"
                    required
                >

            </div>


            <!-- BUTTON LOGIN -->
            <div class="d-grid mb-3">

                <button class="btn btn-primary btn-login">
                    Masuk
                </button>

            </div>


            <!-- LUPA PASSWORD -->
            <div class="text-center small">

                <a href="#">
                    Lupa Password?
                </a>

            </div>

            <hr>


            <!-- FOOTER -->
            <div class="text-center small text-muted">

                © {{ date('Y') }} Universitas Langlangbuana

            </div>

        </form>

    </div>

</div>

@endsection