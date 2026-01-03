<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    icon: '',
    items: '',
    sort_order: 0,
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        items: data.items
            .split(',')
            .map((item) => item.trim())
            .filter(Boolean),
    })).post(route('admin.skills.store'));
};
</script>

<template>
    <AppLayout
        :breadcrumbs="[
            { title: 'Skills', href: route('admin.skills.index') },
            { title: 'Create', href: '#' },
        ]"
    >
        <Head title="Create Skill" />

        <div class="min-h-[calc(100vh-64px)] bg-zinc-950 p-6 lg:p-10">
            <div class="mx-auto">
                <!-- Header -->
                <div class="mb-10">
                    <h2
                        class="bg-gradient-to-r from-white to-zinc-500 bg-clip-text text-3xl font-bold tracking-tight text-transparent"
                    >
                        Define Expertise
                    </h2>
                    <p class="mt-2 text-zinc-400">
                        Create a new category to showcase your technical stack.
                    </p>
                </div>

                <!-- Form Card -->
                <div
                    class="rounded-2xl border border-white/10 bg-zinc-900/50 p-8 shadow-xl backdrop-blur-sm"
                >
                    <form @submit.prevent="submit" class="space-y-8">
                        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                            <div class="space-y-2">
                                <label
                                    class="text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                    >Category Name</label
                                >
                                <input
                                    v-model="form.name"
                                    type="text"
                                    placeholder="e.g. Frontend Development"
                                    class="w-full rounded-xl border-white/10 bg-white/5 px-4 py-3 text-white placeholder-zinc-600 shadow-sm transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 sm:text-sm"
                                />
                                <InputError :message="form.errors.name" />
                            </div>

                            <div class="space-y-2">
                                <label
                                    class="text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                    >Icon (Lucide)</label
                                >
                                <input
                                    v-model="form.icon"
                                    type="text"
                                    placeholder="e.g. Layout, Database"
                                    class="w-full rounded-xl border-white/10 bg-white/5 px-4 py-3 text-white placeholder-zinc-600 shadow-sm transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 sm:text-sm"
                                />
                                <InputError :message="form.errors.icon" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                >Skill Items</label
                            >
                            <input
                                v-model="form.items"
                                type="text"
                                placeholder="Vue, React, TypeScript"
                                class="w-full rounded-xl border-white/10 bg-white/5 px-4 py-3 text-white placeholder-zinc-600 shadow-sm transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 sm:text-sm"
                            />
                            <span
                                class="text-[10px] font-bold tracking-widest text-zinc-600 uppercase"
                                >Separate with commas</span
                            >
                            <InputError :message="form.errors.items" />
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                >Display Order</label
                            >
                            <input
                                v-model="form.sort_order"
                                type="number"
                                placeholder="0"
                                class="w-full max-w-[200px] rounded-xl border-white/10 bg-white/5 px-4 py-3 text-white placeholder-zinc-600 shadow-sm transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 sm:text-sm"
                            />
                            <p
                                class="text-[10px] font-bold tracking-widest text-zinc-600 uppercase"
                            >
                                Lower numbers appear first
                            </p>
                            <InputError :message="form.errors.sort_order" />
                        </div>

                        <div
                            class="flex items-center justify-end gap-4 border-t border-white/5 pt-4"
                        >
                            <Link :href="route('admin.skills.index')">
                                <Button
                                    variant="ghost"
                                    type="button"
                                    class="text-zinc-400 hover:bg-white/5 hover:text-white"
                                >
                                    Cancel
                                </Button>
                            </Link>
                            <Button
                                type="submit"
                                :disabled="form.processing"
                                class="rounded-full bg-indigo-600 px-8 py-2 font-bold text-white transition-all hover:bg-indigo-700 disabled:opacity-50"
                            >
                                Publish Category
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
