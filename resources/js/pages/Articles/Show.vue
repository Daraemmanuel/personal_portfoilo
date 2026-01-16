<script setup lang="ts">
import { computed, onMounted, onUnmounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Calendar, Eye, Clock, Tag } from 'lucide-vue-next';
import type { Article } from '@/types/portfolio';
import LandingNav from '@/components/landing/LandingNav.vue';
import SocialShare from '@/components/landing/SocialShare.vue';
import Breadcrumbs from '@/components/landing/Breadcrumbs.vue';
import CommentsSection from '@/components/articles/CommentsSection.vue';

const props = defineProps<{
    article: Article;
    relatedArticles?: Article[];
    popularArticles?: Article[];
    seriesArticles?: Article[];
    comments?: Array<{
        id: number;
        author_name: string;
        content: string;
        created_at: string;
        replies?: Array<{
            id: number;
            author_name: string;
            content: string;
            created_at: string;
        }>;
    }>;
    breadcrumbs?: Array<{ label: string; href?: string }>;
    metaDescription?: string;
    canonicalUrl?: string;
}>();

const articleUrl = computed(() => window.location.href);

let schemaScript: HTMLScriptElement | null = null;

onMounted(() => {
    const schema = {
        '@context': 'https://schema.org',
        '@type': 'BlogPosting',
        headline: props.article.title,
        description: props.metaDescription || props.article.excerpt,
        datePublished: props.article.published_at || null,
        dateModified: props.article.updated_at,
        author: {
            '@type': 'Person',
            name: 'DaraEmmanuel Akinyode',
        },
        publisher: {
            '@type': 'Organization',
            name: 'DaraEmmanuel Akinyode',
        },
    };
    
    if (props.article.featured_image_url) {
        schema.image = props.article.featured_image_url;
    }
    
    schemaScript = document.createElement('script');
    schemaScript.type = 'application/ld+json';
    schemaScript.textContent = JSON.stringify(schema);
    document.head.appendChild(schemaScript);
});

onUnmounted(() => {
    if (schemaScript && schemaScript.parentNode) {
        schemaScript.parentNode.removeChild(schemaScript);
    }
});
</script>

