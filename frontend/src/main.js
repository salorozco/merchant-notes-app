import { createApp } from 'vue'
import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';
import './assets/main.css';

import App from './App.vue'
import router from './router'
import { vPermission, vRole } from './directives/permissions';

const app = createApp(App)

const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);

app.use(pinia);
app.use(router)

// Register permission directives globally
app.directive('permission', vPermission);
app.directive('role', vRole);

app.mount('#app')
