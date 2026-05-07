<?php

namespace App\Http\Controllers;

use App\Models\Sop;
use App\Models\KategoriSop;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SopController extends Controller
{
    /**
     * Tampilkan Daftar SOP dengan fitur Search & Filter
     */
    public function index(Request $request)
{
    $query = Sop::with(['kategori', 'unitKerja']);

    // Filter Search (Judul/Kode)
    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('judul', 'like', '%' . $request->search . '%')
              ->orWhere('kode_sop', 'like', '%' . $request->search . '%');
        });
    }

    // Filter Kategori
    if ($request->filled('kategori')) {
        $query->where('kategori_id', $request->kategori);
    }

    // Filter Unit Kerja
    if ($request->filled('unit')) {
        $query->where('unit_kerja_id', $request->unit);
    }

    // --- TAMBAHKAN LOGIKA INI (Filter Status) ---
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $sops = $query->latest()->paginate(10);
    $categories = KategoriSop::where('status', 1)->get();
    $units = UnitKerja::where('status_unit', 1)->get();

    return view('sop.index', compact('sops', 'categories', 'units'));
}


 public function versions($id)
{
    $sop = Sop::findOrFail($id);
    // Sementara kita ambil data SOP saja, 
    // kedepannya ini akan ambil data dari tabel 'sop_versions'
    return view('sop.versions', compact('sop'));
}
    /**
     * Form Tambah SOP
     */
    public function create()
    {
        $categories = KategoriSop::where('status', 1)->get();
        $units = UnitKerja::where('status_unit', 1)->get();
        
        return view('sop.create', compact('categories', 'units'));
    }

    /**
     * Simpan Metadata SOP
     */
  public function store(Request $request)
{
    // 1. Validasi (Sesuaikan dengan name di Form Blade kamu)
    $request->validate([
        'judul' => 'required|string|max:255',
        'kategori_id' => 'required|exists:kategori_sop,id',
        'unit_kerja_id' => 'required|exists:unit_kerja,id',
    ]);

    // 2. Logika Kode Otomatis
    $unit = \App\Models\UnitKerja::findOrFail($request->unit_kerja_id);
    $singkatan = $unit->unit_singkatan; 
    $tahun = date('Y');

    $lastSop = \App\Models\Sop::whereYear('created_at', $tahun)
                ->orderBy('id', 'desc')
                ->first();

    $noUrut = $lastSop ? (int)substr($lastSop->kode_sop, -4) + 1 : 1;
    $noUrutFormatted = str_pad($noUrut, 4, '0', STR_PAD_LEFT);
    $kodeOtomatis = "SOP/{$singkatan}/{$tahun}/{$noUrutFormatted}";

    // 3. Simpan ke Database (PASTIKAN KEY SESUAI FILLABLE & DB)
    \App\Models\Sop::create([
        'kode_sop'      => $kodeOtomatis,
        'judul'         => $request->judul,      // Sesuaikan nama kolom
        'kategori_id'   => $request->kategori_id, // Sesuaikan nama kolom
        'unit_kerja_id' => $request->unit_kerja_id,
        'deskripsi'     => $request->deskripsi,
        'status'        => 'Draft',
        'is_active'     => 1, // Default aktif saat dibuat
        'created_by'    => auth()->id(),
    ]);

    return redirect()->route('sop.index')->with('success', "SOP Berhasil dibuat: $kodeOtomatis");
}

    /**
     * Detail SOP
     */
    public function show($id)
    {
        $sop = Sop::with(['kategori', 'unitKerja', 'creator.profil'])->findOrFail($id);
        return view('sop.show', compact('sop'));
    }

    /**
     * Form Edit SOP
     */
    public function edit($id)
    {
        $sop = Sop::findOrFail($id);
        $categories = KategoriSop::where('status', 1)->get();
        $units = UnitKerja::where('status_unit', 1)->get();

        return view('sop.edit', compact('sop', 'categories', 'units'));
    }

    /**
     * Update Metadata SOP
     */
    public function update(Request $request, $id)
{
    $sop = \App\Models\Sop::findOrFail($id);

    // PERBAIKAN VALIDASI
    $request->validate([
        // Tabelnya 'sop', bukan 'sops'
        'kode_sop' => 'required|unique:sop,kode_sop,' . $id, 
        'judul' => 'required|string|max:255',
        'kategori_id' => 'required|exists:kategori_sop,id',
        'unit_kerja_id' => 'required|exists:unit_kerja,id',
        'status' => 'required|in:Draft,Active,Archived', // Sesuaikan enum status di DB
    ]);

    // PERBAIKAN UPDATE DATA
    $sop->update([
        'kode_sop' => $request->kode_sop,
        'judul' => $request->judul,
        'kategori_id' => $request->kategori_id,
        'unit_kerja_id' => $request->unit_kerja_id,
        'deskripsi' => $request->deskripsi,
        'tag_sop' => $request->tag_sop,
        'status' => $request->status,
        'updated_by' => auth()->id(),
    ]);

    return redirect()->route('sop.index')->with('success', 'Data SOP berhasil diperbarui!');
}

    /**
     * Hapus SOP
     */
    public function destroy($id)
    {
        $sop = Sop::findOrFail($id);
        $sop->delete();

        return redirect()->route('sop.index')->with('success', 'SOP berhasil dihapus!');
    }
}