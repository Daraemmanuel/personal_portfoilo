<script setup lang="ts">
defineProps<{
    class?: string;
    variant?: 'text' | 'circular' | 'rectangular';
}>();

const variantClasses = {
    text: 'rounded',
    circular: 'rounded-full',
    rectangular: 'rounded-lg',
};
</script>

<template>
    <div
        :class="[
            'animate-pulse bg-zinc-800',
            variantClasses[variant || 'text'],
            class,
        ]"
    />
</template>

