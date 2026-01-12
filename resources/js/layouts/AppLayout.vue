<script setup lang="ts">
import { Toaster } from '@/components/ui/sonner';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { watch, nextTick } from 'vue';
import { toast } from 'vue-sonner';
import 'vue-sonner/style.css';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

interface FlashMessages {
    success?: string;
    error?: string;
    [key: string]: any;
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const page = usePage();

watch(
    () => page.props.flash as unknown as FlashMessages,
    async (newFlash) => {
        // Usamos una comprobación estricta
        if (newFlash && typeof newFlash === 'object') {

            // Esperamos a que el Toaster esté montado en el DOM
            await nextTick();

            if (newFlash.success) {
                toast.success(newFlash.success);
            }
            if (newFlash.error) {
                toast.error(newFlash.error);
            }
        }
    },
    { deep: true, immediate: true },
);
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
        <Toaster rich-colors close-button :visible-toasts="3"/>
    </AppLayout>
</template>
