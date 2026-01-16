<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { update } from '@/routes/password';
import { Form, Head } from '@inertiajs/vue3';
import { Lock, Mail, RefreshCcw } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    token: string;
    email: string;
}>();

const inputEmail = ref(props.email);
</script>

<template>
    <AuthLayout
        title="Reset Password"
        description="Choose a strong password to secure your account"
    >
        <Head title="Reset password" />

        <Form
            v-bind="update.form()"
            :transform="(data) => ({ ...data, token, email })"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
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
                            autocomplete="email"
                            v-model="inputEmail"
                            readonly
                            placeholder="name@example.com"
                            class="cursor-not-allowed border-muted-foreground/20 bg-muted/50 pl-10"
                        />
                    </div>
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label
                        for="password"
                        class="text-xs font-semibold tracking-wider text-muted-foreground/70 uppercase"
                        >New Password</Label
                    >
                    <div class="relative">
                        <Lock
                            class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-muted-foreground"
                        />
                        <Input
                            id="password"
                            type="password"
                            name="password"
                            autocomplete="new-password"
                            autofocus
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
                    >
                        Confirm New Password
                    </Label>
                    <div class="relative">
                        <Lock
                            class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-muted-foreground"
                        />
                        <Input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            autocomplete="new-password"
                            placeholder="••••••••"
                            class="pl-10"
                        />
                    </div>
                    <InputError :message="errors.password_confirmation" />
                </div>

                <Button
                    type="submit"
                    class="mt-4 w-full bg-indigo-600 font-semibold text-white transition-all hover:bg-indigo-500 hover:shadow-lg hover:shadow-indigo-500/20 active:scale-[0.98]"
                    :disabled="processing"
                    data-test="reset-password-button"
                >
                    <Spinner v-if="processing" class="mr-2" />
                    <span v-else class="flex items-center">
                        Reset Password <RefreshCcw class="ml-2 h-4 w-4" />
                    </span>
                </Button>
            </div>
        </Form>
    </AuthLayout>
</template>
