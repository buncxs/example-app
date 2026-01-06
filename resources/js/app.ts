import '../css/app.css';
import '../sass/custom.scss';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { initializeTheme } from './composables/useAppearance';
import i18next from 'i18next';
import { zodI18nMap } from 'zod-i18n-map';
import translation from 'zod-i18n-map/locales/es/zod.json';
import { z } from 'zod';


const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// --- CONFIGURACIÓN DE TRADUCCIÓN ---
i18next.init({
    lng: 'es',
    resources: {
        es: { zod: translation },
    },
});

// Le decimos a Zod que use los mensajes de i18next por defecto
z.setErrorMap(zodI18nMap);
// -----------------------------------



createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();
