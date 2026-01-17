<script setup lang="ts">
import LandingNav from '@/components/landing/LandingNav.vue';
import type { Project } from '@/types/portfolio';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Calendar, ExternalLink } from 'lucide-vue-next';

defineProps<{
    project: Project;
}>();
</script>

<template>
    <Head :title="`${project.title} - Project`" />

    <div
        class="min-h-screen bg-background font-sans text-foreground antialiased selection:bg-indigo-500 selection:text-white"
    >
        <LandingNav />

        <div class="mx-auto max-w-4xl px-6 py-24">
            <Link
                :href="route('home')"
                class="mb-8 inline-flex items-center gap-2 text-muted-foreground transition-colors hover:text-foreground"
            >
                <ArrowLeft class="h-4 w-4" />
                Back to Portfolio
            </Link>

            <article>
                <header class="mb-8">
                    <h1
                        class="mb-4 bg-gradient-to-b from-foreground to-foreground/50 bg-clip-text text-4xl font-bold text-transparent md:text-5xl"
                    >
                        {{ project.title }}
                    </h1>
                    <div
                        class="flex flex-wrap items-center gap-4 text-sm text-muted-foreground"
                    >
                        <div class="flex items-center gap-2">
                            <Calendar class="h-4 w-4" />
                            <span>{{
                                new Date(
                                    project.created_at,
                                ).toLocaleDateString()
                            }}</span>
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

                <div class="prose dark:prose-invert max-w-none">
                    <p
                        class="mb-6 text-lg leading-relaxed text-muted-foreground"
                    >
                        {{ project.description }}
                    </p>
                </div>

                <div v-if="project.tags && project.tags.length" class="mb-8">
                    <h3 class="mb-4 text-lg font-semibold text-foreground">
                        Technologies
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        <span
                            v-for="(tag, index) in project.tags"
                            :key="`${project.id}-tag-${index}-${tag}`"
                            class="rounded-lg border border-border bg-card px-3 py-1 text-sm text-muted-foreground"
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
                        class="inline-flex items-center gap-2 rounded-xl bg-primary px-6 py-3 font-semibold text-primary-foreground transition-all hover:scale-105"
                    >
                        View Project
                        <ExternalLink class="h-4 w-4" />
                    </a>
                </div>
            </article>
        </div>
    </div>
</template>
