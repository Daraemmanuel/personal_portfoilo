<script setup lang="ts">
import LandingNav from '@/components/landing/LandingNav.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Tag as TagIcon } from 'lucide-vue-next';

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
        class="min-h-screen bg-background font-sans text-foreground antialiased selection:bg-indigo-500 selection:text-white"
    >
        <LandingNav />

        <div class="mx-auto max-w-6xl px-6 py-24">
            <div class="mb-6">
                <Link
                    :href="route('articles.index')"
                    class="inline-flex items-center gap-2 text-sm text-muted-foreground transition-colors hover:text-foreground"
                >
                    <ArrowLeft class="h-4 w-4" />
                    <span>Back to Articles</span>
                </Link>
            </div>

            <header class="fade-in-up mb-12 animate-in text-center">
                <h1
                    class="mb-4 bg-gradient-to-b from-foreground to-foreground/50 bg-clip-text text-4xl font-bold text-transparent md:text-5xl"
                >
                    Tags
                </h1>
                <p class="text-muted-foreground">Browse articles by tag</p>
            </header>

            <div class="flex flex-wrap gap-3">
                <Link
                    v-for="(tag, index) in tags"
                    :key="tag.name"
                    :href="route('articles.tag', tag.name)"
                    class="scale-in group inline-flex animate-in items-center gap-2 rounded-lg border border-border bg-card px-4 py-2 text-sm font-medium text-muted-foreground transition-all hover:border-indigo-500/50 hover:bg-indigo-500/10 hover:text-indigo-500 dark:hover:text-indigo-400"
                    :style="{ animationDelay: `${index * 30}ms` }"
                >
                    <TagIcon class="h-4 w-4" />
                    <span>#{{ tag.name }}</span>
                    <span
                        class="rounded-full bg-muted px-2 py-0.5 text-xs transition-colors group-hover:bg-indigo-500/20"
                    >
                        {{ tag.count }}
                    </span>
                </Link>
            </div>

            <div
                v-if="tags.length === 0"
                class="text-center text-muted-foreground"
            >
                <TagIcon class="mx-auto mb-4 h-12 w-12 opacity-50" />
                <p>No tags yet</p>
            </div>
        </div>
    </div>
</template>
