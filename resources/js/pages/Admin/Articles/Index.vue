<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import type { Article } from '@/types/portfolio';
import { Calendar, Eye, FileText } from 'lucide-vue-next';

defineProps<{
    articles: Article[];
}>();
</script>

<template>
    <AppLayout
        :breadcrumbs="[
            { title: 'Articles', href: route('admin.articles.index') },
        ]"
    >
        <Head title="Articles" />

        <div class="min-h-[calc(100vh-64px)] bg-zinc-950 p-6 lg:p-10">
            <div class="mx-auto max-w-6xl">
                <!-- Header Section -->
                <div
                    class="mb-12 flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
                >
                    <div>
                        <h2
                            class="bg-gradient-to-r from-white to-zinc-500 bg-clip-text text-3xl font-bold tracking-tight text-transparent sm:text-4xl"
                        >
                            Blog Articles
                        </h2>
                        <p class="mt-2 text-zinc-400">
                            Manage your blog posts and articles.
                        </p>
                    </div>
                    <Link :href="route('admin.articles.create')">
                        <Button
                            class="rounded-full bg-indigo-600 px-6 py-6 font-semibold text-white transition-all hover:bg-indigo-700 hover:shadow-[0_0_20px_rgba(79,70,229,0.4)]"
                        >
                            New Article
                        </Button>
                    </Link>
                </div>

                <!-- Articles Table/List -->
                <div
                    class="relative overflow-hidden rounded-2xl border border-white/10 bg-zinc-900/50 backdrop-blur-sm"
                >
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse text-left">
                            <thead>
                                <tr class="border-b border-white/5 bg-white/5">
                                    <th
                                        class="p-5 text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                    >
                                        Article
                                    </th>
                                    <th
                                        class="p-5 text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                    >
                                        Status
                                    </th>
                                    <th
                                        class="p-5 text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                    >
                                        Views
                                    </th>
                                    <th
                                        class="p-5 text-right text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <tr
                                    v-for="article in articles"
                                    :key="article.id"
                                    class="group transition-colors hover:bg-white/[0.02]"
                                >
                                    <td class="p-5">
                                        <div class="flex items-center gap-4">
                                            <div
                                                v-if="article.featured_image_url"
                                                class="h-16 w-24 flex-shrink-0 overflow-hidden rounded-lg border border-white/10 bg-white/5"
                                            >
                                                <img
                                                    :src="article.featured_image_url"
                                                    :alt="article.title"
                                                    class="h-full w-full object-cover"
                                                />
                                            </div>
                                            <div
                                                v-else
                                                class="flex h-16 w-24 flex-shrink-0 items-center justify-center rounded-lg border border-white/10 bg-white/5"
                                            >
                                                <FileText class="h-6 w-6 text-zinc-600" />
                                            </div>
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-lg font-bold text-white transition-colors group-hover:text-indigo-400"
                                                >
                                                    {{ article.title }}
                                                </span>
                                                <span
                                                    class="mt-1 line-clamp-1 max-w-md text-sm text-zinc-500"
                                                >
                                                    {{ article.excerpt }}
                                                </span>
                                                <div
                                                    class="mt-2 flex items-center gap-3 text-xs text-zinc-600"
                                                >
                                                    <div
                                                        v-if="article.category"
                                                        class="rounded-full border border-white/10 bg-white/5 px-2 py-0.5"
                                                    >
                                                        {{ article.category }}
                                                    </div>
                                                    <div
                                                        v-if="article.published_at"
                                                        class="flex items-center gap-1"
                                                    >
                                                        <Calendar class="h-3 w-3" />
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
                                    <td class="p-5">
                                        <span
                                            class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-medium"
                                            :class="
                                                article.published_at &&
                                                new Date(
                                                    article.published_at,
                                                ) <= new Date()
                                                    ? 'border-green-500/20 bg-green-500/10 text-green-400'
                                                    : 'border-yellow-500/20 bg-yellow-500/10 text-yellow-400'
                                            "
                                        >
                                            {{
                                                article.published_at &&
                                                new Date(
                                                    article.published_at,
                                                ) <= new Date()
                                                    ? 'Published'
                                                    : 'Draft'
                                            }}
                                        </span>
                                    </td>
                                    <td class="p-5">
                                        <div class="flex items-center gap-2 text-sm text-zinc-400">
                                            <Eye class="h-4 w-4" />
                                            <span>{{ article.views }}</span>
                                        </div>
                                    </td>
                                    <td class="p-5 text-right">
                                        <div
                                            class="flex items-center justify-end gap-3"
                                        >
                                            <Link
                                                v-if="article.slug && article.published_at && new Date(article.published_at) <= new Date()"
                                                :href="route('articles.show', article.slug)"
                                                target="_blank"
                                                class="text-sm font-medium text-green-400 transition-colors hover:text-green-300"
                                            >
                                                View
                                            </Link>
                                            <Link
                                                :href="
                                                    route(
                                                        'admin.articles.edit',
                                                        article.id,
                                                    )
                                                "
                                                class="text-sm font-medium text-indigo-400 transition-colors hover:text-indigo-300"
                                            >
                                                Edit
                                            </Link>
                                            <div
                                                class="h-1 w-1 rounded-full bg-zinc-700"
                                            ></div>
                                            <Link
                                                :href="
                                                    route(
                                                        'admin.articles.destroy',
                                                        article.id,
                                                    )
                                                "
                                                method="delete"
                                                as="button"
                                                class="text-sm font-medium text-zinc-500 transition-colors hover:text-red-400"
                                            >
                                                Delete
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="articles.length === 0">
                                    <td colspan="4" class="p-12 text-center">
                                        <div
                                            class="flex flex-col items-center justify-center gap-3"
                                        >
                                            <div
                                                class="flex h-12 w-12 items-center justify-center rounded-full bg-white/5 text-zinc-600"
                                            >
                                                <FileText class="h-6 w-6" />
                                            </div>
                                            <p
                                                class="font-medium text-zinc-500"
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
            </div>
        </div>
    </AppLayout>
</template>

