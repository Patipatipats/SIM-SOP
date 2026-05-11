<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProgramStudiController extends Controller
{
    /**
     * Tampilkan data Program Studi dengan relasi Fakultas
     */
    public function index()
    {
       
        $prodi = DB::table('program_studi')
            ->join('fakultas', 'program_studi.fakultas_id', '=', 'fakultas.id')
            ->select('program_studi.*', 'fakultas.nama as nama_fakultas')
            ->orderBy('fakultas.nama', 'asc')
            ->orderBy('program_studi.nama', 'asc')
            ->paginate(10);

        // Ambil semua data fakultas buat dropdown di Modal (Create & Edit)
        $allFakultas = DB::table('fakultas')->where('status', 1)->get();

        return view('program_studi.index', compact('prodi', 'allFakultas'));
    }

    /**
     * Simpan Prodi baru (dari Modal Create)
     */
 public function store(Request $request)
{
    $request->validate([
        'fakultas_id' => 'required',
        'nama'        => 'required|string|max:255',
        'singkatan'   => 'required|string|max:20',
        'status'      => 'required'
    ]);

    // 🔥 LOGIKA KODE BIASA (Hanya Angka)
    // Ambil kode prodi terakhir, terus ubah jadi angka, lalu tambah 1
    $lastProdi = DB::table('program_studi')->orderBy('kode_prodi', 'desc')->first();
    
    // Mulai dari 101 biar keliatan kayak kode beneran, kaga mulai dari 1 (terlalu sepi)
    $autoKode = $lastProdi ? intval($lastProdi->kode_prodi) + 1 : 101;

    DB::table('program_studi')->insert([
        'fakultas_id' => $request->fakultas_id,
        'kode_prodi'  => $autoKode, // Masukkin angka doang jing
        'nama'        => $request->nama,
        'singkatan'   => $request->singkatan,
        'status'      => $request->status,
        'created_at'  => now(),
        'updated_at'  => now(),
    ]);

    return redirect()->back()->with('success', 'Prodi berhasil ditambah! Kode: ' . $autoKode);
}
    /**
     * Update data Prodi (dari Modal Edit)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'fakultas_id' => 'required',
            'kode_prodi'  => 'required|unique:program_studi,kode_prodi,'.$id,
            'nama'        => 'required|string|max:255',
            'singkatan'   => 'required|string|max:20',
            'status'      => 'required'
        ]);

        DB::table('program_studi')->where('id', $id)->update([
            'fakultas_id' => $request->fakultas_id,
            'kode_prodi'  => $request->kode_prodi,
            'nama'        => $request->nama,
            'singkatan'   => $request->singkatan,
            'status'      => $request->status,
            'updated_at'  => now(),
            'updated_by'  => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Data Program Studi berhasil diperbarui!');
    }

    /**
     * Hapus data Prodi
     */
    public function destroy($id)
    {
        // Cek dulu apakah prodi ini masih punya keterkaitan data (opsional)
        DB::table('program_studi')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Program Studi berhasil dihapus!');
    }
}