<script setup lang="ts">
/* --- IMPORTS --- */
import { Head } from '@inertiajs/vue3';
import { type Table } from '@tanstack/vue-table';
import { ref, type ComponentPublicInstance } from 'vue';

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

/* --- PROPS Y TIPOS --- */
// Recibe la colección de roles desde el controlador de Laravel
defineProps<{
    items: PaginatedData<Role>;
}>();

interface PaginatedData<T> {
    data: T[];
    links: any;
    meta: any;
}


// Interfaz para exponer la API interna de TanStack Table desde el componente DataTable
interface DataTableExpose {
    table: Table<any>;
}

/* --- ESTADO Y CONFIGURACIÓN --- */
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Roles',
        href: roles.index.url(),
    },
];

// Referencia al componente DataTable para gestionar filtros y estado global de la tabla
const dataTableRef = ref<(ComponentPublicInstance & DataTableExpose) | null>(
    null,
);

/* --- MÉTODOS DE FILTRADO --- */
// Obtiene el valor actual del filtro de búsqueda
const getSearchValue = () => {
    return (
        (dataTableRef.value?.table
            .getColumn('name')
            ?.getFilterValue() as string) ?? ''
    );
};

// Aplica el nuevo valor de búsqueda a la columna 'name'
const setSearchValue = (value: string | number) => {
    dataTableRef.value?.table.getColumn('name')?.setFilterValue(value);
};
</script>

<template>
    <Head title="Roles" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Heading title="Roles" description="Gestión de roles del sistema" />
        <div class="mt-6 flex items-center justify-between gap-4">
            <div class="relative w-full max-w-sm">
                <Input
                    placeholder="Buscar por nombre..."
                    :model-value="getSearchValue()"
                    @update:model-value="setSearchValue"
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

            <LinkButton :href="roles.create()" class="min-w-[100px] shadow-xl shadow-primary/20"> + Nuevo </LinkButton>
        </div>

        <div class="mt-4">
            <DataTable
                ref="dataTableRef"
                :columns="columns"
                :data="items.data"
                :pagination-data="{ links: items.links, meta: items.meta }"
            />
        </div>
    </AppLayout>
</template>
