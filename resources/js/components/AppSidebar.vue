<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import {
    BarChart3,
    Briefcase,
    Clock,
    Code,
    FileText,
    Layers,
    LayoutGrid,
    Mail,
    MessageSquare,
    Star,
} from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Projects',
        href: route('admin.projects.index'),
        icon: Layers,
    },
    {
        title: 'Skills',
        href: route('admin.skills.index'),
        icon: Code,
    },
    {
        title: 'Experiences',
        href: route('admin.experiences.index'),
        icon: Clock,
    },
    {
        title: 'Testimonials',
        href: route('admin.testimonials.index'),
        icon: Star,
    },
    {
        title: 'Articles',
        href: route('admin.articles.index'),
        icon: FileText,
    },
    {
        title: 'Contact Messages',
        href: route('admin.contact-messages.index'),
        icon: Mail,
    },
    {
        title: 'Comments',
        href: route('admin.comments.index'),
        icon: MessageSquare,
    },
    {
        title: 'Analytics',
        href: route('admin.analytics.index'),
        icon: BarChart3,
    },
    {
        title: 'CV',
        href: route('admin.cv.index'),
        icon: Briefcase,
    },
];

const footerNavItems: NavItem[] = [
    // {
    //     title: 'Github Repo',
    //     href: 'https://github.com/laravel/vue-starter-kit',
    //     icon: Folder,
    // },
    // {
    //     title: 'Documentation',
    //     href: 'https://laravel.com/docs/starter-kits#vue',
    //     icon: BookOpen,
    // },
];
</script>

<template>
    <Sidebar
        collapsible="icon"
        variant="inset"
        class="border-r border-white/5 bg-zinc-950"
    >
        <SidebarHeader class="border-b border-white/5 px-4 pt-6 pb-4">
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton
                        size="lg"
                        as-child
                        class="transition-colors hover:bg-white/5"
                    >
                        <Link :href="dashboard()">
                            <AppLogo class="h-8 w-auto text-indigo-500" />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent class="px-2 pt-6">
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter class="border-t border-white/5 p-2">
            <NavFooter :items="footerNavItems" />
            <NavUser class="mt-4 rounded-xl bg-white/5 p-2" />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
