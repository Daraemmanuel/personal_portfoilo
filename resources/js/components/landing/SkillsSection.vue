<script setup lang="ts">
import * as icons from 'lucide-vue-next';
import { computed, type Component } from 'vue';
import type { Skill } from '@/types/portfolio';

const props = defineProps<{
    skills?: Skill[];
}>();

const defaultSkills = [
    {
        name: 'Frontend',
        icon: 'Layout',
        items: ['Vue 3', 'React', 'Tailwind', 'TypeScript'],
    },
    {
        name: 'Backend',
        icon: 'Database',
        items: ['Laravel', 'Node.js', 'PostgreSQL', 'MySQL', 'Redis'],
    },
    {
        name: 'Architecture',
        icon: 'Cpu',
        items: ['MVC', 'Monolithic', 'System Design', 'API Design'],
    },
    {
        name: 'UI/UX Design',
        icon: 'Monitor',
        items: ['Figma', 'Adobe XD', 'Zeplin', 'Photoshop'],
    },
    // {
    //     name: 'Version Control',
    //     icon: 'GitBranch',
    //     items: ['Git', 'GitHub', 'GitLab'],
    // },
];

const computedSkills = computed(() => {
    return props.skills && props.skills.length > 0
        ? props.skills
        : defaultSkills;
});

const getIcon = (name: string): Component => {
    const icon = icons[name as keyof typeof icons];
    return (icon || icons.HelpCircle) as Component;
};
</script>

<template>
    <section id="skills" class="bg-zinc-950/50 py-24">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-16">
                <h2 class="mb-4 text-3xl font-bold">Technical Arsenal</h2>
                <p class="max-w-xl text-zinc-400">
                    The tools and technologies I use to bring ideas to life.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                <div
                    v-for="skill in computedSkills"
                    :key="skill.id || skill.name"
                    class="rounded-2xl border border-white/5 bg-white/5 p-6 transition-colors hover:border-indigo-500/30"
                >
                    <component
                        :is="getIcon(skill.icon)"
                        class="mb-4 h-8 w-8 text-indigo-400"
                    />
                    <h3 class="mb-4 text-xl font-semibold">
                        {{ skill.name }}
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        <span
                            v-for="(item, index) in skill.items"
                            :key="`${skill.id || skill.name}-${index}-${item}`"
                            class="rounded-md border border-white/5 bg-white/5 px-2 py-1 text-xs text-zinc-400"
                        >
                            {{ item }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
