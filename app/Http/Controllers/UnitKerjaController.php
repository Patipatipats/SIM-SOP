<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UnitKerjaController extends Controller
{
    public function index()
    {
        $unitKerja = UnitKerja::all();
        return view('unit-kerja.index', compact('unitKerja'));
    }

    public function create()
    {
        $unitKerja = new UnitKerja();
        return view('unit-kerja.create', compact('unitKerja'));
    }

    public function store(Request $request)
    {
        $unitKerja = new UnitKerja();
        $validatedData = $request->validate([
            'nama_unit_kerja' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);
        $unitKerja->create($validatedData);
        return redirect()->route('unit-kerja.index')->with('success', 'Unit Kerja berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $unitKerja = UnitKerja::findOrFail($id);
        return view('unit-kerja.edit', compact('unitKerja'));
    }

    public function update(Request $request, $id)
    {
        $unitKerja = UnitKerja::findOrFail($id);
        $validatedData = $request->validate([
            'nama_unit_kerja' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $unitKerja->update($validatedData);
        return redirect()->route('unit-kerja.index')->with('success', 'Unit Kerja berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $unitKerja = UnitKerja::findOrFail($id);
        $unitKerja->delete();
        return redirect()->route('unit-kerja.index')->with('success', 'Unit Kerja berhasil dihapus.');
    }
}
