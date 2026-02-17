<script setup lang="ts">
import type { Project } from '@/types/portfolio';
import { Link } from '@inertiajs/vue3';
import { ArrowRight, Code2, ExternalLink } from 'lucide-vue-next';

defineProps<{
    projects?: Project[];
}>();

const defaultProjects: any[] = [
    {
        id: 0,
        title: 'LLS (University Portal)',
        description:
            'Next-gen analytics platform with real-time data visualization.',
        tags: ['Blade', 'Laravel', 'React', 'Tailwind'],
        link: 'https://lls.lincoln.edu.ng/login',
        image_url: null,
    },
    {
        id: 0,
        title: 'Quantum Commerce',
        description:
            'Headless e-commerce solution built for speed and scalability.',
        tags: ['Nuxt', 'Stripe', 'Redis'],
        link: '#',
        image_url: null,
    },
    {
        id: 0,
        title: 'Aether UI',
        description: 'A comprehensive Vue component library for modern apps.',
        tags: ['TypeScript', 'Vite', 'Storybook'],
        link: '#',
        image_url: null,
    },
];
const stripHtml = (html: string) => {
    if (typeof document === 'undefined') return html;
    const tmp = document.createElement('DIV');
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || '';
};
</script>

<template>
    <section id="work" class="relative overflow-hidden py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div
                class="mb-12 flex flex-col items-start justify-between gap-4 sm:mb-16 sm:flex-row sm:items-end"
            >
                <div>
                    <h2 class="mb-2 text-2xl font-bold sm:mb-4 sm:text-3xl">
                        Selected Work
                    </h2>
                    <p class="text-sm text-muted-foreground sm:text-base">
                        A showcase of technical complexity and design.
                    </p>
                </div>
                <Link
                    :href="route('archive')"
                    class="flex items-center gap-2 text-xs text-indigo-500 hover:text-indigo-400 sm:text-sm"
                >
                    View all archives
                    <ArrowRight class="h-3 w-3 sm:h-4 sm:w-4" />
                </Link>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <div
                    v-for="(project, index) in projects || defaultProjects"
                    :key="project.id || project.title"
                    v-intersect.once
                    class="reveal group relative overflow-hidden rounded-2xl border border-border bg-card transition-all duration-500 hover:-translate-y-2 hover:border-indigo-500/50 hover:shadow-2xl hover:shadow-indigo-500/10"
                    :style="{ transitionDelay: `${index * 200}ms` }"
                >
                    <Link
                        v-if="project.id"
                        :href="route('projects.show', project.id)"
                        class="absolute inset-0 z-10"
                        aria-label="View project: {{ project.title }}"
                    />
                    <!-- Project Image/Placeholder -->
                    <div
                        class="relative flex aspect-video w-full items-center justify-center bg-muted transition-transform duration-700 group-hover:scale-105"
                    >
                        <img
                            v-if="project.image_url"
                            :src="project.image_url"
                            :alt="`${project.title} project screenshot`"
                            loading="lazy"
                            class="absolute inset-0 h-full w-full object-cover opacity-60 transition-opacity duration-500 group-hover:opacity-100"
                        />
                        <Code2
                            v-else
                            class="h-12 w-12 text-muted transition-colors duration-500 group-hover:text-indigo-500"
                        />
                        <div
                            class="absolute inset-0 bg-background/20 transition-colors group-hover:bg-transparent"
                        ></div>
                    </div>

                    <div
                        class="relative z-10 -mt-2 bg-card/90 p-6 backdrop-blur-sm transition-colors group-hover:bg-card"
                    >
                        <h3
                            class="mb-2 text-xl font-semibold transition-colors group-hover:text-indigo-500"
                        >
                            {{ project.title }}
                        </h3>
                        <div
                            class="mb-4 line-clamp-2 text-sm leading-relaxed text-muted-foreground"
                        >
                            {{ stripHtml(project.description) }}
                        </div>
                        <div class="mb-6 flex flex-wrap gap-2">
                            <span
                                v-for="(tag, index) in project.tags"
                                :key="`${project.id || project.title}-tag-${index}-${tag}`"
                                class="font-mono text-xs text-muted-foreground/70"
                                >#{{ tag }}</span
                            >
                        </div>
                        <div class="flex items-center gap-4">
                            <a
                                v-if="project.link"
                                :href="project.link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-2 text-sm font-medium text-foreground transition-all hover:text-indigo-500"
                                @click.stop
                            >
                                View Project <ExternalLink class="h-3 w-3" />
                            </a>
                            <Link
                                v-if="project.id"
                                :href="route('projects.show', project.id)"
                                class="inline-flex items-center gap-2 text-sm font-medium text-muted-foreground transition-all hover:text-indigo-500"
                            >
                                Learn More <ArrowRight class="h-3 w-3" />
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
