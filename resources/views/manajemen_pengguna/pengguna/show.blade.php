@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    {{-- HEADER & TOMBOL KEMBALI --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('pengguna.index') }}" class="btn btn-outline-dark btn-sm rounded-circle p-2 shadow-sm" style="width: 38px; height: 38px;" title="Kembali">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h4 class="fw-bold text-dark mb-0">Detail Profil Pengguna</h4>
        </div>
        <div>
            <span class="text-muted small">ID Pengguna: <strong>#{{ $user->id }}</strong></span>
        </div>
    </div>

    <div class="row g-4">
        {{-- KARTU PROFIL KIRI (AVATAR & STATUS UTAMA) --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-body text-center p-4 h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    {{-- Dummy Avatar Pake Initial Nama --}}
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm mb-3" style="width: 90px; height: 90px; font-size: 2.2rem;">
                        {{ strtoupper(substr($user->nama, 0, 2)) }}
                    </div>
                    
                    <h5 class="fw-bold text-dark mb-1">{{ $user->nama }}</h5>
                    <p class="text-muted small mb-3"><i class="fas fa-at me-1"></i>{{ $user->username }}</p>

                    <div class="mb-4">
                        @if($user->status == 1)
                            <span class="badge rounded-pill bg-success px-4 py-2 border border-success-subtle shadow-sm">Akun Aktif</span>
                        @else
                            <span class="badge rounded-pill bg-danger px-4 py-2 border border-danger-subtle shadow-sm">Nonaktif</span>
                        @endif
                    </div>

                    <hr class="w-100 text-muted opacity-25">

                    <div class="w-100 text-start mt-2">
                        <div class="d-flex justify-content-between align-items-center mb-2 small">
                            <span class="text-muted">Hak Akses / Role:</span>
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle fw-bold">{{ $user->role->nama_role ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center small text-dark">
                            <span class="text-muted">Last Login:</span>
                            <span class="fw-semibold">{{ $user->last_login ? \Carbon\Carbon::parse($user->last_login)->format('d M Y H:i') : 'Belum pernah' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- DETAIL INFORMASI KANAN (DATA LENGKAP) --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm bg-body h-100">
                <div class="card-body p-4">
                    {{-- SEKSI 1: AKUN & KONTAK --}}
                    <h6 class="fw-bold text-primary mb-3 pb-2 border-bottom">
                        <i class="fas fa-user-id me-2"></i>Informasi Data Diri & Kontak
                    </h6>
                    <div class="row g-3 mb-4 text-dark">
                        <div class="col-sm-6">
                            <label class="text-muted small d-block mb-1">Nama Lengkap</label>
                            <div class="fw-semibold bg-light p-2 rounded border border-light-subtle">{{ $user->nama }}</div>
                        </div>
                        <div class="col-sm-6">
                            <label class="text-muted small d-block mb-1">Username Sistem</label>
                            <div class="fw-semibold bg-light p-2 rounded border border-light-subtle">{{ $user->username }}</div>
                        </div>
                        <div class="col-sm-6">
                            <label class="text-muted small d-block mb-1">Alamat Email</label>
                            <div class="fw-semibold bg-light p-2 rounded border border-light-subtle">
                                <i class="fas fa-envelope text-muted me-2"></i>{{ $user->email }}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="text-muted small d-block mb-1">Nomor Handphone</label>
                            <div class="fw-semibold bg-light p-2 rounded border border-light-subtle">
                                <i class="fas fa-phone text-muted me-2"></i>{{ $user->no_hp ?? 'Tidak ada data' }}
                            </div>
                        </div>
                    </div>

                    {{-- SEKSI 2: STRUKTUR / PENEMPATAN KERJA --}}
                    <h6 class="fw-bold text-primary mb-3 pb-2 border-bottom">
                        <i class="fas fa-sitemap me-2"></i>Penempatan & Unit Kerja Kedinasan
                    </h6>
                    <div class="row g-3 text-dark mb-2">
                        <div class="col-md-4">
                            <label class="text-muted small d-block mb-1">Unit Kerja Utama</label>
                            <div class="fw-semibold bg-light p-2 rounded border border-light-subtle">
                                {{ $user->unitKerja->unit_singkatan ?? '-' }} <span class="text-muted small">({{ $user->unitKerja->nama_unit ?? 'N/A' }})</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small d-block mb-1">Fakultas</label>
                            <div class="fw-semibold bg-light p-2 rounded border border-light-subtle">
                                {{ $user->fakultas->nama ?? 'Tidak Terikat Fakultas' }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small d-block mb-1">Program Studi</label>
                            <div class="fw-semibold bg-light p-2 rounded border border-light-subtle">
                                {{ $user->programStudi->nama ?? 'Tidak Terikat Prodi' }}
                            </div>
                        </div>
                    </div>

                    {{-- METADATA LOG WAKTU --}}
                    <div class="mt-4 pt-3 border-top d-flex flex-wrap gap-4 text-muted small">
                        <div><i class="fas fa-calendar-plus me-1"></i> Terdaftar Pada: <strong>{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-' }}</strong></div>
                        <div><i class="fas fa-calendar-check me-1"></i> Terakhir Diperbarui: <strong>{{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : '-' }}</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection