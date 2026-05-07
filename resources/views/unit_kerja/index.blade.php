@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Master Data - Unit Kerja</h4>
        {{-- Pastikan nama route sesuai dengan yang ada di web.php --}}
        <a href="{{ route('unit_kerja.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Tambah Unit Kerja
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th>Nama Unit</th>
                            <th>Singkatan</th>
                            <th>Tipe Unit</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Jumlah SOP</th>
                            <th>Dibuat Pada</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- PERBAIKAN: Gunakan variabel $u untuk item agar tidak bentrok dengan $unit --}}
                        @forelse($unit as $index => $u)
                        <tr>
                            {{-- Gunakan variabel koleksi ($unit) untuk memanggil firstItem() --}}
                            <td class="ps-4">{{ $unit->firstItem() + $index }}</td>
                            <td>
                                <a href="{{ route('unit_kerja.show', $u->id) }}" class="text-decoration-none">
                                    <div class="fw-bold text-dark">{{ $u->nama_unit }}</div>
                                </a>
                            </td>
                            <td><span class="badge bg-light text-primary border">{{ $u->unit_singkatan }}</span></td>
                            <td>{{ $u->tipe_unit }}</td> 
                            <<td class="text-center">
                                @if($u->status_unit == 1)
                                    <span class="badge rounded-pill bg-success px-3">Aktif</span>
                                @else
                                    <span class="badge rounded-pill bg-danger px-3">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{-- Menampilkan jumlah SOP per unit kerja [cite: 162] --}}
                                <span class="badge bg-info text-dark">{{ $u->sops_count ?? 0 }}</span>
                            </td>
                            <td>{{ $u->created_at->format('d/m/Y') }}</td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    
                                    <a href="{{ route('unit_kerja.edit', $u->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('unit_kerja.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus unit ini jing?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">Belum ada data unit kerja.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Gunakan variabel $unit yang dikirim dari controller untuk pagination --}}
        @if($unit->hasPages())
        <div class="card-footer bg-white border-top-0 py-3">
            {{ $unit->links() }}
        </div>
        @endif
    </div>
</div>
@endsection