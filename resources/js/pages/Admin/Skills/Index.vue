<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import * as icons from 'lucide-vue-next';

defineProps<{
    skills: Array<{
        id: number;
        name: string;
        icon: string;
        items: string[];
    }>;
}>();

const getIcon = (name: string): any => {
    return icons[name as keyof typeof icons] || icons.HelpCircle;
};
</script>

<template>
    <AppLayout
        :breadcrumbs="[{ title: 'Skills', href: route('admin.skills.index') }]"
    >
        <Head title="Skills" />

        <div class="min-h-[calc(100vh-64px)] bg-zinc-950 p-6 lg:p-10">
            <div class="mx-auto max-w-6xl">
                <!-- Header Section -->
                <div
                    class="mb-12 flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
                >
                    <div>
                        <h2
                            class="bg-gradient-to-r from-white to-zinc-500 bg-clip-text text-3xl font-bold tracking-tight text-transparent sm:text-4xl"
                        >
                            Skill Inventory
                        </h2>
                        <p class="mt-2 text-zinc-400">
                            Organize and display your technical expertise
                            categories.
                        </p>
                    </div>
                    <Link :href="route('admin.skills.create')">
                        <Button
                            class="rounded-full bg-indigo-600 px-6 py-6 font-semibold text-white transition-all hover:bg-indigo-700 hover:shadow-[0_0_20px_rgba(79,70,229,0.4)]"
                        >
                            Add Skill Category
                        </Button>
                    </Link>
                </div>

                <!-- Skills Table/List -->
                <div
                    class="relative overflow-hidden rounded-2xl border border-white/10 bg-zinc-900/50 backdrop-blur-sm"
                >
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse text-left">
                            <thead>
                                <tr class="border-b border-white/5 bg-white/5">
                                    <th
                                        class="w-16 p-5 text-center text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                    >
                                        Icon
                                    </th>
                                    <th
                                        class="p-5 text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                    >
                                        Category
                                    </th>
                                    <th
                                        class="p-5 text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                    >
                                        Skill Items
                                    </th>
                                    <th
                                        class="p-5 text-right text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <tr
                                    v-for="skill in skills"
                                    :key="skill.id"
                                    class="group transition-colors hover:bg-white/[0.02]"
                                >
                                    <td class="p-5 text-center">
                                        <div
                                            class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-500/10 text-indigo-400 transition-transform group-hover:scale-110"
                                        >
                                            <component
                                                :is="getIcon(skill.icon)"
                                                class="h-6 w-6"
                                            />
                                        </div>
                                    </td>
                                    <td class="p-5">
                                        <span
                                            class="text-lg font-bold text-white transition-colors group-hover:text-indigo-400"
                                        >
                                            {{ skill.name }}
                                        </span>
                                    </td>
                                    <td class="p-5">
                                        <div class="flex flex-wrap gap-1.5">
                                            <span
                                                v-for="(item, index) in skill.items"
                                                :key="`${skill.id}-item-${index}-${item}`"
                                                class="rounded-full border border-white/10 bg-white/5 px-2.5 py-0.5 text-xs font-medium text-zinc-400"
                                            >
                                                {{ item }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="p-5 text-right">
                                        <div
                                            class="flex items-center justify-end gap-3"
                                        >
                                            <Link
                                                :href="
                                                    route(
                                                        'admin.skills.edit',
                                                        skill.id,
                                                    )
                                                "
                                                class="text-sm font-medium text-indigo-400 transition-colors hover:text-indigo-300"
                                            >
                                                Edit
                                            </Link>
                                            <div
                                                class="h-1 w-1 rounded-full bg-zinc-700"
                                            ></div>
                                            <Link
                                                :href="
                                                    route(
                                                        'admin.skills.destroy',
                                                        skill.id,
                                                    )
                                                "
                                                method="delete"
                                                as="button"
                                                class="text-sm font-medium text-zinc-500 transition-colors hover:text-red-400"
                                            >
                                                Delete
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="skills.length === 0">
                                    <td colspan="4" class="p-12 text-center">
                                        <div
                                            class="flex flex-col items-center justify-center gap-3 text-zinc-600"
                                        >
                                            <component
                                                :is="icons.Zap"
                                                class="h-10 w-10 animate-pulse text-zinc-800"
                                            />
                                            <p
                                                class="font-medium tracking-wide text-zinc-500"
                                            >
                                                No skill categories defined yet.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
