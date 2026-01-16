<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Check, X, Trash2, MessageCircle } from 'lucide-vue-next';

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

        <div class="min-h-[calc(100vh-64px)] bg-zinc-950 p-6 lg:p-10">
            <div class="mx-auto max-w-6xl">
                <div class="mb-12">
                    <h2
                        class="bg-gradient-to-r from-white to-zinc-500 bg-clip-text text-3xl font-bold tracking-tight text-transparent sm:text-4xl"
                    >
                        Article Comments
                    </h2>
                    <p class="mt-2 text-zinc-400">
                        Moderate and manage article comments.
                    </p>
                </div>

                <div
                    class="relative overflow-hidden rounded-2xl border border-white/10 bg-zinc-900/50 backdrop-blur-sm"
                >
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse text-left">
                            <thead>
                                <tr class="border-b border-white/5 bg-white/5">
                                    <th class="p-5 text-sm font-semibold text-zinc-300 uppercase">
                                        Comment
                                    </th>
                                    <th class="p-5 text-sm font-semibold text-zinc-300 uppercase">
                                        Article
                                    </th>
                                    <th class="p-5 text-sm font-semibold text-zinc-300 uppercase">
                                        Status
                                    </th>
                                    <th class="p-5 text-right text-sm font-semibold text-zinc-300 uppercase">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <tr
                                    v-for="comment in comments.data"
                                    :key="comment.id"
                                    class="group transition-colors hover:bg-white/[0.02]"
                                >
                                    <td class="p-5">
                                        <div class="max-w-md">
                                            <div class="mb-2 font-semibold text-white">
                                                {{ comment.author_name }}
                                            </div>
                                            <p class="line-clamp-2 text-sm text-zinc-400">
                                                {{ comment.content }}
                                            </p>
                                            <p class="mt-2 text-xs text-zinc-600">
                                                {{ comment.author_email }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="p-5">
                                        <Link
                                            v-if="comment.article"
                                            :href="route('articles.show', comment.article.slug)"
                                            class="text-sm text-indigo-400 hover:text-indigo-300"
                                        >
                                            {{ comment.article.title }}
                                        </Link>
                                        <span v-else class="text-sm text-zinc-500">
                                            Article deleted
                                        </span>
                                    </td>
                                    <td class="p-5">
                                        <span
                                            class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-medium"
                                            :class="
                                                comment.is_approved
                                                    ? 'border-green-500/20 bg-green-500/10 text-green-400'
                                                    : 'border-yellow-500/20 bg-yellow-500/10 text-yellow-400'
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
                                        <div class="flex items-center justify-end gap-2">
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
                                                class="flex h-8 w-8 items-center justify-center rounded-lg border border-green-500/20 bg-green-500/10 text-green-400 transition-colors hover:bg-green-500/20"
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
                                                class="flex h-8 w-8 items-center justify-center rounded-lg border border-yellow-500/20 bg-yellow-500/10 text-yellow-400 transition-colors hover:bg-yellow-500/20"
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
                                                class="flex h-8 w-8 items-center justify-center rounded-lg border border-red-500/20 bg-red-500/10 text-red-400 transition-colors hover:bg-red-500/20"
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
                                                class="h-12 w-12 text-zinc-600"
                                            />
                                            <p class="font-medium text-zinc-500">
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
                                ? 'bg-indigo-500 text-white'
                                : 'bg-white/5 text-zinc-400 hover:bg-white/10',
                            !link.url && 'pointer-events-none opacity-50',
                        ]"
                        v-html="link.label"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

