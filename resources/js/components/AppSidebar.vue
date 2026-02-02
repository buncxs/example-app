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
import users from '@/routes/users';
import roles from '@/routes/roles';
import catalogs from '@/routes/catalogs';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, Users, Shield, Library } from 'lucide-vue-next';
import { useAuth } from '@/composables/useAuth';
import AppLogo from './AppLogo.vue';
import { computed } from 'vue';

const page = usePage();

const { can, is } = useAuth();

const mainNavItems = computed((): NavItem[] => {
    const items: NavItem[] = [
        {
            title: 'Dashboard',
            href: dashboard(),
            icon: LayoutGrid,
        },
        {
            title: 'Catalogos',
            href: catalogs.index(),
            icon: Library,
        },

    ];

    if(can('users.view')){
        items.push({
            title: 'Usuarios',
            href: users.index(),
            icon: Users,
            isActive: page.url.startsWith('/users'),
        });
    }
    if(can('roles.view')){
        items.push({
            title: 'Roles',
            href: roles.index(),
            icon: Shield,
            isActive: page.url.startsWith('/roles'),
        });
    }

    return items;
});

const footerNavItems: NavItem[] = [
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset" class="border-x">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
