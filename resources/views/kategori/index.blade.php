@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">Master Data - Kategori SOP</h4>
        {{-- Tombol ngebuka Modal Create --}}
        <button type="button" class="btn btn-primary shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#createKategoriModal">
            <i class="fas fa-plus me-2"></i> Tambah Kategori
        </button>
    </div>

    <div class="card border-0 shadow-sm bg-body">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="small text-uppercase fw-bold text-dark">
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th>Nama Kategori</th>
                            <th>Slug</th>
                            <th>Deskripsi</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Jumlah SOP</th>
                            <th>Dibuat Pada</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategori as $index => $item)
                        <tr class="text-dark">
                            <td class="ps-4 text-muted">{{ $kategori->firstItem() + $index }}</td>
                            <td class="fw-bold">
                                {{ $item->nama_kategori }}
                            </td>
                            <td><code class="text-primary">{{ $item->slug }}</code></td>
                            <td class="text-muted small">{{ Str::limit($item->deskripsi, 40) ?? '-' }}</td>
                            <td class="text-center">
                                @if($item->status)
                                    <span class="badge rounded-pill bg-success px-3">Aktif</span>
                                @else
                                    <span class="badge rounded-pill bg-danger px-3">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info text-dark px-2">{{ $item->sops_count ?? 0 }}</span>
                            </td>
                            <td>{{ $item->created_at->format('d/m/Y') }}</td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    {{-- TOMBOL EDIT (Pake Data-Attributes) --}}
                                    <button type="button" class="btn btn-sm btn-outline-warning btn-edit-kategori" 
                                        data-id="{{ $item->id }}"
                                        data-nama="{{ $item->nama_kategori }}"
                                        data-deskripsi="{{ $item->deskripsi }}"
                                        data-status="{{ $item->status }}"
                                        title="Edit">
                                        <i class="fas fa-edit text-dark"></i>
                                    </button>

                                    <form action="{{ route('kategori.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini jing?')">
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
                            <td colspan="8" class="text-center py-5 text-muted">Belum ada data kategori.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($kategori->hasPages())
        <div class="card-footer bg-white border-top-0 py-3">
            {{ $kategori->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ========================================== --}}
{{-- 1. MODAL TAMBAH KATEGORI                  --}}
{{-- ========================================== --}}
<div class="modal fade" id="createKategoriModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-tags me-2"></i> Tambah Kategori Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Operasional, Keuangan, dll" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold small text-muted text-uppercase">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Penjelasan singkat kategori ini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ========================================== --}}
{{-- 2. MODAL EDIT KATEGORI (Dynamic)          --}}
{{-- ========================================== --}}
<div class="modal fade" id="editKategoriModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-edit me-2"></i> Edit Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editKategoriForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nama Kategori</label>
                        <input type="text" id="edit_nama_kategori" name="nama_kategori" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Status Kategori</label>
                        <select id="edit_status" name="status" class="form-select" required>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold small text-muted text-uppercase">Deskripsi</label>
                        <textarea id="edit_deskripsi" name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-bold px-4">Update Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT BUAT NGATUR ISI MODAL EDIT KATEGORI --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.btn-edit-kategori');
        const editModal = new bootstrap.Modal(document.getElementById('editKategoriModal'));
        const editForm = document.getElementById('editKategoriForm');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                
                // 🔥 Sesuaikan URL Action ke route kategori.update
                editForm.action = `/kategori/${id}`; 
                
                // Suntik data ke input modal
                document.getElementById('edit_nama_kategori').value = this.getAttribute('data-nama');
                document.getElementById('edit_deskripsi').value = this.getAttribute('data-deskripsi');
                document.getElementById('edit_status').value = this.getAttribute('data-status');

                editModal.show();
            });
        });
    });
</script>
@endsection