<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';

import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const name = page.props.name;
const quote = page.props.quote;

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="relative grid min-h-screen flex-col items-center justify-center lg:max-w-none lg:grid-cols-2 lg:px-0"
    >
        <!-- Left Side: Visual/Branding -->
        <div
            class="relative hidden h-full flex-col bg-muted p-10 text-white lg:flex"
        >
            <!-- Background Image with Overlay -->
            <div
                class="absolute inset-0 bg-cover bg-center bg-no-repeat transition-transform duration-10000 hover:scale-110"
                style="background-image: url('/images/auth-bg.png')"
            />
            <div
                class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-black/20"
            />

            <!-- Content Overlay -->
            <div
                class="relative z-20 flex items-center text-xl font-bold tracking-tight"
            >
                <div
                    class="mr-3 flex h-10 w-10 items-center justify-center rounded-xl border border-white/20 bg-white/10 backdrop-blur-md"
                >
                    <AppLogoIcon class="size-6 fill-current text-white" />
                </div>
                {{ name }}
            </div>

            <div v-if="quote" class="relative z-20 mt-auto max-w-lg">
                <div
                    class="rounded-2xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur-lg"
                >
                    <blockquote class="space-y-4">
                        <p
                            class="text-xl leading-relaxed font-medium text-white/90 italic"
                        >
                            &ldquo;{{ quote.message }}&rdquo;
                        </p>
                        <footer class="flex items-center gap-3">
                            <div class="h-px w-8 bg-indigo-500" />
                            <cite
                                class="text-sm font-semibold tracking-wide text-indigo-400 uppercase not-italic"
                            >
                                {{ quote.author }}
                            </cite>
                        </footer>
                    </blockquote>
                </div>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="flex items-center justify-center bg-background p-8">
            <div
                class="mx-auto flex w-full flex-col justify-center space-y-8 sm:w-[400px]"
            >
                <div class="flex flex-col space-y-3 text-center lg:text-left">
                    <Link
                        :href="route('home')"
                        class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 font-bold text-white shadow-lg shadow-indigo-500/20 lg:hidden"
                    >
                        DE
                    </Link>
                    <h1
                        class="text-3xl font-extrabold tracking-tight text-foreground"
                        v-if="title"
                    >
                        {{ title }}
                    </h1>
                    <p
                        class="text-base leading-relaxed font-medium text-muted-foreground/80"
                        v-if="description"
                    >
                        {{ description }}
                    </p>
                </div>

                <div class="relative">
                    <slot />
                </div>

                <p
                    class="px-8 text-center text-xs leading-normal text-muted-foreground/60"
                >
                    By continuing, you agree to our
                    <a
                        href="#"
                        class="font-medium underline underline-offset-4 transition-colors hover:text-primary"
                        >Terms of Service</a
                    >
                    and
                    <a
                        href="#"
                        class="font-medium underline underline-offset-4 transition-colors hover:text-primary"
                        >Privacy Policy</a
                    >.
                </p>
            </div>
        </div>
    </div>
</template>
