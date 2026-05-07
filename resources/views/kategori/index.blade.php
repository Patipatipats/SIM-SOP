@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Master Data - Kategori SOP</h4>
        <a href="{{ route('kategori.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Tambah Kategori
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th>Nama Kategori</th>
                            <th>Slug</th>
                            <th>Deskripsi</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Jumlah SOP</th>
                            <th>Dibuat Pada</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategori as $index => $item)
                        <tr>
                            <td class="ps-4">{{ $kategori->firstItem() + $index }}</td>
                            <td class="fw-bold text-dark">
                                <a href="{{ route('kategori.show', $item->id) }}" class="text-decoration-none">
                                    {{ $item->nama_kategori }}
                                </a>
                            </td>
                            <td><code class="text-primary">{{ $item->slug }}</code></td>
                            <td class="text-muted small">{{ Str::limit($item->deskripsi, 40) ?? '-' }}</td>
                            <td class="text-center">
                                @if($item->status)
                                    <span class="badge rounded-pill bg-success px-3">Aktif</span>
                                @else
                                    <span class="badge rounded-pill bg-danger px-3">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info text-dark">{{ $item->sops_count ?? 0 }}</span>
                            </td>
                            <td>{{ $item->created_at->format('d/m/Y') }}</td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('kategori.edit', $item->id) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('kategori.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">Belum ada data kategori.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($kategori->hasPages())
        <div class="card-footer bg-white border-top-0 py-3">
            {{ $kategori->links() }}
        </div>
        @endif
    </div>
</div>
@endsection