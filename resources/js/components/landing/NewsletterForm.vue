<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { useToast } from '@/composables/useToast';
import { Button } from '@/components/ui/button';
import { Mail, Loader2 } from 'lucide-vue-next';

const { success, error } = useToast();
const email = ref('');
const name = ref('');
const isSubmitting = ref(false);

const submit = async () => {
    if (!email.value) return;

    isSubmitting.value = true;
    try {
        await router.post(route('newsletter.subscribe'), {
            email: email.value,
            name: name.value,
        }, {
            preserveScroll: true,
            onSuccess: () => {
                success('Successfully subscribed to newsletter!');
                email.value = '';
                name.value = '';
            },
            onError: (errors) => {
                error(errors.email || 'Failed to subscribe. Please try again.');
            },
            onFinish: () => {
                isSubmitting.value = false;
            },
        });
    } catch (err) {
        error('Failed to subscribe. Please try again.');
        isSubmitting.value = false;
    }
};
</script>

<template>
    <div class="rounded-2xl border border-white/10 bg-zinc-900/50 p-8">
        <div class="mb-6 flex items-center gap-3">
            <Mail class="h-6 w-6 text-indigo-400" />
            <h3 class="text-xl font-semibold text-white">Subscribe to Newsletter</h3>
        </div>
        <p class="mb-6 text-zinc-400">
            Get the latest articles and updates delivered to your inbox.
        </p>
        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <input
                    v-model="name"
                    type="text"
                    placeholder="Your name (optional)"
                    class="w-full rounded-lg border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-zinc-600 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                />
            </div>
            <div class="flex gap-2">
                <input
                    v-model="email"
                    type="email"
                    required
                    placeholder="your@email.com"
                    class="flex-1 rounded-lg border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-zinc-600 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                />
                <Button
                    type="submit"
                    :disabled="isSubmitting || !email"
                    class="bg-indigo-600 hover:bg-indigo-700"
                >
                    <Loader2 v-if="isSubmitting" class="mr-2 h-4 w-4 animate-spin" />
                    <Mail v-else class="mr-2 h-4 w-4" />
                    Subscribe
                </Button>
            </div>
        </form>
    </div>
</template>

