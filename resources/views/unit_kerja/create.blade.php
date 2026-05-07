@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex align-items-center">
                <a href="{{ route('unit_kerja.index') }}" class="btn btn-outline-secondary me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h4 class="fw-bold mb-0">Tambah Unit Kerja</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('unit_kerja.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="nama_unit" class="form-label fw-semibold">Nama Unit Kerja <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_unit') is-invalid @enderror" 
                                   id="nama_unit" name="nama_unit" value="{{ old('nama_unit') }}" 
                                   placeholder="Contoh: Fakultas Teknik" required>
                            @error('nama_unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="singkatan" class="form-label fw-semibold">Singkatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('unit_singkatan') is-invalid @enderror" 
                                       id="singkatan" name="unit_singkatan" value="{{ old('unit_singkatan') }}" 
                                       placeholder="Contoh: FT" required>
                                @error('unit_singkatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tipe_unit" class="form-label fw-semibold">Tipe Unit <span class="text-danger">*</span></label>
                                <select class="form-select @error('tipe_unit') is-invalid @enderror" id="tipe_unit" name="tipe_unit" required>
                                    <option value="" selected disabled>Pilih Tipe...</option>
                                    <option value="Universitas" {{ old('tipe_unit') == 'Universitas' ? 'selected' : '' }}>Universitas</option>
                                    <option value="Fakultas" {{ old('tipe_unit') == 'Fakultas' ? 'selected' : '' }}>Fakultas</option>
                                    <option value="Prodi" {{ old('tipe_unit') == 'Prodi' ? 'selected' : '' }}>Program Studi</option>
                                    <option value="Biro" {{ old('tipe_unit') == 'Biro' ? 'selected' : '' }}>Biro / Bagian</option>
                                    <option value="Lembaga" {{ old('tipe_unit') == 'Lembaga' ? 'selected' : '' }}>Lembaga / UPT</option>
                                </select>
                                @error('tipe_unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold d-block">Status Unit</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="status" name="status" value="1" checked>
                                <label class="form-check-label" for="status">Aktif</label>
                            </div>
                            <small class="text-muted">Unit yang tidak aktif tidak dapat dipilih sebagai pemilik dokumen SOP baru.</small>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end">
                            <button type="reset" class="btn btn-light me-2">Batal</button>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i> Simpan Unit Kerja
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 bg-light shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold"><i class="fas fa-info-circle me-2 text-primary"></i> Informasi Unit</h5>
                    <ul class="small text-muted ps-3 mb-0">
                        <li><strong>Nama Unit:</strong> Masukkan nama resmi instansi/unit.</li>
                        <li class="mt-2"><strong>Singkatan:</strong> Digunakan untuk identitas singkat pada tabel dan kode dokumen.</li>
                        <li class="mt-2"><strong>Tipe Unit:</strong> Menentukan level hirarki unit kerja dalam organisasi.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection