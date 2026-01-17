<script setup lang="ts">
import type { Skill } from '@/types/portfolio';
import * as icons from 'lucide-vue-next';
import { computed, type Component } from 'vue';

const props = defineProps<{
    skills?: Skill[];
}>();

const defaultSkills = [
    {
        id: 0,
        name: 'Frontend',
        icon: 'Layout',
        items: ['Vue 3', 'React', 'Tailwind', 'TypeScript'],
    },
    {
        id: 0,
        name: 'Backend',
        icon: 'Database',
        items: ['Laravel', 'Node.js', 'PostgreSQL', 'MySQL', 'Redis'],
    },
    {
        id: 0,
        name: 'Architecture',
        icon: 'Cpu',
        items: ['MVC', 'Monolithic', 'System Design', 'API Design'],
    },
    {
        id: 0,
        name: 'UI/UX Design',
        icon: 'Monitor',
        items: ['Figma', 'Adobe XD', 'Zeplin', 'Photoshop'],
    },
];

const computedSkills = computed(() => {
    return props.skills && props.skills.length > 0
        ? props.skills
        : (defaultSkills as any[]);
});

const getIcon = (name: string | null | undefined): Component => {
    const iconName = name || 'HelpCircle';
    const icon = icons[iconName as keyof typeof icons];
    return (icon || icons.HelpCircle) as Component;
};
</script>

<template>
    <section id="skills" class="bg-muted/30 py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="mb-12 sm:mb-16">
                <h2 class="mb-2 text-2xl font-bold sm:mb-4 sm:text-3xl">Technical Arsenal</h2>
                <p class="max-w-xl text-sm text-muted-foreground sm:text-base">
                    The tools and technologies I use to bring ideas to life.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                <div
                    v-for="(skill, index) in computedSkills"
                    :key="skill.id || skill.name"
                    v-intersect.once
                    class="reveal rounded-2xl border border-border bg-card p-6 transition-all hover:-translate-y-2 hover:border-indigo-500/30 hover:shadow-lg hover:shadow-indigo-500/10"
                    :style="{ transitionDelay: `${index * 150}ms` }"
                >
                    <div
                        class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-500/10 text-indigo-500 transition-transform group-hover:scale-110"
                    >
                        <component :is="getIcon(skill.icon)" class="h-6 w-6" />
                    </div>
                    <h3 class="mb-4 text-xl font-semibold">
                        {{ skill.name }}
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        <span
                            v-for="(item, i) in skill.items"
                            :key="`${skill.id || skill.name}-${i}-${item}`"
                            class="rounded-md border border-border bg-muted/50 px-2.5 py-1 text-xs font-medium text-muted-foreground transition-all hover:border-indigo-500/30 hover:bg-indigo-500/10 hover:text-indigo-500"
                        >
                            {{ item }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
