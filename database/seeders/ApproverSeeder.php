<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ApproverSeeder extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();

        try {
            /*
            |--------------------------------------------------------------------------
            | 1. ROLE APPROVER
            |--------------------------------------------------------------------------
            */
            $role = DB::table('roles')
                ->where('nama_role', 'Approver')
                ->first();

            if (!$role) {
                $roleId = DB::table('roles')->insertGetId([
                    'nama_role'  => 'Approver',
                    'deskripsi'  => 'Review, verifikasi, dan memberikan persetujuan (approval) dokumen SOP',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $roleId = $role->id;
            }

            /*
            |--------------------------------------------------------------------------
            | 2. PERMISSIONS (Hak Akses Review & Approval)
            |--------------------------------------------------------------------------
            */
            $permissions = [
                'dashboard.view',
                'sop.view',
                'sop.approval', // Hak akses utama untuk Approve/Reject
                'sop.view_log',
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
                        'deskripsi'       => 'Izin Approver: ' . $perm,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 3. ASSIGN PERMISSIONS TO ROLE APPROVER
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
            | 4. USER APPROVER (Kabag Hukum / Pimpinan)
            |--------------------------------------------------------------------------
            */
            $user = DB::table('users')
                    ->where('email', 'Approver@unla.ac.id')
                    ->orWhere('username', 'Approver_review')
                    ->first();
            
            if (!$user) {
                $userId = DB::table('users')->insertGetId([
                    'role_id'        => $roleId,
                    'username'       => 'Approver_review',
                    'email'          => 'Approver@unla.ac.id',
                    'password'       => Hash::make('kabag123'),
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
                    'nama'            => 'Approver Kabag Hukum',
                    'nim_nip'         => '19900004',
                    'status_pengguna' => 'aktif',
                    'jenis_pengguna'  => 'approver',
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
                    'password_baru' => 'Approver123',
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
                'aktivitas'    => 'Inisialisasi Akun Approver Kabag',
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