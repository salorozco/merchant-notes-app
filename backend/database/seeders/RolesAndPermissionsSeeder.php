<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions for merchants
        $merchantPermissions = [
            ['name' => 'view-merchants', 'description' => 'View merchants'],
            ['name' => 'create-merchants', 'description' => 'Create merchants'],
            ['name' => 'update-merchants', 'description' => 'Update merchants'],
            ['name' => 'delete-merchants', 'description' => 'Delete merchants'],
        ];

        // Create permissions for notes
        $notePermissions = [
            ['name' => 'view-notes', 'description' => 'View notes'],
            ['name' => 'create-notes', 'description' => 'Create notes'],
            ['name' => 'update-notes', 'description' => 'Update notes'],
            ['name' => 'delete-notes', 'description' => 'Delete notes'],
        ];

        $allPermissions = array_merge($merchantPermissions, $notePermissions);

        // Create all permissions
        foreach ($allPermissions as $permissionData) {
            Permission::firstOrCreate(
                ['name' => $permissionData['name']],
                ['description' => $permissionData['description']]
            );
        }

        // Create admin role with all permissions
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['description' => 'Administrator with full access']
        );
        $adminRole->permissions()->sync(Permission::all());

        // Create manager role with most permissions
        $managerRole = Role::firstOrCreate(
            ['name' => 'manager'],
            ['description' => 'Manager with read and write access']
        );
        $managerPermissions = Permission::whereIn('name', [
            'view-merchants',
            'create-merchants',
            'update-merchants',
            'view-notes',
            'create-notes',
            'update-notes',
        ])->get();
        $managerRole->permissions()->sync($managerPermissions);

        // Create user role with limited permissions
        $userRole = Role::firstOrCreate(
            ['name' => 'user'],
            ['description' => 'Regular user with basic access']
        );
        $userPermissions = Permission::whereIn('name', [
            'view-merchants',
            'view-notes',
            'create-notes',
        ])->get();
        $userRole->permissions()->sync($userPermissions);
    }
}
