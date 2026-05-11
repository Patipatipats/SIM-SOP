@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">Master Data - Unit Kerja</h4>
        {{-- Tombol ngebuka Modal Create --}}
        <button type="button" class="btn btn-primary shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#createUnitModal">
            <i class="fas fa-plus me-2"></i> Tambah Unit Kerja
        </button>
    </div>

    <div class="card border-0 shadow-sm bg-body">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="small text-uppercase fw-bold text-dark">
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th>Nama Unit</th>
                            <th>Singkatan</th>
                            <th>Tipe Unit</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Jumlah SOP</th>
                            <th>Dibuat Pada</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($unit as $index => $u)
                        <tr class="text-dark">
                            <td class="ps-4 text-muted">{{ $unit->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $u->nama_unit }}</div>
                            </td>
                            <td><span class="badge bg-light text-primary border">{{ $u->unit_singkatan }}</span></td>
                            <td>{{ $u->tipe_unit }}</td> 
                            <td class="text-center">
                                @if($u->status_unit == 1)
                                    <span class="badge rounded-pill bg-success px-3">Aktif</span>
                                @else
                                    <span class="badge rounded-pill bg-danger px-3">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info text-dark px-2">{{ $u->sops_count ?? 0 }}</span>
                            </td>
                            <td>{{ $u->created_at->format('d/m/Y') }}</td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    {{-- TOMBOL EDIT (Pake Data-Attributes) --}}
                                    <button type="button" class="btn btn-sm btn-outline-warning btn-edit-unit" 
                                        data-id="{{ $u->id }}"
                                        data-nama="{{ $u->nama_unit }}"
                                        data-singkatan="{{ $u->unit_singkatan }}"
                                        data-tipe="{{ $u->tipe_unit }}"
                                        data-status="{{ $u->status_unit }}"
                                        title="Edit">
                                        <i class="fas fa-edit text-dark"></i>
                                    </button>

                                    <form action="{{ route('unit_kerja.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus unit ini jing?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">Belum ada data unit kerja.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($unit->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $unit->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ========================================== --}}
{{-- 1. MODAL TAMBAH UNIT KERJA                --}}
{{-- ========================================== --}}
<div class="modal fade" id="createUnitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-building me-2"></i> Tambah Unit Kerja</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('unit_kerja.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nama Unit Kerja</label>
                        <input type="text" name="nama_unit" class="form-control" placeholder="Contoh: Biro Sistem Informasi" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Singkatan</label>
                            <input type="text" name="unit_singkatan" class="form-control" placeholder="Contoh: BSI" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Tipe Unit</label>
                            <select name="tipe_unit" class="form-select" required>
                                <option value="UNIVERSITAS">UNIVERSITAS</option>
                                <option value="FAKULTAS">FAKULTAS</option>
                                <option value="PRODI">PRODI</option>
                                <option value="LEMBAGA">LEMBAGA</option>
                                <option value="BIRO">BIRO</option>
                                <option value="UPT">UPT</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">Simpan Unit</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ========================================== --}}
{{-- 2. MODAL EDIT UNIT KERJA (Dynamic)         --}}
{{-- ========================================== --}}
<div class="modal fade" id="editUnitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-edit me-2"></i> Edit Unit Kerja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUnitForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nama Unit Kerja</label>
                        <input type="text" id="edit_nama_unit" name="nama_unit" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Singkatan</label>
                            <input type="text" id="edit_unit_singkatan" name="unit_singkatan" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Status Unit</label>
                            <select id="edit_status_unit" name="status_unit" class="form-select" required>
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold small text-muted text-uppercase">Tipe Unit</label>
                        <select id="edit_tipe_unit" name="tipe_unit" class="form-select" required>
                            <option value="UNIVERSITAS">UNIVERSITAS</option>
                            <option value="FAKULTAS">FAKULTAS</option>
                            <option value="PRODI">PRODI</option>
                            <option value="LEMBAGA">LEMBAGA</option>
                            <option value="BIRO">BIRO</option>
                            <option value="UPT">UPT</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-bold px-4">Update Unit</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT BUAT NGATUR ISI MODAL EDIT UNIT --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.btn-edit-unit');
        const editModal = new bootstrap.Modal(document.getElementById('editUnitModal'));
        const editForm = document.getElementById('editUnitForm');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                
                // 🔥 PERBAIKAN: Gunakan format URL yang sesuai dengan route resource Laravel (unit_kerja)
                // Jika di routes/web.php lu pake Route::resource('unit_kerja', ...), maka:
                editForm.action = `/unit_kerja/${id}`; 
                
                // Suntik data ke input modal
                document.getElementById('edit_nama_unit').value = this.getAttribute('data-nama');
                document.getElementById('edit_unit_singkatan').value = this.getAttribute('data-singkatan');
                document.getElementById('edit_tipe_unit').value = this.getAttribute('data-tipe');
                document.getElementById('edit_status_unit').value = this.getAttribute('data-status');

                editModal.show();
            });
        });
    });
</script>
@endsection