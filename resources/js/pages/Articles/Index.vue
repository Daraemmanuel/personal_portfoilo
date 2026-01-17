<script setup lang="ts">
import LandingNav from '@/components/landing/LandingNav.vue';
import type { Article } from '@/types/portfolio';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRight, Calendar } from 'lucide-vue-next';

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
        class="min-h-screen bg-background font-sans text-foreground antialiased selection:bg-indigo-500 selection:text-white"
    >
        <LandingNav />

        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6 sm:py-24">
            <header class="fade-in-up mb-8 animate-in text-center sm:mb-12">
                <h1
                    class="mb-3 bg-gradient-to-b from-foreground to-foreground/50 bg-clip-text text-3xl font-bold text-transparent sm:mb-4 sm:text-4xl md:text-5xl"
                >
                    Articles & Blog
                </h1>
                <p class="mb-4 text-sm text-muted-foreground sm:mb-6 sm:text-base">
                    Thoughts, tutorials, and insights on web development
                </p>
                <div class="flex flex-col items-center justify-center gap-2 sm:flex-row sm:gap-4">
                    <Link
                        :href="route('articles.categories')"
                        class="text-xs font-medium text-indigo-500 transition-colors hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300 sm:text-sm"
                    >
                        Browse Categories
                    </Link>
                    <span class="hidden text-border sm:inline">•</span>
                    <Link
                        :href="route('articles.tags')"
                        class="text-xs font-medium text-indigo-500 transition-colors hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300 sm:text-sm"
                    >
                        Browse Tags
                    </Link>
                </div>
            </header>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                <article
                    v-for="(article, index) in articles.data"
                    :key="article.id"
                    v-intersect.once
                    class="reveal group overflow-hidden rounded-2xl border border-border bg-card transition-all hover:-translate-y-2 hover:border-indigo-500/50 hover:shadow-2xl hover:shadow-indigo-500/10"
                    :style="{ transitionDelay: `${index * 100}ms` }"
                >
                    <Link :href="route('articles.show', article.slug)">
                        <div
                            v-if="article.featured_image_url"
                            class="aspect-video overflow-hidden"
                        >
                            <img
                                :src="article.featured_image_url"
                                :alt="article.title"
                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                                loading="lazy"
                            />
                        </div>
                        <div class="p-6">
                            <div
                                v-if="article.category"
                                class="mb-2 text-xs font-semibold text-indigo-500 uppercase dark:text-indigo-400"
                            >
                                {{ article.category }}
                            </div>
                            <h2
                                class="mb-3 text-xl font-semibold transition-colors group-hover:text-indigo-500 dark:group-hover:text-indigo-400"
                            >
                                {{ article.title }}
                            </h2>
                            <p
                                class="mb-4 line-clamp-3 text-sm text-muted-foreground"
                            >
                                {{ article.excerpt }}
                            </p>
                            <div
                                class="flex items-center justify-between text-xs text-muted-foreground/60"
                            >
                                <div class="flex items-center gap-2">
                                    <Calendar class="h-3 w-3" />
                                    <span v-if="article.published_at">
                                        {{
                                            new Date(
                                                article.published_at,
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
                class="mt-8 flex flex-wrap justify-center gap-1.5 sm:mt-12 sm:gap-2"
            >
                <Link
                    v-for="link in articles.links"
                    :key="link.label"
                    :href="link.url || '#'"
                    :class="[
                        'rounded-lg px-2 py-1.5 text-xs transition-colors sm:px-4 sm:py-2 sm:text-sm',
                        link.active
                            ? 'bg-indigo-500 text-white'
                            : 'bg-muted text-muted-foreground hover:bg-muted/80',
                        !link.url && 'pointer-events-none opacity-50',
                    ]"
                    v-html="link.label"
                />
            </div>
        </div>
    </div>
</template>
