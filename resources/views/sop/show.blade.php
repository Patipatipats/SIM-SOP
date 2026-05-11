@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            {{-- Tombol kembali diarahkan ke dashboard kalau guest, ke index sop kalau login --}}
            <a href="{{ auth()->check() ? route('sop.index') : url('/') }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            <h4 class="fw-bold mb-0">Detail Dokumen SOP</h4>
            <span class="text-muted small">{{ $sop->kode_sop }}</span>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- ALERT CATATAN PENOLAKAN (Nongol Kalo Ditolak)--}}
    {{-- ========================================== --}}
    @if($sop->status == 'Rejected' && $sop->versions->count() > 0)
        @php
            $latestV = $sop->versions->sortByDesc('id')->first();
            $logCatatan = \Illuminate\Support\Facades\DB::table('sop_approvals')
                            ->where('version_id', $latestV->id)
                            ->where('status', 'Rejected')
                            ->latest('id')
                            ->first();
        @endphp

        @if($logCatatan && $logCatatan->catatan)
            <div class="alert alert-danger mb-4 shadow-sm border-0 border-start border-danger border-4">
                <h6 class="fw-bold mb-1"><i class="fas fa-exclamation-triangle me-2"></i>SOP Ditolak! Alasan dari Atasan:</h6>
                <p class="mb-0 text-dark">{{ $logCatatan->catatan }}</p>
            </div>
        @endif
    @endif

    <div class="card border-0 shadow-sm mb-4 bg-body">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                
                {{-- 🔥 TAHAP 7: JUDUL & BADGE MONITORING --}}
                <div>
                    <h2 class="fw-bold mb-1">{{ $sop->judul }}</h2>
                    <p class="text-muted mb-3">Metadata Lengkap & Riwayat Dokumen</p>
                    
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1 shadow-sm">
                            <i class="fas fa-eye me-1"></i> {{ $sop->total_views }} Kali Dilihat
                        </span>
                        @php
                            $totalDownloads = \Illuminate\Support\Facades\DB::table('sop_download_logs')
                                                ->where('sop_id', $sop->id)
                                                ->count();
                        @endphp
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 shadow-sm">
                            <i class="fas fa-download me-1"></i> {{ $totalDownloads }} Kali Diunduh
                        </span>
                    </div>
                </div>
                
                <div class="text-md-end">
                    @php
                        $statusStyles = [
                            'Draft' => ['bg' => 'bg-warning', 'text' => 'text-dark', 'icon' => 'fa-file-alt'],
                            'Review' => ['bg' => 'bg-info', 'text' => 'text-dark', 'icon' => 'fa-search'],
                            'Active' => ['bg' => 'bg-success', 'text' => 'text-white', 'icon' => 'fa-check-circle'],
                            'Archived' => ['bg' => 'bg-secondary', 'text' => 'text-white', 'icon' => 'fa-archive'],
                            'Rejected' => ['bg' => 'bg-danger', 'text' => 'text-white', 'icon' => 'fa-times-circle'],
                        ];
                        $style = $statusStyles[$sop->status] ?? ['bg' => 'bg-light', 'text' => 'text-dark', 'icon' => 'fa-info-circle'];
                    @endphp
                    
                    <div class="d-inline-flex align-items-center {{ $style['bg'] }} {{ $style['text'] }} px-4 py-2 rounded-pill shadow-sm mt-3 mt-md-0">
                        <i class="fas {{ $style['icon'] }} me-2"></i>
                        <span class="fw-bold text-uppercase" style="letter-spacing: 1px;">{{ $sop->status }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4 bg-body">
                <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="fw-bold mb-0 text-primary">Informasi Utama</h5>
                    @if($sop->is_active)
                        <span class="badge bg-success px-3 py-2 rounded-pill">Aktif</span>
                    @else
                        <span class="badge bg-danger px-3 py-2 rounded-pill">Non-Aktif</span>
                    @endif
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <th width="200" class="text-muted small text-uppercase pb-2">Kode SOP</th>
                            <td class="fw-bold text-primary pb-2">: {{ $sop->kode_sop }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted small text-uppercase pb-2">Kategori</th>
                            <td class="pb-2">: {{ $sop->kategori->nama_kategori ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted small text-uppercase pb-2">Unit Kerja</th>
                            <td class="pb-2">: {{ $sop->unitKerja->nama_unit ?? '-' }} ({{ $sop->unitKerja->unit_singkatan ?? '-' }})</td>
                        </tr>
                        <tr>
                            <th class="text-muted small text-uppercase pb-2">Tag</th>
                            <td class="pb-2">: 
                                @if($sop->tag_sop)
                                    @foreach(explode(',', $sop->tag_sop) as $tag)
                                        <span class="badge bg-secondary bg-opacity-10 text-body border me-1">{{ trim($tag) }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                    <hr class="my-4 text-muted">
                    <div>
                        <label class="text-muted small text-uppercase fw-bold mb-2 d-block">Deskripsi / Ruang Lingkup</label>
                        <p class="bg-body-tertiary p-3 rounded border" style="min-height: 100px;">
                            {{ $sop->deskripsi ?? 'Tidak ada deskripsi.' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm bg-body">
                <div class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-primary">Versi Dokumen & Preview</h5>
                    @if($sop->versions->count() > 0)
                        @php $latestVersion = $sop->versions->sortByDesc('id')->first(); @endphp
                        <span class="badge bg-primary rounded-pill px-3 py-2">Versi Aktif: V{{ $latestVersion->versi }}</span>
                    @endif
                </div>
                
                <div class="card-body p-0 text-center" style="background: #525659; min-height: 500px; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
                    @if($sop->versions->count() > 0)
                        @php $latestVersion = $sop->versions->sortByDesc('id')->first(); @endphp
                        <iframe src="{{ route('sop.lihat_pdf', $latestVersion->id) }}" width="100%" height="600px" style="border: none;"></iframe>
                    @else
                        <div class="py-5 mt-5">
                            <i class="fas fa-file-pdf fa-4x text-light opacity-25 mb-3"></i>
                            <p class="text-light opacity-75">Belum ada file PDF yang diunggah untuk SOP ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card border-0 shadow-sm mb-4 border-top border-primary border-4 bg-body">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Aksi Dokumen</h6>
                    <div class="d-grid gap-2">
                        @if($sop->versions->count() > 0)
                            @php $latestVersion = $sop->versions->sortByDesc('id')->first(); @endphp
                            <a href="{{ route('sop.lihat_pdf', $latestVersion->id) }}" target="_blank" class="btn btn-outline-danger fw-semibold">
                                <i class="fas fa-external-link-alt me-2"></i> Buka Fullscreen
                            </a>
                            <a href="{{ route('sop.download', $latestVersion->id) }}" class="btn btn-outline-secondary fw-semibold">
                                <i class="fas fa-download me-2"></i> Download PDF
                            </a>
                        @else
                            <button class="btn btn-outline-danger fw-semibold" disabled><i class="fas fa-file-pdf me-2"></i> Buka Fullscreen</button>
                            <button class="btn btn-outline-secondary fw-semibold" disabled><i class="fas fa-download me-2"></i> Download PDF</button>
                        @endif
                        
                        @auth
                            @php $roleId = auth()->user()->role_id; @endphp
                            
                            {{-- ============================================== --}}
                            {{-- LOGIKA WORKFLOW APPROVAL (TAHAP 5)             --}}
                            {{-- ============================================== --}}

                            {{-- Admin & Operator Unit --}}
                            @if(in_array($roleId, [2, 3, 6]))
                                <hr class="my-2">
                                <a href="{{ route('sop.edit', $sop->id) }}" class="btn btn-warning fw-bold text-dark">
                                    <i class="fas fa-edit me-2"></i> Edit Metadata
                                </a>
                                
                                <button type="button" class="btn btn-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                    <i class="fas fa-upload me-2"></i> Upload File & Versi Baru
                                </button>
                                
                                <a href="{{ route('sop.versions', $sop->id) }}" class="btn btn-outline-primary fw-semibold">
                                    <i class="fas fa-history me-2"></i> Lihat Riwayat Versi
                                </a>
                                
                                {{-- Tombol Ajukan (Draft / Rejected -> Review) --}}
                                @if(in_array($sop->status, ['Draft', 'Rejected']))
                                    <form action="{{ route('sop.ajukan', $sop->id) }}" method="POST" class="d-grid mt-2">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-primary fw-bold" onclick="return confirm('Yakin mau ajuin SOP ini ke Atasan?')">
                                            <i class="fas fa-paper-plane me-2"></i> Ajukan Approval
                                        </button>
                                    </form>
                                @endif
                            @endif

                            {{-- Approver (Role yang bisa Setujui / Tolak) --}}
                            @if(in_array($roleId, [2, 4]) && $sop->status == 'Review')
                                <hr class="my-2 border-dark">
                                <h6 class="small fw-bold text-uppercase text-muted mb-2 text-center">Keputusan Approver</h6>
                                
                                <div class="d-grid gap-2">
                                    <form action="{{ route('sop.setujui', $sop->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success fw-bold text-white shadow-sm w-100" onclick="return confirm('SOP ini akan diaktifkan untuk publik. Lanjutkan?')">
                                            <i class="fas fa-check-circle me-2"></i> Terima & Setujui
                                        </button>
                                    </form>
                                    
                                    <button type="button" class="btn btn-danger fw-bold text-white shadow-sm w-100" data-bs-toggle="modal" data-bs-target="#modalTolak">
                                        <i class="fas fa-times-circle me-2"></i> Tolak Dokumen
                                    </button>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm bg-body">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="fw-bold mb-0 small text-uppercase">Jejak Audit</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-borderless mb-0 small">
                        <tr class="border-bottom">
                            <th class="ps-3 py-2 text-muted fw-normal">Dibuat Oleh</th>
                            <td class="pe-3 py-2 text-end fw-bold">{{ $sop->creator->username ?? 'System' }}</td>
                        </tr>
                        <tr class="border-bottom">
                            <th class="ps-3 py-2 text-muted fw-normal">Tanggal Dibuat</th>
                            <td class="pe-3 py-2 text-end">{{ $sop->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr class="border-bottom">
                            <th class="ps-3 py-2 text-muted fw-normal">Diperbarui Oleh</th>
                            <td class="pe-3 py-2 text-end fw-bold">{{ $sop->updater->username ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="ps-3 py-2 text-muted fw-normal">Terakhir Update</th>
                            <td class="pe-3 py-2 text-end text-nowrap">{{ $sop->updated_at->diffForHumans() }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- ========================================== --}}
            {{-- TIMELINE AUDIT TRAIL (TAHAP 6)             --}}
            {{-- ========================================== --}}
            <div class="card border-0 shadow-sm bg-body mt-4">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="fw-bold mb-0 text-primary">
                        <i class="fas fa-history me-2"></i> Riwayat Perubahan Status
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        // Ambil data riwayat dari database
                        $historyLogs = \Illuminate\Support\Facades\DB::table('sop_status_history')
                                        ->leftJoin('users', 'users.id', '=', 'sop_status_history.changed_by')
                                        ->select('sop_status_history.*', 'users.username as user_name')
                                        ->where('sop_id', $sop->id)
                                        ->orderBy('created_at', 'desc')
                                        ->get();
                    @endphp

                    @if($historyLogs->count() > 0)
                        <style>
                            .timeline-audit { position: relative; padding-left: 1.5rem; list-style: none; margin-bottom: 0; }
                            .timeline-audit::before { content: ''; position: absolute; top: 0; left: 0.35rem; height: 100%; width: 2px; background: var(--bs-border-color); }
                            .timeline-audit-item { position: relative; margin-bottom: 1.5rem; }
                            .timeline-audit-item:last-child { margin-bottom: 0; }
                            .timeline-audit-item::before { content: ''; position: absolute; left: -1.5rem; top: 0.25rem; width: 12px; height: 12px; border-radius: 50%; background: #0d6efd; border: 2px solid var(--bs-body-bg); box-shadow: 0 0 0 2px var(--bs-border-color); }
                            .item-Active::before { background: #198754; }
                            .item-Review::before { background: #0dcaf0; }
                            .item-Rejected::before { background: #dc3545; }
                            .item-Draft::before { background: #ffc107; }
                        </style>

                        <ul class="timeline-audit mt-2">
                            @foreach($historyLogs as $log)
                                <li class="timeline-audit-item item-{{ $log->status_baru }}">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <div class="fw-bold small">
                                            <span class="text-muted text-decoration-line-through me-1">{{ $log->status_lama }}</span> 
                                            <i class="fas fa-arrow-right text-muted mx-1" style="font-size: 0.8em;"></i> 
                                            <span class="text-primary">{{ $log->status_baru }}</span>
                                        </div>
                                        <span class="badge bg-light text-dark border" style="font-size: 0.7rem;">
                                            {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, H:i') }}
                                        </span>
                                    </div>
                                    <div class="text-muted mb-1" style="font-size: 0.8rem;">
                                        <i class="fas fa-user me-1"></i> Oleh: {{ $log->user_name ?? 'Sistem' }}
                                    </div>
                                    @if($log->catatan)
                                        <div class="bg-body-tertiary p-2 rounded border mt-2" style="font-size: 0.8rem;">
                                            <strong>Catatan:</strong> {{ $log->catatan }}
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center text-muted py-3 small">
                            <i class="fas fa-ghost mb-2 fs-3 opacity-50"></i><br>
                            Belum ada riwayat perubahan status.
                        </div>
                    @endif
                </div>
            </div>
            
        </div>
    </div>
</div>

@auth
@if(in_array(auth()->user()->role_id, [2, 3, 6]))
{{-- MODAL UPLOAD --}}
<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
            <div class="modal-header bg-body-tertiary border-bottom">
                <h5 class="modal-title fw-bold text-primary">
                    <i class="fas fa-file-pdf me-2"></i> Upload File SOP Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('sop.upload_versi', $sop->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info bg-info bg-opacity-10 text-info border-info border-opacity-25 mb-4 small">
                        <i class="fas fa-info-circle me-1"></i> File PDF akan disimpan di Private Storage.
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Nomor Versi</label>
                            <input type="text" name="versi" class="form-control" placeholder="Cth: 1.0 atau 1.1" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Pilih Dokumen (.PDF)</label>
                            <input type="file" name="file_pdf" class="form-control" accept="application/pdf" required>
                            <div class="form-text mt-2 small text-muted"><i class="fas fa-shield-alt text-success me-1"></i> Maksimal ukuran file: 10 MB.</div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Catatan Revisi</label>
                            <textarea name="catatan_revisi" class="form-control" rows="2" placeholder="Apa saja yang berubah di versi ini?"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Tanggal Berlaku</label>
                            <input type="date" name="tanggal_berlaku" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Tanggal Expired</label>
                            <input type="date" name="tanggal_expired" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-body-tertiary border-top">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">Upload & Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@if(in_array(auth()->user()->role_id, [2, 4]))
{{-- MODAL TOLAK SOP --}}
<div class="modal fade" id="modalTolak" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger">
                <h5 class="modal-title fw-bold text-white"><i class="fas fa-times-circle me-2"></i> Alasan Penolakan SOP</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('sop.tolak', $sop->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <label class="form-label fw-bold small text-muted text-uppercase">Kenapa SOP ini ditolak?</label>
                    <textarea name="catatan_approval" class="form-control" rows="4" placeholder="Jelaskan alasan penolakan atau bagian mana yang harus diperbaiki operator..." required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger fw-bold text-white">Tolak Dokumen</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endauth

@endsection