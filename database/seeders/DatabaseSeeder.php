<?php

namespace Database\Seeders;

use Database\Seeders\SuperAdminSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Super Admin
        $this->call([
            SuperAdminSeeder::class,
            AdminSeeder::class,
            ApproverSeeder::class,
            UserInternalSeeder::class,
            OperatorUnitSeeder::class,
        ]);
    }
}