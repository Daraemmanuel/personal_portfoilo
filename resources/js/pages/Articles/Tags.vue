<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Tag as TagIcon } from 'lucide-vue-next';
import LandingNav from '@/components/landing/LandingNav.vue';

defineProps<{
    tags: Array<{
        name: string;
        count: number;
    }>;
}>();
</script>

<template>
    <Head title="Tags - Articles" />

    <div
        class="min-h-screen bg-black font-sans text-white antialiased selection:bg-indigo-500 selection:text-white"
    >
        <LandingNav />

        <div class="mx-auto max-w-6xl px-6 py-24">
            <header class="mb-12 text-center">
                <h1
                    class="mb-4 bg-gradient-to-b from-white to-zinc-500 bg-clip-text text-4xl font-bold text-transparent md:text-5xl"
                >
                    Tags
                </h1>
                <p class="text-zinc-400">
                    Browse articles by tag
                </p>
            </header>

            <div class="flex flex-wrap gap-3">
                <Link
                    v-for="tag in tags"
                    :key="tag.name"
                    :href="route('articles.tag', tag.name)"
                    class="group inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-zinc-300 transition-all hover:border-indigo-500/50 hover:bg-indigo-500/10 hover:text-indigo-400"
                >
                    <TagIcon class="h-4 w-4" />
                    <span>#{{ tag.name }}</span>
                    <span class="rounded-full bg-white/10 px-2 py-0.5 text-xs">
                        {{ tag.count }}
                    </span>
                </Link>
            </div>

            <div v-if="tags.length === 0" class="text-center text-zinc-500">
                <TagIcon class="mx-auto mb-4 h-12 w-12 opacity-50" />
                <p>No tags yet</p>
            </div>
        </div>
    </div>
</template>

