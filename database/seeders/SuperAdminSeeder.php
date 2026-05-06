<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | 1. ROLE
            |--------------------------------------------------------------------------
            */
            $role = DB::table('roles')
                ->where('nama_role', 'Super Admin')
                ->first();

            if (!$role) {
                $roleId = DB::table('roles')->insertGetId([
                    'nama_role'  => 'Super Admin',
                    'deskripsi'  => 'Akses penuh ke seluruh sistem',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $roleId = $role->id;
            }

            /*
            |--------------------------------------------------------------------------
            | 2. PERMISSIONS
            |--------------------------------------------------------------------------
            */
            $permissions = [
                'dashboard.view',

                'sop.view',
                'sop.create',
                'sop.update',
                'sop.delete',

                'kategori.view',
                'unit_kerja.view',
                'tag.view',

                'users.view',
                'users.create',
                'users.update',
                'users.delete',

                'role.view',
                'permission.view',

                'log.view',
                'sop.download',
                'sop.view_log',
                'sop.approval',

                'setting.landing',
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
                        'deskripsi'       => $perm,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 3. ROLE PERMISSIONS
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
            | 4. USER (FIXED: TANPA NAME)
            |--------------------------------------------------------------------------
            */
            $user = DB::table('users')
                    ->where(function ($q) {
                        $q->where('email', 'admin@unla.ac.id')
                          ->orWhere('username', 'superadmin');
                    })
                    ->first();
            
            if (!$user) {
                $userId = DB::table('users')->insertGetId([
                    'role_id'        => $roleId, // 🔥 PENTING
                    'unit_kerja_id'  => null,
                    'fakultas_id'    => null,
                    'prodi_id'       => null,
            
                    'username'       => 'superadmin', // 🔥 biasanya wajib
                    'email'          => 'admin@unla.ac.id',
                    'password'       => Hash::make('admin123'),
            
                    'no_hp'          => null,
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
            $profil = DB::table('profil_pengguna')
                ->where('user_id', $userId)
                ->first();

            if (!$profil) {
                DB::table('profil_pengguna')->insert([
                    'user_id'         => $userId,
                    'nama'            => 'Super Admin',
                    'nim_nip'         => '00000001',
                    'nidn'            => null,
                    'status_pengguna' => 'aktif',
                    'jenis_pengguna'  => 'admin',
                    'is_aktif'        => 1,
                    'fk_prodi_id'     => null,
                    'unit_kerja_id'   => null,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 6. TEMP PASSWORD
            |--------------------------------------------------------------------------
            */
            $temp = DB::table('temp_users')
                    ->where('user_id', $userId)
                    ->first();

            if (!$temp) {
                DB::table('temp_users')->insert([
                    'user_id'       => $userId,
                    'password_lama' => null,
                    'password_baru' => 'admin123',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 7. LOG AKTIVITAS
            |--------------------------------------------------------------------------
            */
            $logExist = DB::table('log_aktivitas')
                ->where('modul', 'Seeder')
                ->where('user_id', $userId)
                ->where('aktivitas', 'Membuat / memastikan Super Admin')
                ->first();
            
            if (!$logExist) {
                DB::table('log_aktivitas')->insert([
                    'user_id'      => $userId,
                    'modul'        => 'Seeder',
                    'aktivitas'    => 'Membuat / memastikan Super Admin',
                    'referensi_id' => $userId,
                    'created_at'   => now(),
                ]);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}