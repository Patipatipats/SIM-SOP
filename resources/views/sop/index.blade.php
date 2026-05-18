@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">Data SOP Inti</h4>
        <button type="button" class="btn btn-primary shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#createSopModal">
            <i class="fas fa-plus me-2"></i> Tambah SOP Baru
        </button>
    </div>

    {{-- FILTER SEARCH --}}
    <div class="card border-0 shadow-sm mb-4 bg-body">
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

    {{-- TABEL DATA --}}
    <div class="card border-0 shadow-sm bg-body mb-3">
        <div class="card-body p-0">
            <div class="table-responsive" style="overflow: visible !important;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="small text-uppercase fw-bold text-dark">
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th>Kode SOP</th>
                            <th>Judul SOP</th>
                            <th>Kategori</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Versi</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sops as $index => $sop)
                        <tr class="text-dark">
                            <td class="ps-4 text-muted">{{ $sops->firstItem() + $index }}</td>
                            <td>
                                <a href="{{ route('sop.show', $sop->id) }}" class="fw-bold text-decoration-none text-primary">{{ $sop->kode_sop }}</a>
                            </td>
                            <td style="max-width: 250px;" class="fw-semibold">{{ $sop->judul }}</td>
                            <td><small>{{ $sop->kategori->nama_kategori ?? 'N/A' }}</small></td>
                            <td class="text-center">
                                @php
                                    $statusClass = [
                                        'Draft' => 'bg-warning text-dark',
                                        'Review' => 'bg-info text-white',
                                        'Active' => 'bg-success text-white',
                                        'Rejected' => 'bg-danger text-white',
                                        'Archived' => 'bg-secondary text-white'
                                    ];
                                @endphp
                                <span class="badge {{ $statusClass[$sop->status] ?? 'bg-light text-dark' }} px-2 py-1">
                                    {{ $sop->status }}
                                </span>
                            </td>
                            @php $latest = $sop->versions->sortByDesc('id')->first(); @endphp
                            <td class="text-center fw-bold text-secondary">v{{ $latest ? $latest->versi : '1.0' }}</td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('sop.show', $sop->id) }}" class="btn btn-sm btn-outline-primary" title="Detail"><i class="fas fa-eye"></i></a>
                                    
                                    {{-- TOMBOL EDIT MODAL (Kirim data tag juga ke JS) --}}
                                    <button type="button" class="btn btn-sm btn-outline-warning btn-edit-sop" 
                                        data-id="{{ $sop->id }}" 
                                        data-judul="{{ $sop->judul }}"
                                        data-kategori="{{ $sop->kategori_id }}"
                                        data-unit="{{ $sop->unit_kerja_id }}"
                                        data-deskripsi="{{ $sop->deskripsi }}"
                                        data-status="{{ $sop->status }}"
                                        data-kode="{{ $sop->kode_sop }}"
                                        {{-- Pluck ID tag yang nempel di SOP ini jadi array JSON string --}}
                                        data-tags="{{ json_encode($sop->tags->pluck('id')->toArray()) }}"
                                        title="Edit">
                                        <i class="fas fa-edit text-dark"></i>
                                    </button>

                                    <form action="{{ route('sop.destroy', $sop->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus dokumen ini jing?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="fas fa-trash"></i></button>
                                    </form>
                                    
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown"></button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2" style="z-index: 1050;">
                                            <li><h6 class="dropdown-header small text-uppercase">Opsi Dokumen</h6></li>
                                            <li><a class="dropdown-item rounded py-2" href="{{ route('sop.versions', $sop->id) }}"><i class="fas fa-upload me-2 text-muted"></i> Riwayat Versi</a></li>
                                            @if($latest)
                                                <li><a class="dropdown-item rounded py-2" href="{{ route('sop.download', $latest->id) }}"><i class="fas fa-download me-2 text-muted"></i> Download PDF</a></li>
                                            @endif
                                            @if(in_array($sop->status, ['Draft', 'Rejected']))
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('sop.ajukan', $sop->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item rounded text-primary fw-bold py-2"><i class="fas fa-paper-plane me-2"></i> Ajukan Approval</button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-5 text-muted">Belum ada data SOP.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- PAGINATION RAPI --}}
    <div class="d-flex justify-content-between align-items-center px-2">
        <div class="small text-muted">
            Menampilkan <strong>{{ $sops->firstItem() ?? 0 }}</strong> sampai <strong>{{ $sops->lastItem() ?? 0 }}</strong> dari <strong>{{ $sops->total() }}</strong> data
        </div>
        <div class="pagination-sm">
            {{ $sops->links() }}
        </div>
    </div>
</div>

