@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Unit Kerja</h1>
        <a href="{{ route('unit-kerja.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Unit Kerja Baru</h6>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('unit-kerja.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label font-weight-bold">Nama Lengkap Unit <span class="text-danger">*</span></label>
                    <input type="text" name="nama_unit" class="form-control @error('nama_unit') is-invalid @enderror" value="{{ old('nama_unit') }}" placeholder="Contoh: Fakultas Teknik" required>
                </div>

                <div class="mb-3">
                    <label class="form-label font-weight-bold">Singkatan Unit <span class="text-danger">*</span></label>
                    <input type="text" name="unit_singkatan" class="form-control @error('unit_singkatan') is-invalid @enderror" value="{{ old('unit_singkatan') }}" placeholder="Contoh: FT" required>
                </div>

                <div class="mb-3">
                    <label class="form-label font-weight-bold">Tipe Unit <span class="text-danger">*</span></label>
                    <select name="tipe_unit" class="form-select @error('tipe_unit') is-invalid @enderror" required>
                        <option value="" disabled selected>-- Pilih Tipe Unit --</option>
                        <option value="Pusat" {{ old('tipe_unit') == 'Pusat' ? 'selected' : '' }}>Pusat</option>
                        <option value="Fakultas" {{ old('tipe_unit') == 'Fakultas' ? 'selected' : '' }}>Fakultas</option>
                        <option value="Prodi" {{ old('tipe_unit') == 'Prodi' ? 'selected' : '' }}>Prodi</option>
                        <option value="Lembaga" {{ old('tipe_unit') == 'Lembaga' ? 'selected' : '' }}>Lembaga</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label font-weight-bold">Status Unit <span class="text-danger">*</span></label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="status_unit" value="1" id="statusUnitSwitch" checked>
                        <label class="form-check-label" for="statusUnitSwitch">Aktif</label>
                    </div>
                </div>

                <div class="mt-4 border-top pt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Data
                    </button>
                    <a href="{{ route('unit-kerja.index') }}" class="btn btn-light">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection