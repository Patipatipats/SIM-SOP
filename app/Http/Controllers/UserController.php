<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Panggil relasi, asumsikan di model User lu udah bikin relasi ini (role, unitKerja, fakultas, programStudi)
        $users = User::with(['role', 'unitKerja', 'fakultas', 'programStudi'])
                     ->orderBy('id', 'desc')
                     ->paginate(10);

        // Data buat dropdown di modal
        $roles    = DB::table('roles')->get(); // Sesuaikan nama tabel role lu
        $units    = DB::table('unit_kerja')->get();
        $fakultas = DB::table('fakultas')->get();
        $prodi    = DB::table('program_studi')->get();

        return view('manajemen_pengguna.pengguna.index', compact('users', 'roles', 'units', 'fakultas', 'prodi'));
    }

    public function store(Request $request)
    {
        // Validasi sesuai modul PDF lu
        $request->validate([
            'nama'      => 'required|string|max:255',
            'username'  => 'required|string|max:255|unique:users,username',
            'email'     => 'required|email|max:255|unique:users,email',
            'password'  => 'required|min:6|confirmed', // Confirmed butuh input name="password_confirmation"
            'role_id'   => 'required',
            'status'    => 'required'
        ]);

        User::create([
            'nama'             => $request->nama,
            'username'         => $request->username,
            'email'            => $request->email,
            'password'         => Hash::make($request->password),
            'no_hp'            => $request->no_hp,
            'role_id'          => $request->role_id,
            'unit_kerja_id'    => $request->unit_kerja_id,
            'fakultas_id'      => $request->fakultas_id,
            'program_studi_id' => $request->program_studi_id,
            'status'           => $request->status,
        ]);

        return redirect()->back()->with('success', 'User baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama'      => 'required|string|max:255',
            'username'  => 'required|string|max:255|unique:users,username,'.$id,
            'email'     => 'required|email|max:255|unique:users,email,'.$id,
            'role_id'   => 'required',
            'status'    => 'required'
        ]);

        $user->update([
            'nama'             => $request->nama,
            'username'         => $request->username,
            'email'            => $request->email,
            'no_hp'            => $request->no_hp,
            'role_id'          => $request->role_id,
            'unit_kerja_id'    => $request->unit_kerja_id,
            'fakultas_id'      => $request->fakultas_id,
            'program_studi_id' => $request->program_studi_id,
            'status'           => $request->status,
        ]);

        return redirect()->back()->with('success', 'Data user berhasil diperbarui!');
    }
    public function show($id)
{
    // Ambil data user lengkap beserta relasi penempatannya agar muncul nama singkatan kedinasannya
    $user = User::with(['role', 'unitKerja', 'fakultas', 'programStudi'])->findOrFail($id);

    // 🔥 Arahin murni ke sub-folder manajemen_pengguna/pengguna lu Patt
    return view('manajemen_pengguna.pengguna.show', compact('user'));
}

    // Aksi Cepat: Aktif / Nonaktif
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'status' => $user->status == 1 ? 0 : 1
        ]);
        
        return redirect()->back()->with('success', 'Status user berhasil diubah!');
    }

    // Aksi Cepat: Reset Password ke default "12345678"
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make('12345678')
        ]);
        
        return redirect()->back()->with('success', 'Password user berhasil direset ke: 12345678');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'User berhasil dihapus!');
    }
}