<?php

namespace App\Http\Controllers;
Use App\Models\KategoriSop;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = \App\Models\KategoriSop::latest()->paginate(10);
        return view ('kategori.index' , compact('kategori'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kategori.create');        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'nama_kategori' => 'required|unique:kategori_sop,nama_kategori',
        ]);

    \App\Models\KategoriSop::create([
        'nama_kategori' => $request->nama_kategori,
        'slug' => \Str::slug($request->nama_kategori),
        'deskripsi' => $request->deskripsi,
        'status' => true, // Default aktif
        'created_by' => auth()->id(), // Catat user pengubah
        ]);

    return redirect()->route('kategori.index')->with('success', 'Kategori Berhasil Disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kategori = KategoriSop::with(['creator', 'updater'])->findOrFail($id);
        return view('kategori.show', compact('kategori'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kategori = KategoriSop::findOrFail($id);
        return view('kategori.edit', compact('kategori'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kategori = KategoriSop::findOrFail($id);

    $request->validate([
        'nama_kategori' => 'required|unique:kategori_sop,nama_kategori,' . $id,
        'status' => 'required|boolean',
    ]);

    $kategori->update([
        'nama_kategori' => $request->nama_kategori,
        'slug' => \Str::slug($request->nama_kategori),
        'deskripsi' => $request->deskripsi,
        'status' => $request->status,
        'updated_by' => auth()->id(), 
    ]);

    return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    $kategori = KategoriSop::findOrFail($id);
    
    // Cek dulu apakah kategori ini lagi dipakai di tabel SOP[cite: 1]
    if ($kategori->sops()->count() > 0) {
        return redirect()->back()->with('error', 'Gak bisa dihapus , kategori ini lagi dipakai di dokumen SOP!');
    }

    $kategori->delete();
    return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus!');
}
}
