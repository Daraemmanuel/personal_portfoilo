<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { useToast } from '@/composables/useToast';
import { router } from '@inertiajs/vue3';
import { Loader2, Mail, Send } from 'lucide-vue-next';
import { ref } from 'vue';

const { success, error } = useToast();
const email = ref('');
const isSubmitting = ref(false);

const submit = async () => {
    if (!email.value) return;

    isSubmitting.value = true;
    try {
        await router.post(
            route('newsletter.subscribe'),
            {
                email: email.value,
            },
            {
                preserveScroll: true,
                onSuccess: () => {
                    success('Successfully subscribed!');
                    email.value = '';
                },
                onError: (errors) => {
                    error(
                        errors.email ||
                            'Failed to subscribe. Please try again.',
                    );
                },
                onFinish: () => {
                    isSubmitting.value = false;
                },
            },
        );
    } catch (err) {
        error('Failed to subscribe. Please try again.');
        isSubmitting.value = false;
    }
};
</script>

<template>
    <div class="flex flex-col items-center gap-6 py-8">
        <div class="text-center">
            <h3 class="mb-2 text-lg font-semibold text-foreground">
                Newsletter
            </h3>
            <p class="text-sm text-muted-foreground">
                Get the latest articles and updates delivered to your inbox.
            </p>
        </div>

        <form @submit.prevent="submit" class="w-full max-w-md">
            <div class="group relative flex items-center">
                <Mail
                    class="absolute left-4 h-4 w-4 text-muted-foreground transition-colors group-focus-within:text-indigo-500"
                />
                <input
                    v-model="email"
                    type="email"
                    required
                    placeholder="Enter your email"
                    class="h-12 w-full rounded-full border border-border bg-muted/50 pr-32 pl-11 text-sm text-foreground placeholder-muted-foreground/50 transition-all focus:border-indigo-500/50 focus:bg-background focus:ring-4 focus:ring-indigo-500/10 focus:outline-none"
                />
                <div class="absolute right-1">
                    <Button
                        type="submit"
                        :disabled="isSubmitting || !email"
                        class="h-10 rounded-full bg-indigo-600 px-6 text-xs font-bold transition-all hover:bg-indigo-500 hover:shadow-lg hover:shadow-indigo-500/20 active:scale-95"
                    >
                        <Loader2
                            v-if="isSubmitting"
                            class="h-3 w-3 animate-spin"
                        />
                        <span v-else class="flex items-center gap-2">
                            Join <Send class="h-3 w-3" />
                        </span>
                    </Button>
                </div>
            </div>
        </form>
    </div>
</template>
