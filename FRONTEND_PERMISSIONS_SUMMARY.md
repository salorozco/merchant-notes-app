# Frontend Permissions Implementation Summary

## Overview

This document summarizes the implementation of role-based access control (RBAC) in the Vue.js frontend application.

## Changes Made

### Backend Changes

#### 1. UserResource Updated (`backend/app/Http/Resources/UserResource.php`)
- Added `roles` field returning array of role names
- Added `permissions` field returning array of permission names
- This ensures the frontend receives permission data when fetching user info

#### 2. AuthController Updated (`backend/app/Http/Controllers/AuthController.php`)
- Now uses `UserResource` for both login and user endpoints
- Ensures consistent data structure with roles and permissions

### Frontend Changes

#### 1. Permission Composable (`frontend/src/composables/usePermissions.js`)
**Purpose**: Provides reusable functions for permission checking

**Functions**:
- `hasPermission(permission)`: Check single permission
- `hasAnyPermission(permissions)`: Check if user has any of the permissions
- `hasAllPermissions(permissions)`: Check if user has all permissions
- `hasRole(role)`: Check if user has a specific role
- `hasAnyRole(roles)`: Check if user has any of the roles

**Computed Properties**:
- `canViewMerchants`, `canCreateMerchants`, `canUpdateMerchants`, `canDeleteMerchants`
- `canViewNotes`, `canCreateNotes`, `canUpdateNotes`, `canDeleteNotes`

#### 2. Auth Store Enhanced (`frontend/src/stores/authStore.js`)
**New Getters**:
- `isAuthenticated`: Returns true if user is logged in
- `userRoles`: Returns array of user roles
- `userPermissions`: Returns array of user permissions

**New Actions**:
- `hasPermission(permission)`: Check if current user has permission
- `hasRole(role)`: Check if current user has role

#### 3. Permission Directives (`frontend/src/directives/permissions.js`)
**v-permission**: Hides element if user lacks permission
```vue
<button v-permission="'create-merchants'">Create</button>
```

**v-role**: Hides element if user lacks role
```vue
<div v-role="'admin'">Admin Panel</div>
```

#### 4. Component Updates

**MerchantShow.vue**:
- Import `usePermissions` composable
- Add `canCreateNotes` check
- "Add Note" button only shows if user has `create-notes` permission
- Note form only renders if user has permission

**NoteItem.vue**:
- Import `usePermissions` composable
- Add `canUpdateNotes` and `canDeleteNotes` checks
- Edit button only shows if user has `update-notes` permission
- Delete button only shows if user has `delete-notes` permission

#### 5. Main App Configuration (`frontend/src/main.js`)
- Register `v-permission` directive globally
- Register `v-role` directive globally

#### 6. Documentation
- `PERMISSIONS_README.md`: Complete guide on using permissions
- `PERMISSION_EXAMPLES.md`: Code examples for common scenarios

## Usage Patterns

### Pattern 1: Using Composable
```vue
<template>
  <button v-if="canCreateNotes">Add Note</button>
</template>

<script>
import { usePermissions } from '@/composables/usePermissions';

export default {
  setup() {
    const { canCreateNotes } = usePermissions();
    return { canCreateNotes };
  }
}
</script>
```

### Pattern 2: Using Directive
```vue
<template>
  <button v-permission="'create-notes'">Add Note</button>
</template>
```

### Pattern 3: Programmatic Check
```javascript
methods: {
  createNote() {
    if (!this.hasPermission('create-notes')) {
      alert('No permission');
      return;
    }
    // Create note
  }
}
```

## Permissions Available

### Merchants
- `view-merchants`: View merchant list and details
- `create-merchants`: Create new merchants
- `update-merchants`: Update merchant information
- `delete-merchants`: Delete merchants

### Notes
- `view-notes`: View notes
- `create-notes`: Create new notes
- `update-notes`: Edit notes
- `delete-notes`: Delete notes

## Default Roles

### Admin
Full access - all 8 permissions

### Manager
Read/write access - 6 permissions:
- `view-merchants`, `create-merchants`, `update-merchants`
- `view-notes`, `create-notes`, `update-notes`

### User
Basic access - 3 permissions:
- `view-merchants`
- `view-notes`, `create-notes`

## UI Changes Examples

### Before Implementation
- All users saw "Add Note", "Edit", and "Delete" buttons regardless of permissions
- No role-based UI customization

### After Implementation
- "Add Note" button only visible to users with `create-notes` permission
- "Edit" button only visible to users with `update-notes` permission
- "Delete" button only visible to users with `delete-notes` permission
- UI adapts based on user's role and permissions

## Testing the Implementation

### To test with different permissions:

1. **Create test users with different roles**:
```php
// In Laravel tinker or seeder
$user = User::create([
    'name' => 'Test User',
    'email' => 'user@example.com',
    'password' => bcrypt('password')
]);

$role = Role::where('name', 'user')->first();
$user->roles()->attach($role);
```

2. **Log in as each user** to see different UI elements based on permissions

3. **Verify permissions in browser console**:
```javascript
// In browser console
const authStore = useAuthStore();
console.log(authStore.userPermissions);
console.log(authStore.userRoles);
```

## Benefits

1. **Improved Security**: Only show actions users can perform
2. **Better UX**: No confusion about what users can do
3. **Flexible**: Easy to add new permissions without changing code
4. **Maintainable**: Centralized permission logic
5. **Testable**: Clear functions to test permission checks
6. **Reusable**: Composable and directives work across all components

## Future Enhancements

Potential future improvements:
1. Permission-based route guards
2. Bulk permission operations
3. Permission caching for performance
4. Visual indicators for permission-restricted content
5. Permission audit logging
6. Dynamic permission loading
7. Permission presets for common roles

## Migration Guide

For existing components:

1. Import the composable:
   ```javascript
   import { usePermissions } from '@/composables/usePermissions';
   ```

2. Use in setup:
   ```javascript
   setup() {
     const { canCreateNotes } = usePermissions();
     return { canCreateNotes };
   }
   ```

3. Add to template:
   ```vue
   <button v-if="canCreateNotes">Add Note</button>
   ```

Or use directive:
```vue
<button v-permission="'create-notes'">Add Note</button>
```

## Troubleshooting

### Issue: Permissions not working
**Solution**: Check that backend is returning roles and permissions in UserResource

### Issue: Buttons still showing without permission
**Solution**: Ensure directives are registered in main.js

### Issue: Permission check always returns false
**Solution**: Verify user is logged in and data is loaded in authStore

### Issue: Stale permissions after role change
**Solution**: Call `authStore.fetchUser()` or have user log out and back in

## Files Changed

### Backend
- `app/Http/Resources/UserResource.php`
- `app/Http/Controllers/AuthController.php`

### Frontend
- `src/composables/usePermissions.js` (NEW)
- `src/directives/permissions.js` (NEW)
- `src/stores/authStore.js`
- `src/main.js`
- `src/views/MerchantShow.vue`
- `src/components/notes/NoteItem.vue`
- `PERMISSIONS_README.md` (NEW)
- `PERMISSION_EXAMPLES.md` (NEW)

## Conclusion

The frontend permission system is now fully integrated with the backend RBAC. Users will only see UI elements they have permission to use, improving both security and user experience.
