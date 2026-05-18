@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">Manajemen Temp Password</h4>
        <div class="text-muted small">Kelola dan hasilkan password sementara untuk pengguna</div>
    </div>

    {{-- ALERT BUAT NAMPIILIN PASSWORD SEMENTARA YANG BARU DIGENERATE --}}
    @if(session('temp_password'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center" role="alert">
        <i class="fas fa-check-circle fs-4 me-3"></i>
        <div>
            <strong>Berhasil!</strong> Password sementara untuk <strong>{{ session('user_nama') }}</strong> adalah: 
            <span class="badge bg-white text-success border border-success fs-6 mx-2" id="newTempPassword">{{ session('temp_password') }}</span>
            <button class="btn btn-sm btn-outline-success ms-2" onclick="copyPassword()" title="Copy ke Clipboard">
                <i class="fas fa-copy"></i> Copy
            </button>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- TABEL DATA PENGGUNA UNTUK RESET --}}
    <div class="card border-0 shadow-sm bg-body mb-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="small text-uppercase fw-bold text-dark">
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th>Nama Pengguna</th>
                            <th>Username / Email</th>
                            <th>Role</th>
                            <th class="text-center">Aksi (Generate)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $u)
                        <tr class="text-dark">
                            <td class="ps-4 text-muted">{{ $users->firstItem() + $index }}</td>
                            <td class="fw-bold text-primary">{{ $u->nama }}</td>
                            <td>
                                <div class="text-dark"><i class="fas fa-at text-muted me-1 small"></i>{{ $u->username }}</div>
                                <div class="small text-muted"><i class="fas fa-envelope me-1"></i>{{ $u->email }}</div>
                            </td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle">
                                    {{ $u->role->nama_role ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger fw-bold shadow-sm btn-generate" 
                                    data-id="{{ $u->id }}" 
                                    data-nama="{{ $u->nama }}"
                                    title="Generate Temp Password">
                                    <i class="fas fa-key me-1"></i> Generate Temp Pwd
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada data Pengguna.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-between align-items-center px-2">
        <div class="small text-muted">Menampilkan <strong>{{ $users->firstItem() ?? 0 }}</strong> - <strong>{{ $users->lastItem() ?? 0 }}</strong> dari <strong>{{ $users->total() }}</strong> pengguna</div>
        <div class="pagination-sm">{{ $users->links() }}</div>
    </div>
</div>

{{-- ========================================== --}}
{{-- MODAL KONFIRMASI GENERATE PASSWORD         --}}
{{-- ========================================== --}}
<div class="modal fade" id="generateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle me-2"></i> Konfirmasi Reset Password</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="generateForm" method="POST">
                @csrf
                <div class="modal-body p-4 text-center">
                    <i class="fas fa-user-shield text-danger mb-3" style="font-size: 3rem;"></i>
                    <h6 class="fw-bold text-dark mb-2">Generate Password Sementara?</h6>
                    <p class="text-muted small mb-0">Anda akan membuat password acak sementara untuk pengguna <br><strong id="nama_user_teks" class="text-primary"></strong>.</p>
                    <p class="text-muted small mt-2">Password lama mereka tidak akan bisa digunakan lagi.</p>
                </div>
                <div class="modal-footer bg-light justify-content-center">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger fw-bold px-4">Ya, Generate Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const generateButtons = document.querySelectorAll('.btn-generate');
        const generateModal = new bootstrap.Modal(document.getElementById('generateModal'));
        const generateForm = document.getElementById('generateForm');

        generateButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                
                // Set action form ke route generate
                generateForm.action = `/temp-password/generate/${id}`; 
                document.getElementById('nama_user_teks').innerText = nama;

                generateModal.show();
            });
        });
    });

    // Fungsi copy to clipboard ala pro
    function copyPassword() {
        const passwordText = document.getElementById('newTempPassword').innerText;
        navigator.clipboard.writeText(passwordText).then(() => {
            alert('Password berhasil di-copy ke clipboard! Tinggal paste ke WA/Email orangnya.');
        }).catch(err => {
            console.error('Gagal copy: ', err);
        });
    }
</script>

<style>
    .pagination { margin-bottom: 0; }
    .page-link { padding: 0.25rem 0.75rem; font-size: 0.875rem; border-radius: 6px; margin: 0 2px; }
</style>
@endsection