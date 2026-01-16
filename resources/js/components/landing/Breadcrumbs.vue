<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronRight, Home } from 'lucide-vue-next';

defineProps<{
    items: Array<{
        label: string;
        href?: string;
    }>;
}>();
</script>

<template>
    <nav aria-label="Breadcrumb" class="mb-8">
        <ol class="flex items-center gap-2 text-sm text-zinc-400">
            <li>
                <Link
                    :href="route('home')"
                    class="flex items-center gap-1 transition-colors hover:text-white"
                >
                    <Home class="h-4 w-4" />
                    <span class="sr-only">Home</span>
                </Link>
            </li>
            <li v-for="(item, index) in items" :key="index" class="flex items-center gap-2">
                <ChevronRight class="h-4 w-4 text-zinc-600" />
                <Link
                    v-if="item.href"
                    :href="item.href"
                    class="transition-colors hover:text-white"
                >
                    {{ item.label }}
                </Link>
                <span v-else class="text-white">{{ item.label }}</span>
            </li>
        </ol>
    </nav>
</template>

