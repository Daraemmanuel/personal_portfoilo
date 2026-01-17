<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Testimonial } from '@/types/portfolio';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    testimonial: Testimonial;
}>();

const imageInput = ref<HTMLInputElement | null>(null);
const imagePreview = ref<string | null>(props.testimonial.avatar_url || null);

const form = useForm({
    name: props.testimonial.name,
    role: props.testimonial.role,
    company: props.testimonial.company,
    content: props.testimonial.content,
    avatar: null as File | null,
    rating: props.testimonial.rating || 5,
    sort_order: props.testimonial.sort_order || 0,
    _method: 'PUT',
});

const submit = () => {
    form.post(route('admin.testimonials.update', props.testimonial.id));
};

const handleImageChange = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) {
        form.avatar = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const triggerFileInput = () => {
    imageInput.value?.click();
};

const clearImage = () => {
    form.avatar = null;
    imagePreview.value = props.testimonial.avatar_url || null;
};
</script>

<template>
    <AppLayout
        :breadcrumbs="[
            { title: 'Testimonials', href: route('admin.testimonials.index') },
            { title: 'Edit', href: '#' },
        ]"
    >
        <Head title="Edit Testimonial" />

        <div class="min-h-[calc(100vh-64px)] bg-background p-6 lg:p-10">
            <div class="mx-auto max-w-5xl">
                <!-- Header -->
                <div class="mb-10">
                    <h2
                        class="bg-gradient-to-r from-foreground to-foreground/50 bg-clip-text text-3xl font-bold tracking-tight text-transparent"
                    >
                        Edit Testimonial
                    </h2>
                    <p class="mt-2 text-muted-foreground">
                        Update testimonial information.
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
                                    >Name</label
                                >
                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="w-full rounded-xl border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 shadow-sm transition-all focus:border-primary focus:bg-background focus:ring-2 focus:ring-primary/20 sm:text-sm"
                                />
                                <InputError :message="form.errors.name" />
                            </div>

                            <div class="space-y-2">
                                <label
                                    class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                    >Role</label
                                >
                                <input
                                    v-model="form.role"
                                    type="text"
                                    class="w-full rounded-xl border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 shadow-sm transition-all focus:border-primary focus:bg-background focus:ring-2 focus:ring-primary/20 sm:text-sm"
                                />
                                <InputError :message="form.errors.role" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                >Company</label
                            >
                            <input
                                v-model="form.company"
                                type="text"
                                class="w-full rounded-xl border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 shadow-sm transition-all focus:border-primary focus:bg-background focus:ring-2 focus:ring-primary/20 sm:text-sm"
                            />
                            <InputError :message="form.errors.company" />
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                >Testimonial Content</label
                            >
                            <textarea
                                v-model="form.content"
                                rows="6"
                                class="w-full rounded-xl border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 shadow-sm transition-all focus:border-primary focus:bg-background focus:ring-2 focus:ring-primary/20 sm:text-sm"
                            ></textarea>
                            <InputError :message="form.errors.content" />
                        </div>

                        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                            <div class="space-y-2">
                                <label
                                    class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                    >Rating (1-5)</label
                                >
                                <input
                                    v-model.number="form.rating"
                                    type="number"
                                    min="1"
                                    max="5"
                                    class="w-full rounded-xl border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 shadow-sm transition-all focus:border-primary focus:bg-background focus:ring-2 focus:ring-primary/20 sm:text-sm"
                                />
                                <InputError :message="form.errors.rating" />
                            </div>

                            <div class="space-y-2">
                                <label
                                    class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                    >Display Order</label
                                >
                                <input
                                    v-model.number="form.sort_order"
                                    type="number"
                                    class="w-full rounded-xl border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 shadow-sm transition-all focus:border-primary focus:bg-background focus:ring-2 focus:ring-primary/20 sm:text-sm"
                                />
                                <InputError :message="form.errors.sort_order" />
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                >Avatar (Optional)</label
                            >
                            <div
                                class="relative flex min-h-[150px] cursor-pointer items-center justify-center rounded-2xl border-2 border-dashed border-border bg-muted/30 transition-all hover:border-primary/50"
                                @click="triggerFileInput"
                            >
                                <input
                                    ref="imageInput"
                                    type="file"
                                    class="hidden"
                                    accept="image/*"
                                    @change="handleImageChange"
                                />
                                <div v-if="!imagePreview" class="text-center">
                                    <div
                                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary"
                                    >
                                        <svg
                                            class="h-6 w-6"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 4v16m8-8H4"
                                            />
                                        </svg>
                                    </div>
                                    <p
                                        class="mt-3 text-sm font-medium text-foreground"
                                    >
                                        Click to upload avatar
                                    </p>
                                </div>
                                <div v-else class="relative w-full p-4">
                                    <img
                                        :src="imagePreview"
                                        class="mx-auto h-32 w-32 rounded-full border-2 border-primary/20 object-cover shadow-lg"
                                    />
                                    <button
                                        type="button"
                                        @click.stop="clearImage"
                                        class="absolute top-6 right-6 flex h-10 w-10 items-center justify-center rounded-full bg-destructive text-destructive-foreground shadow-lg transition-transform hover:scale-110"
                                    >
                                        <span class="text-xl">&times;</span>
                                    </button>
                                </div>
                            </div>
                            <InputError :message="form.errors.avatar" />
                        </div>

                        <div
                            class="flex items-center justify-end gap-6 border-t border-border pt-8"
                        >
                            <Link :href="route('admin.testimonials.index')">
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
                                Update Testimonial
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
