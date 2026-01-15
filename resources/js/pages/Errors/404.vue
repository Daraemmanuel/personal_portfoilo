<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Home, ArrowLeft } from 'lucide-vue-next';
</script>

<template>
    <Head title="404 - Page Not Found" />

    <div class="flex min-h-screen items-center justify-center bg-black px-6">
        <div class="text-center">
            <h1
                class="mb-4 bg-gradient-to-b from-white to-zinc-500 bg-clip-text text-9xl font-bold text-transparent"
            >
                404
            </h1>
            <h2 class="mb-4 text-2xl font-semibold text-white">
                Page Not Found
            </h2>
            <p class="mb-8 text-zinc-400">
                The page you're looking for doesn't exist or has been moved.
            </p>
            <div class="flex justify-center gap-4">
                <Link
                    :href="route('home')"
                    class="inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3 font-semibold text-black transition-all hover:scale-105"
                >
                    <Home class="h-5 w-5" />
                    Go Home
                </Link>
                <button
                    @click="$inertia.reload({ only: [] })"
                    class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-6 py-3 font-semibold text-white transition-all hover:bg-white/10"
                >
                    <ArrowLeft class="h-5 w-5" />
                    Go Back
                </button>
            </div>
        </div>
    </div>
</template>

