@extends('layouts.guest')

@section('title', 'Dokumen Manajemen Sistem UNLA')

@section('content')

@php
    use Illuminate\Support\Str;
@endphp


<!-- ================= HERO ================= -->
<section class="py-5 bg-light">
    <div class="container text-center">

        <h1 class="fw-bold mb-3">
            Sistem Informasi Manajemen
            <span class="text-primary">Dokumen SOP</span>
        </h1>

        <p class="text-muted mb-4">
            Portal resmi pengelolaan dan pencarian dokumen SOP Universitas Langlangbuana
        </p>

        <form action="{{ url('/sop') }}" method="GET">

            <div class="row justify-content-center">
                <div class="col-md-8">

                    <div class="input-group input-group-lg shadow-sm">

                        <input
                            type="text"
                            name="q"
                            class="form-control"
                            placeholder="Cari SOP, unit kerja, atau kategori..."
                        >

                        <select name="kategori" class="form-select">

                            <option value="">
                                Semua Kategori
                            </option>

                            @foreach ($kategori as $k)

                                <option value="{{ $k->id }}">
                                    {{ $k->nama_kategori }}
                                </option>

                            @endforeach

                        </select>

                        <button class="btn btn-primary px-4">
                            Cari
                        </button>

                    </div>

                </div>
            </div>

        </form>

    </div>
</section>



<!-- ================= STATISTIK ================= -->
<section class="py-5">
    <div class="container">

        <div class="row text-center">

            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">

                        <div class="fs-1 text-primary mb-2">
                            📄
                        </div>

                        <h3 class="fw-bold">
                            {{ $total_sop }}
                        </h3>

                        <p class="text-muted mb-0">
                            Total SOP
                        </p>

                    </div>
                </div>
            </div>


            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">

                        <div class="fs-1 text-primary mb-2">
                            🏢
                        </div>

                        <h3 class="fw-bold">
                            {{ $total_unit }}
                        </h3>

                        <p class="text-muted mb-0">
                            Unit Kerja
                        </p>

                    </div>
                </div>
            </div>


            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">

                        <div class="fs-1 text-primary mb-2">
                            📂
                        </div>

                        <h3 class="fw-bold">
                            {{ $total_kategori }}
                        </h3>

                        <p class="text-muted mb-0">
                            Kategori
                        </p>

                    </div>
                </div>
            </div>


            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">

                        <div class="fs-1 text-primary mb-2">
                            ⬇️
                        </div>

                        <h3 class="fw-bold">
                            {{ $total_download }}
                        </h3>

                        <p class="text-muted mb-0">
                            Total Download
                        </p>

                    </div>
                </div>
            </div>

        </div>

    </div>
</section>



<!-- ================= SOP TERBARU ================= -->
<section class="py-5 bg-light">
    <div class="container">

        <h4 class="fw-bold mb-4">
            SOP Terbaru
        </h4>

        <div class="row">

            @foreach ($sop_terbaru as $sop)

                <div class="col-md-6 mb-3">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body">

                            <h6 class="fw-bold">
                                {{ $sop->judul }}
                            </h6>

                            <div class="mb-2">

                                <span class="badge bg-light text-dark">
                                    {{ $sop->nama_kategori }}
                                </span>

                                <span class="badge bg-light text-dark">
                                    {{ $sop->nama_unit }}
                                </span>

                            </div>

                            <p class="text-muted small">
                                {{ Str::limit($sop->deskripsi, 120) }}
                            </p>

                            <div class="d-flex justify-content-between">

                                <a
                                    href="{{ url('sop/' . $sop->id) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    Detail
                                </a>

                                <a
                                    href="{{ url('download/' . $sop->id) }}"
                                    class="btn btn-sm btn-primary"
                                >
                                    Unduh
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>
</section>



<!-- ================= SOP POPULER ================= -->
@if (isset($sop_populer))

<section class="py-5">
    <div class="container">

        <h4 class="fw-bold mb-4">
            SOP Paling Banyak Dilihat
        </h4>

        <div class="row">

            @foreach ($sop_populer as $sop)

                <div class="col-md-4 mb-3">

                    <div class="card border-0 shadow-sm">

                        <div class="card-body">

                            <h6 class="fw-bold">
                                {{ $sop->judul }}
                            </h6>

                            <p class="text-muted small">
                                👁 {{ $sop->total_views }} views
                            </p>

                            <a
                                href="{{ url('sop/' . $sop->id) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Lihat SOP
                            </a>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>
</section>

@endif



<!-- ================= UPDATE SOP ================= -->
@if (isset($sop_update_terbaru))

<section class="py-5 bg-light">
    <div class="container">

        <h4 class="fw-bold mb-4">
            Update SOP Terbaru
        </h4>

        <div class="row">

            @foreach ($sop_update_terbaru as $sop)

                <div class="col-md-4 mb-3">

                    <div class="card border-0 shadow-sm">

                        <div class="card-body">

                            <h6 class="fw-bold">
                                {{ $sop->judul }}
                            </h6>

                            <p class="text-muted small">
                                Versi {{ $sop->versi }}
                            </p>

                            <a
                                href="{{ url('sop/' . $sop->id) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Detail
                            </a>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>
</section>

@endif



<!-- ================= KATEGORI ================= -->
<section class="py-5">
    <div class="container">

        <h4 class="fw-bold mb-4">
            Kategori SOP
        </h4>

        <div class="row">

            @foreach ($kategori as $k)

                <div class="col-md-3 mb-3">

                    <a
                        href="{{ url('kategori/' . $k->slug) }}"
                        class="text-decoration-none"
                    >

                        <div class="card border-0 shadow-sm h-100">

                            <div class="card-body text-center">

                                <div class="fs-2 mb-2">
                                    📁
                                </div>

                                <h6 class="fw-bold">
                                    {{ $k->nama_kategori }}
                                </h6>

                                <p class="text-muted small mb-0">
                                    {{ $k->jumlah_sop }} SOP
                                </p>

                            </div>

                        </div>

                    </a>

                </div>

            @endforeach

        </div>

    </div>
</section>

@endsection