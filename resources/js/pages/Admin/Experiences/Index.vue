<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Edit, Trash2 } from 'lucide-vue-next';

defineProps<{
    experiences: Array<{
        id: number;
        role: string;
        company: string;
        period: string;
        description: string;
    }>;
}>();
</script>

<template>
    <AppLayout
        :breadcrumbs="[
            { title: 'Experiences', href: route('admin.experiences.index') },
        ]"
    >
        <Head title="Experiences" />

        <div class="min-h-[calc(100vh-64px)] bg-background p-6 lg:p-10">
            <div class="mx-auto max-w-6xl">
                <!-- Header Section -->
                <div
                    class="mb-12 flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
                >
                    <div>
                        <h2
                            class="bg-gradient-to-r from-foreground to-foreground/50 bg-clip-text text-3xl font-bold tracking-tight text-transparent sm:text-4xl"
                        >
                            Career Timeline
                        </h2>
                        <p class="mt-2 text-muted-foreground">
                            Document your professional journey and key
                            milestones.
                        </p>
                    </div>
                    <Link :href="route('admin.experiences.create')">
                        <Button
                            class="rounded-full bg-primary px-8 py-6 font-bold text-primary-foreground transition-all hover:shadow-xl hover:shadow-primary/20 active:scale-95"
                        >
                            Add Experience
                        </Button>
                    </Link>
                </div>

                <!-- Experiences Table/List -->
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
                                        Role & Company
                                    </th>
                                    <th
                                        class="p-5 text-xs font-bold tracking-wider text-muted-foreground uppercase"
                                    >
                                        Period
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
                                    v-for="(experience, index) in experiences"
                                    :key="experience.id"
                                    :class="[
                                        'group reveal transition-colors hover:bg-muted/30',
                                        'delay-' + index * 100,
                                    ]"
                                >
                                    <td class="p-5">
                                        <div class="flex items-start gap-4">
                                            <div
                                                class="mt-1.5 h-2 w-2 rounded-full bg-primary shadow-[0_0_10px_rgba(var(--primary),0.5)]"
                                            ></div>
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-base font-bold text-foreground transition-colors group-hover:text-primary"
                                                >
                                                    {{ experience.role }}
                                                </span>
                                                <span
                                                    class="text-sm font-medium text-muted-foreground"
                                                >
                                                    {{ experience.company }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-5">
                                        <span
                                            class="inline-flex items-center rounded-full border border-border bg-muted px-3 py-1 text-[10px] font-bold tracking-wider text-muted-foreground uppercase"
                                        >
                                            {{ experience.period }}
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
                                                            'admin.experiences.edit',
                                                            experience.id,
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
                                                            'admin.experiences.destroy',
                                                            experience.id,
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
                                <tr v-if="experiences.length === 0">
                                    <td colspan="3" class="p-16 text-center">
                                        <div
                                            class="flex flex-col items-center justify-center gap-4"
                                        >
                                            <div
                                                class="flex h-16 w-16 items-center justify-center rounded-full bg-muted text-muted-foreground"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-8 w-8"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                                    />
                                                </svg>
                                            </div>
                                            <p
                                                class="text-lg font-bold text-muted-foreground"
                                            >
                                                No experience entries found.
                                                Time to record your journey!
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
