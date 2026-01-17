<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { ArrowRight } from 'lucide-vue-next';
import { computed } from 'vue';

const page = usePage();
const cvUrl = computed(() => {
    return (page.props as any).cvUrl || null;
});
</script>

<template>
    <section
        id="about"
        class="relative flex min-h-screen items-center justify-center overflow-hidden pt-16"
    >
        <!-- Background Elements -->
        <div class="absolute inset-0 z-0 opacity-30 dark:opacity-100">
            <div
                class="floating absolute top-[10%] left-[10%] h-[30rem] w-[30rem] animate-pulse rounded-full bg-indigo-500/10 blur-3xl dark:bg-indigo-600/10"
            ></div>
            <div
                class="floating-delayed absolute right-[10%] bottom-[10%] h-[25rem] w-[25rem] animate-pulse rounded-full bg-cyan-500/10 blur-3xl delay-1000 dark:bg-cyan-600/10"
            ></div>
            <div
                class="absolute top-1/2 left-1/2 h-[40rem] w-[40rem] -translate-x-1/2 -translate-y-1/2 rounded-full bg-primary/5 blur-3xl"
            ></div>
        </div>

        <div class="relative z-10 mx-auto max-w-5xl px-4 text-center sm:px-6">
            <h1
                class="fade-in-up mb-6 animate-in bg-gradient-to-b from-foreground via-foreground to-foreground/50 bg-clip-text text-4xl font-bold tracking-tight text-transparent sm:mb-8 sm:text-5xl md:text-6xl lg:text-8xl"
            >
                Crafting Digital <br class="hidden sm:block" />
                <span class="text-indigo-600 dark:text-indigo-400"
                    >Masterpieces</span
                >
            </h1>
            <p
                class="fade-in-up mx-auto mb-8 max-w-2xl animate-in text-base leading-relaxed text-muted-foreground delay-200 sm:mb-10 sm:text-lg md:text-xl"
            >
                I'm DaraEmmanuel, a Software Engineer specializing in building
                exceptional digital experiences. I blend architectural precision
                with aesthetic excellence using modern technologies.
            </p>
            <div
                class="fade-in-up flex animate-in flex-col items-center justify-center gap-3 delay-400 sm:flex-row sm:gap-4"
            >
                <a
                    href="#work"
                    class="group flex w-full items-center justify-center gap-2 rounded-full bg-indigo-600 px-6 py-2.5 text-sm font-medium text-white transition-all hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-500/20 sm:w-auto sm:px-8 sm:py-3"
                >
                    View Projects
                    <ArrowRight
                        class="h-4 w-4 transition-transform group-hover:translate-x-1"
                    />
                </a>
                <a
                    :href="cvUrl || '#contact'"
                    :class="[
                        'flex w-full items-center justify-center gap-2 rounded-full border border-border bg-muted/50 px-6 py-2.5 text-sm font-medium text-muted-foreground transition-all hover:border-foreground/20 hover:bg-muted hover:text-foreground sm:w-auto sm:px-8 sm:py-3',
                    ]"
                    :download="cvUrl ? true : false"
                >
                    <svg
                        v-if="cvUrl"
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                        />
                    </svg>
                    {{ cvUrl ? 'Download CV' : 'Contact Me' }}
                </a>
            </div>
        </div>
    </section>
</template>
