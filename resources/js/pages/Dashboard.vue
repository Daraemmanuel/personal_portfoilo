<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Article } from '@/types/portfolio';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import {
    BarChart3,
    BookOpen,
    Briefcase,
    Calendar,
    Clock,
    Eye,
    FileText,
    Mail,
    MessageSquare,
    Plus,
    Star,
    TrendingUp,
    Zap,
} from 'lucide-vue-next';

defineProps<{
    stats: {
        // Dashboard stats
        projects: number;
        skills: number;
        experiences: number;
        testimonials: number;
        articles: number;
        contact_messages: number;
        unread_messages: number;
        // Analytics stats
        total_articles: number;
        published_articles: number;
        draft_articles: number;
        total_views: number;
        avg_views_per_article: number;
        total_projects: number;
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
    recentActivity: Array<{
        type: 'article' | 'message' | 'comment';
        title: string;
        date: string;
        url: string;
        author?: string;
        is_unread?: boolean;
        is_pending?: boolean;
    }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
];

const statCards = [
    {
        title: 'Projects',
        value: 'projects',
        icon: Briefcase,
        color: 'bg-indigo-500',
        href: route('admin.projects.index'),
    },
    {
        title: 'Skills',
        value: 'skills',
        icon: Zap,
        color: 'bg-amber-500',
        href: route('admin.skills.index'),
    },
    {
        title: 'Experiences',
        value: 'experiences',
        icon: Clock,
        color: 'bg-emerald-500',
        href: route('admin.experiences.index'),
    },
    {
        title: 'Testimonials',
        value: 'testimonials',
        icon: Star,
        color: 'bg-purple-500',
        href: route('admin.testimonials.index'),
    },
    {
        title: 'Articles',
        value: 'articles',
        icon: FileText,
        color: 'bg-blue-500',
        href: route('admin.articles.index'),
    },
    {
        title: 'Messages',
        value: 'contact_messages',
        icon: Mail,
        color: 'bg-pink-500',
        href: route('admin.contact-messages.index'),
        badge: 'unread_messages',
    },
];

const quickActions = [
    {
        title: 'New Article',
        description: 'Create a new blog post',
        icon: FileText,
        href: route('admin.articles.create'),
        color: 'bg-blue-500/10 text-blue-500',
    },
    {
        title: 'New Project',
        description: 'Add a new project',
        icon: Briefcase,
        href: route('admin.projects.create'),
        color: 'bg-indigo-500/10 text-indigo-500',
    },
    {
        title: 'View Messages',
        description: 'Check contact messages',
        icon: Mail,
        href: route('admin.contact-messages.index'),
        color: 'bg-pink-500/10 text-pink-500',
        badge: 'unread_messages',
    },
    {
        title: 'Moderate Comments',
        description: 'Review pending comments',
        icon: MessageSquare,
        href: route('admin.comments.index'),
        color: 'bg-yellow-500/10 text-yellow-500',
        badge: 'pending_comments',
    },
];

const getActivityIcon = (type: string) => {
    switch (type) {
        case 'article':
            return FileText;
        case 'message':
            return Mail;
        case 'comment':
            return MessageSquare;
        default:
            return FileText;
    }
};

const getActivityColor = (type: string) => {
    switch (type) {
        case 'article':
            return 'bg-blue-500/10 text-blue-500';
        case 'message':
            return 'bg-pink-500/10 text-pink-500';
        case 'comment':
            return 'bg-yellow-500/10 text-yellow-500';
        default:
            return 'bg-gray-500/10 text-gray-500';
    }
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-[calc(100vh-64px)] bg-background p-4 sm:p-6 lg:p-10">
            <div class="mx-auto max-w-7xl">
                <!-- Header Section -->
                <div class="fade-in-up mb-8 animate-in sm:mb-12">
                    <h2
                        class="bg-gradient-to-r from-foreground to-foreground/50 bg-clip-text text-2xl font-bold tracking-tight text-transparent sm:text-3xl lg:text-4xl"
                    >
                        Dashboard
                    </h2>
                    <p class="mt-1 text-sm text-muted-foreground sm:mt-2 sm:text-base">
                        Comprehensive overview of your portfolio performance and
                        activity.
                    </p>
                </div>

                <!-- Quick Stats Cards (Dashboard) -->
                <div
                    class="mb-8 grid grid-cols-1 gap-4 sm:mb-12 sm:gap-6 sm:grid-cols-2 lg:grid-cols-3"
                >
                    <Link
                        v-for="(card, index) in statCards"
                        :key="card.title"
                        :href="card.href"
                        v-intersect.once
                        :class="[
                            'reveal group relative overflow-hidden rounded-2xl border border-border bg-card p-4 transition-all hover:-translate-y-1 hover:border-primary/50 hover:shadow-lg hover:shadow-primary/5 active:scale-[0.98] sm:p-6',
                        ]"
                        :style="{ transitionDelay: `${index * 100}ms` }"
                    >
                        <div class="flex items-center gap-4">
                            <div
                                :class="[
                                    'flex h-12 w-12 items-center justify-center rounded-full',
                                    card.color === 'bg-blue-500'
                                        ? 'bg-blue-500/10'
                                        : '',
                                    card.color === 'bg-amber-500'
                                        ? 'bg-amber-500/10'
                                        : '',
                                    card.color === 'bg-emerald-500'
                                        ? 'bg-emerald-500/10'
                                        : '',
                                    card.color === 'bg-purple-500'
                                        ? 'bg-purple-500/10'
                                        : '',
                                    card.color === 'bg-pink-500'
                                        ? 'bg-pink-500/10'
                                        : '',
                                    card.color === 'bg-indigo-500'
                                        ? 'bg-indigo-500/10'
                                        : '',
                                ]"
                            >
                                <component
                                    :is="card.icon"
                                    :class="[
                                        'h-6 w-6',
                                        card.color === 'bg-blue-500'
                                            ? 'text-blue-500'
                                            : '',
                                        card.color === 'bg-amber-500'
                                            ? 'text-amber-500'
                                            : '',
                                        card.color === 'bg-emerald-500'
                                            ? 'text-emerald-500'
                                            : '',
                                        card.color === 'bg-purple-500'
                                            ? 'text-purple-500'
                                            : '',
                                        card.color === 'bg-pink-500'
                                            ? 'text-pink-500'
                                            : '',
                                        card.color === 'bg-indigo-500'
                                            ? 'text-indigo-500'
                                            : '',
                                    ]"
                                />
                            </div>
                            <div class="flex-1">
                                <p
                                    class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                >
                                    {{ card.title }}
                                </p>
                                <p
                                    class="text-2xl font-black tracking-tight text-foreground sm:text-3xl"
                                >
                                    {{
                                        stats[
                                            card.value as keyof typeof stats
                                        ] || 0
                                    }}
                                </p>
                            </div>
                            <div
                                v-if="
                                    card.badge &&
                                    stats[card.badge as keyof typeof stats] > 0
                                "
                                class="flex h-6 w-6 animate-pulse items-center justify-center rounded-full bg-destructive text-xs font-bold text-destructive-foreground"
                            >
                                {{ stats[card.badge as keyof typeof stats] }}
                            </div>
                        </div>
                    </Link>
                </div>

                <!-- Quick Actions -->
                <div class="mb-8 sm:mb-12">
                    <h3
                        class="fade-in-up mb-4 animate-in text-lg font-bold text-foreground delay-300 sm:text-xl"
                    >
                        Quick Actions
                    </h3>
                    <div
                        class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4"
                    >
                        <Link
                            v-for="(action, index) in quickActions"
                            :key="action.title"
                            :href="action.href"
                            v-intersect.once
                            class="reveal group relative overflow-hidden rounded-xl border border-border bg-card p-4 transition-all hover:-translate-y-1 hover:border-primary/50 hover:shadow-md sm:p-6"
                            :style="{ transitionDelay: `${index * 100}ms` }"
                        >
                            <div class="flex items-start gap-4">
                                <div
                                    :class="[
                                        'flex h-10 w-10 shrink-0 items-center justify-center rounded-lg',
                                        action.color,
                                    ]"
                                >
                                    <component
                                        :is="action.icon"
                                        class="h-5 w-5"
                                    />
                                </div>
                                <div class="flex-1">
                                    <h4
                                        class="mb-1 text-sm font-bold text-foreground sm:text-base"
                                    >
                                        {{ action.title }}
                                    </h4>
                                    <p
                                        class="text-xs text-muted-foreground sm:text-sm"
                                    >
                                        {{ action.description }}
                                    </p>
                                </div>
                                <div
                                    v-if="
                                        action.badge &&
                                        stats[
                                            action.badge as keyof typeof stats
                                        ] > 0
                                    "
                                    class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-destructive text-xs font-bold text-destructive-foreground"
                                >
                                    {{
                                        stats[
                                            action.badge as keyof typeof stats
                                        ]
                                    }}
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="mb-8 grid grid-cols-1 gap-8 lg:grid-cols-3">
                    <!-- Left Column: Recent Activity & Popular Content -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Recent Activity Feed -->
                        <div
                            v-intersect.once
                            class="reveal relative overflow-hidden rounded-2xl border border-border bg-card shadow-sm transition-all delay-400"
                        >
                            <div class="border-b border-border bg-muted/50 p-4 sm:p-6">
                                <h3
                                    class="flex items-center gap-2 text-lg font-bold text-foreground"
                                >
                                    <Clock class="h-5 w-5 text-primary" />
                                    Recent Activity
                                </h3>
                            </div>
                            <div class="p-4 sm:p-6">
                                <div class="space-y-4">
                                    <div
                                        v-for="(activity, index) in recentActivity"
                                        :key="index"
                                        class="flex items-start gap-4 rounded-xl border border-border bg-muted/30 p-3 transition-colors hover:bg-muted/50 sm:p-4"
                                    >
                                        <div
                                            :class="[
                                                'flex h-10 w-10 shrink-0 items-center justify-center rounded-lg',
                                                getActivityColor(activity.type),
                                            ]"
                                        >
                                            <component
                                                :is="getActivityIcon(activity.type)"
                                                class="h-5 w-5"
                                            />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <Link
                                                :href="activity.url"
                                                class="block"
                                            >
                                                <p
                                                    class="truncate text-sm font-bold text-foreground sm:text-base"
                                                >
                                                    {{ activity.title }}
                                                </p>
                                                <div
                                                    class="mt-1 flex items-center gap-2 text-xs text-muted-foreground"
                                                >
                                                    <span
                                                        v-if="activity.author"
                                                    >
                                                        by {{ activity.author }}
                                                    </span>
                                                    <span
                                                        v-if="activity.author"
                                                        class="text-border"
                                                    >
                                                        •
                                                    </span>
                                                    <span>{{
                                                        new Date(
                                                            activity.date,
                                                        ).toLocaleDateString()
                                                    }}</span>
                                                </div>
                                            </Link>
                                        </div>
                                        <div
                                            v-if="
                                                activity.is_unread ||
                                                activity.is_pending
                                            "
                                            class="flex h-2 w-2 shrink-0 rounded-full bg-destructive"
                                        ></div>
                                    </div>
                                    <div
                                        v-if="
                                            !recentActivity ||
                                            recentActivity.length === 0
                                        "
                                        class="py-8 text-center text-sm text-muted-foreground"
                                    >
                                        No recent activity
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Popular Articles -->
                        <div
                            v-intersect.once
                            class="reveal relative overflow-hidden rounded-2xl border border-border bg-card shadow-sm transition-all delay-500"
                        >
                            <div class="border-b border-border bg-muted/50 p-4 sm:p-6">
                                <h3
                                    class="flex items-center gap-2 text-lg font-bold text-foreground"
                                >
                                    <TrendingUp class="h-5 w-5 text-primary" />
                                    Most Popular Articles
                                </h3>
                            </div>
                            <div class="p-4 sm:p-6">
                                <div class="space-y-4">
                                    <div
                                        v-for="(article, index) in popularArticles.slice(0, 5)"
                                        :key="article.id"
                                        class="flex items-center justify-between rounded-xl border border-border bg-muted/30 p-3 transition-colors hover:bg-muted/50 sm:p-4"
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
                                                    class="truncate text-sm font-bold text-foreground sm:text-base"
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
                    </div>

                    <!-- Right Column: Analytics Stats -->
                    <div class="space-y-8">
                        <!-- Performance Stats -->
                        <div
                            v-intersect.once
                            class="reveal relative overflow-hidden rounded-2xl border border-border bg-card shadow-sm transition-all delay-400"
                        >
                            <div class="border-b border-border bg-muted/50 p-4 sm:p-6">
                                <h3
                                    class="flex items-center gap-2 text-lg font-bold text-foreground"
                                >
                                    <BarChart3 class="h-5 w-5 text-primary" />
                                    Performance
                                </h3>
                            </div>
                            <div class="p-4 sm:p-6">
                                <div class="space-y-4">
                                    <div
                                        class="flex items-center justify-between rounded-xl border border-border bg-muted/30 p-3 sm:p-4"
                                    >
                                        <div>
                                            <p
                                                class="text-xs font-bold text-muted-foreground uppercase"
                                            >
                                                Total Views
                                            </p>
                                            <p
                                                class="mt-1 text-2xl font-bold text-foreground"
                                            >
                                                {{
                                                    stats.total_views.toLocaleString()
                                                }}
                                            </p>
                                        </div>
                                        <Eye class="h-8 w-8 text-primary/50" />
                                    </div>
                                    <div
                                        class="flex items-center justify-between rounded-xl border border-border bg-muted/30 p-3 sm:p-4"
                                    >
                                        <div>
                                            <p
                                                class="text-xs font-bold text-muted-foreground uppercase"
                                            >
                                                Avg Views
                                            </p>
                                            <p
                                                class="mt-1 text-2xl font-bold text-foreground"
                                            >
                                                {{
                                                    Math.round(
                                                        stats.avg_views_per_article,
                                                    ).toLocaleString()
                                                }}
                                            </p>
                                        </div>
                                        <TrendingUp
                                            class="h-8 w-8 text-primary/50"
                                        />
                                    </div>
                                    <div
                                        class="flex items-center justify-between rounded-xl border border-border bg-muted/30 p-3 sm:p-4"
                                    >
                                        <div>
                                            <p
                                                class="text-xs font-bold text-muted-foreground uppercase"
                                            >
                                                This Month
                                            </p>
                                            <p
                                                class="mt-1 text-2xl font-bold text-foreground"
                                            >
                                                {{ articlesThisMonth }}
                                            </p>
                                        </div>
                                        <Calendar
                                            class="h-8 w-8 text-primary/50"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Views by Category -->
                        <div
                            v-if="viewsByCategory && viewsByCategory.length > 0"
                            v-intersect.once
                            class="reveal relative overflow-hidden rounded-2xl border border-border bg-card shadow-sm transition-all delay-500"
                        >
                            <div class="border-b border-border bg-muted/50 p-4 sm:p-6">
                                <h3
                                    class="flex items-center gap-2 text-lg font-bold text-foreground"
                                >
                                    <BarChart3 class="h-5 w-5 text-primary" />
                                    Views by Category
                                </h3>
                            </div>
                            <div class="p-4 sm:p-6">
                                <div class="space-y-3">
                                    <div
                                        v-for="category in viewsByCategory.slice(0, 5)"
                                        :key="category.category"
                                        class="flex items-center justify-between rounded-xl border border-border bg-muted/30 p-3 transition-colors hover:bg-muted/50"
                                    >
                                        <p
                                            class="text-sm font-bold text-foreground"
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
