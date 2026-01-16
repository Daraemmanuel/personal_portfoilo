<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Folder } from 'lucide-vue-next';
import LandingNav from '@/components/landing/LandingNav.vue';

defineProps<{
    categories: Array<{
        name: string;
        count: number;
    }>;
}>();
</script>

<template>
    <Head title="Categories - Articles" />

    <div
        class="min-h-screen bg-black font-sans text-white antialiased selection:bg-indigo-500 selection:text-white"
    >
        <LandingNav />

        <div class="mx-auto max-w-6xl px-6 py-24">
            <header class="mb-12 text-center">
                <h1
                    class="mb-4 bg-gradient-to-b from-white to-zinc-500 bg-clip-text text-4xl font-bold text-transparent md:text-5xl"
                >
                    Categories
                </h1>
                <p class="text-zinc-400">
                    Browse articles by category
                </p>
            </header>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                <Link
                    v-for="category in categories"
                    :key="category.name"
                    :href="route('articles.category', category.name)"
                    class="group overflow-hidden rounded-2xl border border-white/5 bg-zinc-900 p-6 transition-all hover:border-indigo-500/50 hover:bg-zinc-800"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <Folder class="h-6 w-6 text-indigo-400" />
                            <div>
                                <h3
                                    class="text-lg font-semibold transition-colors group-hover:text-indigo-400"
                                >
                                    {{ category.name }}
                                </h3>
                                <p class="text-sm text-zinc-500">
                                    {{ category.count }} article{{ category.count !== 1 ? 's' : '' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </Link>
            </div>

            <div v-if="categories.length === 0" class="text-center text-zinc-500">
                <Folder class="mx-auto mb-4 h-12 w-12 opacity-50" />
                <p>No categories yet</p>
            </div>
        </div>
    </div>
</template>

