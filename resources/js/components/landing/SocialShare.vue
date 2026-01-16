<script setup lang="ts">
import { computed, ref } from 'vue';
import { Twitter, Linkedin, Facebook, Link as LinkIcon, Copy, Check } from 'lucide-vue-next';

const props = defineProps<{
    url: string;
    title: string;
    description?: string;
}>();

const encodedUrl = computed(() => encodeURIComponent(props.url));
const encodedTitle = computed(() => encodeURIComponent(props.title));
const encodedDescription = computed(() => encodeURIComponent(props.description || ''));

const twitterUrl = computed(() => 
    `https://twitter.com/intent/tweet?url=${encodedUrl.value}&text=${encodedTitle.value}`
);

const linkedinUrl = computed(() => 
    `https://www.linkedin.com/sharing/share-offsite/?url=${encodedUrl.value}`
);

const facebookUrl = computed(() => 
    `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl.value}`
);

const copied = ref(false);

// Check if Web Share API is available
const canShare = computed(() => {
    if (typeof window !== 'undefined' && typeof navigator !== 'undefined') {
        return 'share' in navigator;
    }
    return false;
});

const copyToClipboard = async () => {
    try {
        if (typeof navigator !== 'undefined' && navigator.clipboard) {
            await navigator.clipboard.writeText(props.url);
            copied.value = true;
            setTimeout(() => {
                copied.value = false;
            }, 2000);
        }
    } catch (err) {
        console.error('Failed to copy:', err);
    }
};

const shareNative = async () => {
    if (typeof navigator !== 'undefined' && navigator.share) {
        try {
            await navigator.share({
                title: props.title,
                text: props.description,
                url: props.url,
            });
        } catch (err) {
            // User cancelled or error occurred
        }
    }
};
</script>

<template>
    <div class="flex items-center gap-3">
        <span class="text-sm font-medium text-zinc-400">Share:</span>
        <div class="flex items-center gap-2">
            <a
                :href="twitterUrl"
                target="_blank"
                rel="noopener noreferrer"
                class="flex h-9 w-9 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-zinc-400 transition-all hover:border-indigo-500/50 hover:bg-indigo-500/10 hover:text-indigo-400"
                aria-label="Share on Twitter"
            >
                <Twitter class="h-4 w-4" />
            </a>
            <a
                :href="linkedinUrl"
                target="_blank"
                rel="noopener noreferrer"
                class="flex h-9 w-9 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-zinc-400 transition-all hover:border-indigo-500/50 hover:bg-indigo-500/10 hover:text-blue-400"
                aria-label="Share on LinkedIn"
            >
                <Linkedin class="h-4 w-4" />
            </a>
            <a
                :href="facebookUrl"
                target="_blank"
                rel="noopener noreferrer"
                class="flex h-9 w-9 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-zinc-400 transition-all hover:border-indigo-500/50 hover:bg-indigo-500/10 hover:text-blue-500"
                aria-label="Share on Facebook"
            >
                <Facebook class="h-4 w-4" />
            </a>
            <button
                @click="copyToClipboard"
                class="flex h-9 w-9 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-zinc-400 transition-all hover:border-indigo-500/50 hover:bg-indigo-500/10 hover:text-indigo-400"
                aria-label="Copy link"
            >
                <Check v-if="copied" class="h-4 w-4 text-green-400" />
                <Copy v-else class="h-4 w-4" />
            </button>
            <button
                v-if="canShare"
                @click="shareNative"
                class="flex h-9 w-9 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-zinc-400 transition-all hover:border-indigo-500/50 hover:bg-indigo-500/10 hover:text-indigo-400"
                aria-label="Share"
            >
                <LinkIcon class="h-4 w-4" />
            </button>
        </div>
    </div>
</template>

