@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid py-4">

    <!-- ================= HEADER ================= -->
    <div class="mb-4">
        <h4 class="fw-bold">Dashboard SIM SOP</h4>
        <p class="text-muted mb-0">
            Selamat datang, {{ auth()->user()->name ?? 'User' }}
        </p>
    </div>


    <!-- ================= STATISTIK ================= -->
    <div class="row g-3 mb-4">

        <!-- TOTAL SOP -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Total SOP</h6>
                    <h3 class="fw-bold">
                        {{ $totalSop }}
                    </h3>
                </div>
            </div>
        </div>

        <!-- SOP AKTIF -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">SOP Aktif</h6>
                    <h3 class="fw-bold text-success">
                        {{ $sopAktif }}
                    </h3>
                </div>
            </div>
        </div>

        <!-- KATEGORI -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Kategori SOP</h6>
                    <h3 class="fw-bold text-primary">
                        {{ $totalKategori }}
                    </h3>
                </div>
            </div>
        </div>

        <!-- USER AKTIF -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">User Aktif</h6>
                    <h3 class="fw-bold text-warning">
                        {{ $userAktif }}
                    </h3>
                </div>
            </div>
        </div>

    </div>


    <!-- ================= CONTENT ================= -->
    <div class="row g-4">

        <!-- SOP TERBARU -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    SOP Terbaru
                </div>
                <div class="card-body p-0">

                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Judul</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($sopTerbaru as $sop)
                                <tr>
                                    <td>{{ $sop->kode_sop }}</td>
                                    <td>{{ $sop->judul }}</td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ $sop->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        Tidak ada data SOP
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>

                </div>
            </div>
        </div>


        <!-- AKTIVITAS TERBARU -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    Aktivitas Terbaru
                </div>
                <div class="card-body">

                    <ul class="list-group list-group-flush">

                        @forelse($logAktivitas as $log)
                            <li class="list-group-item px-0">
                                <div class="fw-semibold">
                                    {{ $log->aktivitas }}
                                </div>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                                </small>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">
                                Tidak ada aktivitas
                            </li>
                        @endforelse

                    </ul>

                </div>
            </div>
        </div>

    </div>

</div>

@endsection