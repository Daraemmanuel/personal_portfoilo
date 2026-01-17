<script setup lang="ts">
import type { Testimonial } from '@/types/portfolio';
import { Star } from 'lucide-vue-next';

defineProps<{
    testimonials?: Testimonial[];
}>();

const defaultTestimonials: any[] = [
    {
        id: 0,
        name: 'John Doe',
        role: 'CEO',
        company: 'TechCorp',
        content:
            'DaraEmmanuel delivered exceptional work on our project. Highly recommended!',
        rating: 5,
        avatar_url: null,
    },
    {
        id: 0,
        name: 'Jane Smith',
        role: 'Product Manager',
        company: 'StartupXYZ',
        content:
            'Professional, reliable, and talented developer. Exceeded our expectations.',
        rating: 5,
        avatar_url: null,
    },
];
</script>

<template>
    <section id="testimonials" class="bg-muted/30 py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="mb-12 text-center sm:mb-16">
                <h2 class="mb-2 text-2xl font-bold sm:mb-4 sm:text-3xl">What People Say</h2>
                <p class="text-sm text-muted-foreground sm:text-base">
                    Testimonials from clients and colleagues
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="(testimonial, index) in testimonials &&
                    testimonials.length
                        ? testimonials
                        : defaultTestimonials"
                    :key="testimonial.id || testimonial.name"
                    v-intersect.once
                    class="reveal rounded-2xl border border-border bg-card p-6 transition-all hover:-translate-y-2 hover:border-indigo-500/30 hover:shadow-lg hover:shadow-indigo-500/10"
                    :style="{ transitionDelay: `${index * 200}ms` }"
                >
                    <div class="mb-4 flex gap-1">
                        <Star
                            v-for="(star, index) in testimonial.rating || 5"
                            :key="`${testimonial.id || testimonial.name}-star-${index}`"
                            class="h-4 w-4 fill-yellow-500 text-yellow-500"
                        />
                    </div>
                    <p class="mb-6 leading-relaxed text-foreground/80 italic">
                        &ldquo;{{ testimonial.content }}&rdquo;
                    </p>
                    <div class="flex items-center gap-4">
                        <div
                            v-if="testimonial.avatar_url"
                            class="h-12 w-12 overflow-hidden rounded-full border-2 border-border"
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
                            class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-500/10 font-bold text-indigo-500"
                        >
                            {{ testimonial.name.charAt(0) }}
                        </div>
                        <div>
                            <div class="font-bold text-foreground">
                                {{ testimonial.name }}
                            </div>
                            <div
                                class="text-sm font-medium text-muted-foreground"
                            >
                                {{ testimonial.role }},
                                {{ testimonial.company }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
