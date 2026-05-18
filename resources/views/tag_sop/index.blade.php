@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">Master Data - Tag SOP</h4>
        {{-- Tombol Tambah Tag --}}
        <button type="button" class="btn btn-primary shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#createTagModal">
            <i class="fas fa-plus me-2"></i> Tambah Tag
        </button>
    </div>

    <div class="card border-0 shadow-sm bg-body mb-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="small text-uppercase fw-bold text-dark">
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th>Nama Tag</th>
                            <th class="text-center">Jumlah SOP</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tags as $index => $t)
                        <tr class="text-dark">
                            <td class="ps-4 text-muted">{{ $tags->firstItem() + $index }}</td>
                            <td class="fw-bold text-dark">{{ $t->nama }}</td>
                            <td class="text-center">
                                {{-- Menampilkan Jumlah SOP yang memakai tag ini secara real-time --}}
                                <span class="badge bg-info text-dark px-3 py-2 fw-semibold rounded-pill">
                                    {{ $t->sop_count ?? 0 }} SOP
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    {{-- Tombol Edit --}}
                                    <button type="button" class="btn btn-sm btn-outline-warning btn-edit-tag" 
                                        data-id="{{ $t->id }}"
                                        data-nama="{{ $t->nama }}"
                                        title="Edit Tag">
                                        <i class="fas fa-edit text-dark"></i>
                                    </button>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('tag_sop.destroy', $t->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus tag ini jing?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Tag">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">Belum ada data Tag SOP.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination Ganteng & Rapih --}}
    <div class="d-flex justify-content-between align-items-center px-2">
        <div class="small text-muted">
            Menampilkan <strong>{{ $tags->firstItem() ?? 0 }}</strong> sampai <strong>{{ $tags->lastItem() ?? 0 }}</strong> dari <strong>{{ $tags->total() }}</strong> data
        </div>
        <div class="pagination-sm">
            {{ $tags->links() }}
        </div>
    </div>
</div>

{{-- ========================================== --}}
{{-- 1. MODAL TAMBAH TAG SOP                    --}}
{{-- ========================================== --}}
<div class="modal fade" id="createTagModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-tag me-2"></i> Tambah Tag SOP</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('tag_sop.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-0">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nama Tag</label>
                        <input type="text" name="nama" class="form-control" placeholder="Contoh: akademik, keuangan, layanan, audit" required>
                        <div class="form-text text-muted small mt-1">Nama tag wajib diisi dan sebaiknya unik.</div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">Simpan Tag</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ========================================== --}}
{{-- 2. MODAL EDIT TAG SOP (Dynamic)            --}}
{{-- ========================================== --}}
<div class="modal fade" id="editTagModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-edit me-2"></i> Edit Tag</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editTagForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-0">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nama Tag</label>
                        <input type="text" id="edit_nama" name="nama" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-bold px-4">Update Tag</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT JAVASCRIPT UNTUK MODAL EDIT --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.btn-edit-tag');
        const editModal = new bootstrap.Modal(document.getElementById('editTagModal'));
        const editForm = document.getElementById('editTagForm');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                editForm.action = `/tag_sop/${id}`; 
                
                document.getElementById('edit_nama').value = this.getAttribute('data-nama');

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