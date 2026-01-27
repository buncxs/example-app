<script setup lang="ts">
/* --- IMPORTS --- */
import { ref, watch, type ComponentPublicInstance } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import { type Table } from '@tanstack/vue-table';

// Iconos
import { Search } from 'lucide-vue-next';

// Layouts y Componentes de UI
import AppLayout from '@/layouts/AppLayout.vue';
import Heading from '@/components/Heading.vue';
import DataTable from '@/components/ui/datatable/DataTable.vue';
import { Input } from '@/components/ui/input';
import LinkButton from '@/components/ui/link/LinkButton.vue';


// Configuración de Tabla y Rutas
import { columns } from '@/pages/Users/columns';
import users from '@/routes/users';

// Tipado TypeScript
import { type BreadcrumbItem } from '@/types';
import type { User } from '@/types/models/User';
import { debounce } from 'lodash';

/* --- PROPS Y TIPOS --- */
// Recibe la colección de usuarios desde el controlador de Laravel
const props = defineProps<{
    items: PaginatedData<User>;
    filters: { search: string, };
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
        title: 'Usuarios',
        href: users.index.url(),
    },
];

/* --- MÉTODOS DE FILTRADO --- */
// Búsqueda por server
const updateSearch = debounce((value: string) => {
    router.get(
        users.index.url(),
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
    <Head title="Usuarios" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Heading
            title="Usuarios"
            description="Gestión de perfiles y asignación de roles del sistema"
        />

        <div class="flex items-center justify-between gap-4 mt-6">
            <div class="relative w-full max-w-sm">
                <Input
                    placeholder="Buscar por nombre..."
                    v-model="search"
                    class="pl-10" 
                />
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-muted-foreground">
                    <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                </div>
            </div>

            <LinkButton :href="users.create()" class="min-w-[100px] shadow-xl shadow-primary/20">
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