<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    experience: {
        id: number;
        role: string;
        company: string;
        period: string;
        description: string;
        sort_order: number;
    };
}>();

const form = useForm({
    role: props.experience.role,
    company: props.experience.company,
    period: props.experience.period,
    description: props.experience.description,
    sort_order: props.experience.sort_order || 0,
});

const submit = () => {
    form.put(route('admin.experiences.update', props.experience.id));
};
</script>

<template>
    <AppLayout
        :breadcrumbs="[
            { title: 'Experiences', href: route('admin.experiences.index') },
            { title: 'Edit', href: '#' },
        ]"
    >
        <Head title="Edit Experience" />

        <div class="min-h-[calc(100vh-64px)] bg-zinc-950 p-6 lg:p-10">
            <div class="mx-auto">
                <!-- Header -->
                <div class="mb-10">
                    <h2
                        class="bg-gradient-to-r from-white to-zinc-500 bg-clip-text text-3xl font-bold tracking-tight text-transparent"
                    >
                        Refine Journey
                    </h2>
                    <p class="mt-2 text-zinc-400">
                        Update the details for this milestone in your
                        professional career timeline.
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
                                    >Role / Title</label
                                >
                                <input
                                    v-model="form.role"
                                    type="text"
                                    placeholder="e.g. Lead Engineer"
                                    class="w-full rounded-xl border-white/10 bg-white/5 px-4 py-3 text-white placeholder-zinc-600 shadow-sm transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 sm:text-sm"
                                />
                                <InputError :message="form.errors.role" />
                            </div>

                            <div class="space-y-2">
                                <label
                                    class="text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                    >Company / Org</label
                                >
                                <input
                                    v-model="form.company"
                                    type="text"
                                    placeholder="e.g. Kodemmy Tech"
                                    class="w-full rounded-xl border-white/10 bg-white/5 px-4 py-3 text-white placeholder-zinc-600 shadow-sm transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 sm:text-sm"
                                />
                                <InputError :message="form.errors.company" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                >Period</label
                            >
                            <input
                                v-model="form.period"
                                type="text"
                                placeholder="e.g. 2023 — Present"
                                class="w-full rounded-xl border-white/10 bg-white/5 px-4 py-3 text-white placeholder-zinc-600 shadow-sm transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 sm:text-sm"
                            />
                            <InputError :message="form.errors.period" />
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                >Description</label
                            >
                            <textarea
                                v-model="form.description"
                                rows="4"
                                placeholder="Describe your impact and key achievements..."
                                class="w-full rounded-xl border-white/10 bg-white/5 px-4 py-3 text-white placeholder-zinc-600 shadow-sm transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 sm:text-sm"
                            ></textarea>
                            <InputError :message="form.errors.description" />
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
                            <Link :href="route('admin.experiences.index')">
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
                                Update Experience
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
