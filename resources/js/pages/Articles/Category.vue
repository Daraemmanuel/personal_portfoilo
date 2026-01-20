<script setup lang="ts">
import LandingNav from '@/components/landing/LandingNav.vue';
import type { Article } from '@/types/portfolio';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, ArrowRight, Calendar } from 'lucide-vue-next';
import { computed } from 'vue';
import { stripHtml } from '@/utils/stripHtml';

const props = defineProps<{
    category: string;
    articles: {
        data: Article[];
        links: any;
        meta?: any;
    };
}>();

const totalArticles = computed(() => {
    return props.articles?.meta?.total ?? props.articles?.data?.length ?? 0;
});
</script>

<template>
    <Head :title="`${category} - Articles`" />

    <div
        class="min-h-screen bg-background font-sans text-foreground antialiased selection:bg-indigo-500 selection:text-white"
    >
        <LandingNav />

        <div class="mx-auto max-w-6xl px-6 py-24">
            <header class="fade-in-up mb-12 animate-in">
                <Link
                    :href="route('articles.categories')"
                    class="mb-4 inline-flex items-center gap-2 text-muted-foreground transition-colors hover:text-foreground"
                >
                    <ArrowLeft class="h-4 w-4" />
                    Back to Categories
                </Link>
                <h1
                    class="bg-gradient-to-b from-foreground to-foreground/50 bg-clip-text text-4xl font-bold text-transparent md:text-5xl"
                >
                    Category: {{ category }}
                </h1>
                <p class="mt-4 text-muted-foreground">
                    {{ totalArticles }} article{{
                        totalArticles !== 1 ? 's' : ''
                    }}
                    found
                </p>
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
                            <h2
                                class="mb-3 text-xl font-semibold transition-colors group-hover:text-indigo-500 dark:group-hover:text-indigo-400"
                            >
                                {{ article.title }}
                            </h2>
                            <p
                                class="mb-4 line-clamp-3 text-sm text-muted-foreground"
                            >
                                {{ stripHtml(article.excerpt) }}
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
                            : 'bg-muted text-muted-foreground hover:bg-muted/80',
                        !link.url && 'pointer-events-none opacity-50',
                    ]"
                    v-html="link.label"
                />
            </div>
        </div>
    </div>
</template>
