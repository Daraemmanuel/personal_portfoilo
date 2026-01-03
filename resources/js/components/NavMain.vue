<script setup lang="ts">
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { urlIsActive } from '@/lib/utils';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';

defineProps<{
    items: NavItem[];
}>();

const page = usePage();
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel
            class="text-[10px] font-semibold tracking-widest text-zinc-500 uppercase"
            >Management</SidebarGroupLabel
        >
        <SidebarMenu class="mt-2 space-y-1">
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <SidebarMenuButton
                    as-child
                    :is-active="urlIsActive(item.href, page.url)"
                    :tooltip="item.title"
                    class="group relative flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200"
                    :class="[
                        urlIsActive(item.href, page.url)
                            ? 'bg-indigo-500/10 text-indigo-400 shadow-[inset_0_0_0_1px_rgba(99,102,241,0.2)]'
                            : 'text-zinc-400 hover:bg-white/5 hover:text-white',
                    ]"
                >
                    <Link :href="item.href">
                        <component
                            :is="item.icon"
                            class="h-4 w-4 transition-colors"
                            :class="
                                urlIsActive(item.href, page.url)
                                    ? 'text-indigo-400'
                                    : 'text-zinc-500 group-hover:text-white'
                            "
                        />
                        <span>{{ item.title }}</span>

                        <!-- Active Indicator Dot -->
                        <div
                            v-if="urlIsActive(item.href, page.url)"
                            class="absolute right-3 h-1.5 w-1.5 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.8)]"
                        ></div>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
