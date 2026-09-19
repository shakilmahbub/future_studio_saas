<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
class TenantService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function createTenant(array $data): Tenant
    {
        $tenant = DB::transaction(function () use ($data) {
            $tenant = Tenant::create(['id' => $data['name']]);

            $tenant->domains()->create([
                'domain' => $data['name'] . '.' . config('tenancy.central_domains')[0],
            ]);

            return $tenant;
        });

        try {
            tenancy()->initialize($tenant);

            $user = User::create([
                'name' => $data['admin_name'],
                'email' => $data['admin_email'],
                'password' => Hash::make($data['admin_password']),
            ]);

            $adminRole = Role::where('name', 'admin')->first();

            if ($adminRole) {
                $user->roles()->syncWithoutDetaching([$adminRole->id]);
            }
        } finally {
            tenancy()->end();
        }

        return $tenant;
    }
    
    public function deleteTenant($tenant)
    {
        // Delete the tenant's database
        $tenant->delete();

        // Delete the tenant's domain
        $tenant->domains()->delete();

        // Delete the tenant's users
        $tenant->users()->delete();
    }
}
