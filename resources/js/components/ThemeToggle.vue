<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { useAppearance } from '@/composables/useAppearance';
import { Monitor, Moon, Sun } from 'lucide-vue-next';

const { appearance, updateAppearance } = useAppearance();
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button variant="ghost" size="icon" class="h-9 w-9">
                <Sun
                    class="h-[1.2rem] w-[1.2rem] scale-100 rotate-0 transition-all dark:scale-0 dark:-rotate-90"
                />
                <Moon
                    class="absolute h-[1.2rem] w-[1.2rem] scale-0 rotate-90 transition-all dark:scale-100 dark:rotate-0"
                />
                <span class="sr-only">Toggle theme</span>
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
            <DropdownMenuItem @click="updateAppearance('light')">
                <Sun class="mr-2 h-4 w-4" />
                <span>Light</span>
                <span v-if="appearance === 'light'" class="ml-auto">✓</span>
            </DropdownMenuItem>
            <DropdownMenuItem @click="updateAppearance('dark')">
                <Moon class="mr-2 h-4 w-4" />
                <span>Dark</span>
                <span v-if="appearance === 'dark'" class="ml-auto">✓</span>
            </DropdownMenuItem>
            <DropdownMenuItem @click="updateAppearance('system')">
                <Monitor class="mr-2 h-4 w-4" />
                <span>System</span>
                <span v-if="appearance === 'system'" class="ml-auto">✓</span>
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
