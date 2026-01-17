<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';

import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Briefcase, Clock, FileText, Mail, Star, Zap } from 'lucide-vue-next';

defineProps<{
    stats: {
        projects: number;
        skills: number;
        experiences: number;
        testimonials: number;
        articles: number;
        contact_messages: number;
        unread_messages: number;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
];

const statCards = [
    {
        title: 'Projects',
        value: 'projects',
        icon: Briefcase,
        color: 'bg-indigo-500',
        href: route('admin.projects.index'),
    },
    {
        title: 'Skills',
        value: 'skills',
        icon: Zap,
        color: 'bg-amber-500',
        href: route('admin.skills.index'),
    },
    {
        title: 'Experiences',
        value: 'experiences',
        icon: Clock,
        color: 'bg-emerald-500',
        href: route('admin.experiences.index'),
    },
    {
        title: 'Testimonials',
        value: 'testimonials',
        icon: Star,
        color: 'bg-purple-500',
        href: route('admin.testimonials.index'),
    },
    {
        title: 'Articles',
        value: 'articles',
        icon: FileText,
        color: 'bg-blue-500',
        href: route('admin.articles.index'),
    },
    {
        title: 'Messages',
        value: 'contact_messages',
        icon: Mail,
        color: 'bg-pink-500',
        href: route('admin.contact-messages.index'),
        badge: 'unread_messages',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Command Center" />

        <div class="min-h-[calc(100vh-64px)] bg-background p-6 lg:p-10">
            <div class="mx-auto max-w-6xl">
                <!-- Header Section -->
                <div class="mb-12">
                    <h2
                        class="bg-gradient-to-r from-foreground to-foreground/50 bg-clip-text text-3xl font-bold tracking-tight text-transparent sm:text-4xl"
                    >
                        Command Center
                    </h2>
                    <p class="mt-2 text-muted-foreground">
                        Welcome back. Your portfolio is currently available and
                        trending.
                    </p>
                </div>

                <!-- Stats Grid -->
                <div
                    class="mb-10 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
                    v-intersect
                >
                    <Link
                        v-for="(card, index) in statCards"
                        :key="card.title"
                        :href="card.href"
                        :class="[
                            'group relative overflow-hidden rounded-2xl border border-border bg-card p-6 transition-all hover:border-primary/50 hover:shadow-lg hover:shadow-primary/5 active:scale-[0.98]',
                            'reveal delay-' + (index * 100 + 100),
                        ]"
                    >
                        <div
                            class="absolute top-0 right-0 p-4 opacity-10 transition-opacity group-hover:opacity-20"
                        >
                            <component
                                :is="card.icon"
                                class="h-12 w-12 text-foreground"
                            />
                        </div>
                        <div class="flex items-start justify-between">
                            <div>
                                <h4
                                    class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                >
                                    {{ card.title }}
                                </h4>
                                <div class="mt-4 flex items-baseline gap-2">
                                    <span
                                        class="text-4xl font-black tracking-tight text-foreground"
                                    >
                                        {{
                                            stats[
                                                card.value as keyof typeof stats
                                            ] || 0
                                        }}
                                    </span>
                                    <span
                                        class="text-sm font-semibold text-primary"
                                        >Total</span
                                    >
                                </div>
                            </div>
                            <div
                                v-if="
                                    card.badge &&
                                    stats[card.badge as keyof typeof stats] > 0
                                "
                                class="flex h-6 w-6 animate-pulse items-center justify-center rounded-full bg-destructive text-xs font-bold text-destructive-foreground"
                            >
                                {{ stats[card.badge as keyof typeof stats] }}
                            </div>
                        </div>
                    </Link>
                </div>

                <!-- Main Content Row -->
                <div
                    class="reveal relative min-h-[400px] rounded-2xl border border-border bg-card p-8 transition-colors delay-700"
                    v-intersect
                >
                    <div
                        class="flex h-full flex-col items-center justify-center space-y-4 text-center"
                    >
                        <div
                            class="flex h-16 w-16 items-center justify-center rounded-full bg-primary/10 text-primary"
                        >
                            <Zap class="h-8 w-8" />
                        </div>
                        <h3 class="text-xl font-bold text-foreground">
                            System Operational
                        </h3>
                        <p class="max-w-md text-muted-foreground">
                            Everything is running smoothly. Your portfolio is
                            currently utilizing the latest architectural
                            standards.
                        </p>
                        <div class="flex gap-4 pt-4">
                            <Link :href="route('admin.projects.index')">
                                <Button
                                    variant="outline"
                                    class="rounded-full px-8 py-6 font-bold transition-all hover:scale-105 active:scale-95"
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
