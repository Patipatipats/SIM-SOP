@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<style>
    /* ==========================================================================
       FIX UTAMA: Memaksa area konten mengikuti tema
       ========================================================================== */
    body {
        background-color: var(--bs-body-bg) !important;
        color: var(--bs-body-color) !important;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    /* Reset background sidebar agar tidak ikut berubah (tetap gelap) */
    .sidebar-wrapper {
        background: #212529 !important;
    }

    /* ==========================================================================
       UI Enhancements
       ========================================================================== */
    .stat-card {
        border-radius: 12px;
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05) !important;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }
    .icon-box {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.25rem;
    }
    .table-custom th {
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: var(--bs-body-color);
        background-color: var(--bs-tertiary-bg) !important;
        border-bottom: 2px solid var(--bs-border-color);
    }
    /* Timeline Design for Activity Log */
    .timeline {
        position: relative;
        padding-left: 1.5rem;
        list-style: none;
        margin-bottom: 0;
    }
    .timeline::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0.35rem;
        height: 100%;
        width: 2px;
        background: var(--bs-border-color);
    }
    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -1.5rem;
        top: 0.25rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #0d6efd;
        border: 2px solid var(--bs-body-bg);
        box-shadow: 0 0 0 2px var(--bs-border-color);
    }
    .timeline-item.warning::before { background: #ffc107; }
    .timeline-item.success::before { background: #198754; }

    /* Dark Mode Toggle Switch */
    .theme-switch {
        cursor: pointer;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        background: var(--bs-secondary-bg);
        border: 1px solid var(--bs-border-color);
        transition: all 0.3s ease;
    }
    .theme-switch:hover { background: var(--bs-tertiary-bg); }
</style>

<div class="container-fluid py-4 px-4">

    <div class="mb-4 d-flex justify-content-between align-items-end">
        <div>
            <h4 class="fw-bold mb-1">Dashboard SIM SOP</h4>
            <p class="text-muted mb-0 small">
                Selamat datang kembali, <span class="fw-bold">{{ auth()->user()->username }}</span> 
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 ms-2 text-uppercase" style="font-size: 10px; letter-spacing: 0.5px;">
                    {{ auth()->user()->role->nama_role ?? 'User' }}
                </span>
            </p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <button id="themeToggleBtn" class="theme-switch d-flex align-items-center gap-2">
                <i class="fas fa-moon text-secondary" id="themeIcon"></i>
                <span class="small fw-semibold text-secondary" id="themeText">Dark Mode</span>
            </button>

            <div class="text-muted small fw-semibold bg-body px-3 py-2 rounded-pill shadow-sm border">
                <i class="fas fa-calendar-alt me-2 text-primary"></i> {{ now()->format('d F Y') }}
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card shadow-sm h-100 bg-body">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted small text-uppercase fw-bold mb-1">
                            {{ auth()->user()->role_id == 6 ? 'SOP Unit Anda' : 'Total SOP Sistem' }}
                        </h6>
                        <h3 class="fw-bold mb-0">{{ $totalSop }}</h3>
                    </div>
                    <div class="icon-box bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            @php
                $isApprover = auth()->user()->role_id == 4;
                $textClass = $isApprover ? 'text-warning' : 'text-success';
                $bgClass = $isApprover ? 'bg-warning' : 'bg-success';
                $icon = $isApprover ? 'fa-clock' : 'fa-check-circle';
                $label = $isApprover ? 'Perlu Review' : 'SOP Aktif';
            @endphp
            <div class="card stat-card shadow-sm h-100 bg-body {{ $isApprover ? 'border-start border-4 border-warning' : '' }}">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted small text-uppercase fw-bold mb-1">{{ $label }}</h6>
                        <h3 class="fw-bold mb-0 {{ $textClass }}">{{ $sopAktif }}</h3>
                    </div>
                    <div class="icon-box {{ $bgClass }} bg-opacity-10 {{ $textClass }}">
                        <i class="fas {{ $icon }}"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card shadow-sm h-100 bg-body">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted small text-uppercase fw-bold mb-1">Kategori SOP</h6>
                        <h3 class="fw-bold text-primary mb-0">{{ $totalKategori }}</h3>
                    </div>
                    <div class="icon-box bg-info bg-opacity-10 text-info">
                        <i class="fas fa-tags"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card shadow-sm h-100 bg-body">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted small text-uppercase fw-bold mb-1">
                            {{ auth()->user()->role_id == 2 ? 'User Terdaftar' : 'Status Akun' }}
                        </h6>
                        <h3 class="fw-bold mb-0">
                            {{ auth()->user()->role_id == 2 ? $userAktif : 'Aktif' }}
                        </h3>
                    </div>
                    <div class="icon-box bg-secondary bg-opacity-10 text-secondary">
                        <i class="fas {{ auth()->user()->role_id == 2 ? 'fa-users' : 'fa-user-shield' }}"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(in_array(auth()->user()->role_id, [2, 3]))
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 bg-body" style="border-radius: 12px;">
                <div class="card-header bg-transparent py-3 border-0 pt-4 px-4">
                    <h6 class="fw-bold mb-0">
                        <i class="fas fa-chart-bar me-2 text-primary"></i> Sebaran SOP per Kategori
                    </h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <div style="height: 280px;">
                        <canvas id="sopChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row g-4">
        <div class="col-md-7">
            <div class="card shadow-sm border-0 h-100 bg-body" style="border-radius: 12px;">
                <div class="card-header bg-transparent py-3 px-4 border-0 pt-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">
                        <i class="fas fa-file-alt me-2 text-primary"></i>
                        {{ auth()->user()->role_id == 4 ? 'Antrian Review' : 'SOP Terbaru' }}
                    </h6>
                    <a href="{{ auth()->user()->role_id == 4 ? route('sop.index' , ['status' => 'Review']) : route('sop.index') }}" class="btn btn-sm btn-outline-primary fw-semibold rounded-pill px-3">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-custom align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">KODE SOP</th>
                                    <th>JUDUL DOKUMEN</th>
                                    <th class="text-center pe-4">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sopTerbaru as $sop)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="badge bg-secondary bg-opacity-10 text-body border fw-semibold px-2 py-1">
                                                {{ $sop->kode_sop }}
                                            </span>
                                        </td>
                                        <td class="fw-medium">
                                            <a href="{{ route('sop.show', $sop->id) }}" class="text-decoration-none text-body">{{ Str::limit($sop->judul, 45) }}</a>
                                        </td>
                                        <td class="text-center pe-4">
                                            @php
                                                $badgeColor = $sop->status == 'Active' ? 'bg-success' : ($sop->status == 'Review' ? 'bg-info' : 'bg-warning text-dark');
                                            @endphp
                                            <span class="badge {{ $badgeColor }} fw-normal px-2 py-1 rounded-pill" style="letter-spacing: 0.3px;">
                                                <i class="fas fa-circle me-1" style="font-size: 6px; vertical-align: middle;"></i> 
                                                {{ $sop->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">
                                            <i class="fas fa-folder-open fs-2 mb-2 text-secondary opacity-50"></i><br>
                                            Belum ada data dokumen.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-sm border-0 h-100 bg-body" style="border-radius: 12px;">
                <div class="card-header bg-transparent py-3 px-4 border-0 pt-4">
                    <h6 class="fw-bold mb-0">
                        <i class="fas fa-history me-2 text-primary"></i> Aktivitas Terbaru
                    </h6>
                </div>
                <div class="card-body px-4 pt-2">
                    <ul class="timeline">
                        @forelse($logAktivitas as $log)
                            @php
                                $timelineColor = '';
                                if(stripos($log->aktivitas, 'login') !== false) $timelineColor = 'success';
                                elseif(stripos($log->aktivitas, 'hapus') !== false || stripos($log->aktivitas, 'tolak') !== false) $timelineColor = 'warning';
                            @endphp
                            <li class="timeline-item {{ $timelineColor }}">
                                <div class="fw-bold" style="font-size: 0.9rem;">{{ $log->aktivitas }}</div>
                                <div class="text-muted mt-1 fw-semibold" style="font-size: 0.75rem;">
                                    <i class="far fa-clock me-1"></i> {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                                </div>
                            </li>
                        @empty
                            <div class="text-center py-4 text-muted small">
                                Tidak ada catatan aktivitas.
                            </div>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================== --}}
    {{-- TAHAP 7: MONITORING TOP SOP (VIEWS & DOWNLOADS)--}}
    {{-- ============================================== --}}
    <div class="row mt-4">
        {{-- 1. Top Views --}}
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100 bg-body" style="border-radius: 12px;">
                <div class="card-header bg-transparent py-3 px-4 border-0 pt-4">
                    <h6 class="fw-bold mb-0">
                        <i class="fas fa-eye me-2 text-primary"></i> 5 SOP Paling Banyak Dilihat
                    </h6>
                </div>
                <div class="card-body px-4 pt-2">
                    @php
                        $topViews = \App\Models\Sop::where('status', 'Active')
                                        ->orderBy('total_views', 'desc')
                                        ->limit(5)
                                        ->get();
                    @endphp
                    <ul class="list-group list-group-flush">
                        @forelse($topViews as $index => $sopView)
                            <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-3">
                                <div class="d-flex align-items-start">
                                    <div class="fw-bold fs-5 me-3 text-muted">#{{ $index + 1 }}</div>
                                    <div>
                                        <a href="{{ route('sop.show', $sopView->id) }}" class="fw-bold text-decoration-none d-block mb-1 text-body">{{ \Illuminate\Support\Str::limit($sopView->judul, 40) }}</a>
                                        <span class="badge bg-secondary bg-opacity-10 text-body border" style="font-size: 0.7rem;">{{ $sopView->kode_sop }}</span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">
                                        <i class="fas fa-eye me-1"></i> {{ $sopView->total_views }}
                                    </span>
                                </div>
                            </li>
                        @empty
                            <div class="text-center text-muted small py-3">Belum ada data view.</div>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        {{-- 2. Top Downloads --}}
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100 bg-body" style="border-radius: 12px;">
                <div class="card-header bg-transparent py-3 px-4 border-0 pt-4">
                    <h6 class="fw-bold mb-0">
                        <i class="fas fa-download me-2 text-success"></i> 5 SOP Paling Banyak Diunduh
                    </h6>
                </div>
                <div class="card-body px-4 pt-2">
                    @php
                        // Query pinter buat ngitung jumlah download dari log
                        $topDownloads = \Illuminate\Support\Facades\DB::table('sop_download_logs')
                                            ->join('sop', 'sop.id', '=', 'sop_download_logs.sop_id')
                                            ->select('sop.id', 'sop.judul', 'sop.kode_sop', \Illuminate\Support\Facades\DB::raw('COUNT(sop_download_logs.id) as total_download'))
                                            ->groupBy('sop.id', 'sop.judul', 'sop.kode_sop')
                                            ->orderBy('total_download', 'desc')
                                            ->limit(5)
                                            ->get();
                    @endphp
                    <ul class="list-group list-group-flush">
                        @forelse($topDownloads as $index => $sopDl)
                            <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-3">
                                <div class="d-flex align-items-start">
                                    <div class="fw-bold fs-5 me-3 text-muted">#{{ $index + 1 }}</div>
                                    <div>
                                        <a href="{{ route('sop.show', $sopDl->id) }}" class="fw-bold text-decoration-none d-block mb-1 text-body">{{ \Illuminate\Support\Str::limit($sopDl->judul, 40) }}</a>
                                        <span class="badge bg-secondary bg-opacity-10 text-body border" style="font-size: 0.7rem;">{{ $sopDl->kode_sop }}</span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                                        <i class="fas fa-download me-1"></i> {{ $sopDl->total_download }}
                                    </span>
                                </div>
                            </li>
                        @empty
                            <div class="text-center text-muted small py-3">Belum ada data download.</div>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div> {{-- SCRIPT DARK MODE & CHART JS --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- LOGIKA DARK MODE (Dengan LocalStorage) ---
        const themeToggleBtn = document.getElementById('themeToggleBtn');
        const themeIcon = document.getElementById('themeIcon');
        const themeText = document.getElementById('themeText');
        const htmlElement = document.documentElement;

        // 1. Cek tema yang tersimpan di localStorage saat halaman dimuat
        const currentTheme = localStorage.getItem('theme');
        if (currentTheme) {
            htmlElement.setAttribute('data-bs-theme', currentTheme);
            updateToggleButton(currentTheme);
        } else {
            // Default ke light kalau belum ada pilihan
            htmlElement.setAttribute('data-bs-theme', 'light');
        }

        // 2. Logika saat tombol saklar ditekan
        themeToggleBtn.addEventListener('click', function() {
            let currentDataTheme = htmlElement.getAttribute('data-bs-theme');
            let newTheme = currentDataTheme === 'dark' ? 'light' : 'dark';
            
            htmlElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme); // Simpan pilihan ke browser
            updateToggleButton(newTheme);
        });

        // 3. Fungsi pembantu untuk update tampilan tombol
        function updateToggleButton(theme) {
            if (theme === 'dark') {
                themeIcon.classList.replace('fa-moon', 'fa-sun');
                themeIcon.classList.replace('text-secondary', 'text-warning');
                themeText.textContent = 'Light Mode';
            } else {
                themeIcon.classList.replace('fa-sun', 'fa-moon');
                themeIcon.classList.replace('text-warning', 'text-secondary');
                themeText.textContent = 'Dark Mode';
            }
        }
    });
</script>

@if(in_array(auth()->user()->role_id, [2, 3]))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- LOGIKA GRAFIK KATEGORI ---
        const canvas = document.getElementById('sopChart');
        if(!canvas) return;

        const ctx = canvas.getContext('2d');
        const chartData = @json($chartData ?? []);
        
        // Sesuaikan warna grafik agar bagus di mode terang/gelap
        let isDarkMode = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        let barColor = isDarkMode ? 'rgba(56, 139, 253, 0.8)' : 'rgba(13, 110, 253, 0.8)';

        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, barColor); 
        gradient.addColorStop(1, 'rgba(13, 110, 253, 0.1)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.map(item => item.nama_kategori), 
                datasets: [{
                    label: ' Jumlah SOP',
                    data: chartData.map(item => item.total),
                    backgroundColor: gradient,
                    borderColor: '#0d6efd',
                    borderWidth: 1,
                    borderRadius: 6,
                    barPercentage: 0.6
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#212529',
                        padding: 10,
                        cornerRadius: 8,
                        displayColors: false,
                    }
                },
                scales: { 
                    y: { 
                        beginAtZero: true,
                        grid: { borderDash: [5, 5], color: isDarkMode ? '#444' : '#e9ecef' },
                        ticks: { stepSize: 1, color: isDarkMode ? '#aaa' : '#666' }
                    }, 
                    x: { 
                        grid: { display: false },
                        ticks: { font: { weight: 'bold' }, color: isDarkMode ? '#aaa' : '#666' }
                    } 
                }
            }
        });
    });
</script>
@endif

@endsection