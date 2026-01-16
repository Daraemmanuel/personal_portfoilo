<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Search, Calendar, ArrowRight, FileText, Code2 } from 'lucide-vue-next';
import type { Article, Project } from '@/types/portfolio';
import LandingNav from '@/components/landing/LandingNav.vue';
import SearchBar from '@/components/landing/SearchBar.vue';

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
        class="min-h-screen bg-black font-sans text-white antialiased selection:bg-indigo-500 selection:text-white"
    >
        <LandingNav />

        <div class="mx-auto max-w-6xl px-6 py-24">
            <header class="mb-12">
                <div class="mb-6 flex items-center justify-center">
                    <SearchBar :initial-query="query" />
                </div>
                <h1
                    class="mb-4 bg-gradient-to-b from-white to-zinc-500 bg-clip-text text-center text-4xl font-bold text-transparent md:text-5xl"
                >
                    Search Results
                </h1>
                <p v-if="query" class="text-center text-zinc-400">
                    Found {{ total }} result{{ total !== 1 ? 's' : '' }} for
                    "<span class="font-semibold text-white">{{ query }}</span>"
                </p>
                <p v-else class="text-center text-zinc-400">
                    Enter a search query to find articles and projects
                </p>
            </header>

            <div v-if="!query" class="text-center text-zinc-500">
                <Search class="mx-auto mb-4 h-12 w-12 opacity-50" />
                <p>Start typing to search...</p>
            </div>

            <div v-else-if="total === 0" class="text-center text-zinc-500">
                <FileText class="mx-auto mb-4 h-12 w-12 opacity-50" />
                <p>No results found for "{{ query }}"</p>
                <p class="mt-2 text-sm">Try different keywords or check your spelling</p>
            </div>

            <div v-else class="space-y-12">
                <!-- Articles Section -->
                <section v-if="articles.length > 0">
                    <h2 class="mb-6 text-2xl font-semibold text-white">
                        Articles ({{ articles.length }})
                    </h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                        <article
                            v-for="article in articles"
                            :key="article.id"
                            class="group overflow-hidden rounded-2xl border border-white/5 bg-zinc-900 transition-all hover:border-indigo-500/50"
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
                                        class="mb-2 text-xs font-semibold uppercase text-indigo-400"
                                    >
                                        {{ article.category }}
                                    </div>
                                    <h3
                                        class="mb-3 text-xl font-semibold transition-colors group-hover:text-indigo-400"
                                    >
                                        {{ article.title }}
                                    </h3>
                                    <p class="mb-4 line-clamp-2 text-sm text-zinc-400">
                                        {{ article.excerpt }}
                                    </p>
                                    <div class="flex items-center gap-2 text-xs text-zinc-500">
                                        <Calendar class="h-3 w-3" />
                                        <span
                                            v-if="article.published_at"
                                        >
                                            {{
                                                new Date(
                                                    article.published_at
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
                    <h2 class="mb-6 text-2xl font-semibold text-white">
                        Projects ({{ projects.length }})
                    </h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                        <article
                            v-for="project in projects"
                            :key="project.id"
                            class="group overflow-hidden rounded-2xl border border-white/5 bg-zinc-900 transition-all hover:border-indigo-500/50"
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
                                    class="flex aspect-video items-center justify-center bg-gradient-to-br from-zinc-800 to-zinc-950"
                                >
                                    <Code2 class="h-12 w-12 text-zinc-700" />
                                </div>
                                <div class="p-6">
                                    <h3
                                        class="mb-3 text-xl font-semibold transition-colors group-hover:text-indigo-400"
                                    >
                                        {{ project.title }}
                                    </h3>
                                    <p class="mb-4 line-clamp-2 text-sm text-zinc-400">
                                        {{ project.description }}
                                    </p>
                                    <div
                                        v-if="project.tags && project.tags.length"
                                        class="mb-4 flex flex-wrap gap-2"
                                    >
                                        <span
                                            v-for="(tag, index) in project.tags.slice(0, 3)"
                                            :key="`${project.id}-tag-${index}-${tag}`"
                                            class="font-mono text-xs text-zinc-500"
                                        >
                                            #{{ tag }}
                                        </span>
                                    </div>
                                    <ArrowRight
                                        class="h-4 w-4 text-zinc-500 transition-transform group-hover:translate-x-1"
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

