<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import type { Article } from '@/types/portfolio';
import { useAutoSave } from '@/composables/useAutoSave';

const props = defineProps<{
    article: Article;
}>();

const imageInput = ref<HTMLInputElement | null>(null);
const imagePreview = ref<string | null>(
    props.article.featured_image_url || null,
);

const form = useForm({
    title: props.article.title,
    slug: props.article.slug,
    excerpt: props.article.excerpt,
    content: props.article.content,
    featured_image: null as File | null,
    category: props.article.category || '',
    tags: props.article.tags
        ? props.article.tags.join(', ')
        : '',
    published_at: props.article.published_at
        ? new Date(props.article.published_at)
              .toISOString()
              .slice(0, 16)
        : '',
    series: props.article.series || '',
    series_order: props.article.series_order || null,
    is_featured: props.article.is_featured || false,
    _method: 'PUT',
});

// Auto-save functionality
const autoSave = useAutoSave(form, route('admin.articles.update', props.article.id), {
    debounceMs: 3000,
    enabled: true,
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        tags: data.tags
            .split(',')
            .map((tag) => tag.trim())
            .filter(Boolean),
    })).post(route('admin.articles.update', props.article.id));
};

const handleImageChange = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) {
        form.featured_image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const triggerFileInput = () => {
    imageInput.value?.click();
};

const clearImage = () => {
    form.featured_image = null;
    imagePreview.value = props.article.featured_image_url || null;
};
</script>

<template>
    <AppLayout
        :breadcrumbs="[
            { title: 'Articles', href: route('admin.articles.index') },
            { title: 'Edit', href: '#' },
        ]"
    >
        <Head title="Edit Article" />

        <div class="min-h-[calc(100vh-64px)] bg-zinc-950 p-6 lg:p-10">
            <div class="mx-auto max-w-4xl">
                <!-- Header -->
                <div class="mb-10">
                    <h2
                        class="bg-gradient-to-r from-white to-zinc-500 bg-clip-text text-3xl font-bold tracking-tight text-transparent"
                    >
                        Edit Article
                    </h2>
                    <p class="mt-2 text-zinc-400">
                        Update article information and content.
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
                                >Title</label
                            >
                            <input
                                v-model="form.title"
                                type="text"
                                class="w-full rounded-xl border-white/10 bg-white/5 px-4 py-3 text-white placeholder-zinc-600 shadow-sm transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 sm:text-sm"
                            />
                            <InputError :message="form.errors.title" />
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                >Slug</label
                            >
                            <input
                                v-model="form.slug"
                                type="text"
                                class="w-full rounded-xl border-white/10 bg-white/5 px-4 py-3 text-white placeholder-zinc-600 shadow-sm transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 sm:text-sm"
                            />
                            <InputError :message="form.errors.slug" />
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                >Excerpt</label
                            >
                            <textarea
                                v-model="form.excerpt"
                                rows="3"
                                class="w-full rounded-xl border-white/10 bg-white/5 px-4 py-3 text-white placeholder-zinc-600 shadow-sm transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 sm:text-sm"
                            ></textarea>
                            <InputError :message="form.errors.excerpt" />
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                >Content</label
                            >
                            <textarea
                                v-model="form.content"
                                rows="12"
                                class="w-full rounded-xl border-white/10 bg-white/5 px-4 py-3 text-white placeholder-zinc-600 shadow-sm transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 sm:text-sm font-mono"
                            ></textarea>
                            <InputError :message="form.errors.content" />
                        </div>

                        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                            <div class="space-y-2">
                                <label
                                    class="text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                    >Category</label
                                >
                                <input
                                    v-model="form.category"
                                    type="text"
                                    class="w-full rounded-xl border-white/10 bg-white/5 px-4 py-3 text-white placeholder-zinc-600 shadow-sm transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 sm:text-sm"
                                />
                                <InputError :message="form.errors.category" />
                            </div>

                            <div class="space-y-2">
                                <label
                                    class="text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                    >Series (Optional)</label
                                >
                                <input
                                    v-model="form.series"
                                    type="text"
                                    placeholder="e.g. Laravel Basics"
                                    class="w-full rounded-xl border-white/10 bg-white/5 px-4 py-3 text-white placeholder-zinc-600 shadow-sm transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 sm:text-sm"
                                />
                                <InputError :message="form.errors.series" />
                            </div>
                        </div>

                        <div v-if="form.series" class="grid grid-cols-1 gap-8 md:grid-cols-2">
                            <div class="space-y-2">
                                <label
                                    class="text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                    >Series Order</label
                                >
                                <input
                                    v-model.number="form.series_order"
                                    type="number"
                                    min="1"
                                    placeholder="1, 2, 3..."
                                    class="w-full rounded-xl border-white/10 bg-white/5 px-4 py-3 text-white placeholder-zinc-600 shadow-sm transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 sm:text-sm"
                                />
                                <p class="text-xs text-zinc-500">
                                    Order of this article in the series.
                                </p>
                                <InputError :message="form.errors.series_order" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <label
                                        class="text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                        >Publish Date</label
                                    >
                                    <button
                                        type="button"
                                        @click="form.published_at = new Date().toISOString().slice(0, 16)"
                                        class="text-xs text-indigo-400 hover:text-indigo-300 transition-colors"
                                    >
                                        Publish Now
                                    </button>
                                </div>
                                <input
                                    v-model="form.published_at"
                                    type="datetime-local"
                                    class="w-full rounded-xl border-white/10 bg-white/5 px-4 py-3 text-white placeholder-zinc-600 shadow-sm transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 sm:text-sm"
                                />
                                <p class="text-xs text-zinc-500">
                                    Articles without a publish date will remain as drafts and won't be visible on the public site.
                                </p>
                                <InputError :message="form.errors.published_at" />
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
                                class="w-full rounded-xl border-white/10 bg-white/5 px-4 py-3 text-white placeholder-zinc-600 shadow-sm transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 sm:text-sm"
                            />
                            <InputError :message="form.errors.tags" />
                        </div>

                        <div class="flex items-center gap-3">
                            <input
                                v-model="form.is_featured"
                                type="checkbox"
                                id="is_featured"
                                class="h-4 w-4 rounded border-white/20 bg-zinc-700 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            />
                            <label
                                for="is_featured"
                                class="text-sm font-medium text-zinc-300"
                            >
                                Feature this article on the homepage
                            </label>
                        </div>

                        <div class="space-y-4">
                            <label
                                class="text-sm font-semibold tracking-wider text-zinc-300 uppercase"
                                >Featured Image</label
                            >
                            <div
                                class="relative flex min-h-[200px] cursor-pointer items-center justify-center rounded-2xl border-2 border-dashed border-white/10 bg-white/5 transition-all hover:border-indigo-500/50"
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
                                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-indigo-500/10 text-indigo-400"
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
                                    <p class="mt-3 text-sm text-zinc-400">
                                        Click to upload featured image
                                    </p>
                                </div>
                                <div v-else class="relative w-full p-4">
                                    <img
                                        :src="imagePreview"
                                        class="h-48 w-full rounded-xl object-cover"
                                    />
                                    <button
                                        type="button"
                                        @click.stop="clearImage"
                                        class="absolute top-6 right-6 flex h-8 w-8 items-center justify-center rounded-full bg-red-500 text-white shadow-lg transition-transform hover:scale-110"
                                    >
                                        &times;
                                    </button>
                                </div>
                            </div>
                            <InputError :message="form.errors.featured_image" />
                        </div>

                        <div
                            class="flex items-center justify-between border-t border-white/5 pt-4"
                        >
                            <div class="text-xs text-zinc-500">
                                <span v-if="autoSave.isSaving">Saving...</span>
                                <span v-else-if="autoSave.lastSaved">
                                    Last saved: {{ autoSave.lastSaved.toLocaleTimeString() }}
                                </span>
                            </div>
                            <div class="flex items-center gap-4">
                                <Link
                                    :href="route('articles.show', props.article.slug)"
                                    target="_blank"
                                >
                                    <Button
                                        variant="ghost"
                                        type="button"
                                        class="text-zinc-400 hover:bg-white/5 hover:text-white"
                                    >
                                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Preview
                                    </Button>
                                </Link>
                                <Link :href="route('admin.articles.index')">
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
                                    Update Article
                                </Button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

