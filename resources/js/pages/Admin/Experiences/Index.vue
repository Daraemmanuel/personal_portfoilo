<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

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
                            Career Timeline
                        </h2>
                        <p class="mt-2 text-zinc-400">
                            Document your professional journey and key
                            milestones.
                        </p>
                    </div>
                    <Link :href="route('admin.experiences.create')">
                        <Button
                            class="rounded-full bg-indigo-600 px-6 py-6 font-semibold text-white transition-all hover:bg-indigo-700 hover:shadow-[0_0_20px_rgba(79,70,229,0.4)]"
                        >
                            Add Experience
                        </Button>
                    </Link>
                </div>

                <!-- Experiences Table/List -->
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
                                        Role & Company
                                    </th>
                                    <th
                                        class="p-5 text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                    >
                                        Period
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
                                    v-for="experience in experiences"
                                    :key="experience.id"
                                    class="group transition-colors hover:bg-white/[0.02]"
                                >
                                    <td class="p-5">
                                        <div class="flex items-start gap-4">
                                            <div
                                                class="mt-1 h-2 w-2 rounded-full bg-indigo-500 shadow-[0_0_10px_rgba(99,102,241,0.5)]"
                                            ></div>
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-lg font-bold text-white transition-colors group-hover:text-indigo-400"
                                                >
                                                    {{ experience.role }}
                                                </span>
                                                <span
                                                    class="text-sm text-zinc-500"
                                                >
                                                    {{ experience.company }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-5">
                                        <span
                                            class="inline-flex items-center rounded-full border border-white/5 bg-white/5 px-3 py-1 text-xs font-medium text-zinc-400"
                                        >
                                            {{ experience.period }}
                                        </span>
                                    </td>
                                    <td class="p-5 text-right">
                                        <div
                                            class="flex items-center justify-end gap-3"
                                        >
                                            <Link
                                                :href="
                                                    route(
                                                        'admin.experiences.edit',
                                                        experience.id,
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
                                                        'admin.experiences.destroy',
                                                        experience.id,
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
                                <tr v-if="experiences.length === 0">
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
                                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                                    />
                                                </svg>
                                            </div>
                                            <p
                                                class="font-medium text-zinc-500"
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
