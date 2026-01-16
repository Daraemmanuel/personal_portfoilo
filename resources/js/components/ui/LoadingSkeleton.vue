<script setup lang="ts">
defineProps<{
    lines?: number;
    class?: string;
}>();
</script>

<template>
    <div :class="['animate-pulse', class]">
        <div class="space-y-3">
            <div
                v-for="i in lines || 3"
                :key="i"
                class="h-4 rounded bg-white/10"
                :style="{ width: i === lines ? '75%' : '100%' }"
            ></div>
        </div>
    </div>
</template>

