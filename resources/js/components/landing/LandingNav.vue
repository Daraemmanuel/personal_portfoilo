<script setup lang="ts">
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
            <div
                class="bg-gradient-to-r from-indigo-400 to-cyan-400 bg-clip-text text-xl font-bold tracking-tighter text-transparent"
            >
                DE.
            </div>
            <div class="hidden gap-8 text-sm font-medium text-zinc-400 md:flex">
                <a href="#about" class="transition-colors hover:text-white"
                    >About</a
                >
                <a href="#work" class="transition-colors hover:text-white"
                    >Work</a
                >
                <a href="#skills" class="transition-colors hover:text-white"
                    >Skills</a
                >
                <a href="#contact" class="transition-colors hover:text-white"
                    >Contact</a
                >
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
