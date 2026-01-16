import { onMounted } from 'vue';

export function useErrorTracking() {
    onMounted(() => {
        if (typeof window === 'undefined') {
            return;
        }

        // Global error handler
        window.addEventListener('error', (event) => {
            console.error('Global error:', event.error);
            
            // Send to error tracking service if configured
            if (import.meta.env.VITE_SENTRY_DSN) {
                // Sentry integration would go here
                // Example: Sentry.captureException(event.error);
            }

            // Log to console in development
            if (import.meta.env.DEV) {
                console.error('Error details:', {
                    message: event.message,
                    filename: event.filename,
                    lineno: event.lineno,
                    colno: event.colno,
                    error: event.error,
                });
            }
        });

        // Unhandled promise rejection handler
        window.addEventListener('unhandledrejection', (event) => {
            console.error('Unhandled promise rejection:', event.reason);
            
            // Send to error tracking service if configured
            if (import.meta.env.VITE_SENTRY_DSN) {
                // Sentry integration would go here
                // Example: Sentry.captureException(event.reason);
            }

            // Log to console in development
            if (import.meta.env.DEV) {
                console.error('Promise rejection details:', {
                    reason: event.reason,
                    promise: event.promise,
                });
            }
        });
    });
}

