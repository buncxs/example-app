<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import PaddingContainer from '@/components/PaddingContainer.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <!--<div class="mx-auto max-w-screen-2xl">-->
    <AppShell variant="sidebar">
        <AppSidebar />
        <AppContent variant="sidebar" class="overflow-x-hidden">
            <PaddingContainer>
                <AppSidebarHeader :breadcrumbs="breadcrumbs" />
                <slot />
            </PaddingContainer>
        </AppContent>
    </AppShell>
    <!--</div>-->
</template>
