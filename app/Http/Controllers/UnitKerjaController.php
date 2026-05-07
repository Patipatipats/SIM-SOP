<?php

namespace App\Http\Controllers;

use App\Models\UnitKerja; // Pastikan Model di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitKerjaController extends Controller
{
    public function index()
    {
        // Sesuaikan nama variabel dengan loop di index.blade.php ($units)
        $units = UnitKerja::latest()->get();
        return view('unit_kerja.index', compact('units'));
    }

    public function create()
    {
        return view('unit_kerja.create');
    }

    public function store(Request $request)
    {
        // Sesuaikan validasi dengan fillable di Model
        $validatedData = $request->validate([
            'nama_unit' => 'required|string|max:255',
            'unit_singkatan' => 'required|string|max:50',
            'tipe_unit' => 'required|string',
            'status_unit' => 'required|boolean',
        ]);

        // Tambahkan user yang sedang login sebagai pembuat
        $validatedData['created_by'] = Auth::id();

        UnitKerja::create($validatedData);

        return redirect()->route('unit_kerja.index')->with('success', 'Unit Kerja berhasil ditambahkan.');
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