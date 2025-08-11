<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, LayoutGrid, Trophy } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import LeaderBoard from './LeaderBoard.vue';
import { ref, watch } from 'vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Changelog',
        href: '',
        icon: BookOpen,
    },
];

const openPopup = ref(false);

watch(openPopup, (newValue) => {
    if (newValue) {
        // Logic to open the popup
        console.log('Popup opened');
    } else {
        // Logic to close the popup
        console.log('Popup closed');
    }
});
</script>

<template>
    <Sidebar collapsible="icon" variant="inset" style="z-index: 9999999">
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
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <!-- <LeaderBoard /> -->
            <button v-if="!openPopup" @click="openPopup = true" class="peer/menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-left outline-hidden ring-sidebar-ring transition-[width,height,padding] focus-visible:ring-2 active:bg-sidebar-accent active:text-sidebar-accent-foreground disabled:pointer-events-none disabled:opacity-50 group-has-data-[sidebar=menu-action]/menu-item:pr-8 aria-disabled:pointer-events-none aria-disabled:opacity-50 data-[active=true]:bg-sidebar-accent data-[active=true]:font-medium data-[active=true]:text-sidebar-accent-foreground data-[state=open]:hover:bg-sidebar-accent data-[state=open]:hover:text-sidebar-accent-foreground group-data-[collapsible=icon]:size-8! group-data-[collapsible=icon]:pr-2! [&>span:last-child]:truncate [&>svg]:size-4 [&>svg]:shrink-0 hover:bg-sidebar-accent h-8 text-sm text-neutral-600 hover:text-neutral-800 dark:text-neutral-300 dark:hover:text-neutral-100">
                <Trophy class="flex shrink-0"/>
                <span>Leaderboard</span>
            </button>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
        <div style="z-index: 9999999" class="p-30 fixed inset-0 flex justify-center" v-if="openPopup">
            <div class="absolute inset-x-1/3 bg-gray-800">
                <h2 class="text-white text-2xl font-bold mb-4 text-center p-4">Leaderboard</h2>
                    <button @click="openPopup = false" class="absolute top-4 right-4 text-white text-3xl hover:text-red-500 focus:outline-none" aria-label="Close">
                        &times;
                    </button>
                <div class="m-4">
                    <LeaderBoard />
                </div>
            </div>
        </div>
    </Sidebar>
    <slot />
</template>
