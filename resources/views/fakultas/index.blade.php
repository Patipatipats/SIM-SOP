@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">Master Data - Fakultas</h4>
        {{-- Tombol Tambah Fakultas (Trigger Modal) --}}
        <button type="button" class="btn btn-primary shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#createFakultasModal">
            <i class="fas fa-plus me-2"></i> Tambah Fakultas
        </button>
    </div>

    <div class="card border-0 shadow-sm bg-body">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="small text-uppercase fw-bold text-dark">
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th>Nama Fakultas</th>
                            <th>Singkatan</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Jumlah Program Studi</th>
                            <th>Dibuat Pada</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fakultas as $index => $f)
                        <tr class="text-dark">
                            <td class="ps-4 text-muted">{{ $fakultas->firstItem() + $index }}</td>
                            <td class="fw-bold">
                                <a href="{{ route('fakultas.show', $f->id) }}" class="text-decoration-none text-dark">{{ $f->nama }}</a>
                            </td>
                            <td><span class="badge bg-light text-primary border">{{ $f->singkatan }}</span></td>
                            <td class="text-center">
                                {{-- Status: 1 = Aktif, 0 = Nonaktif --}}
                                @if(isset($f->status) && $f->status == 1)
                                    <span class="badge rounded-pill bg-success px-3">Aktif</span>
                                @else
                                    <span class="badge rounded-pill bg-danger px-3">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{-- Menampilkan Jumlah Prodi (Gua asumsikan di DB lu ada kolom atau relasi prodi_count) --}}
                                <span class="badge bg-info text-dark px-2">{{ $f->prodi_count ?? 0 }} Prodi</span>
                            </td>
                            <td>{{ $f->created_at ? \Carbon\Carbon::parse($f->created_at)->format('d/m/Y') : '-' }}</td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    {{-- Tombol Edit (Kirim data ke Modal) --}}
                                    <button type="button" class="btn btn-sm btn-outline-warning btn-edit-fakultas" 
                                        data-id="{{ $f->id }}"
                                        data-nama="{{ $f->nama }}"
                                        data-singkatan="{{ $f->singkatan }}"
                                        data-status="{{ $f->status ?? 1 }}"
                                        title="Edit Fakultas">
                                        <i class="fas fa-edit text-dark"></i>
                                    </button>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('fakultas.destroy', $f->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus fakultas ini jing?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Fakultas">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Belum ada data fakultas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($fakultas->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $fakultas->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ========================================== --}}
{{-- 1. MODAL TAMBAH FAKULTAS                  --}}
{{-- ========================================== --}}
<div class="modal fade" id="createFakultasModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle me-2"></i> Tambah Fakultas</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('fakultas.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nama Fakultas</label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap Fakultas" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Singkatan</label>
                        <input type="text" name="singkatan" class="form-control" placeholder="Contoh: FT, FEB, FISIP" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold small text-muted text-uppercase">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">Simpan Fakultas</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ========================================== --}}
{{-- 2. MODAL EDIT FAKULTAS (Dynamic)           --}}
{{-- ========================================== --}}
<div class="modal fade" id="editFakultasModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-edit me-2"></i> Edit Fakultas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editFakultasForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nama Fakultas</label>
                        <input type="text" id="edit_nama" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Singkatan</label>
                        <input type="text" id="edit_singkatan" name="singkatan" class="form-control" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold small text-muted text-uppercase">Status</label>
                        <select id="edit_status" name="status" class="form-select" required>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-bold px-4">Update Fakultas</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT JAVASCRIPT UNTUK MODAL EDIT --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.btn-edit-fakultas');
        const editModal = new bootstrap.Modal(document.getElementById('editFakultasModal'));
        const editForm = document.getElementById('editFakultasForm');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                editForm.action = `/fakultas/${id}`; 
                
                document.getElementById('edit_nama').value = this.getAttribute('data-nama');
                document.getElementById('edit_singkatan').value = this.getAttribute('data-singkatan');
                document.getElementById('edit_status').value = this.getAttribute('data-status');

                editModal.show();
            });
        });
    });
</script>
@endsection