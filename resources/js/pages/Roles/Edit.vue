<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';
import { watch } from 'vue';
import * as z from 'zod';

import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter } from '@/components/ui/card';
import {
    Field,
    FieldError,
    FieldGroup,
    FieldLabel,
} from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import roles from '@/routes/roles';
import { type BreadcrumbItem } from '@/types';
import { Role } from '@/types/models/Role';

// Recibimos al usuario como Prop
const props = defineProps<{
    role: Role;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Roles', href: roles.index().url },
    { title: 'Editar rol', href: '#' },
];

/**
 * Esquema de validación:
 */

const formSchema = toTypedSchema(
    z.object({
        name: z.string().min(4),
    }),
);

const { handleSubmit, errors, defineField, setErrors, resetForm } = useForm({
    validationSchema: formSchema,
    // Pre-rellenamos el formulario con los datos del prop
    initialValues: {
        name: props.role.name,
    },
});

const [name, nameProps] = defineField('name', { validateOnModelUpdate: false });

const onSubmit = handleSubmit((values) => {
    // Usamos el UUID para la ruta y el método PUT/PATCH
    router.put(roles.update(props.role.uuid).url, values);
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
    <Head title="Editar usuario" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Heading
            :title="`Editar: ${role.name}`"
            description="Modifica la información del rol"
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
                                :class="{ 'border-destructive': errors.name }"
                            />
                            <FieldError>{{ errors.name }}</FieldError>
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
                    <Button type="submit">Guardar Cambios</Button>
                </CardFooter>
            </Card>
        </form>
    </AppLayout>
</template>
