<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function index()
    {
        // Ambil data permission
        $permissions = DB::table('permissions')
            ->orderBy('nama_permission', 'asc')
            ->paginate(10);

        // Arahin path view sesuai folder lu
        return view('manajemen_pengguna.permission.index', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_permission' => 'required|string|max:255|unique:permissions,nama_permission',
        ]);

        DB::table('permissions')->insert([
            'nama_permission' => $request->nama_permission,
            'deskripsi'       => $request->deskripsi,
            'created_at'      => now(),
            'updated_at'      => now()
        ]);

        return redirect()->back()->with('success', 'Permission baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_permission' => 'required|string|max:255|unique:permissions,nama_permission,'.$id,
        ]);

        DB::table('permissions')->where('id', $id)->update([
            'nama_permission' => $request->nama_permission,
            'deskripsi'       => $request->deskripsi,
            'updated_at'      => now()
        ]);

        return redirect()->back()->with('success', 'Data Permission berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Hapus pivotnya dulu di tabel role_permissions biar kaga nyangkut (foreign key constraint)
        DB::table('role_permissions')->where('permission_id', $id)->delete();
        
        // Baru hapus master permission-nya
        DB::table('permissions')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Permission berhasil dihapus permanen!');
    }
}