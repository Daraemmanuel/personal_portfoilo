<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    projects: Array<{
        id: number;
        title: string;
        description: string;
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

        <div class="min-h-[calc(100vh-64px)] bg-zinc-950 p-6 lg:p-10">
            <div class="mx-auto max-w-6xl">
                <!-- Header Section -->
                <div
                    class="mb-12 flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
                >
                    <div>
                        <h2
                            class="bg-gradient-to-r from-white to-zinc-500 bg-clip-text text-3xl font-bold tracking-tight text-transparent sm:text-4xl"
                        >
                            Project Catalog
                        </h2>
                        <p class="mt-2 text-zinc-400">
                            Manage your featured works and portfolio projects.
                        </p>
                    </div>
                    <Link :href="route('admin.projects.create')">
                        <Button
                            class="rounded-full bg-indigo-600 px-6 py-6 font-semibold text-white transition-all hover:bg-indigo-700 hover:shadow-[0_0_20px_rgba(79,70,229,0.4)]"
                        >
                            Add New Project
                        </Button>
                    </Link>
                </div>

                <!-- Projects Table/List -->
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
                                        Project Info
                                    </th>
                                    <th
                                        class="p-5 text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                    >
                                        Technologies
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
                                    v-for="project in projects"
                                    :key="project.id"
                                    class="group transition-colors hover:bg-white/[0.02]"
                                >
                                    <td class="p-5">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-lg font-bold text-white transition-colors group-hover:text-indigo-400"
                                            >
                                                {{ project.title }}
                                            </span>
                                            <span
                                                class="mt-1 line-clamp-1 max-w-md text-sm text-zinc-500"
                                            >
                                                {{ project.description }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="p-5">
                                        <div class="flex flex-wrap gap-1.5">
                                            <span
                                                v-for="tag in project.tags"
                                                :key="tag"
                                                class="rounded-full border border-white/10 bg-white/5 px-2.5 py-0.5 text-xs font-medium text-zinc-300"
                                            >
                                                {{ tag }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="p-5 text-right">
                                        <div
                                            class="flex items-center justify-end gap-3"
                                        >
                                            <Link
                                                :href="
                                                    route(
                                                        'admin.projects.edit',
                                                        project.id,
                                                    )
                                                "
                                                class="text-sm font-medium text-indigo-400 transition-colors hover:text-indigo-300"
                                            >
                                                Edit
                                            </Link>
                                            <div
                                                class="h-1 w-1 rounded-full bg-zinc-700"
                                            ></div>
                                            <Link
                                                :href="
                                                    route(
                                                        'admin.projects.destroy',
                                                        project.id,
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
                                <tr v-if="projects.length === 0">
                                    <td colspan="3" class="p-12 text-center">
                                        <div
                                            class="flex flex-col items-center justify-center gap-3"
                                        >
                                            <div
                                                class="flex h-12 w-12 items-center justify-center rounded-full bg-white/5 text-zinc-600"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-6 w-6"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
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
                                                class="font-medium text-zinc-500"
                                            >
                                                No projects found. Ready to add
                                                your first masterpiece?
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