{{-- ========================================== --}}
{{-- 1. MODAL TAMBAH SOP BARU                   --}}
{{-- ========================================== --}}
<div class="modal fade" id="createSopModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle me-2"></i> Registrasi SOP Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('sop.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Judul Dokumen</label>
                        <input type="text" name="judul" class="form-control" placeholder="Masukan judul lengkap SOP" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Kategori SOP</label>
                            <select name="kategori_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Unit Kerja</label>
                            <select name="unit_kerja_id" class="form-select" required>
                                <option value="">-- Pilih Unit Kerja --</option>
                                @foreach($units as $u) <option value="{{ $u->id }}">{{ $u->unit_singkatan }}</option> @endforeach
                            </select>
                        </div>
                    </div>
                    
                    {{-- 🔥 TAMBAHAN BIDANG: TAG SOP SESUAI MODAL PDF LU --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase d-block">Tag SOP (Pilihan Kunci Pencarian)</label>
                        <div class="card p-3 bg-light border-0">
                            <div class="row">
                                @forelse($allTags ?? [] as $tag)
                                    <div class="col-md-3 col-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tags[]" value="{{ $tag->id }}" id="create_tag_{{ $tag->id }}">
                                            <label class="form-check-label small text-dark" for="create_tag_{{ $tag->id }}">
                                                <i class="fas fa-tag me-1 text-muted"></i> {{ $tag->nama }}
                                            </label>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12"><small class="text-muted">Belum ada master tag di sistem, isi dulu!</small></div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold small text-muted text-uppercase">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Penjelasan singkat mengenai isi dokumen..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">Simpan SOP</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ========================================== --}}
{{-- 2. MODAL EDIT SOP (Dynamic)                --}}
{{-- ========================================== --}}
<div class="modal fade" id="editSopModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-edit me-2"></i> Update Metadata SOP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editSopForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Kode SOP (Read Only)</label>
                            <input type="text" id="edit_kode" name="kode_sop" class="form-control bg-light" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Status Dokumen</label>
                            <select id="edit_status" name="status" class="form-select" required>
                                <option value="Draft">Draft</option>
                                <option value="Review">Review</option>
                                <option value="Active">Active</option>
                                <option value="Archived">Archived</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Judul Dokumen</label>
                        <input type="text" id="edit_judul" name="judul" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Kategori</label>
                            <select id="edit_kategori" name="kategori_id" class="form-select" required>
                                @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Unit Kerja</label>
                            <select id="edit_unit" name="unit_kerja_id" class="form-select" required>
                                @foreach($units as $u) <option value="{{ $u->id }}">{{ $u->unit_singkatan }}</option> @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- 🔥 INPUT TAG DI MODAL EDIT --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase d-block">Tag SOP (Update)</label>
                        <div class="card p-3 bg-light border-0">
                            <div class="row">
                                @foreach($allTags ?? [] as $tag)
                                    <div class="col-md-3 col-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input edit-tag-checkbox" type="checkbox" name="tags[]" value="{{ $tag->id }}" id="edit_tag_{{ $tag->id }}">
                                            <label class="form-check-label small text-dark" for="edit_tag_{{ $tag->id }}">
                                                <i class="fas fa-tag me-1 text-muted"></i> {{ $tag->nama }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold small text-muted text-uppercase">Deskripsi</label>
                        <textarea id="edit_deskripsi" name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-bold px-4">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT BUAT NGATUR ISI MODAL EDIT --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.btn-edit-sop');
        const editModal = new bootstrap.Modal(document.getElementById('editSopModal'));
        const editForm = document.getElementById('editSopForm');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                editForm.action = `/sop/${id}`;
                
                // Isi input text & select biasa
                document.getElementById('edit_kode').value = this.getAttribute('data-kode');
                document.getElementById('edit_judul').value = this.getAttribute('data-judul');
                document.getElementById('edit_kategori').value = this.getAttribute('data-kategori');
                document.getElementById('edit_unit').value = this.getAttribute('data-unit');
                document.getElementById('edit_deskripsi').value = this.getAttribute('data-deskripsi');
                document.getElementById('edit_status').value = this.getAttribute('data-status');

                // 🔥 LOGIKA MENCENTANG CHECKBOX TAG DI MODAL EDIT
                // Ambil string json array tag id yang nempel di tombol
                const attachedTags = JSON.parse(this.getAttribute('data-tags') || '[]');
                
                // Reset semua checkbox biar gak sisa dari klik sebelumnya
                const tagCheckboxes = document.querySelectorAll('.edit-tag-checkbox');
                tagCheckboxes.forEach(cb => {
                    // Jika id checkbox ini ada di dalam list array JSON, auto centang!
                    if(attachedTags.includes(parseInt(cb.value))) {
                        cb.checked = true;
                    } else {
                        cb.checked = false;
                    }
                });

                editModal.show();
            });
        });
    });
</script>

<style>
    .pagination { margin-bottom: 0; }
    .page-link { padding: 0.25rem 0.75rem; font-size: 0.875rem; border-radius: 6px; margin: 0 2px; }
</style>
@endsection