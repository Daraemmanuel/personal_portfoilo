<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { BarChart3, TrendingUp, Eye, FileText, Mail, Users, MessageSquare, Star } from 'lucide-vue-next';
import type { Article } from '@/types/portfolio';

defineProps<{
    stats: {
        total_articles: number;
        published_articles: number;
        draft_articles: number;
        total_views: number;
        avg_views_per_article: number;
        total_projects: number;
        contact_messages: number;
        unread_messages: number;
        newsletter_subscribers: number;
        total_comments: number;
        pending_comments: number;
        featured_articles: number;
    };
    articleViews: Array<{
        date: string;
        total_views: number;
    }>;
    popularArticles: Article[];
    recentArticles: Article[];
    viewsByCategory: Array<{
        category: string;
        total_views: number;
    }>;
    articlesThisMonth: number;
}>();
</script>

<template>
    <AppLayout
        :breadcrumbs="[
            { title: 'Analytics', href: route('admin.analytics.index') },
        ]"
    >
        <Head title="Analytics" />

        <div class="min-h-[calc(100vh-64px)] bg-zinc-950 p-6 lg:p-10">
            <div class="mx-auto max-w-7xl">
                <!-- Header -->
                <div class="mb-12">
                    <h2
                        class="bg-gradient-to-r from-white to-zinc-500 bg-clip-text text-3xl font-bold tracking-tight text-transparent sm:text-4xl"
                    >
                        Analytics Dashboard
                    </h2>
                    <p class="mt-2 text-zinc-400">
                        Track your portfolio performance and engagement.
                    </p>
                </div>

                <!-- Stats Grid -->
                <div class="mb-12 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                    <div
                        class="rounded-2xl border border-white/10 bg-zinc-900/50 p-6 backdrop-blur-sm"
                    >
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-sm font-medium text-zinc-400">Draft Articles</h3>
                            <FileText class="h-5 w-5 text-yellow-400" />
                        </div>
                        <p class="text-3xl font-bold text-white">
                            {{ stats.draft_articles }}
                        </p>
                        <p class="mt-2 text-sm text-zinc-500">
                            Unpublished articles
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border border-white/10 bg-zinc-900/50 p-6 backdrop-blur-sm"
                    >
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-sm font-medium text-zinc-400">Total Views</h3>
                            <Eye class="h-5 w-5 text-indigo-400" />
                        </div>
                        <p class="text-3xl font-bold text-white">
                            {{ stats.total_views.toLocaleString() }}
                        </p>
                        <p class="mt-2 text-sm text-zinc-500">
                            Across all articles
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border border-white/10 bg-zinc-900/50 p-6 backdrop-blur-sm"
                    >
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-sm font-medium text-zinc-400">Published Articles</h3>
                            <FileText class="h-5 w-5 text-green-400" />
                        </div>
                        <p class="text-3xl font-bold text-white">
                            {{ stats.published_articles }}
                        </p>
                        <p class="mt-2 text-sm text-zinc-500">
                            of {{ stats.total_articles }} total
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border border-white/10 bg-zinc-900/50 p-6 backdrop-blur-sm"
                    >
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-sm font-medium text-zinc-400">Avg Views</h3>
                            <TrendingUp class="h-5 w-5 text-blue-400" />
                        </div>
                        <p class="text-3xl font-bold text-white">
                            {{ Math.round(stats.avg_views_per_article).toLocaleString() }}
                        </p>
                        <p class="mt-2 text-sm text-zinc-500">
                            Per article
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border border-white/10 bg-zinc-900/50 p-6 backdrop-blur-sm"
                    >
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-sm font-medium text-zinc-400">Subscribers</h3>
                            <Users class="h-5 w-5 text-purple-400" />
                        </div>
                        <p class="text-3xl font-bold text-white">
                            {{ stats.newsletter_subscribers }}
                        </p>
                        <p class="mt-2 text-sm text-zinc-500">
                            Newsletter subscribers
                        </p>
                    </div>
                </div>

                <!-- Additional Stats Grid -->
                <div class="mb-12 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                    <div
                        class="rounded-2xl border border-white/10 bg-zinc-900/50 p-6 backdrop-blur-sm"
                    >
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-sm font-medium text-zinc-400">Unread Messages</h3>
                            <Mail class="h-5 w-5 text-orange-400" />
                        </div>
                        <p class="text-3xl font-bold text-white">
                            {{ stats.unread_messages }}
                        </p>
                        <p class="mt-2 text-sm text-zinc-500">
                            of {{ stats.contact_messages }} total
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border border-white/10 bg-zinc-900/50 p-6 backdrop-blur-sm"
                    >
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-sm font-medium text-zinc-400">Pending Comments</h3>
                            <MessageSquare class="h-5 w-5 text-yellow-400" />
                        </div>
                        <p class="text-3xl font-bold text-white">
                            {{ stats.pending_comments }}
                        </p>
                        <p class="mt-2 text-sm text-zinc-500">
                            of {{ stats.total_comments }} total
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border border-white/10 bg-zinc-900/50 p-6 backdrop-blur-sm"
                    >
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-sm font-medium text-zinc-400">Featured Articles</h3>
                            <Star class="h-5 w-5 text-amber-400" />
                        </div>
                        <p class="text-3xl font-bold text-white">
                            {{ stats.featured_articles }}
                        </p>
                        <p class="mt-2 text-sm text-zinc-500">
                            Featured on homepage
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border border-white/10 bg-zinc-900/50 p-6 backdrop-blur-sm"
                    >
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-sm font-medium text-zinc-400">This Month</h3>
                            <TrendingUp class="h-5 w-5 text-green-400" />
                        </div>
                        <p class="text-3xl font-bold text-white">
                            {{ articlesThisMonth }}
                        </p>
                        <p class="mt-2 text-sm text-zinc-500">
                            Articles created
                        </p>
                    </div>
                </div>

                <!-- Popular Articles -->
                <div class="mb-12">
                    <div
                        class="relative overflow-hidden rounded-2xl border border-white/10 bg-zinc-900/50 backdrop-blur-sm"
                    >
                        <div class="border-b border-white/5 bg-white/5 p-6">
                            <h3 class="flex items-center gap-2 text-lg font-semibold text-white">
                                <BarChart3 class="h-5 w-5 text-indigo-400" />
                                Most Popular Articles
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div
                                    v-for="(article, index) in popularArticles"
                                    :key="article.id"
                                    class="flex items-center justify-between rounded-lg border border-white/5 bg-white/5 p-4"
                                >
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-500/20 text-sm font-bold text-indigo-400"
                                        >
                                            {{ index + 1 }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-white">
                                                {{ article.title }}
                                            </p>
                                            <p class="text-sm text-zinc-500">
                                                Published
                                                {{
                                                    article.published_at
                                                        ? new Date(
                                                              article.published_at,
                                                          ).toLocaleDateString()
                                                        : 'Draft'
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-zinc-400">
                                        <Eye class="h-4 w-4" />
                                        <span class="font-semibold">{{ article.views }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Articles -->
                <div>
                    <div
                        class="relative overflow-hidden rounded-2xl border border-white/10 bg-zinc-900/50 backdrop-blur-sm"
                    >
                        <div class="border-b border-white/5 bg-white/5 p-6">
                            <h3 class="flex items-center gap-2 text-lg font-semibold text-white">
                                <FileText class="h-5 w-5 text-green-400" />
                                Recent Articles
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div
                                    v-for="article in recentArticles"
                                    :key="article.id"
                                    class="flex items-center justify-between rounded-lg border border-white/5 bg-white/5 p-4"
                                >
                                    <div>
                                        <p class="font-semibold text-white">
                                            {{ article.title }}
                                        </p>
                                        <p class="text-sm text-zinc-500">
                                            Created
                                            {{
                                                new Date(
                                                    article.created_at,
                                                ).toLocaleDateString()
                                            }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-zinc-400">
                                        <Eye class="h-4 w-4" />
                                        <span>{{ article.views }} views</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Views by Category -->
                <div v-if="viewsByCategory && viewsByCategory.length > 0" class="mb-12">
                    <div
                        class="relative overflow-hidden rounded-2xl border border-white/10 bg-zinc-900/50 backdrop-blur-sm"
                    >
                        <div class="border-b border-white/5 bg-white/5 p-6">
                            <h3 class="flex items-center gap-2 text-lg font-semibold text-white">
                                <BarChart3 class="h-5 w-5 text-indigo-400" />
                                Views by Category
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                <div
                                    v-for="category in viewsByCategory"
                                    :key="category.category"
                                    class="flex items-center justify-between rounded-lg border border-white/5 bg-white/5 p-4"
                                >
                                    <div>
                                        <p class="font-semibold text-white">
                                            {{ category.category }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-zinc-400">
                                        <Eye class="h-4 w-4" />
                                        <span class="font-semibold">{{ category.total_views.toLocaleString() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>



