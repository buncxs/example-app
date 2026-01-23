<script setup lang="ts">
/* --- IMPORTS --- */
import { Head, router } from '@inertiajs/vue3';
import { type Table } from '@tanstack/vue-table';
import { ref, watch } from 'vue';

// Iconos
import { Search } from 'lucide-vue-next';

// Layouts y Componentes de UI
import Heading from '@/components/Heading.vue';
import DataTable from '@/components/ui/datatable/DataTable.vue';
import { Input } from '@/components/ui/input';
import LinkButton from '@/components/ui/link/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';

// Configuración de Tabla y Rutas
import { columns } from '@/pages/Roles/columns';
import roles from '@/routes/roles';

// Tipado TypeScript
import { type BreadcrumbItem } from '@/types';
import type { Role } from '@/types/models/Role';
import { debounce } from 'lodash';

/* --- PROPS Y TIPOS --- */
// Recibe la colección de roles desde el controlador de Laravel
const props = defineProps<{
    items: PaginatedData<Role>;
    filters: { search?: string };
}>();

const search = ref(props.filters.search ?? '');

interface PaginatedData<T> {
    data: T[];
    links: any;
    meta: any;
}

/* --- ESTADO Y CONFIGURACIÓN --- */
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Roles',
        href: roles.index.url(),
    },
];

/* --- MÉTODOS DE FILTRADO --- */
// Búsqueda por server
const updateSearch = debounce((value: string) => {
    router.get(
        roles.index.url(),
        { search: value },
        {
            preserveState: true,
            replace: true,
            preserveScroll: true,
        },
    );
}, 300);

watch(search, (newValue) => {
    updateSearch(newValue);
});
</script>

<template>
    <Head title="Roles" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Heading title="Roles" description="Gestión de roles del sistema" />
        <div class="mt-6 flex items-center justify-between gap-4">
            <div class="relative w-full max-w-sm">
                <Input
                    placeholder="Buscar por nombre..."
                    v-model="search"
                    class="pl-10"
                />
                <div
                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-muted-foreground"
                >
                    <Search
                        class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-muted-foreground"
                    />
                </div>
            </div>

            <LinkButton
                :href="roles.create()"
                class="min-w-[100px] shadow-xl shadow-primary/20"
            >
                + Nuevo
            </LinkButton>
        </div>

        <div class="mt-4">
            <DataTable
                :columns="columns"
                :data="items.data"
                :pagination-data="{ links: items.links, meta: items.meta }"
            />
        </div>
    </AppLayout>
</template>
