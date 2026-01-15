<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import type { ContactMessage } from '@/types/portfolio';
import { Mail, Calendar, User } from 'lucide-vue-next';

defineProps<{
    message: ContactMessage;
}>();
</script>

<template>
    <AppLayout
        :breadcrumbs="[
            {
                title: 'Contact Messages',
                href: route('admin.contact-messages.index'),
            },
            { title: 'View Message', href: '#' },
        ]"
    >
        <Head title="View Message" />

        <div class="min-h-[calc(100vh-64px)] bg-zinc-950 p-6 lg:p-10">
            <div class="mx-auto max-w-4xl">
                <!-- Header -->
                <div class="mb-8 flex items-center justify-between">
                    <div>
                        <h2
                            class="bg-gradient-to-r from-white to-zinc-500 bg-clip-text text-3xl font-bold tracking-tight text-transparent"
                        >
                            Contact Message
                        </h2>
                        <p class="mt-2 text-zinc-400">
                            View message details and respond if needed.
                        </p>
                    </div>
                    <Link :href="route('admin.contact-messages.index')">
                        <Button
                            variant="ghost"
                            class="text-zinc-400 hover:bg-white/5 hover:text-white"
                        >
                            Back to Messages
                        </Button>
                    </Link>
                </div>

                <!-- Message Card -->
                <div
                    class="rounded-2xl border border-white/10 bg-zinc-900/50 p-8 backdrop-blur-sm"
                >
                    <div class="space-y-6">
                        <!-- Message Header -->
                        <div class="border-b border-white/5 pb-6">
                            <div class="mb-4 flex items-center gap-3">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-500/20 text-indigo-400"
                                >
                                    <User class="h-6 w-6" />
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white">
                                        {{ message.name }}
                                    </h3>
                                    <p class="text-sm text-zinc-400">
                                        {{ message.email }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center gap-4 text-sm text-zinc-500">
                                <div class="flex items-center gap-2">
                                    <Mail class="h-4 w-4" />
                                    <span>{{ message.subject }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <Calendar class="h-4 w-4" />
                                    <span>{{
                                        new Date(
                                            message.created_at,
                                        ).toLocaleString()
                                    }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Message Content -->
                        <div>
                            <h4
                                class="mb-3 text-sm font-semibold uppercase tracking-wider text-zinc-500"
                            >
                                Message
                            </h4>
                            <div
                                class="rounded-lg border border-white/5 bg-white/5 p-6 text-zinc-300 whitespace-pre-wrap"
                            >
                                {{ message.message }}
                            </div>
                        </div>

                        <!-- Actions -->
                        <div
                            class="flex items-center justify-end gap-4 border-t border-white/5 pt-6"
                        >
                            <a
                                :href="`mailto:${message.email}?subject=Re: ${message.subject}`"
                                class="rounded-lg bg-indigo-600 px-6 py-2 text-sm font-semibold text-white transition-all hover:bg-indigo-700"
                            >
                                Reply via Email
                            </a>
                            <Link
                                :href="
                                    route(
                                        'admin.contact-messages.destroy',
                                        message.id,
                                    )
                                "
                                method="delete"
                                as="button"
                                class="rounded-lg border border-red-500/20 bg-red-500/10 px-6 py-2 text-sm font-semibold text-red-400 transition-all hover:bg-red-500/20"
                            >
                                Delete Message
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

