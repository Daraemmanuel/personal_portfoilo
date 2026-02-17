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
                class="fade-in-up mb-8 inline-flex animate-in items-center gap-2 text-muted-foreground transition-colors hover:text-foreground"
            >
                <ArrowLeft class="h-4 w-4" />
                Back to Portfolio
            </Link>

            <article>
                <header class="fade-in-up mb-8 animate-in delay-150">
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
                    class="fade-in-up mb-8 animate-in overflow-hidden rounded-2xl delay-200"
                >
                    <img
                        :src="project.image_url"
                        :alt="project.title"
                        class="h-auto w-full object-cover transition-transform duration-500 hover:scale-105"
                        loading="lazy"
                    />
                </div>

                <div
                    class="fade-in-up prose dark:prose-invert max-w-none animate-in delay-300"
                    v-html="project.description"
                ></div>

                <div
                    v-if="project.tags && project.tags.length"
                    class="fade-in-up mb-8 animate-in delay-400"
                >
                    <h3 class="mb-4 text-lg font-semibold text-foreground">
                        Technologies
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        <span
                            v-for="(tag, index) in project.tags"
                            :key="`${project.id}-tag-${index}-${tag}`"
                            class="reveal rounded-lg border border-border bg-card px-3 py-1 text-sm text-muted-foreground transition-all hover:border-indigo-500/50 hover:bg-indigo-500/10 hover:text-indigo-500"
                            :style="{ transitionDelay: `${index * 50}ms` }"
                        >
                            #{{ tag }}
                        </span>
                    </div>
                </div>

                <div
                    v-if="project.link"
                    class="fade-in-up mt-8 animate-in delay-500"
                >
                    <a
                        :href="project.link"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group inline-flex items-center gap-2 rounded-xl bg-primary px-6 py-3 font-semibold text-primary-foreground transition-all hover:scale-105 active:scale-95"
                    >
                        View Project
                        <ExternalLink
                            class="h-4 w-4 transition-transform group-hover:translate-x-1"
                        />
                    </a>
                </div>
            </article>
        </div>
    </div>
</template>
