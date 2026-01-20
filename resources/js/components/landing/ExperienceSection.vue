<script setup lang="ts">
import type { Experience } from '@/types/portfolio';

defineProps<{
    experiences?: Experience[];
}>();

const defaultExperiences = [
    {
        id: 0,
        role: 'Lead Software Engineer',
        company: 'Lincoln University',
        period: '2025 — Present',
        description:
            'I led the development team to create a modern and user-friendly University portal Called LLS, a large-scale portal supporting thousands of students and staff daily. My work focused on: Designing scalable, production-ready backend architecture, Leading development and coordinating across teams, Ensuring maintainable, user-focused code.',
    },
    {
        id: 0,
        role: 'Senior Frontend Engineer',
        company: 'TechCorp Inc.',
        period: '2023 — Present',
        description:
            'Leading the migration of legacy monoliths to modern micro-frontend architectures using Vue 3 and Module Federation.',
    },
    {
        id: 0,
        role: 'Full Stack Developer',
        company: 'Creative Agency',
        period: '2021 — 2023',
        description:
            'Built award-winning digital campaigns and high-performance websites for global brands.',
    },
    {
        id: 0,
        role: 'UI/UX Designer',
        company: 'Personal Project',
        period: '2021 — 2022',
        description:
            'Developed a responsive and user-friendly UI/UX design for a personal project.',
    },
];
</script>

<template>
    <section class="bg-muted/30 py-16 sm:py-24">
        <div class="mx-auto max-w-3xl px-4 sm:px-6">
            <h2 class="mb-8 text-center text-2xl font-bold sm:mb-12 sm:text-3xl">Journey</h2>
            <div class="space-y-8 sm:space-y-12">
                <div
                    v-for="(experience, index) in experiences &&
                    experiences.length
                        ? experiences
                        : defaultExperiences"
                    :key="experience.id || index"
                    v-intersect.once
                    class="reveal relative border-l border-border pb-8 pl-6 last:pb-0 sm:pb-12 sm:pl-8"
                    :style="{ transitionDelay: `${index * 150}ms` }"
                >
                    <div
                        class="absolute top-0 -left-1.5 h-3 w-3 rounded-full"
                        :class="
                            index === 0
                                ? 'bg-indigo-500 shadow-[0_0_15px_rgba(99,102,241,0.5)]'
                                : 'bg-muted-foreground/30'
                        "
                    ></div>
                    <div
                        class="mb-1 text-xs font-semibold tracking-wide sm:text-sm"
                        :class="
                            index === 0
                                ? 'text-indigo-500'
                                : 'text-muted-foreground/60'
                        "
                    >
                        {{ experience.period }}
                    </div>
                    <h3 class="mb-1 text-lg font-semibold sm:mb-2 sm:text-xl">
                        {{ experience.role }}
                    </h3>
                    <div class="mb-3 text-sm font-medium text-muted-foreground sm:mb-4 sm:text-base">
                        {{ experience.company }}
                    </div>
                    <div
                        class="prose prose-sm dark:prose-invert max-w-none text-sm leading-relaxed text-muted-foreground/80 sm:text-base"
                        v-html="experience.description"
                    ></div>
                </div>
            </div>
        </div>
    </section>
</template>
