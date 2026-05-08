<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserInternalSeeder extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();

        try {
            /*
            |--------------------------------------------------------------------------
            | 1. ROLE USER INTERNAL
            |--------------------------------------------------------------------------
            */
            $role = DB::table('roles')
                ->where('nama_role', 'User Internal')
                ->first();

            if (!$role) {
                $roleId = DB::table('roles')->insertGetId([
                    'nama_role'  => 'User Internal',
                    'deskripsi'  => 'Akses baca, preview, dan unduh dokumen SOP yang telah aktif',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $roleId = $role->id;
            }

            /*
            |--------------------------------------------------------------------------
            | 2. PERMISSIONS (Hak Akses Terbatas / Read-Only)
            |--------------------------------------------------------------------------
            */
            $permissions = [
                'dashboard.view',
                'sop.view',
                'sop.download',
                'kategori.view',
                'unit_kerja.view',
            ];

            $permissionIds = [];
            foreach ($permissions as $perm) {
                $existing = DB::table('permissions')
                    ->where('nama_permission', $perm)
                    ->first();

                if ($existing) {
                    $permissionIds[] = $existing->id;
                } else {
                    $permissionIds[] = DB::table('permissions')->insertGetId([
                        'nama_permission' => $perm,
                        'deskripsi'       => 'Izin User: ' . $perm,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 3. ASSIGN PERMISSIONS TO ROLE USER INTERNAL
            |--------------------------------------------------------------------------
            */
            foreach ($permissionIds as $pid) {
                $exists = DB::table('role_permissions')
                    ->where('role_id', $roleId)
                    ->where('permission_id', $pid)
                    ->exists();

                if (!$exists) {
                    DB::table('role_permissions')->insert([
                        'role_id'       => $roleId,
                        'permission_id' => $pid,
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 4. USER INTERNAL (Staff / Mahasiswa Unla)
            |--------------------------------------------------------------------------
            */
            $user = DB::table('users')
                    ->where('email', 'mahasiswa@unla.ac.id')
                    ->orWhere('username', 'mahasiswa_unla')
                    ->first();
            
            if (!$user) {
                $userId = DB::table('users')->insertGetId([
                    'role_id'        => $roleId,
                    'username'       => 'mahasiswa_unla',
                    'email'          => 'mahasiswa@unla.ac.id',
                    'password'       => Hash::make('unla2026'),
                    'status'         => 1,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                    'created_by'     => 1,
                    'updated_by'     => 1,
                ]);
            } else {
                $userId = $user->id;
            }

            /*
            |--------------------------------------------------------------------------
            | 5. PROFIL PENGGUNA
            |--------------------------------------------------------------------------
            */
            DB::table('profil_pengguna')->updateOrInsert(
                ['user_id' => $userId],
                [
                    'nama'            => 'User Internal Unla',
                    'nim_nip'         => '41037006',
                    'status_pengguna' => 'aktif',
                    'jenis_pengguna'  => 'user',
                    'is_aktif'        => 1,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | 6. TEMP PASSWORD
            |--------------------------------------------------------------------------
            */
            DB::table('temp_users')->updateOrInsert(
                ['user_id' => $userId],
                [
                    'password_baru' => 'unla2026',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | 7. LOG AKTIVITAS
            |--------------------------------------------------------------------------
            */
            DB::table('log_aktivitas')->insert([
                'user_id'      => $userId,
                'modul'        => 'Seeder',
                'aktivitas'    => 'Inisialisasi Akun User Internal',
                'referensi_id' => $userId,
                'created_at'   => now(),
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}