@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="row mb-4">
        <div class="col-md-12 text-dark">
            <div class="d-flex align-items-center">
                <a href="{{ route('sop.index') }}" class="btn btn-outline-secondary me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h4 class="fw-bold mb-0">Tambah Dokumen SOP</h4>
            </div>
        </div>
    </div>

    <form action="{{ route('sop.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4 text-dark">
                        <div class="mb-3">
                            <label for="judul" class="form-label fw-semibold">Judul SOP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                                   id="judul" name="judul" value="{{ old('judul') }}" 
                                   placeholder="Masukkan judul lengkap dokumen..." required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Kode SOP</label>
                                <input type="text" class="form-control bg-light" value="Otomatis oleh Sistem" readonly>
                                <small class="text-muted small">Format: SOP/[UNIT]/[TAHUN]/[NOMOR]</small>
                            </div>
    
                            <div class="col-md-6 mb-3">
                                <label for="tag_sop" class="form-label fw-semibold">Tag SOP <small class="text-muted">(Opsional)</small></label>
                                <input type="text" class="form-control" id="tag_sop" name="tag_sop" value="{{ old('tag_sop') }}"
                                       placeholder="akademik, pendaftaran, dsb.">
                            </div>
                        </div>

                        <div class="mb-0">
                            <label for="deskripsi" class="form-label fw-semibold">Deskripsi Singkat</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" 
                                      placeholder="Jelaskan singkat isi dokumen...">{{ old('deskripsi') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4 text-dark">
                        <div class="mb-3">
                            <label for="kategori_id" class="form-label fw-semibold">Kategori SOP <span class="text-danger">*</span></label>
                            <select class="form-select @error('kategori_id') is-invalid @enderror" name="kategori_id" required>
                                <option value="" selected disabled>-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('kategori_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <div class="invalid-feedback text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="unit_kerja_id" class="form-label fw-semibold">Unit Kerja <span class="text-danger">*</span></label>
                            <select class="form-select @error('unit_kerja_id') is-invalid @enderror" name="unit_kerja_id" required>
                                <option value="" selected disabled>-- Pilih Unit Pemilik --</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ old('unit_kerja_id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->nama_unit }} ({{ $unit->unit_singkatan }})
                                    </option>
                                @endforeach
                            </select>
                            @error('unit_kerja_id')
                                <div class="invalid-feedback text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label fw-semibold d-block text-muted small">Informasi Sistem</label>
                            <div class="d-flex gap-2">
                                <span class="badge bg-warning text-dark px-3">Draft</span>
                                <span class="badge bg-success px-3 text-white">Active</span>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary shadow-sm">
                                <i class="fas fa-save me-2"></i> Simpan SOP
                            </button>
                            <a href="{{ route('sop.index') }}" class="btn btn-light mt-2 border">Batal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection