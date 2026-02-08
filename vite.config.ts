import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        tailwindcss(),

        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    build: {
        minify: 'esbuild', // esbuild is faster than terser
        cssMinify: true,
        sourcemap: false,
        chunkSizeWarningLimit: 1000,
        rollupOptions: {
            output: {
                manualChunks: (id) => {
                    // Vendor chunks
                    if (id.includes('node_modules')) {
                        if (id.includes('vue') || id.includes('@inertiajs')) {
                            return 'vendor-core';
                        }
                        if (id.includes('reka-ui') || id.includes('lucide-vue-next')) {
                            return 'vendor-ui';
                        }
                        if (id.includes('@tiptap')) {
                            return 'vendor-editor';
                        }
                        return 'vendor';
                    }
                },
                // Optimize chunk names
                chunkFileNames: 'js/[name]-[hash].js',
                entryFileNames: 'js/[name]-[hash].js',
                assetFileNames: 'assets/[name]-[hash].[ext]',
            },
        },
        // Tree shaking
        treeshake: true,
        // Reduce bundle size
        reportCompressedSize: false,
    },
    server: {
        host: '127.0.0.1', 
        port: 5173,
        strictPort: true,
    },
});
