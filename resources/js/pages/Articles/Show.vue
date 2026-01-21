<script setup lang="ts">
import CommentsSection from '@/components/articles/CommentsSection.vue';
import LandingNav from '@/components/landing/LandingNav.vue';
import SocialShare from '@/components/landing/SocialShare.vue';
import type { Article } from '@/types/portfolio';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Calendar, Clock, Eye, Tag } from 'lucide-vue-next';
import { computed, onMounted, onUnmounted } from 'vue';
import { stripHtml } from '@/utils/stripHtml';

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

const plainExcerpt = computed(() => stripHtml(props.article.excerpt));

let schemaScript: HTMLScriptElement | null = null;

onMounted(() => {
    const schema: any = {
        '@context': 'https://schema.org',
        '@type': 'BlogPosting',
        headline: props.article.title,
        description: props.metaDescription || plainExcerpt.value,
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
        <meta
            name="description"
            :content="metaDescription || plainExcerpt"
        />
        <link rel="canonical" :href="canonicalUrl || articleUrl" />

        <!-- Open Graph -->
        <meta property="og:title" :content="article.title" />
        <meta
            property="og:description"
            :content="metaDescription || plainExcerpt"
        />
        <meta property="og:type" content="article" />
        <meta property="og:url" :content="canonicalUrl || articleUrl" />
        <meta
            v-if="article.featured_image_url"
            property="og:image"
            :content="article.featured_image_url"
        />

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="article.title" />
        <meta
            name="twitter:description"
            :content="metaDescription || plainExcerpt"
        />
        <meta
            v-if="article.featured_image_url"
            name="twitter:image"
            :content="article.featured_image_url"
        />
    </Head>

    <div
        class="min-h-screen bg-background font-sans text-foreground antialiased selection:bg-indigo-500 selection:text-white"
    >
        <LandingNav />
        <main id="main-content" tabindex="-1" class="focus:outline-none">

        <div class="mx-auto max-w-4xl px-4 py-16 sm:px-6 sm:py-24">
            <div v-if="!article" class="text-center text-muted-foreground">
                <p class="text-lg sm:text-xl">Article not found or not published.</p>
                <Link
                    :href="route('articles.index')"
                    class="mt-4 inline-block text-sm text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300 sm:text-base"
                >
                    Back to Articles
                </Link>
            </div>
            <Link
                :href="route('articles.index')"
                class="mb-6 inline-flex items-center gap-2 text-sm text-muted-foreground transition-colors hover:text-foreground sm:mb-8"
            >
                <ArrowLeft class="h-4 w-4" />
                Back to Articles
            </Link>

            <article v-if="article">
                <header class="fade-in-up mb-6 animate-in sm:mb-8">
                    <div
                        v-if="article.category"
                        class="mb-3 text-xs font-semibold text-indigo-500 uppercase dark:text-indigo-400 sm:mb-4 sm:text-sm"
                    >
                        {{ article.category }}
                    </div>
                    <h1
                        class="mb-3 bg-gradient-to-b from-foreground to-foreground/50 bg-clip-text text-3xl font-bold text-transparent sm:mb-4 sm:text-4xl md:text-5xl"
                    >
                        {{ article.title || 'Untitled Article' }}
                    </h1>
                    <div
                        class="flex flex-wrap items-center gap-3 text-xs text-muted-foreground sm:gap-4 sm:text-sm"
                    >
                        <div
                            v-if="article.published_at"
                            class="flex items-center gap-2"
                        >
                            <Calendar class="h-4 w-4" />
                            <span>{{
                                new Date(
                                    article.published_at,
                                ).toLocaleDateString()
                            }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <Eye class="h-4 w-4" />
                            <span>{{ article.views }} views</span>
                        </div>
                        <div
                            v-if="article.reading_time"
                            class="flex items-center gap-2"
                        >
                            <Clock class="h-4 w-4" />
                            <span>{{ article.reading_time }} min read</span>
                        </div>
                    </div>
                </header>

                <!-- Social Share -->
                <div class="fade-in-up mb-8 animate-in delay-150">
                    <SocialShare
                        :url="articleUrl"
                        :title="article.title"
                        :description="plainExcerpt"
                    />
                </div>

                <div
                    v-if="article.featured_image_url"
                    class="fade-in-up mb-8 animate-in overflow-hidden rounded-2xl delay-200"
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
                    class="fade-in-up prose dark:prose-invert prose-headings:text-foreground prose-p:text-muted-foreground prose-a:text-indigo-500 dark:prose-a:text-indigo-400 prose-strong:text-foreground max-w-none animate-in delay-300"
                    v-html="article.content"
                ></div>
                <div v-else class="text-muted-foreground">
                    <p>No content available for this article.</p>
                </div>

                <div
                    v-if="article.tags && article.tags.length"
                    v-intersect.once
                    class="reveal mt-8 border-t border-border pt-8"
                >
                    <h3
                        class="mb-4 flex items-center gap-2 text-lg font-semibold"
                    >
                        <Tag class="h-5 w-5" />
                        Tags
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        <Link
                            v-for="(tag, index) in article.tags"
                            :key="`${article.id}-tag-${index}-${tag}`"
                            :href="route('articles.tag', tag)"
                            class="rounded-lg border border-border bg-muted/50 px-3 py-1 text-sm text-muted-foreground transition-all hover:border-indigo-500/50 hover:bg-indigo-500/10 hover:text-indigo-500 dark:hover:text-indigo-400"
                        >
                            #{{ tag }}
                        </Link>
                    </div>
                </div>

                <div
                    v-if="article.category"
                    v-intersect.once
                    class="reveal mt-8 border-t border-border pt-8"
                >
                    <Link
                        :href="route('articles.category', article.category)"
                        class="inline-flex items-center gap-2 text-sm font-medium text-indigo-500 transition-colors hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300"
                    >
                        <span>Category: {{ article.category }}</span>
                        <ArrowLeft class="h-4 w-4 rotate-180" />
                    </Link>
                </div>

                <!-- Series Navigation -->
                <div
                    v-if="
                        article.series &&
                        seriesArticles &&
                        seriesArticles.length > 0
                    "
                    v-intersect.once
                    class="reveal mt-8 border-t border-border pt-8"
                >
                    <div class="mb-4">
                        <h3 class="mb-2 text-lg font-semibold text-foreground">
                            Series: {{ article.series }}
                        </h3>
                        <p class="text-sm text-muted-foreground">
                            Part {{ article.series_order }} of
                            {{ seriesArticles.length + 1 }}
                        </p>
                    </div>
                    <div class="space-y-2">
                        <div
                            v-for="seriesArticle in seriesArticles"
                            :key="seriesArticle.id"
                            class="flex items-center gap-3 rounded-lg border border-border bg-card p-3 transition-colors hover:bg-muted/50"
                            :class="
                                seriesArticle.id === article.id
                                    ? 'border-indigo-500/50 bg-indigo-500/10'
                                    : ''
                            "
                        >
                            <div
                                class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-indigo-500/20 text-sm font-bold text-indigo-500 dark:text-indigo-400"
                            >
                                {{ seriesArticle.series_order }}
                            </div>
                            <Link
                                :href="
                                    route('articles.show', seriesArticle.slug)
                                "
                                class="flex-1 text-sm font-medium transition-colors"
                                :class="
                                    seriesArticle.id === article.id
                                        ? 'text-indigo-500 dark:text-indigo-400'
                                        : 'text-muted-foreground hover:text-foreground'
                                "
                            >
                                {{ seriesArticle.title }}
                            </Link>
                            <span
                                v-if="seriesArticle.id === article.id"
                                class="text-xs text-indigo-500 dark:text-indigo-400"
                                >Current</span
                            >
                        </div>
                    </div>
                </div>
            </article>

            <!-- Comments Section -->
            <div v-intersect.once class="reveal comments-section delay-150">
                <CommentsSection
                    :article-id="article.id"
                    :comments="comments"
                />
            </div>

            <!-- Related Articles -->
            <section
                v-if="relatedArticles && relatedArticles.length > 0"
                v-intersect.once
                class="reveal mt-16"
            >
                <h2 class="mb-8 text-2xl font-semibold text-foreground">
                    Related Articles
                </h2>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <article
                        v-for="(related, index) in relatedArticles"
                        :key="related.id"
                        class="group overflow-hidden rounded-2xl border border-border bg-card transition-all hover:-translate-y-1 hover:border-indigo-500/50 hover:shadow-xl hover:shadow-indigo-500/5"
                        :style="{ transitionDelay: `${index * 100}ms` }"
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
                                    class="mb-2 text-lg font-semibold transition-colors group-hover:text-indigo-500 dark:group-hover:text-indigo-400"
                                >
                                    {{ related.title }}
                                </h3>
                                <p
                                    class="line-clamp-2 text-sm text-muted-foreground"
                                >
                                    {{ stripHtml(related.excerpt) }}
                                </p>
                            </div>
                        </Link>
                    </article>
                </div>
            </section>

            <!-- Popular Articles -->
            <section
                v-if="popularArticles && popularArticles.length > 0"
                v-intersect.once
                class="reveal mt-16"
            >
                <h2 class="mb-8 text-2xl font-semibold text-foreground">
                    Popular Articles
                </h2>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <article
                        v-for="(popular, index) in popularArticles"
                        :key="popular.id"
                        class="group overflow-hidden rounded-2xl border border-border bg-card transition-all hover:-translate-y-1 hover:border-indigo-500/50 hover:shadow-xl hover:shadow-indigo-500/5"
                        :style="{ transitionDelay: `${index * 100}ms` }"
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
                                <div
                                    class="mb-2 flex items-center gap-2 text-xs text-muted-foreground/60"
                                >
                                    <Eye class="h-3 w-3" />
                                    <span>{{ popular.views }} views</span>
                                </div>
                                <h3
                                    class="mb-2 text-lg font-semibold transition-colors group-hover:text-indigo-500 dark:group-hover:text-indigo-400"
                                >
                                    {{ popular.title }}
                                </h3>
                                <p
                                    class="line-clamp-2 text-sm text-muted-foreground"
                                >
                                    {{ stripHtml(popular.excerpt) }}
                                </p>
                            </div>
                        </Link>
                    </article>
                </div>
            </section>
        </div>
        </main>
    </div>
</template>
