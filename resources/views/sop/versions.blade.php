@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('sop.show', $sop->id) }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Detail
            </a>
            <h4 class="fw-bold text-dark mb-0">Riwayat Versi & File: {{ $sop->kode_sop }}</h4>
            <p class="text-muted mb-0">{{ $sop->judul }}</p>
        </div>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="fas fa-upload me-2"></i> Upload Versi Baru
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-dark">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Versi</th>
                        <th>Keterangan Perubahan</th>
                        <th>Tanggal Upload</th>
                        <th>Pengunggah</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Nanti di sini pakai @foreach($sop->versions) --}}
                    <tr>
                        <td class="ps-4 text-muted small">1</td>
                        <td><span class="badge bg-info">v1.0</span></td>
                        <td>Inisialisasi dokumen awal (Metadata)</td>
                        <td>{{ $sop->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $sop->creator->name ?? 'System' }}</td>
                        <td class="text-center">
                            <span class="text-muted small italic">Belum ada file</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="alert alert-info mt-4 border-0 shadow-sm d-flex align-items-center">
        <i class="fas fa-info-circle fa-2x me-3"></i>
        <div>
            <h6 class="fw-bold mb-1">Catatan Tahap 3</h6>
            <p class="mb-0 small text-dark">Halaman ini adalah jembatan ke **Tahap 4 (Sistem Upload)**. Untuk saat ini, data di atas hanya menampilkan info dasar dari metadata yang kamu buat.</p>
        </div>
    </div>
</div>
@endsection