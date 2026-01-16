<script setup lang="ts">
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { Search, X } from 'lucide-vue-next';

const props = defineProps<{
    initialQuery?: string;
}>();

const searchQuery = ref(props.initialQuery || '');
const isOpen = ref(false);

const performSearch = () => {
    if (searchQuery.value.trim()) {
        router.get(route('search'), { q: searchQuery.value, type: 'all' }, {
            preserveState: true,
            preserveScroll: true,
        });
    }
};

const clearSearch = () => {
    searchQuery.value = '';
    if (isOpen.value) {
        isOpen.value = false;
    }
};

const handleKeydown = (e: KeyboardEvent) => {
    if (e.key === 'Enter') {
        e.preventDefault();
        performSearch();
    } else if (e.key === 'Escape') {
        clearSearch();
    }
};

watch(() => props.initialQuery, (newVal) => {
    searchQuery.value = newVal || '';
});
</script>

<template>
    <div class="relative">
        <div class="relative flex items-center">
            <input
                v-model="searchQuery"
                type="text"
                placeholder="Search articles, projects..."
                class="w-full rounded-full border border-white/10 bg-white/5 px-4 py-2 pl-10 pr-10 text-sm text-white placeholder-zinc-500 transition-all focus:border-indigo-500 focus:bg-white/10 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 md:w-64"
                @keydown="handleKeydown"
                @focus="isOpen = true"
            />
            <Search
                class="absolute left-3 h-4 w-4 text-zinc-500"
            />
            <button
                v-if="searchQuery"
                @click="clearSearch"
                class="absolute right-3 flex h-5 w-5 items-center justify-center rounded-full bg-white/10 text-zinc-400 transition-colors hover:bg-white/20 hover:text-white"
            >
                <X class="h-3 w-3" />
            </button>
        </div>
    </div>
</template>

