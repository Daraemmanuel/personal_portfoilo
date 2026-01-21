import '../css/app.css';

import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import AriaLiveRegion from './components/AriaLiveRegion.vue';
import ErrorBoundary from './components/ErrorBoundary.vue';
import GlobalConfirmDialog from './components/ui/GlobalConfirmDialog.vue';
import SkipLink from './components/SkipLink.vue';
import ToastContainer from './components/ui/ToastContainer.vue';
import { initializeTheme } from './composables/useAppearance';
import { useErrorTracking } from './composables/useErrorTracking';
import { usePerformanceMonitoring } from './composables/usePerformanceMonitoring';

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
                const offsetPosition =
                    elementPosition + window.pageYOffset - offset;

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
            render: () => h(ErrorBoundary, () => [
                h(SkipLink),
                h(AriaLiveRegion, { message: '' }),
                h(App, props),
                h(ToastContainer),
                h(GlobalConfirmDialog),
            ]),
        })
            .use(plugin)
            .use(ZiggyVue);

        // Custom Intersection Observer Directive
        app.directive('intersect', {
            mounted(el, binding) {
                const options = {
                    root: null,
                    rootMargin: '0px',
                    threshold: binding.value?.threshold || 0.1,
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            el.classList.add('active');
                            if (binding.modifiers.once) {
                                observer.unobserve(el);
                            }
                        } else if (!binding.modifiers.once) {
                            el.classList.remove('active');
                        }
                    });
                }, options);

                observer.observe(el);
                (el as HTMLElement & { _observer?: IntersectionObserver })._observer = observer;
            },
            unmounted(el) {
                const element = el as HTMLElement & { _observer?: IntersectionObserver };
                if (element._observer) {
                    element._observer.disconnect();
                }
            },
        });

        app.mount(el);

        // Handle initial page load with hash
        if (window.location.hash) {
            handleAnchorScroll();
        }
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();
