<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { 
    ArrowLeft, Calendar, Edit, Eye, EyeOff, Fingerprint, 
    LayoutGrid, Mail, Settings, ShieldAlert, ShieldCheck, UserCog, Users, Lock 
} from 'lucide-vue-next';

// Layouts y UI
import AppLayout from '@/layouts/AppLayout.vue';
import Heading from '@/components/Heading.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';

// Rutas JS (Asegúrate de que este archivo exporte correctamente)
import users from '@/routes/users';
import { type BreadcrumbItem } from '@/types';
import { type User } from '@/types/models/User';

const props = defineProps<{
    user: User;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Usuarios', href: users.index().url },
    { title: 'Detalles', href: '#' },
];

const icons = {
    ShieldAlert, ShieldCheck, UserCog, Eye,
    EyeOff, Users, Lock, Settings, LayoutGrid,
};
</script>

<template>
    <Head :title="`Detalles - ${user.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
            <Heading :title="user.name" :description="`Registrado desde ${user.created_at}`" />
            <div class="flex gap-2">
                <Button variant="outline" as-child>
                    <Link :href="users.index().url">
                        <ArrowLeft class="mr-2 h-4 w-4" /> Volver
                    </Link>
                </Button>
                <Button as-child>
                    <Link :href="users.edit(user.uuid).url">
                        <Edit class="mr-2 h-4 w-4" /> Editar
                    </Link>
                </Button>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <div class="md:col-span-2 space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle class="text-lg flex items-center gap-2">
                            <UserCog class="h-5 w-5 text-primary" />
                            Información General
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <p class="text-sm text-muted-foreground italic">Nombre</p>
                                <p class="font-bold text-lg">{{ user.name }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-sm text-muted-foreground italic">Email</p>
                                <div class="flex items-center gap-2">
                                    <Mail class="h-4 w-4 text-muted-foreground" />
                                    <span>{{ user.email }}</span>
                                </div>
                            </div>
                        </div>

                        <Separator />

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <p class="text-sm text-muted-foreground italic">Identificador Único (UUID)</p>
                                <div class="flex items-center gap-2 font-mono text-xs bg-muted p-2 rounded">
                                    <Fingerprint class="h-3 w-3" />
                                    {{ user.uuid }}
                                </div>
                            </div>
                            <div class="space-y-1">
                                <p class="text-sm text-muted-foreground italic">Cuenta creada el</p>
                                <div class="flex items-center gap-2 text-sm">
                                    <Calendar class="h-4 w-4 text-muted-foreground" />
                                    {{ user.created_at }}
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <div class="space-y-4">
                    <h3 class="font-bold text-xl flex items-center gap-2">
                        <ShieldCheck class="h-6 w-6 text-primary" />
                        Roles del Usuario
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div v-for="role in user.roles" :key="role.id" 
                            class="flex items-center gap-4 p-4 rounded-xl border bg-card shadow-sm">
                            <div class="h-10 w-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                                <component :is="icons[role.icon as keyof typeof icons] || LayoutGrid" class="h-5 w-5" />
                            </div>
                            <div>
                                <p class="font-bold text-sm">{{ role.name }}</p>
                                <Badge variant="secondary" class="text-[10px] h-4">ASIGNADO</Badge>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>