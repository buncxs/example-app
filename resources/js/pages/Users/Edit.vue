<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { Eye, EyeOff } from 'lucide-vue-next';
import { useForm } from 'vee-validate';
import { ref, watch } from 'vue';
import * as z from 'zod';

import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter } from '@/components/ui/card';
import {
    Field,
    FieldDescription,
    FieldError,
    FieldGroup,
    FieldLabel,
} from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import users from '@/routes/users';
import { type BreadcrumbItem } from '@/types';
import { User } from '@/types/models/User';

// Recibimos al usuario como Prop
const props = defineProps<{
    user: User;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Usuarios', href: users.index().url },
    { title: 'Editar usuario', href: '#' },
];

const showPassword = ref(false);
const showConfirmPassword = ref(false);

/**
 * Esquema de validación:
 * En edición, la contraseña suele ser opcional.
 */

const passwordSchema = z
    .string()
    .min(8, 'Debe tener al menos 8 caracteres')
    .regex(/[a-zA-Z]/, 'Debe contener al menos una letra')
    .regex(/[0-9]/, 'Debe contener al menos un número');

const formSchema = toTypedSchema(
    z
        .object({
            name: z.string().min(5),
            email: z.string().email(),
            password: z.union([z.literal(''), passwordSchema]).optional(),
            // Confirmación también acepta string vacío inicialmente
            password_confirmation: z.string().optional().or(z.literal('')),
        })
        .refine((values) => values.password === values.password_confirmation, {
            message: 'Las contraseñas no coinciden',
            path: ['password_confirmation'], // El error se mostrará en este campo específico
        }),
);

const { handleSubmit, errors, defineField, setErrors, resetForm } = useForm({
    validationSchema: formSchema,
    // Pre-rellenamos el formulario con los datos del prop
    initialValues: {
        name: props.user.name,
        email: props.user.email,
        password: '',
        password_confirmation: '',
    },
});

const [name, nameProps] = defineField('name', { validateOnModelUpdate: false });
const [email, emailProps] = defineField('email', {
    validateOnModelUpdate: false,
});
const [password, passwordProps] = defineField('password', {
    validateOnModelUpdate: false,
});
const [password_confirmation, password_confirmationProps] = defineField(
    'password_confirmation',
    { validateOnModelUpdate: false },
);

const onSubmit = handleSubmit((values) => {
    // Usamos el UUID para la ruta y el método PUT/PATCH
    router.put(users.update(props.user.uuid).url, values);
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
            :title="`Editar: ${user.name}`"
            description="Modifica la información de la cuenta"
        />

        <form @submit.prevent="onSubmit">
            <Card>
                <CardContent class="space-y-4 pt-6">
                    <FieldGroup>
                        <Field>
                            <FieldLabel for="name"
                                >Nombre de usuario</FieldLabel
                            >
                            <Input
                                id="name"
                                v-model="name"
                                v-bind="nameProps"
                                :class="{ 'border-destructive': errors.name }"
                            />
                            <FieldError>{{ errors.name }}</FieldError>
                        </Field>

                        <Field>
                            <FieldLabel for="email"
                                >Correo Electrónico</FieldLabel
                            >
                            <Input
                                id="email"
                                type="email"
                                v-model="email"
                                v-bind="emailProps"
                                :class="{ 'border-destructive': errors.email }"
                            />
                            <FieldError>{{ errors.email }}</FieldError>
                        </Field>

                        <Field>
                            <FieldLabel for="password">Contraseña</FieldLabel>
                            <div class="relative">
                                <Input
                                    :type="showPassword ? 'text' : 'password'"
                                    id="password"
                                    v-model="password"
                                    v-bind="passwordProps"
                                    :class="{
                                        'border-destructive': errors.password,
                                    }"
                                />
                                <button
                                    type="button"
                                    class="absolute top-1/2 right-3 -translate-y-1/2"
                                    @click="showPassword = !showPassword"
                                >
                                    <Eye v-if="!showPassword" class="h-4 w-4" />
                                    <EyeOff v-else class="h-4 w-4" />
                                </button>
                            </div>
                            <FieldDescription
                                >(Dejar en blanco para no
                                cambiar)</FieldDescription
                            >
                            <FieldError>{{ errors.password }}</FieldError>
                        </Field>

                        <Field>
                            <FieldLabel for="password_confirmation"
                                >Confirmar contraseña</FieldLabel
                            >
                            <div class="relative">
                                <Input
                                    :type="
                                        showConfirmPassword
                                            ? 'text'
                                            : 'password'
                                    "
                                    id="password_confirmation"
                                    v-model="password_confirmation"
                                    v-bind="password_confirmationProps"
                                    :class="{
                                        'border-destructive':
                                            errors.password_confirmation,
                                    }"
                                />
                                <button
                                    type="button"
                                    class="absolute top-1/2 right-3 -translate-y-1/2"
                                    @click="
                                        showConfirmPassword =
                                            !showConfirmPassword
                                    "
                                >
                                    <Eye
                                        v-if="!showConfirmPassword"
                                        class="h-4 w-4"
                                    />
                                    <EyeOff v-else class="h-4 w-4" />
                                </button>
                            </div>
                            <FieldDescription
                                >(Dejar en blanco para no
                                cambiar)</FieldDescription
                            >
                            <FieldError>{{
                                errors.password_confirmation
                            }}</FieldError>
                        </Field>
                    </FieldGroup>
                </CardContent>
                <CardFooter class="flex justify-end gap-3 border-t py-4">
                    <Button
                        variant="ghost"
                        type="button"
                        @click="resetForm()"
                    >
                        Cancelar
                    </Button>
                    <Button type="submit" class="min-w-[120px] shadow-xl shadow-primary/20">Guardar</Button>
                </CardFooter>
            </Card>
        </form>
    </AppLayout>
</template>
