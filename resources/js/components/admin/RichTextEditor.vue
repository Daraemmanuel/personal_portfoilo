<script setup lang="ts">
import Placeholder from '@tiptap/extension-placeholder';
import StarterKit from '@tiptap/starter-kit';
import Image from '@tiptap/extension-image';
import { EditorContent, useEditor } from '@tiptap/vue-3';
import {
    Bold,
    Heading1,
    Heading2,
    Heading3,
    Italic,
    List,
    ListOrdered,
    Quote,
    Redo,
    Undo,
    Image as ImageIcon,
    Loader2,
    Link as LinkIcon,
} from 'lucide-vue-next';
import { onBeforeUnmount, ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
    modelValue: string;
    placeholder?: string;
    rows?: number;
}>();

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

const editor = useEditor({
    content: props.modelValue,
    extensions: [
        StarterKit.configure({
            heading: {
                levels: [1, 2, 3],
            },
        }),
        Image.configure({
            HTMLAttributes: {
                class: 'my-4 rounded-xl shadow-md',
            },
        }),
        Placeholder.configure({
            placeholder: props.placeholder || 'Start typing...',
        }),
    ],
    editorProps: {
        attributes: {
            class: 'prose prose-sm sm:prose-base lg:prose-lg xl:prose-2xl focus:outline-none p-4',
            style: `min-height: ${props.rows ? props.rows * 24 : 200}px;`,
        },
    },
    onUpdate: ({ editor }) => {
        emit('update:modelValue', editor.getHTML());
    },
});

watch(
    () => props.modelValue,
    (value) => {
        const isSame = editor.value?.getHTML() === value;
        if (!isSame) {
            editor.value?.commands.setContent(value || '');
        }
    },
);

onBeforeUnmount(() => {
    editor.value?.destroy();
});

const toggleBold = () => {
    editor.value?.chain().focus().toggleBold().run();
};

const toggleItalic = () => {
    editor.value?.chain().focus().toggleItalic().run();
};

const toggleHeading = (level: 1 | 2 | 3) => {
    editor.value?.chain().focus().toggleHeading({ level }).run();
};

const toggleBulletList = () => {
    editor.value?.chain().focus().toggleBulletList().run();
};

const toggleOrderedList = () => {
    editor.value?.chain().focus().toggleOrderedList().run();
};

const toggleBlockquote = () => {
    editor.value?.chain().focus().toggleBlockquote().run();
};

const imageInput = ref<HTMLInputElement | null>(null);
const isUploading = ref(false);

const triggerImageInput = () => {
    imageInput.value?.click();
};

const handleImageUpload = async (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (!file) return;

    // Validate file type
    if (!file.type.startsWith('image/')) {
        alert('Please select an image file');
        return;
    }

    // Validate file size (5MB max)
    if (file.size > 5 * 1024 * 1024) {
        alert('Image size must be less than 5MB');
        return;
    }

    isUploading.value = true;

    try {
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content');

        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }

        const formData = new FormData();
        formData.append('image', file);

        const response = await fetch(route('admin.media.upload-image'), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'include',
            body: formData,
        });

        if (!response.ok) {
            let errorMessage = 'Upload failed';
            try {
                const errorData = await response.json();
                errorMessage = errorData.message || errorData.error || errorMessage;
            } catch {
                errorMessage = response.statusText || errorMessage;
            }
            throw new Error(errorMessage);
        }

        const data = await response.json();

        // Insert image into editor
        editor.value
            ?.chain()
            .focus()
            .setImage({ src: data.url })
            .run();

        // Reset input
        if (imageInput.value) {
            imageInput.value.value = '';
        }
    } catch (error) {
        console.error('Image upload error:', error);
        const errorMessage =
            error instanceof Error
                ? error.message
                : 'Failed to upload image. Please try again.';
        alert(errorMessage);
    } finally {
        isUploading.value = false;
    }
};

const insertImageByUrl = () => {
    const url = window.prompt('Enter image URL (https://...)');
    if (!url) return;

    editor.value
        ?.chain()
        .focus()
        .setImage({ src: url })
        .run();
};

const undo = () => {
    editor.value?.chain().focus().undo().run();
};

const redo = () => {
    editor.value?.chain().focus().redo().run();
};
</script>

