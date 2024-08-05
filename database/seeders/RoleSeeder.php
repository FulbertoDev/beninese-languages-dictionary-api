<?php

namespace Database\Seeders;

use App\Helpers\PermissionsEnum;
use App\Helpers\RolesEnum;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $adminRole = Role::create([
            'name' => RolesEnum::ADMIN_ROLE->value,
            'description' => RolesEnum::ADMIN_ROLE->label(),
        ]);

        foreach (PermissionsEnum::cases() as $role) {
            $permission = Permission::create(['name' => $role->value, 'description' => $role->label(),]);
            $adminRole->givePermissionTo($permission);

        }
    }
}
