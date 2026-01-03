<script setup lang="ts">
import { Github, Linkedin, Twitter } from 'lucide-vue-next';
</script>

<template>
    <footer id="contact" class="border-t border-white/5 bg-black py-24">
        <div class="mx-auto max-w-7xl px-6 text-center">
            <h2
                class="mb-8 bg-gradient-to-b from-white to-zinc-500 bg-clip-text text-4xl font-bold text-transparent md:text-5xl"
            >
                Let's Build Something Amazing
            </h2>
            <p class="mx-auto mb-10 max-w-xl text-zinc-400">
                Whether you have a question or just want to say hi, I'll try my
                best to get back to you!
            </p>
            <a
                href="mailto:immanuelatwork@gmail.com"
                class="inline-block rounded-xl bg-white px-8 py-4 font-bold text-black transition-all duration-300 hover:scale-105 hover:shadow-[0_0_20px_rgba(255,255,255,0.3)]"
            >
                Say Hello
            </a>

            <div class="mt-20 flex justify-center gap-8">
                <a
                    href="#"
                    class="rounded-full bg-white/5 p-3 transition-all hover:bg-white/10 hover:text-indigo-400"
                >
                    <Github class="h-6 w-6" />
                </a>
                <a
                    href="#"
                    class="rounded-full bg-white/5 p-3 transition-all hover:bg-white/10 hover:text-blue-400"
                >
                    <Linkedin class="h-6 w-6" />
                </a>
                <a
                    href="#"
                    class="rounded-full bg-white/5 p-3 transition-all hover:bg-white/10 hover:text-sky-400"
                >
                    <Twitter class="h-6 w-6" />
                </a>
            </div>

            <div class="mt-12 text-sm text-zinc-600">
                &copy; {{ new Date().getFullYear() }} DaraEmmanuel Code. All
                rights reserved.
            </div>
        </div>
    </footer>
</template>
