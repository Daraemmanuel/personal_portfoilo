<template>
    <div
        aria-live="polite"
        aria-atomic="true"
        class="sr-only"
        role="status"
    >
        {{ message }}
    </div>
</template>

<script setup lang="ts">
import { useAriaLive } from '@/composables/useAriaLive';

const { message } = useAriaLive();
</script>

<style scoped>
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border-width: 0;
}
</style>

