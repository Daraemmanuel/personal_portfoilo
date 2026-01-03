<script setup lang="ts">
defineProps<{
    experiences?: Array<{
        role: string;
        company: string;
        period: string;
        description: string;
    }>;
}>();

const defaultExperiences = [
    {
        role: 'Lead Software Engineer',
        company: 'Lincoln University',
        period: '2025 — Present',
        description:
            'I led the development team to create a modern and user-friendly University portal Called LLS, a large-scale portal supporting thousands of students and staff daily. My work focused on: Designing scalable, production-ready backend architecture, Leading development and coordinating across teams, Ensuring maintainable, user-focused code.',
    },
    {
        role: 'Senior Frontend Engineer',
        company: 'TechCorp Inc.',
        period: '2023 — Present',
        description:
            'Leading the migration of legacy monoliths to modern micro-frontend architectures using Vue 3 and Module Federation.',
    },
    {
        role: 'Full Stack Developer',
        company: 'Creative Agency',
        period: '2021 — 2023',
        description:
            'Built award-winning digital campaigns and high-performance websites for global brands.',
    },
    {
        role: 'UI/UX Designer',
        company: 'Personal Project',
        period: '2021 — 2022',
        description:
            'Developed a responsive and user-friendly UI/UX design for a personal project.',
    },
];
</script>

<template>
    <section class="bg-zinc-950 py-24">
        <div class="mx-auto max-w-3xl px-6">
            <h2 class="mb-12 text-center text-3xl font-bold">Journey</h2>
            <div class="space-y-12">
                <div
                    v-for="(experience, index) in experiences &&
                    experiences.length
                        ? experiences
                        : defaultExperiences"
                    :key="index"
                    class="relative border-l border-white/10 pb-12 pl-8 last:pb-0"
                >
                    <div
                        class="absolute top-0 -left-1.5 h-3 w-3 rounded-full"
                        :class="
                            index === 0
                                ? 'bg-indigo-500 shadow-[0_0_10px_rgba(99,102,241,0.5)]'
                                : 'bg-zinc-700'
                        "
                    ></div>
                    <div
                        class="mb-1 text-sm"
                        :class="
                            index === 0 ? 'text-indigo-400' : 'text-zinc-500'
                        "
                    >
                        {{ experience.period }}
                    </div>
                    <h3 class="mb-2 text-xl font-semibold">
                        {{ experience.role }}
                    </h3>
                    <div class="mb-4 text-zinc-500">
                        {{ experience.company }}
                    </div>
                    <p
                        class="leading-relaxed whitespace-pre-line text-zinc-400"
                    >
                        {{ experience.description }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>
