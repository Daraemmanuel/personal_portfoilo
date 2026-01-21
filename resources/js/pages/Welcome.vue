<script setup lang="ts">
import ContactFooter from '@/components/landing/ContactFooter.vue';
import ExperienceSection from '@/components/landing/ExperienceSection.vue';
import HeroSection from '@/components/landing/HeroSection.vue';
import LandingNav from '@/components/landing/LandingNav.vue';
import ScrollToTop from '@/components/landing/ScrollToTop.vue';
import SkillsSection from '@/components/landing/SkillsSection.vue';
import TestimonialsSection from '@/components/landing/TestimonialsSection.vue';
import WorkSection from '@/components/landing/WorkSection.vue';
import type {
    Experience,
    Project,
    Skill,
    Testimonial,
} from '@/types/portfolio';
import { Head } from '@inertiajs/vue3';
import { computed, onMounted, onUnmounted } from 'vue';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    projects?: Project[];
    skills?: Skill[];
    experiences?: Experience[];
    testimonials?: Testimonial[];
}>();

// Dynamic URLs for SEO
const siteUrl = computed(() => {
    if (typeof window !== 'undefined') {
        return window.location.origin;
    }
    return '';
});

const ogImage = computed(() => `${siteUrl.value}/og-image.jpg`);

// Inject structured data script
let structuredDataScript: HTMLScriptElement | null = null;

onMounted(() => {
    const structuredData = {
        '@context': 'https://schema.org',
        '@type': 'Person',
        name: 'DaraEmmanuel Akinyode',
        jobTitle: 'Software Engineer',
        url: siteUrl.value,
        sameAs: [
            'https://github.com/Daraemmanuel',
            'https://www.linkedin.com/in/daraemmanuel/',
            'https://x.com/_DaraEmmanuel',
        ],
        email: 'immanuelatwork@gmail.com',
    };

    structuredDataScript = document.createElement('script');
    structuredDataScript.type = 'application/ld+json';
    structuredDataScript.textContent = JSON.stringify(structuredData);
    document.head.appendChild(structuredDataScript);
});

onUnmounted(() => {
    if (structuredDataScript && structuredDataScript.parentNode) {
        structuredDataScript.parentNode.removeChild(structuredDataScript);
    }
});
</script>

<template>
    <Head title="DaraEmmanuel Akinyode | Software Engineer">
        <meta
            name="description"
            content="Portfolio of DaraEmmanuel Akinyode, a Software Engineer specializing in building exceptional digital experiences with modern technologies like Laravel, Node.js, Vue.js, React, Blade, Bootstrap, Tailwind CSS, TypeScript, React-Native, and more."
        />
        <meta
            name="keywords"
            content="software engineer, web developer, Laravel, Vue.js, TypeScript, portfolio, full-stack developer, DaraEmmanuel Akinyode"
        />
        <meta name="author" content="DaraEmmanuel Akinyode" />
        <meta name="robots" content="index, follow" />

        <!-- Open Graph -->
        <meta
            property="og:title"
            content="DaraEmmanuel Akinyode | Software Engineer"
        />
        <meta
            property="og:description"
            content="Portfolio showcasing projects, skills, and experience in modern web development. Specializing in Laravel, Vue.js, TypeScript, React, React-Native, and more."
        />
        <meta property="og:type" content="website" />
        <meta property="og:url" :content="siteUrl" />
        <meta property="og:image" :content="ogImage" />
        <meta
            property="og:site_name"
            content="DaraEmmanuel Akinyode Portfolio"
        />

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta
            name="twitter:title"
            content="DaraEmmanuel Akinyode | Software Engineer"
        />
        <meta
            name="twitter:description"
            content="Portfolio showcasing projects, skills, and experience in modern web development."
        />
        <meta name="twitter:image" :content="ogImage" />

        <!-- Canonical URL -->
        <link rel="canonical" :href="siteUrl" />
    </Head>

    <div
        class="min-h-screen bg-background font-sans text-foreground antialiased selection:bg-indigo-500 selection:text-white"
    >
        <LandingNav :can-login="canLogin" />
        <main id="main-content" tabindex="-1" class="focus:outline-none">
            <div class="fade-in-up animate-in">
            <HeroSection />
        </div>
        <div class="fade-in-up animate-in delay-200">
            <SkillsSection :skills="skills" />
        </div>
        <div class="fade-in-up animate-in delay-300">
            <WorkSection :projects="projects" />
        </div>
        <div class="fade-in-up animate-in delay-400">
            <ExperienceSection :experiences="experiences" />
        </div>
        <div class="fade-in-up animate-in delay-500">
            <TestimonialsSection :testimonials="testimonials" />
        </div>
            <div class="fade-in-up animate-in delay-700">
                <ContactFooter />
            </div>
        </main>
        <ScrollToTop />
    </div>
</template>

<style scoped>
/* Smooth scrolling for anchor links */
html {
    scroll-behavior: smooth;
}
</style>
