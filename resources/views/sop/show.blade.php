@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 text-dark">
        <div>
            <a href="{{ route('sop.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
            </a>
            <h4 class="fw-bold mb-0">Detail Dokumen SOP</h4>
            <span class="text-muted small">{{ $sop->kode_sop }}</span>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h2 class="fw-bold text-dark mb-1">{{ $sop->judul }}</h2>
                    <p class="text-muted mb-0">Metadata Lengkap & Riwayat Dokumen</p>
                </div>
                
                <div class="text-md-end">
                    @php
                        $statusStyles = [
                            'Draft' => ['bg' => 'bg-warning', 'text' => 'text-dark', 'icon' => 'fa-file-alt'],
                            'Review' => ['bg' => 'bg-info', 'text' => 'text-white', 'icon' => 'fa-search'],
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
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-primary">Informasi Utama</h5>
                    @if($sop->is_active)
                        <span class="badge bg-success px-3">Aktif</span>
                    @else
                        <span class="badge bg-danger px-3">Non-Aktif</span>
                    @endif
                </div>
                <div class="card-body text-dark">
                    <table class="table table-borderless">
                        <tr>
                            <th width="200" class="text-muted small text-uppercase">Kode SOP</th>
                            <td class="fw-bold text-primary">: {{ $sop->kode_sop }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted small text-uppercase">Kategori</th>
                            <td>: {{ $sop->kategori->nama_kategori ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted small text-uppercase">Unit Kerja</th>
                            <td>: {{ $sop->unitKerja->nama_unit ?? '-' }} ({{ $sop->unitKerja->unit_singkatan ?? '-' }})</td>
                        </tr>
                        <tr>
                            <th class="text-muted small text-uppercase">Tag</th>
                            <td>: 
                                @if($sop->tag_sop)
                                    @foreach(explode(',', $sop->tag_sop) as $tag)
                                        <span class="badge bg-light text-primary border">{{ trim($tag) }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                    <hr>
                    <div class="mt-3">
                        <label class="text-muted small text-uppercase fw-bold mb-2 d-block">Deskripsi / Ruang Lingkup</label>
                        <p class="text-dark bg-light p-3 rounded border" style="min-height: 100px;">
                            {{ $sop->deskripsi ?? 'Tidak ada deskripsi.' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0 text-primary">Versi Dokumen & Preview</h5>
                </div>
                <div class="card-body py-5 text-center">
                    <i class="fas fa-file-pdf fa-4x text-danger opacity-25 mb-3"></i>
                    <p class="text-muted">Gunakan tombol <strong>Preview PDF</strong> di samping untuk melihat isi dokumen.</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4 border-top border-primary border-4">
                <div class="card-body">
                    <h6 class="fw-bold text-dark mb-3">Aksi Dokumen</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('sop.edit', $sop->id) }}" class="btn btn-warning fw-bold text-dark">
                            <i class="fas fa-edit me-2"></i> Edit Metadata
                        </a>
                        <a href="{{ route('sop.versions', $sop->id) }}" class="btn btn-primary">
                            <i class="fas fa-upload me-2"></i> Kelola Versi & File
                        </a>
                        <button class="btn btn-outline-danger"><i class="fas fa-file-pdf me-2"></i> Preview PDF</button>
                        <button class="btn btn-outline-dark"><i class="fas fa-download me-2"></i> Download PDF</button>
                        <hr class="my-3">
                        <button class="btn btn-outline-primary fw-bold"><i class="fas fa-paper-plane me-2"></i> Ajukan Approval</button>
                        <button class="btn btn-outline-success"><i class="fas fa-check-circle me-2"></i> Setujui Dokumen</button>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="fw-bold mb-0 small text-uppercase">Jejak Audit</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-borderless mb-0 small">
                        <tr class="border-bottom">
                            <th class="ps-3 py-2 text-muted fw-normal">Dibuat Oleh</th>
                            <td class="pe-3 py-2 text-end text-dark fw-bold">{{ $sop->creator->name ?? 'System' }}</td>
                        </tr>
                        <tr class="border-bottom">
                            <th class="ps-3 py-2 text-muted fw-normal">Tanggal Dibuat</th>
                            <td class="pe-3 py-2 text-end text-dark">{{ $sop->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr class="border-bottom">
                            <th class="ps-3 py-2 text-muted fw-normal">Diperbarui Oleh</th>
                            <td class="pe-3 py-2 text-end text-dark fw-bold">{{ $sop->updater->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="ps-3 py-2 text-muted fw-normal">Terakhir Update</th>
                            <td class="pe-3 py-2 text-end text-dark text-nowrap">{{ $sop->updated_at->diffForHumans() }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection