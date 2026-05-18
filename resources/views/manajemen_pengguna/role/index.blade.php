@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">Master Data - Role & Permission</h4>
        <button type="button" class="btn btn-primary shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#createRoleModal">
            <i class="fas fa-shield-alt me-2"></i> Tambah Role Baru
        </button>
    </div>

    {{-- TABEL DATA ROLE --}}
    <div class="card border-0 shadow-sm bg-body mb-3">
        <div class="card-body p-0">
            <div class="table-responsive" style="overflow: visible !important;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="small text-uppercase fw-bold text-dark">
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th>Nama Role</th>
                            <th>Deskripsi</th>
                            <th class="text-center">Jumlah Permission</th>
                            <th class="text-center">Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $index => $r)
                        <tr class="text-dark">
                            <td class="ps-4 text-muted">{{ $roles->firstItem() + $index }}</td>
                            <td class="fw-bold text-primary">{{ $r->nama_role }}</td>
                            <td class="text-muted small" style="max-width: 250px; white-space: normal;">{{ $r->deskripsi ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge bg-info text-dark rounded-pill px-3 py-2 fw-semibold">
                                    {{ $r->permission_count ?? 0 }} Akses
                                </span>
                            </td>
                            <td class="text-center">
                                @if($r->status == 1)
                                    <span class="badge rounded-pill bg-success px-3">Aktif</span>
                                @else
                                    <span class="badge rounded-pill bg-danger px-3">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    {{-- Tombol Edit + Bawa Data JSON Permission --}}
                                    <button type="button" class="btn btn-sm btn-outline-warning btn-edit-role" 
                                        data-id="{{ $r->id }}"
                                        data-nama="{{ $r->nama_role }}"
                                        data-deskripsi="{{ $r->deskripsi }}"
                                        data-status="{{ $r->status }}"
                                        data-permissions="{{ json_encode($r->permission_ids) }}"
                                        title="Edit Role & Permission">
                                        <i class="fas fa-edit text-dark"></i>
                                    </button>

                                    <form action="{{ route('role.destroy', $r->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus role ini jing? User yang pake role ini bisa error loh!')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Role">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada data Role.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-between align-items-center px-2">
        <div class="small text-muted">Menampilkan <strong>{{ $roles->firstItem() ?? 0 }}</strong> - <strong>{{ $roles->lastItem() ?? 0 }}</strong> dari <strong>{{ $roles->total() }}</strong> role</div>
        <div class="pagination-sm">{{ $roles->links() }}</div>
    </div>
</div>

{{-- ========================================== --}}
{{-- 1. MODAL TAMBAH ROLE & PERMISSION          --}}
{{-- ========================================== --}}
<div class="modal fade" id="createRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-shield-alt me-2"></i> Tambah Role Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('role.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Nama Role</label>
                            <input type="text" name="nama_role" class="form-control" placeholder="Contoh: Admin, Operator, Approver" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Deskripsi (Opsional)</label>
                        <textarea name="deskripsi" class="form-control" rows="2" placeholder="Keterangan singkat hak akses role ini..."></textarea>
                    </div>
                    
                    {{-- CHECKBOX GRID UNTUK PERMISSION --}}
                    <div class="mb-0">
                        <label class="form-label fw-bold small text-muted text-uppercase d-block border-bottom pb-2 mb-3">
                            <i class="fas fa-key me-2"></i>Atur Hak Akses (Permissions)
                        </label>
                        <div class="card p-3 bg-light border-0" style="max-height: 300px; overflow-y: auto;">
                            <div class="row">
                                @forelse($permissions as $perm)
                                    <div class="col-md-4 col-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $perm->id }}" id="create_perm_{{ $perm->id }}">
                                            <label class="form-check-label small text-dark" for="create_perm_{{ $perm->id }}">
                                                {{ $perm->nama_permission }}
                                            </label>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-muted small">Master permission belum ada di database jing!</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">Simpan Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ========================================== --}}
{{-- 2. MODAL EDIT ROLE & PERMISSION            --}}
{{-- ========================================== --}}
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-edit me-2"></i> Edit Role & Permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editRoleForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Nama Role</label>
                            <input type="text" id="edit_nama" name="nama_role" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Status</label>
                            <select id="edit_status" name="status" class="form-select" required>
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Deskripsi</label>
                        <textarea id="edit_deskripsi" name="deskripsi" class="form-control" rows="2"></textarea>
                    </div>

                    {{-- CHECKBOX GRID EDIT PERMISSION --}}
                    <div class="mb-0">
                        <label class="form-label fw-bold small text-muted text-uppercase d-block border-bottom pb-2 mb-3">
                            <i class="fas fa-key me-2"></i>Update Hak Akses
                        </label>
                        <div class="card p-3 bg-light border-0" style="max-height: 300px; overflow-y: auto;">
                            <div class="row">
                                @foreach($permissions as $perm)
                                    <div class="col-md-4 col-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input edit-perm-checkbox" type="checkbox" name="permissions[]" value="{{ $perm->id }}" id="edit_perm_{{ $perm->id }}">
                                            <label class="form-check-label small text-dark" for="edit_perm_{{ $perm->id }}">
                                                {{ $perm->nama_permission }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-bold px-4">Update Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.btn-edit-role');
        const editModal = new bootstrap.Modal(document.getElementById('editRoleModal'));
        const editForm = document.getElementById('editRoleForm');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                editForm.action = `/role/${id}`; // Sesuain route lu
                
                document.getElementById('edit_nama').value = this.getAttribute('data-nama');
                document.getElementById('edit_deskripsi').value = this.getAttribute('data-deskripsi');
                document.getElementById('edit_status').value = this.getAttribute('data-status');

                // 🔥 LOGIKA AUTO-CENTANG PERMISSION
                const attachedPerms = JSON.parse(this.getAttribute('data-permissions') || '[]');
                
                const permCheckboxes = document.querySelectorAll('.edit-perm-checkbox');
                permCheckboxes.forEach(cb => {
                    // Kalau ID checkbox ada di array, centang!
                    cb.checked = attachedPerms.includes(parseInt(cb.value));
                });

                editModal.show();
            });
        });
    });
</script>
@endsection