<script setup lang="ts">
import { ArrowRight, Code2, ExternalLink } from 'lucide-vue-next';

defineProps<{
    projects?: Array<{
        title: string;
        description: string;
        tags: string[];
        link: string | null;
    }>;
}>();

const defaultProjects = [
    {
        title: 'LLS (University Portal)',
        description:
            'Next-gen analytics platform with real-time data visualization.',
        tags: ['Blade', 'Laravel', 'React', 'Tailwind'],
        link: 'https://lls.lincoln.edu.ng/login',
    },
    {
        title: 'Quantum Commerce',
        description:
            'Headless e-commerce solution built for speed and scalability.',
        tags: ['Nuxt', 'Stripe', 'Redis'],
        link: '#',
    },
    {
        title: 'Aether UI',
        description: 'A comprehensive Vue component library for modern apps.',
        tags: ['TypeScript', 'Vite', 'Storybook'],
        link: '#',
    },
];
</script>

<template>
    <section id="work" class="relative overflow-hidden py-24">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-16 flex items-end justify-between">
                <div>
                    <h2 class="mb-4 text-3xl font-bold">Selected Work</h2>
                    <p class="text-zinc-400">
                        A showcase of technical complexity and design.
                    </p>
                </div>
                <a
                    href="#"
                    class="hidden items-center gap-2 text-sm text-indigo-400 hover:text-indigo-300 md:flex"
                >
                    View all archives <ArrowRight class="h-4 w-4" />
                </a>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <div
                    v-for="(project, i) in projects || defaultProjects"
                    :key="i"
                    class="group relative overflow-hidden rounded-2xl border border-white/5 bg-zinc-900 transition-all duration-500 hover:border-indigo-500/50"
                >
                    <!-- Placeholder Image Gradient -->
                    <div
                        class="relative flex aspect-video w-full items-center justify-center bg-gradient-to-br from-zinc-800 to-zinc-950 transition-transform duration-700 group-hover:scale-105"
                    >
                        <Code2
                            class="h-12 w-12 text-zinc-700 transition-colors duration-500 group-hover:text-indigo-500"
                        />
                        <div
                            class="absolute inset-0 bg-black/20 transition-colors group-hover:bg-transparent"
                        ></div>
                    </div>

                    <div
                        class="relative z-10 -mt-2 bg-zinc-900/90 p-6 backdrop-blur-sm"
                    >
                        <h3
                            class="mb-2 text-xl font-semibold transition-colors group-hover:text-indigo-400"
                        >
                            {{ project.title }}
                        </h3>
                        <p class="mb-4 text-sm leading-relaxed text-zinc-400">
                            {{ project.description }}
                        </p>
                        <div class="mb-6 flex flex-wrap gap-2">
                            <span
                                v-for="tag in project.tags"
                                :key="tag"
                                class="font-mono text-xs text-zinc-500"
                                >#{{ tag }}</span
                            >
                        </div>
                        <a
                            v-if="project.link"
                            :href="project.link"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 text-sm font-medium text-white transition-colors hover:text-indigo-400"
                        >
                            View Project <ExternalLink class="h-3 w-3" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
