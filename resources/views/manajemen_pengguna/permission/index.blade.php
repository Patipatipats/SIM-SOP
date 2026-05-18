@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">Master Data - Permission</h4>
        <button type="button" class="btn btn-primary shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#createPermissionModal">
            <i class="fas fa-key me-2"></i> Tambah Permission
        </button>
    </div>

    {{-- TABEL DATA PERMISSION --}}
    <div class="card border-0 shadow-sm bg-body mb-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="small text-uppercase fw-bold text-dark">
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th>Nama Permission (Kode Akses)</th>
                            <th>Deskripsi</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissions as $index => $p)
                        <tr class="text-dark">
                            <td class="ps-4 text-muted">{{ $permissions->firstItem() + $index }}</td>
                            <td class="fw-bold text-danger">{{ $p->nama_permission }}</td>
                            <td class="text-muted small">{{ $p->deskripsi ?? 'Tidak ada deskripsi' }}</td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-warning btn-edit-permission" 
                                        data-id="{{ $p->id }}"
                                        data-nama="{{ $p->nama_permission }}"
                                        data-deskripsi="{{ $p->deskripsi }}"
                                        title="Edit Permission">
                                        <i class="fas fa-edit text-dark"></i>
                                    </button>

                                    <form action="{{ route('permission.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus permission ini jing? Nanti role yang pake akses ini bisa ikutan error!')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Permission">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-5 text-muted">Belum ada data Permission.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-between align-items-center px-2">
        <div class="small text-muted">Menampilkan <strong>{{ $permissions->firstItem() ?? 0 }}</strong> - <strong>{{ $permissions->lastItem() ?? 0 }}</strong> dari <strong>{{ $permissions->total() }}</strong> permission</div>
        <div class="pagination-sm">{{ $permissions->links() }}</div>
    </div>
</div>

{{-- ========================================== --}}
{{-- 1. MODAL TAMBAH PERMISSION                 --}}
{{-- ========================================== --}}
<div class="modal fade" id="createPermissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-key me-2"></i> Tambah Permission Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('permission.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nama Permission</label>
                        <input type="text" name="nama_permission" class="form-control" placeholder="Contoh: create_sop, edit_user, delete_role" required>
                        <small class="text-muted form-text">Gunakan format huruf kecil dan underscore (tanpa spasi).</small>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold small text-muted text-uppercase">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="2" placeholder="Jelaskan fungsi dari permission ini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ========================================== --}}
{{-- 2. MODAL EDIT PERMISSION                   --}}
{{-- ========================================== --}}
<div class="modal fade" id="editPermissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-edit me-2"></i> Edit Permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editPermissionForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nama Permission</label>
                        <input type="text" id="edit_nama" name="nama_permission" class="form-control" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold small text-muted text-uppercase">Deskripsi</label>
                        <textarea id="edit_deskripsi" name="deskripsi" class="form-control" rows="2"></textarea>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.btn-edit-permission');
        const editModal = new bootstrap.Modal(document.getElementById('editPermissionModal'));
        const editForm = document.getElementById('editPermissionForm');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                editForm.action = `/permission/${id}`; 
                
                document.getElementById('edit_nama').value = this.getAttribute('data-nama');
                document.getElementById('edit_deskripsi').value = this.getAttribute('data-deskripsi');

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