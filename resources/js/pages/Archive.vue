<script setup lang="ts">
import ContactFooter from '@/components/landing/ContactFooter.vue';
import LandingNav from '@/components/landing/LandingNav.vue';
import type { Project } from '@/types/portfolio';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, ArrowRight, Code2, ExternalLink } from 'lucide-vue-next';

defineProps<{
    projects: Project[];
    canLogin?: boolean;
}>();
</script>

<template>
    <Head title="Project Archive" />

    <div
        class="min-h-screen bg-background font-sans text-foreground antialiased"
    >
        <LandingNav :can-login="canLogin" />

        <main class="py-20 sm:py-32">
            <div class="mx-auto max-w-7xl px-4 sm:px-6">
                <!-- Header -->
                <div class="fade-in-up mb-16 animate-in">
                    <Link
                        :href="route('home')"
                        class="mb-6 inline-flex items-center gap-2 text-sm font-medium text-muted-foreground transition-colors hover:text-primary"
                    >
                        <ArrowLeft class="h-4 w-4" /> Back to Home
                    </Link>
                    <h1 class="text-4xl font-bold tracking-tight sm:text-5xl">
                        Project Archive
                    </h1>
                    <p class="mt-4 max-w-2xl text-lg text-muted-foreground">
                        A collection of past work, experiments, and older
                        projects.
                    </p>
                </div>

                <!-- Projects Grid -->
                <div
                    class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3"
                >
                    <div
                        v-for="(project, index) in projects"
                        :key="project.id"
                        v-intersect.once
                        class="reveal group relative overflow-hidden rounded-2xl border border-border bg-card transition-all duration-300 hover:-translate-y-2 hover:border-indigo-500/50 hover:shadow-xl hover:shadow-indigo-500/10"
                        :style="{ transitionDelay: `${index * 100}ms` }"
                    >
                        <!-- Project Image -->
                        <div
                            class="aspect-video w-full overflow-hidden bg-muted"
                        >
                            <img
                                v-if="project.image_url"
                                :src="project.image_url"
                                :alt="project.title"
                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                            />
                            <div
                                v-else
                                class="flex h-full w-full items-center justify-center text-muted-foreground"
                            >
                                <Code2 class="h-10 w-10 opacity-50" />
                            </div>
                        </div>

                        <div class="p-6">
                            <h3 class="text-xl font-bold">
                                {{ project.title }}
                            </h3>
                            <div
                                class="mt-2 line-clamp-2 text-sm text-muted-foreground"
                                v-html="project.description"
                            ></div>

                            <div class="mt-4 flex flex-wrap gap-2">
                                <span
                                    v-for="tag in project.tags"
                                    :key="tag"
                                    class="rounded-full bg-muted px-2 py-0.5 text-xs font-medium text-muted-foreground"
                                >
                                    {{ tag }}
                                </span>
                            </div>

                            <div class="mt-6 flex items-center gap-4">
                                <a
                                    v-if="project.link"
                                    :href="project.link"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center gap-2 text-sm font-medium text-primary hover:underline"
                                >
                                    View Project
                                    <ExternalLink class="h-3 w-3" />
                                </a>
                                <Link
                                    :href="route('projects.show', project.id)"
                                    class="inline-flex items-center gap-2 text-sm font-medium text-muted-foreground hover:text-primary hover:underline"
                                >
                                    Details <ArrowRight class="h-3 w-3" />
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div
                    v-if="projects.length === 0"
                    class="py-20 text-center text-muted-foreground"
                >
                    <p class="text-lg">No archived projects found.</p>
                </div>
            </div>
        </main>

        <ContactFooter />
    </div>
</template>
