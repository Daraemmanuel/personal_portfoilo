<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import RichTextEditor from '@/components/admin/RichTextEditor.vue';
import { Button } from '@/components/ui/button';
import { useAutoSave } from '@/composables/useAutoSave';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Article } from '@/types/portfolio';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

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
    tags: props.article.tags ? props.article.tags.join(', ') : '',
    published_at: props.article.published_at
        ? new Date(props.article.published_at).toISOString().slice(0, 16)
        : '',
    series: props.article.series || '',
    series_order: props.article.series_order || null,
    is_featured: props.article.is_featured || false,
    _method: 'PUT',
});

// Auto-save functionality
const { isSaving, lastSaved } = useAutoSave(
    form,
    route('admin.articles.update', props.article.id),
    {
        debounceMs: 3000,
        enabled: true,
    },
);

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

        <div class="min-h-[calc(100vh-64px)] bg-background p-4 sm:p-6 lg:p-10">
            <div class="mx-auto max-w-5xl">
                <!-- Header -->
                <div class="mb-6 sm:mb-10">
                    <h2
                        class="bg-gradient-to-r from-foreground to-foreground/50 bg-clip-text text-2xl font-bold tracking-tight text-transparent sm:text-3xl"
                    >
                        Edit Article
                    </h2>
                    <p class="mt-1 text-sm text-muted-foreground sm:mt-2 sm:text-base">
                        Update article information and content.
                    </p>
                </div>

                <!-- Form Card -->
                <div
                    class="rounded-2xl border border-border bg-card p-4 shadow-sm transition-colors sm:p-6 lg:p-8"
                >
                    <form @submit.prevent="submit" class="space-y-8">
                        <div class="space-y-2">
                            <label
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                >Title</label
                            >
                            <input
                                v-model="form.title"
                                type="text"
                                class="w-full rounded-xl border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 shadow-sm transition-all focus:border-primary focus:bg-background focus:ring-2 focus:ring-primary/20 sm:text-sm"
                            />
                            <InputError :message="form.errors.title" />
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                >Slug</label
                            >
                            <input
                                v-model="form.slug"
                                type="text"
                                class="w-full rounded-xl border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 shadow-sm transition-all focus:border-primary focus:bg-background focus:ring-2 focus:ring-primary/20 sm:text-sm"
                            />
                            <InputError :message="form.errors.slug" />
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                >Excerpt</label
                            >
                            <RichTextEditor
                                v-model="form.excerpt"
                                placeholder="Brief summary of the article..."
                                :rows="3"
                            />
                            <InputError :message="form.errors.excerpt" />
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                >Content</label
                            >
                            <RichTextEditor
                                v-model="form.content"
                                placeholder="Write your article content here..."
                                :rows="12"
                            />
                            <InputError :message="form.errors.content" />
                        </div>

                        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                            <div class="space-y-2">
                                <label
                                    class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                    >Category</label
                                >
                                <input
                                    v-model="form.category"
                                    type="text"
                                    class="w-full rounded-xl border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 shadow-sm transition-all focus:border-primary focus:bg-background focus:ring-2 focus:ring-primary/20 sm:text-sm"
                                />
                                <InputError :message="form.errors.category" />
                            </div>

                            <div class="space-y-2">
                                <label
                                    class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                    >Series (Optional)</label
                                >
                                <input
                                    v-model="form.series"
                                    type="text"
                                    placeholder="e.g. Laravel Basics"
                                    class="w-full rounded-xl border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 shadow-sm transition-all focus:border-primary focus:bg-background focus:ring-2 focus:ring-primary/20 sm:text-sm"
                                />
                                <InputError :message="form.errors.series" />
                            </div>
                        </div>

                        <div
                            v-if="form.series"
                            class="grid grid-cols-1 gap-8 md:grid-cols-2"
                        >
                            <div class="space-y-2">
                                <label
                                    class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                    >Series Order</label
                                >
                                <input
                                    v-model.number="form.series_order"
                                    type="number"
                                    min="1"
                                    placeholder="1, 2, 3..."
                                    class="w-full rounded-xl border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 shadow-sm transition-all focus:border-primary focus:bg-background focus:ring-2 focus:ring-primary/20 sm:text-sm"
                                />
                                <p class="text-xs text-muted-foreground/60">
                                    Order of this article in the series.
                                </p>
                                <InputError
                                    :message="form.errors.series_order"
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <label
                                        class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                        >Publish Date</label
                                    >
                                    <div class="flex items-center gap-2">
                                        <button
                                            type="button"
                                            @click="
                                                form.published_at = new Date()
                                                    .toISOString()
                                                    .slice(0, 16)
                                            "
                                            class="text-xs font-bold text-primary transition-colors hover:text-primary/80"
                                        >
                                            Now
                                        </button>
                                        <button
                                            type="button"
                                            @click="
                                                const tomorrow = new Date();
                                                tomorrow.setDate(
                                                    tomorrow.getDate() + 1,
                                                );
                                                form.published_at =
                                                    tomorrow
                                                        .toISOString()
                                                        .slice(0, 16);
                                            "
                                            class="text-xs font-bold text-muted-foreground transition-colors hover:text-foreground"
                                        >
                                            Tomorrow
                                        </button>
                                    </div>
                                </div>
                                <input
                                    v-model="form.published_at"
                                    type="datetime-local"
                                    :min="new Date().toISOString().slice(0, 16)"
                                    class="w-full rounded-xl border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 shadow-sm transition-all focus:border-primary focus:bg-background focus:ring-2 focus:ring-primary/20 sm:text-sm"
                                />
                                <div
                                    v-if="form.published_at"
                                    class="mt-2 flex items-center gap-2 rounded-lg border border-blue-500/20 bg-blue-500/10 px-3 py-2"
                                >
                                    <span
                                        class="text-xs font-medium text-blue-600 dark:text-blue-400"
                                    >
                                        {{
                                            new Date(
                                                form.published_at,
                                            ) <= new Date()
                                                ? 'Published'
                                                : 'Scheduled for'
                                        }}:
                                        {{
                                            new Date(
                                                form.published_at,
                                            ).toLocaleString()
                                        }}
                                    </span>
                                </div>
                                <p
                                    v-else
                                    class="text-xs text-muted-foreground/60"
                                >
                                    Leave empty to save as draft. Articles
                                    without a publish date won't be visible on
                                    the public site.
                                </p>
                                <InputError
                                    :message="form.errors.published_at"
                                />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                >Tags</label
                            >
                            <input
                                v-model="form.tags"
                                type="text"
                                class="w-full rounded-xl border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 shadow-sm transition-all focus:border-primary focus:bg-background focus:ring-2 focus:ring-primary/20 sm:text-sm"
                            />
                            <InputError :message="form.errors.tags" />
                        </div>

                        <div class="flex items-center gap-3">
                            <input
                                v-model="form.is_featured"
                                type="checkbox"
                                id="is_featured"
                                class="h-5 w-5 rounded border-border bg-muted text-primary shadow-sm focus:ring-primary/20"
                            />
                            <label
                                for="is_featured"
                                class="text-sm font-bold tracking-widest text-muted-foreground uppercase"
                            >
                                Feature this article on the homepage
                            </label>
                        </div>

                        <div class="space-y-4">
                            <label
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                >Featured Image</label
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
                                        Click to upload featured image
                                    </p>
                                </div>
                                <div v-else class="relative w-full p-4">
                                    <img
                                        :src="imagePreview"
                                        class="h-64 w-full rounded-xl border border-border object-cover shadow-lg"
                                    />
                                    <button
                                        type="button"
                                        @click.stop="clearImage"
                                        class="absolute top-8 right-8 flex h-10 w-10 items-center justify-center rounded-full bg-destructive text-destructive-foreground shadow-lg transition-transform hover:scale-110"
                                    >
                                        <span class="text-xl">&times;</span>
                                    </button>
                                </div>
                            </div>
                            <InputError :message="form.errors.featured_image" />
                        </div>

                        <div
                            class="flex items-center justify-between border-t border-border pt-8"
                        >
                            <div
                                class="text-xs font-medium text-muted-foreground"
                            >
                                <span
                                    v-if="isSaving"
                                    class="flex items-center gap-2"
                                >
                                    Saving...
                                </span>
                                <span v-else-if="lastSaved">
                                    Last saved:
                                    {{ lastSaved.toLocaleTimeString() }}
                                </span>
                            </div>
                            <div class="flex items-center gap-4">
                                <Link
                                    :href="
                                        route(
                                            'articles.show',
                                            props.article.slug,
                                        )
                                    "
                                    target="_blank"
                                >
                                    <Button
                                        variant="ghost"
                                        type="button"
                                        class="font-bold text-muted-foreground hover:text-foreground"
                                    >
                                        <svg
                                            class="mr-2 h-4 w-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                            />
                                        </svg>
                                        Preview
                                    </Button>
                                </Link>
                                <Link :href="route('admin.articles.index')">
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
