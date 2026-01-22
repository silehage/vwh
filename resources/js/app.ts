import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';

import { Quasar, Dialog, Notify } from 'quasar'

import AuthLayout from './layouts/AuthLayout.vue';
import AppLayout from './layouts/AppLayout.vue';
import AppHeader from './components/Header.vue';
import AppPagination from './components/Pagination.vue';
import TableContainer from '@/components/TableContainer.vue';

// Import icon libraries
import '@quasar/extras/material-icons/material-icons.css'

// Import Quasar css
import 'quasar/src/css/index.sass'

import '../css/app.scss';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: async (name) => {
        const module = await resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue'))
        if (name.startsWith('Auth')) {
            module.default.layout = AuthLayout
        }

        module.default.layout = module.default.layout || AppLayout
        console.log(module);
        return module
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
             .use(Quasar, {
                plugins: {
                    Dialog, Notify
                }, 
            })
            .component('AppHeader', AppHeader)
            .component('AppPagination', AppPagination)
            .component('TableContainer', TableContainer)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
