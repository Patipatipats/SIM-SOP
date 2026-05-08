<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OperatorUnitSeeder extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();

        try {
            /*
            |--------------------------------------------------------------------------
            | 1. ROLE OPERATOR UNIT
            |--------------------------------------------------------------------------
            */
            $role = DB::table('roles')
                ->where('nama_role', 'Operator Unit')
                ->first();

            if (!$role) {
                $roleId = DB::table('roles')->insertGetId([
                    'nama_role'  => 'Operator Unit',
                    'deskripsi'  => 'Input metadata SOP dan upload file dokumen unit kerja',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $roleId = $role->id;
            }

            /*
            |--------------------------------------------------------------------------
            | 2. PERMISSIONS (Hak Akses Operasional SOP)
            |--------------------------------------------------------------------------
            */
            $permissions = [
                'dashboard.view',
                'sop.view',
                'sop.create',
                'sop.update',
                'sop.upload', // Izin krusial untuk Task 4
                'sop.view_log',
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
                        'deskripsi'       => 'Izin Operator: ' . $perm,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 3. ASSIGN PERMISSIONS TO ROLE OPERATOR
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
            | 4. USER OPERATOR (Staff Teknik)
            |--------------------------------------------------------------------------
            */
            $user = DB::table('users')
                    ->where('email', 'operator@unla.ac.id')
                    ->orWhere('username', 'staff_teknik')
                    ->first();
            
            if (!$user) {
                $userId = DB::table('users')->insertGetId([
                    'role_id'        => $roleId,
                    'username'       => 'staff_teknik',
                    'email'          => 'operator@unla.ac.id',
                    'password'       => Hash::make('staffft123'),
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
                    'nama'            => 'Operator Unit FT',
                    'nim_nip'         => '19900003',
                    'status_pengguna' => 'aktif',
                    'jenis_pengguna'  => 'operator',
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
                    'password_baru' => 'staffft123',
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
                'aktivitas'    => 'Inisialisasi Akun Operator Unit',
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