<script setup lang="ts">
/* --- 1. IMPORTS --- */
import { ref, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { Search } from 'lucide-vue-next';
import { useAuth } from '@/composables/useAuth';

// Layouts & UI Components
import AppLayout from '@/layouts/AppLayout.vue';
import Heading from '@/components/Heading.vue';
import DataTable from '@/components/ui/datatable/DataTable.vue';
import { Input } from '@/components/ui/input';
import LinkButton from '@/components/ui/link/LinkButton.vue';

// Business Logic & Types
import { getColumns } from '@/pages/Roles/columns';
import roles from '@/routes/roles';
import { type BreadcrumbItem } from '@/types';
import type { Role } from '@/types/models/Role';

const { can } = useAuth();
const columns = getColumns(can);

/* --- 2. TYPES & INTERFACES --- */
interface PaginatedData<T> {
    data: T[];
    links: any;
    meta: any;
}

/* --- 3. PROPS --- */
const props = defineProps<{
    items: PaginatedData<Role>;
    filters: { search?: string };
}>();

/* --- 4. STATE (Reactividad) --- */
const search = ref(props.filters.search ?? '');

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Roles', href: roles.index.url() },
];

/* --- 5. LOGIC & METHODS --- */
/**
 * Petición al servidor para filtrar roles
 */
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
}, 350);

/* --- 6. WATCHERS --- */
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
                v-if="can('roles.create')"
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
