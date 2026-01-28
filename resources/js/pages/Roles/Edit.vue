<script setup lang="ts">
/* --- 1. IMPORTS --- */
import { watch } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { useForm } from 'vee-validate';
import { toTypedSchema } from '@vee-validate/zod';
import * as z from 'zod';

// UI Components
import AppLayout from '@/layouts/AppLayout.vue';
import Heading from '@/components/Heading.vue';
import PermissionSelector from '@/components/PermissionSelector.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Field, FieldError, FieldGroup, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';

// Business Logic & Types
import roles from '@/routes/roles';
import { type BreadcrumbItem } from '@/types';
import { type Role } from '@/types/models/Role';

/* --- 2. TYPES & INTERFACES --- */
interface PermissionItem {
    id: number | string;
    name: string;
    module: string;
    display_name: string;
}

/* --- 3. PROPS --- */
const props = defineProps<{
    role: Role;
    permissions: Record<string, PermissionItem[]>;
}>();

/* --- 4. VALIDATION SCHEMAS (Zod) --- */
const formSchema = toTypedSchema(
    z.object({
        name: z.string().min(4, 'El nombre debe tener al menos 4 caracteres'),
        description: z.string().min(5, 'La descripción es muy corta'),
        icon: z.string().min(3, 'El icono es requerido'),
        permission_ids: z
            .array(z.number())
            .min(1, 'Selecciona al menos un permiso')
            .default([]),
    })
);

/* --- 5. FORM SETUP (Vee-Validate) --- */
const {
    handleSubmit,
    errors,
    defineField,
    setErrors,
    setFieldValue,
    resetForm,
} = useForm({
    validationSchema: formSchema,
    initialValues: { 
        name: props.role.name, 
        description: props.role.description,
        icon: props.role.icon,
        // Mapeamos los permisos actuales del rol para el formulario
        permission_ids: props.role.permissions?.map(p => p.id) ?? [] 
    },
    validateOnMount: false,
});

// Definición de campos
const [name, nameProps] = defineField('name', { validateOnModelUpdate: false });
const [description, descriptionProps] = defineField('description', { validateOnModelUpdate: false });
const [icon, iconProps] = defineField('icon', { validateOnModelUpdate: false });
const [permission_ids] = defineField('permission_ids');

/* --- 6. STATE & CONFIG --- */
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Roles', href: roles.index().url },
    { title: 'Editar Rol', href: '#' },
];

/* --- 7. METHODS --- */
const onSubmit = handleSubmit((formValues) => {
    router.put(roles.update(props.role.id).url, formValues);
});

/* --- 8. WATCHERS --- */
// Sincronización de errores desde Laravel (Inertia)
watch(
    () => usePage().props.errors,
    (serverErrors) => {
        if (serverErrors && Object.keys(serverErrors).length > 0) {
            setErrors(serverErrors as Record<string, string>);
        }
    },
    { deep: true, immediate: true }
);
</script>

<template>
    <Head title="Editar rol" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Heading
            title="Editar rol"
            description="Modifica el nombre o los permisos correspondientes para este nivel de acceso."
        />

        <form @submit.prevent="onSubmit" class="mt-6 space-y-8 pb-10">
            <Card>
                <CardHeader class="pb-3">
                    <CardTitle
                        class="text-sm font-semibold tracking-wider text-muted-foreground uppercase"
                    >
                        Información General
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <FieldGroup>
                        <Field>
                            <FieldLabel for="name" class="text-foreground"
                                >Nombre del Rol</FieldLabel
                            >
                            <Input
                                id="name"
                                v-model="name"
                                v-bind="nameProps"
                                placeholder="Ej: Administrador Jefe"
                                :class="{
                                    'border-destructive focus-visible:ring-destructive':
                                        errors.name,
                                }"
                            />
                            <FieldError>{{ errors.name }}</FieldError>
                        </Field>
                        <Field>
                            <FieldLabel for="description" class="text-foreground">Descripción</FieldLabel>
                            <Input
                                id="description"
                                v-model="description"
                                v-bind="descriptionProps"
                                placeholder="Acceso total a todos los módulos y configuración del sistema."
                                :class="{ 'border-destructive focus-visible:ring-destructive': errors.description }"
                            />
                            <FieldError>{{ errors.description }}</FieldError>
                        </Field>
                        <Field>
                            <FieldLabel for="icon" class="text-foreground">Icono</FieldLabel>
                            <Input
                                id="icon"
                                v-model="icon"
                                v-bind="iconProps"
                                placeholder="Icono de la libreria lucide-vue-next"
                                :class="{ 'border-destructive focus-visible:ring-destructive': errors.icon }"
                            />
                            <FieldError>{{ errors.icon }}</FieldError>
                        </Field>
                    </FieldGroup>
                </CardContent>
            </Card>

            <PermissionSelector
                :permissions="permissions"
                :model-value="permission_ids ?? []"
                @update:model-value="setFieldValue('permission_ids', $event)"
            />

            <FieldError v-if="errors.permission_ids">
            
                <p class="mt-4 animate-in fade-in zoom-in rounded-lg bg-destructive/10 p-2 text-center text-sm font-bold text-destructive">
                    ⚠ {{ errors.permission_ids }}
                </p>
            </FieldError>

            <div class="flex items-center justify-end gap-3 border-t pt-6">
                <Button
                    variant="ghost"
                    type="button"
                    @click="resetForm()"
                    class="rounded-full"
                >
                    Cancelar
                </Button>
                <Button
                    type="submit"
                    class="min-w-[120px] shadow-xl shadow-primary/20"
                >
                    Guardar
                </Button>
            </div>
        </form>
    </AppLayout>
</template>
