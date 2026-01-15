<script setup lang="ts">
// --- 1. Imports de Librerías Externas ---
import { Head, router, usePage } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';
import { watch } from 'vue';
import * as z from 'zod'; // Importación estándar de Zod

// --- 2. Componentes de UI (Shadcn) ---
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter } from '@/components/ui/card';
import {
    Field,
    FieldError,
    FieldGroup,
    FieldLabel,
} from '@/components/ui/field';
import { Input } from '@/components/ui/input';

// --- 3. Componentes Propios y Layouts ---
import AppLayout from '@/layouts/AppLayout.vue';
import permissions from '@/routes/permissions';

// --- 4. Tipos ---
import Heading from '@/components/Heading.vue';
import { type BreadcrumbItem } from '@/types';

// Configuración de la línea de tiempo/navegación
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Permisos', href: permissions.index().url },
    { title: 'Crear Permiso', href: permissions.create().url },
];

/**
 * Esquema de validación con Zod.
 * .refine() se utiliza para comparar campos entre sí (validación cruzada).
 */

const formSchema = toTypedSchema(
    z.object({
        name: z.string().min(4),
    }),
);

/**
 * Inicialización de Vee-Validate con el esquema de Zod.
 */
const { handleSubmit, errors, defineField, setErrors, resetForm } = useForm({
    validationSchema: formSchema,
});

/**
 * Definir los campos
 * Props contiene los eventos (blur, input)
 */

const [name, nameProps] = defineField('name', { validateOnModelUpdate: false });

/**
 * Manejador de envío del formulario.
 * Solo se ejecuta si todas las validaciones de Zod pasan correctamente.
 */
const onSubmit = handleSubmit((values) => {
    router.post(permissions.store().url, values);
});

// Observar los errores que vienen desde el servidor (Laravel)
watch(
    () => usePage().props.errors,
    (serverErrors) => {
        if (serverErrors && Object.keys(serverErrors).length > 0) {
            // Pasamos los errores directamente a Vee-Validate
            setErrors(serverErrors as Record<string, string>);
        }
    },
    { deep: true, immediate: true },
);
</script>

<template>
    <Head title="Crear permiso" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Heading
            title="Nuevo permiso"
            description="Configura un nuevo permiso"
        />
        <form @submit.prevent="onSubmit">
            <Card>
                <CardContent class="space-y-4 pt-6">
                    <FieldGroup>
                        <Field>
                            <FieldLabel for="name">Nombre</FieldLabel>
                            <Input
                                id="name"
                                v-model="name"
                                v-bind="nameProps"
                                placeholder="Nombre de permiso"
                                :class="{ 'border-destructive': errors.name }"
                            />
                            <FieldError> {{ errors.name }} </FieldError>
                        </Field>
                    </FieldGroup>
                </CardContent>
                <CardFooter class="flex justify-end gap-3 border-t py-4">
                    <Button
                        variant="outline"
                        type="button"
                        @click="resetForm()"
                    >
                        Cancelar
                    </Button>
                    <Button type="submit">Aceptar</Button>
                </CardFooter>
            </Card>
        </form>
    </AppLayout>
</template>
