<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Testimonial } from '@/types/portfolio';
import { Head, Link } from '@inertiajs/vue3';
import { Edit, Trash2 } from 'lucide-vue-next';

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
                            Client Testimonials
                        </h2>
                        <p class="mt-2 text-muted-foreground">
                            Manage testimonials and client feedback.
                        </p>
                    </div>
                    <Link :href="route('admin.testimonials.create')">
                        <Button
                            class="rounded-full bg-primary px-8 py-6 font-bold text-primary-foreground transition-all hover:shadow-xl hover:shadow-primary/20 active:scale-95"
                        >
                            Add Testimonial
                        </Button>
                    </Link>
                </div>

                <!-- Testimonials Table/List -->
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
                                        Testimonial
                                    </th>
                                    <th
                                        class="p-5 text-xs font-bold tracking-wider text-muted-foreground uppercase"
                                    >
                                        Rating
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
                                    v-for="(testimonial, index) in testimonials"
                                    :key="testimonial.id"
                                    :class="[
                                        'group reveal transition-colors hover:bg-muted/30',
                                        'delay-' + index * 100,
                                    ]"
                                >
                                    <td class="p-5">
                                        <div class="flex items-center gap-4">
                                            <div
                                                v-if="testimonial.avatar_url"
                                                class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-full border border-border"
                                            >
                                                <img
                                                    :src="
                                                        testimonial.avatar_url
                                                    "
                                                    :alt="testimonial.name"
                                                    class="h-full w-full object-cover"
                                                />
                                            </div>
                                            <div
                                                v-else
                                                class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 font-bold text-primary"
                                            >
                                                {{ testimonial.name.charAt(0) }}
                                            </div>
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-base font-bold text-foreground transition-colors group-hover:text-primary"
                                                >
                                                    {{ testimonial.name }}
                                                </span>
                                                <span
                                                    class="text-xs font-medium text-muted-foreground"
                                                >
                                                    {{ testimonial.role }} •
                                                    {{ testimonial.company }}
                                                </span>
                                                <span
                                                    class="mt-1 line-clamp-1 max-w-md text-xs text-muted-foreground/70 italic"
                                                >
                                                    "{{ testimonial.content }}"
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-5">
                                        <div class="flex gap-0.5">
                                            <span
                                                v-for="i in 5"
                                                :key="i"
                                                class="text-sm"
                                                :class="
                                                    i <=
                                                    (testimonial.rating || 5)
                                                        ? 'text-yellow-500'
                                                        : 'text-muted-foreground/30'
                                                "
                                            >
                                                ★
                                            </span>
                                        </div>
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
                                                            'admin.testimonials.edit',
                                                            testimonial.id,
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
                                                            'admin.testimonials.destroy',
                                                            testimonial.id,
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
                                <tr v-if="testimonials.length === 0">
                                    <td colspan="3" class="p-16 text-center">
                                        <div
                                            class="flex flex-col items-center justify-center gap-4 text-muted-foreground"
                                        >
                                            <div
                                                class="flex h-16 w-16 items-center justify-center rounded-full bg-muted"
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
                                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                                                    />
                                                </svg>
                                            </div>
                                            <p class="text-lg font-bold">
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
