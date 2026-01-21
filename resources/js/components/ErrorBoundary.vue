<script setup lang="ts">
import { onErrorCaptured, ref, computed } from 'vue';
import { AlertCircle, Home, RefreshCw } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';

const error = ref<Error | null>(null);
const errorInfo = ref<string>('');

// Check if we're in development mode
const isDev = computed(() => import.meta.env.DEV);

onErrorCaptured((err: Error, instance, info) => {
    error.value = err;
    errorInfo.value = info;
    
    // Log error for debugging
    console.error('ErrorBoundary caught error:', {
        error: err,
        component: instance?.$options.name || 'Unknown',
        info,
    });

    // Prevent the error from propagating further
    return false;
});

const resetError = () => {
    error.value = null;
    errorInfo.value = '';
};
</script>

<template>
    <div v-if="error" class="flex min-h-screen items-center justify-center bg-background px-6">
        <div class="max-w-md text-center">
            <AlertCircle class="mx-auto mb-4 h-16 w-16 text-destructive" />
            <h1 class="mb-2 text-2xl font-bold text-foreground">
                Something went wrong
            </h1>
            <p class="mb-4 text-muted-foreground">
                An unexpected error occurred. Please try refreshing the page.
            </p>
            <div v-if="isDev" class="mb-4 rounded-lg bg-muted p-4 text-left">
                <p class="mb-2 font-mono text-sm font-semibold text-destructive">
                    {{ error.name }}: {{ error.message }}
                </p>
                <pre class="max-h-40 overflow-auto text-xs text-muted-foreground">{{ error.stack }}</pre>
                <p v-if="errorInfo" class="mt-2 text-xs text-muted-foreground">
                    Info: {{ errorInfo }}
                </p>
            </div>
            <div class="flex justify-center gap-4">
                <button
                    @click="resetError"
                    class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground transition-colors hover:bg-primary/90"
                >
                    <RefreshCw class="h-4 w-4" />
                    Try Again
                </button>
                <Link
                    :href="route('home')"
                    class="inline-flex items-center gap-2 rounded-lg border border-border bg-background px-4 py-2 text-sm font-semibold text-foreground transition-colors hover:bg-muted"
                >
                    <Home class="h-4 w-4" />
                    Go Home
                </Link>
            </div>
        </div>
    </div>
    <slot v-else />
</template>

