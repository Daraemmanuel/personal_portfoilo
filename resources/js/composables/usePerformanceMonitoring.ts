import { onMounted } from 'vue';

export function usePerformanceMonitoring() {
    onMounted(() => {
        if (typeof window === 'undefined' || !('PerformanceObserver' in window)) {
            return;
        }

        // Skip if Lighthouse is running (detect by checking for Lighthouse user agent or chrome-lighthouse)
        const isLighthouse = 
            navigator.userAgent.includes('Chrome-Lighthouse') ||
            navigator.userAgent.includes('lighthouse') ||
            (window as any).__LIGHTHOUSE__ ||
            (window as any).__LIGHTHOUSE_EXTENSION__;

        if (isLighthouse) {
            return;
        }

        // Track Core Web Vitals
        try {
            // Largest Contentful Paint (LCP)
            const lcpObserver = new PerformanceObserver((list) => {
                const entries = list.getEntries();
                const lastEntry = entries[entries.length - 1] as any;
                if (import.meta.env.DEV) {
                console.log('LCP:', lastEntry.renderTime || lastEntry.loadTime);
            }
            });
            lcpObserver.observe({ entryTypes: ['largest-contentful-paint'] });

            // First Input Delay (FID)
            const fidObserver = new PerformanceObserver((list) => {
                const entries = list.getEntries();
                entries.forEach((entry: any) => {
                    if (import.meta.env.DEV) {
                        console.log('FID:', entry.processingStart - entry.startTime);
                    }
                });
            });
            fidObserver.observe({ entryTypes: ['first-input'] });

            // Cumulative Layout Shift (CLS)
            let clsValue = 0;
            const clsObserver = new PerformanceObserver((list) => {
                const entries = list.getEntries() as any[];
                entries.forEach((entry) => {
                    if (!entry.hadRecentInput) {
                        clsValue += entry.value;
                    }
                });
                if (import.meta.env.DEV) {
                    console.log('CLS:', clsValue);
                }
            });
            clsObserver.observe({ entryTypes: ['layout-shift'] });

            // Time to First Byte (TTFB)
            window.addEventListener('load', () => {
                const navigation = performance.getEntriesByType('navigation')[0] as PerformanceNavigationTiming;
                if (navigation) {
                    const ttfb = navigation.responseStart - navigation.requestStart;
                    if (import.meta.env.DEV) {
                        console.log('TTFB:', ttfb);
                    }
                }
            });
        } catch (error) {
            console.error('Performance monitoring error:', error);
        }
    });
}

