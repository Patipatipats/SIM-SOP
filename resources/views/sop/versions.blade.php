@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <a href="{{ route('sop.show', $sop->id) }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Detail
            </a>
            <h4 class="fw-bold mb-0 text-body">Riwayat Versi Dokumen</h4>
            <span class="text-muted small">{{ $sop->kode_sop }} - {{ $sop->judul }}</span>
        </div>
        
        @auth
        @if(in_array(auth()->user()->role_id, [2, 3, 6]))
            <button type="button" class="btn btn-primary fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i class="fas fa-upload me-2"></i> Upload Versi Baru
            </button>
        @endif
        @endauth
    </div>

    <div class="card border-0 shadow-sm bg-body" style="border-radius: 12px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-body-tertiary">
                        <tr>
                            <th class="ps-4 py-3 text-muted small text-uppercase" width="10%">Versi</th>
                            <th class="py-3 text-muted small text-uppercase">File Dokumen</th>
                            <th class="py-3 text-muted small text-uppercase">Tanggal Upload</th>
                            <th class="py-3 text-muted small text-uppercase">Pengunggah</th>
                            <th class="text-end pe-4 py-3 text-muted small text-uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Looping data dari relasi $sop->versions yang udah kita buat di Model --}}
                        @forelse($sop->versions as $version)
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-primary px-3 py-2 rounded-pill shadow-sm">V{{ $version->versi }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-file-pdf fs-4 text-danger me-3"></i>
                                        <div>
                                            <div class="fw-bold text-body mb-0">Dokumen SOP (V{{ $version->versi }})</div>
                                            <small class="text-muted">Aman (Private Storage)</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-medium text-body">{{ $version->created_at->format('d M Y') }}</div>
                                    <small class="text-muted">{{ $version->created_at->format('H:i') }} WIB</small>
                                </td>
                                <td>
                                    {{-- Ambil nama user berdasarkan ID created_by secara aman --}}
                                    @php $uploader = \App\Models\User::find($version->created_by); @endphp
                                    <span class="fw-medium text-body">{{ $uploader->name ?? $uploader->username ?? 'Sistem' }}</span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('sop.lihat_pdf', $version->id) }}" target="_blank" class="btn btn-sm btn-info text-dark fw-semibold" title="Buka Fullscreen">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    <a href="{{ route('sop.lihat_pdf', $version->id) }}" download class="btn btn-sm btn-secondary fw-semibold" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i><br>
                                    <span class="fw-medium">Belum ada riwayat versi file.</span><br>
                                    <small>Silakan klik tombol 'Upload Versi Baru' di atas.</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
                        <i class="fas fa-info-circle me-1"></i> File akan disimpan di Private Storage dan menjadi versi terbaru dari dokumen ini.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nomor Versi</label>
                        <input type="text" name="versi" class="form-control" placeholder="Cth: 1.0 atau 1.1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Pilih Dokumen (.PDF)</label>
                        <input type="file" name="file_pdf" class="form-control" accept="application/pdf" required>
                        <div class="form-text mt-2"><i class="fas fa-shield-alt text-success me-1"></i> Maksimal ukuran file: 5 MB.</div>
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