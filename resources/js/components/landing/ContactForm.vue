<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';

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
    <form @submit.prevent="submit" class="mx-auto max-w-2xl">
        <div v-if="showSuccess" class="mb-6 rounded-lg bg-green-500/10 border border-green-500/20 p-4 text-green-400">
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

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-zinc-300">
                    Name
                </label>
                <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    required
                    class="w-full rounded-lg border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-zinc-500 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                    placeholder="Your name"
                    aria-label="Your name"
                />
                <InputError :message="form.errors.name" />
            </div>

            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-zinc-300">
                    Email
                </label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    required
                    class="w-full rounded-lg border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-zinc-500 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                    placeholder="your@email.com"
                    aria-label="Your email address"
                />
                <InputError :message="form.errors.email" />
            </div>
        </div>

        <div class="mt-6">
            <label for="subject" class="mb-2 block text-sm font-medium text-zinc-300">
                Subject
            </label>
            <input
                id="subject"
                v-model="form.subject"
                type="text"
                required
                class="w-full rounded-lg border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-zinc-500 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                placeholder="What's this about?"
                aria-label="Message subject"
            />
            <InputError :message="form.errors.subject" />
        </div>

        <div class="mt-6">
            <label for="message" class="mb-2 block text-sm font-medium text-zinc-300">
                Message
            </label>
            <textarea
                id="message"
                v-model="form.message"
                required
                rows="6"
                class="w-full rounded-lg border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-zinc-500 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                placeholder="Tell me about your project..."
                aria-label="Your message"
            ></textarea>
            <InputError :message="form.errors.message" />
        </div>

        <button
            type="submit"
            :disabled="form.processing"
            class="mt-6 w-full rounded-xl bg-white px-8 py-4 font-bold text-black transition-all duration-300 hover:scale-105 hover:shadow-[0_0_20px_rgba(255,255,255,0.3)] disabled:opacity-50 disabled:cursor-not-allowed"
            aria-label="Send message"
        >
            <span v-if="form.processing">Sending...</span>
            <span v-else>Send Message</span>
        </button>
    </form>
</template>

