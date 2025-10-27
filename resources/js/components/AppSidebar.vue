<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, Users, UserCircle, Briefcase, Clock } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const platformNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/super-admin/dashboard',
        icon: LayoutGrid,
        permission: 'view-superadmin-dashboard',
    },
    // {
    //     title: 'Tenants',
    //     href: '/tenants',
    //     icon: Users,
    //     permission: 'view-tenants',
    // },
    {
        title: 'Tenants',
        href: '/super-admin/tenants',
        icon: UserCircle,
        permission: 'view-tenants',
    },
]

const managementNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
        permission: 'view-dashboard',
    },
    {
        title: 'Branches',
        href: '/branches',
        icon: Users,
        permission: 'view-branches',
    },
    {
        title: 'Classes',
        href: '/classes',
        icon: Users,
        permission: 'view-classes',
    },
    {
        title: 'Students',
        href: '/students',
        icon: Users,
        permission: 'view-students',
    },
];

const staffNavItems: NavItem[] = [
    {
        title: 'Staff',

        
        href: '/staff',
        icon: Briefcase,
        permission: 'view-users',
    },
    {
        title: 'Attendance',
        href: '/staff-attendance',
        icon: Clock,
        permission: 'view-users',
    },
];

const teacherNavItems: NavItem[] = [
    {
        title: 'Daily Reports',
        href: '/daily/create',
        icon: Users,
        permission: 'view-daily-reports',
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain title="Management" :items="platformNavItems" />
            <NavMain title="Branch Management" :items="managementNavItems" />
            <NavMain title="Staff Management" :items="staffNavItems" />
            <NavMain title="Teacher Management" :items="teacherNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <!-- <NavFooter :items="footerNavItems" /> -->
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
