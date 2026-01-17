<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Check, MessageCircle, Trash2, X } from 'lucide-vue-next';

defineProps<{
    comments: {
        data: Array<{
            id: number;
            article_id: number;
            article?: { title: string; slug: string };
            author_name: string;
            author_email: string;
            content: string;
            is_approved: boolean;
            created_at: string;
        }>;
        links: any;
        meta: any;
    };
}>();
</script>

<template>
    <AppLayout
        :breadcrumbs="[
            { title: 'Comments', href: route('admin.comments.index') },
        ]"
    >
        <Head title="Comments" />

        <div class="min-h-[calc(100vh-64px)] bg-background p-6 lg:p-10">
            <div class="mx-auto max-w-6xl">
                <div class="mb-12">
                    <h2
                        class="bg-gradient-to-r from-foreground to-foreground/50 bg-clip-text text-3xl font-bold tracking-tight text-transparent sm:text-4xl"
                    >
                        Article Comments
                    </h2>
                    <p class="mt-2 text-muted-foreground">
                        Moderate and manage article comments.
                    </p>
                </div>

                <div
                    class="relative overflow-hidden rounded-2xl border border-border bg-card shadow-sm backdrop-blur-sm"
                >
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse text-left">
                            <thead>
                                <tr class="border-b border-border bg-muted/50">
                                    <th
                                        class="p-5 text-sm font-semibold text-foreground uppercase"
                                    >
                                        Comment
                                    </th>
                                    <th
                                        class="p-5 text-sm font-semibold text-foreground uppercase"
                                    >
                                        Article
                                    </th>
                                    <th
                                        class="p-5 text-sm font-semibold text-foreground uppercase"
                                    >
                                        Status
                                    </th>
                                    <th
                                        class="p-5 text-right text-sm font-semibold text-foreground uppercase"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                <tr
                                    v-for="comment in comments.data"
                                    :key="comment.id"
                                    class="group transition-colors hover:bg-muted/30"
                                >
                                    <td class="p-5">
                                        <div class="max-w-md">
                                            <div
                                                class="mb-2 font-semibold text-foreground"
                                            >
                                                {{ comment.author_name }}
                                            </div>
                                            <p
                                                class="line-clamp-2 text-sm text-muted-foreground"
                                            >
                                                {{ comment.content }}
                                            </p>
                                            <p
                                                class="mt-2 text-xs text-muted-foreground/60"
                                            >
                                                {{ comment.author_email }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="p-5">
                                        <Link
                                            v-if="comment.article"
                                            :href="
                                                route(
                                                    'articles.show',
                                                    comment.article.slug,
                                                )
                                            "
                                            class="text-sm text-primary hover:underline"
                                        >
                                            {{ comment.article.title }}
                                        </Link>
                                        <span
                                            v-else
                                            class="text-sm text-muted-foreground"
                                        >
                                            Article deleted
                                        </span>
                                    </td>
                                    <td class="p-5">
                                        <span
                                            class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-medium"
                                            :class="
                                                comment.is_approved
                                                    ? 'border-green-500/20 bg-green-500/10 text-green-600 dark:text-green-400'
                                                    : 'border-yellow-500/20 bg-yellow-500/10 text-yellow-600 dark:text-yellow-400'
                                            "
                                        >
                                            {{
                                                comment.is_approved
                                                    ? 'Approved'
                                                    : 'Pending'
                                            }}
                                        </span>
                                    </td>
                                    <td class="p-5 text-right">
                                        <div
                                            class="flex items-center justify-end gap-2"
                                        >
                                            <Link
                                                v-if="!comment.is_approved"
                                                :href="
                                                    route(
                                                        'admin.comments.approve',
                                                        comment.id,
                                                    )
                                                "
                                                method="post"
                                                as="button"
                                                class="flex h-8 w-8 items-center justify-center rounded-lg border border-green-500/20 bg-green-500/10 text-green-600 transition-colors hover:bg-green-500/20 dark:text-green-400"
                                            >
                                                <Check class="h-4 w-4" />
                                            </Link>
                                            <Link
                                                v-if="comment.is_approved"
                                                :href="
                                                    route(
                                                        'admin.comments.reject',
                                                        comment.id,
                                                    )
                                                "
                                                method="post"
                                                as="button"
                                                class="flex h-8 w-8 items-center justify-center rounded-lg border border-yellow-500/20 bg-yellow-500/10 text-yellow-600 transition-colors hover:bg-yellow-500/20 dark:text-yellow-400"
                                            >
                                                <X class="h-4 w-4" />
                                            </Link>
                                            <Link
                                                :href="
                                                    route(
                                                        'admin.comments.destroy',
                                                        comment.id,
                                                    )
                                                "
                                                method="delete"
                                                as="button"
                                                class="flex h-8 w-8 items-center justify-center rounded-lg border border-red-500/20 bg-red-500/10 text-red-600 transition-colors hover:bg-red-500/20 dark:text-red-400"
                                            >
                                                <Trash2 class="h-4 w-4" />
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="comments.data.length === 0">
                                    <td colspan="4" class="p-12 text-center">
                                        <div
                                            class="flex flex-col items-center justify-center gap-3"
                                        >
                                            <MessageCircle
                                                class="h-12 w-12 text-muted-foreground/40"
                                            />
                                            <p
                                                class="font-medium text-muted-foreground"
                                            >
                                                No comments yet.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div
                    v-if="comments.links && comments.links.length > 3"
                    class="mt-6 flex justify-center gap-2"
                >
                    <Link
                        v-for="link in comments.links"
                        :key="link.label"
                        :href="link.url || '#'"
                        :class="[
                            'rounded-lg px-4 py-2 text-sm transition-colors',
                            link.active
                                ? 'bg-primary font-bold text-primary-foreground shadow-lg shadow-primary/20'
                                : 'bg-muted text-muted-foreground hover:bg-muted/80',
                            !link.url && 'pointer-events-none opacity-50',
                        ]"
                        v-html="link.label"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
