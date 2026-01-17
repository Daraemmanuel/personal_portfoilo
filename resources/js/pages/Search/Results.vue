<script setup lang="ts">
import LandingNav from '@/components/landing/LandingNav.vue';
import SearchBar from '@/components/landing/SearchBar.vue';
import type { Article, Project } from '@/types/portfolio';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRight, Calendar, Code2, FileText, Search } from 'lucide-vue-next';

defineProps<{
    query: string;
    type: string;
    articles: Article[];
    projects: Project[];
    total: number;
}>();
</script>

<template>
    <Head :title="`Search${query ? ': ' + query : ''}`" />

    <div
        class="min-h-screen bg-background font-sans text-foreground antialiased selection:bg-indigo-500 selection:text-white"
    >
        <LandingNav />

        <div class="mx-auto max-w-6xl px-6 py-24">
            <header class="mb-12">
                <div class="mb-6 flex items-center justify-center">
                    <SearchBar :initial-query="query" />
                </div>
                <h1
                    class="mb-4 bg-gradient-to-b from-foreground to-foreground/50 bg-clip-text text-center text-4xl font-bold text-transparent md:text-5xl"
                >
                    Search Results
                </h1>
                <p v-if="query" class="text-center text-muted-foreground">
                    Found {{ total }} result{{ total !== 1 ? 's' : '' }} for
                    "<span class="font-semibold text-foreground">{{
                        query
                    }}</span
                    >"
                </p>
                <p v-else class="text-center text-muted-foreground">
                    Enter a search query to find articles and projects
                </p>
            </header>

            <div v-if="!query" class="text-center text-muted-foreground">
                <Search class="mx-auto mb-4 h-12 w-12 opacity-50" />
                <p>Start typing to search...</p>
            </div>

            <div
                v-else-if="total === 0"
                class="text-center text-muted-foreground"
            >
                <FileText class="mx-auto mb-4 h-12 w-12 opacity-50" />
                <p>No results found for "{{ query }}"</p>
                <p class="mt-2 text-sm text-muted-foreground/60">
                    Try different keywords or check your spelling
                </p>
            </div>

            <div v-else class="space-y-12">
                <!-- Articles Section -->
                <section v-if="articles.length > 0">
                    <h2 class="mb-6 text-2xl font-semibold text-foreground">
                        Articles ({{ articles.length }})
                    </h2>
                    <div
                        class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
                    >
                        <article
                            v-for="article in articles"
                            :key="article.id"
                            class="group overflow-hidden rounded-2xl border border-border bg-card transition-all hover:border-indigo-500/50"
                        >
                            <Link :href="route('articles.show', article.slug)">
                                <div
                                    v-if="article.featured_image_url"
                                    class="aspect-video overflow-hidden"
                                >
                                    <img
                                        :src="article.featured_image_url"
                                        :alt="article.title"
                                        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        loading="lazy"
                                    />
                                </div>
                                <div class="p-6">
                                    <div
                                        v-if="article.category"
                                        class="mb-2 text-xs font-semibold text-indigo-500 uppercase dark:text-indigo-400"
                                    >
                                        {{ article.category }}
                                    </div>
                                    <h3
                                        class="mb-3 text-xl font-semibold transition-colors group-hover:text-indigo-500 dark:group-hover:text-indigo-400"
                                    >
                                        {{ article.title }}
                                    </h3>
                                    <p
                                        class="mb-4 line-clamp-2 text-sm text-muted-foreground"
                                    >
                                        {{ article.excerpt }}
                                    </p>
                                    <div
                                        class="flex items-center gap-2 text-xs text-muted-foreground/60"
                                    >
                                        <Calendar class="h-3 w-3" />
                                        <span v-if="article.published_at">
                                            {{
                                                new Date(
                                                    article.published_at,
                                                ).toLocaleDateString()
                                            }}
                                        </span>
                                        <ArrowRight
                                            class="ml-auto h-4 w-4 transition-transform group-hover:translate-x-1"
                                        />
                                    </div>
                                </div>
                            </Link>
                        </article>
                    </div>
                </section>

                <!-- Projects Section -->
                <section v-if="projects.length > 0">
                    <h2 class="mb-6 text-2xl font-semibold text-foreground">
                        Projects ({{ projects.length }})
                    </h2>
                    <div
                        class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
                    >
                        <article
                            v-for="project in projects"
                            :key="project.id"
                            class="group overflow-hidden rounded-2xl border border-border bg-card transition-all hover:border-indigo-500/50"
                        >
                            <Link :href="route('projects.show', project.id)">
                                <div
                                    v-if="project.image_url"
                                    class="aspect-video overflow-hidden"
                                >
                                    <img
                                        :src="project.image_url"
                                        :alt="project.title"
                                        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        loading="lazy"
                                    />
                                </div>
                                <div
                                    v-else
                                    class="flex aspect-video items-center justify-center bg-muted"
                                >
                                    <Code2
                                        class="h-12 w-12 text-muted-foreground"
                                    />
                                </div>
                                <div class="p-6">
                                    <h3
                                        class="mb-3 text-xl font-semibold transition-colors group-hover:text-indigo-500 dark:group-hover:text-indigo-400"
                                    >
                                        {{ project.title }}
                                    </h3>
                                    <p
                                        class="mb-4 line-clamp-2 text-sm text-muted-foreground"
                                    >
                                        {{ project.description }}
                                    </p>
                                    <div
                                        v-if="
                                            project.tags && project.tags.length
                                        "
                                        class="mb-4 flex flex-wrap gap-2"
                                    >
                                        <span
                                            v-for="(
                                                tag, index
                                            ) in project.tags.slice(0, 3)"
                                            :key="`${project.id}-tag-${index}-${tag}`"
                                            class="font-mono text-xs text-muted-foreground/60"
                                        >
                                            #{{ tag }}
                                        </span>
                                    </div>
                                    <ArrowRight
                                        class="h-4 w-4 text-muted-foreground/60 transition-transform group-hover:translate-x-1"
                                    />
                                </div>
                            </Link>
                        </article>
                    </div>
                </section>
            </div>
        </div>
    </div>
</template>
