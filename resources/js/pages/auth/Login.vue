<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';
import { Form, Head } from '@inertiajs/vue3';
import { ArrowRight, Lock, Mail } from 'lucide-vue-next';

defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}>();
</script>

<template>
    <AuthBase
        title="Welcome Back"
        description="Enter your credentials to access your account"
    >
        <Head title="Log in" />

        <div
            v-if="status"
            class="mb-4 rounded-lg bg-green-500/10 p-3 text-center text-sm font-medium text-green-500"
        >
            {{ status }}
        </div>

        <Form
            v-bind="store.form()"
            :reset-on-success="['password']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-5">
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
                            required
                            autofocus
                            :tabindex="1"
                            autocomplete="email"
                            placeholder="name@example.com"
                            class="pl-10"
                        />
                    </div>
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label
                            for="password"
                            class="text-xs font-semibold tracking-wider text-muted-foreground/70 uppercase"
                            >Password</Label
                        >
                        <TextLink
                            v-if="canResetPassword"
                            :href="request()"
                            class="text-xs font-medium text-indigo-400 decoration-indigo-400/30 hover:text-indigo-300"
                            :tabindex="5"
                        >
                            Forgot password?
                        </TextLink>
                    </div>
                    <div class="relative">
                        <Lock
                            class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-muted-foreground"
                        />
                        <Input
                            id="password"
                            type="password"
                            name="password"
                            required
                            :tabindex="2"
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="pl-10"
                        />
                    </div>
                    <InputError :message="errors.password" />
                </div>

                <div class="flex items-center justify-between">
                    <Label
                        for="remember"
                        class="group flex cursor-pointer items-center space-x-2.5"
                    >
                        <Checkbox
                            id="remember"
                            name="remember"
                            :tabindex="3"
                            class="border-muted-foreground/30 data-[state=checked]:bg-indigo-500"
                        />
                        <span
                            class="text-sm text-muted-foreground transition-colors group-hover:text-foreground"
                            >Remember me for 30 days</span
                        >
                    </Label>
                </div>

                <Button
                    type="submit"
                    class="mt-2 w-full bg-indigo-600 font-semibold text-white transition-all hover:bg-indigo-500 hover:shadow-lg hover:shadow-indigo-500/20 active:scale-[0.98]"
                    :tabindex="4"
                    :disabled="processing"
                    data-test="login-button"
                >
                    <Spinner v-if="processing" class="mr-2" />
                    <span v-else class="flex items-center">
                        Sign In
                        <ArrowRight
                            class="ml-2 h-4 w-4 transition-transform group-hover:translate-x-1"
                        />
                    </span>
                </Button>
            </div>

            <div
                class="mt-4 text-center text-sm text-muted-foreground"
                v-if="canRegister"
            >
                Don't have an account?
                <TextLink
                    :href="register()"
                    :tabindex="5"
                    class="ml-1 font-semibold text-indigo-400 hover:text-indigo-300"
                    >Create one now</TextLink
                >
            </div>
        </Form>
    </AuthBase>
</template>
