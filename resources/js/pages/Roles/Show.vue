<script setup lang="ts">
import { computed } from 'vue'; // Importante para agrupar
import { Head, Link } from '@inertiajs/vue3';
import { 
    ShieldCheck, Edit, ArrowLeft, LayoutGrid, 
    Info, CheckCircle2, Lock 
} from 'lucide-vue-next';

import AppLayout from '@/layouts/AppLayout.vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Separator } from '@/components/ui/separator';

import roles from '@/routes/roles';
import { type Role } from '@/types/models/Role';

/* --- 1. PROPS --- */
// Nota: Inertia desenvuelve el Resource, por lo que recibes 'role' directamente
const props = defineProps<{
    role: Role; 
}>();

/* --- 2. LOGIC: Agrupar permisos por módulo --- */
const permissionsByModule = computed(() => {
    if (!props.role.permissions) return {};
    
    return props.role.permissions.reduce((acc, permission) => {
        const module = permission.module || 'Otros';
        if (!acc[module]) acc[module] = [];
        acc[module].push(permission);
        return acc;
    }, {} as Record<string, any[]>);
});

/* --- 3. CONFIG --- */
const breadcrumbs = [
    { title: 'Roles', href: roles.index().url },
    { title: 'Detalles', href: '#' },
];

const icons = { ShieldCheck, LayoutGrid, Lock };
</script>

<template>
    <Head :title="`Rol: ${role.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-8">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-primary/10 rounded-xl">
                    <component 
                        :is="icons[role.icon as keyof typeof icons] || ShieldCheck" 
                        class="h-8 w-8 text-primary" 
                    />
                </div>
                <Heading :title="role.name" :description="role.description" />
            </div>
            
            <div class="flex gap-2">
                <Button variant="outline" as-child>
                    <Link :href="roles.index().url">
                        <ArrowLeft class="mr-2 h-4 w-4" /> Volver
                    </Link>
                </Button>
                <Button as-child>
                    <Link :href="roles.edit(role.id).url">
                        <Edit class="mr-2 h-4 w-4" /> Editar
                    </Link>
                </Button>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle class="text-sm font-bold uppercase text-muted-foreground flex items-center gap-2">
                            <Info class="h-4 w-4" /> Resumen
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="space-y-1">
                            <p class="text-xs text-muted-foreground uppercase font-bold">Nombre Técnico</p>
                            <p class="font-mono text-sm">{{ role.name }}</p>
                        </div>
                        <Separator />
                        <div class="space-y-1">
                            <p class="text-xs text-muted-foreground uppercase font-bold">Permisos Asignados</p>
                            <p class="text-2xl font-black text-primary">{{ role.permissions?.length || 0 }}</p>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <div class="lg:col-span-2 space-y-8">
                <div v-for="(perms, module) in permissionsByModule" :key="module" class="space-y-4">
                    <div class="flex items-center gap-2 border-b pb-2">
                        <Badge variant="outline" class="rounded-sm px-1 font-mono uppercase text-[10px]">
                            {{ module }}
                        </Badge>
                        <h4 class="font-bold text-sm text-foreground">Gestión de {{ module }}</h4>
                    </div>
                    
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div v-for="permission in perms" :key="permission.id" 
                             class="flex items-center justify-between p-3 rounded-lg border bg-card/50">
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold capitalize">{{ permission.display_name }}</span>
                                <span class="text-[10px] font-mono text-muted-foreground">{{ permission.name }}</span>
                            </div>
                            <CheckCircle2 class="h-4 w-4 text-primary opacity-70" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>