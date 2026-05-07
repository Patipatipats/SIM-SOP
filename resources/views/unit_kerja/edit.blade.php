@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Unit Kerja</h1>
        <a href="{{ route('unit_kerja.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Edit: {{ $unit->nama_unit }}</h6>
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

            <form action="{{ route('unit_kerja.update', $unit->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label font-weight-bold">Nama Lengkap Unit <span class="text-danger">*</span></label>
                    <input type="text" name="nama_unit" class="form-control @error('nama_unit') is-invalid @enderror" value="{{ old('nama_unit', $unit->nama_unit) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label font-weight-bold">Singkatan Unit <span class="text-danger">*</span></label>
                    <input type="text" name="unit_singkatan" class="form-control @error('unit_singkatan') is-invalid @enderror" value="{{ old('unit_singkatan', $unit->unit_singkatan) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label font-weight-bold">Tipe Unit <span class="text-danger">*</span></label>
                    <select name="tipe_unit" class="form-select @error('tipe_unit') is-invalid @enderror" required>
                        <option value="Pusat" {{ old('tipe_unit', $unit->tipe_unit) == 'Pusat' ? 'selected' : '' }}>Pusat</option>
                        <option value="Fakultas" {{ old('tipe_unit', $unit->tipe_unit) == 'Fakultas' ? 'selected' : '' }}>Fakultas</option>
                        <option value="Prodi" {{ old('tipe_unit', $unit->tipe_unit) == 'Prodi' ? 'selected' : '' }}>Prodi</option>
                        <option value="Lembaga" {{ old('tipe_unit', $unit->tipe_unit) == 'Lembaga' ? 'selected' : '' }}>Lembaga</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label font-weight-bold">Status Unit <span class="text-danger">*</span></label>
                    <div class="form-check form-switch">
                        <input type="hidden" name="status_unit" value="0">
                        <input class="form-check-input" type="checkbox" name="status_unit" value="1" id="statusUnitSwitch" {{ old('status_unit', $unit->status_unit) ? 'checked' : '' }}>
                        <label class="form-check-label" for="statusUnitSwitch">Aktif</label>
                    </div>
                </div>

                <div class="mt-4 border-top pt-3 d-flex justify-content-between align-items-center">
                    <div>
                        <button type="submit" class="btn btn-warning text-dark font-weight-bold">
                            <i class="fas fa-save"></i> Perbarui Data
                        </button>
                        <a href="{{ route('unit_kerja.index') }}" class="btn btn-light">Batal</a>
                    </div>
                    
                    @if($unit->updater)
                        <small class="text-muted">
                            Terakhir diubah oleh: <strong>{{ $unit->updater->name }}</strong> ({{ $unit->updated_at->diffForHumans() }})
                        </small>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection