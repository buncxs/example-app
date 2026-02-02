<script setup lang="ts">
import {
    Card,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import catalogs from '@/routes/catalogs';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Briefcase, FileText, GraduationCap, MapPin } from 'lucide-vue-next'; // Iconos de Lucide

const catalogos = [
    {
        name: 'Estados',
        description: 'Gestión de estados del país',
        route: 'catalogos.estados.index',
        icon: MapPin,
    },
    {
        name: 'Municipios',
        description: 'Municipios de Tamaulipas',
        route: 'catalogos.municipios.index',
        icon: MapPin,
    },
    {
        name: 'Puestos',
        description: 'Directores, Tutores, Cocineros...',
        route: 'catalogos.puestos.index',
        icon: Briefcase,
    },
    {
        name: 'Categorías',
        description: 'Tipos de claves presupuestales',
        route: 'catalogos.categorias.index',
        icon: FileText,
    },
    {
        name: 'Niveles Académicos',
        description: 'Preparatoria, Licenciatura...',
        route: 'catalogos.niveles.index',
        icon: GraduationCap,
    },
    // ... agregar los demás hasta llegar a los 10
];

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Catalogos', href: catalogs.index.url() },
];

</script>

<template>
    <Head title="Catálogos del Sistema" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-8">
            <h1 class="mb-8 text-3xl font-bold">Catálogos del Sistema</h1>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                <Link
                    v-for="item in catalogos"
                    :key="item.name"
                    :href="item.route"
                >
                    <Card
                        class="h-full cursor-pointer transition-colors hover:border-primary"
                    >
                        <CardHeader>
                            <div class="flex items-center gap-4">
                                <div class="rounded-lg bg-primary/10 p-2">
                                    <component
                                        :is="item.icon"
                                        class="h-6 w-6 text-primary"
                                    />
                                </div>
                                <div>
                                    <CardTitle>{{ item.name }}</CardTitle>
                                    <CardDescription>{{
                                        item.description
                                    }}</CardDescription>
                                </div>
                            </div>
                        </CardHeader>
                    </Card>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
