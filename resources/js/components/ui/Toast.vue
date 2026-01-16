<script setup lang="ts">
import { computed } from 'vue';
import { X, CheckCircle, AlertCircle, Info, AlertTriangle } from 'lucide-vue-next';
import { useToast, type Toast } from '@/composables/useToast';

const props = defineProps<{
    toast: Toast;
}>();

const { remove } = useToast();

const icon = computed(() => {
    switch (props.toast.type) {
        case 'success':
            return CheckCircle;
        case 'error':
            return AlertCircle;
        case 'warning':
            return AlertTriangle;
        default:
            return Info;
    }
});

const bgColor = computed(() => {
    switch (props.toast.type) {
        case 'success':
            return 'bg-green-500/10 border-green-500/50 text-green-400';
        case 'error':
            return 'bg-red-500/10 border-red-500/50 text-red-400';
        case 'warning':
            return 'bg-yellow-500/10 border-yellow-500/50 text-yellow-400';
        default:
            return 'bg-blue-500/10 border-blue-500/50 text-blue-400';
    }
});
</script>

<template>
    <div
        :class="[
            'flex items-center gap-3 rounded-lg border px-4 py-3 shadow-lg backdrop-blur-sm',
            bgColor,
        ]"
    >
        <component :is="icon" class="h-5 w-5 flex-shrink-0" />
        <p class="flex-1 text-sm font-medium">{{ toast.message }}</p>
        <button
            @click="remove(toast.id)"
            class="flex-shrink-0 text-current opacity-70 transition-opacity hover:opacity-100"
        >
            <X class="h-4 w-4" />
        </button>
    </div>
</template>

