<?php

namespace App\Http\Controllers;

use App\Models\Sop;
use App\Models\SopVersion; // Wajib dipanggil untuk menyimpan riwayat versi
use App\Models\KategoriSop;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage; // Wajib dipanggil untuk simpan file PDF

class SopController extends Controller
{
    /**
     * Tampilkan Daftar SOP dengan fitur Search & Filter
     */
    public function index(Request $request)
    {
        $query = Sop::with(['kategori', 'unitKerja', 'versions']);

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

        // Filter Status
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
        // Nanti kita akan tambahin logika untuk narik data SopVersion ke view ini
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
        // 1. Validasi
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_sop,id',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
        ]);

        // 2. Logika Kode Otomatis
        $unit = UnitKerja::findOrFail($request->unit_kerja_id);
        $singkatan = $unit->unit_singkatan; 
        $tahun = date('Y');

        $lastSop = Sop::whereYear('created_at', $tahun)
                    ->orderBy('id', 'desc')
                    ->first();

        $noUrut = $lastSop ? (int)substr($lastSop->kode_sop, -4) + 1 : 1;
        $noUrutFormatted = str_pad($noUrut, 4, '0', STR_PAD_LEFT);
        $kodeOtomatis = "SOP/{$singkatan}/{$tahun}/{$noUrutFormatted}";

        // 3. Simpan ke Database
        Sop::create([
            'kode_sop'      => $kodeOtomatis,
            'judul'         => $request->judul,
            'kategori_id'   => $request->kategori_id,
            'unit_kerja_id' => $request->unit_kerja_id,
            'deskripsi'     => $request->deskripsi,
            'status'        => 'Draft',
            'is_active'     => 1, 
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
        $sop = Sop::findOrFail($id);

        $request->validate([
            'kode_sop' => 'required|unique:sop,kode_sop,' . $id, 
            'judul' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_sop,id',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
            'status' => 'required|in:Draft,Active,Archived', 
        ]);

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

   public function downloadTerbaru($sop_id)
{
    $version = SopVersion::where('sop_id', $sop_id)->orderBy('id', 'desc')->first();

    if (!$version || !Storage::disk('local')->exists($version->file_path)) {
        return back()->with('error', 'SOP ini belum punya file PDF yang valid di server!');
    }

    return Storage::disk('local')->download($version->file_path);
}
   // =========================================================================
    // FITUR UPLOAD DAN PREVIEW PDF SOP (ANTI ERROR 404 CLUB)
    // =========================================================================

    /**
     * Upload Versi SOP (PDF)
     */
    public function uploadVersi(Request $request, $id)
    {
        // 1. Validasi Input Lengkap
        $request->validate([
            'versi'           => 'required|string|max:10',
            'file_pdf'        => 'required|mimes:pdf|max:10240', // Max 10MB
            'catatan_revisi'  => 'nullable|string',
            'tanggal_berlaku' => 'nullable|date',
            'tanggal_expired' => 'nullable|date|after_or_equal:tanggal_berlaku',
        ]);

        $sop = Sop::findOrFail($id);
        $file = $request->file('file_pdf');

        $safeKode = str_replace(['/', '\\'], '-', $sop->kode_sop);
        $fileName = 'SOP_' . $safeKode . '_V' . $request->versi . '_' . time() . '.pdf';

        // Simpan file ke storage/app/sop_dokumen
        $path = $file->storeAs('sop_dokumen', $fileName, 'local');

        // 2. Simpan ke Database (Nama kolom harus sesuai image_53265a.jpg)
        SopVersion::create([
            'sop_id'          => $sop->id,
            'versi'           => $request->versi,
            'file_path'       => $path,
            'catatan_revisi'  => $request->catatan_revisi, 
            'tanggal_berlaku' => $request->tanggal_berlaku,
            'tanggal_expired' => $request->tanggal_expired,
            'created_by'      => auth()->id(),
            'status'          => 'Draft', // Set default status
        ]);

        return back()->with('success', 'Versi ' . $request->versi . ' berhasil diunggah dengan catatan revisi!');
    }

    public function lihatPdf($version_id)
    {
        $version = SopVersion::findOrFail($version_id);
        
        // 🔥 Cek file pake disk 'local'
        if (!Storage::disk('local')->exists($version->file_path)) {
            $lokasi = storage_path('app/' . $version->file_path);
            abort(404, 'File GA ADA di folder: ' . $lokasi);
        }

        return Storage::disk('local')->response($version->file_path);
    }
}