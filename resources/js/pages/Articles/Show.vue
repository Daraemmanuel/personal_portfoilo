<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Calendar, Eye } from 'lucide-vue-next';
import type { Article } from '@/types/portfolio';
import LandingNav from '@/components/landing/LandingNav.vue';

defineProps<{
    article: Article;
}>();
</script>

<template>
    <Head :title="`${article.title} - Articles`" />

    <div
        class="min-h-screen bg-black font-sans text-white antialiased selection:bg-indigo-500 selection:text-white"
    >
        <LandingNav />

        <div class="mx-auto max-w-4xl px-6 py-24">
            <Link
                :href="route('articles.index')"
                class="mb-8 inline-flex items-center gap-2 text-zinc-400 transition-colors hover:text-white"
            >
                <ArrowLeft class="h-4 w-4" />
                Back to Articles
            </Link>

            <article>
                <header class="mb-8">
                    <div
                        v-if="article.category"
                        class="mb-4 text-sm font-semibold uppercase text-indigo-400"
                    >
                        {{ article.category }}
                    </div>
                    <h1
                        class="mb-4 bg-gradient-to-b from-white to-zinc-500 bg-clip-text text-4xl font-bold text-transparent md:text-5xl"
                    >
                        {{ article.title }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-4 text-sm text-zinc-400">
                        <div
                            v-if="article.published_at"
                            class="flex items-center gap-2"
                        >
                            <Calendar class="h-4 w-4" />
                            <span>{{
                                new Date(
                                    article.published_at
                                ).toLocaleDateString()
                            }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <Eye class="h-4 w-4" />
                            <span>{{ article.views }} views</span>
                        </div>
                    </div>
                </header>

                <div
                    v-if="article.featured_image_url"
                    class="mb-8 overflow-hidden rounded-2xl"
                >
                    <img
                        :src="article.featured_image_url"
                        :alt="article.title"
                        class="h-auto w-full object-cover"
                        loading="lazy"
                    />
                </div>

                <div
                    class="prose prose-invert max-w-none prose-headings:text-white prose-p:text-zinc-300 prose-a:text-indigo-400 prose-strong:text-white"
                    v-html="article.content"
                ></div>

                <div
                    v-if="article.tags && article.tags.length"
                    class="mt-8 border-t border-white/10 pt-8"
                >
                    <h3 class="mb-4 text-lg font-semibold">Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        <span
                            v-for="(tag, index) in article.tags"
                            :key="`${article.id}-tag-${index}-${tag}`"
                            class="rounded-lg border border-white/10 bg-white/5 px-3 py-1 text-sm text-zinc-300"
                        >
                            #{{ tag }}
                        </span>
                    </div>
                </div>
            </article>
        </div>
    </div>
</template>

