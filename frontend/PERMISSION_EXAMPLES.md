# Permission Usage Examples

This file contains complete examples of how to use permissions in Vue components.

## Example 1: Merchant Management Component

```vue
<template>
  <div class="merchant-management">
    <h1>Merchants</h1>
    
    <!-- Create button - only visible with create permission -->
    <button 
      v-if="canCreateMerchants"
      @click="showCreateForm = true"
      class="btn-primary"
    >
      Create Merchant
    </button>
    
    <!-- Alternative using directive -->
    <button 
      v-permission="'create-merchants'"
      @click="showCreateForm = true"
      class="btn-primary"
    >
      Create Merchant
    </button>
    
    <!-- Merchant list -->
    <div v-for="merchant in merchants" :key="merchant.id" class="merchant-card">
      <h3>{{ merchant.name }}</h3>
      
      <!-- Actions based on permissions -->
      <div class="actions">
        <button 
          v-if="canUpdateMerchants"
          @click="editMerchant(merchant)"
        >
          Edit
        </button>
        
        <button 
          v-if="canDeleteMerchants"
          @click="deleteMerchant(merchant.id)"
          class="btn-danger"
        >
          Delete
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';
import { usePermissions } from '@/composables/usePermissions';

export default {
  name: 'MerchantManagement',
  setup() {
    const {
      canCreateMerchants,
      canUpdateMerchants,
      canDeleteMerchants
    } = usePermissions();
    
    const merchants = ref([]);
    const showCreateForm = ref(false);
    
    return {
      merchants,
      showCreateForm,
      canCreateMerchants,
      canUpdateMerchants,
      canDeleteMerchants
    };
  },
  methods: {
    editMerchant(merchant) {
      // Edit logic
    },
    deleteMerchant(id) {
      // Delete logic
    }
  }
}
</script>
```

## Example 2: Admin Panel with Role Check

```vue
<template>
  <div>
    <!-- Only visible to admins -->
    <div v-role="'admin'" class="admin-panel">
      <h2>Admin Panel</h2>
      <button @click="manageUsers">Manage Users</button>
      <button @click="viewReports">View Reports</button>
    </div>
    
    <!-- Visible to admin or manager -->
    <div v-role="['admin', 'manager']" class="management-panel">
      <h2>Management Tools</h2>
      <button @click="exportData">Export Data</button>
    </div>
    
    <!-- Using composable for more complex logic -->
    <div v-if="hasAnyRole(['admin', 'manager'])">
      <h2>Dashboard</h2>
      <dashboard-stats />
    </div>
  </div>
</template>

<script>
import { usePermissions } from '@/composables/usePermissions';

export default {
  setup() {
    const { hasRole, hasAnyRole } = usePermissions();
    return { hasRole, hasAnyRole };
  }
}
</script>
```

## Example 3: Note Editor with Permission Guards

```vue
<template>
  <div class="note-editor">
    <div v-if="isEditing && canUpdateNotes">
      <textarea v-model="noteBody"></textarea>
      <button @click="saveNote">Save</button>
      <button @click="cancelEdit">Cancel</button>
    </div>
    
    <div v-else>
      <p>{{ note.body }}</p>
      
      <div class="actions">
        <!-- Edit button -->
        <button 
          v-permission="'update-notes'"
          @click="startEdit"
        >
          Edit
        </button>
        
        <!-- Delete button -->
        <button 
          v-permission="'delete-notes'"
          @click="confirmDelete"
          class="btn-danger"
        >
          Delete
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';
import { usePermissions } from '@/composables/usePermissions';

export default {
  props: {
    note: Object
  },
  setup() {
    const { canUpdateNotes, canDeleteNotes, hasPermission } = usePermissions();
    const isEditing = ref(false);
    const noteBody = ref('');
    
    return {
      isEditing,
      noteBody,
      canUpdateNotes,
      canDeleteNotes,
      hasPermission
    };
  },
  methods: {
    startEdit() {
      if (!this.hasPermission('update-notes')) {
        alert('You do not have permission to edit notes');
        return;
      }
      this.noteBody = this.note.body;
      this.isEditing = true;
    },
    async saveNote() {
      // Save logic
      this.isEditing = false;
    },
    cancelEdit() {
      this.isEditing = false;
    },
    async confirmDelete() {
      if (!this.hasPermission('delete-notes')) {
        alert('You do not have permission to delete notes');
        return;
      }
      if (confirm('Are you sure?')) {
        // Delete logic
      }
    }
  }
}
</script>
```

