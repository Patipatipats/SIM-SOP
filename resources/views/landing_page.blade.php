@extends('layouts.guest')

@section('title', 'Dokumen Manajemen Sistem UNLA')

@section('content')
@if(session('error'))
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> <strong>Waduh!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

@php
    use Illuminate\Support\Str;
    
    // 🔥 MAGIC CMS: Pisahin data dari database berdasarkan tipenya
    $banner = isset($konten_landing) ? $konten_landing->where('tipe', 'banner')->first() : null;
    $informasi = isset($konten_landing) ? $konten_landing->where('tipe', 'informasi') : collect([]);
    $panduan = isset($konten_landing) ? $konten_landing->where('tipe', 'panduan') : collect([]);
@endphp

<style>
    /* CUSTOM UI LANDING PAGE */
    .hero-section {
        background: linear-gradient(135deg, #0d6efd 0%, #084298 100%);
        padding: 80px 0 100px;
        position: relative;
    }
    .hero-title {
        font-weight: 800;
        letter-spacing: -1px;
    }
    .search-box {
        border-radius: 50px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .search-box input, .search-box select, .search-box button {
        border: none;
        padding: 15px 25px;
    }
    .search-box select {
        border-left: 1px solid #e9ecef;
        max-width: 200px;
    }
    
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 16px;
    }
    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    
    .stat-container {
        margin-top: -50px;
        position: relative;
        z-index: 10;
    }
    
    .icon-circle {
        width: 60px;
        height: 60px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 1.5rem;
        margin-bottom: 15px;
    }
    .section-title {
        position: relative;
        padding-bottom: 10px;
        margin-bottom: 30px;
        font-weight: 800;
        color: #2b3452;
    }
    .section-title::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50px;
        height: 4px;
        background: #0d6efd;
        border-radius: 2px;
    }
</style>

<section class="hero-section text-white text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <span class="badge bg-white text-primary rounded-pill px-3 py-2 mb-3 fw-bold shadow-sm">
                    <i class="fas fa-university me-1"></i> Universitas Langlangbuana
                </span>
                
                {{-- 🔥 BANNER DINAMIS DARI CMS --}}
                <h1 class="hero-title mb-3 display-4">
                    @if($banner)
                        {!! $banner->judul !!}
                    @else
                        Sistem Informasi Manajemen <br> <span class="text-warning">Dokumen SOP</span>
                    @endif
                </h1>
                
                <p class="lead mb-5 opacity-75 fw-normal">
                    @if($banner)
                        {!! strip_tags($banner->konten) !!}
                    @else
                        Portal resmi pengelolaan, pencarian, dan arsip dokumen Standar Operasional Prosedur (SOP) secara digital dan terintegrasi.
                    @endif
                </p>

                <form action="{{ url('/sop') }}" method="GET">
                    <div class="input-group input-group-lg search-box bg-white">
                        <span class="input-group-text bg-white border-0 text-muted ps-4">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="q" class="form-control" placeholder="Cari judul SOP atau kata kunci..." autocomplete="off">
                        
                        <select name="kategori" class="form-select text-muted d-none d-md-block">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategori as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                            @endforeach
                        </select>
                        
                        <button class="btn btn-warning text-dark fw-bold px-5" type="submit">
                            Cari SOP
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="stat-container pb-5">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow hover-lift h-100 bg-white">
                    <div class="card-body py-4">
                        <div class="icon-circle bg-primary bg-opacity-10 text-primary">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h2 class="fw-bold text-dark mb-0">{{ $total_sop }}</h2>
                        <p class="text-muted small fw-semibold text-uppercase mb-0">Total SOP</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card border-0 shadow hover-lift h-100 bg-white">
                    <div class="card-body py-4">
                        <div class="icon-circle bg-success bg-opacity-10 text-success">
                            <i class="fas fa-building"></i>
                        </div>
                        <h2 class="fw-bold text-dark mb-0">{{ $total_unit }}</h2>
                        <p class="text-muted small fw-semibold text-uppercase mb-0">Unit Kerja</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card border-0 shadow hover-lift h-100 bg-white">
                    <div class="card-body py-4">
                        <div class="icon-circle bg-info bg-opacity-10 text-info">
                            <i class="fas fa-tags"></i>
                        </div>
                        <h2 class="fw-bold text-dark mb-0">{{ $total_kategori }}</h2>
                        <p class="text-muted small fw-semibold text-uppercase mb-0">Kategori</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card border-0 shadow hover-lift h-100 bg-white">
                    <div class="card-body py-4">
                        <div class="icon-circle bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-cloud-download-alt"></i>
                        </div>
                        <h2 class="fw-bold text-dark mb-0">{{ $total_download }}</h2>
                        <p class="text-muted small fw-semibold text-uppercase mb-0">Total Unduhan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 🔥 SECTION BARU: INFORMASI & PANDUAN DINAMIS DARI CMS --}}
