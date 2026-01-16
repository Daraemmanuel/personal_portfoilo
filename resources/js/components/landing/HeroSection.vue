<script setup lang="ts">
import { computed } from 'vue';
import { ArrowRight } from 'lucide-vue-next';
import { usePage } from '@inertiajs/vue3';

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
                    :href="cvUrl || '#contact'"
                    :class="[
                        'rounded-full border border-white/10 bg-white/5 px-8 py-3 font-medium text-zinc-300 transition-colors hover:bg-white/10',
                        cvUrl && 'flex items-center gap-2'
                    ]"
                    :download="cvUrl ? true : false"
                >
                    <svg v-if="cvUrl" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ cvUrl ? 'Download CV' : 'Contact Me' }}
                </a>
            </div>
        </div>
    </section>
</template>
