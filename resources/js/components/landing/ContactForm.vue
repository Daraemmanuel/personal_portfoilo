<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    name: '',
    email: '',
    subject: '',
    message: '',
    honeypot: '', // Honeypot field for spam protection
});

const showSuccess = ref(false);

const submit = () => {
    form.post(route('contact.store'), {
        preserveScroll: true,
        onSuccess: () => {
            showSuccess.value = true;
            form.reset();
            setTimeout(() => {
                showSuccess.value = false;
            }, 5000);
        },
    });
};
</script>

<template>
    <form @submit.prevent="submit" class="mx-auto max-w-2xl px-4 sm:px-0">
        <div
            v-if="showSuccess"
            class="mb-6 rounded-lg border border-green-500/20 bg-green-500/10 p-3 text-sm font-medium text-green-500 sm:p-4 sm:text-base"
        >
            Thank you for your message! I'll get back to you soon.
        </div>

        <!-- Honeypot field (hidden from users) -->
        <input
            v-model="form.honeypot"
            type="text"
            name="website"
            autocomplete="off"
            tabindex="-1"
            class="hidden"
            aria-hidden="true"
        />

        <div class="grid gap-4 sm:gap-6 sm:grid-cols-2">
            <div>
                <label
                    for="name"
                    class="mb-2 block text-sm font-semibold tracking-wider text-muted-foreground uppercase"
                >
                    Name
                </label>
                <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    required
                    class="w-full rounded-lg border border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 transition-all focus:border-indigo-500 focus:bg-background focus:ring-4 focus:ring-indigo-500/10 focus:outline-none"
                    placeholder="Your name"
                    aria-label="Your name"
                />
                <InputError :message="form.errors.name" />
            </div>

            <div>
                <label
                    for="email"
                    class="mb-2 block text-sm font-semibold tracking-wider text-muted-foreground uppercase"
                >
                    Email
                </label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    required
                    class="w-full rounded-lg border border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 transition-all focus:border-indigo-500 focus:bg-background focus:ring-4 focus:ring-indigo-500/10 focus:outline-none"
                    placeholder="your@email.com"
                    aria-label="Your email address"
                />
                <InputError :message="form.errors.email" />
            </div>
        </div>

        <div class="mt-6">
            <label
                for="subject"
                class="mb-2 block text-sm font-semibold tracking-wider text-muted-foreground uppercase"
            >
                Subject
            </label>
            <input
                id="subject"
                v-model="form.subject"
                type="text"
                required
                class="w-full rounded-lg border border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 transition-all focus:border-indigo-500 focus:bg-background focus:ring-4 focus:ring-indigo-500/10 focus:outline-none"
                placeholder="What's this about?"
                aria-label="Message subject"
            />
            <InputError :message="form.errors.subject" />
        </div>

        <div class="mt-6">
            <label
                for="message"
                class="mb-2 block text-sm font-semibold tracking-wider text-muted-foreground uppercase"
            >
                Message
            </label>
            <textarea
                id="message"
                v-model="form.message"
                required
                rows="6"
                class="w-full rounded-lg border border-border bg-muted/50 px-4 py-3 text-foreground placeholder-muted-foreground/50 transition-all focus:border-indigo-500 focus:bg-background focus:ring-4 focus:ring-indigo-500/10 focus:outline-none"
                placeholder="Tell me about your project..."
                aria-label="Your message"
            ></textarea>
            <InputError :message="form.errors.message" />
        </div>

        <button
            type="submit"
            :disabled="form.processing"
            class="mt-6 w-full rounded-xl bg-primary px-6 py-3 text-sm font-bold text-primary-foreground transition-all duration-300 hover:shadow-xl hover:shadow-primary/20 active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-50 sm:mt-8 sm:px-8 sm:py-4 sm:text-base"
            aria-label="Send message"
        >
            <span v-if="form.processing">Sending...</span>
            <span v-else>Send Message</span>
        </button>
    </form>
</template>