@if(isset($konten_landing) && ($informasi->count() > 0 || $panduan->count() > 0))
<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-5">
            {{-- Bagian Panduan Ringkas (Pake Accordion) --}}
            @if($panduan->count() > 0)
            <div class="col-lg-{{ $informasi->count() > 0 ? '6' : '12' }}">
                <h4 class="section-title">Panduan Sistem</h4>
                <div class="accordion shadow-sm" id="accordionPanduan">
                    @foreach($panduan as $idx => $p)
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $idx == 0 ? '' : 'collapsed' }} fw-bold bg-light text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#pan-{{ $p->id }}">
                                <i class="fas fa-book-open text-primary me-2"></i> {{ $p->judul }}
                            </button>
                        </h2>
                        <div id="pan-{{ $p->id }}" class="accordion-collapse collapse {{ $idx == 0 ? 'show' : '' }}" data-bs-parent="#accordionPanduan">
                            <div class="accordion-body text-muted small">
                                {!! $p->konten !!}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Bagian Informasi Utama (Pake Cards) --}}
            @if($informasi->count() > 0)
            <div class="col-lg-{{ $panduan->count() > 0 ? '6' : '12' }}">
                <h4 class="section-title">Pusat Informasi</h4>
                <div class="d-flex flex-column gap-3">
                    @foreach($informasi as $info)
                    <div class="card border-0 shadow-sm hover-lift bg-light">
                        <div class="card-body p-4">
                            <h5 class="fw-bold text-dark mb-2"><i class="fas fa-bullhorn text-warning me-2"></i>{{ $info->judul }}</h5>
                            <div class="text-muted small m-0">
                                {!! $info->konten !!}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <h4 class="section-title mb-0">SOP Terbaru</h4>
            <a href="{{ url('/sop') }}" class="btn btn-outline-primary rounded-pill px-4 fw-semibold shadow-sm">Lihat Semua <i class="fas fa-arrow-right ms-1"></i></a>
        </div>

        <div class="row g-4">
            @foreach ($sop_terbaru as $sop)
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm hover-lift h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="fw-bold text-dark mb-0 pe-3">
                                    <i class="far fa-file-pdf text-danger me-2"></i>{{ $sop->judul }}
                                </h5>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-2">Baru</span>
                            </div>

                            <div class="mb-3 d-flex gap-2 flex-wrap">
                                <span class="badge bg-light text-secondary border px-2 py-1"><i class="fas fa-tag me-1"></i> {{ $sop->nama_kategori }}</span>
                                <span class="badge bg-light text-secondary border px-2 py-1"><i class="fas fa-building me-1"></i> {{ $sop->nama_unit }}</span>
                            </div>

                            <p class="text-muted small mb-4 line-clamp-2">
                                {{ Str::limit($sop->deskripsi, 120) }}
                            </p>

                            <div class="d-flex justify-content-between align-items-center mt-auto border-top pt-3">
                                <a href="{{ url('sop/' . $sop->id) }}" class="text-primary fw-semibold text-decoration-none">
                                    Lihat Detail <i class="fas fa-chevron-right ms-1" style="font-size: 10px;"></i>
                                </a>
                                <a href="{{ route('sop.download_terbaru', $sop->id) }}" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                                    <i class="fas fa-download me-1"></i> Unduh
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-5">
            @if (isset($sop_populer) && count($sop_populer) > 0)
            <div class="col-lg-6">
                <h4 class="section-title">SOP Paling Banyak Dilihat</h4>
                <div class="d-flex flex-column gap-3">
                    @foreach ($sop_populer as $sop)
                        <div class="card border-0 shadow-sm hover-lift">
                            <div class="card-body d-flex justify-content-between align-items-center p-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-light text-primary rounded p-3 text-center" style="width: 50px;">
                                        <i class="fas fa-fire fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1 text-dark">{{ $sop->judul }}</h6>
                                        <p class="text-muted small mb-0"><i class="far fa-eye me-1"></i> {{ $sop->total_views }} tayangan</p>
                                    </div>
                                </div>
                                <a href="{{ url('sop/' . $sop->id) }}" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 35px; height: 35px; line-height: 22px;">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if (isset($sop_update_terbaru) && count($sop_update_terbaru) > 0)
            <div class="col-lg-6">
                <h4 class="section-title">Update SOP Terbaru</h4>
                <div class="d-flex flex-column gap-3">
                    @foreach ($sop_update_terbaru as $sop)
                        <div class="card border-0 shadow-sm hover-lift">
                            <div class="card-body d-flex justify-content-between align-items-center p-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-light text-success rounded p-3 text-center" style="width: 50px;">
                                        <i class="fas fa-history fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1 text-dark">{{ $sop->judul }}</h6>
                                        <p class="text-muted small mb-0"><i class="fas fa-code-branch me-1"></i> Versi {{ $sop->versi }}</p>
                                    </div>
                                </div>
                                <a href="{{ url('sop/' . $sop->id) }}" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 35px; height: 35px; line-height: 22px;">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container text-center">
        <h4 class="fw-bold mb-2 text-dark">Jelajahi Berdasarkan Kategori</h4>
        <p class="text-muted mb-5">Temukan dokumen SOP dengan cepat melalui kategori di bawah ini.</p>

        <div class="row justify-content-center g-4">
            @foreach ($kategori as $k)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <a href="{{ url('kategori/' . $k->slug) }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm hover-lift h-100">
                            <div class="card-body text-center p-4">
                                <div class="icon-circle bg-primary bg-opacity-10 text-primary mb-3 mx-auto" style="width: 70px; height: 70px; font-size: 2rem;">
                                    <i class="far fa-folder-open"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-2">{{ $k->nama_kategori }}</h6>
                                <span class="badge bg-light text-secondary border rounded-pill">{{ $k->jumlah_sop }} Dokumen</span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

@endsection