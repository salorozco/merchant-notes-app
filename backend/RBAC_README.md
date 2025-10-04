# Role-Based Access Control (RBAC) Implementation

This document describes the Role-Based Access Control (RBAC) system implemented in the merchant-notes-app backend.

## Overview

The RBAC system provides a flexible way to manage user permissions through roles. Users can be assigned multiple roles, and each role can have multiple permissions. The system uses Laravel's built-in authorization features with custom policies.

## Database Structure

### Tables

- **roles**: Stores role information
  - `id`: Primary key
  - `name`: Unique role name (e.g., 'admin', 'manager', 'user')
  - `description`: Optional description of the role
  - `timestamps`: Created at and updated at timestamps

- **permissions**: Stores permission information
  - `id`: Primary key
  - `name`: Unique permission name (e.g., 'view-merchants', 'create-notes')
  - `description`: Optional description of the permission

- **role_user**: Pivot table linking users to roles
  - `role_id`: Foreign key to roles table
  - `user_id`: Foreign key to users table

- **permission_role**: Pivot table linking permissions to roles
  - `permission_id`: Foreign key to permissions table
  - `role_id`: Foreign key to roles table

## Models

### Role Model
- Location: `app/Models/Role.php`
- Relationships:
  - `users()`: Many-to-many relationship with User
  - `permissions()`: Many-to-many relationship with Permission

### Permission Model
- Location: `app/Models/Permission.php`
- Relationships:
  - `roles()`: Many-to-many relationship with Role

### User Model (Extended)
- Location: `app/Models/User.php`
- New Relationships:
  - `roles()`: Many-to-many relationship with Role
- Helper Methods:
  - `hasRole(string $roleName)`: Check if user has a specific role
  - `hasAnyRole(array $roles)`: Check if user has any of the given roles
  - `hasPermission(string $permissionName)`: Check if user has a specific permission
  - `getAllPermissions()`: Get all permissions for the user through their roles

## Policies

### MerchantPolicy
- Location: `app/Policies/MerchantPolicy.php`
- Methods:
  - `viewAny()`: Requires 'view-merchants' permission
  - `view()`: Requires 'view-merchants' permission
  - `create()`: Requires 'create-merchants' permission
  - `update()`: Requires 'update-merchants' permission
  - `delete()`: Requires 'delete-merchants' permission

### NotePolicy
- Location: `app/Policies/NotePolicy.php`
- Methods:
  - `viewAny()`: Requires 'view-notes' permission
  - `view()`: Requires 'view-notes' permission
  - `create()`: Requires 'create-notes' permission
  - `update()`: Requires 'update-notes' permission
  - `delete()`: Requires 'delete-notes' permission

## Default Roles and Permissions

The seeder creates three default roles:

### Admin Role
- Full access to all resources
- Permissions:
  - view-merchants, create-merchants, update-merchants, delete-merchants
  - view-notes, create-notes, update-notes, delete-notes

### Manager Role
- Read and write access (no delete)
- Permissions:
  - view-merchants, create-merchants, update-merchants
  - view-notes, create-notes, update-notes

### User Role
- Basic read access and limited write access
- Permissions:
  - view-merchants
  - view-notes, create-notes

## Usage

### Seeding Roles and Permissions
```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
```

### Assigning Roles to Users
```php
$user = User::find(1);
$role = Role::where('name', 'admin')->first();
$user->roles()->attach($role);
```

### Checking Permissions in Code
```php
// Check if user has a specific role
if ($user->hasRole('admin')) {
    // Do something
}

// Check if user has a specific permission
if ($user->hasPermission('create-merchants')) {
    // Allow creation
}

// Check multiple roles
if ($user->hasAnyRole(['admin', 'manager'])) {
    // Do something
}
```

### Using Policies in Controllers
```php
// Check authorization before performing action
$this->authorize('create', Merchant::class);

// Or use the Gate facade
if (Gate::allows('update', $merchant)) {
    // Update the merchant
}

// Or use the can method on the user
if ($user->can('delete', $merchant)) {
    // Delete the merchant
}
```

### Using Policies in Blade Views
```blade
@can('create', App\Models\Merchant::class)
    <button>Create Merchant</button>
@endcan

@can('update', $merchant)
    <button>Edit Merchant</button>
@endcan
```

## Migrations

To run the RBAC migrations:
```bash
php artisan migrate
```

The following migrations are included:
- `create_roles_table.php`
- `create_permissions_table.php`
- `create_role_user_table.php`
- `create_permission_role_table.php`

## Testing

Tests are available in:
- `tests/Feature/RoleBasedAccessControlTest.php`: Tests for roles, permissions, and user relationships
- `tests/Feature/PolicyTest.php`: Tests for policy authorization

Run tests with:
```bash
php artisan test --filter=RoleBasedAccessControlTest
php artisan test --filter=PolicyTest
```

## Extending the System

### Adding New Permissions
```php
Permission::create([
    'name' => 'export-reports',
    'description' => 'Export reports to CSV/PDF'
]);
```

### Creating New Roles
```php
$role = Role::create([
    'name' => 'analyst',
    'description' => 'Data analyst with read-only access'
]);

$permissions = Permission::whereIn('name', ['view-merchants', 'view-notes'])->get();
$role->permissions()->attach($permissions);
```

### Creating Custom Policies
```bash
php artisan make:policy ReportPolicy --model=Report
```

Then implement the policy methods to check for appropriate permissions using `$user->hasPermission()`.

## Best Practices

1. Always check permissions through policies rather than directly checking roles
2. Use descriptive permission names that follow the pattern: `{action}-{resource}` (e.g., 'view-merchants', 'create-notes')
3. Assign permissions to roles, not directly to users
4. Keep the principle of least privilege - only grant permissions that are necessary
5. Document any custom permissions or roles you create
6. Test authorization logic thoroughly
