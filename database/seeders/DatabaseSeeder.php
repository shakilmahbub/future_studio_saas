<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\CentralUserSeeder;
use Database\Seeders\TenantRolePermissionSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (tenancy()->initialized) {
            $this->call([
                TenantRolePermissionSeeder::class,
            ]);

            return;
        }
        $this->call([
            CentralUserSeeder::class,
        ]);
    }
}
