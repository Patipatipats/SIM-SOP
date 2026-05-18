<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PengaturanLandingController extends Controller
{
    public function index()
    {
        // Panggil data landing page diload bareng tabel users buat ngambil nama pembuatnya
        $landingPages = DB::table('landing_pages')
            ->leftJoin('users', 'landing_pages.created_by', '=', 'users.id')
            ->select('landing_pages.*', 'users.username as nama_pembuat') 
            ->orderBy('urutan', 'asc') 
            ->paginate(10);

        return view('pengaturan_sistem.landingpage.index', compact('landingPages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'  => 'required|string|max:255',
            'tipe'   => 'required|string',
            'urutan' => 'required|integer',
            'aktif'  => 'required' // 🔥 Disesuaikan dengan kolom 'aktif' di DB lu
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->judul);

        DB::table('landing_pages')->insert([
            'judul'      => $request->judul,
            'slug'       => $slug,
            'konten'     => $request->konten,
            'tipe'       => $request->tipe,
            'urutan'     => $request->urutan,
            'aktif'      => $request->aktif, // 🔥 Pakai kolom aktif
            'created_by' => Auth::id() ?? 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Konten Landing Page berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul'  => 'required|string|max:255',
            'tipe'   => 'required|string',
            'urutan' => 'required|integer',
            'aktif'  => 'required'
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->judul);

        DB::table('landing_pages')->where('id', $id)->update([
            'judul'      => $request->judul,
            'slug'       => $slug,
            'konten'     => $request->konten,
            'tipe'       => $request->tipe,
            'urutan'     => $request->urutan,
            'aktif'      => $request->aktif,
            'updated_by' => Auth::id() ?? 1, // 🔥 Manfaatin kolom updated_by yang ada di DB lu
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Konten Landing Page berhasil diperbarui!');
    }

    public function toggleStatus($id)
    {
        $lp = DB::table('landing_pages')->where('id', $id)->first();
        if($lp) {
            DB::table('landing_pages')->where('id', $id)->update([
                'aktif' => $lp->aktif == 1 ? 0 : 1, // 🔥 Toggle pake kolom aktif
                'updated_by' => Auth::id() ?? 1
            ]);
        }
        return redirect()->back()->with('success', 'Status konten berhasil diubah!');
    }

    public function destroy($id)
    {
        DB::table('landing_pages')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Konten berhasil dihapus!');
    }
}