<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { useAutoSave } from '@/composables/useAutoSave';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Save } from 'lucide-vue-next';
import { ref } from 'vue';

const imageInput = ref<HTMLInputElement | null>(null);
const imagePreview = ref<string | null>(null);

const form = useForm({
    title: '',
    slug: '',
    excerpt: '',
    content: '',
    featured_image: null as File | null,
    category: '',
    tags: '',
    published_at: '',
    series: '',
    series_order: null as number | null,
    is_featured: false,
});

// Auto-save (disabled for new articles, only works on edit)
const { isSaving, lastSaved } = useAutoSave(
    form,
    route('admin.articles.store'),
    {
        debounceMs: 3000,
        enabled: false, // Disable for create, enable in edit
    },
);

const submit = () => {
    form.transform((data) => ({
        ...data,
        tags: data.tags
            .split(',')
            .map((tag) => tag.trim())
            .filter(Boolean),
    })).post(route('admin.articles.store'));
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
    imagePreview.value = null;
};
</script>

<template>
    <AppLayout
        :breadcrumbs="[
            { title: 'Articles', href: route('admin.articles.index') },
            { title: 'Create', href: '#' },
        ]"
    >
        <Head title="Create Article" />

        <div class="min-h-[calc(100vh-64px)] bg-background p-4 sm:p-6 lg:p-10">
            <div class="mx-auto max-w-5xl">
                <!-- Header -->
                <div class="mb-6 sm:mb-10">
                    <h2
                        class="bg-gradient-to-r from-foreground to-foreground/50 bg-clip-text text-2xl font-bold tracking-tight text-transparent sm:text-3xl"
                    >
                        New Article
                    </h2>
                    <p class="mt-1 text-sm text-muted-foreground sm:mt-2 sm:text-base">
                        Create a new blog post or article.
                    </p>
                </div>

                <!-- Form Card -->
                <div
                    class="rounded-2xl border border-border bg-card p-4 shadow-sm transition-colors sm:p-6 lg:p-8"
                >
                    <form @submit.prevent="submit" class="space-y-6 sm:space-y-8">
                        <div class="space-y-2">
                            <label
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                >Title</label
                            >
                            <input
                                v-model="form.title"
                                type="text"
                                placeholder="Article Title"
                                class="w-full rounded-xl border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 shadow-sm transition-all focus:border-primary focus:bg-background focus:ring-2 focus:ring-primary/20 sm:text-sm"
                            />
                            <InputError :message="form.errors.title" />
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                >Slug (Optional - auto-generated from
                                title)</label
                            >
                            <input
                                v-model="form.slug"
                                type="text"
                                placeholder="article-slug"
                                class="w-full rounded-xl border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 shadow-sm transition-all focus:border-primary focus:bg-background focus:ring-2 focus:ring-primary/20 sm:text-sm"
                            />
                            <InputError :message="form.errors.slug" />
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                >Excerpt</label
                            >
                            <textarea
                                v-model="form.excerpt"
                                rows="3"
                                placeholder="Brief summary of the article..."
                                class="w-full rounded-xl border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 shadow-sm transition-all focus:border-primary focus:bg-background focus:ring-2 focus:ring-primary/20 sm:text-sm"
                            ></textarea>
                            <InputError :message="form.errors.excerpt" />
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                >Content</label
                            >
                            <textarea
                                v-model="form.content"
                                rows="12"
                                placeholder="Write your article content here..."
                                class="w-full rounded-xl border-border bg-muted/50 px-4 py-3 font-mono text-foreground placeholder-muted-foreground/50 shadow-sm transition-all focus:border-primary focus:bg-background focus:ring-2 focus:ring-primary/20 sm:text-sm"
                            ></textarea>
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
                                    placeholder="e.g. Tutorial, News"
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
                                        Scheduled for:
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
                                placeholder="Laravel, Vue, Tutorial"
                                class="w-full rounded-xl border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 shadow-sm transition-all focus:border-primary focus:bg-background focus:ring-2 focus:ring-primary/20 sm:text-sm"
                            />
                            <span
                                class="text-[10px] font-bold tracking-widest text-muted-foreground/60 uppercase"
                                >Separate with commas</span
                            >
                            <InputError :message="form.errors.tags" />
                        </div>

                        <div class="space-y-4">
                            <label
                                class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                                >Featured Image (Optional)</label
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
                                    <Save class="h-3 w-3 animate-pulse" />
                                    Saving...
                                </span>
                                <span v-else-if="lastSaved">
                                    Last saved:
                                    {{ lastSaved.toLocaleTimeString() }}
                                </span>
                            </div>
                            <div class="flex items-center gap-6">
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
                                    Publish Article
                                </Button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
