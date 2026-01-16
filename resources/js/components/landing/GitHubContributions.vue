<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { Github } from 'lucide-vue-next';

const props = defineProps<{
    username?: string;
}>();

const contributions = ref<any[]>([]);
const loading = ref(true);

onMounted(async () => {
    if (!props.username) {
        loading.value = false;
        return;
    }

    try {
        // Using GitHub's public API to get contribution data
        // Note: This is a simplified version. For production, consider using GitHub's GraphQL API
        const response = await fetch(`https://api.github.com/users/${props.username}/events/public?per_page=100`, {
            headers: {
                'Accept': 'application/vnd.github.v3+json',
            },
        });
        if (response.ok) {
            const events = await response.json();
            // Process events to show recent activity
            contributions.value = events.slice(0, 20);
        }
    } catch (error) {
        console.error('Failed to load GitHub contributions:', error);
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <div v-if="username" class="rounded-2xl border border-white/10 bg-zinc-900/50 p-6">
        <div class="mb-4 flex items-center gap-3">
            <Github class="h-6 w-6 text-indigo-400" />
            <h3 class="text-lg font-semibold text-white">GitHub Activity</h3>
        </div>
        <div v-if="loading" class="text-center text-zinc-400">
            Loading...
        </div>
        <div v-else-if="contributions.length === 0" class="text-center text-zinc-400">
            <p>No recent activity</p>
            <a
                :href="`https://github.com/${username}`"
                target="_blank"
                class="mt-2 inline-block text-sm text-indigo-400 hover:text-indigo-300"
            >
                View on GitHub →
            </a>
        </div>
        <div v-else class="space-y-2">
            <a
                :href="`https://github.com/${username}`"
                target="_blank"
                class="block rounded-lg border border-white/10 bg-white/5 p-4 transition-colors hover:bg-white/10"
            >
                <p class="text-sm text-white">
                    View all activity on GitHub
                </p>
                <p class="mt-1 text-xs text-zinc-400">
                    @{{ username }}
                </p>
            </a>
        </div>
    </div>
</template>

