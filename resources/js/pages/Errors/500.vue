<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Home, AlertCircle } from 'lucide-vue-next';
</script>

<template>
    <Head title="500 - Server Error" />

    <div class="flex min-h-screen items-center justify-center bg-black px-6">
        <div class="text-center">
            <AlertCircle
                class="mx-auto mb-4 h-16 w-16 text-red-500"
            />
            <h1
                class="mb-4 bg-gradient-to-b from-white to-zinc-500 bg-clip-text text-9xl font-bold text-transparent"
            >
                500
            </h1>
            <h2 class="mb-4 text-2xl font-semibold text-white">
                Server Error
            </h2>
            <p class="mb-8 text-zinc-400">
                Something went wrong on our end. We're working to fix it.
            </p>
            <div class="flex justify-center gap-4">
                <Link
                    :href="route('home')"
                    class="inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3 font-semibold text-black transition-all hover:scale-105"
                >
                    <Home class="h-5 w-5" />
                    Go Home
                </Link>
            </div>
        </div>
    </div>
</template>