<template>
    <div
        class="rounded-xl border border-border bg-muted/50 transition-all focus-within:border-primary focus-within:bg-background focus-within:ring-2 focus-within:ring-primary/20"
    >
        <!-- Toolbar -->
        <div
            class="flex flex-wrap items-center gap-2 border-b border-border bg-muted/30 p-2"
        >
                   <button
                       type="button"
                       @click="toggleBold"
                       :class="[
                           'flex h-8 w-8 items-center justify-center rounded transition-colors',
                           editor?.isActive('bold')
                               ? 'bg-primary text-primary-foreground'
                               : 'text-muted-foreground hover:bg-muted hover:text-foreground',
                       ]"
                       title="Bold (Ctrl+B)"
                       aria-label="Bold"
                   >
                       <Bold class="h-4 w-4" />
                   </button>
            <button
                type="button"
                @click="toggleItalic"
                :class="[
                    'flex h-8 w-8 items-center justify-center rounded transition-colors',
                    editor?.isActive('italic')
                        ? 'bg-primary text-primary-foreground'
                        : 'text-muted-foreground hover:bg-muted hover:text-foreground',
                ]"
                title="Italic (Ctrl+I)"
            >
                <Italic class="h-4 w-4" />
            </button>
            <div class="h-6 w-px bg-border"></div>
            <button
                type="button"
                @click="toggleHeading(1)"
                :class="[
                    'flex h-8 w-8 items-center justify-center rounded transition-colors',
                    editor?.isActive('heading', { level: 1 })
                        ? 'bg-primary text-primary-foreground'
                        : 'text-muted-foreground hover:bg-muted hover:text-foreground',
                ]"
                       title="Heading 1"
                       aria-label="Heading 1"
                   >
                       <Heading1 class="h-4 w-4" />
                   </button>
                   <button
                       type="button"
                       @click="toggleHeading(2)"
                       :class="[
                           'flex h-8 w-8 items-center justify-center rounded transition-colors',
                           editor?.isActive('heading', { level: 2 })
                               ? 'bg-primary text-primary-foreground'
                               : 'text-muted-foreground hover:bg-muted hover:text-foreground',
                       ]"
                       title="Heading 2"
                       aria-label="Heading 2"
                   >
                       <Heading2 class="h-4 w-4" />
                   </button>
                   <button
                       type="button"
                       @click="toggleHeading(3)"
                       :class="[
                           'flex h-8 w-8 items-center justify-center rounded transition-colors',
                           editor?.isActive('heading', { level: 3 })
                               ? 'bg-primary text-primary-foreground'
                               : 'text-muted-foreground hover:bg-muted hover:text-foreground',
                       ]"
                       title="Heading 3"
                       aria-label="Heading 3"
                   >
                       <Heading3 class="h-4 w-4" />
                   </button>
            <div class="h-6 w-px bg-border"></div>
            <button
                type="button"
                @click="toggleBulletList"
                :class="[
                    'flex h-8 w-8 items-center justify-center rounded transition-colors',
                    editor?.isActive('bulletList')
                        ? 'bg-primary text-primary-foreground'
                        : 'text-muted-foreground hover:bg-muted hover:text-foreground',
                ]"
                       title="Bullet List"
                       aria-label="Bullet List"
                   >
                       <List class="h-4 w-4" />
                   </button>
                   <button
                       type="button"
                       @click="toggleOrderedList"
                       :class="[
                           'flex h-8 w-8 items-center justify-center rounded transition-colors',
                           editor?.isActive('orderedList')
                               ? 'bg-primary text-primary-foreground'
                               : 'text-muted-foreground hover:bg-muted hover:text-foreground',
                       ]"
                       title="Numbered List"
                       aria-label="Numbered List"
                   >
                       <ListOrdered class="h-4 w-4" />
                   </button>
                   <button
                       type="button"
                       @click="toggleBlockquote"
                       :class="[
                           'flex h-8 w-8 items-center justify-center rounded transition-colors',
                           editor?.isActive('blockquote')
                               ? 'bg-primary text-primary-foreground'
                               : 'text-muted-foreground hover:bg-muted hover:text-foreground',
                       ]"
                       title="Quote"
                       aria-label="Blockquote"
                   >
                       <Quote class="h-4 w-4" />
                   </button>
            <div class="relative">
                <input
                    ref="imageInput"
                    type="file"
                    accept="image/*"
                    class="hidden"
                    @change="handleImageUpload"
                />
                <button
                    type="button"
                    @click="triggerImageInput"
                    :disabled="isUploading"
                    :class="[
                        'flex h-8 w-8 items-center justify-center rounded transition-colors',
                        isUploading
                            ? 'cursor-not-allowed opacity-50'
                            : 'text-muted-foreground hover:bg-muted hover:text-foreground',
                    ]"
                    :title="isUploading ? 'Uploading...' : 'Upload Image'"
                    :aria-label="isUploading ? 'Uploading image...' : 'Upload Image'"
                >
                    <ImageIcon v-if="!isUploading" class="h-4 w-4" />
                    <Loader2 v-else class="h-4 w-4 animate-spin" />
                </button>
            </div>
            <button
                type="button"
                @click="insertImageByUrl"
                :class="[
                    'flex h-8 w-8 items-center justify-center rounded transition-colors',
                    'text-muted-foreground hover:bg-muted hover:text-foreground',
                ]"
                title="Insert Image by URL"
                aria-label="Insert Image by URL"
            >
                <LinkIcon class="h-4 w-4" />
            </button>
            <div class="h-6 w-px bg-border"></div>
            <button
                type="button"
                @click="undo"
                :disabled="!editor?.can().undo()"
                :class="[
                    'flex h-8 w-8 items-center justify-center rounded transition-colors',
                    editor?.can().undo()
                        ? 'text-muted-foreground hover:bg-muted hover:text-foreground'
                        : 'cursor-not-allowed opacity-50',
                ]"
                title="Undo (Ctrl+Z)"
                aria-label="Undo"
            >
                <Undo class="h-4 w-4" />
            </button>
            <button
                type="button"
                @click="redo"
                :disabled="!editor?.can().redo()"
                :class="[
                    'flex h-8 w-8 items-center justify-center rounded transition-colors',
                    editor?.can().redo()
                        ? 'text-muted-foreground hover:bg-muted hover:text-foreground'
                        : 'cursor-not-allowed opacity-50',
                ]"
                title="Redo (Ctrl+Y)"
                aria-label="Redo"
            >
                <Redo class="h-4 w-4" />
            </button>
        </div>
        <!-- Editor Content -->
        <div class="max-h-[600px] min-h-[200px] overflow-y-auto">
            <EditorContent :editor="editor" />
        </div>
    </div>
