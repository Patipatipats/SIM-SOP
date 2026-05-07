@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Unit Kerja</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('unit_kerja.update', $unit->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label">Nama Unit Kerja</label>
                    <input type="text" name="nama_unit" class="form-control" value="{{ $unit->nama_unit }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Singkatan</label>
                    <input type="text" name="unit_singkatan" class="form-control" value="{{ $unit->unit_singkatan }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipe Unit</label>
                    <select name="tipe_unit" class="form-select" required>
                        <option value="Pusat" {{ $unit->tipe_unit == 'Pusat' ? 'selected' : '' }}>Pusat</option>
                        <option value="Fakultas" {{ $unit->tipe_unit == 'Fakultas' ? 'selected' : '' }}>Fakultas</option>
                        <option value="Prodi" {{ $unit->tipe_unit == 'Prodi' ? 'selected' : '' }}>Prodi</option>
                        <option value="Lembaga" {{ $unit->tipe_unit == 'Lembaga' ? 'selected' : '' }}>Lembaga</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status_unit" class="form-select">
                        <option value="1" {{ $unit->status_unit ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !$unit->status_unit ? 'selected' : '' }}>Non-aktif</option>
                    </select>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-warning">Update Data</button>
                    <a href="{{ route('unit_kerja.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection