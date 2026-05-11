<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FakultasController extends Controller
{
    /**
     * Tampilkan data fakultas dengan perhitungan jumlah prodi (Tahap 8)
     */
    public function index()
    {
        // 🔥 PERBAIKAN: Gunakan Join untuk menghitung jumlah prodi dari tabel program_studi
        $fakultas = DB::table('fakultas')
            ->leftJoin('program_studi', 'fakultas.id', '=', 'program_studi.fakultas_id')
            ->select(
                'fakultas.*', 
                DB::raw('count(program_studi.id) as prodi_count')
            )
            ->groupBy('fakultas.id')
            ->orderBy('fakultas.nama', 'asc')
            ->paginate(10);

        return view('fakultas.index', compact('fakultas'));
    }

    /**
     * Simpan data fakultas baru (dari Modal Create)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'singkatan' => 'required|string|max:20',
            'status' => 'required' // 🔥 Pastikan status divalidasi
        ]);

        DB::table('fakultas')->insert([
            'nama'       => $request->nama,
            'singkatan'  => $request->singkatan,
            'status'     => $request->status, // 🔥 Simpan status (1 atau 0)
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        // Catat ke log aktivitas
        DB::table('log_aktivitas')->insert([
            'user_id'   => Auth::id(),
            'aktivitas' => "Menambahkan fakultas baru: " . $request->nama,
            'created_at' => now()
        ]);

        return redirect()->back()->with('success', 'Fakultas berhasil ditambahkan!');
    }

    /**
     * Update data fakultas (dari Modal Edit)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'singkatan' => 'required|string|max:20',
            'status' => 'required' // 🔥 Pastikan status divalidasi
        ]);

        DB::table('fakultas')->where('id', $id)->update([
            'nama'       => $request->nama,
            'singkatan'  => $request->singkatan,
            'status'     => $request->status,  
            'updated_at' => now(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Data fakultas berhasil diperbarui!');
    }

    /**
     * Tampilkan detail fakultas
     */
    public function show($id)
    {
        $f = DB::table('fakultas')->where('id', $id)->first();

        if (!$f) {
            return redirect()->route('fakultas.index')->with('error', 'Fakultas tidak ditemukan!');
        }

        // Ambil prodi yang nyambung ke fakultas ini
        $units = DB::table('program_studi')->where('fakultas_id', $id)->get();

        return view('fakultas.show', compact('f', 'units'));
    }

    /**
     * Hapus data fakultas
     */
    public function destroy($id)
    {
        DB::table('fakultas')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Fakultas berhasil dihapus!');
    }
}