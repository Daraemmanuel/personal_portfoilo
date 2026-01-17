<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Sheet, SheetContent, SheetTrigger } from '@/components/ui/sheet';
import { useAppearance } from '@/composables/useAppearance';
import { Link } from '@inertiajs/vue3';
import { Menu, Monitor, Moon, Sun } from 'lucide-vue-next';
import { ref } from 'vue';
import SearchBar from './SearchBar.vue';

defineProps<{
    canLogin?: boolean;
}>();

const { appearance, updateAppearance } = useAppearance();
const isMobileMenuOpen = ref(false);
const isMobile = ref(false);

// Check if mobile on mount and resize
if (typeof window !== 'undefined') {
    const checkMobile = () => {
        isMobile.value = window.innerWidth < 1024;
        if (!isMobile.value) {
            isMobileMenuOpen.value = false;
        }
    };

    checkMobile();
    window.addEventListener('resize', checkMobile);
}
</script>

<template>
    <nav
        class="fixed top-0 z-50 w-full border-b border-border bg-background/50 backdrop-blur-md"
    >
        <div
            class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6"
        >
            <a
                href="/"
                class="bg-gradient-to-r from-indigo-500 to-cyan-500 bg-clip-text text-lg font-bold tracking-tighter text-transparent sm:text-xl"
            >
                DE.
            </a>

            <!-- Desktop Navigation -->
            <div
                class="hidden gap-6 text-sm font-medium text-muted-foreground lg:flex lg:gap-8"
            >
                <a
                    href="/#about"
                    class="transition-colors hover:text-foreground"
                >
                    About
                </a>
                <a
                    href="/#work"
                    class="transition-colors hover:text-foreground"
                >
                    Work
                </a>
                <a
                    href="/#skills"
                    class="transition-colors hover:text-foreground"
                >
                    Skills
                </a>
                <Link
                    :href="route('articles.index')"
                    class="transition-colors hover:text-foreground"
                >
                    Articles
                </Link>
                <a
                    href="/#contact"
                    class="transition-colors hover:text-foreground"
                >
                    Contact
                </a>
            </div>

            <div class="flex items-center gap-2 sm:gap-4">
                <!-- Search Bar - Hidden on mobile -->
                <div class="hidden md:block">
                    <SearchBar />
                </div>

                <!-- Dark Mode Toggle - Smaller on mobile -->
                <div
                    class="flex items-center gap-0.5 rounded-lg bg-muted p-0.5 shadow-sm sm:gap-1 sm:p-1"
                    title="Theme toggle"
                >
                    <button
                        @click="updateAppearance('light')"
                        :class="[
                            'rounded-md p-1 transition-all duration-200 sm:p-1.5',
                            appearance === 'light'
                                ? 'scale-105 bg-background text-foreground shadow-sm'
                                : 'text-muted-foreground hover:scale-110 hover:text-foreground',
                        ]"
                        title="Light mode"
                        aria-label="Switch to light mode"
                    >
                        <Sun class="h-3.5 w-3.5 sm:h-4 sm:w-4" />
                    </button>
                    <button
                        @click="updateAppearance('dark')"
                        :class="[
                            'rounded-md p-1 transition-all duration-200 sm:p-1.5',
                            appearance === 'dark'
                                ? 'scale-105 bg-background text-foreground shadow-sm'
                                : 'text-muted-foreground hover:scale-110 hover:text-foreground',
                        ]"
                        title="Dark mode"
                        aria-label="Switch to dark mode"
                    >
                        <Moon class="h-3.5 w-3.5 sm:h-4 sm:w-4" />
                    </button>
                    <button
                        @click="updateAppearance('system')"
                        :class="[
                            'rounded-md p-1 transition-all duration-200 sm:p-1.5',
                            appearance === 'system'
                                ? 'scale-105 bg-background text-foreground shadow-sm'
                                : 'text-muted-foreground hover:scale-110 hover:text-foreground',
                        ]"
                        title="System preference"
                        aria-label="Use system theme"
                    >
                        <Monitor class="h-3.5 w-3.5 sm:h-4 sm:w-4" />
                    </button>
                </div>

                <!-- Hire Me Button - Hidden on mobile -->
                <a
                    href="mailto:immanuelatwork@gmail.com"
                    class="hidden rounded-full bg-primary px-3 py-1.5 text-xs font-semibold text-primary-foreground transition-colors hover:bg-primary/90 sm:block sm:px-4 sm:py-2 sm:text-sm"
                >
                    Hire Me
                </a>

                <!-- Mobile Menu Button - Only visible on mobile/tablet -->
                <Sheet v-if="isMobile" v-model:open="isMobileMenuOpen">
                    <SheetTrigger as-child>
                        <Button variant="ghost" size="icon" class="h-9 w-9">
                            <Menu class="h-5 w-5" />
                        </Button>
                    </SheetTrigger>
                    <SheetContent side="right" class="w-[300px] p-6">
                        <div class="flex flex-col gap-6">
                            <div class="flex items-center justify-between">
                                <a
                                    href="/"
                                    class="bg-gradient-to-r from-indigo-500 to-cyan-500 bg-clip-text text-xl font-bold tracking-tighter text-transparent"
                                    @click="isMobileMenuOpen = false"
                                >
                                    DE.
                                </a>
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    @click="isMobileMenuOpen = false"
                                >
                                    <X class="h-5 w-5" />
                                </Button>
                            </div>

                            <!-- Mobile Search -->
                            <div class="md:hidden">
                                <SearchBar />
                            </div>

                            <!-- Mobile Dark Mode Toggle -->
                            <div
                                class="flex items-center justify-center gap-1 rounded-lg bg-muted p-1 shadow-sm"
                            >
                                <button
                                    @click="updateAppearance('light')"
                                    :class="[
                                        'rounded-md p-2 transition-all duration-200',
                                        appearance === 'light'
                                            ? 'scale-105 bg-background text-foreground shadow-sm'
                                            : 'text-muted-foreground hover:scale-110 hover:text-foreground',
                                    ]"
                                    title="Light mode"
                                    aria-label="Switch to light mode"
                                >
                                    <Sun class="h-4 w-4" />
                                </button>
                                <button
                                    @click="updateAppearance('dark')"
                                    :class="[
                                        'rounded-md p-2 transition-all duration-200',
                                        appearance === 'dark'
                                            ? 'scale-105 bg-background text-foreground shadow-sm'
                                            : 'text-muted-foreground hover:scale-110 hover:text-foreground',
                                    ]"
                                    title="Dark mode"
                                    aria-label="Switch to dark mode"
                                >
                                    <Moon class="h-4 w-4" />
                                </button>
                                <button
                                    @click="updateAppearance('system')"
                                    :class="[
                                        'rounded-md p-2 transition-all duration-200',
                                        appearance === 'system'
                                            ? 'scale-105 bg-background text-foreground shadow-sm'
                                            : 'text-muted-foreground hover:scale-110 hover:text-foreground',
                                    ]"
                                    title="System preference"
                                    aria-label="Use system theme"
                                >
                                    <Monitor class="h-4 w-4" />
                                </button>
                            </div>

                            <!-- Mobile Navigation Links -->
                            <nav class="flex flex-col gap-4">
                                <a
                                    href="/#about"
                                    class="text-base font-medium text-muted-foreground transition-colors hover:text-foreground"
                                    @click="isMobileMenuOpen = false"
                                >
                                    About
                                </a>
                                <a
                                    href="/#work"
                                    class="text-base font-medium text-muted-foreground transition-colors hover:text-foreground"
                                    @click="isMobileMenuOpen = false"
                                >
                                    Work
                                </a>
                                <a
                                    href="/#skills"
                                    class="text-base font-medium text-muted-foreground transition-colors hover:text-foreground"
                                    @click="isMobileMenuOpen = false"
                                >
                                    Skills
                                </a>
                                <Link
                                    :href="route('articles.index')"
                                    class="text-base font-medium text-muted-foreground transition-colors hover:text-foreground"
                                    @click="isMobileMenuOpen = false"
                                >
                                    Articles
                                </Link>
                                <a
                                    href="/#contact"
                                    class="text-base font-medium text-muted-foreground transition-colors hover:text-foreground"
                                    @click="isMobileMenuOpen = false"
                                >
                                    Contact
                                </a>
                            </nav>

                            <!-- Mobile Hire Me Button -->
                            <a
                                href="mailto:immanuelatwork@gmail.com"
                                class="rounded-full bg-primary px-6 py-3 text-center text-sm font-semibold text-primary-foreground transition-colors hover:bg-primary/90"
                                @click="isMobileMenuOpen = false"
                            >
                                Hire Me
                            </a>
                        </div>
                    </SheetContent>
                </Sheet>
            </div>
        </div>
    </nav>
</template>
