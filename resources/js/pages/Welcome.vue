<script setup lang="ts">
import ContactFooter from '@/components/landing/ContactFooter.vue';
import ExperienceSection from '@/components/landing/ExperienceSection.vue';
import HeroSection from '@/components/landing/HeroSection.vue';
import LandingNav from '@/components/landing/LandingNav.vue';
import SkillsSection from '@/components/landing/SkillsSection.vue';
import WorkSection from '@/components/landing/WorkSection.vue';
import { Head } from '@inertiajs/vue3';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    projects?: any[];
    skills?: any[];
    experiences?: any[];
}>();
</script>

<template>
    <Head title="DaraEmmanuel Akinyode | Software Engineer">
        <meta
            name="description"
            content="Portfolio of DaraEmmanuel Akinyode, a Software Engineer."
        />
    </Head>

    <div
        class="min-h-screen bg-black font-sans text-white antialiased selection:bg-indigo-500 selection:text-white"
    >
        <LandingNav :can-login="canLogin" />
        <HeroSection />
        <SkillsSection :skills="skills" />
        <WorkSection :projects="projects" />
        <ExperienceSection :experiences="experiences" />
        <ContactFooter />
    </div>
</template>

<style scoped>
/* Smooth scrolling for anchor links */
html {
    scroll-behavior: smooth;
}
</style>
