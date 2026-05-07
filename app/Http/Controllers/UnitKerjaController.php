<?php

namespace App\Http\Controllers;

use App\Models\UnitKerja; // Pastikan Model di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitKerjaController extends Controller
{
    public function index()
{
    // Menggunakan withCount agar otomatis ada variabel sops_count
   $unit = \App\Models\UnitKerja::withCount('sops')
                ->latest()
                ->paginate(10);
    
    return view('unit_kerja.index', compact('unit'));
}

    public function create()
    {
        return view('unit_kerja.create');
    }

  public function store(Request $request)
{
    // 1. Validasi sesuai dengan atribut 'name' di Blade kamu
    $request->validate([
        'nama_unit'      => 'required|string|max:255',
        'unit_singkatan' => 'required|string|max:50|unique:unit_kerja,unit_singkatan',
        'tipe_unit'      => 'required|string',
    ]);

    // 2. Simpan ke Model UnitKerja (Bukan KategoriSop!)
    \App\Models\UnitKerja::create([
        'nama_unit'      => $request->nama_unit,
        'unit_singkatan' => $request->unit_singkatan,
        'tipe_unit'      => $request->tipe_unit,
        'status_unit'    => $request->has('status') ? 1 : 0, // Mengambil dari checkbox name="status"
        'created_by'     => auth()->id(),
    ]);

    // 3. Redirect ke index Unit Kerja
    return redirect()->route('unit_kerja.index')->with('success', 'Unit Kerja Berhasil Disimpan');
}
    public function show($id)
    {
        $unit = UnitKerja::with(['creator', 'updater'])->findOrFail($id);
        return view('unit_kerja.show', compact('unit'));
    }

    public function edit($id)
    {
        $unit = UnitKerja::findOrFail($id);
        return view('unit_kerja.edit', compact('unit'));
    }

    public function update(Request $request, $id)
    {
        $unit = UnitKerja::findOrFail($id);

        $validatedData = $request->validate([
            'nama_unit' => 'required|string|max:255',
            'unit_singkatan' => 'required|string|max:50',
            'tipe_unit' => 'required|string',
            'status_unit' => 'required|boolean',
        ]);

        // Tambahkan user yang sedang login sebagai pengubah
        $validatedData['updated_by'] = Auth::id();

        $unit->update($validatedData);

        return redirect()->route('unit_kerja.index')->with('success', 'Unit Kerja berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $unit = UnitKerja::findOrFail($id);
        $unit->delete();
        
        return redirect()->route('unit_kerja.index')->with('success', 'Unit Kerja berhasil dihapus.');
    }
}