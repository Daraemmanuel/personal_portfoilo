<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, ExternalLink, Calendar } from 'lucide-vue-next';
import type { Project } from '@/types/portfolio';
import LandingNav from '@/components/landing/LandingNav.vue';

defineProps<{
    project: Project;
}>();
</script>

<template>
    <Head :title="`${project.title} - Project`" />

    <div
        class="min-h-screen bg-black font-sans text-white antialiased selection:bg-indigo-500 selection:text-white"
    >
        <LandingNav />

        <div class="mx-auto max-w-4xl px-6 py-24">
            <Link
                :href="route('home')"
                class="mb-8 inline-flex items-center gap-2 text-zinc-400 transition-colors hover:text-white"
            >
                <ArrowLeft class="h-4 w-4" />
                Back to Portfolio
            </Link>

            <article>
                <header class="mb-8">
                    <h1
                        class="mb-4 bg-gradient-to-b from-white to-zinc-500 bg-clip-text text-4xl font-bold text-transparent md:text-5xl"
                    >
                        {{ project.title }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-4 text-sm text-zinc-400">
                        <div class="flex items-center gap-2">
                            <Calendar class="h-4 w-4" />
                            <span>{{ new Date(project.created_at).toLocaleDateString() }}</span>
                        </div>
                    </div>
                </header>

                <div
                    v-if="project.image_url"
                    class="mb-8 overflow-hidden rounded-2xl"
                >
                    <img
                        :src="project.image_url"
                        :alt="project.title"
                        class="h-auto w-full object-cover"
                        loading="lazy"
                    />
                </div>

                <div class="prose prose-invert max-w-none">
                    <p class="mb-6 text-lg leading-relaxed text-zinc-300">
                        {{ project.description }}
                    </p>
                </div>

                <div v-if="project.tags && project.tags.length" class="mb-8">
                    <h3 class="mb-4 text-lg font-semibold">Technologies</h3>
                    <div class="flex flex-wrap gap-2">
                        <span
                            v-for="(tag, index) in project.tags"
                            :key="`${project.id}-tag-${index}-${tag}`"
                            class="rounded-lg border border-white/10 bg-white/5 px-3 py-1 text-sm text-zinc-300"
                        >
                            #{{ tag }}
                        </span>
                    </div>
                </div>

                <div v-if="project.link" class="mt-8">
                    <a
                        :href="project.link"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3 font-semibold text-black transition-all hover:scale-105"
                    >
                        View Project
                        <ExternalLink class="h-4 w-4" />
                    </a>
                </div>
            </article>
        </div>
    </div>
</template>

