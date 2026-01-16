<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { Calendar, ArrowRight, Tag } from 'lucide-vue-next';
import type { Article } from '@/types/portfolio';
import LandingNav from '@/components/landing/LandingNav.vue';

const props = defineProps<{
    tag: string;
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
    <Head :title="`#${tag} - Articles`" />

    <div
        class="min-h-screen bg-black font-sans text-white antialiased selection:bg-indigo-500 selection:text-white"
    >
        <LandingNav />

        <div class="mx-auto max-w-6xl px-6 py-24">
            <header class="mb-12">
                <Link
                    :href="route('articles.index')"
                    class="mb-4 inline-flex items-center gap-2 text-zinc-400 transition-colors hover:text-white"
                >
                    <ArrowRight class="h-4 w-4 rotate-180" />
                    Back to Articles
                </Link>
                <div class="mb-4 flex items-center gap-3">
                    <Tag class="h-6 w-6 text-indigo-400" />
                    <h1
                        class="bg-gradient-to-b from-white to-zinc-500 bg-clip-text text-4xl font-bold text-transparent md:text-5xl"
                    >
                        #{{ tag }}
                    </h1>
                </div>
                <p class="text-zinc-400">
                    {{ totalArticles }} article{{ totalArticles !== 1 ? 's' : '' }} tagged with #{{ tag }}
                </p>
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

