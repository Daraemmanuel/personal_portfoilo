<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Article } from '@/types/portfolio';
import { Head, Link } from '@inertiajs/vue3';
import { Calendar, Edit, Eye, FileText, Trash2 } from 'lucide-vue-next';
import { stripHtml } from '@/utils/stripHtml';

defineProps<{
    articles: Article[];
    scheduledArticles?: Article[];
}>();
</script>

<template>
    <AppLayout
        :breadcrumbs="[
            { title: 'Articles', href: route('admin.articles.index') },
        ]"
    >
        <Head title="Articles" />

        <div class="min-h-[calc(100vh-64px)] bg-background p-4 sm:p-6 lg:p-10">
            <div class="mx-auto max-w-7xl">
                <!-- Header Section -->
                <div
                    class="fade-in-up mb-8 flex animate-in flex-col gap-4 sm:mb-12 md:flex-row md:items-center md:justify-between"
                >
                    <div>
                        <h2
                            class="bg-gradient-to-r from-foreground to-foreground/50 bg-clip-text text-2xl font-bold tracking-tight text-transparent sm:text-3xl lg:text-4xl"
                        >
                            Blog Articles
                        </h2>
                        <p
                            class="mt-1 text-sm text-muted-foreground sm:mt-2 sm:text-base"
                        >
                            Manage your blog posts and articles.
                        </p>
                    </div>
                    <Link :href="route('admin.articles.create')">
                        <Button
                            class="w-full rounded-full bg-primary px-6 py-4 text-sm font-bold text-primary-foreground shadow-lg shadow-primary/20 transition-all hover:scale-105 active:scale-95 sm:w-auto sm:px-8 sm:py-6"
                        >
                            New Article
                        </Button>
                    </Link>
                </div>

                <!-- Articles Table/List -->
                <div
                    v-intersect.once
                    class="reveal relative overflow-hidden rounded-2xl border border-border bg-card shadow-sm transition-all delay-200"
                >
                    <div class="-mx-4 overflow-x-auto sm:mx-0">
                        <table
                            class="w-full min-w-[640px] border-collapse text-left"
                        >
                            <thead>
                                <tr class="border-b border-border bg-muted/50">
                                    <th
                                        class="p-3 text-xs font-bold tracking-widest text-muted-foreground uppercase sm:p-5"
                                    >
                                        Article
                                    </th>
                                    <th
                                        class="p-3 text-xs font-bold tracking-widest text-muted-foreground uppercase sm:p-5"
                                    >
                                        Status
                                    </th>
                                    <th
                                        class="p-3 text-xs font-bold tracking-widest text-muted-foreground uppercase sm:p-5"
                                    >
                                        Views
                                    </th>
                                    <th
                                        class="p-3 text-right text-xs font-bold tracking-widest text-muted-foreground uppercase sm:p-5"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border" v-intersect>
                                <tr
                                    v-for="(article, index) in articles"
                                    :key="article.id"
                                    :class="[
                                        'group reveal transition-colors hover:bg-muted/30',
                                        'delay-' + index * 100,
                                    ]"
                                >
                                    <td class="p-3 sm:p-5">
                                        <div
                                            class="flex items-center gap-2 sm:gap-4"
                                        >
                                            <div
                                                v-if="
                                                    article.featured_image_url
                                                "
                                                class="h-12 w-16 flex-shrink-0 overflow-hidden rounded-lg border border-border bg-muted sm:h-16 sm:w-24"
                                            >
                                                <img
                                                    :src="
                                                        article.featured_image_url
                                                    "
                                                    :alt="article.title"
                                                    class="h-full w-full object-cover"
                                                />
                                            </div>
                                            <div
                                                v-else
                                                class="flex h-12 w-16 flex-shrink-0 items-center justify-center rounded-lg border border-border bg-muted sm:h-16 sm:w-24"
                                            >
                                                <FileText
                                                    class="h-4 w-4 text-muted-foreground sm:h-6 sm:w-6"
                                                />
                                            </div>
                                            <div
                                                class="flex min-w-0 flex-1 flex-col"
                                            >
                                                <span
                                                    class="text-sm font-bold text-foreground transition-colors group-hover:text-primary sm:text-lg"
                                                >
                                                    {{ article.title }}
                                                </span>
                                                <span
                                                    class="mt-0.5 line-clamp-1 max-w-md text-xs text-muted-foreground sm:mt-1 sm:text-sm"
                                                >
                                                    {{ stripHtml(article.excerpt) }}
                                                </span>
                                                <div
                                                    class="mt-1.5 flex flex-wrap items-center gap-2 text-xs text-muted-foreground/60 sm:mt-2 sm:gap-3"
                                                >
                                                    <div
                                                        v-if="article.category"
                                                        class="rounded-full border border-border bg-muted px-2 py-0.5"
                                                    >
                                                        {{ article.category }}
                                                    </div>
                                                    <div
                                                        v-if="
                                                            article.published_at
                                                        "
                                                        class="flex items-center gap-1"
                                                    >
                                                        <Calendar
                                                            class="h-3 w-3"
                                                        />
                                                        <span>{{
                                                            new Date(
                                                                article.published_at,
                                                            ).toLocaleDateString()
                                                        }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-3 sm:p-5">
                                        <span
                                            class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-bold tracking-wider uppercase sm:px-3 sm:py-1"
                                            :class="
                                                article.published_at &&
                                                new Date(
                                                    article.published_at,
                                                ) <= new Date()
                                                    ? 'border-emerald-500/20 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'
                                                    : article.published_at &&
                                                        new Date(
                                                            article.published_at,
                                                        ) > new Date()
                                                      ? 'border-blue-500/20 bg-blue-500/10 text-blue-600 dark:text-blue-400'
                                                      : 'border-yellow-500/20 bg-yellow-500/10 text-yellow-600 dark:text-yellow-400'
                                            "
                                        >
                                            {{
                                                article.published_at &&
                                                new Date(
                                                    article.published_at,
                                                ) <= new Date()
                                                    ? 'Published'
                                                    : article.published_at &&
                                                        new Date(
                                                            article.published_at,
                                                        ) > new Date()
                                                      ? 'Scheduled'
                                                      : 'Draft'
                                            }}
                                        </span>
                                    </td>
                                    <td class="p-5">
                                        <div
                                            class="flex items-center gap-2 text-sm text-muted-foreground"
                                        >
                                            <Eye class="h-4 w-4" />
                                            <span>{{ article.views }}</span>
                                        </div>
                                    </td>
                                    <td class="p-5 text-right">
                                        <div
                                            class="flex items-center justify-end gap-2"
                                        >
                                            <Button
                                                v-if="
                                                    article.slug &&
                                                    article.published_at &&
                                                    new Date(
                                                        article.published_at,
                                                    ) <= new Date()
                                                "
                                                as-child
                                                variant="ghost"
                                                size="sm"
                                                class="text-emerald-600 hover:bg-emerald-100 hover:text-emerald-700 dark:text-emerald-400 dark:hover:bg-emerald-900/30"
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'articles.show',
                                                            article.slug,
                                                        )
                                                    "
                                                    target="_blank"
                                                >
                                                    <Eye class="h-4 w-4" />
                                                </Link>
                                            </Button>
                                            <Button
                                                as-child
                                                variant="ghost"
                                                size="sm"
                                                class="text-primary hover:bg-primary/10"
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'admin.articles.edit',
                                                            article.id,
                                                        )
                                                    "
                                                >
                                                    <Edit class="h-4 w-4" />
                                                </Link>
                                            </Button>
                                            <Button
                                                as-child
                                                variant="ghost"
                                                size="sm"
                                                class="text-destructive hover:bg-destructive/10"
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'admin.articles.destroy',
                                                            article.id,
                                                        )
                                                    "
                                                    method="delete"
                                                    as="button"
                                                >
                                                    <Trash2 class="h-4 w-4" />
                                                </Link>
                                            </Button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="articles.length === 0">
                                    <td colspan="4" class="p-16 text-center">
                                        <div
                                            class="flex flex-col items-center justify-center gap-4"
                                        >
                                            <div
                                                class="flex h-16 w-16 items-center justify-center rounded-full bg-muted text-muted-foreground"
                                            >
                                                <FileText class="h-8 w-8" />
                                            </div>
                                            <p
                                                class="text-lg font-medium text-muted-foreground"
                                            >
                                                No articles yet. Start writing!
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Scheduled Articles Section -->
                <div
                    v-if="scheduledArticles && scheduledArticles.length > 0"
                    class="mt-12"
                >
                    <div
                        class="relative overflow-hidden rounded-2xl border border-border bg-card shadow-sm transition-colors"
                    >
                        <div class="border-b border-border bg-muted/50 p-6">
                            <h3
                                class="flex items-center gap-2 text-lg font-semibold text-foreground"
                            >
                                <Calendar class="h-5 w-5 text-blue-400" />
                                Scheduled Articles
                                <span
                                    class="ml-2 rounded-full bg-blue-500/20 px-2 py-0.5 text-xs font-bold text-blue-600 dark:text-blue-400"
                                >
                                    {{ scheduledArticles.length }}
                                </span>
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                <div
                                    v-for="article in scheduledArticles"
                                    :key="article.id"
                                    class="flex items-center justify-between rounded-lg border border-border bg-muted/30 p-4"
                                >
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3">
                                            <h4
                                                class="font-semibold text-foreground"
                                            >
                                                {{ article.title }}
                                            </h4>
                                            <span
                                                class="rounded-full border border-blue-500/20 bg-blue-500/10 px-2 py-0.5 text-xs font-bold text-blue-600 dark:text-blue-400"
                                            >
                                                Scheduled
                                            </span>
                                        </div>
                                        <p
                                            class="mt-1 text-sm text-muted-foreground"
                                        >
                                            Will be published on
                                            {{
                                                new Date(
                                                    article.published_at || '',
                                                ).toLocaleString()
                                            }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <Link
                                            :href="
                                                route(
                                                    'admin.articles.edit',
                                                    article.id,
                                                )
                                            "
                                            class="text-sm font-bold text-primary transition-colors hover:text-primary/80"
                                        >
                                            Edit
                                        </Link>
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
