import { onMounted } from 'vue';
import { router } from '@inertiajs/vue3';

export function useErrorTracking() {
    onMounted(() => {
        if (typeof window === 'undefined') {
            return;
        }

        // Global error handler
        window.addEventListener('error', (event) => {
            const errorDetails = {
                message: event.message,
                filename: event.filename,
                lineno: event.lineno,
                colno: event.colno,
                error: event.error,
                stack: event.error?.stack,
                url: window.location.href,
                userAgent: navigator.userAgent,
                timestamp: new Date().toISOString(),
            };

            console.error('Global error:', errorDetails);
            
            // Send to error tracking service if configured
            if (import.meta.env.VITE_SENTRY_DSN) {
                // Sentry integration would go here
                // Example: Sentry.captureException(event.error, { extra: errorDetails });
            }

            // In production, you might want to send to your logging service
            if (import.meta.env.PROD) {
                // Example: Send to your logging endpoint
                // fetch('/api/log-error', { method: 'POST', body: JSON.stringify(errorDetails) });
            }

            // Log to console in development
            if (import.meta.env.DEV) {
                console.error('Error details:', errorDetails);
            }
        });

        // Unhandled promise rejection handler
        window.addEventListener('unhandledrejection', (event) => {
            const rejectionDetails = {
                reason: event.reason,
                promise: event.promise,
                url: window.location.href,
                userAgent: navigator.userAgent,
                timestamp: new Date().toISOString(),
            };

            console.error('Unhandled promise rejection:', rejectionDetails);
            
            // Send to error tracking service if configured
            if (import.meta.env.VITE_SENTRY_DSN) {
                // Sentry integration would go here
                // Example: Sentry.captureException(event.reason, { extra: rejectionDetails });
            }

            // In production, you might want to send to your logging service
            if (import.meta.env.PROD) {
                // Example: Send to your logging endpoint
                // fetch('/api/log-error', { method: 'POST', body: JSON.stringify(rejectionDetails) });
            }

            // Log to console in development
            if (import.meta.env.DEV) {
                console.error('Promise rejection details:', rejectionDetails);
            }
        });

        // Handle Inertia errors
        router.on('error', (event) => {
            const inertiaError = {
                errors: event.detail.errors,
                url: window.location.href,
                timestamp: new Date().toISOString(),
            };

            console.error('Inertia error:', inertiaError);

            if (import.meta.env.VITE_SENTRY_DSN) {
                // Sentry integration would go here
            }
        });

        // Handle Inertia exceptions
        router.on('exception', (event) => {
            const inertiaException = {
                exception: event.detail.exception,
                url: window.location.href,
                timestamp: new Date().toISOString(),
            };

            console.error('Inertia exception:', inertiaException);

            if (import.meta.env.VITE_SENTRY_DSN) {
                // Sentry integration would go here
            }
        });
    });
}

