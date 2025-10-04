# Frontend Permissions Implementation

This document describes how to use the role-based access control (RBAC) system in the Vue.js frontend.

## Overview

The frontend permissions system integrates with the backend RBAC to provide permission checks in the Vue application. Users' roles and permissions are loaded from the backend and stored in the Pinia auth store.

## Components

### 1. Auth Store (`stores/authStore.js`)

The auth store has been enhanced to include:
- `userRoles`: Getter that returns an array of role names
- `userPermissions`: Getter that returns an array of permission names
- `hasPermission(permission)`: Method to check if user has a specific permission
- `hasRole(role)`: Method to check if user has a specific role

### 2. Permissions Composable (`composables/usePermissions.js`)

A Vue composable that provides permission checking functions:

```javascript
import { usePermissions } from '@/composables/usePermissions';

const {
  hasPermission,
  hasAnyPermission,
  hasAllPermissions,
  hasRole,
  hasAnyRole,
  canViewMerchants,
  canCreateMerchants,
  canUpdateMerchants,
  canDeleteMerchants,
  canViewNotes,
  canCreateNotes,
  canUpdateNotes,
  canDeleteNotes
} = usePermissions();
```

### 3. Permission Directives (`directives/permissions.js`)

Custom Vue directives for declarative permission checks in templates:

- `v-permission`: Hide element if user doesn't have the permission
- `v-role`: Hide element if user doesn't have the role

## Usage Examples

### Using Composable in Component

```vue
<template>
  <button v-if="canCreateNotes" @click="createNote">
    Add Note
  </button>
  
  <button v-if="canDeleteNotes" @click="deleteNote">
    Delete
  </button>
</template>

<script>
import { usePermissions } from '@/composables/usePermissions';

export default {
  setup() {
    const { canCreateNotes, canDeleteNotes } = usePermissions();
    return { canCreateNotes, canDeleteNotes };
  }
}
</script>
```

### Using hasPermission Function

```vue
<script>
import { usePermissions } from '@/composables/usePermissions';

export default {
  setup() {
    const { hasPermission } = usePermissions();
    
    const canEdit = () => {
      return hasPermission('update-merchants');
    };
    
    return { hasPermission, canEdit };
  }
}
</script>
```

### Using Permission Directive

```vue
<template>
  <!-- Single permission -->
  <button v-permission="'create-merchants'" @click="createMerchant">
    Create Merchant
  </button>
  
  <!-- Multiple permissions (any) -->
  <button v-permission="['update-merchants', 'delete-merchants']" @click="manageMerchant">
    Manage
  </button>
</template>
```

### Using Role Directive

```vue
<template>
  <!-- Single role -->
  <div v-role="'admin'">
    Admin Panel
  </div>
  
  <!-- Multiple roles (any) -->
  <div v-role="['admin', 'manager']">
    Management Panel
  </div>
</template>
```

### Checking Multiple Permissions

```javascript
import { usePermissions } from '@/composables/usePermissions';

const { hasAnyPermission, hasAllPermissions } = usePermissions();

// Check if user has any of these permissions
if (hasAnyPermission(['view-merchants', 'view-notes'])) {
  // User can view either merchants or notes
}

// Check if user has all of these permissions
if (hasAllPermissions(['create-merchants', 'update-merchants'])) {
  // User can both create and update merchants
}
```

### Using in Auth Store Directly

```javascript
import { useAuthStore } from '@/stores/authStore';

const authStore = useAuthStore();

if (authStore.hasPermission('create-notes')) {
  // Allow creation
}

if (authStore.hasRole('admin')) {
  // Admin-only functionality
}
```

## Available Permissions

### Merchant Permissions
- `view-merchants`: View merchant list and details
- `create-merchants`: Create new merchants
- `update-merchants`: Edit merchant information
- `delete-merchants`: Delete merchants

### Note Permissions
- `view-notes`: View notes
- `create-notes`: Create new notes
- `update-notes`: Edit existing notes
- `delete-notes`: Delete notes

## Default Roles

### Admin
- All permissions

### Manager
- `view-merchants`, `create-merchants`, `update-merchants`
- `view-notes`, `create-notes`, `update-notes`

### User
- `view-merchants`
- `view-notes`, `create-notes`

## Component Examples

### Conditional Rendering Based on Permissions

```vue
<template>
  <div>
    <!-- Always visible -->
    <h1>{{ merchant.name }}</h1>
    
    <!-- Only visible if user can update -->
    <button v-if="canUpdateMerchants" @click="edit">
      Edit
    </button>
    
    <!-- Only visible if user can delete -->
    <button v-if="canDeleteMerchants" @click="remove">
      Delete
    </button>
    
    <!-- Using directive (alternative approach) -->
    <button v-permission="'update-merchants'" @click="edit">
      Edit
    </button>
  </div>
</template>

<script>
import { usePermissions } from '@/composables/usePermissions';

export default {
  setup() {
    const { canUpdateMerchants, canDeleteMerchants } = usePermissions();
    return { canUpdateMerchants, canDeleteMerchants };
  },
  methods: {
    edit() {
      // Edit logic
    },
    remove() {
      // Delete logic
    }
  }
}
</script>
```

### Programmatic Permission Checks

```javascript
methods: {
  async saveChanges() {
    const { hasPermission } = usePermissions();
    
    if (!hasPermission('update-merchants')) {
      alert('You do not have permission to update merchants');
      return;
    }
    
    // Proceed with save
    await this.saveMerchant();
  }
}
```

## Best Practices

1. **Use Composable for Logic**: Use the `usePermissions` composable in your component's setup function for reactive permission checks.

2. **Use Directives for Simple UI**: Use `v-permission` or `v-role` directives for simple show/hide scenarios in templates.

3. **Check Permissions Before Actions**: Always verify permissions before performing actions, not just in the UI.

4. **Fallback Gracefully**: Provide appropriate feedback when users don't have permissions (e.g., hide buttons, show messages).

5. **Keep UI Consistent**: If a user can't perform an action, don't show them the UI for it.

6. **Combine with Backend**: Always enforce permissions on the backend as well. Frontend checks are for UX only.

## Troubleshooting

### Permissions Not Working

1. **Check User Data**: Ensure the user object in the auth store contains `roles` and `permissions` arrays:
   ```javascript
   console.log(authStore.user);
   // Should show: { id, name, email, roles: [...], permissions: [...] }
   ```

2. **Verify API Response**: Check that the backend is returning roles and permissions in the user response.

3. **Clear Persisted Store**: If permissions seem stale, clear the persisted auth store:
   ```javascript
   localStorage.removeItem('auth-store');
   ```

4. **Refresh User Data**: Call `authStore.fetchUser()` to reload user data from the backend.

### Directive Not Hiding Elements

1. Ensure directives are registered in `main.js`
2. Check that the permission name matches exactly (case-sensitive)
3. Verify the user is logged in and has loaded data

## Integration with Backend

The frontend automatically receives roles and permissions when:
1. User logs in (via `/login` endpoint)
2. User data is fetched (via `/user` endpoint)

The backend `UserResource` includes:
```php
[
    'id' => $this->id,
    'name' => $this->name,
    'email' => $this->email,
    'roles' => $this->roles->pluck('name'),
    'permissions' => $this->getAllPermissions()->pluck('name'),
    // ... other fields
]
```

This ensures the frontend always has the latest permission data.
