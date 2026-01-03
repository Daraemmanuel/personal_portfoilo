<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    description: '',
    link: '',
    tags: '',
    sort_order: 0,
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        tags: data.tags
            .split(',')
            .map((tag) => tag.trim())
            .filter(Boolean),
    })).post(route('admin.projects.store'));
};
</script>

<template>
    <AppLayout
        :breadcrumbs="[
            { title: 'Projects', href: route('admin.projects.index') },
            { title: 'Create', href: '#' },
        ]"
    >
        <Head title="Create Project" />

        <div class="min-h-[calc(100vh-64px)] bg-zinc-950 p-6 lg:p-10">
            <div class="mx-auto">
                <!-- Header -->
                <div class="mb-10">
                    <h2
                        class="bg-gradient-to-r from-white to-zinc-500 bg-clip-text text-3xl font-bold tracking-tight text-transparent"
                    >
                        New Masterpiece
                    </h2>
                    <p class="mt-2 text-zinc-400">
                        Specify the details for your new portfolio project.
                    </p>
                </div>

                <!-- Form Card -->
                <div
                    class="rounded-2xl border border-white/10 bg-zinc-900/50 p-8 shadow-xl backdrop-blur-sm"
                >
                    <form @submit.prevent="submit" class="space-y-8">
                        <div class="space-y-2">
                            <label
                                class="text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                >Project Title</label
                            >
                            <input
                                v-model="form.title"
                                type="text"
                                placeholder="e.g. AI-Powered Portfolio"
                                class="w-full rounded-xl border-white/10 bg-white/5 px-4 py-3 text-white placeholder-zinc-600 shadow-sm transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 sm:text-sm"
                            />
                            <InputError :message="form.errors.title" />
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                >Project Description</label
                            >
                            <textarea
                                v-model="form.description"
                                rows="4"
                                placeholder="Describe the problem solved and the technology used..."
                                class="w-full rounded-xl border-white/10 bg-white/5 px-4 py-3 text-white placeholder-zinc-600 shadow-sm transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 sm:text-sm"
                            ></textarea>
                            <InputError :message="form.errors.description" />
                        </div>

                        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                            <div class="space-y-2">
                                <label
                                    class="text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                    >Live URL</label
                                >
                                <input
                                    v-model="form.link"
                                    type="url"
                                    placeholder="https://example.com"
                                    class="w-full rounded-xl border-white/10 bg-white/5 px-4 py-3 text-white placeholder-zinc-600 shadow-sm transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 sm:text-sm"
                                />
                                <InputError :message="form.errors.link" />
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
                                    class="w-full rounded-xl border-white/10 bg-white/5 px-4 py-3 text-white placeholder-zinc-600 shadow-sm transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 sm:text-sm"
                                />
                                <span
                                    class="text-[10px] font-bold tracking-widest text-zinc-600 uppercase"
                                    >Lower numbers appear first</span
                                >
                                <InputError :message="form.errors.sort_order" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                >Tags</label
                            >
                            <input
                                v-model="form.tags"
                                type="text"
                                placeholder="Vue, Tailwind, Laravel"
                                class="w-full rounded-xl border-white/10 bg-white/5 px-4 py-3 text-white placeholder-zinc-600 shadow-sm transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 sm:text-sm"
                            />
                            <span
                                class="text-[10px] font-bold tracking-widest text-zinc-600 uppercase"
                                >Separate with commas</span
                            >
                            <InputError :message="form.errors.tags" />
                        </div>

                        <div
                            class="flex items-center justify-end gap-4 border-t border-white/5 pt-4"
                        >
                            <Link :href="route('admin.projects.index')">
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
                                Publish Project
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
