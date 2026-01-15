<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import type { ContactMessage } from '@/types/portfolio';
import { Mail, MailOpen } from 'lucide-vue-next';

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

        <div class="min-h-[calc(100vh-64px)] bg-zinc-950 p-6 lg:p-10">
            <div class="mx-auto max-w-6xl">
                <!-- Header Section -->
                <div class="mb-12">
                    <h2
                        class="bg-gradient-to-r from-white to-zinc-500 bg-clip-text text-3xl font-bold tracking-tight text-transparent sm:text-4xl"
                    >
                        Contact Messages
                    </h2>
                    <p class="mt-2 text-zinc-400">
                        View and manage messages from your contact form.
                    </p>
                </div>

                <!-- Messages Table/List -->
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
                                        From
                                    </th>
                                    <th
                                        class="p-5 text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                    >
                                        Subject
                                    </th>
                                    <th
                                        class="p-5 text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                    >
                                        Date
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
                                    v-for="message in messages.data"
                                    :key="message.id"
                                    class="group transition-colors hover:bg-white/[0.02]"
                                    :class="
                                        !message.read_at
                                            ? 'bg-indigo-500/5'
                                            : ''
                                    "
                                >
                                    <td class="p-5">
                                        <div class="flex items-center gap-3">
                                            <component
                                                :is="
                                                    message.read_at
                                                        ? MailOpen
                                                        : Mail
                                                "
                                                class="h-4 w-4"
                                                :class="
                                                    message.read_at
                                                        ? 'text-zinc-600'
                                                        : 'text-indigo-400'
                                                "
                                            />
                                            <div class="flex flex-col">
                                                <span
                                                    class="font-semibold text-white"
                                                >
                                                    {{ message.name }}
                                                </span>
                                                <span
                                                    class="text-sm text-zinc-500"
                                                >
                                                    {{ message.email }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-5">
                                        <span
                                            class="font-medium text-white"
                                            :class="
                                                !message.read_at
                                                    ? 'font-bold'
                                                    : ''
                                            "
                                        >
                                            {{ message.subject }}
                                        </span>
                                    </td>
                                    <td class="p-5">
                                        <span
                                            class="text-sm text-zinc-500"
                                        >
                                            {{
                                                new Date(
                                                    message.created_at,
                                                ).toLocaleDateString()
                                            }}
                                        </span>
                                    </td>
                                    <td class="p-5 text-right">
                                        <div
                                            class="flex items-center justify-end gap-3"
                                        >
                                            <Link
                                                :href="
                                                    route(
                                                        'admin.contact-messages.show',
                                                        message.id,
                                                    )
                                                "
                                                class="text-sm font-medium text-indigo-400 transition-colors hover:text-indigo-300"
                                            >
                                                View
                                            </Link>
                                            <div
                                                class="h-1 w-1 rounded-full bg-zinc-700"
                                            ></div>
                                            <Link
                                                :href="
                                                    route(
                                                        'admin.contact-messages.destroy',
                                                        message.id,
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
                                <tr v-if="messages.data.length === 0">
                                    <td colspan="4" class="p-12 text-center">
                                        <div
                                            class="flex flex-col items-center justify-center gap-3"
                                        >
                                            <div
                                                class="flex h-12 w-12 items-center justify-center rounded-full bg-white/5 text-zinc-600"
                                            >
                                                <Mail class="h-6 w-6" />
                                            </div>
                                            <p
                                                class="font-medium text-zinc-500"
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
                    class="mt-6 flex justify-center gap-2"
                >
                    <Link
                        v-for="link in messages.links"
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

