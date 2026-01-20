<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import RichTextEditor from '@/components/admin/RichTextEditor.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    project: {
        id: number;
        title: string;
        description: string;
        image: string | null;
        image_url: string | null;
        link: string | null;
        tags: string[];
        sort_order: number;
        is_archived: boolean;
    };
}>();

const imageInput = ref<HTMLInputElement | null>(null);
const imagePreview = ref<string | null>(props.project.image_url);

const form = useForm({
    title: props.project.title,
    description: props.project.description,
    image: null as File | null,
    link: props.project.link || '',
    tags: props.project.tags ? props.project.tags.join(', ') : '',
    sort_order: props.project.sort_order || 0,
    is_archived: !!props.project.is_archived,
    _method: 'PUT',
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        tags: data.tags
            .split(',')
            .map((tag) => tag.trim())
            .filter(Boolean),
    })).post(route('admin.projects.update', props.project.id));
};

const handleImageChange = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) {
        form.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const triggerFileInput = () => {
    imageInput.value?.click();
};

const clearImage = () => {
    form.image = null;
    imagePreview.value = props.project.image_url;
};
</script>

<template>
    <AppLayout
        :breadcrumbs="[
            { title: 'Projects', href: route('admin.projects.index') },
            { title: 'Edit', href: '#' },
        ]"
    >
        <Head title="Edit Project" />

        <div class="min-h-[calc(100vh-64px)] bg-background p-6 lg:p-10">
            <div class="mx-auto max-w-5xl">
                <!-- Header -->
                <div class="mb-10">
                    <h2
                        class="bg-gradient-to-r from-foreground to-foreground/50 bg-clip-text text-3xl font-bold tracking-tight text-transparent"
                    >
                        Refine Masterpiece
                    </h2>
                    <p class="mt-2 text-muted-foreground">
                        Update the details for your portfolio project.
                    </p>
                </div>

                <!-- Form Card -->
                <div
                    class="rounded-2xl border border-border bg-card p-8 shadow-sm transition-colors"
                >
                    <form @submit.prevent="submit" class="space-y-8">
                        <div class="space-y-2">
                            <label
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                >Project Title</label
                            >
                            <input
                                v-model="form.title"
                                type="text"
                                placeholder="e.g. AI-Powered Portfolio"
                                class="w-full rounded-xl border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 shadow-sm transition-all focus:border-primary focus:bg-background focus:ring-2 focus:ring-primary/20 sm:text-sm"
                            />
                            <InputError :message="form.errors.title" />
                        </div>

                        <div class="space-y-4">
                            <label
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                >Project Hero Image (Optional)</label
                            >
                            <div
                                class="relative flex min-h-[200px] cursor-pointer items-center justify-center rounded-2xl border-2 border-dashed border-border bg-muted/30 transition-all hover:border-primary/50"
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
                                        Click to upload project hero shot
                                    </p>
                                    <p
                                        class="mt-1 text-xs text-muted-foreground"
                                    >
                                        PNG, JPG, WEBP (MAX. 2MB)
                                    </p>
                                </div>
                                <div v-else class="relative w-full p-4">
                                    <img
                                        :src="imagePreview"
                                        class="h-48 w-full rounded-xl object-cover shadow-lg"
                                    />
                                    <button
                                        type="button"
                                        @click.stop="clearImage"
                                        class="absolute top-6 right-6 flex h-10 w-10 items-center justify-center rounded-full bg-destructive text-destructive-foreground shadow-lg transition-transform hover:scale-110"
                                    >
                                        <span v-if="form.image" class="text-xl"
                                            >&times;</span
                                        >
                                        <span
                                            v-else
                                            class="text-[10px] font-bold tracking-tight uppercase"
                                            >Change</span
                                        >
                                    </button>
                                </div>
                            </div>
                            <InputError :message="form.errors.image" />
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                >Project Description</label
                            >
                            <RichTextEditor
                                v-model="form.description"
                                placeholder="Describe the problem solved and the technology used..."
                                :rows="8"
                            />
                            <InputError :message="form.errors.description" />
                        </div>

                        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                            <div class="space-y-2">
                                <label
                                    class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                    >Live URL</label
                                >
                                <input
                                    v-model="form.link"
                                    type="url"
                                    placeholder="https://example.com"
                                    class="w-full rounded-xl border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 shadow-sm transition-all focus:border-primary focus:bg-background focus:ring-2 focus:ring-primary/20 sm:text-sm"
                                />
                                <InputError :message="form.errors.link" />
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
                                    class="w-full rounded-xl border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 shadow-sm transition-all focus:border-primary focus:bg-background focus:ring-2 focus:ring-primary/20 sm:text-sm"
                                />
                                <span
                                    class="text-[10px] font-bold tracking-widest text-muted-foreground/60 uppercase"
                                    >Lower numbers appear first</span
                                >
                                <InputError :message="form.errors.sort_order" />
                            </div>
                        </div>

                        <div class="flex items-center gap-4 py-4">
                            <label
                                class="relative inline-flex cursor-pointer items-center"
                            >
                                <input
                                    v-model="form.is_archived"
                                    type="checkbox"
                                    class="peer sr-only"
                                />
                                <div
                                    class="peer h-6 w-11 rounded-full bg-input peer-checked:bg-primary peer-focus:ring-2 peer-focus:ring-primary/20 peer-focus:ring-offset-2 peer-focus:outline-none after:absolute after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:rounded-full after:bg-blue-500 after:transition-all after:content-[''] peer-checked:after:translate-x-full"
                                ></div>
                                <span
                                    class="ml-3 text-sm font-medium text-foreground"
                                    >Archive Project</span
                                >
                            </label>
                            <p class="text-xs text-muted-foreground">
                                Archived projects are hidden from the main
                                portfolio but visible in the archives.
                            </p>
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                >Tags</label
                            >
                            <input
                                v-model="form.tags"
                                type="text"
                                placeholder="Vue, Tailwind, Laravel"
                                class="w-full rounded-xl border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 shadow-sm transition-all focus:border-primary focus:bg-background focus:ring-2 focus:ring-primary/20 sm:text-sm"
                            />
                            <span
                                class="text-[10px] font-bold tracking-widest text-muted-foreground/60 uppercase"
                                >Separate with commas</span
                            >
                            <InputError :message="form.errors.tags" />
                        </div>

                        <div
                            class="flex items-center justify-end gap-6 border-t border-border pt-8"
                        >
                            <Link :href="route('admin.projects.index')">
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
                                Update Project
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
