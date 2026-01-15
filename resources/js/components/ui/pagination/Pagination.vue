<script setup lang="ts">
import LinkButton from '../link/LinkButton.vue';
import { computed } from 'vue';

interface Props {
    links: {
        prev: string | null;
        next: string | null;
    };
    meta: {
        current_page: number;
        last_page: number;
        from: number;
        to: number;
        total: number;
    };
}

const props = defineProps<Props>();

// Lógica de visibilidad
const hasPagination = computed(() => props.meta.last_page > 1);
</script>

<template>
    <div v-if="hasPagination" class="flex items-center justify-between border-t bg-gray-50 px-4 py-2">
        <div class="flex gap-2">
            <LinkButton 
                :href="links.prev ?? '#'" 
                :disabled="!links.prev" 
                size="sm" 
                preserve-scroll
            >
                <
            </LinkButton>
            
            <LinkButton 
                :href="links.next ?? '#'" 
                :disabled="!links.next" 
                size="sm" 
                preserve-scroll
            >
                >
            </LinkButton>
        </div>

        <div class="hidden sm:block text-sm text-gray-600">
            Mostrando {{ meta.from }} a {{ meta.to }} de {{ meta.total }} resultados
        </div>

        <span class="text-sm text-gray-600">
            Página {{ meta.current_page }} de {{ meta.last_page }}
        </span>
    </div>
</template>