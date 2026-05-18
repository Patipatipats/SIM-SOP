<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        // 1. Ambil data Role + Hitung jumlah permission yang nempel
        $roles = DB::table('roles')
            ->leftJoin('role_permissions', 'roles.id', '=', 'role_permissions.role_id')
            ->select('roles.*', DB::raw('count(role_permissions.permission_id) as permission_count'))
            // Group By semua kolom yang di select biar kaga error strict mode MySQL
            ->groupBy('roles.id', 'roles.nama_role', 'roles.deskripsi', 'roles.status', 'roles.created_at', 'roles.updated_at')
            ->orderBy('roles.id', 'desc')
            ->paginate(10);

        // 2. Inject array permission_id ke tiap role buat nge-centang checkbox di modal Edit
        foreach ($roles as $role) {
            $role->permission_ids = DB::table('role_permissions')
                                        ->where('role_id', $role->id)
                                        ->pluck('permission_id')
                                        ->toArray();
        }

        // 3. Ambil semua master permission buat ditampilin di form checkbox
        $permissions = DB::table('permissions')->orderBy('nama_permission', 'asc')->get();

        // 🔥 Arahin path view sesuai folder lu
        return view('manajemen_pengguna.role.index', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_role'   => 'required|string|max:255|unique:roles,nama_role',
            'status'      => 'required',
            'permissions' => 'nullable|array'
        ]);

        // 1. Insert Role baru & ambil ID-nya
        $roleId = DB::table('roles')->insertGetId([
            'nama_role'  => $request->nama_role,
            'deskripsi'  => $request->deskripsi,
            'status'     => $request->status,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 2. 🔥 Sinkronisasi ke Tabel Pivot (role_permissions)
        if ($request->has('permissions') && is_array($request->permissions)) {
            foreach ($request->permissions as $permId) {
                DB::table('role_permissions')->insert([
                    'role_id'       => $roleId,
                    'permission_id' => $permId
                ]);
            }
        }

        return redirect()->back()->with('success', 'Role dan Permission berhasil disimpan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_role'   => 'required|string|max:255|unique:roles,nama_role,'.$id,
            'status'      => 'required',
            'permissions' => 'nullable|array'
        ]);

        // 1. Update master role
        DB::table('roles')->where('id', $id)->update([
            'nama_role'  => $request->nama_role,
            'deskripsi'  => $request->deskripsi,
            'status'     => $request->status,
            'updated_at' => now()
        ]);

        // 2. 🔥 Hapus pivot lama, lalu masukin centangan baru
        DB::table('role_permissions')->where('role_id', $id)->delete();

        if ($request->has('permissions') && is_array($request->permissions)) {
            foreach ($request->permissions as $permId) {
                DB::table('role_permissions')->insert([
                    'role_id'       => $id,
                    'permission_id' => $permId
                ]);
            }
        }

        return redirect()->back()->with('success', 'Role dan Permission berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Hapus pivotnya dulu biar ga nyangkut (foreign key constraint)
        DB::table('role_permissions')->where('role_id', $id)->delete();
        
        // Baru hapus master role-nya
        DB::table('roles')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Role berhasil dihapus!');
    }
}