<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthLayout from '@/layouts/AuthLayout.vue';

import { Form, Head } from '@inertiajs/vue3';
import { ArrowLeft, Mail, Send } from 'lucide-vue-next';

defineProps<{
    status?: string;
}>();
</script>

<template>
    <AuthLayout
        title="Forgot Password?"
        description="No worries! Enter your email and we'll send you a link to reset your password."
    >
        <Head title="Forgot password" />

        <div
            v-if="status"
            class="mb-6 rounded-lg border border-green-500/20 bg-green-500/10 p-3 text-center text-sm font-medium text-green-500"
        >
            {{ status }}
        </div>

        <div class="space-y-6">
            <Form
                :action="route('password.email')"
                method="post"
                v-slot="{ errors, processing }"
            >
                <div class="grid gap-2">
                    <Label
                        for="email"
                        class="text-xs font-semibold tracking-wider text-muted-foreground/70 uppercase"
                        >Email Address</Label
                    >
                    <div class="relative">
                        <Mail
                            class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-muted-foreground"
                        />
                        <Input
                            id="email"
                            type="email"
                            name="email"
                            autocomplete="off"
                            autofocus
                            placeholder="name@example.com"
                            class="pl-10"
                        />
                    </div>
                    <InputError :message="errors.email" />
                </div>

                <Button
                    class="mt-6 w-full bg-indigo-600 font-semibold text-white transition-all hover:bg-indigo-500 hover:shadow-lg hover:shadow-indigo-500/20 active:scale-[0.98]"
                    :disabled="processing"
                    data-test="email-password-reset-link-button"
                >
                    <Spinner v-if="processing" class="mr-2" />
                    <span v-else class="flex items-center">
                        Send Reset Link <Send class="ml-2 h-4 w-4" />
                    </span>
                </Button>
            </Form>

            <div class="pt-2 text-center">
                <TextLink
                    :href="route('login')"
                    class="group inline-flex items-center text-sm font-medium text-muted-foreground hover:text-foreground"
                >
                    <ArrowLeft
                        class="mr-2 h-4 w-4 transition-transform group-hover:-translate-x-1"
                    />
                    Back to Sign In
                </TextLink>
            </div>
        </div>
    </AuthLayout>
</template>
