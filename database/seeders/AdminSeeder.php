<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();

        try {
            /*
            |--------------------------------------------------------------------------
            | 1. ROLE ADMIN
            |--------------------------------------------------------------------------
            */
            $role = DB::table('roles')
                ->where('nama_role', 'Admin')
                ->first();

            if (!$role) {
                $roleId = DB::table('roles')->insertGetId([
                    'nama_role'  => 'Admin',
                    'deskripsi'  => 'Kelola Master Data, Laporan, dan Aktivasi SOP',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $roleId = $role->id;
            }

            /*
            |--------------------------------------------------------------------------
            | 2. PERMISSIONS (Khusus Hak Akses Admin)
            |--------------------------------------------------------------------------
            */
            $permissions = [
                'dashboard.view',
                'sop.view',
                'sop.download',
                'sop.view_log',
                
                'kategori.view',
                'kategori.create',
                'kategori.update',
                
                'unit_kerja.view',
                'unit_kerja.create',
                'unit_kerja.update',

                'users.view',
                'log.view',
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
                        'deskripsi'       => 'Izin Admin: ' . $perm,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 3. ASSIGN PERMISSIONS TO ROLE ADMIN
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
            | 4. USER ADMIN
            |--------------------------------------------------------------------------
            */
            $user = DB::table('users')
                    ->where('email', 'adminsop@unla.ac.id')
                    ->orWhere('username', 'admin_sop')
                    ->first();
            
            if (!$user) {
                $userId = DB::table('users')->insertGetId([
                    'role_id'        => $roleId,
                    'username'       => 'admin_sop',
                    'email'          => 'adminsop@unla.ac.id',
                    'password'       => Hash::make('adminsop123'),
                    'status'         => 1, // aktif
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
                    'nama'            => 'Admin Master Data',
                    'nim_nip'         => '19900002',
                    'status_pengguna' => 'aktif',
                    'jenis_pengguna'  => 'admin',
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
                    'password_baru' => 'adminsop123',
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
                'aktivitas'    => 'Inisialisasi Akun Admin SOP',
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