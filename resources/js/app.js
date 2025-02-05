import './bootstrap';
import '../css/app.css';

import {createApp, h, ref} from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/index.esm.js';
import store from './store.js';
import router from './router.js';

import ChatMessages from './components/ChatMessages.vue';
import ChatForm from './components/ChatForm.vue';
import axios from 'axios';

const echo = window.Echo;

// Reactive messages array
const messages = ref([]);

createInertiaApp({
    title: (title) => `${title} - PIN`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(store)
            .use(router);

        // Register components globally
        app.component('chat-messages', ChatMessages);
        app.component('chat-form', ChatForm);

        // Register Echo event listener for presence channel
        echo.private('chat').listen('MessageSent', (e) => {
            console.log("New message received:", e.message);
            messages.value.push({
                message: e.message.message,
                user: e.user
            });
        });

        app.provide('messages', messages); // Provide reactive data globally

        // Provide a global event bus for real-time messaging (Optional)
        app.config.globalProperties.$axios = axios;

        return app.mount(el);
    },
    progress: {
        color: '#F7C806',
    },
});

