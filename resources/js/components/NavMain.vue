<script setup lang="ts">
import { ChevronRight } from 'lucide-vue-next';
import { 
    Collapsible, 
    CollapsibleContent, 
    CollapsibleTrigger 
} from '@/components/ui/collapsible';
import {
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import { Link } from '@inertiajs/vue3';
import { type NavItem } from '@/types';

defineProps<{
    items: NavItem[];
}>();
</script>

<template>
    <SidebarMenu class="gap-1 px-2"> <template v-for="item in items" :key="item.title">
            
            <Collapsible v-if="item.items?.length" as-child :default-open="item.isActive" class="group/collapsible">
                <SidebarMenuItem>
                    <CollapsibleTrigger as-child>
                        <SidebarMenuButton 
                            :tooltip="item.title"
                            class="h-10 transition-all duration-300 hover:bg-accent/50 active:scale-[0.98] data-[state=open]:bg-accent/30"
                        >
                            <div class="flex h-7 w-7 items-center justify-center rounded-lg border bg-background shadow-sm transition-colors group-hover/collapsible:border-primary/30 group-hover/collapsible:text-primary">
                                <component :is="item.icon" class="h-4 w-4" />
                            </div>
                            <span class="ml-2 font-medium text-sidebar-foreground/80 group-data-[state=open]:text-foreground">{{ item.title }}</span>
                            <ChevronRight class="ml-auto h-4 w-4 opacity-50 transition-transform duration-300 group-data-[state=open]/collapsible:rotate-90" />
                        </SidebarMenuButton>
                    </CollapsibleTrigger>
                    
                    <CollapsibleContent class="overflow-hidden transition-all data-[state=closed]:animate-collapsible-up data-[state=open]:animate-collapsible-down">
                        <SidebarMenuSub class="ml-5 border-l-2 border-primary/10 py-1 pl-2 transition-colors group-data-[state=open]/collapsible:border-primary/30">
                            <SidebarMenuSubItem v-for="subItem in item.items" :key="subItem.title">
                                <SidebarMenuSubButton as-child :is-active="subItem.isActive" class="h-9 transition-colors hover:text-primary">
                                    <Link :href="subItem.href" class="flex w-full items-center gap-2">
                                        <div v-if="subItem.isActive" class="h-1.5 w-1.5 rounded-full bg-primary shadow-[0_0_8px_rgba(var(--primary),0.6)]" />
                                        <div v-else class="h-1 w-1 rounded-full bg-muted-foreground/30" />
                                        <span :class="[subItem.isActive ? 'font-semibold text-foreground' : 'text-muted-foreground']">
                                            {{ subItem.title }}
                                        </span>
                                    </Link>
                                </SidebarMenuSubButton>
                            </SidebarMenuSubItem>
                        </SidebarMenuSub>
                    </CollapsibleContent>
                </SidebarMenuItem>
            </Collapsible>

            <SidebarMenuItem v-else>
                <SidebarMenuButton 
                    as-child 
                    :is-active="item.isActive" 
                    :tooltip="item.title"
                    class="h-10 transition-all duration-300 hover:bg-accent/50 active:scale-[0.98]"
                >
                    <Link :href="item.href">
                        <div :class="[
                            'flex h-7 w-7 items-center justify-center rounded-lg border shadow-sm transition-all',
                            item.isActive ? 'border-primary/50 bg-primary/10 text-primary' : 'bg-background border-border'
                        ]">
                            <component :is="item.icon" class="h-4 w-4" />
                        </div>
                        <span :class="['ml-2 font-medium', item.isActive ? 'text-foreground' : 'text-sidebar-foreground/80']">
                            {{ item.title }}
                        </span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
            
        </template>
    </SidebarMenu>
</template>