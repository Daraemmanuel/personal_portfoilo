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

        <div class="min-h-[calc(100vh-64px)] bg-background p-6 lg:p-10">
            <div class="mx-auto max-w-5xl">
                <!-- Header -->
                <div class="mb-10">
                    <h2
                        class="bg-gradient-to-r from-foreground to-foreground/50 bg-clip-text text-3xl font-bold tracking-tight text-transparent"
                    >
                        Define Expertise
                    </h2>
                    <p class="mt-2 text-muted-foreground">
                        Create a new category to showcase your technical stack.
                    </p>
                </div>

                <!-- Form Card -->
                <div
                    class="rounded-2xl border border-border bg-card p-8 shadow-sm transition-colors"
                >
                    <form @submit.prevent="submit" class="space-y-8">
                        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                            <div class="space-y-2">
                                <label
                                    class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                    >Category Name</label
                                >
                                <input
                                    v-model="form.name"
                                    type="text"
                                    placeholder="e.g. Frontend Development"
                                    class="w-full rounded-xl border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 shadow-sm transition-all focus:border-primary focus:bg-background focus:ring-2 focus:ring-primary/20 sm:text-sm"
                                />
                                <InputError :message="form.errors.name" />
                            </div>

                            <div class="space-y-2">
                                <label
                                    class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                    >Icon (Lucide)</label
                                >
                                <input
                                    v-model="form.icon"
                                    type="text"
                                    placeholder="e.g. Layout, Database"
                                    class="w-full rounded-xl border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 shadow-sm transition-all focus:border-primary focus:bg-background focus:ring-2 focus:ring-primary/20 sm:text-sm"
                                />
                                <InputError :message="form.errors.icon" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                >Skill Items</label
                            >
                            <input
                                v-model="form.items"
                                type="text"
                                placeholder="Vue, React, TypeScript"
                                class="w-full rounded-xl border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 shadow-sm transition-all focus:border-primary focus:bg-background focus:ring-2 focus:ring-primary/20 sm:text-sm"
                            />
                            <span
                                class="text-[10px] font-bold tracking-widest text-muted-foreground/60 uppercase"
                                >Separate with commas</span
                            >
                            <InputError :message="form.errors.items" />
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                >Display Order</label
                            >
                            <input
                                v-model="form.sort_order"
                                type="number"
                                placeholder="0"
                                class="w-full max-w-[200px] rounded-xl border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 shadow-sm transition-all focus:border-primary focus:bg-background focus:ring-2 focus:ring-primary/20 sm:text-sm"
                            />
                            <p
                                class="text-[10px] font-bold tracking-widest text-muted-foreground/60 uppercase"
                            >
                                Lower numbers appear first
                            </p>
                            <InputError :message="form.errors.sort_order" />
                        </div>

                        <div
                            class="flex items-center justify-end gap-6 border-t border-border pt-8"
                        >
                            <Link :href="route('admin.skills.index')">
                                <Button
                                    variant="ghost"
                                    type="button"
                                    class="font-bold text-muted-foreground hover:text-foreground"
                                >
                                    Cancel
                                </Button>
                            </Link>
                            <Button
                                type="submit"
                                :disabled="form.processing"
                                class="rounded-full bg-primary px-10 py-6 font-bold text-primary-foreground shadow-lg shadow-primary/20 transition-all hover:scale-105 active:scale-95 disabled:opacity-50"
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
