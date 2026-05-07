@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Unit Kerja</h1>
        <a href="{{ route('unit-kerja.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Lengkap: {{ $unit->nama_unit }}</h6>
            <span class="badge {{ $unit->status_unit ? 'bg-success' : 'bg-danger' }} px-3 py-2">
                {{ $unit->status_unit ? 'Aktif' : 'Non-aktif' }}
            </span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 border-right">
                    <h5 class="text-muted mb-3 font-weight-bold">Data Utama</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th width="180">Nama Unit</th>
                            <td>: {{ $unit->nama_unit }}</td>
                        </tr>
                        <tr>
                            <th>Singkatan</th>
                            <td>: <span class="badge bg-light text-dark border">{{ $unit->unit_singkatan }}</span></td>
                        </tr>
                        <tr>
                            <th>Tipe Unit</th>
                            <td>: {{ $unit->tipe_unit }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h5 class="text-muted mb-3 font-weight-bold">Statistik & Riwayat</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th width="180">Jumlah SOP Terdaftar</th>
                            <td>: <span class="badge bg-primary text-white font-weight-bold">{{ $unit->sops_count ?? 0 }} SOP</span></td>
                        </tr>
                        <tr>
                            <th>Dibuat Pada</th>
                            <td>: {{ $unit->created_at->format('d F Y H:i') }} ({{ $unit->created_at->diffForHumans() }})</td>
                        </tr>
                        <tr>
                            <th>Dibuat Oleh</th>
                            <td>: {{ $unit->creator->name ?? 'System / Seeder' }}</td>
                        </tr>
                        <tr>
                            <th>Terakhir Diubah</th>
                            <td>: {{ $unit->updated_at->format('d F Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr>

            <div class="mt-3">
                <a href="{{ route('unit-kerja.edit', $unit->id) }}" class="btn btn-warning text-dark">
                    <i class="fas fa-edit"></i> Edit Unit Kerja
                </a>
                
                <a href="{{ route('unit-kerja.index') }}" class="btn btn-outline-primary ms-2">
                    <i class="fas fa-list"></i> Lihat Daftar SOP Unit Ini
                </a>
            </div>
        </div>
    </div>
</div>
@endsection