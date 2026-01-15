<script setup lang="ts">
import { ArrowRight } from 'lucide-vue-next';
</script>

<template>
    <section
        id="about"
        class="relative flex min-h-screen items-center justify-center overflow-hidden pt-16"
    >
        <!-- Background Elements -->
        <div class="absolute inset-0 z-0">
            <div
                class="absolute top-1/4 left-1/4 h-96 w-96 animate-pulse rounded-full bg-indigo-600/20 blur-3xl"
            ></div>
            <div
                class="absolute right-1/4 bottom-1/4 h-96 w-96 animate-pulse rounded-full bg-cyan-600/20 blur-3xl delay-1000"
            ></div>
        </div>

        <div class="relative z-10 mx-auto max-w-5xl px-6 text-center">
            <h1
                class="mb-8 bg-gradient-to-b from-white via-white to-white/50 bg-clip-text text-5xl font-bold tracking-tight text-transparent md:text-8xl"
            >
                Crafting Digital <br />
                <span class="text-indigo-400">Masterpieces</span>
            </h1>
            <p
                class="mx-auto mb-10 max-w-2xl text-lg leading-relaxed text-zinc-400 md:text-xl"
            >
                I'm DaraEmmanuel, a Software Engineer specializing in building
                exceptional digital experiences. I blend architectural precision
                with aesthetic excellence using modern technologies.
            </p>
            <div
                class="flex flex-col items-center justify-center gap-4 md:flex-row"
            >
                <a
                    href="#work"
                    class="group flex items-center gap-2 rounded-full bg-indigo-600 px-8 py-3 font-medium text-white transition-all hover:bg-indigo-700"
                >
                    View Projects
                    <ArrowRight
                        class="h-4 w-4 transition-transform group-hover:translate-x-1"
                    />
                </a>
                <a
                    href="#contact"
                    class="rounded-full border border-white/10 bg-white/5 px-8 py-3 font-medium text-zinc-300 transition-colors hover:bg-white/10"
                >
                    Contact Me
                </a>
            </div>
        </div>
    </section>
</template>
