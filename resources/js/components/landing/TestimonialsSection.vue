<script setup lang="ts">
import type { Testimonial } from '@/types/portfolio';
import { Star } from 'lucide-vue-next';

defineProps<{
    testimonials?: Testimonial[];
}>();

const defaultTestimonials = [
    {
        name: 'John Doe',
        role: 'CEO',
        company: 'TechCorp',
        content: 'DaraEmmanuel delivered exceptional work on our project. Highly recommended!',
        rating: 5,
    },
    {
        name: 'Jane Smith',
        role: 'Product Manager',
        company: 'StartupXYZ',
        content: 'Professional, reliable, and talented developer. Exceeded our expectations.',
        rating: 5,
    },
];
</script>

<template>
    <section id="testimonials" class="bg-zinc-950/50 py-24">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-16 text-center">
                <h2 class="mb-4 text-3xl font-bold">What People Say</h2>
                <p class="text-zinc-400">
                    Testimonials from clients and colleagues
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="testimonial in testimonials && testimonials.length
                        ? testimonials
                        : defaultTestimonials"
                    :key="testimonial.id || testimonial.name"
                    class="rounded-2xl border border-white/5 bg-white/5 p-6 transition-all hover:border-indigo-500/30"
                >
                    <div class="mb-4 flex gap-1">
                        <Star
                            v-for="(star, index) in (testimonial.rating || 5)"
                            :key="`${testimonial.id || testimonial.name}-star-${index}`"
                            class="h-4 w-4 fill-yellow-400 text-yellow-400"
                        />
                    </div>
                    <p class="mb-6 text-zinc-300">
                        "{{ testimonial.content }}"
                    </p>
                    <div class="flex items-center gap-4">
                        <div
                            v-if="testimonial.avatar_url"
                            class="h-12 w-12 overflow-hidden rounded-full"
                        >
                            <img
                                :src="testimonial.avatar_url"
                                :alt="testimonial.name"
                                class="h-full w-full object-cover"
                                loading="lazy"
                            />
                        </div>
                        <div
                            v-else
                            class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-500/20 text-indigo-400"
                        >
                            {{ testimonial.name.charAt(0) }}
                        </div>
                        <div>
                            <div class="font-semibold text-white">
                                {{ testimonial.name }}
                            </div>
                            <div class="text-sm text-zinc-400">
                                {{ testimonial.role }}, {{ testimonial.company }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

