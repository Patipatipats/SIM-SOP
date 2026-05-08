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

    <div class="card border-0 shadow-sm mb-4 bg-body">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h2 class="fw-bold mb-1">{{ $sop->judul }}</h2>
                    <p class="text-muted mb-0">Metadata Lengkap & Riwayat Dokumen</p>
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
                    
                    <div class="d-inline-flex align-items-center {{ $style['bg'] }} {{ $style['text'] }} px-4 py-2 rounded-pill shadow-sm">
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
                        {{-- DI SINI SUDAH SAYA TAMBAHKAN SORTBYDESC --}}
                        @php $latestVersion = $sop->versions->sortByDesc('id')->first(); @endphp
                        <span class="badge bg-primary rounded-pill px-3 py-2">Versi Aktif: V{{ $latestVersion->versi }}</span>
                    @endif
                </div>
                
                <div class="card-body p-0 text-center" style="background: #525659; min-height: 500px; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
                    @if($sop->versions->count() > 0)
                        {{-- DI SINI JUGA SAYA TAMBAHKAN SORTBYDESC --}}
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
                            {{-- DI SINI JUGA SAYA TAMBAHKAN SORTBYDESC --}}
                            @php $latestVersion = $sop->versions->sortByDesc('id')->first(); @endphp
                            <a href="{{ route('sop.lihat_pdf', $latestVersion->id) }}" target="_blank" class="btn btn-outline-danger fw-semibold">
                                <i class="fas fa-external-link-alt me-2"></i> Buka Fullscreen
                            </a>
                            <a href="{{ route('sop.lihat_pdf', $latestVersion->id) }}" download class="btn btn-outline-secondary fw-semibold">
                                <i class="fas fa-download me-2"></i> Download PDF
                            </a>
                        @else
                            <button class="btn btn-outline-danger fw-semibold" disabled><i class="fas fa-file-pdf me-2"></i> Buka Fullscreen</button>
                            <button class="btn btn-outline-secondary fw-semibold" disabled><i class="fas fa-download me-2"></i> Download PDF</button>
                        @endif
                        
                        @auth
                            @php $roleId = auth()->user()->role_id; @endphp
                            
                            {{-- Admin & Operator Unit --}}
                            @if(in_array($roleId, [2, 3, 6]))
                                <hr class="my-2">
                                <a href="{{ route('sop.edit', $sop->id) }}" class="btn btn-warning fw-bold text-dark">
                                    <i class="fas fa-edit me-2"></i> Edit Metadata
                                </a>
                                
                                {{-- Tombol ini memicu Modal Upload (Kode Modal ada di paling bawah) --}}
                                <button type="button" class="btn btn-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                    <i class="fas fa-upload me-2"></i> Upload File & Versi Baru
                                </button>
                                
                                <a href="{{ route('sop.versions', $sop->id) }}" class="btn btn-outline-primary fw-semibold">
                                    <i class="fas fa-history me-2"></i> Lihat Riwayat Versi
                                </a>
                                
                                @if(in_array($sop->status, ['Draft', 'Rejected']))
                                    <button class="btn btn-outline-primary fw-bold mt-2">
                                        <i class="fas fa-paper-plane me-2"></i> Ajukan Approval
                                    </button>
                                @endif
                            @endif

                            {{-- Approver --}}
                            @if(in_array($roleId, [2, 4]) && $sop->status == 'Review')
                                <hr class="my-2">
                                <button class="btn btn-success fw-bold text-white shadow-sm">
                                    <i class="fas fa-check-circle me-2"></i> Setujui Dokumen
                                </button>
                                <button class="btn btn-danger fw-bold text-white shadow-sm mt-1">
                                    <i class="fas fa-times-circle me-2"></i> Tolak / Revisi
                                </button>
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
                            <td class="pe-3 py-2 text-end fw-bold">{{ $sop->creator->name ?? 'System' }}</td>
                        </tr>
                        <tr class="border-bottom">
                            <th class="ps-3 py-2 text-muted fw-normal">Tanggal Dibuat</th>
                            <td class="pe-3 py-2 text-end">{{ $sop->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr class="border-bottom">
                            <th class="ps-3 py-2 text-muted fw-normal">Diperbarui Oleh</th>
                            <td class="pe-3 py-2 text-end fw-bold">{{ $sop->updater->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="ps-3 py-2 text-muted fw-normal">Terakhir Update</th>
                            <td class="pe-3 py-2 text-end text-nowrap">{{ $sop->updated_at->diffForHumans() }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</div>

@auth
@if(in_array(auth()->user()->role_id, [2, 3, 6]))
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
            <div class="modal-header bg-body-tertiary border-bottom">
                <h5 class="modal-title fw-bold text-primary" id="uploadModalLabel">
                    <i class="fas fa-file-pdf me-2"></i> Upload File SOP Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('sop.upload_versi', $sop->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    
                    <div class="alert alert-info bg-info bg-opacity-10 text-info border-info border-opacity-25 mb-4 small">
                        <i class="fas fa-info-circle me-1"></i> File PDF akan disimpan di Private Storage. Publik hanya bisa melihat via sistem.
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
@endauth

@endsection