</template>

<style>
.ProseMirror {
    outline: none;
    min-height: 200px;
}

.ProseMirror p.is-editor-empty:first-child::before {
    color: hsl(var(--muted-foreground) / 0.5);
    content: attr(data-placeholder);
    float: left;
    height: 0;
    pointer-events: none;
}

.ProseMirror h1 {
    font-size: 2.25rem;
    font-weight: 800;
    line-height: 1.2;
    margin-top: 0;
    margin-bottom: 0.75rem;
}

.ProseMirror h2 {
    font-size: 1.875rem;
    font-weight: 700;
    line-height: 1.3;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
}

.ProseMirror h3 {
    font-size: 1.5rem;
    font-weight: 600;
    line-height: 1.4;
    margin-top: 1.25rem;
    margin-bottom: 0.5rem;
}

.ProseMirror p {
    margin-top: 0.75rem;
    margin-bottom: 0.75rem;
    line-height: 1.75;
}

.ProseMirror ul,
.ProseMirror ol {
    margin-top: 0.75rem;
    margin-bottom: 0.75rem;
    padding-left: 1.75rem;
}

.ProseMirror ul {
    list-style-type: disc;
}

.ProseMirror ol {
    list-style-type: decimal;
}

.ProseMirror li {
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
}

.ProseMirror blockquote {
    border-left: 4px solid hsl(var(--border));
    padding-left: 1rem;
    margin-top: 1rem;
    margin-bottom: 1rem;
    font-style: italic;
    color: hsl(var(--muted-foreground));
}

.ProseMirror strong {
    font-weight: 700;
}

.ProseMirror em {
    font-style: italic;
}

.ProseMirror img {
    max-width: 100%;
    height: auto;
    border-radius: 0.75rem;
    margin: 1rem 0;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.ProseMirror img.ProseMirror-selectednode {
    outline: 2px solid hsl(var(--primary));
    outline-offset: 2px;
}
</style>
