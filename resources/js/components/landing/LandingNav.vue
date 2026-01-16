<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useAppearance } from '@/composables/useAppearance';
import { Moon, Sun, Monitor } from 'lucide-vue-next';
import SearchBar from './SearchBar.vue';

defineProps<{
    canLogin?: boolean;
}>();

const { appearance, updateAppearance } = useAppearance();
</script>

<template>
    <nav
        class="fixed top-0 z-50 w-full border-b border-white/5 bg-black/50 backdrop-blur-md"
    >
        <div
            class="mx-auto flex h-16 max-w-7xl items-center justify-between px-6"
        >
            <a
                href="/"
                class="bg-gradient-to-r from-indigo-400 to-cyan-400 bg-clip-text text-xl font-bold tracking-tighter text-transparent"
            >
                DE.
            </a>
            <div class="hidden gap-8 text-sm font-medium text-zinc-400 md:flex">
                <a
                    href="/#about"
                    class="transition-colors hover:text-white"
                >
                    About
                </a>
                <a
                    href="/#work"
                    class="transition-colors hover:text-white"
                >
                    Work
                </a>
                <a
                    href="/#skills"
                    class="transition-colors hover:text-white"
                >
                    Skills
                </a>
                <Link
                    :href="route('articles.index')"
                    class="transition-colors hover:text-white"
                >
                    Articles
                </Link>
                <a
                    href="/#contact"
                    class="transition-colors hover:text-white"
                >
                    Contact
                </a>
            </div>
            <div class="flex items-center gap-4">
                <SearchBar />
                <!-- Dark Mode Toggle -->
                <div class="flex items-center gap-1 rounded-lg bg-white/5 p-1">
                    <button
                        @click="updateAppearance('light')"
                        :class="[
                            'rounded-md p-1.5 transition-colors',
                            appearance === 'light'
                                ? 'bg-white/10 text-white'
                                : 'text-zinc-400 hover:text-white',
                        ]"
                        title="Light mode"
                    >
                        <Sun class="h-4 w-4" />
                    </button>
                    <button
                        @click="updateAppearance('dark')"
                        :class="[
                            'rounded-md p-1.5 transition-colors',
                            appearance === 'dark'
                                ? 'bg-white/10 text-white'
                                : 'text-zinc-400 hover:text-white',
                        ]"
                        title="Dark mode"
                    >
                        <Moon class="h-4 w-4" />
                    </button>
                    <button
                        @click="updateAppearance('system')"
                        :class="[
                            'rounded-md p-1.5 transition-colors',
                            appearance === 'system'
                                ? 'bg-white/10 text-white'
                                : 'text-zinc-400 hover:text-white',
                        ]"
                        title="System preference"
                    >
                        <Monitor class="h-4 w-4" />
                    </button>
                </div>
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
