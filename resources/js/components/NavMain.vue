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
import { computed } from 'vue';

defineProps<{
    items: NavItem[];
}>();

const page = usePage();
const pendingCommentsCount = computed(() => (page.props as any).pendingCommentsCount || 0);
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel
            class="text-[10px] font-semibold tracking-widest text-muted-foreground uppercase"
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
                            ? 'bg-primary/10 text-primary'
                            : 'text-muted-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground',
                    ]"
                >
                    <Link
                        :href="item.href"
                        class="flex w-full items-center gap-3"
                    >
                        <component
                            :is="item.icon"
                            class="h-4 w-4 transition-colors"
                            :class="
                                urlIsActive(item.href, page.url)
                                    ? 'text-primary'
                                    : 'text-muted-foreground group-hover:text-sidebar-accent-foreground'
                            "
                        />
                        <span>{{ item.title }}</span>

                        <!-- Pending Comments Badge -->
                        <span
                            v-if="item.title === 'Comments' && pendingCommentsCount > 0"
                            class="ml-auto rounded-full bg-yellow-500 px-2 py-0.5 text-xs font-bold text-white"
                        >
                            {{ pendingCommentsCount }}
                        </span>

                        <!-- Active Indicator Dot -->
                        <div
                            v-if="urlIsActive(item.href, page.url)"
                            class="absolute right-3 h-1.5 w-1.5 rounded-full bg-primary shadow-sm"
                        ></div>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
