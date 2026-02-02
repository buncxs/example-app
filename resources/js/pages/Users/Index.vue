<script setup lang="ts">
/* --- 1. IMPORTS (Externos primero, internos después) --- */
import { useAuth } from '@/composables/useAuth';
import { Head, router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { Search } from 'lucide-vue-next';
import { ref, watch } from 'vue';

// Layouts & UI Components
import Heading from '@/components/Heading.vue';
import DataTable from '@/components/ui/datatable/DataTable.vue';
import { Input } from '@/components/ui/input';
import LinkButton from '@/components/ui/link/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';

// Business Logic & Types
import { getColumns } from '@/pages/Users/columns';
import users from '@/routes/users';
import { type BreadcrumbItem } from '@/types';
import type { User } from '@/types/models/User';

const { can } = useAuth();
const columns = getColumns(can);

/* --- 2. TYPES & INTERFACES --- */
interface PaginatedData<T> {
    data: T[];
    links: any;
    meta: any;
}

/* --- 3. PROPS & EMITS --- */
const props = defineProps<{
    items: PaginatedData<User>;
    filters: { search: string };
}>();

/* --- 4. STATE (Reactividad) --- */
const search = ref(props.filters.search ?? '');

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Usuarios', href: users.index.url() },
];

/* --- 5. LOGIC & METHODS --- */
/**
 * Realiza la petición al servidor con debounce para evitar sobrecarga
 */
const performSearch = debounce((value: string) => {
    router.get(
        users.index.url(),
        { search: value },
        {
            preserveState: true,
            replace: true,
            preserveScroll: true,
        },
    );
}, 350); // Un poco más de tiempo suele ser más cómodo

/* --- 6. WATCHERS --- */
watch(search, (newValue) => {
    performSearch(newValue);
});

console.log(props.items.data)
</script>

<template>
    <Head title="Usuarios" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Heading
            title="Usuarios"
            description="Gestión de perfiles y asignación de roles del sistema"
        />

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
                v-if="can('users.create')"
                :href="users.create()"
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
