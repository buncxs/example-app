<script setup lang="ts">
import DataTable from '@/components/ui/datatable/DataTable.vue';
import { Input } from '@/components/ui/input';
import LinkButton from '@/components/ui/link/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { columns } from '@/pages/Users/columns';
import users from '@/routes/users';
import { type BreadcrumbItem } from '@/types';
import type { User } from '@/types/models/User';
import { Head } from '@inertiajs/vue3';
import { Table } from '@tanstack/vue-table';
import { ComponentPublicInstance, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Usuarios',
        href: '/users',
    },
];

// Recibe los datos enviados desde el controlador de Laravel
defineProps<{
    data: User[];
}>();

// Define la interfaz para acceder a la instancia de la tabla de TanStack (necesaria para TypeScript)
interface DataTableExpose {
    table: Table<any>;
}

// Crea una referencia al componente hijo para poder manipular su estado interno (como filtros)
const dataTableRef = ref<(ComponentPublicInstance & DataTableExpose) | null>(
    null,
);
</script>

<template>
    <Head title="Usuarios" />
    <AppLayout :breadcrumbs="breadcrumbs">
            <div class="flex justify-between">
                <Input
                    class="max-w-sm"
                    placeholder="Buscar nombre..."
                    :model-value="
                        // Lee el valor del filtro directamente desde la columna 'name' de la tabla
                        (dataTableRef?.table
                            .getColumn('name')
                            ?.getFilterValue() as string) ?? ''
                    "
                    @update:model-value="
                        // Actualiza el filtro de la columna cada vez que el usuario escribe
                        dataTableRef?.table
                            .getColumn('name')
                            ?.setFilterValue($event)
                    "
                />
                <LinkButton :href="users.create()">+ Nuevo</LinkButton>
            </div>

            <DataTable
                ref="dataTableRef"
                :columns="columns"
                :data="data"
                class="mt-3"
            />
    </AppLayout>
</template>