## Example 4: Navigation Menu with Permissions

```vue
<template>
  <nav>
    <ul>
      <!-- Always visible -->
      <li><router-link to="/">Home</router-link></li>
      
      <!-- Only show if user can view merchants -->
      <li v-permission="'view-merchants'">
        <router-link to="/merchants">Merchants</router-link>
      </li>
      
      <!-- Only show if user can view notes -->
      <li v-permission="'view-notes'">
        <router-link to="/notes">Notes</router-link>
      </li>
      
      <!-- Admin-only menu items -->
      <li v-role="'admin'">
        <router-link to="/admin">Admin</router-link>
      </li>
      
      <!-- Manager or Admin -->
      <li v-role="['admin', 'manager']">
        <router-link to="/reports">Reports</router-link>
      </li>
    </ul>
  </nav>
</template>
```

## Example 5: Programmatic Permission Checks in Methods

```vue
<script>
import { usePermissions } from '@/composables/usePermissions';

export default {
  setup() {
    const { hasPermission, hasAllPermissions } = usePermissions();
    
    return { hasPermission, hasAllPermissions };
  },
  methods: {
    async performAction() {
      // Check single permission
      if (!this.hasPermission('create-merchants')) {
        this.$toast.error('You do not have permission to create merchants');
        return;
      }
      
      // Proceed with action
      await this.createMerchant();
    },
    
    async performComplexAction() {
      // Check multiple permissions
      if (!this.hasAllPermissions(['create-merchants', 'update-merchants'])) {
        this.$toast.error('Insufficient permissions');
        return;
      }
      
      // Proceed with complex action
      await this.complexOperation();
    }
  }
}
</script>
```

## Example 6: Using Auth Store Directly

```vue
<script>
import { useAuthStore } from '@/stores/authStore';

export default {
  setup() {
    const authStore = useAuthStore();
    
    return { authStore };
  },
  computed: {
    userPermissions() {
      return this.authStore.userPermissions;
    },
    userRoles() {
      return this.authStore.userRoles;
    },
    isAdmin() {
      return this.authStore.hasRole('admin');
    }
  },
  methods: {
    checkAccess() {
      if (this.authStore.hasPermission('view-merchants')) {
        // Access granted
      }
    }
  }
}
</script>
```

## Example 7: Router Guard with Permissions

```javascript
// router/index.js
import { useAuthStore } from '@/stores/authStore';

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  
  // Check if route requires permissions
  if (to.meta.permissions) {
    const hasPermission = to.meta.permissions.some(permission =>
      authStore.hasPermission(permission)
    );
    
    if (!hasPermission) {
      next({ name: 'Unauthorized' });
      return;
    }
  }
  
  // Check if route requires roles
  if (to.meta.roles) {
    const hasRole = to.meta.roles.some(role =>
      authStore.hasRole(role)
    );
    
    if (!hasRole) {
      next({ name: 'Unauthorized' });
      return;
    }
  }
  
  next();
});

// Route definition
{
  path: '/admin',
  component: AdminPanel,
  meta: {
    roles: ['admin']
  }
}

{
  path: '/merchants/create',
  component: CreateMerchant,
  meta: {
    permissions: ['create-merchants']
  }
}
```

## Best Practices Summary

1. **Use v-if with computed properties from composable** for reactive UI updates
2. **Use v-permission directive** for simple show/hide scenarios
3. **Check permissions in methods** before performing actions
4. **Combine frontend and backend checks** - always enforce on backend
5. **Provide feedback** when users lack permissions
6. **Use router guards** for route-level protection
7. **Cache permission results** in computed properties for better performance
