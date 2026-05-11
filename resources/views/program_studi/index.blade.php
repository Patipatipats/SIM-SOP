@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">Master Data - Program Studi</h4>
        <button type="button" class="btn btn-primary shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#createProdiModal">
            <i class="fas fa-plus me-2"></i> Tambah Program Studi
        </button>
    </div>

    <div class="card border-0 shadow-sm bg-body mb-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="small text-uppercase fw-bold text-dark">
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th>Kode Prodi</th>
                            <th>Nama Program Studi</th>
                            <th>Singkatan</th>
                            <th>Fakultas</th>
                            <th class="text-center">Status</th>
                            <th>Dibuat Pada</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prodi as $index => $p)
                        <tr class="text-dark">
                            <td class="ps-4 text-muted">{{ $prodi->firstItem() + $index }}</td>
                            <td class="fw-bold text-primary">{{ $p->kode_prodi }}</td>
                            <td class="fw-semibold">{{ $p->nama }}</td>
                            <td><span class="badge bg-light text-primary border">{{ $p->singkatan }}</span></td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-dark border fw-normal">
                                    {{ $p->nama_fakultas }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if(isset($p->status) && $p->status == 1)
                                    <span class="badge rounded-pill bg-success px-3">Aktif</span>
                                @else
                                    <span class="badge rounded-pill bg-danger px-3">Nonaktif</span>
                                @endif
                            </td>
                            <td>{{ $p->created_at ? \Carbon\Carbon::parse($p->created_at)->format('d/m/Y') : '-' }}</td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-warning btn-edit-prodi" 
                                        data-id="{{ $p->id }}"
                                        data-fakultas="{{ $p->fakultas_id }}"
                                        data-kode="{{ $p->kode_prodi }}"
                                        data-nama="{{ $p->nama }}"
                                        data-singkatan="{{ $p->singkatan }}"
                                        data-status="{{ $p->status ?? 1 }}"
                                        title="Edit">
                                        <i class="fas fa-edit text-dark"></i>
                                    </button>

                                    <form action="{{ route('program_studi.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus prodi ini jing?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center py-5 text-muted">Belum ada data program studi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 🔥 PAGINATION CAKEP & RAPI --}}
    <div class="d-flex justify-content-between align-items-center px-2">
        <div class="small text-muted">
            Menampilkan <strong>{{ $prodi->firstItem() ?? 0 }}</strong> sampai <strong>{{ $prodi->lastItem() ?? 0 }}</strong> dari <strong>{{ $prodi->total() }}</strong> data
        </div>
        <div class="pagination-sm">
            {{ $prodi->links() }}
        </div>
    </div>
</div>

{{-- MODAL CREATE PRODI --}}
<div class="modal fade" id="createProdiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-graduation-cap me-2"></i> Tambah Prodi Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('program_studi.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Fakultas (Relasi)</label>
                        <select name="fakultas_id" class="form-select" required>
                            <option value="">-- Pilih Fakultas --</option>
                            @foreach($allFakultas as $f)
                                <option value="{{ $f->id }}">{{ $f->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Kode Prodi</label>
                        <input type="text" class="form-control bg-light" placeholder="Otomatis oleh sistem" readonly>
                        <small class="text-muted">Kode akan dibuat otomatis setelah data disimpan.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nama Lengkap Program Studi</label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama Prodi" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Singkatan</label>
                            <input type="text" name="singkatan" class="form-control" placeholder="Cth: TIF" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">Simpan Prodi</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT PRODI --}}
<div class="modal fade" id="editProdiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-edit me-2"></i> Edit Prodi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editProdiForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Fakultas</label>
                        <select id="edit_fakultas_id" name="fakultas_id" class="form-select" required>
                            @foreach($allFakultas as $f)
                                <option value="{{ $f->id }}">{{ $f->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Kode Prodi</label>
                        <input type="text" id="edit_kode_prodi" name="kode_prodi" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nama Lengkap Program Studi</label>
                        <input type="text" id="edit_nama" name="nama" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Singkatan</label>
                            <input type="text" id="edit_singkatan" name="singkatan" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Status</label>
                            <select id="edit_status" name="status" class="form-select" required>
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-bold px-4">Update Prodi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.btn-edit-prodi');
        const editModal = new bootstrap.Modal(document.getElementById('editProdiModal'));
        const editForm = document.getElementById('editProdiForm');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                editForm.action = `/program_studi/${id}`; 
                
                document.getElementById('edit_fakultas_id').value = this.getAttribute('data-fakultas');
                document.getElementById('edit_kode_prodi').value = this.getAttribute('data-kode');
                document.getElementById('edit_nama').value = this.getAttribute('data-nama');
                document.getElementById('edit_singkatan').value = this.getAttribute('data-singkatan');
                document.getElementById('edit_status').value = this.getAttribute('data-status');

                editModal.show();
            });
        });
    });
</script>

<style>
    /* Pagination Fix */
    .pagination { margin-bottom: 0; }
    .page-link { padding: 0.25rem 0.75rem; font-size: 0.875rem; border-radius: 6px; margin: 0 2px; }
</style>
@endsection