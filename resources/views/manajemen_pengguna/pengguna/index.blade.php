@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">User Management</h4>
        <button type="button" class="btn btn-primary shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#createUserModal">
            <i class="fas fa-user-plus me-2"></i> Tambah User Baru
        </button>
    </div>

    {{-- TABEL DATA PENGGUNA --}}
    <div class="card border-0 shadow-sm bg-body mb-3">
        <div class="card-body p-0">
            <div class="table-responsive" style="overflow: visible !important;">
                <table class="table table-hover align-middle mb-0 text-nowrap">
                    <thead class="bg-light">
                        <tr class="small text-uppercase fw-bold text-dark">
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th>Nama & Username</th>
                            <th>Kontak</th>
                            <th>Role & Penempatan</th>
                            <th class="text-center">Status</th>
                            <th>Last Login</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $u)
                        <tr class="text-dark">
                            <td class="ps-4 text-muted">{{ $users->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $u->nama }}</div>
                                <div class="small text-muted"><i class="fas fa-at me-1"></i>{{ $u->username }}</div>
                            </td>
                            <td>
                                <div class="small"><i class="fas fa-envelope text-muted me-1"></i> {{ $u->email }}</div>
                                <div class="small"><i class="fas fa-phone text-muted me-1"></i> {{ $u->no_hp ?? '-' }}</div>
                            </td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle mb-1">{{ $u->role->nama_role ?? 'N/A' }}</span>
                                <div class="small text-muted">
                                    {{ $u->unitKerja->unit_singkatan ?? '-' }} 
                                    {{ $u->fakultas ? '| ' . $u->fakultas->singkatan : '' }}
                                    {{ $u->programStudi ? '| ' . $u->programStudi->singkatan : '' }}
                                </div>
                            </td>
                            <td class="text-center">
                                @if($u->status == 1)
                                    <span class="badge rounded-pill bg-success px-3">Aktif</span>
                                @else
                                    <span class="badge rounded-pill bg-danger px-3">Nonaktif</span>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ $u->last_login ? \Carbon\Carbon::parse($u->last_login)->diffForHumans() : 'Belum pernah' }}</small></td>
                            <td class="text-end pe-4">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown">Aksi</button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2" style="z-index: 1050;">
                                        <li><h6 class="dropdown-header small text-uppercase">Opsi User</h6></li>
                                        <li><a class="dropdown-item rounded py-2" href="{{ route('pengguna.show', $u->id) }}"><i class="fas fa-eye me-2 text-primary"></i> Detail User</a></li>
                                        <li>
                                            <button type="button" class="dropdown-item rounded py-2 btn-edit-user"
                                                data-id="{{ $u->id }}" data-nama="{{ $u->nama }}" data-username="{{ $u->username }}"
                                                data-email="{{ $u->email }}" data-nohp="{{ $u->no_hp }}" data-role="{{ $u->role_id }}"
                                                data-unit="{{ $u->unit_kerja_id }}" data-fakultas="{{ $u->fakultas_id }}"
                                                data-prodi="{{ $u->program_studi_id }}" data-status="{{ $u->status }}">
                                                <i class="fas fa-edit me-2 text-warning"></i> Edit User
                                            </button>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('pengguna.reset_password', $u->id) }}" method="POST" onsubmit="return confirm('Reset password user ini jadi default (12345678)?')">
                                                @csrf
                                                <button type="submit" class="dropdown-item rounded py-2"><i class="fas fa-key me-2 text-dark"></i> Reset Password</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('pengguna.toggle_status', $u->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item rounded py-2">
                                                    <i class="fas fa-power-off me-2 {{ $u->status == 1 ? 'text-danger' : 'text-success' }}"></i> 
                                                    {{ $u->status == 1 ? 'Nonaktifkan User' : 'Aktifkan User' }}
                                                </button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('pengguna.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Hapus user ini permanen jing?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="dropdown-item rounded py-2 text-danger fw-bold"><i class="fas fa-trash me-2"></i> Hapus User</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-5 text-muted">Belum ada data Pengguna.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-between align-items-center px-2">
        <div class="small text-muted">Menampilkan <strong>{{ $users->firstItem() ?? 0 }}</strong> - <strong>{{ $users->lastItem() ?? 0 }}</strong> dari <strong>{{ $users->total() }}</strong> user</div>
        <div class="pagination-sm">{{ $users->links() }}</div>
    </div>
</div>

{{-- ========================================== --}}
{{-- 1. MODAL TAMBAH USER BARU                  --}}
{{-- ========================================== --}}
<div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-plus me-2"></i> Registrasi User Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pengguna.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">1. Informasi Akun</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">Nama Lengkap *</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">Username *</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">Email *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">No HP (Opsional)</label>
                            <input type="text" name="no_hp" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">Password *</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">Konfirmasi Password *</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    <h6 class="fw-bold text-primary mb-3 mt-2 border-bottom pb-2">2. Akses & Penempatan</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">Role *</label>
                            <select name="role_id" class="form-select" required>
                                <option value="">-- Pilih Role --</option>
                                @foreach($roles as $role) <option value="{{ $role->id }}">{{ $role->nama_role }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">Status *</label>
                            <select name="status" class="form-select" required>
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small text-muted">Unit Kerja</label>
                            <select name="unit_kerja_id" class="form-select">
                                <option value="">-- Pilih Unit --</option>
                                @foreach($units as $u) <option value="{{ $u->id }}">{{ $u->unit_singkatan }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small text-muted">Fakultas (Opsional)</label>
                            <select name="fakultas_id" class="form-select">
                                <option value="">-- Pilih Fakultas --</option>
                                @foreach($fakultas as $f) <option value="{{ $f->id }}">{{ $f->singkatan }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small text-muted">Program Studi (Opsional)</label>
                            <select name="program_studi_id" class="form-select">
                                <option value="">-- Pilih Prodi --</option>
                                @foreach($prodi as $p) <option value="{{ $p->id }}">{{ $p->singkatan }}</option> @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ========================================== --}}
{{-- 2. MODAL EDIT USER                         --}}
{{-- ========================================== --}}
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-edit me-2"></i> Edit Data User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUserForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">Nama Lengkap</label>
                            <input type="text" id="edit_nama" name="nama" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">Username</label>
                            <input type="text" id="edit_username" name="username" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">Email</label>
                            <input type="email" id="edit_email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">No HP</label>
                            <input type="text" id="edit_nohp" name="no_hp" class="form-control">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">Role</label>
                            <select id="edit_role" name="role_id" class="form-select" required>
                                @foreach($roles as $role) <option value="{{ $role->id }}">{{ $role->nama_role }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">Status</label>
                            <select id="edit_status" name="status" class="form-select" required>
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small text-muted">Unit Kerja</label>
                            <select id="edit_unit" name="unit_kerja_id" class="form-select">
                                <option value="">-- Pilih --</option>
                                @foreach($units as $u) <option value="{{ $u->id }}">{{ $u->unit_singkatan }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small text-muted">Fakultas</label>
                            <select id="edit_fakultas" name="fakultas_id" class="form-select">
                                <option value="">-- Pilih --</option>
                                @foreach($fakultas as $f) <option value="{{ $f->id }}">{{ $f->singkatan }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small text-muted">Program Studi</label>
                            <select id="edit_prodi" name="program_studi_id" class="form-select">
                                <option value="">-- Pilih --</option>
                                @foreach($prodi as $p) <option value="{{ $p->id }}">{{ $p->singkatan }}</option> @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-bold px-4">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.btn-edit-user');
        const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
        const editForm = document.getElementById('editUserForm');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                // Sesuain sama route resource lu
                editForm.action = `/pengguna/${id}`; 
                
                document.getElementById('edit_nama').value = this.getAttribute('data-nama');
                document.getElementById('edit_username').value = this.getAttribute('data-username');
                document.getElementById('edit_email').value = this.getAttribute('data-email');
                document.getElementById('edit_nohp').value = this.getAttribute('data-nohp');
                document.getElementById('edit_role').value = this.getAttribute('data-role');
                document.getElementById('edit_status').value = this.getAttribute('data-status');
                document.getElementById('edit_unit').value = this.getAttribute('data-unit');
                document.getElementById('edit_fakultas').value = this.getAttribute('data-fakultas');
                document.getElementById('edit_prodi').value = this.getAttribute('data-prodi');

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