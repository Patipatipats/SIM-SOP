@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold">Detail Kategori SOP</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Kategori SOP</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </nav>
        </div>
        <div class="btn-group">
            <a href="{{ route('kategori.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
            <a href="{{ route('kategori.edit', $kategori->id) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i> Edit Data
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary">Informasi Kategori</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <th class="ps-4 bg-light" style="width: 200px;">Nama Kategori</th>
                                <td class="ps-4 fw-bold text-dark">{{ $kategori->nama_kategori }}</td>
                            </tr>
                            <tr>
                                <th class="ps-4 bg-light">Slug</th>
                                <td class="ps-4"><code class="text-primary">{{ $kategori->slug }}</code></td>
                            </tr>
                            <tr>
                                <th class="ps-4 bg-light">Deskripsi</th>
                                <td class="ps-4 text-muted">{{ $kategori->deskripsi ?? 'Tidak ada deskripsi.' }}</td>
                            </tr>
                            <tr>
                                <th class="ps-4 bg-light">Status</th>
                                <td class="ps-4">
                                    @if($kategori->status)
                                        <span class="badge rounded-pill bg-success px-3">Aktif</span>
                                    @else
                                        <span class="badge rounded-pill bg-danger px-3">Nonaktif</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-4 bg-light">Jumlah Dokumen SOP</th>
                                <td class="ps-4">
                                    <span class="badge bg-info text-dark px-3">{{ $kategori->sops_count ?? 0 }} Dokumen</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-history me-2"></i> Jejak Audit</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small d-block">Dibuat Oleh:</label>
                        <span class="fw-semibold">{{ $kategori->creator->profil->nama ?? 'System' }}</span>
                        <small class="d-block text-secondary">{{ $kategori->created_at->format('d F Y, H:i') }}</small>
                    </div>
                    <hr>
                    <div class="mb-0">
                        <label class="text-muted small d-block">Terakhir Diperbarui:</label>
                        <span class="fw-semibold">{{ $kategori->updater->profil->nama ?? '-' }}</span>
                        <small class="d-block text-secondary">{{ $kategori->updated_at->format('d F Y, H:i') }}</small>
                    </div>
                </div>
            </div>

            <div class="card border-danger mt-4 bg-danger-subtle">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <span class="text-danger small fw-bold">Hapus permanen data ini?</span>
                    <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST" onsubmit="return confirm('Yakin mau hapus jing?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection