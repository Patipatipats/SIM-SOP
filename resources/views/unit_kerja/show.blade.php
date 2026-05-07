@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Detail Unit Kerja</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ $unit->nama_unit }} ({{ $unit->unit_singkatan }})</h6>
        </div>
        <div class="card-body">
            <table class="table table-borderless">
                <tr>
                    <th width="200">Tipe Unit</th>
                    <td>: {{ $unit->tipe_unit }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>: 
                        <span class="badge {{ $unit->status_unit ? 'bg-success' : 'bg-danger' }}">
                            {{ $unit->status_unit ? 'Aktif' : 'Non-aktif' }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Dibuat Oleh</th>
                    <td>: {{ $unit->creator->name ?? 'System' }} pada {{ $unit->created_at->format('d M Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Terakhir Update</th>
                    <td>: {{ $unit->updater->name ?? '-' }} pada {{ $unit->updated_at->format('d M Y H:i') }}</td>
                </tr>
            </table>
            
            <hr>
            <div class="mt-3">
                <a href="{{ route('unit_kerja.index') }}" class="btn btn-secondary">Kembali</a>
                <a href="{{ route('unit_kerja.edit', $unit->id) }}" class="btn btn-warning">Edit</a>
            </div>
        </div>
    </div>
</div>
@endsection