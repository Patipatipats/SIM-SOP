@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">Data SOP Inti</h4>
        <a href="{{ route('sop.create') }}" class="btn btn-primary shadow-sm px-4">
            <i class="fas fa-plus me-2"></i> Tambah SOP Baru
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('sop.index') }}" method="GET" class="row g-3">
    <div class="col-md-3">
        <input type="text" name="search" class="form-control" placeholder="Cari Kode/Judul..." value="{{ request('search') }}">
    </div>
    
    <div class="col-md-2">
        <select name="kategori" class="form-select">
            <option value="">-- Kategori --</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('kategori') == $cat->id ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <select name="unit" class="form-select">
            <option value="">-- Unit --</option>
            @foreach($units as $unit)
                <option value="{{ $unit->id }}" {{ request('unit') == $unit->id ? 'selected' : '' }}>{{ $unit->unit_singkatan }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">-- Semua Status --</option>
            <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
            <option value="Review" {{ request('status') == 'Review' ? 'selected' : '' }}>Review</option>
            <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
            <option value="Archived" {{ request('status') == 'Archived' ? 'selected' : '' }}>Archived</option>
        </select>
    </div>

    <div class="col-md-2 d-grid">
        <button type="submit" class="btn btn-dark">Filter</button>
    </div>
</form>
        </div>
    </div> 

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="small text-uppercase fw-bold text-dark">
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th>Kode SOP</th>
                            <th>Judul SOP</th>
                            <th>Kategori</th>
                            <th>Unit Kerja</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aktif</th>
                            <th class="text-center">Versi</th>
                            <th>Berlaku/Expired</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sops as $index => $sop)
                        <tr class="text-dark">
                            <td class="ps-4 text-muted">{{ $sops->firstItem() + $index }}</td>
                            <td>
                                <a href="{{ route('sop.show', $sop->id) }}" class="fw-bold text-decoration-none">{{ $sop->kode_sop }}</a>
                            </td>
                            {{-- PERBAIKAN: Gunakan 'judul' sesuai database --}}
                            <td style="max-width: 250px;" class="fw-semibold">
                                {{ $sop->judul ?? 'Tidak ada judul' }}
                            </td>
                            <td>
                                <small class="d-block">{{ $sop->kategori->nama_kategori ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border fw-normal">
                                    {{ $sop->unitKerja->unit_singkatan ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="text-center">
                                @php
                                    $statusClass = [
                                        'Draft' => 'bg-warning text-dark',
                                        'Review' => 'bg-info text-white',
                                        'Approved' => 'bg-primary text-white',
                                        'Active' => 'bg-success text-white',
                                        'Rejected' => 'bg-danger text-white',
                                        'Archived' => 'bg-secondary text-white'
                                    ];
                                @endphp
                                <span class="badge {{ $statusClass[$sop->status] ?? 'bg-light text-dark' }} px-2 py-1">
                                    {{ $sop->status }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($sop->is_active == 1)
                                    <i class="fas fa-check-circle text-success" title="Aktif"></i>
                                @else
                                    <i class="fas fa-times-circle text-muted" title="Non-aktif"></i>
                                @endif
                            </td>
@php 
    // Ambil data versi paling terakhir biar sinkron semua
    $latest = $sop->versions->sortByDesc('id')->first(); 
@endphp

{{-- 1. Kolom Versi --}}
<td class="text-center fw-bold text-secondary">
    v{{ $latest ? $latest->versi : '1.0' }}
</td>                           <td class="small">
    {{-- Ambil versi terbaru dari SOP ini --}}
    @php $latest = $sop->versions->sortByDesc('id')->first(); @endphp
    
    @if($latest)
        <div class="text-nowrap">B: <span class="text-success">{{ $latest->tanggal_berlaku ?? '-' }}</span></div>
        <div class="text-nowrap">E: <span class="text-danger">{{ $latest->tanggal_expired ?? '-' }}</span></div>
    @else
        <div class="text-muted small">Belum ada file</div>
    @endif
</td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('sop.show', $sop->id) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('sop.edit', $sop->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit text-dark"></i>
                                    </a>
                                    <form action="{{ route('sop.destroy', $sop->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus dokumen ini jing?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2" style="min-width: 200px; z-index: 1050;">
                                            <li><h6 class="dropdown-header small text-uppercase">Opsi Dokumen</h6></li>
                                            <li>
                                                <a class="dropdown-item rounded mb-1 py-2" href="{{ route('sop.versions', $sop->id) }}">
                                                    <i class="fas fa-upload me-2 text-muted"></i> Upload Versi Baru
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item rounded mb-1 py-2" href="#" target="_blank">
                                                    <i class="fas fa-file-pdf me-2 text-muted"></i> Preview Dokumen
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item rounded mb-1 py-2" href="#">
                                                    <i class="fas fa-download me-2 text-muted"></i> Download PDF
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item rounded text-primary fw-bold py-2" href="#">
                                                    <i class="fas fa-paper-plane me-2"></i> Ajukan Approval
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 d-block opacity-25"></i>
                                Belum ada data SOP yang terdaftar.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($sops->hasPages())
        <div class="card-footer bg-white border-top-0 py-3">
            {{ $sops->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    /* Agar dropdown tidak terpotong oleh tabel responsive */
    .table-responsive {
        overflow: visible !important;
    }
</style>
@endsection