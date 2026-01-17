<script setup lang="ts">
import { ArrowUp } from 'lucide-vue-next';
import { onMounted, onUnmounted, ref } from 'vue';

const isVisible = ref(false);

const handleScroll = () => {
    isVisible.value = window.scrollY > 300;
};

const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth',
    });
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
    handleScroll(); // Check initial state
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <button
        v-if="isVisible"
        @click="scrollToTop"
        class="fixed bottom-8 right-8 z-50 flex h-12 w-12 items-center justify-center rounded-full bg-indigo-600 text-white shadow-lg shadow-indigo-500/50 transition-all duration-300 hover:bg-indigo-700 hover:scale-110 hover:shadow-xl hover:shadow-indigo-500/60 active:scale-95"
        aria-label="Scroll to top"
    >
        <ArrowUp class="h-5 w-5" />
    </button>
</template>

