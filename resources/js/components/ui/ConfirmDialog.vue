<script setup lang="ts">
import { Button } from './button';
import { AlertTriangle } from 'lucide-vue-next';

defineProps<{
    title?: string;
    message: string;
    confirmText?: string;
    cancelText?: string;
    variant?: 'danger' | 'warning' | 'info';
}>();

const emit = defineEmits<{
    confirm: [];
    cancel: [];
}>();

const variantColors = {
    danger: 'text-red-400',
    warning: 'text-yellow-400',
    info: 'text-blue-400',
};
</script>

<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="mx-4 w-full max-w-md rounded-2xl border border-white/10 bg-zinc-900 p-6 shadow-xl">
            <div class="mb-4 flex items-center gap-3">
                <AlertTriangle
                    :class="[
                        'h-6 w-6',
                        variantColors[variant || 'warning'],
                    ]"
                />
                <h3 class="text-lg font-semibold text-white">
                    {{ title || 'Confirm Action' }}
                </h3>
            </div>
            <p class="mb-6 text-zinc-400">
                {{ message }}
            </p>
            <div class="flex justify-end gap-3">
                <Button
                    variant="ghost"
                    @click="emit('cancel')"
                    class="text-zinc-400 hover:text-white"
                >
                    {{ cancelText || 'Cancel' }}
                </Button>
                <Button
                    :class="[
                        variant === 'danger'
                            ? 'bg-red-600 hover:bg-red-700'
                            : variant === 'warning'
                            ? 'bg-yellow-600 hover:bg-yellow-700'
                            : 'bg-indigo-600 hover:bg-indigo-700',
                    ]"
                    @click="emit('confirm')"
                >
                    {{ confirmText || 'Confirm' }}
                </Button>
            </div>
        </div>
    </div>
</template>

