<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Briefcase, Clock, Zap } from 'lucide-vue-next';

defineProps<{
    stats: {
        projects: number;
        skills: number;
        experiences: number;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const statCards = [
    {
        title: 'Projects',
        value: 'projects',
        icon: Briefcase,
        color: 'bg-indigo-500',
    },
    {
        title: 'Skills',
        value: 'skills',
        icon: Zap,
        color: 'bg-amber-500',
    },
    {
        title: 'Experience',
        value: 'experiences',
        icon: Clock,
        color: 'bg-emerald-500',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Command Center" />

        <div class="min-h-[calc(100vh-64px)] bg-zinc-950 p-6 lg:p-10">
            <div class="mx-auto max-w-6xl">
                <!-- Header Section -->
                <div class="mb-12">
                    <h2
                        class="bg-gradient-to-r from-white to-zinc-500 bg-clip-text text-3xl font-bold tracking-tight text-transparent sm:text-4xl"
                    >
                        Command Center
                    </h2>
                    <p class="mt-2 text-zinc-400">
                        Welcome back. Your portfolio is currently available and
                        trending.
                    </p>
                </div>

                <!-- Stats Grid -->
                <div class="mb-10 grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div
                        v-for="card in statCards"
                        :key="card.title"
                        class="group relative overflow-hidden rounded-2xl border border-white/10 bg-zinc-900/50 p-6 backdrop-blur-sm transition-all hover:border-indigo-500/50"
                    >
                        <div
                            class="absolute top-0 right-0 p-4 opacity-10 transition-opacity group-hover:opacity-20"
                        >
                            <component
                                :is="card.icon"
                                class="h-12 w-12 text-white"
                            />
                        </div>
                        <h4
                            class="text-sm font-semibold tracking-widest text-zinc-500 uppercase"
                        >
                            {{ card.title }}
                        </h4>
                        <div class="mt-4 flex items-baseline gap-2">
                            <span
                                class="text-4xl font-bold tracking-tight text-white"
                            >
                                {{
                                    stats[card.value as keyof typeof stats] || 0
                                }}
                            </span>
                            <span class="text-sm font-medium text-indigo-400"
                                >Active Items</span
                            >
                        </div>
                    </div>
                </div>

                <!-- Main Content Row -->
                <div
                    class="relative min-h-[400px] rounded-2xl border border-white/10 bg-zinc-900/50 p-8 backdrop-blur-sm"
                >
                    <div
                        class="flex h-full flex-col items-center justify-center space-y-4 text-center"
                    >
                        <div
                            class="flex h-16 w-16 items-center justify-center rounded-full bg-indigo-500/10 text-indigo-500"
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
                                    d="M13 10V3L4 14h7v7l9-11h-7z"
                                />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white">
                            System Operational
                        </h3>
                        <p class="max-w-md text-zinc-400">
                            Everything is running smoothly. Your portfolio is
                            currently utilizing the latest architectural
                            standards.
                        </p>
                        <div class="flex gap-4 pt-4">
                            <Link :href="route('admin.projects.index')">
                                <Button
                                    class="rounded-full border border-white/10 bg-white/5 px-6 py-2 text-sm font-semibold text-white transition-colors hover:bg-white/10"
                                >
                                    Manage Works
                                </Button>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
