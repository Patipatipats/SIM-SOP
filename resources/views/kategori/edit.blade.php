@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex align-items-center">
                <a href="{{ route('kategori.index') }}" class="btn btn-outline-secondary me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h4 class="fw-bold mb-0">Edit Kategori SOP</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    {{-- Action diarahkan ke route update dengan parameter ID --}}
                    <form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
                        @csrf
                        {{-- Method PUT wajib untuk proses update di Laravel --}}
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_kategori') is-invalid @enderror" 
                                   id="nama_kategori" name="nama_kategori" 
                                   value="{{ old('nama_kategori', $kategori->nama_kategori) }}" 
                                   placeholder="Contoh: Prosedur Akademik" required>
                            @error('nama_kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label fw-semibold">Slug (Otomatis)</label>
                            <input type="text" class="form-control bg-light" id="slug" name="slug" 
                                   value="{{ old('slug', $kategori->slug) }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" name="deskripsi" rows="4" 
                                      placeholder="Jelaskan singkat tentang kategori ini...">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold d-block">Status Kategori</label>
                            <div class="form-check form-switch">
                                {{-- Logika checked berdasarkan status di database --}}
                                <input class="form-check-input" type="checkbox" id="status" name="status" value="1" 
                                    {{ $kategori->status ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">Aktif</label>
                            </div>
                            <small class="text-muted">Kategori yang tidak aktif tidak akan muncul saat pembuatan SOP baru.</small>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('kategori.index') }}" class="btn btn-light me-2">Batal</a>
                            <button type="submit" class="btn btn-warning px-4">
                                <i class="fas fa-save me-2"></i> Perbarui Kategori
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 bg-light shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold"><i class="fas fa-info-circle me-2 text-primary"></i> Informasi Edit</h5>
                    <p class="small text-muted mb-0">
                        Mengubah nama kategori akan secara otomatis memperbarui slug. Pastikan perubahan ini tidak merusak link referensi yang sudah ada jika sistem sudah berjalan lama.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const nama = document.querySelector('#nama_kategori');
    const slug = document.querySelector('#slug');

    nama.addEventListener('keyup', function() {
        let preslug = nama.value;
        preslug = preslug.replace(/ /g,"-");
        slug.value = preslug.toLowerCase();
    });
</script>
@endsection