<template>
    <Head :title="`${article.title} - Articles`">
        <meta name="description" :content="metaDescription || article.excerpt" />
        <link rel="canonical" :href="canonicalUrl || articleUrl" />
        
        <!-- Open Graph -->
        <meta property="og:title" :content="article.title" />
        <meta property="og:description" :content="metaDescription || article.excerpt" />
        <meta property="og:type" content="article" />
        <meta property="og:url" :content="canonicalUrl || articleUrl" />
        <meta v-if="article.featured_image_url" property="og:image" :content="article.featured_image_url" />
        
        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="article.title" />
        <meta name="twitter:description" :content="metaDescription || article.excerpt" />
        <meta v-if="article.featured_image_url" name="twitter:image" :content="article.featured_image_url" />
    </Head>

    <div
        class="min-h-screen bg-black font-sans text-white antialiased selection:bg-indigo-500 selection:text-white"
    >
        <LandingNav />

        <div class="mx-auto max-w-4xl px-6 py-24">
            <div
                v-if="!article"
                class="text-center text-zinc-400"
            >
                <p class="text-xl">Article not found or not published.</p>
                <Link
                    :href="route('articles.index')"
                    class="mt-4 inline-block text-indigo-400 hover:text-indigo-300"
                >
                    Back to Articles
                </Link>
            </div>
            <Link
                :href="route('articles.index')"
                class="mb-8 inline-flex items-center gap-2 text-zinc-400 transition-colors hover:text-white"
            >
                <ArrowLeft class="h-4 w-4" />
                Back to Articles
            </Link>

            <article v-if="article">
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
                        {{ article.title || 'Untitled Article' }}
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
                        <div v-if="article.reading_time" class="flex items-center gap-2">
                            <Clock class="h-4 w-4" />
                            <span>{{ article.reading_time }} min read</span>
                        </div>
                    </div>
                </header>

                <!-- Social Share -->
                <div class="mb-8">
                    <SocialShare
                        :url="articleUrl"
                        :title="article.title"
                        :description="article.excerpt"
                    />
                </div>

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
                    v-if="article.content"
                    class="prose prose-invert max-w-none prose-headings:text-white prose-p:text-zinc-300 prose-a:text-indigo-400 prose-strong:text-white"
                    v-html="article.content"
                ></div>
                <div v-else class="text-zinc-400">
                    <p>No content available for this article.</p>
                </div>

                <div
                    v-if="article.tags && article.tags.length"
                    class="mt-8 border-t border-white/10 pt-8"
                >
                    <h3 class="mb-4 flex items-center gap-2 text-lg font-semibold">
                        <Tag class="h-5 w-5" />
                        Tags
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        <Link
                            v-for="(tag, index) in article.tags"
                            :key="`${article.id}-tag-${index}-${tag}`"
                            :href="route('articles.tag', tag)"
                            class="rounded-lg border border-white/10 bg-white/5 px-3 py-1 text-sm text-zinc-300 transition-colors hover:border-indigo-500/50 hover:bg-indigo-500/10 hover:text-indigo-400"
                        >
                            #{{ tag }}
                        </Link>
                    </div>
                </div>

                <div v-if="article.category" class="mt-8 border-t border-white/10 pt-8">
                    <Link
                        :href="route('articles.category', article.category)"
                        class="inline-flex items-center gap-2 text-sm font-medium text-indigo-400 transition-colors hover:text-indigo-300"
                    >
                        <span>Category: {{ article.category }}</span>
                        <ArrowLeft class="h-4 w-4 rotate-180" />
                    </Link>
                </div>

            </article>

            <!-- Comments Section -->
            <div class="comments-section">
                <CommentsSection
                    :article-id="article.id"
                    :comments="comments"
                />
            </div>

            <!-- Related Articles -->
            <section v-if="relatedArticles && relatedArticles.length > 0" class="mt-16">
                <h2 class="mb-8 text-2xl font-semibold text-white">Related Articles</h2>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <article
                        v-for="related in relatedArticles"
                        :key="related.id"
                        class="group overflow-hidden rounded-2xl border border-white/5 bg-zinc-900 transition-all hover:border-indigo-500/50"
                    >
                        <Link :href="route('articles.show', related.slug)">
                            <div
                                v-if="related.featured_image_url"
                                class="aspect-video overflow-hidden"
                            >
                                <img
                                    :src="related.featured_image_url"
                                    :alt="related.title"
                                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                    loading="lazy"
                                />
                            </div>
                            <div class="p-6">
                                <h3
                                    class="mb-2 text-lg font-semibold transition-colors group-hover:text-indigo-400"
                                >
                                    {{ related.title }}
                                </h3>
                                <p class="line-clamp-2 text-sm text-zinc-400">
                                    {{ related.excerpt }}
                                </p>
                            </div>
                        </Link>
                    </article>
                </div>
            </section>

            <!-- Popular Articles -->
            <section v-if="popularArticles && popularArticles.length > 0" class="mt-16">
                <h2 class="mb-8 text-2xl font-semibold text-white">Popular Articles</h2>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <article
                        v-for="popular in popularArticles"
                        :key="popular.id"
                        class="group overflow-hidden rounded-2xl border border-white/5 bg-zinc-900 transition-all hover:border-indigo-500/50"
                    >
                        <Link :href="route('articles.show', popular.slug)">
                            <div
                                v-if="popular.featured_image_url"
                                class="aspect-video overflow-hidden"
                            >
                                <img
                                    :src="popular.featured_image_url"
                                    :alt="popular.title"
                                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                    loading="lazy"
                                />
                            </div>
                            <div class="p-6">
                                <div class="mb-2 flex items-center gap-2 text-xs text-zinc-500">
                                    <Eye class="h-3 w-3" />
                                    <span>{{ popular.views }} views</span>
                                </div>
                                <h3
                                    class="mb-2 text-lg font-semibold transition-colors group-hover:text-indigo-400"
                                >
                                    {{ popular.title }}
                                </h3>
                                <p class="line-clamp-2 text-sm text-zinc-400">
                                    {{ popular.excerpt }}
                                </p>
                            </div>
                        </Link>
                    </article>
                </div>
            </section>
        </div>
    </div>
</template>

