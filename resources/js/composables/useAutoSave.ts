import { ref, watch, onBeforeUnmount } from 'vue';
import { router } from '@inertiajs/vue3';
import { useToast } from './useToast';

interface AutoSaveOptions {
    debounceMs?: number;
    enabled?: boolean;
    onSave?: () => void;
    onError?: (error: any) => void;
}

export function useAutoSave(
    form: any,
    saveUrl: string,
    options: AutoSaveOptions = {}
) {
    const { debounceMs = 2000, enabled = true, onSave, onError } = options;
    const { success, error } = useToast();
    const isSaving = ref(false);
    const lastSaved = ref<Date | null>(null);
    let debounceTimer: ReturnType<typeof setTimeout> | null = null;

    const save = async () => {
        if (!enabled || form.processing || isSaving.value) {
            return;
        }

        // Don't save if form is empty
        if (!form.title && !form.content) {
            return;
        }

        isSaving.value = true;

        try {
            await new Promise<void>((resolve, reject) => {
                // Transform tags if needed
                const data = form.tags && typeof form.tags === 'string'
                    ? {
                          ...form.data(),
                          tags: form.tags.split(',').map((tag: string) => tag.trim()).filter(Boolean),
                      }
                    : form.data();

                form.transform(() => data).post(saveUrl, {
                    preserveState: true,
                    preserveScroll: true,
                    only: ['article', 'errors'],
                    onSuccess: () => {
                        lastSaved.value = new Date();
                        onSave?.();
                        resolve();
                    },
                    onError: (errors: any) => {
                        onError?.(errors);
                        reject(errors);
                    },
                    onFinish: () => {
                        isSaving.value = false;
                    },
                });
            });
        } catch (err) {
            // Silent fail for auto-save
            console.warn('Auto-save failed:', err);
        }
    };

    const debouncedSave = () => {
        if (debounceTimer) {
            clearTimeout(debounceTimer);
        }

        debounceTimer = setTimeout(() => {
            save();
        }, debounceMs);
    };

    // Watch form fields for changes
    watch(
        () => [form.title, form.content, form.excerpt],
        () => {
            if (enabled && form.title) {
                debouncedSave();
            }
        },
        { deep: true }
    );

    onBeforeUnmount(() => {
        if (debounceTimer) {
            clearTimeout(debounceTimer);
        }
    });

    return {
        isSaving,
        lastSaved,
        save: debouncedSave,
        saveNow: save,
    };
}
