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

        <div class="min-h-[calc(100vh-64px)] bg-background p-6 lg:p-10">
            <div class="mx-auto max-w-6xl">
                <!-- Header Section -->
                <div
                    class="mb-12 flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
                >
                    <div>
                        <h2
                            class="bg-gradient-to-r from-foreground to-foreground/50 bg-clip-text text-3xl font-bold tracking-tight text-transparent sm:text-4xl"
                        >
                            Skill Inventory
                        </h2>
                        <p class="mt-2 text-muted-foreground">
                            Organize and display your technical expertise
                            categories.
                        </p>
                    </div>
                    <Link :href="route('admin.skills.create')">
                        <Button
                            class="rounded-full bg-primary px-8 py-6 font-bold text-primary-foreground transition-all hover:shadow-xl hover:shadow-primary/20 active:scale-95"
                        >
                            Add Skill Category
                        </Button>
                    </Link>
                </div>

                <!-- Skills Table/List -->
                <div
                    class="relative overflow-hidden rounded-2xl border border-border bg-card shadow-sm"
                >
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse text-left">
                            <thead>
                                <tr class="border-b border-border bg-muted/50">
                                    <th
                                        class="w-16 p-5 text-center text-xs font-bold tracking-wider text-muted-foreground uppercase"
                                    >
                                        Icon
                                    </th>
                                    <th
                                        class="p-5 text-xs font-bold tracking-wider text-muted-foreground uppercase"
                                    >
                                        Category
                                    </th>
                                    <th
                                        class="p-5 text-xs font-bold tracking-wider text-muted-foreground uppercase"
                                    >
                                        Skill Items
                                    </th>
                                    <th
                                        class="p-5 text-right text-xs font-bold tracking-wider text-muted-foreground uppercase"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border" v-intersect>
                                <tr
                                    v-for="(skill, index) in skills"
                                    :key="skill.id"
                                    :class="[
                                        'group reveal transition-colors hover:bg-muted/30',
                                        'delay-' + index * 100,
                                    ]"
                                >
                                    <td class="p-5 text-center">
                                        <div
                                            class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl bg-primary/10 text-primary transition-transform group-hover:scale-110"
                                        >
                                            <component
                                                :is="getIcon(skill.icon)"
                                                class="h-6 w-6"
                                            />
                                        </div>
                                    </td>
                                    <td class="p-5">
                                        <span
                                            class="text-base font-bold text-foreground transition-colors group-hover:text-primary"
                                        >
                                            {{ skill.name }}
                                        </span>
                                    </td>
                                    <td class="p-5">
                                        <div class="flex flex-wrap gap-1.5">
                                            <span
                                                v-for="(
                                                    item, index
                                                ) in skill.items"
                                                :key="`${skill.id}-item-${index}-${item}`"
                                                class="rounded-full border border-border bg-muted px-2.5 py-0.5 text-[10px] font-bold tracking-tight text-muted-foreground uppercase"
                                            >
                                                {{ item }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="p-5 text-right">
                                        <div
                                            class="flex items-center justify-end gap-5"
                                        >
                                            <Link
                                                :href="
                                                    route(
                                                        'admin.skills.edit',
                                                        skill.id,
                                                    )
                                                "
                                                class="text-xs font-bold text-primary transition-colors hover:underline"
                                            >
                                                Edit
                                            </Link>
                                            <Link
                                                :href="
                                                    route(
                                                        'admin.skills.destroy',
                                                        skill.id,
                                                    )
                                                "
                                                method="delete"
                                                as="button"
                                                class="text-xs font-bold text-muted-foreground transition-colors hover:text-destructive hover:underline"
                                            >
                                                Delete
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="skills.length === 0">
                                    <td colspan="4" class="p-16 text-center">
                                        <div
                                            class="flex flex-col items-center justify-center gap-4 text-muted-foreground"
                                        >
                                            <component
                                                :is="icons.Zap"
                                                class="h-12 w-12 animate-pulse text-muted-foreground/20"
                                            />
                                            <p class="text-lg font-bold">
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
