<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import type { Article } from '@/types/portfolio';
import { Head, Link } from '@inertiajs/vue3';
import {
    BarChart3,
    BookOpen,
    Calendar,
    Eye,
    MessageSquare,
    Star,
    TrendingDown,
    TrendingUp,
} from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
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
        article_count: number;
    }>;
    viewsByMonth: Array<{
        year: number;
        month: number;
        total_views: number;
        article_count: number;
    }>;
    popularArticles: Article[];
    viewsByCategory: Array<{
        category: string;
        total_views: number;
        article_count: number;
    }>;
    articlePerformance: {
        total_articles: number;
        published_articles: number;
        draft_articles: number;
        total_views: number;
        avg_views_per_article: number;
        max_views: number;
        min_views: number;
        articles_this_month: number;
        articles_last_month: number;
        views_this_month: number;
        views_last_month: number;
    };
    seriesStats: Array<{
        series: string;
        article_count: number;
        total_views: number;
    }>;
    topPerformingArticles: Array<{
        id: number;
        title: string;
        views: number;
        published_at: string | null;
        category: string | null;
        comments_count: number;
    }>;
}>();

const maxViews = computed(() => {
    return Math.max(
        ...(props.articleViews?.map((v) => v.total_views) || [0]),
        1,
    );
});

const getBarHeight = (views: number) => {
    return `${(views / maxViews.value) * 100}%`;
};

const getMonthName = (month: number) => {
    const months = [
        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'May',
        'Jun',
        'Jul',
        'Aug',
        'Sep',
        'Oct',
        'Nov',
        'Dec',
    ];
    return months[month - 1] || '';
};

const viewsTrend = computed(() => {
    if (!props.articlePerformance) return null;
    const current = props.articlePerformance.views_this_month;
    const previous = props.articlePerformance.views_last_month;
    if (previous === 0) return null;
    const change = ((current - previous) / previous) * 100;
    return {
        value: Math.abs(change).toFixed(1),
        isPositive: change > 0,
    };
});

const articlesTrend = computed(() => {
    if (!props.articlePerformance) return null;
    const current = props.articlePerformance.articles_this_month;
    const previous = props.articlePerformance.articles_last_month;
    if (previous === 0) return null;
    const change = ((current - previous) / previous) * 100;
    return {
        value: Math.abs(change).toFixed(1),
        isPositive: change > 0,
    };
});
</script>

