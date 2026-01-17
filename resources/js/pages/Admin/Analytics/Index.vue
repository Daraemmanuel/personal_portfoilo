<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import type { Article } from '@/types/portfolio';
import { Head } from '@inertiajs/vue3';
import {
    BarChart3,
    BookOpen,
    Calendar,
    Eye,
    FileText,
    Mail,
    MessageSquare,
    Star,
    TrendingUp,
} from 'lucide-vue-next';

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
    scheduledArticles: number;
    seriesStats: Array<{
        series: string;
        article_count: number;
    }>;
    totalSeries: number;
    totalArticlesInSeries: number;
}>();
</script>

<template>
    <AppLayout
        :breadcrumbs="[
            { title: 'Analytics', href: route('admin.analytics.index') },
        ]"
    >
        <Head title="Analytics" />

        <div class="min-h-[calc(100vh-64px)] bg-background p-4 sm:p-6 lg:p-10">
            <div class="mx-auto max-w-7xl">
                <!-- Header -->
                <div class="mb-8 sm:mb-12">
                    <h2
                        class="bg-gradient-to-r from-foreground to-foreground/50 bg-clip-text text-2xl font-bold tracking-tight text-transparent sm:text-3xl lg:text-4xl"
                    >
                        Analytics Dashboard
                    </h2>
                    <p class="mt-1 text-sm text-muted-foreground sm:mt-2 sm:text-base">
                        Track your portfolio performance and engagement.
                    </p>
                </div>

                <!-- Stats Grid -->
                <div
                    class="mb-8 grid grid-cols-1 gap-4 sm:mb-12 sm:gap-6 sm:grid-cols-2 lg:grid-cols-4"
                >
                    <div
                        class="rounded-2xl border border-border bg-card p-4 shadow-sm transition-colors sm:p-6"
                    >
                        <div class="mb-3 flex items-center justify-between sm:mb-4">
                            <h3
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                            >
                                Draft Articles
                            </h3>
                            <div
                                class="rounded-full bg-yellow-500/10 p-1.5 text-yellow-500 sm:p-2"
                            >
                                <FileText class="h-4 w-4 sm:h-5 sm:w-5" />
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-foreground sm:text-3xl">
                            {{ stats.draft_articles }}
                        </p>
                        <p
                            class="mt-1.5 text-xs font-medium text-muted-foreground sm:mt-2"
                        >
                            Unpublished articles
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border border-border bg-card p-4 shadow-sm transition-colors sm:p-6"
                    >
                        <div class="mb-3 flex items-center justify-between sm:mb-4">
                            <h3
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                            >
                                Total Views
                            </h3>
                            <div
                                class="rounded-full bg-primary/10 p-1.5 text-primary sm:p-2"
                            >
                                <Eye class="h-4 w-4 sm:h-5 sm:w-5" />
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-foreground sm:text-3xl">
                            {{ stats.total_views.toLocaleString() }}
                        </p>
                        <p
                            class="mt-1.5 text-xs font-medium text-muted-foreground sm:mt-2"
                        >
                            Across all articles
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border border-border bg-card p-6 shadow-sm transition-colors"
                    >
                        <div class="mb-4 flex items-center justify-between">
                            <h3
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                            >
                                Published Articles
                            </h3>
                            <div
                                class="rounded-full bg-emerald-500/10 p-2 text-emerald-600 dark:text-emerald-400"
                            >
                                <FileText class="h-5 w-5" />
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-foreground">
                            {{ stats.published_articles }}
                        </p>
                        <p
                            class="mt-1.5 text-xs font-medium text-muted-foreground sm:mt-2"
                        >
                            of {{ stats.total_articles }} total
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border border-border bg-card p-6 shadow-sm transition-colors"
                    >
                        <div class="mb-4 flex items-center justify-between">
                            <h3
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                            >
                                Avg Views
                            </h3>
                            <div
                                class="rounded-full bg-blue-500/10 p-2 text-blue-500"
                            >
                                <TrendingUp class="h-5 w-5" />
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-foreground">
                            {{
                                Math.round(
                                    stats.avg_views_per_article,
                                ).toLocaleString()
                            }}
                        </p>
                        <p
                            class="mt-1.5 text-xs font-medium text-muted-foreground sm:mt-2"
                        >
                            Per article
                        </p>
                    </div>
                </div>

                <!-- Additional Stats Grid -->
                <div
                    class="mb-12 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4"
                >
                    <div
                        class="rounded-2xl border border-border bg-card p-6 shadow-sm transition-colors"
                    >
                        <div class="mb-4 flex items-center justify-between">
                            <h3
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                            >
                                Unread Messages
                            </h3>
                            <div
                                class="rounded-full bg-orange-500/10 p-2 text-orange-500"
                            >
                                <Mail class="h-5 w-5" />
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-foreground">
                            {{ stats.unread_messages }}
                        </p>
                        <p
                            class="mt-1.5 text-xs font-medium text-muted-foreground sm:mt-2"
                        >
                            of {{ stats.contact_messages }} total
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border border-border bg-card p-6 shadow-sm transition-colors"
                    >
                        <div class="mb-4 flex items-center justify-between">
                            <h3
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                            >
                                Pending Comments
                            </h3>
                            <div
                                class="rounded-full bg-yellow-500/10 p-2 text-yellow-500"
                            >
                                <MessageSquare class="h-5 w-5" />
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-foreground">
                            {{ stats.pending_comments }}
                        </p>
                        <p
                            class="mt-1.5 text-xs font-medium text-muted-foreground sm:mt-2"
                        >
                            of {{ stats.total_comments }} total
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border border-border bg-card p-6 shadow-sm transition-colors"
                    >
                        <div class="mb-4 flex items-center justify-between">
                            <h3
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                            >
                                Featured Articles
                            </h3>
                            <div
                                class="rounded-full bg-amber-500/10 p-2 text-amber-500"
                            >
                                <Star class="h-5 w-5" />
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-foreground">
                            {{ stats.featured_articles }}
                        </p>
                        <p
                            class="mt-1.5 text-xs font-medium text-muted-foreground sm:mt-2"
                        >
                            Featured on homepage
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border border-border bg-card p-6 shadow-sm transition-colors"
                    >
                        <div class="mb-4 flex items-center justify-between">
                            <h3
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                            >
                                This Month
                            </h3>
                            <div
                                class="rounded-full bg-emerald-500/10 p-2 text-emerald-600 dark:text-emerald-400"
                            >
                                <TrendingUp class="h-5 w-5" />
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-foreground">
                            {{ articlesThisMonth }}
                        </p>
                        <p
                            class="mt-1.5 text-xs font-medium text-muted-foreground sm:mt-2"
                        >
                            Articles created
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border border-border bg-card p-6 shadow-sm transition-colors"
                    >
                        <div class="mb-4 flex items-center justify-between">
                            <h3
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                            >
                                Scheduled
                            </h3>
                            <div
                                class="rounded-full bg-blue-500/10 p-2 text-blue-500"
                            >
                                <Calendar class="h-5 w-5" />
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-foreground">
                            {{ scheduledArticles }}
                        </p>
                        <p
                            class="mt-1.5 text-xs font-medium text-muted-foreground sm:mt-2"
                        >
                            Articles scheduled
                        </p>
                    </div>
                </div>

                <!-- Series Statistics Section -->
                <div
                    v-if="seriesStats && seriesStats.length > 0"
                    class="mb-12"
                >
                    <div
                        class="relative overflow-hidden rounded-2xl border border-border bg-card shadow-sm transition-colors"
                    >
                        <div class="border-b border-border bg-muted/50 p-6">
                            <h3
                                class="flex items-center gap-2 text-lg font-bold text-foreground"
                            >
                                <BookOpen class="h-5 w-5 text-indigo-500" />
                                Article Series
                            </h3>
                            <p class="mt-2 text-sm text-muted-foreground">
                                {{ totalSeries }} series with
                                {{ totalArticlesInSeries }} total articles
                            </p>
                        </div>
                        <div class="p-6">
                            <div
                                class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3"
                            >
                                <div
                                    v-for="series in seriesStats"
                                    :key="series.series"
                                    class="flex items-center justify-between rounded-xl border border-border bg-muted/30 p-4 transition-colors hover:bg-muted/50"
                                >
                                    <div>
                                        <p class="font-bold text-foreground">
                                            {{ series.series }}
                                        </p>
                                        <p
                                            class="mt-1 text-xs text-muted-foreground"
                                        >
                                            {{ series.article_count }}
                                            article{{
                                                series.article_count !== 1
                                                    ? 's'
                                                    : ''
                                            }}
                                        </p>
                                    </div>
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-500/10 text-sm font-bold text-indigo-500"
                                    >
                                        {{ series.article_count }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Views by Category Section (Moved up) -->
                <div
                    v-if="viewsByCategory && viewsByCategory.length > 0"
                    class="mb-12"
                >
                    <div
                        class="relative overflow-hidden rounded-2xl border border-border bg-card shadow-sm transition-colors"
                    >
                        <div class="border-b border-border bg-muted/50 p-6">
                            <h3
                                class="flex items-center gap-2 text-lg font-bold text-foreground"
                            >
                                <BarChart3 class="h-5 w-5 text-primary" />
                                Views by Category
                            </h3>
                        </div>
                        <div class="p-6">
                            <div
                                class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3"
                            >
                                <div
                                    v-for="category in viewsByCategory"
                                    :key="category.category"
                                    class="flex items-center justify-between rounded-xl border border-border bg-muted/30 p-4 transition-colors hover:bg-muted/50"
                                >
                                    <div>
                                        <p class="font-bold text-foreground">
                                            {{ category.category }}
                                        </p>
                                    </div>
                                    <div
                                        class="flex items-center gap-2 text-sm font-bold text-muted-foreground"
                                    >
                                        <Eye class="h-4 w-4" />
                                        <span>{{
                                            category.total_views.toLocaleString()
                                        }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Popular Articles -->
                <div class="mb-12 grid grid-cols-1 gap-8 lg:grid-cols-2">
                    <div
                        class="relative overflow-hidden rounded-2xl border border-border bg-card shadow-sm transition-colors"
                    >
                        <div class="border-b border-border bg-muted/50 p-6">
                            <h3
                                class="flex items-center gap-2 text-lg font-bold text-foreground"
                            >
                                <TrendingUp class="h-5 w-5 text-primary" />
                                Most Popular Articles
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div
                                    v-for="(article, index) in popularArticles"
                                    :key="article.id"
                                    class="flex items-center justify-between rounded-xl border border-border bg-muted/30 p-4 transition-colors hover:bg-muted/50"
                                >
                                    <div
                                        class="flex min-w-0 items-center gap-4"
                                    >
                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary/10 text-sm font-black text-primary"
                                        >
                                            {{ index + 1 }}
                                        </div>
                                        <div class="min-w-0">
                                            <p
                                                class="truncate font-bold text-foreground"
                                            >
                                                {{ article.title }}
                                            </p>
                                            <p
                                                class="text-xs font-medium text-muted-foreground"
                                            >
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
                                    <div
                                        class="ml-4 flex items-center gap-2 text-sm font-bold text-muted-foreground"
                                    >
                                        <Eye class="h-4 w-4" />
                                        <span>{{
                                            article.views.toLocaleString()
                                        }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="relative overflow-hidden rounded-2xl border border-border bg-card shadow-sm transition-colors"
                    >
                        <div class="border-b border-border bg-muted/50 p-6">
                            <h3
                                class="flex items-center gap-2 text-lg font-bold text-foreground"
                            >
                                <FileText
                                    class="h-5 w-5 text-emerald-600 dark:text-emerald-400"
                                />
                                Recent Articles
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div
                                    v-for="article in recentArticles"
                                    :key="article.id"
                                    class="flex items-center justify-between rounded-xl border border-border bg-muted/30 p-4 transition-colors hover:bg-muted/50"
                                >
                                    <div class="min-w-0">
                                        <p
                                            class="truncate font-bold text-foreground"
                                        >
                                            {{ article.title }}
                                        </p>
                                        <p
                                            class="text-xs font-medium text-muted-foreground"
                                        >
                                            Created
                                            {{
                                                new Date(
                                                    article.created_at,
                                                ).toLocaleDateString()
                                            }}
                                        </p>
                                    </div>
                                    <div
                                        class="ml-4 flex items-center gap-2 text-sm font-bold text-muted-foreground"
                                    >
                                        <Eye class="h-4 w-4" />
                                        <span>{{
                                            article.views.toLocaleString()
                                        }}</span>
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
