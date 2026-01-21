<script setup lang="ts">
import LandingNav from '@/components/landing/LandingNav.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Folder } from 'lucide-vue-next';

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
                    Categories
                </h1>
                <p class="text-muted-foreground">Browse articles by category</p>
            </header>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                <Link
                    v-for="(category, index) in categories"
                    :key="category.name"
                    v-intersect.once
                    :href="route('articles.category', category.name)"
                    class="reveal group overflow-hidden rounded-2xl border border-border bg-card p-6 transition-all hover:-translate-y-1 hover:border-indigo-500/50 hover:bg-muted/50"
                    :style="{ transitionDelay: `${index * 50}ms` }"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <Folder
                                class="h-6 w-6 text-indigo-500 dark:text-indigo-400"
                            />
                            <div>
                                <h3
                                    class="text-lg font-semibold transition-colors group-hover:text-indigo-500 dark:group-hover:text-indigo-400"
                                >
                                    {{ category.name }}
                                </h3>
                                <p class="text-sm text-muted-foreground">
                                    {{ category.count }} article{{
                                        category.count !== 1 ? 's' : ''
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                </Link>
            </div>

            <div
                v-if="categories.length === 0"
                class="text-center text-muted-foreground"
            >
                <Folder class="mx-auto mb-4 h-12 w-12 opacity-50" />
                <p>No categories yet</p>
            </div>
        </div>
    </div>
</template>
