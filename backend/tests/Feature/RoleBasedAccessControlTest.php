<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleBasedAccessControlTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that roles can be created and assigned to users.
     */
    public function test_user_can_be_assigned_roles(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'test-role',
            'description' => 'Test Role',
        ]);

        $user->roles()->attach($role);

        $this->assertTrue($user->hasRole('test-role'));
        $this->assertFalse($user->hasRole('non-existent-role'));
    }

    /**
     * Test that permissions can be created and assigned to roles.
     */
    public function test_roles_can_have_permissions(): void
    {
        $role = Role::create([
            'name' => 'test-role',
            'description' => 'Test Role',
        ]);

        $permission = Permission::create([
            'name' => 'test-permission',
            'description' => 'Test Permission',
        ]);

        $role->permissions()->attach($permission);

        $this->assertTrue($role->permissions->contains('name', 'test-permission'));
    }

    /**
     * Test that users inherit permissions from their roles.
     */
    public function test_user_has_permission_through_role(): void
    {
        $user = User::factory()->create();
        
        $permission = Permission::create([
            'name' => 'test-permission',
            'description' => 'Test Permission',
        ]);

        $role = Role::create([
            'name' => 'test-role',
            'description' => 'Test Role',
        ]);

        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $this->assertTrue($user->hasPermission('test-permission'));
        $this->assertFalse($user->hasPermission('non-existent-permission'));
    }

    /**
     * Test that user can have multiple roles.
     */
    public function test_user_can_have_multiple_roles(): void
    {
        $user = User::factory()->create();
        
        $role1 = Role::create(['name' => 'role1', 'description' => 'Role 1']);
        $role2 = Role::create(['name' => 'role2', 'description' => 'Role 2']);

        $user->roles()->attach([$role1->id, $role2->id]);

        $this->assertTrue($user->hasRole('role1'));
        $this->assertTrue($user->hasRole('role2'));
        $this->assertTrue($user->hasAnyRole(['role1', 'role2']));
        $this->assertTrue($user->hasAnyRole(['role1', 'non-existent']));
        $this->assertFalse($user->hasAnyRole(['non-existent']));
    }

    /**
     * Test that user inherits permissions from all their roles.
     */
    public function test_user_inherits_permissions_from_all_roles(): void
    {
        $user = User::factory()->create();
        
        $permission1 = Permission::create(['name' => 'permission1', 'description' => 'Permission 1']);
        $permission2 = Permission::create(['name' => 'permission2', 'description' => 'Permission 2']);

        $role1 = Role::create(['name' => 'role1', 'description' => 'Role 1']);
        $role2 = Role::create(['name' => 'role2', 'description' => 'Role 2']);

        $role1->permissions()->attach($permission1);
        $role2->permissions()->attach($permission2);

        $user->roles()->attach([$role1->id, $role2->id]);

        $this->assertTrue($user->hasPermission('permission1'));
        $this->assertTrue($user->hasPermission('permission2'));
        
        $allPermissions = $user->getAllPermissions();
        $this->assertCount(2, $allPermissions);
    }
}
