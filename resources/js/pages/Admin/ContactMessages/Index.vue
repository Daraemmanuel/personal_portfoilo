<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import type { ContactMessage } from '@/types/portfolio';
import { Head, Link } from '@inertiajs/vue3';
import { Eye, Mail, MailOpen, Trash2 } from 'lucide-vue-next';

defineProps<{
    messages: {
        data: ContactMessage[];
        links: any;
        meta: any;
    };
}>();
</script>

<template>
    <AppLayout
        :breadcrumbs="[
            {
                title: 'Contact Messages',
                href: route('admin.contact-messages.index'),
            },
        ]"
    >
        <Head title="Contact Messages" />

        <div class="min-h-[calc(100vh-64px)] bg-background p-6 lg:p-10">
            <div class="mx-auto max-w-7xl">
                <!-- Header Section -->
                <div class="mb-12">
                    <h2
                        class="bg-gradient-to-r from-foreground to-foreground/50 bg-clip-text text-3xl font-bold tracking-tight text-transparent sm:text-4xl"
                    >
                        Contact Messages
                    </h2>
                    <p class="mt-2 text-muted-foreground">
                        View and manage messages from your contact form.
                    </p>
                </div>

                <!-- Messages Table/List -->
                <div
                    class="relative overflow-hidden rounded-2xl border border-border bg-card shadow-sm transition-colors"
                >
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse text-left">
                            <thead>
                                <tr class="border-b border-border bg-muted/50">
                                    <th
                                        class="p-5 text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                    >
                                        From
                                    </th>
                                    <th
                                        class="p-5 text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                    >
                                        Subject
                                    </th>
                                    <th
                                        class="p-5 text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                    >
                                        Date
                                    </th>
                                    <th
                                        class="p-5 text-right text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border" v-intersect>
                                <tr
                                    v-for="(message, index) in messages.data"
                                    :key="message.id"
                                    class="group reveal transition-colors hover:bg-muted/30"
                                    :class="[
                                        !message.read_at ? 'bg-primary/5' : '',
                                        'delay-' + index * 100,
                                    ]"
                                >
                                    <td class="p-5">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full transition-colors"
                                                :class="
                                                    message.read_at
                                                        ? 'bg-muted text-muted-foreground'
                                                        : 'bg-primary/10 text-primary'
                                                "
                                            >
                                                <component
                                                    :is="
                                                        message.read_at
                                                            ? MailOpen
                                                            : Mail
                                                    "
                                                    class="h-5 w-5"
                                                />
                                            </div>
                                            <div class="flex min-w-0 flex-col">
                                                <span
                                                    class="truncate font-bold text-foreground"
                                                >
                                                    {{ message.name }}
                                                </span>
                                                <span
                                                    class="truncate text-xs font-medium text-muted-foreground"
                                                >
                                                    {{ message.email }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-5">
                                        <span
                                            class="line-clamp-1 text-sm text-foreground"
                                            :class="
                                                !message.read_at
                                                    ? 'font-bold'
                                                    : 'font-medium'
                                            "
                                        >
                                            {{ message.subject }}
                                        </span>
                                    </td>
                                    <td class="p-5">
                                        <span
                                            class="text-sm font-medium text-muted-foreground"
                                        >
                                            {{
                                                new Date(
                                                    message.created_at,
                                                ).toLocaleDateString(
                                                    undefined,
                                                    {
                                                        month: 'short',
                                                        day: 'numeric',
                                                        year: 'numeric',
                                                    },
                                                )
                                            }}
                                        </span>
                                    </td>
                                    <td class="p-5 text-right">
                                        <div
                                            class="flex items-center justify-end gap-2"
                                        >
                                            <Button
                                                as-child
                                                variant="ghost"
                                                size="sm"
                                                class="text-primary hover:bg-primary/10"
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'admin.contact-messages.show',
                                                            message.id,
                                                        )
                                                    "
                                                >
                                                    <Eye class="h-4 w-4" />
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
                                                            'admin.contact-messages.destroy',
                                                            message.id,
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
                                <tr v-if="messages.data.length === 0">
                                    <td colspan="4" class="p-20 text-center">
                                        <div
                                            class="flex flex-col items-center justify-center gap-4"
                                        >
                                            <div
                                                class="flex h-16 w-16 items-center justify-center rounded-full bg-muted text-muted-foreground"
                                            >
                                                <Mail class="h-8 w-8" />
                                            </div>
                                            <p
                                                class="text-lg font-medium text-muted-foreground"
                                            >
                                                No messages yet.
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
                    v-if="messages.links && messages.links.length > 3"
                    class="mt-10 flex justify-center gap-3"
                >
                    <Link
                        v-for="link in messages.links"
                        :key="link.label"
                        :href="link.url || '#'"
                        :class="[
                            'rounded-xl px-5 py-2.5 text-sm font-bold shadow-sm transition-all',
                            link.active
                                ? 'scale-105 bg-primary text-primary-foreground shadow-primary/20'
                                : 'border border-border bg-card text-muted-foreground hover:bg-muted hover:text-foreground',
                            !link.url && 'pointer-events-none opacity-50',
                        ]"
                        v-html="link.label"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
