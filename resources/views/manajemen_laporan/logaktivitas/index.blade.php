@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-0">Monitoring & Log Aktivitas</h4>
            <div class="text-muted small">Pantau seluruh aktivitas pengguna, view, riwayat download, dan approval dokumen SOP.</div>
        </div>
    </div>

    {{-- KOTAK FILTER PENCARIAN --}}
    <div class="card border-0 shadow-sm mb-4 bg-body">
        <div class="card-body">
            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-filter me-2"></i>Filter Pencarian Log</h6>
            <form action="{{ route('log-aktivitas.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="small text-muted mb-1">User</label>
                    <select name="user_id" class="form-select form-select-sm">
                        <option value="">-- Semua User --</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small text-muted mb-1">Modul</label>
                    <select name="modul" class="form-select form-select-sm">
                        <option value="">-- Semua Modul --</option>
                        <option value="SOP" {{ request('modul') == 'SOP' ? 'selected' : '' }}>SOP</option>
                        <option value="User" {{ request('modul') == 'User' ? 'selected' : '' }}>User</option>
                        <option value="Role" {{ request('modul') == 'Role' ? 'selected' : '' }}>Role</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small text-muted mb-1">Aktivitas</label>
                    <input type="text" name="aktivitas" class="form-control form-control-sm" placeholder="Contoh: Create, Update..." value="{{ request('aktivitas') }}">
                </div>
                <div class="col-md-2">
                    <label class="small text-muted mb-1">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ request('tanggal') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-dark w-100"><i class="fas fa-search me-1"></i> Terapkan</button>
                    <a href="{{ route('log-aktivitas.index') }}" class="btn btn-sm btn-outline-secondary w-100"><i class="fas fa-sync-alt me-1"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- NAVIGASI TABS --}}
    <ul class="nav nav-tabs fw-bold mb-3" id="logTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active text-dark" id="aktivitas-tab" data-bs-toggle="tab" data-bs-target="#aktivitas" type="button" role="tab"><i class="fas fa-history text-primary me-2"></i>Log Aktivitas Umum</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link text-dark" id="view-tab" data-bs-toggle="tab" data-bs-target="#view" type="button" role="tab"><i class="fas fa-eye text-info me-2"></i>Log View SOP</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link text-dark" id="download-tab" data-bs-toggle="tab" data-bs-target="#download" type="button" role="tab"><i class="fas fa-download text-success me-2"></i>Log Download SOP</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link text-dark" id="approval-tab" data-bs-toggle="tab" data-bs-target="#approval" type="button" role="tab"><i class="fas fa-check-circle text-warning me-2"></i>Log Approval SOP</button>
        </li>
    </ul>

    {{-- ISI TABS --}}
    <div class="tab-content" id="logTabContent">
        
        {{-- TAB 1: LOG AKTIVITAS UMUM --}}
        <div class="tab-pane fade show active" id="aktivitas" role="tabpanel">
            <div class="card border-0 shadow-sm bg-body mb-3">
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="small text-uppercase fw-bold text-dark">
                                <th class="ps-4">No</th>
                                <th>Tanggal</th>
                                <th>User Pelaku</th>
                                <th>Modul</th>
                                <th>Aktivitas</th>
                                <th>Referensi ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logAktivitas as $index => $log)
                            <tr>
                                <td class="ps-4 text-muted">{{ $logAktivitas->firstItem() + $index }}</td>
                                <td><span class="badge bg-light text-dark border"><i class="far fa-clock me-1"></i>{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}</span></td>
                                <td class="fw-bold text-primary">{{ $log->nama_user ?? 'Sistem/Guest' }}</td>
                                <td><span class="badge bg-secondary">{{ $log->modul }}</span></td>
                                <td>{{ $log->aktivitas }}</td>
                                <td class="text-muted">#{{ $log->referensi_id ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center py-4 text-muted">Tidak ada log aktivitas ditemukan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="d-flex justify-content-end">{{ $logAktivitas->appends(request()->except('page_aktivitas'))->links() }}</div>
        </div>

        {{-- TAB 2: LOG VIEW SOP --}}
        <div class="tab-pane fade" id="view" role="tabpanel">
            <div class="card border-0 shadow-sm bg-body mb-3">
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="small text-uppercase fw-bold text-dark">
                                <th class="ps-4">No</th>
                                <th>Tanggal View</th>
                                <th>Dokumen SOP</th>
                                <th>Viewer (User)</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logViews as $index => $view)
                            <tr>
                                <td class="ps-4 text-muted">{{ $logViews->firstItem() + $index }}</td>
                                <td><span class="badge bg-light text-dark border"><i class="far fa-clock me-1"></i>{{ \Carbon\Carbon::parse($view->viewed_at)->format('d/m/Y H:i') }}</span></td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $view->kode_sop }}</div>
                                    <div class="small text-muted">{{ Str::limit($view->judul, 40) }}</div>
                                </td>
                                <td class="fw-bold text-info">{{ $view->nama_user ?? 'Guest / Anonim' }}</td>
                                <td><code class="text-dark bg-light px-2 py-1 rounded">{{ $view->ip_address }}</code></td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada dokumen yang dilihat.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="d-flex justify-content-end">{{ $logViews->appends(request()->except('page_view'))->links() }}</div>
        </div>

        {{-- TAB 3: LOG DOWNLOAD SOP --}}
        <div class="tab-pane fade" id="download" role="tabpanel">
            <div class="card border-0 shadow-sm bg-body mb-3">
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="small text-uppercase fw-bold text-dark">
                                <th class="ps-4">No</th>
                                <th>Waktu Download</th>
                                <th>Dokumen & Versi</th>
                                <th>User (Pengunduh)</th>
                                <th>IP & Info Perangkat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logDownloads as $index => $dl)
                            <tr>
                                <td class="ps-4 text-muted">{{ $logDownloads->firstItem() + $index }}</td>
                                <td><span class="badge bg-light text-dark border"><i class="far fa-clock me-1"></i>{{ \Carbon\Carbon::parse($dl->downloaded_at)->format('d/m/Y H:i') }}</span></td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $dl->kode_sop }} <span class="badge bg-success ms-1">v{{ $dl->versi ?? '1.0' }}</span></div>
                                    <div class="small text-muted">{{ Str::limit($dl->judul, 40) }}</div>
                                </td>
                                <td class="fw-bold text-success">{{ $dl->nama_user ?? 'Guest' }}</td>
                                <td>
                                    <div class="small fw-semibold">{{ $dl->ip_address }}</div>
                                    <div class="small text-muted" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $dl->user_agent }}">{{ $dl->user_agent }}</div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada riwayat unduhan dokumen.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="d-flex justify-content-end">{{ $logDownloads->appends(request()->except('page_download'))->links() }}</div>
        </div>

        {{-- TAB 4: LOG APPROVAL SOP --}}
        <div class="tab-pane fade" id="approval" role="tabpanel">
            <div class="card border-0 shadow-sm bg-body mb-3">
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="small text-uppercase fw-bold text-dark">
                                <th class="ps-4">No</th>
                                <th>Waktu Approval</th>
                                <th>Dokumen & Versi</th>
                                <th>Approver</th>
                                <th>Status</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logApprovals as $index => $app)
                            <tr>
                                <td class="ps-4 text-muted">{{ $logApprovals->firstItem() + $index }}</td>
                                <td><span class="badge bg-light text-dark border"><i class="far fa-clock me-1"></i>{{ \Carbon\Carbon::parse($app->created_at)->format('d/m/Y H:i') }}</span></td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $app->kode_sop }} <span class="badge bg-success ms-1">v{{ $app->versi ?? '1.0' }}</span></div>
                                    <div class="small text-muted">{{ Str::limit($app->judul, 40) }}</div>
                                </td>
                                <td class="fw-bold text-warning">{{ $app->nama_user ?? 'Sistem' }}</td>
                                <td>
                                    @if($app->status == 'Approved' || $app->status == 'Active')
                                        <span class="badge bg-success px-3 rounded-pill">Disetujui</span>
                                    @else
                                        <span class="badge bg-danger px-3 rounded-pill">Ditolak</span>
                                    @endif
                                </td>
                                <td class="text-muted small" style="max-width: 250px; white-space: normal;">{{ $app->catatan ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada riwayat approval dokumen.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="d-flex justify-content-end">{{ $logApprovals->appends(request()->except('page_approval'))->links() }}</div>
        </div>

    </div>
</div>

{{-- SCRIPT JAVASCRIPT: AUTO-BUKA TAB DARI SIDEBAR LINK --}}
{{-- SCRIPT JAVASCRIPT: AUTO-BUKA TAB & KUNCI TAB SAAT FILTER --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const filterForm = document.querySelector('form');

        // 1. Fungsi buat pindah tab otomatis sesuai URL
        function activateTabFromHash() {
            let hash = window.location.hash;
            if (hash) {
                let triggerEl = document.querySelector('button[data-bs-target="' + hash + '"]');
                if (triggerEl) {
                    let tab = new bootstrap.Tab(triggerEl);
                    tab.show();
                    // Update action form biar kalau nge-filter tetep di tab ini
                    let baseUrl = "{{ route('log-aktivitas.index') }}";
                    filterForm.action = baseUrl + hash;
                }
            }
        }

        // Panggil saat halaman pertama kali dibuka
        activateTabFromHash();

        // 2. Panggil saat link sidebar di-klik (meskipun udah ada di halaman log)
        window.addEventListener('hashchange', activateTabFromHash, false);

        // 3. Update URL & Form Action setiap kali lu pindah tab manual (di-klik)
        const tabButtons = document.querySelectorAll('button[data-bs-toggle="tab"]');
        tabButtons.forEach(btn => {
            btn.addEventListener('shown.bs.tab', function (event) {
                let target = event.target.getAttribute('data-bs-target');
                
                // Ubah URL di atas browser tanpa reload
                history.replaceState(null, null, target);
                
                // Kunci form filter ke tab yang lagi aktif
                let baseUrl = "{{ route('log-aktivitas.index') }}";
                filterForm.action = baseUrl + target;
            });
        });
    });
</script>
@endsection