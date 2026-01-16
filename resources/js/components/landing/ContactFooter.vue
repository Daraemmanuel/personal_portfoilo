<script setup lang="ts">
import {
    GithubIcon,
    Linkedin,
    MessageCircleIcon,
    TwitterIcon,
} from 'lucide-vue-next';
import ContactForm from './ContactForm.vue';
import NewsletterForm from './NewsletterForm.vue';
import GitHubContributions from './GitHubContributions.vue';

const socialLinks = [
    {
        name: 'GitHub',
        href: 'https://github.com/Daraemmanuel',
        icon: GithubIcon,
        color: 'hover:text-indigo-400',
        target: '_blank',
    },
    {
        name: 'LinkedIn',
        href: 'https://www.linkedin.com/in/daraemmanuel/',
        icon: Linkedin,
        color: 'hover:text-blue-400',
        target: '_blank',
    },
    {
        name: 'X (Twitter)',
        href: 'https://x.com/_DaraEmmanuel',
        icon: TwitterIcon,
        color: 'hover:text-sky-400',
        target: '_blank',
    },
    {
        name: 'WhatsApp',
        href: 'https://wa.me/2348137627905',
        icon: MessageCircleIcon,
        color: 'hover:text-green-400',
        target: '_blank',
    },
];
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
            <ContactForm />

            <div class="mx-auto mt-20 max-w-2xl">
                <NewsletterForm />
            </div>

            <div class="mx-auto mt-20 max-w-2xl">
                <GitHubContributions username="Daraemmanuel" />
            </div>

            <div class="mt-20 flex justify-center gap-8">
                <div
                    v-for="social in socialLinks"
                    :key="social.name"
                    class="group relative"
                >
                    <!-- Tooltip -->
                    <div
                        class="invisible absolute -top-12 left-1/2 -translate-x-1/2 rounded-lg bg-white px-3 py-1.5 text-xs font-bold whitespace-nowrap text-black opacity-0 transition-all duration-300 group-hover:visible group-hover:-top-14 group-hover:opacity-100"
                    >
                        {{ social.name }}
                        <!-- Tooltip Arrow -->
                        <div
                            class="absolute -bottom-1 left-1/2 h-2 w-2 -translate-x-1/2 rotate-45 bg-white"
                        ></div>
                    </div>

                    <a
                        :href="social.href"
                        :target="social.target || '_self'"
                        class="block rounded-full bg-white/5 p-3 transition-all hover:bg-white/10"
                        :class="social.color"
                    >
                        <component :is="social.icon" class="h-6 w-6" />
                    </a>
                </div>
            </div>

            <div class="mt-12 text-sm text-zinc-600">
                &copy; {{ new Date().getFullYear() }} DaraEmmanuel Code. All
                rights reserved.
            </div>
        </div>
    </footer>
</template>
