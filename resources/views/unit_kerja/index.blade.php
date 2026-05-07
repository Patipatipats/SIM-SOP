@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Master Unit Kerja</h1>
        <a href="{{ route('unit_kerja.create') }}" class="btn btn-primary">Tambah Unit Kerja</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Unit</th>
                            <th>Singkatan</th>
                            <th>Tipe</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($units as $unit)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $unit->nama_unit }}</td>
                            <td>{{ $unit->unit_singkatan }}</td>
                            <td>{{ $unit->tipe_unit }}</td>
                            <td>
                                <span class="badge {{ $unit->status_unit ? 'bg-success' : 'bg-danger' }}">
                                    {{ $unit->status_unit ? 'Aktif' : 'Non-aktif' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('unit_kerja.show', $unit->id) }}" class="btn btn-sm btn-info text-white">Detail</a>
                                <a href="{{ route('unit_kerja.edit', $unit->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('unit_kerja.destroy', $unit->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection