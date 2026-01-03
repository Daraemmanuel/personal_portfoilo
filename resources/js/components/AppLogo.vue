<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
</script>

<template>
    <div
        class="flex aspect-square size-9 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-[0_0_15px_rgba(79,70,229,0.4)]"
    >
        <AppLogoIcon class="size-5 fill-current" />
    </div>
    <div class="ml-3 grid flex-1 text-left text-sm">
        <span class="truncate leading-tight font-bold tracking-tight text-white"
            >KODEMMY</span
        >
        <span
            class="truncate text-[10px] font-bold tracking-widest text-zinc-500 uppercase"
            >PORTFOLIO</span
        >
    </div>
</template>
