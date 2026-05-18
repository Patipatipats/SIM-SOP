<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagSopController extends Controller
{
    /**
     * Tampilkan data Tag SOP + Hitung relasi jumlah SOP dari tabel pivot
     */
    public function index()
    {
        // Ambil data dari tabel master 'tags' dan join ke pivot 'sop_tags'
        $tags = DB::table('tags')
            ->leftJoin('sop_tags', 'tags.id', '=', 'sop_tags.tag_id')
            ->select('tags.*', DB::raw('count(sop_tags.sop_id) as sop_count'))
            ->groupBy('tags.id')
            ->orderBy('tags.nama', 'asc')
            ->paginate(10);

        return view('tag_sop.index', compact('tags'));
    }

    /**
     * Simpan Tag Baru ke tabel Master
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255'
        ]);

        // 🔥 PERBAIKAN SAKTI: Hapus created_at & updated_at dari insert karena tabel tags lu gak punya kolom itu
        DB::table('tags')->insert([
            'nama' => $request->nama
        ]);

        return redirect()->back()->with('success', 'Tag SOP berhasil ditambahkan!');
    }

    /**
     * Update Data Tag
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255'
        ]);

        // 🔥 PERBAIKAN SAKTI: Hapus updated_at juga di sini biar gak error pas edit data
        DB::table('tags')->where('id', $id)->update([
            'nama' => $request->nama
        ]);

        return redirect()->back()->with('success', 'Tag SOP berhasil diperbarui!');
    }

    /**
     * Hapus Data Tag beserta hubungannya di pivot
     */
    public function destroy($id)
    {
        // 1. Hapus hubungan pivotnya dulu di 'sop_tags' biar gak error foreign key
        DB::table('sop_tags')->where('tag_id', $id)->delete();

        // 2. Hapus data aslinya di tabel master 'tags'
        DB::table('tags')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Tag SOP berhasil dihapus!');
    }
}