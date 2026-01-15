<script setup lang="ts">
import ContactFooter from '@/components/landing/ContactFooter.vue';
import ExperienceSection from '@/components/landing/ExperienceSection.vue';
import HeroSection from '@/components/landing/HeroSection.vue';
import LandingNav from '@/components/landing/LandingNav.vue';
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
import { onMounted, onUnmounted } from 'vue';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    projects?: Project[];
    skills?: Skill[];
    experiences?: Experience[];
    testimonials?: Testimonial[];
}>();

// Inject structured data script
let structuredDataScript: HTMLScriptElement | null = null;

onMounted(() => {
    const structuredData = {
        '@context': 'https://schema.org',
        '@type': 'Person',
        name: 'DaraEmmanuel Akinyode',
        jobTitle: 'Software Engineer',
        url: 'https://yourdomain.com',
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
            content="Portfolio of DaraEmmanuel Akinyode, a Software Engineer specializing in building exceptional digital experiences with modern technologies like Laravel, Node.js, Vue.js, React, Blade, Bootstrap, Tailwind CSS, and TypeScript."
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
            content="Portfolio showcasing projects, skills, and experience in modern web development. Specializing in Laravel, Vue.js, and TypeScript."
        />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://yourdomain.com" />
        <meta
            property="og:image"
            content="https://yourdomain.com/og-image.jpg"
        />
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
        <meta
            name="twitter:image"
            content="https://yourdomain.com/og-image.jpg"
        />

        <!-- Canonical URL -->
        <link rel="canonical" href="https://yourdomain.com" />
    </Head>

    <div
        class="min-h-screen bg-black font-sans text-white antialiased selection:bg-indigo-500 selection:text-white"
    >
        <LandingNav :can-login="canLogin" />
        <HeroSection />
        <SkillsSection :skills="skills" />
        <WorkSection :projects="projects" />
        <ExperienceSection :experiences="experiences" />
        <TestimonialsSection :testimonials="testimonials" />
        <ContactFooter />
    </div>
</template>

<style scoped>
/* Smooth scrolling for anchor links */
html {
    scroll-behavior: smooth;
}
</style>
