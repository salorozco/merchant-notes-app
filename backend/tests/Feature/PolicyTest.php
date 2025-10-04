<?php

namespace Tests\Feature;

use App\Models\Merchant;
use App\Models\Note;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PolicyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create permissions
        $merchantPermissions = [
            'view-merchants',
            'create-merchants',
            'update-merchants',
            'delete-merchants',
        ];

        $notePermissions = [
            'view-notes',
            'create-notes',
            'update-notes',
            'delete-notes',
        ];

        foreach (array_merge($merchantPermissions, $notePermissions) as $permissionName) {
            Permission::create([
                'name' => $permissionName,
                'description' => ucfirst(str_replace('-', ' ', $permissionName)),
            ]);
        }
    }

    /**
     * Test MerchantPolicy viewAny authorization.
     */
    public function test_merchant_policy_view_any(): void
    {
        $user = User::factory()->create();
        $permission = Permission::where('name', 'view-merchants')->first();
        $role = Role::create(['name' => 'viewer', 'description' => 'Viewer']);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $this->assertTrue($user->can('viewAny', Merchant::class));
    }

    /**
     * Test MerchantPolicy view authorization.
     */
    public function test_merchant_policy_view(): void
    {
        $user = User::factory()->create();
        $merchant = Merchant::factory()->create(['user_id' => $user->id]);
        
        $permission = Permission::where('name', 'view-merchants')->first();
        $role = Role::create(['name' => 'viewer', 'description' => 'Viewer']);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $this->assertTrue($user->can('view', $merchant));
    }

    /**
     * Test MerchantPolicy create authorization.
     */
    public function test_merchant_policy_create(): void
    {
        $user = User::factory()->create();
        
        $permission = Permission::where('name', 'create-merchants')->first();
        $role = Role::create(['name' => 'creator', 'description' => 'Creator']);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $this->assertTrue($user->can('create', Merchant::class));
    }

    /**
     * Test MerchantPolicy update authorization.
     */
    public function test_merchant_policy_update(): void
    {
        $user = User::factory()->create();
        $merchant = Merchant::factory()->create(['user_id' => $user->id]);
        
        $permission = Permission::where('name', 'update-merchants')->first();
        $role = Role::create(['name' => 'editor', 'description' => 'Editor']);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $this->assertTrue($user->can('update', $merchant));
    }

    /**
     * Test MerchantPolicy delete authorization.
     */
    public function test_merchant_policy_delete(): void
    {
        $user = User::factory()->create();
        $merchant = Merchant::factory()->create(['user_id' => $user->id]);
        
        $permission = Permission::where('name', 'delete-merchants')->first();
        $role = Role::create(['name' => 'deleter', 'description' => 'Deleter']);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $this->assertTrue($user->can('delete', $merchant));
    }

    /**
     * Test NotePolicy viewAny authorization.
     */
    public function test_note_policy_view_any(): void
    {
        $user = User::factory()->create();
        $permission = Permission::where('name', 'view-notes')->first();
        $role = Role::create(['name' => 'viewer', 'description' => 'Viewer']);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $this->assertTrue($user->can('viewAny', Note::class));
    }

    /**
     * Test NotePolicy create authorization.
     */
    public function test_note_policy_create(): void
    {
        $user = User::factory()->create();
        
        $permission = Permission::where('name', 'create-notes')->first();
        $role = Role::create(['name' => 'creator', 'description' => 'Creator']);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $this->assertTrue($user->can('create', Note::class));
    }

    /**
     * Test user without permission is denied.
     */
    public function test_user_without_permission_is_denied(): void
    {
        $user = User::factory()->create();
        $merchant = Merchant::factory()->create(['user_id' => $user->id]);

        $this->assertFalse($user->can('view', $merchant));
        $this->assertFalse($user->can('create', Merchant::class));
        $this->assertFalse($user->can('update', $merchant));
        $this->assertFalse($user->can('delete', $merchant));
    }
}
