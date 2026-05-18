<?php

namespace App\Http\Controllers;

use App\Models\Sop;
use App\Models\SopVersion; 
use App\Models\SopApproval; 
use App\Models\SopStatusHistory; 
use App\Models\KategoriSop;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\DB; 

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
        
        // 🔥 FIX TAHAP 10: Ambil semua master data tag untuk dicentang di modal checkbox view
        $allTags = DB::table('tags')->get();

        return view('sop.index', compact('sops', 'categories', 'units', 'allTags'));
    }

    public function versions($id)
    {
        $sop = Sop::findOrFail($id);
        return view('sop.versions', compact('sop'));
    }

    /**
     * Form Tambah SOP
     */
    public function create()
    {
        $categories = KategoriSop::where('status', 1)->get();
        $units = UnitKerja::where('status_unit', 1)->get();
        $allTags = DB::table('tags')->get();
        
        return view('sop.create', compact('categories', 'units', 'allTags'));
    }

    /**
     * Simpan Metadata SOP + Input Multi-Select Tag SOP
     */
    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_sop,id',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
            'tags' => 'nullable|array' // Pastiin request tags bentuknya array checkbox
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
        $sop = Sop::create([
            'kode_sop'      => $kodeOtomatis,
            'judul'         => $request->judul,
            'kategori_id'   => $request->kategori_id,
            'unit_kerja_id' => $request->unit_kerja_id,
            'deskripsi'     => $request->deskripsi,
            'status'        => 'Draft',
            'is_active'     => 1, 
            'created_by'    => auth()->id(),
        ]);

        // 🔥 FIX SAKTI STORE: Simpan data array checkbox ke tabel pivot 'sop_tags'
        $inputTags = $request->input('tags') ?? $request->input('tag_sop');

        if ($inputTags && is_array($inputTags)) {
            foreach ($inputTags as $tagId) {
                DB::table('sop_tags')->insert([
                    'sop_id' => $sop->id,
                    'tag_id' => $tagId
                ]);
            }
        }

        // 4. Catat Sejarah Pembuatan Awal
        DB::table('sop_status_history')->insert([
            'sop_id'      => $sop->id, 
            'status_lama' => '-', 
            'status_baru' => 'Draft', 
            'changed_by'  => auth()->id(),
            'catatan'     => 'Dokumen SOP baru dibuat di sistem',
            'created_at'  => now(),
        ]);

        return redirect()->route('sop.index')->with('success', "SOP Berhasil dibuat: $kodeOtomatis");
    }

    /**
     * Detail SOP
     */
    public function show(Request $request, $id)
    {
        $sop = Sop::with(['kategori', 'unitKerja', 'creator.profil'])->findOrFail($id);

        // Tambah 1 ke kolom total_views
        $sop->increment('total_views');

        // Catat log view ke tabel
        DB::table('sop_views')->insert([
            'sop_id'     => $sop->id,
            'user_id'    => auth()->check() ? auth()->id() : null, 
            'ip_address' => $request->ip(),
            'viewed_at'  => now(), 
        ]);

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
        $allTags = DB::table('tags')->get();

        return view('sop.edit', compact('sop', 'categories', 'units', 'allTags'));
    }

    /**
     * Update Metadata SOP + Sinkronisasi Ulang Tag SOP (Sync)
     */
    public function update(Request $request, $id)
    {
        $sop = Sop::findOrFail($id);

        $request->validate([
            'kode_sop' => 'required|unique:sop,kode_sop,' . $id, 
            'judul' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_sop,id',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
            'status' => 'required|in:Draft,Active,Archived,Review', 
            'tags' => 'nullable|array'
        ]);

        $sop->update([
            'kode_sop' => $request->kode_sop,
            'judul' => $request->judul,
            'kategori_id' => $request->kategori_id,
            'unit_kerja_id' => $request->unit_kerja_id,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
            'updated_by' => auth()->id(),
        ]);

        // 🔥 FIX SAKTI UPDATE: Hapus dulu semua tag lama milik SOP ini, baru tulis ulang dari checkbox baru
        DB::table('sop_tags')->where('sop_id', $id)->delete();

        $inputTags = $request->input('tags') ?? $request->input('tag_sop');

        if ($inputTags && is_array($inputTags)) {
            foreach ($inputTags as $tagId) {
                DB::table('sop_tags')->insert([
                    'sop_id' => $id,
                    'tag_id' => $tagId
                ]);
            }
        }

        return redirect()->route('sop.index')->with('success', 'Data SOP berhasil diperbarui!');
    }

    /**
     * Hapus SOP beserta log pivotnya
     */
    public function destroy($id)
    {
        $sop = Sop::findOrFail($id);
        
        // Hapus dulu data relasinya di pivot biar ga melanggar foreign key constraint
        DB::table('sop_tags')->where('sop_id', $id)->delete();
        
        $sop->delete();

        return redirect()->route('sop.index')->with('success', 'SOP berhasil dihapus!');
    }

    // =========================================================================
    // FITUR UPLOAD DAN PREVIEW PDF SOP (ANTI ERROR 404 CLUB)
    // =========================================================================

    public function uploadVersi(Request $request, $id)
    {
        $request->validate([
            'versi'           => 'required|string|max:10',
            'file_pdf'        => 'required|mimes:pdf|max:10240', 
            'catatan_revisi'  => 'nullable|string',
            'tanggal_berlaku' => 'nullable|date',
            'tanggal_expired' => 'nullable|date|after_or_equal:tanggal_berlaku',
        ]);

        $sop = Sop::findOrFail($id);
        $file = $request->file('file_pdf');

        $safeKode = str_replace(['/', '\\'], '-', $sop->kode_sop);
        $fileName = 'SOP_' . $safeKode . '_V' . $request->versi . '_' . time() . '.pdf';

        $path = $file->storeAs('sop_dokumen', $fileName, 'local');

        SopVersion::create([
            'sop_id'          => $sop->id,
            'versi'           => $request->versi,
            'file_path'       => $path,
            'catatan_revisi'  => $request->catatan_revisi, 
            'tanggal_berlaku' => $request->tanggal_berlaku,
            'tanggal_expired' => $request->tanggal_expired,
            'created_by'      => auth()->id(),
            'status'          => 'Draft', 
        ]);

        return back()->with('success', 'Versi ' . $request->versi . ' berhasil diunggah dengan catatan revisi!');
    }

    public function lihatPdf($version_id)
    {
        $version = SopVersion::findOrFail($version_id);
        
        if (!Storage::disk('local')->exists($version->file_path)) {
            $lokasi = storage_path('app/' . $version->file_path);
            abort(404, 'File GA ADA di folder: ' . $lokasi);
        }

        return Storage::disk('local')->response($version->file_path);
    }

    // =========================================================================
    // FITUR DOWNLOAD & TRACKING LOG
    // =========================================================================
    
    public function downloadPdf(Request $request, $id)
    {
        $version = SopVersion::findOrFail($id);
        $sop = $version->sop; 

        // 1. CATAT KE LOG DOWNLOAD 
        DB::table('sop_download_logs')->insert([
            'sop_id'        => $version->sop_id,
            'version_id'    => $version->id,
            'user_id'       => auth()->check() ? auth()->id() : null, 
            'ip_address'    => $request->ip(), 
            'user_agent'    => $request->userAgent(), 
            'downloaded_at' => now(),
            'created_at'    => now(),
        ]);

        // 2. Cek apakah file fisik PDF-nya beneran ada
        if (!Storage::disk('local')->exists($version->file_path)) {
            return back()->with('error', 'Waduh! File PDF fisiknya kaga ketemu di server jing!');
        }

        // 3. Bikin nama file cakep & Lempar file-nya buat di-download
        $safeKode = str_replace(['/', '\\', ' '], '_', $sop->kode_sop);
        $namaFile = 'SOP_' . $safeKode . '_V' . $version->versi . '.pdf';

        return Storage::disk('local')->download($version->file_path, $namaFile);
    }

    // =========================================================================
    // APPROVAL WORKFLOW
    // =========================================================================

    public function ajukanApproval($id)
    {
        $sop = Sop::findOrFail($id);
        
        if ($sop->versions->count() == 0) {
            return back()->with('error', 'Upload dulu file PDF-nya jing, baru diajuin!');
        }

        $statusLama = $sop->status; 
        $sop->update(['status' => 'Review']);

        DB::table('sop_status_history')->insert([
            'sop_id'      => $sop->id,
            'status_lama' => $statusLama,
            'status_baru' => 'Review',
            'changed_by'  => auth()->id(),
            'catatan'     => 'Operator mengajukan dokumen untuk di-review',
            'created_at'  => now(),
        ]);
        
        return back()->with('success', 'SOP berhasil diajukan ke Admin/Approver!');
    }

    public function setujui(Request $request, $id)
    {
        $sop = Sop::findOrFail($id);
        $latestVersion = $sop->versions()->latest('id')->first();
        $statusLama = $sop->status;
        
        $sop->update([
            'status'      => 'Active',
            'is_active'   => 1,
            'approved_by' => auth()->id(),
        ]);

        if ($latestVersion) {
            DB::table('sop_approvals')->insert([
                'version_id'     => $latestVersion->id,
                'user_id'        => auth()->id(),
                'status'         => 'Approved',
                'catatan'        => 'Dokumen Disetujui',
                'level_approval' => 1,
                'approved_at'    => now(),
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        // Sinkronisasi status riwayat versi
        if ($latestVersion) {
            $latestVersion->update(['status' => 'Active']);
        }

        DB::table('sop_status_history')->insert([
            'sop_id'      => $sop->id,
            'status_lama' => $statusLama,
            'status_baru' => 'Active',
            'changed_by'  => auth()->id(),
            'catatan'     => 'Dokumen Disetujui dan Diaktifkan',
            'created_at'  => now(),
        ]);

        return back()->with('success', 'Mantap! SOP telah disetujui dan berstatus ACTIVE.');
    }

    public function tolak(Request $request, $id)
    {
        $sop = Sop::findOrFail($id);
        $latestVersion = $sop->versions()->latest('id')->first();
        $statusLama = $sop->status;
        
        $sop->update([
            'status'    => 'Rejected',
            'is_active' => 0
        ]);

        if ($latestVersion) {
            DB::table('sop_approvals')->insert([
                'version_id'     => $latestVersion->id,
                'user_id'        => auth()->id(),
                'status'         => 'Rejected',
                'catatan'        => $request->catatan_approval,
                'level_approval' => 1,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
        
        DB::table('sop_status_history')->insert([
            'sop_id'      => $sop->id,
            'status_lama' => $statusLama,
            'status_baru' => 'Rejected',
            'changed_by'  => auth()->id(),
            'catatan'     => $request->catatan_approval, 
            'created_at'  => now(),
        ]);

        return back()->with('error', 'SOP resmi ditolak dengan catatan!');
    }
}