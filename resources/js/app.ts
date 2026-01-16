import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';
import { usePerformanceMonitoring } from './composables/usePerformanceMonitoring';
import { useErrorTracking } from './composables/useErrorTracking';
import ToastContainer from './components/ui/ToastContainer.vue';
import GlobalConfirmDialog from './components/ui/GlobalConfirmDialog.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Handle scrolling to anchor links after Inertia navigation
const handleAnchorScroll = () => {
    const hash = window.location.hash;
    if (hash) {
        setTimeout(() => {
            const element = document.querySelector(hash);
            if (element) {
                const offset = 80; // Account for fixed navbar
                const elementPosition = element.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - offset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth',
                });
            }
        }, 150);
    }
};

// Listen for Inertia page visits
router.on('finish', () => {
    handleAnchorScroll();
});

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        // Initialize performance monitoring
        usePerformanceMonitoring();
        
        // Initialize error tracking
        useErrorTracking();

        const app = createApp({
            render: () => [
                h(App, props),
                h(ToastContainer),
                h(GlobalConfirmDialog),
            ],
        })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);

        // Handle initial page load with hash
        if (window.location.hash) {
            handleAnchorScroll();
        }

        return app;
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();
