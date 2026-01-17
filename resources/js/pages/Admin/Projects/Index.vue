<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    projects: Array<{
        id: number;
        title: string;
        description: string;
        image_url: string | null;
        link: string | null;
        tags: string[];
    }>;
}>();
</script>

<template>
    <AppLayout
        :breadcrumbs="[
            { title: 'Projects', href: route('admin.projects.index') },
        ]"
    >
        <Head title="Projects" />

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
                            Project Catalog
                        </h2>
                        <p class="mt-2 text-muted-foreground">
                            Manage your featured works and portfolio projects.
                        </p>
                    </div>
                    <Link :href="route('admin.projects.create')">
                        <Button
                            class="rounded-full bg-primary px-8 py-6 font-bold text-primary-foreground transition-all hover:shadow-xl hover:shadow-primary/20 active:scale-95"
                        >
                            Add New Project
                        </Button>
                    </Link>
                </div>

                <!-- Projects Table/List -->
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
                                        Project Info
                                    </th>
                                    <th
                                        class="p-5 text-xs font-bold tracking-wider text-muted-foreground uppercase"
                                    >
                                        Technologies
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
                                    v-for="(project, index) in projects"
                                    :key="project.id"
                                    :class="[
                                        'group reveal transition-colors hover:bg-muted/30',
                                        'delay-' + index * 100,
                                    ]"
                                >
                                    <td class="p-5">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="h-12 w-20 flex-shrink-0 overflow-hidden rounded-lg border border-border bg-muted"
                                            >
                                                <img
                                                    v-if="project.image_url"
                                                    :src="project.image_url"
                                                    class="h-full w-full object-cover"
                                                />
                                                <div
                                                    v-else
                                                    class="flex h-full w-full items-center justify-center text-muted-foreground"
                                                >
                                                    <svg
                                                        class="h-5 w-5"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                        />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-base font-bold text-foreground transition-colors group-hover:text-primary"
                                                >
                                                    {{ project.title }}
                                                </span>
                                                <span
                                                    class="mt-0.5 line-clamp-1 max-w-sm text-sm text-muted-foreground"
                                                >
                                                    {{ project.description }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-5">
                                        <div class="flex flex-wrap gap-1.5">
                                            <span
                                                v-for="(
                                                    tag, index
                                                ) in project.tags"
                                                :key="`${project.id}-tag-${index}-${tag}`"
                                                class="rounded-full border border-border bg-muted px-2.5 py-0.5 text-[10px] font-bold tracking-tight text-muted-foreground uppercase"
                                            >
                                                {{ tag }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="p-5 text-right">
                                        <div
                                            class="flex items-center justify-end gap-4"
                                        >
                                            <Link
                                                :href="
                                                    route(
                                                        'projects.show',
                                                        project.id,
                                                    )
                                                "
                                                target="_blank"
                                                class="text-xs font-bold text-emerald-600 transition-colors hover:underline dark:text-emerald-400"
                                            >
                                                View
                                            </Link>
                                            <Link
                                                :href="
                                                    route(
                                                        'admin.projects.edit',
                                                        project.id,
                                                    )
                                                "
                                                class="text-xs font-bold text-primary transition-colors hover:underline"
                                            >
                                                Edit
                                            </Link>
                                            <Link
                                                :href="
                                                    route(
                                                        'admin.projects.destroy',
                                                        project.id,
                                                    )
                                                "
                                                method="delete"
                                                as="button"
                                                class="text-xs font-bold text-muted-foreground transition-colors hover:text-destructive hover:underline"
                                            >
                                                Delete
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="projects.length === 0">
                                    <td colspan="3" class="p-16 text-center">
                                        <div
                                            class="flex flex-col items-center justify-center gap-4"
                                        >
                                            <div
                                                class="flex h-16 w-16 items-center justify-center rounded-full bg-muted text-muted-foreground"
                                            >
                                                <svg
                                                    class="h-8 w-8"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                                    />
                                                </svg>
                                            </div>
                                            <p
                                                class="text-lg font-bold text-muted-foreground"
                                            >
                                                No projects yet. Start
                                                showcasing your work!
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
