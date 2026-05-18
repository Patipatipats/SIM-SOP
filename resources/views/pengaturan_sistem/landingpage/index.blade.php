@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-0">Pengaturan Landing Page</h4>
            <div class="text-muted small">Kelola konten, banner, dan informasi publik di halaman utama.</div>
        </div>
        <button type="button" class="btn btn-primary shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#createLandingModal">
            <i class="fas fa-plus-circle me-2"></i> Tambah Konten
        </button>
    </div>

    {{-- TABEL DATA KONTEN LANDING PAGE --}}
    <div class="card border-0 shadow-sm bg-body mb-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="small text-uppercase fw-bold text-dark">
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th>Judul & Konten</th>
                            <th>Tipe & Urutan</th>
                            <th>Status</th>
                            <th>Dibuat Oleh/Pada</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($landingPages as $index => $lp)
                        <tr class="text-dark">
                            <td class="ps-4 text-muted">{{ $landingPages->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-bold text-primary">{{ $lp->judul }}</div>
                                <div class="small text-muted mb-1"><i class="fas fa-link me-1"></i>/{{ $lp->slug }}</div>
                                <div class="small text-secondary text-truncate" style="max-width: 250px;">
                                    {{ strip_tags($lp->konten) ?? 'Tidak ada konten' }}
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-dark bg-opacity-10 text-dark border border-dark-subtle mb-1 text-uppercase">{{ $lp->tipe }}</span>
                                <div class="small fw-bold text-muted"><i class="fas fa-sort-numeric-down me-1"></i>Urutan: {{ $lp->urutan }}</div>
                            </td>
                            <td>
                                {{-- 🔥 Pakai $lp->aktif --}}
                                @if($lp->aktif == 1)
                                    <span class="badge rounded-pill bg-success px-3">Aktif</span>
                                @else
                                    <span class="badge rounded-pill bg-danger px-3">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="small fw-semibold"><i class="fas fa-user-circle text-muted me-1"></i>{{ $lp->nama_pembuat ?? 'Sistem' }}</div>
                                <div class="small text-muted"><i class="far fa-calendar-alt me-1"></i>{{ \Carbon\Carbon::parse($lp->created_at)->format('d M Y') }}</div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <form action="{{ route('pengaturan-landing.toggle', $lp->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-{{ $lp->aktif == 1 ? 'secondary' : 'success' }}" title="{{ $lp->aktif == 1 ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="fas fa-power-off"></i>
                                        </button>
                                    </form>

                                    <button type="button" class="btn btn-sm btn-outline-warning btn-edit-lp" 
                                        data-id="{{ $lp->id }}"
                                        data-judul="{{ $lp->judul }}"
                                        data-slug="{{ $lp->slug }}"
                                        data-konten="{{ $lp->konten }}"
                                        data-tipe="{{ $lp->tipe }}"
                                        data-urutan="{{ $lp->urutan }}"
                                        data-aktif="{{ $lp->aktif }}"
                                        title="Edit Konten">
                                        <i class="fas fa-edit text-dark"></i>
                                    </button>

                                    <form action="{{ route('pengaturan-landing.destroy', $lp->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus konten landing page ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada konten Landing Page.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-between align-items-center px-2">
        <div class="small text-muted">Menampilkan <strong>{{ $landingPages->firstItem() ?? 0 }}</strong> - <strong>{{ $landingPages->lastItem() ?? 0 }}</strong> dari <strong>{{ $landingPages->total() }}</strong> konten</div>
        <div class="pagination-sm">{{ $landingPages->links() }}</div>
    </div>
</div>

{{-- ========================================== --}}
{{-- 1. MODAL TAMBAH KONTEN                     --}}
{{-- ========================================== --}}
<div class="modal fade" id="createLandingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle me-2"></i> Tambah Konten Landing Page</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pengaturan-landing.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">Judul Konten *</label>
                            <input type="text" name="judul" id="create_judul" class="form-control" placeholder="Contoh: Selamat Datang di SIM SOP" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">Slug (URL) <span class="fw-normal fst-italic">- Auto Generate</span></label>
                            <input type="text" name="slug" id="create_slug" class="form-control" placeholder="selamat-datang-di-sim-sop" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small text-muted">Tipe Konten *</label>
                            <select name="tipe" class="form-select" required>
                                <option value="banner">Banner / Hero</option>
                                <option value="informasi">Informasi Utama</option>
                                <option value="panduan">Panduan Ringkas</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small text-muted">Urutan Tampil *</label>
                            <input type="number" name="urutan" class="form-control" value="1" min="1" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small text-muted">Status *</label>
                            <select name="aktif" class="form-select" required>
                                <option value="1">Aktif (Tampilkan)</option>
                                <option value="0">Nonaktif (Sembunyikan)</option>
                            </select>
                        </div>
                        <div class="col-12 mb-0">
                            <label class="form-label fw-bold small text-muted">Isi Konten (Teks / HTML)</label>
                            <textarea name="konten" class="form-control" rows="5" placeholder="Tulis deskripsi konten di sini..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">Simpan Konten</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ========================================== --}}
{{-- 2. MODAL EDIT KONTEN                       --}}
{{-- ========================================== --}}
<div class="modal fade" id="editLandingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-edit me-2"></i> Edit Konten Landing Page</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editLandingForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">Judul Konten</label>
                            <input type="text" id="edit_judul" name="judul" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">Slug (URL)</label>
                            <input type="text" id="edit_slug" name="slug" class="form-control" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small text-muted">Tipe Konten</label>
                            <select id="edit_tipe" name="tipe" class="form-select" required>
                                <option value="banner">Banner / Hero</option>
                                <option value="informasi">Informasi Utama</option>
                                <option value="panduan">Panduan Ringkas</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small text-muted">Urutan</label>
                            <input type="number" id="edit_urutan" name="urutan" class="form-control" min="1" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small text-muted">Status</label>
                            <select id="edit_aktif" name="aktif" class="form-select" required>
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-12 mb-0">
                            <label class="form-label fw-bold small text-muted">Isi Konten</label>
                            <textarea id="edit_konten" name="konten" class="form-control" rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-bold px-4">Update Konten</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const createJudul = document.getElementById('create_judul');
        const createSlug = document.getElementById('create_slug');
        
        createJudul.addEventListener('keyup', function() {
            let text = this.value;
            createSlug.value = text.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
        });

        const editButtons = document.querySelectorAll('.btn-edit-lp');
        const editModal = new bootstrap.Modal(document.getElementById('editLandingModal'));
        const editForm = document.getElementById('editLandingForm');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                editForm.action = `/pengaturan-landing/${id}`; 
                
                document.getElementById('edit_judul').value = this.getAttribute('data-judul');
                document.getElementById('edit_slug').value = this.getAttribute('data-slug');
                document.getElementById('edit_konten').value = this.getAttribute('data-konten');
                document.getElementById('edit_tipe').value = this.getAttribute('data-tipe');
                document.getElementById('edit_urutan').value = this.getAttribute('data-urutan');
                document.getElementById('edit_aktif').value = this.getAttribute('data-aktif');

                editModal.show();
            });
        });

        const editJudul = document.getElementById('edit_judul');
        const editSlug = document.getElementById('edit_slug');
        editJudul.addEventListener('keyup', function() {
            let text = this.value;
            editSlug.value = text.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
        });
    });
</script>

<style>
    .pagination { margin-bottom: 0; }
    .page-link { padding: 0.25rem 0.75rem; font-size: 0.875rem; border-radius: 6px; margin: 0 2px; }
</style>
@endsection