import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/index.esm.js';
import vue3GoogleLogin from 'vue3-google-login';
import store from './store.js';
import router from './router.js';

const appNameShort = import.meta.env.VITE_APP_NAME_SHORT;

createInertiaApp({
    title: (title) => `${title} - ${appNameShort}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(store)
            .use(router);


        app.use(vue3GoogleLogin, {
            clientId: import.meta.env.VITE_GOOGLE_CLIENT_ID
        });

        // Set global property
        app.config.globalProperties.$appVersion = import.meta.env.VITE_APP_VERSION;
        app.config.globalProperties.$appName = import.meta.env.VITE_APP_NAME;
        app.config.globalProperties.$appNameShort = appNameShort;
        app.config.globalProperties.$companyName = import.meta.env.VITE_COMPANY_NAME;
        app.config.globalProperties.$companyNameShort = import.meta.env.VITE_COMPANY_NAME_SHORT;

        return app.mount(el);
    },
    progress: {
        color: '#F7C806',
    },
});
