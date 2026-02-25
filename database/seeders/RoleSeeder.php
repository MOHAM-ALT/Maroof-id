<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $roles = [
            [
                'name' => 'super_admin',
                'guard_name' => 'web',
            ],
            [
                'name' => 'customer',
                'guard_name' => 'web',
            ],
            [
                'name' => 'print_partner',
                'guard_name' => 'web',
            ],
            [
                'name' => 'reseller',
                'guard_name' => 'web',
            ],
            [
                'name' => 'designer',
                'guard_name' => 'web',
            ],
            [
                'name' => 'affiliate',
                'guard_name' => 'web',
            ],
            [
                'name' => 'business',
                'guard_name' => 'web',
            ],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }

        $this->command->info('✅ Created 7 roles successfully!');
    }
}
