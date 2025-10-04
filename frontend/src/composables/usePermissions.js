import { computed } from 'vue';
import { useAuthStore } from '../stores/authStore';

export function usePermissions() {
    const authStore = useAuthStore();

    const hasPermission = (permission) => {
        if (!authStore.user || !authStore.user.permissions) {
            return false;
        }
        return authStore.user.permissions.includes(permission);
    };

    const hasAnyPermission = (permissions) => {
        if (!authStore.user || !authStore.user.permissions) {
            return false;
        }
        return permissions.some(permission => 
            authStore.user.permissions.includes(permission)
        );
    };

    const hasAllPermissions = (permissions) => {
        if (!authStore.user || !authStore.user.permissions) {
            return false;
        }
        return permissions.every(permission => 
            authStore.user.permissions.includes(permission)
        );
    };

    const hasRole = (role) => {
        if (!authStore.user || !authStore.user.roles) {
            return false;
        }
        return authStore.user.roles.includes(role);
    };

    const hasAnyRole = (roles) => {
        if (!authStore.user || !authStore.user.roles) {
            return false;
        }
        return roles.some(role => authStore.user.roles.includes(role));
    };

    // Computed properties for common permissions
    const canViewMerchants = computed(() => hasPermission('view-merchants'));
    const canCreateMerchants = computed(() => hasPermission('create-merchants'));
    const canUpdateMerchants = computed(() => hasPermission('update-merchants'));
    const canDeleteMerchants = computed(() => hasPermission('delete-merchants'));

    const canViewNotes = computed(() => hasPermission('view-notes'));
    const canCreateNotes = computed(() => hasPermission('create-notes'));
    const canUpdateNotes = computed(() => hasPermission('update-notes'));
    const canDeleteNotes = computed(() => hasPermission('delete-notes'));

    return {
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
        canDeleteNotes,
    };
}
