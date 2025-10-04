import { useAuthStore } from '../stores/authStore';

export const vPermission = {
    mounted(el, binding) {
        const authStore = useAuthStore();
        const { value } = binding;

        if (!value) {
            return;
        }

        const hasPermission = () => {
            if (!authStore.user || !authStore.user.permissions) {
                return false;
            }

            if (Array.isArray(value)) {
                // Check if user has any of the permissions
                return value.some(permission => 
                    authStore.user.permissions.includes(permission)
                );
            } else {
                // Check single permission
                return authStore.user.permissions.includes(value);
            }
        };

        if (!hasPermission()) {
            el.style.display = 'none';
        }
    }
};

export const vRole = {
    mounted(el, binding) {
        const authStore = useAuthStore();
        const { value } = binding;

        if (!value) {
            return;
        }

        const hasRole = () => {
            if (!authStore.user || !authStore.user.roles) {
                return false;
            }

            if (Array.isArray(value)) {
                // Check if user has any of the roles
                return value.some(role => authStore.user.roles.includes(role));
            } else {
                // Check single role
                return authStore.user.roles.includes(value);
            }
        };

        if (!hasRole()) {
            el.style.display = 'none';
        }
    }
};
