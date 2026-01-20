<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import {
    Download,
    Mail,
    Search,
    Trash2,
    UserCheck,
    UserX,
} from 'lucide-vue-next';
import { ref } from 'vue';

interface NewsletterSubscriber {
    id: number;
    email: string;
    name: string | null;
    is_active: boolean;
    subscribed_at: string;
    unsubscribed_at: string | null;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    subscribers: {
        data: NewsletterSubscriber[];
        links: any[];
        meta: any;
    };
    stats: {
        total: number;
        active: number;
        inactive: number;
    };
    filters?: {
        status?: string;
        search?: string;
    };
}>();

const searchQuery = ref(props.filters?.search || '');
const statusFilter = ref(props.filters?.status || 'all');

const applyFilters = () => {
    router.get(
        route('admin.newsletter.index'),
        {
            search: searchQuery.value || undefined,
            status:
                statusFilter.value !== 'all' ? statusFilter.value : undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
};

const exportSubscribers = () => {
    window.location.href = route('admin.newsletter.export', {
        status: statusFilter.value !== 'all' ? statusFilter.value : undefined,
    });
};

const deleteSubscriber = (id: number) => {
    if (confirm('Are you sure you want to delete this subscriber?')) {
        router.delete(route('admin.newsletter.destroy', id));
    }
};
</script>

<template>
    <AppLayout
        :breadcrumbs="[
            { title: 'Newsletter', href: route('admin.newsletter.index') },
        ]"
    >
        <Head title="Newsletter Subscribers" />

        <div class="min-h-[calc(100vh-64px)] bg-background p-6 lg:p-10">
            <div class="mx-auto max-w-7xl">
                <!-- Header Section -->
                <div
                    class="mb-12 flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
                >
                    <div>
                        <h2
                            class="bg-gradient-to-r from-foreground to-foreground/50 bg-clip-text text-3xl font-bold tracking-tight text-transparent sm:text-4xl"
                        >
                            Newsletter Subscribers
                        </h2>
                        <p class="mt-2 text-muted-foreground">
                            Manage your newsletter subscribers and export data.
                        </p>
                    </div>
                    <Button
                        @click="exportSubscribers"
                        class="rounded-full bg-primary px-8 py-6 font-bold text-primary-foreground transition-all hover:shadow-xl hover:shadow-primary/20 active:scale-95"
                    >
                        <Download class="mr-2 h-4 w-4" />
                        Export CSV
                    </Button>
                </div>

                <!-- Stats Cards -->
                <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div
                        class="rounded-2xl border border-border bg-card p-6 transition-all hover:border-primary/50"
                    >
                        <div class="flex items-center gap-4">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-500/10"
                            >
                                <Mail class="h-6 w-6 text-blue-500" />
                            </div>
                            <div>
                                <p
                                    class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                >
                                    Total Subscribers
                                </p>
                                <p
                                    class="text-3xl font-black tracking-tight text-foreground"
                                >
                                    {{ stats.total }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-2xl border border-border bg-card p-6 transition-all hover:border-primary/50"
                    >
                        <div class="flex items-center gap-4">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-500/10"
                            >
                                <UserCheck class="h-6 w-6 text-emerald-500" />
                            </div>
                            <div>
                                <p
                                    class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                >
                                    Active
                                </p>
                                <p
                                    class="text-3xl font-black tracking-tight text-foreground"
                                >
                                    {{ stats.active }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-2xl border border-border bg-card p-6 transition-all hover:border-primary/50"
                    >
                        <div class="flex items-center gap-4">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-amber-500/10"
                            >
                                <UserX class="h-6 w-6 text-amber-500" />
                            </div>
                            <div>
                                <p
                                    class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                >
                                    Unsubscribed
                                </p>
                                <p
                                    class="text-3xl font-black tracking-tight text-foreground"
                                >
                                    {{ stats.inactive }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div
                    class="mb-6 flex flex-col gap-4 rounded-2xl border border-border bg-card p-6 md:flex-row"
                >
                    <div class="relative flex-1">
                        <Search
                            class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-muted-foreground"
                        />
                        <input
                            v-model="searchQuery"
                            @keyup.enter="applyFilters"
                            type="text"
                            placeholder="Search by email or name..."
                            class="w-full rounded-lg border border-border bg-background py-2 pr-3 pl-10 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"
                        />
                    </div>
                    <select
                        v-model="statusFilter"
                        @change="applyFilters"
                        class="rounded-lg border border-border bg-background px-4 py-2 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"
                    >
                        <option value="all">All Status</option>
                        <option value="active">Active Only</option>
                        <option value="inactive">Unsubscribed Only</option>
                    </select>
                    <Button
                        @click="applyFilters"
                        variant="outline"
                        class="rounded-lg px-6"
                    >
                        Apply Filters
                    </Button>
                </div>

                <!-- Subscribers Table -->
                <div
                    class="relative overflow-hidden rounded-2xl border border-border bg-card shadow-sm"
                >
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse text-left">
                            <thead>
                                <tr class="border-b border-border bg-muted/50">
                                    <th
                                        class="p-5 text-xs font-bold tracking-wider text-muted-foreground uppercase"
                                    >
                                        Subscriber
                                    </th>
                                    <th
                                        class="p-5 text-xs font-bold tracking-wider text-muted-foreground uppercase"
                                    >
                                        Status
                                    </th>
                                    <th
                                        class="p-5 text-xs font-bold tracking-wider text-muted-foreground uppercase"
                                    >
                                        Subscribed Date
                                    </th>
                                    <th
                                        class="p-5 text-right text-xs font-bold tracking-wider text-muted-foreground uppercase"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border" v-intersect>
                                <tr
                                    v-for="(
                                        subscriber, index
                                    ) in subscribers.data"
                                    :key="subscriber.id"
                                    :class="[
                                        'group reveal transition-colors hover:bg-muted/30',
                                        'delay-' + (index % 10) * 100,
                                    ]"
                                >
                                    <td class="p-5">
                                        <div class="flex flex-col">
                                            <span
                                                class="font-bold text-foreground transition-colors group-hover:text-primary"
                                            >
                                                {{ subscriber.email }}
                                            </span>
                                            <span
                                                v-if="subscriber.name"
                                                class="mt-0.5 text-sm text-muted-foreground"
                                            >
                                                {{ subscriber.name }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="p-5">
                                        <span
                                            v-if="subscriber.is_active"
                                            class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400"
                                        >
                                            Active
                                        </span>
                                        <span
                                            v-else
                                            class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800 dark:bg-amber-900/30 dark:text-amber-400"
                                        >
                                            Unsubscribed
                                        </span>
                                    </td>
                                    <td class="p-5">
                                        <span
                                            class="text-sm text-muted-foreground"
                                        >
                                            {{
                                                new Date(
                                                    subscriber.subscribed_at,
                                                ).toLocaleDateString()
                                            }}
                                        </span>
                                    </td>
                                    <td class="p-5 text-right">
                                        <Button
                                            @click="
                                                deleteSubscriber(subscriber.id)
                                            "
                                            variant="ghost"
                                            size="sm"
                                            class="text-destructive hover:bg-destructive/10 hover:text-destructive"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                    </td>
                                </tr>
                                <tr v-if="subscribers.data.length === 0">
                                    <td colspan="4" class="p-16 text-center">
                                        <div
                                            class="flex flex-col items-center justify-center gap-4"
                                        >
                                            <div
                                                class="flex h-16 w-16 items-center justify-center rounded-full bg-muted text-muted-foreground"
                                            >
                                                <Mail class="h-8 w-8" />
                                            </div>
                                            <p
                                                class="text-lg font-bold text-muted-foreground"
                                            >
                                                No subscribers found.
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
                    v-if="subscribers.links && subscribers.links.length > 3"
                    class="mt-10 flex flex-wrap justify-center gap-2"
                >
                    <Link
                        v-for="(link, key) in subscribers.links"
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
