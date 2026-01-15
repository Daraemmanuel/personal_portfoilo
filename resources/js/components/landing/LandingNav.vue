<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    canLogin?: boolean;
}>();
</script>

<template>
    <nav
        class="fixed top-0 z-50 w-full border-b border-white/5 bg-black/50 backdrop-blur-md"
    >
        <div
            class="mx-auto flex h-16 max-w-7xl items-center justify-between px-6"
        >
            <Link
                :href="route('home')"
                class="bg-gradient-to-r from-indigo-400 to-cyan-400 bg-clip-text text-xl font-bold tracking-tighter text-transparent"
            >
                DE.
            </Link>
            <div class="hidden gap-8 text-sm font-medium text-zinc-400 md:flex">
                <Link
                    :href="route('home') + '#about'"
                    class="transition-colors hover:text-white"
                >
                    About
                </Link>
                <Link
                    :href="route('home') + '#work'"
                    class="transition-colors hover:text-white"
                >
                    Work
                </Link>
                <Link
                    :href="route('home') + '#skills'"
                    class="transition-colors hover:text-white"
                >
                    Skills
                </Link>
                <Link
                    :href="route('articles.index')"
                    class="transition-colors hover:text-white"
                >
                    Articles
                </Link>
                <Link
                    :href="route('home') + '#contact'"
                    class="transition-colors hover:text-white"
                >
                    Contact
                </Link>
            </div>
            <div class="flex items-center gap-4">
                <!-- <Link
                    v-if="canLogin"
                    href="/login"
                    class="text-sm text-zinc-400 transition-colors hover:text-white"
                >
                    Admin
                </Link> -->
                <a
                    href="mailto:immanuelatwork@gmail.com"
                    class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-black transition-colors hover:bg-zinc-200"
                >
                    Hire Me
                </a>
            </div>
        </div>
    </nav>
</template>
