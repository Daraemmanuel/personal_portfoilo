<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import type { ActivityLog } from '@/types/portfolio';
import { Head, Link } from '@inertiajs/vue3';
import { Activity, Clock, Database, Globe, User } from 'lucide-vue-next';

defineProps<{
    logs: {
        data: ActivityLog[];
        links: any[];
        meta: any;
    };
    filters?: any;
}>();

const formatModelType = (type: string) => {
    return type.split('\\').pop() || type;
};
</script>

<template>
    <AppLayout
        :breadcrumbs="[
            {
                title: 'Activity Logs',
                href: route('admin.activity-logs.index'),
            },
        ]"
    >
        <Head title="Activity Logs" />

        <div class="min-h-[calc(100vh-64px)] bg-background p-6 lg:p-10">
            <div class="mx-auto max-w-7xl">
                <!-- Header -->
                <div class="mb-12">
                    <h2
                        class="bg-gradient-to-r from-foreground to-foreground/50 bg-clip-text text-3xl font-bold tracking-tight text-transparent sm:text-4xl"
                    >
                        System Activity
                    </h2>
                    <p class="mt-2 text-muted-foreground">
                        Monitor user actions and system events.
                    </p>
                </div>

                <!-- Logs Table -->
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
                                        User / IP
                                    </th>
                                    <th
                                        class="p-5 text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                    >
                                        Action
                                    </th>
                                    <th
                                        class="p-5 text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                    >
                                        Target
                                    </th>
                                    <th
                                        class="p-5 text-right text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                    >
                                        Date
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border" v-intersect>
                                <tr
                                    v-for="(log, index) in logs.data"
                                    :key="log.id"
                                    :class="[
                                        'group reveal transition-colors hover:bg-muted/30',
                                        'delay-' + (index % 10) * 100,
                                    ]"
                                >
                                    <td class="p-5">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary"
                                            >
                                                <User
                                                    v-if="log.user"
                                                    class="h-5 w-5"
                                                />
                                                <Globe v-else class="h-5 w-5" />
                                            </div>
                                            <div class="flex flex-col">
                                                <span
                                                    class="font-bold text-foreground"
                                                >
                                                    {{
                                                        log.user
                                                            ? log.user.name
                                                            : 'Guest'
                                                    }}
                                                </span>
                                                <span
                                                    class="text-xs font-medium text-muted-foreground"
                                                >
                                                    {{
                                                        log.ip_address ||
                                                        'Unknown IP'
                                                    }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-5">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="inline-flex items-center rounded-full border border-border bg-muted px-2.5 py-0.5 text-xs font-bold tracking-tight text-foreground transition-colors group-hover:bg-primary/10 group-hover:text-primary"
                                            >
                                                {{ log.action }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="p-5">
                                        <div class="flex flex-col gap-1">
                                            <span
                                                class="flex items-center gap-1.5 text-sm font-medium text-foreground"
                                            >
                                                <Database
                                                    class="h-3.5 w-3.5 text-muted-foreground"
                                                />
                                                {{
                                                    formatModelType(
                                                        log.model_type || '',
                                                    )
                                                }}
                                            </span>
                                            <span
                                                class="text-xs text-muted-foreground"
                                                >ID: {{ log.model_id }}</span
                                            >
                                        </div>
                                    </td>
                                    <td class="p-5 text-right">
                                        <div
                                            class="flex items-center justify-end gap-2 text-sm text-muted-foreground"
                                        >
                                            <Clock class="h-3.5 w-3.5" />
                                            <span>
                                                {{
                                                    new Date(
                                                        log.created_at,
                                                    ).toLocaleString()
                                                }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="logs.data.length === 0">
                                    <td colspan="4" class="p-16 text-center">
                                        <div
                                            class="flex flex-col items-center justify-center gap-4"
                                        >
                                            <div
                                                class="flex h-16 w-16 items-center justify-center rounded-full bg-muted text-muted-foreground"
                                            >
                                                <Activity class="h-8 w-8" />
                                            </div>
                                            <p
                                                class="text-lg font-bold text-muted-foreground"
                                            >
                                                No activity logs found.
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
                    v-if="logs.links && logs.links.length > 3"
                    class="mt-10 flex flex-wrap justify-center gap-2"
                >
                    <Link
                        v-for="(link, key) in logs.links"
                        :key="key"
                        :href="link.url || '#'"
                        :class="[
                            'rounded-xl px-4 py-2 text-sm font-bold shadow-sm transition-all',
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
