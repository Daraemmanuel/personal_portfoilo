<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import type { Testimonial } from '@/types/portfolio';

defineProps<{
    testimonials: Testimonial[];
}>();
</script>

<template>
    <AppLayout
        :breadcrumbs="[
            { title: 'Testimonials', href: route('admin.testimonials.index') },
        ]"
    >
        <Head title="Testimonials" />

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
                            Client Testimonials
                        </h2>
                        <p class="mt-2 text-zinc-400">
                            Manage testimonials and client feedback.
                        </p>
                    </div>
                    <Link :href="route('admin.testimonials.create')">
                        <Button
                            class="rounded-full bg-indigo-600 px-6 py-6 font-semibold text-white transition-all hover:bg-indigo-700 hover:shadow-[0_0_20px_rgba(79,70,229,0.4)]"
                        >
                            Add Testimonial
                        </Button>
                    </Link>
                </div>

                <!-- Testimonials Table/List -->
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
                                        Testimonial
                                    </th>
                                    <th
                                        class="p-5 text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                    >
                                        Rating
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
                                    v-for="testimonial in testimonials"
                                    :key="testimonial.id"
                                    class="group transition-colors hover:bg-white/[0.02]"
                                >
                                    <td class="p-5">
                                        <div class="flex items-center gap-4">
                                            <div
                                                v-if="testimonial.avatar_url"
                                                class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-full"
                                            >
                                                <img
                                                    :src="testimonial.avatar_url"
                                                    :alt="testimonial.name"
                                                    class="h-full w-full object-cover"
                                                />
                                            </div>
                                            <div
                                                v-else
                                                class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-500/20 text-indigo-400"
                                            >
                                                {{ testimonial.name.charAt(0) }}
                                            </div>
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-lg font-bold text-white transition-colors group-hover:text-indigo-400"
                                                >
                                                    {{ testimonial.name }}
                                                </span>
                                                <span
                                                    class="text-sm text-zinc-500"
                                                >
                                                    {{ testimonial.role }},
                                                    {{ testimonial.company }}
                                                </span>
                                                <span
                                                    class="mt-1 line-clamp-1 max-w-md text-xs text-zinc-600"
                                                >
                                                    {{ testimonial.content }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-5">
                                        <div class="flex gap-1">
                                            <span
                                                v-for="i in 5"
                                                :key="i"
                                                class="text-sm"
                                                :class="
                                                    i <= (testimonial.rating || 5)
                                                        ? 'text-yellow-400'
                                                        : 'text-zinc-700'
                                                "
                                            >
                                                ★
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
                                                        'admin.testimonials.edit',
                                                        testimonial.id,
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
                                                        'admin.testimonials.destroy',
                                                        testimonial.id,
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
                                <tr v-if="testimonials.length === 0">
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
                                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                                                    />
                                                </svg>
                                            </div>
                                            <p
                                                class="font-medium text-zinc-500"
                                            >
                                                No testimonials found. Start
                                                collecting feedback!
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

