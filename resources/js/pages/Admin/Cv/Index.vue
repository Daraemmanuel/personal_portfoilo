<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { useToast } from '@/composables/useToast';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Download, FileText, Trash2, Upload } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    cv?: {
        id: number;
        file_name: string;
        file_size: number;
        file_size_human?: string;
        created_at: string;
        file_url?: string;
    } | null;
}>();

const { success, error } = useToast();
const fileInput = ref<HTMLInputElement | null>(null);
const filePreview = ref<string | null>(null);

const form = useForm({
    cv_file: null as File | null,
});

const deleteForm = useForm({});

const handleFileChange = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) {
        if (file.type !== 'application/pdf') {
            error('Please upload a PDF file.');
            return;
        }
        if (file.size > 10 * 1024 * 1024) {
            error('File size must be less than 10MB.');
            return;
        }
        form.cv_file = file;
        filePreview.value = file.name;
    }
};

const submit = () => {
    if (!form.cv_file) {
        error('Please select a PDF file to upload.');
        return;
    }

    form.post(route('admin.cv.store'), {
        onSuccess: () => {
            success('CV uploaded successfully!');
            form.reset();
            filePreview.value = null;
            if (fileInput.value) {
                fileInput.value.value = '';
            }
        },
        onError: () => {
            error('Failed to upload CV. Please try again.');
        },
    });
};

const deleteCv = () => {
    if (confirm('Are you sure you want to delete this CV?')) {
        deleteForm.delete(route('admin.cv.destroy', props.cv?.id), {
            onSuccess: () => {
                success('CV deleted successfully!');
            },
            onError: () => {
                error('Failed to delete CV. Please try again.');
            },
        });
    }
};

const triggerFileInput = () => {
    fileInput.value?.click();
};
</script>

<template>
    <AppLayout
        :breadcrumbs="[
            { title: 'CV Management', href: route('admin.cv.index') },
        ]"
    >
        <Head title="CV Management" />

        <div class="min-h-[calc(100vh-64px)] bg-background p-6 lg:p-10">
            <div class="mx-auto max-w-4xl">
                <!-- Header -->
                <div class="mb-10">
                    <h2
                        class="bg-gradient-to-r from-foreground to-foreground/50 bg-clip-text text-3xl font-bold tracking-tight text-transparent"
                    >
                        CV Management
                    </h2>
                    <p class="mt-2 text-muted-foreground">
                        Upload and manage your CV/Resume. Visitors can download
                        it from the landing page.
                    </p>
                </div>

                <!-- Current CV Card -->
                <div
                    v-if="cv"
                    class="mb-8 rounded-2xl border border-border bg-card p-8 shadow-sm backdrop-blur-sm"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-4">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-lg bg-destructive/10 text-destructive"
                            >
                                <FileText class="h-6 w-6" />
                            </div>
                            <div>
                                <h3
                                    class="text-lg font-semibold text-foreground"
                                >
                                    {{ cv.file_name }}
                                </h3>
                                <p class="mt-1 text-sm text-muted-foreground">
                                    {{
                                        cv.file_size_human ||
                                        `${(cv.file_size / 1024).toFixed(2)} KB`
                                    }}
                                </p>
                                <p
                                    class="mt-1 text-xs text-muted-foreground/60"
                                >
                                    Uploaded:
                                    {{
                                        new Date(
                                            cv.created_at,
                                        ).toLocaleDateString()
                                    }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a
                                :href="route('cv.download')"
                                target="_blank"
                                class="inline-flex items-center gap-2 rounded-lg border border-border bg-muted/50 px-4 py-2 text-sm font-medium text-foreground transition-colors hover:bg-muted"
                            >
                                <Download class="h-4 w-4" />
                                Download
                            </a>
                            <Button
                                variant="ghost"
                                size="sm"
                                @click="deleteCv"
                                class="text-destructive hover:bg-destructive/10 hover:text-destructive"
                            >
                                <Trash2 class="h-4 w-4" />
                            </Button>
                        </div>
                    </div>
                </div>

                <!-- Upload Form -->
                <div
                    class="rounded-2xl border border-border bg-card p-8 shadow-sm backdrop-blur-sm"
                >
                    <h3 class="mb-6 text-xl font-semibold text-foreground">
                        {{ cv ? 'Replace CV' : 'Upload CV' }}
                    </h3>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="space-y-2">
                            <label
                                class="text-xs font-semibold tracking-wider text-muted-foreground uppercase"
                            >
                                CV File (PDF only, max 10MB)
                            </label>
                            <div
                                class="relative flex min-h-[200px] cursor-pointer items-center justify-center rounded-2xl border-2 border-dashed border-border bg-muted/30 transition-all hover:border-primary/50"
                                @click="triggerFileInput"
                            >
                                <input
                                    ref="fileInput"
                                    type="file"
                                    class="hidden"
                                    accept="application/pdf"
                                    @change="handleFileChange"
                                />
                                <div v-if="!filePreview" class="text-center">
                                    <div
                                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary"
                                    >
                                        <Upload class="h-6 w-6" />
                                    </div>
                                    <p
                                        class="mt-3 text-sm text-muted-foreground"
                                    >
                                        Click to upload PDF file
                                    </p>
                                    <p
                                        class="mt-1 text-xs text-muted-foreground/60"
                                    >
                                        Maximum file size: 10MB
                                    </p>
                                </div>
                                <div v-else class="relative w-full p-4">
                                    <div
                                        class="flex items-center gap-3 rounded-lg bg-muted/50 p-4"
                                    >
                                        <FileText
                                            class="h-8 w-8 text-primary"
                                        />
                                        <div class="flex-1">
                                            <p
                                                class="text-sm font-medium text-foreground"
                                            >
                                                {{ filePreview }}
                                            </p>
                                            <p
                                                class="text-xs text-muted-foreground"
                                            >
                                                {{
                                                    form.cv_file
                                                        ? `${(form.cv_file.size / 1024).toFixed(2)} KB`
                                                        : ''
                                                }}
                                            </p>
                                        </div>
                                        <button
                                            type="button"
                                            @click.stop="
                                                form.cv_file = null;
                                                filePreview = null;
                                                if (fileInput)
                                                    fileInput.value = '';
                                            "
                                            class="flex h-8 w-8 items-center justify-center rounded-full bg-destructive/10 text-destructive transition-colors hover:bg-destructive/20"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <InputError :message="form.errors.cv_file" />
                        </div>

                        <div
                            class="flex items-center justify-end gap-4 border-t border-border pt-4"
                        >
                            <Button
                                type="submit"
                                :disabled="form.processing || !form.cv_file"
                                class="rounded-xl bg-primary px-8 py-2.5 font-bold shadow-lg shadow-primary/20 transition-all active:scale-95 disabled:opacity-50"
                            >
                                <Upload class="mr-2 h-4 w-4" />
                                {{ cv ? 'Replace CV' : 'Upload CV' }}
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
