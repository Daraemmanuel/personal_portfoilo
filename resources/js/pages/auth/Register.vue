<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';

import { Form, Head } from '@inertiajs/vue3';
import { Lock, Mail, User, UserPlus } from 'lucide-vue-next';
</script>

<template>
    <AuthBase
        title="Create Account"
        description="Join our professional network today"
    >
        <Head title="Register" />

        <Form
            :action="route('register.store')"
            method="post"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-5">
                <div class="grid gap-2">
                    <Label
                        for="name"
                        class="text-xs font-semibold tracking-wider text-muted-foreground/70 uppercase"
                        >Full Name</Label
                    >
                    <div class="relative">
                        <User
                            class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-muted-foreground"
                        />
                        <Input
                            id="name"
                            type="text"
                            required
                            autofocus
                            :tabindex="1"
                            autocomplete="name"
                            name="name"
                            placeholder="John Doe"
                            class="pl-10"
                        />
                    </div>
                    <InputError :message="errors.name" />
                </div>

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
                            required
                            :tabindex="2"
                            autocomplete="email"
                            name="email"
                            placeholder="name@example.com"
                            class="pl-10"
                        />
                    </div>
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="grid gap-2">
                        <Label
                            for="password"
                            class="text-xs font-semibold tracking-wider text-muted-foreground/70 uppercase"
                            >Password</Label
                        >
                        <div class="relative">
                            <Lock
                                class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-muted-foreground"
                            />
                            <Input
                                id="password"
                                type="password"
                                required
                                :tabindex="3"
                                autocomplete="new-password"
                                name="password"
                                placeholder="••••••••"
                                class="pl-10"
                            />
                        </div>
                        <InputError :message="errors.password" />
                    </div>

                    <div class="grid gap-2">
                        <Label
                            for="password_confirmation"
                            class="text-xs font-semibold tracking-wider text-muted-foreground/70 uppercase"
                            >Confirm</Label
                        >
                        <div class="relative">
                            <Lock
                                class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-muted-foreground"
                            />
                            <Input
                                id="password_confirmation"
                                type="password"
                                required
                                :tabindex="4"
                                autocomplete="new-password"
                                name="password_confirmation"
                                placeholder="••••••••"
                                class="pl-10"
                            />
                        </div>
                        <InputError :message="errors.password_confirmation" />
                    </div>
                </div>

                <Button
                    type="submit"
                    class="mt-2 w-full bg-indigo-600 font-semibold text-white transition-all hover:bg-indigo-500 hover:shadow-lg hover:shadow-indigo-500/20 active:scale-[0.98]"
                    tabindex="5"
                    :disabled="processing"
                    data-test="register-user-button"
                >
                    <Spinner v-if="processing" class="mr-2" />
                    <span v-else class="flex items-center">
                        Create Account <UserPlus class="ml-2 h-4 w-4" />
                    </span>
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                Already have an account?
                <TextLink
                    :href="route('login')"
                    class="ml-1 font-semibold text-indigo-400 hover:text-indigo-300"
                    :tabindex="6"
                    >Sign In</TextLink
                >
            </div>
        </Form>
    </AuthBase>
</template>
