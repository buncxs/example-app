<script setup lang="ts">
// --- 1. Imports de Librerías Externas ---
import { Head, router, usePage } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';
import { watch } from 'vue';
import * as z from 'zod';

// --- 2. Componentes de UI (Shadcn) ---
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import {
    Field,
    FieldError,
    FieldGroup,
    FieldLabel,
} from '@/components/ui/field';
import { Input } from '@/components/ui/input';

// --- 3. Componentes Propios y Layouts ---
import AppLayout from '@/layouts/AppLayout.vue';
import roles from '@/routes/roles';
import Heading from '@/components/Heading.vue';

// --- 4. Tipos ---
import { type BreadcrumbItem } from '@/types';

interface PermissionItem {
    uuid: string;
    name: string;
    module: string;
    display_name: string;
}

const props = defineProps<{ 
    permissions: Record<string, PermissionItem[]> 
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Roles', href: roles.index().url },
    { title: 'Crear Roles', href: roles.create().url },
];

// --- 5. Validación con Zod ---
const formSchema = toTypedSchema(
    z.object({
        name: z.string().min(4, 'El nombre debe tener al menos 4 caracteres'),
        permission_ids: z.array(z.string()).min(1, 'Selecciona al menos un permiso').default([]),
    }),
);

const {
    handleSubmit,
    errors,
    defineField,
    setErrors,
    resetForm,
    setFieldValue,
    values,
} = useForm({
    validationSchema: formSchema,
    initialValues: {
        name: '',
        permission_ids: [],
    },
});

const [name, nameProps] = defineField('name', { validateOnModelUpdate: false });

// --- 6. Lógica de Selección de Permisos ---

const togglePermission = (uuid: string) => {
    const current = [...values.permission_ids || []];
    const index = current.indexOf(uuid);
    if (index > -1) {
        current.splice(index, 1);
    } else {
        current.push(uuid);
    }
    setFieldValue('permission_ids', current);
};

const toggleModule = (modulePermissions: PermissionItem[]) => {
    const ids = modulePermissions.map(p => p.uuid);
    // Usamos el operador de encadenamiento opcional ?. y fallback a []
    const currentIds = values.permission_ids || [];
    const allSelected = ids.every(id => currentIds.includes(id));

    if (allSelected) {
        setFieldValue('permission_ids', currentIds.filter(id => !ids.includes(id)));
    } else {
        const newSelection = [...new Set([...currentIds, ...ids])];
        setFieldValue('permission_ids', newSelection);
    }
};

// --- 7. Envío de Formulario ---
const onSubmit = handleSubmit((formValues) => {
    router.post(roles.store().url, formValues);
});

// Sync de errores del servidor
watch(
    () => usePage().props.errors,
    (serverErrors) => {
        if (serverErrors && Object.keys(serverErrors).length > 0) {
            setErrors(serverErrors as Record<string, string>);
        }
    },
    { deep: true, immediate: true },
);
</script>

<template>
    <Head title="Crear rol" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Heading
            title="Nuevo rol"
            description="Configura un nombre y asigna los permisos correspondientes para este nivel de acceso."
        />

        <form @submit.prevent="onSubmit" class="mt-6 space-y-8">
            <Card>
                <CardHeader>
                    <CardTitle class="text-base">Información General</CardTitle>
                </CardHeader>
                <CardContent>
                    <FieldGroup>
                        <Field>
                            <FieldLabel for="name">Nombre del Rol</FieldLabel>
                            <Input
                                id="name"
                                v-model="name"
                                v-bind="nameProps"
                                placeholder="Ej: Administrador de Almacén"
                                :class="{ 'border-destructive': errors.name }"
                            />
                            <FieldError>{{ errors.name }}</FieldError>
                        </Field>
                    </FieldGroup>
                </CardContent>
            </Card>

            <div class="space-y-4">
                <div class="flex items-center justify-between px-1">
                    <div>
                        <h2 class="text-lg font-semibold tracking-tight">Permisos del Sistema</h2>
                        <p class="text-sm text-muted-foreground">Agrupados por módulos funcionales</p>
                    </div>
                    <div v-if="errors.permission_ids" class="text-sm font-medium text-destructive bg-destructive/10 px-3 py-1 rounded-md border border-destructive/20">
                        {{ errors.permission_ids }}
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <Card
                        v-for="(group, moduleName) in permissions"
                        :key="moduleName"
                        class="overflow-hidden flex flex-col"
                    >
                        <div class="flex items-center justify-between border-b bg-muted/40 px-4 py-2">
                            <span class="text-xs font-bold uppercase tracking-widest text-foreground">
                                {{ moduleName }}
                            </span>
                            <Button 
                                type="button" 
                                variant="ghost" 
                                size="sm" 
                                class="h-7 px-2 text-[10px] hover:bg-primary/10 hover:text-primary"
                                @click="toggleModule(group)"
                            >
                                Seleccionar módulo
                            </Button>
                        </div>

                        <CardContent class="p-4 space-y-3 flex-1">
                            <div
                                v-for="permission in group"
                                :key="permission.uuid"
                                class="flex items-center space-x-3"
                            >
                                <Checkbox
                                    :id="permission.uuid"
                                    :checked="values.permission_ids?.includes(permission.uuid)"
                                    @update:checked="togglePermission(permission.uuid)"
                                />
                                <label
                                    :for="permission.uuid"
                                    class="cursor-pointer text-sm font-medium leading-none select-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                >
                                    {{ permission.display_name }}
                                </label>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t pt-6">
                <Button
                    variant="ghost"
                    type="button"
                    @click="resetForm()"
                >
                    Reiniciar
                </Button>
                <Button type="submit" class="min-w-[120px]">
                    Guardar Rol
                </Button>
            </div>
        </form>
    </AppLayout>
</template>