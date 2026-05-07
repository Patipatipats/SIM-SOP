@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Tambah Unit Kerja</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('unit_kerja.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Unit Kerja</label>
                    <input type="text" name="nama_unit" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Singkatan</label>
                    <input type="text" name="unit_singkatan" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipe Unit</label>
                    <select name="tipe_unit" class="form-select" required>
                        <option value="Pusat">Pusat</option>
                        <option value="Fakultas">Fakultas</option>
                        <option value="Prodi">Prodi</option>
                        <option value="Lembaga">Lembaga</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status_unit" class="form-select">
                        <option value="1">Aktif</option>
                        <option value="0">Non-aktif</option>
                    </select>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                    <a href="{{ route('unit_kerja.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection