<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Calendar, ArrowRight } from 'lucide-vue-next';
import type { Article } from '@/types/portfolio';
import LandingNav from '@/components/landing/LandingNav.vue';

defineProps<{
    articles: {
        data: Article[];
        links: any;
        meta: any;
    };
}>();
</script>

<template>
    <Head title="Articles - Blog" />

    <div
        class="min-h-screen bg-black font-sans text-white antialiased selection:bg-indigo-500 selection:text-white"
    >
        <LandingNav />

        <div class="mx-auto max-w-6xl px-6 py-24">
            <header class="mb-12 text-center">
                <h1
                    class="mb-4 bg-gradient-to-b from-white to-zinc-500 bg-clip-text text-4xl font-bold text-transparent md:text-5xl"
                >
                    Articles & Blog
                </h1>
                <p class="mb-6 text-zinc-400">
                    Thoughts, tutorials, and insights on web development
                </p>
                <div class="flex items-center justify-center gap-4">
                    <Link
                        :href="route('articles.categories')"
                        class="text-sm font-medium text-indigo-400 transition-colors hover:text-indigo-300"
                    >
                        Browse Categories
                    </Link>
                    <span class="text-zinc-600">•</span>
                    <Link
                        :href="route('articles.tags')"
                        class="text-sm font-medium text-indigo-400 transition-colors hover:text-indigo-300"
                    >
                        Browse Tags
                    </Link>
                </div>
            </header>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                <article
                    v-for="article in articles.data"
                    :key="article.id"
                    class="group overflow-hidden rounded-2xl border border-white/5 bg-zinc-900 transition-all hover:border-indigo-500/50"
                >
                    <Link :href="route('articles.show', article.slug)">
                        <div
                            v-if="article.featured_image_url"
                            class="aspect-video overflow-hidden"
                        >
                            <img
                                :src="article.featured_image_url"
                                :alt="article.title"
                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                loading="lazy"
                            />
                        </div>
                        <div class="p-6">
                            <div
                                v-if="article.category"
                                class="mb-2 text-xs font-semibold uppercase text-indigo-400"
                            >
                                {{ article.category }}
                            </div>
                            <h2
                                class="mb-3 text-xl font-semibold transition-colors group-hover:text-indigo-400"
                            >
                                {{ article.title }}
                            </h2>
                            <p class="mb-4 text-sm text-zinc-400">
                                {{ article.excerpt }}
                            </p>
                            <div class="flex items-center justify-between text-xs text-zinc-500">
                                <div class="flex items-center gap-2">
                                    <Calendar class="h-3 w-3" />
                                    <span
                                        v-if="article.published_at"
                                    >
                                        {{
                                            new Date(
                                                article.published_at
                                            ).toLocaleDateString()
                                        }}
                                    </span>
                                </div>
                                <ArrowRight
                                    class="h-4 w-4 transition-transform group-hover:translate-x-1"
                                />
                            </div>
                        </div>
                    </Link>
                </article>
            </div>

            <!-- Pagination -->
            <div
                v-if="articles.links && articles.links.length > 3"
                class="mt-12 flex justify-center gap-2"
            >
                <Link
                    v-for="link in articles.links"
                    :key="link.label"
                    :href="link.url || '#'"
                    :class="[
                        'rounded-lg px-4 py-2 text-sm transition-colors',
                        link.active
                            ? 'bg-indigo-500 text-white'
                            : 'bg-white/5 text-zinc-400 hover:bg-white/10',
                        !link.url && 'pointer-events-none opacity-50',
                    ]"
                    v-html="link.label"
                />
            </div>
        </div>
    </div>
</template>

