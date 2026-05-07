<table class="table table-bordered" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Unit</th>
            <th>Singkatan</th>
            <th>Tipe Unit</th>
            <th>Status</th>
            <th>Jumlah SOP</th> <th>Dibuat Pada</th> <th>Aksi</th>
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
            <td>{{ $unit->sops_count ?? 0 }} SOP</td> 
            
            <td>{{ $unit->created_at->format('d/m/Y') }}</td>
            
            <td>
                <div class="btn-group" role="group">
                    <a href="{{ route('unit-kerja.show', $unit->id) }}" class="btn btn-sm btn-info text-white" title="Lihat SOP Unit">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('unit-kerja.edit', $unit->id) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('unit-kerja.destroy', $unit->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus unit ini?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>