<template>
    <AppLayout
        :breadcrumbs="[
            { title: 'Dashboard', href: route('dashboard') },
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
                        Detailed Analytics
                    </h2>
                    <p
                        class="mt-1 text-sm text-muted-foreground sm:mt-2 sm:text-base"
                    >
                        Deep dive into your portfolio performance, trends, and
                        engagement metrics.
                    </p>
                </div>

                <!-- Performance Overview -->
                <div
                    class="mb-8 grid grid-cols-1 gap-4 sm:mb-12 sm:grid-cols-2 sm:gap-6 lg:grid-cols-4"
                >
                    <div
                        class="rounded-2xl border border-border bg-card p-4 shadow-sm transition-colors sm:p-6"
                    >
                        <div
                            class="mb-3 flex items-center justify-between sm:mb-4"
                        >
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
                        <p
                            class="text-2xl font-bold text-foreground sm:text-3xl"
                        >
                            {{
                                props.articlePerformance.total_views.toLocaleString()
                            }}
                        </p>
                        <div
                            v-if="viewsTrend"
                            class="mt-2 flex items-center gap-1 text-xs"
                            :class="
                                viewsTrend.isPositive
                                    ? 'text-emerald-500'
                                    : 'text-red-500'
                            "
                        >
                            <component
                                :is="
                                    viewsTrend.isPositive
                                        ? TrendingUp
                                        : TrendingDown
                                "
                                class="h-3 w-3"
                            />
                            <span> {{ viewsTrend.value }}% vs last month </span>
                        </div>
                    </div>

                    <div
                        class="rounded-2xl border border-border bg-card p-4 shadow-sm transition-colors sm:p-6"
                    >
                        <div
                            class="mb-3 flex items-center justify-between sm:mb-4"
                        >
                            <h3
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                            >
                                Avg Views
                            </h3>
                            <div
                                class="rounded-full bg-blue-500/10 p-1.5 text-blue-500 sm:p-2"
                            >
                                <TrendingUp class="h-4 w-4 sm:h-5 sm:w-5" />
                            </div>
                        </div>
                        <p
                            class="text-2xl font-bold text-foreground sm:text-3xl"
                        >
                            {{
                                Math.round(
                                    props.articlePerformance
                                        .avg_views_per_article,
                                ).toLocaleString()
                            }}
                        </p>
                        <p
                            class="mt-1.5 text-xs font-medium text-muted-foreground sm:mt-2"
                        >
                            Per published article
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border border-border bg-card p-4 shadow-sm transition-colors sm:p-6"
                    >
                        <div
                            class="mb-3 flex items-center justify-between sm:mb-4"
                        >
                            <h3
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                            >
                                Articles This Month
                            </h3>
                            <div
                                class="rounded-full bg-emerald-500/10 p-1.5 text-emerald-500 sm:p-2"
                            >
                                <Calendar class="h-4 w-4 sm:h-5 sm:w-5" />
                            </div>
                        </div>
                        <p
                            class="text-2xl font-bold text-foreground sm:text-3xl"
                        >
                            {{ props.articlePerformance.articles_this_month }}
                        </p>
                        <div
                            v-if="articlesTrend"
                            class="mt-2 flex items-center gap-1 text-xs"
                            :class="
                                articlesTrend.isPositive
                                    ? 'text-emerald-500'
                                    : 'text-red-500'
                            "
                        >
                            <component
                                :is="
                                    articlesTrend.isPositive
                                        ? TrendingUp
                                        : TrendingDown
                                "
                                class="h-3 w-3"
                            />
                            <span>
                                {{ articlesTrend.value }}% vs last month
                            </span>
                        </div>
                    </div>

                    <div
                        class="rounded-2xl border border-border bg-card p-4 shadow-sm transition-colors sm:p-6"
                    >
                        <div
                            class="mb-3 flex items-center justify-between sm:mb-4"
                        >
                            <h3
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                            >
                                Max Views
                            </h3>
                            <div
                                class="rounded-full bg-amber-500/10 p-1.5 text-amber-500 sm:p-2"
                            >
                                <Star class="h-4 w-4 sm:h-5 sm:w-5" />
                            </div>
                        </div>
                        <p
                            class="text-2xl font-bold text-foreground sm:text-3xl"
                        >
                            {{
                                props.articlePerformance.max_views.toLocaleString()
                            }}
                        </p>
                        <p
                            class="mt-1.5 text-xs font-medium text-muted-foreground sm:mt-2"
                        >
                            Best performing article
                        </p>
                    </div>
                </div>

                <!-- Views Over Time Chart -->
                <div class="mb-8 sm:mb-12">
                    <div
                        class="relative overflow-hidden rounded-2xl border border-border bg-card shadow-sm transition-colors"
                    >
                        <div
                            class="border-b border-border bg-muted/50 p-4 sm:p-6"
                        >
                            <h3
                                class="flex items-center gap-2 text-lg font-bold text-foreground"
                            >
                                <BarChart3 class="h-5 w-5 text-primary" />
                                Views Over Time (Last 90 Days)
                            </h3>
                            <p class="mt-2 text-sm text-muted-foreground">
                                Daily view count and article creation
                            </p>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div
                                v-if="
                                    props.articleViews &&
                                    props.articleViews.length > 0
                                "
                                class="flex items-end justify-between gap-2"
                                style="min-height: 200px"
                            >
                                <div
                                    v-for="(view, index) in props.articleViews"
                                    :key="index"
                                    class="flex flex-1 flex-col items-center gap-2"
                                >
                                    <div
                                        class="relative w-full rounded-t bg-primary/20 transition-all hover:bg-primary/30"
                                        :style="{
                                            height: getBarHeight(
                                                view.total_views,
                                            ),
                                            minHeight: '4px',
                                        }"
                                        :title="`${view.total_views} views on ${new Date(view.date).toLocaleDateString()}`"
                                    ></div>
                                    <span
                                        class="text-xs text-muted-foreground"
                                        v-if="index % 7 === 0"
                                    >
                                        {{
                                            new Date(
                                                view.date,
                                            ).toLocaleDateString('en-US', {
                                                month: 'short',
                                                day: 'numeric',
                                            })
                                        }}
                                    </span>
                                </div>
                            </div>
                            <div
                                v-else
                                class="py-12 text-center text-sm text-muted-foreground"
                            >
                                No view data available
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Trends -->
                <div class="mb-8 sm:mb-12">
                    <div
                        class="relative overflow-hidden rounded-2xl border border-border bg-card shadow-sm transition-colors"
                    >
                        <div
                            class="border-b border-border bg-muted/50 p-4 sm:p-6"
                        >
                            <h3
                                class="flex items-center gap-2 text-lg font-bold text-foreground"
                            >
                                <TrendingUp class="h-5 w-5 text-primary" />
                                Monthly Trends (Last 12 Months)
                            </h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div
                                v-if="
                                    props.viewsByMonth &&
                                    props.viewsByMonth.length > 0
                                "
                                class="space-y-4"
                            >
                                <div
                                    v-for="month in props.viewsByMonth"
                                    :key="`${month.year}-${month.month}`"
                                    class="flex items-center gap-4"
                                >
                                    <div
                                        class="w-24 text-sm font-medium text-foreground"
                                    >
                                        {{ getMonthName(month.month) }}
                                        {{ month.year }}
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="mb-1 flex items-center justify-between text-xs"
                                        >
                                            <span class="text-muted-foreground">
                                                {{
                                                    month.total_views.toLocaleString()
                                                }}
                                                views
                                            </span>
                                            <span class="text-muted-foreground">
                                                {{ month.article_count }}
                                                articles
                                            </span>
                                        </div>
                                        <div
                                            class="h-2 w-full overflow-hidden rounded-full bg-muted"
                                        >
                                            <div
                                                class="h-full bg-primary transition-all"
                                                :style="{
                                                    width: `${
                                                        (month.total_views /
                                                            Math.max(
                                                                ...props.viewsByMonth.map(
                                                                    (m) =>
                                                                        m.total_views,
                                                                ),
                                                                1,
                                                            )) *
                                                        100
                                                    }%`,
                                                }"
                                            ></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                v-else
                                class="py-12 text-center text-sm text-muted-foreground"
                            >
                                No monthly data available
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Two Column Layout -->
                <div class="mb-8 grid grid-cols-1 gap-8 lg:grid-cols-2">
                    <!-- Top Performing Articles -->
                    <div
                        class="relative overflow-hidden rounded-2xl border border-border bg-card shadow-sm transition-colors"
                    >
                        <div
                            class="border-b border-border bg-muted/50 p-4 sm:p-6"
                        >
                            <h3
                                class="flex items-center gap-2 text-lg font-bold text-foreground"
                            >
                                <Star class="h-5 w-5 text-amber-500" />
                                Top Performing Articles
                            </h3>
                            <p class="mt-2 text-sm text-muted-foreground">
                                Ranked by views + engagement
                            </p>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div class="space-y-4">
                                <div
                                    v-for="(
                                        article, index
                                    ) in props.topPerformingArticles"
                                    :key="article.id"
                                    class="flex items-center justify-between rounded-xl border border-border bg-muted/30 p-3 transition-colors hover:bg-muted/50 sm:p-4"
                                >
                                    <div
                                        class="flex min-w-0 items-center gap-4"
                                    >
                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-500/10 text-sm font-black text-amber-500"
                                        >
                                            {{ index + 1 }}
                                        </div>
                                        <div class="min-w-0">
                                            <Link
                                                :href="
                                                    route(
                                                        'admin.articles.edit',
                                                        article.id,
                                                    )
                                                "
                                                class="block"
                                            >
                                                <p
                                                    class="truncate text-sm font-bold text-foreground sm:text-base"
                                                >
                                                    {{ article.title }}
                                                </p>
                                                <div
                                                    class="mt-1 flex items-center gap-2 text-xs text-muted-foreground"
                                                >
                                                    <Eye class="h-3 w-3" />
                                                    <span>{{
                                                        article.views.toLocaleString()
                                                    }}</span>
                                                    <MessageSquare
                                                        class="ml-2 h-3 w-3"
                                                    />
                                                    <span>{{
                                                        article.comments_count ||
                                                        0
                                                    }}</span>
                                                </div>
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Views by Category -->
                    <div
                        v-if="
                            props.viewsByCategory &&
                            props.viewsByCategory.length > 0
                        "
                        class="relative overflow-hidden rounded-2xl border border-border bg-card shadow-sm transition-colors"
                    >
                        <div
                            class="border-b border-border bg-muted/50 p-4 sm:p-6"
                        >
                            <h3
                                class="flex items-center gap-2 text-lg font-bold text-foreground"
                            >
                                <BarChart3 class="h-5 w-5 text-primary" />
                                Performance by Category
                            </h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div class="space-y-4">
                                <div
                                    v-for="category in props.viewsByCategory"
                                    :key="category.category"
                                    class="rounded-xl border border-border bg-muted/30 p-4 transition-colors hover:bg-muted/50"
                                >
                                    <div
                                        class="mb-2 flex items-center justify-between"
                                    >
                                        <p
                                            class="text-sm font-bold text-foreground sm:text-base"
                                        >
                                            {{ category.category }}
                                        </p>
                                        <div
                                            class="flex items-center gap-2 text-xs font-bold text-muted-foreground sm:text-sm"
                                        >
                                            <Eye class="h-4 w-4" />
                                            <span>{{
                                                category.total_views.toLocaleString()
                                            }}</span>
                                        </div>
                                    </div>
                                    <div
                                        class="mb-2 h-2 w-full overflow-hidden rounded-full bg-muted"
                                    >
                                        <div
                                            class="h-full bg-primary transition-all"
                                            :style="{
                                                width: `${
                                                    (category.total_views /
                                                        Math.max(
                                                            ...props.viewsByCategory.map(
                                                                (c) =>
                                                                    c.total_views,
                                                            ),
                                                            1,
                                                        )) *
                                                    100
                                                }%`,
                                            }"
                                        ></div>
                                    </div>
                                    <p class="text-xs text-muted-foreground">
                                        {{ category.article_count }}
                                        article{{
                                            category.article_count !== 1
                                                ? 's'
                                                : ''
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Series Statistics -->
                <div
                    v-if="props.seriesStats && props.seriesStats.length > 0"
                    class="mb-8 sm:mb-12"
                >
                    <div
                        class="relative overflow-hidden rounded-2xl border border-border bg-card shadow-sm transition-colors"
                    >
                        <div
                            class="border-b border-border bg-muted/50 p-4 sm:p-6"
                        >
                            <h3
                                class="flex items-center gap-2 text-lg font-bold text-foreground"
                            >
                                <BookOpen class="h-5 w-5 text-indigo-500" />
                                Article Series Performance
                            </h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div
                                class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3"
                            >
                                <div
                                    v-for="series in props.seriesStats"
                                    :key="series.series"
                                    class="rounded-xl border border-border bg-muted/30 p-4 transition-colors hover:bg-muted/50"
                                >
                                    <div
                                        class="mb-2 flex items-center justify-between"
                                    >
                                        <p class="font-bold text-foreground">
                                            {{ series.series }}
                                        </p>
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-500/10 text-sm font-bold text-indigo-500"
                                        >
                                            {{ series.article_count }}
                                        </div>
                                    </div>
                                    <div
                                        class="flex items-center gap-2 text-xs text-muted-foreground"
                                    >
                                        <Eye class="h-3 w-3" />
                                        <span>{{
                                            series.total_views.toLocaleString()
                                        }}</span>
                                        <span class="text-border">•</span>
                                        <span
                                            >{{
                                                Math.round(
                                                    series.total_views /
                                                        series.article_count,
                                                )
                                            }}
                                            avg views</span
                                        >
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
