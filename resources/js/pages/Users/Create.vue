<script setup lang="ts">
// --- 1. Imports de Librerías Externas ---
import { Head, router, usePage } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';
import { ref, watch } from 'vue';
import * as z from 'zod'; // Importación estándar de Zod

// --- 2. Iconos ---
import { Eye, EyeOff } from 'lucide-vue-next';

// --- 3. Componentes de UI (Shadcn) ---
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter } from '@/components/ui/card';
import {
    Field,
    FieldError,
    FieldGroup,
    FieldLabel,
} from '@/components/ui/field';
import { Input } from '@/components/ui/input';

// --- 4. Componentes Propios y Layouts ---
import AppLayout from '@/layouts/AppLayout.vue';
import users from '@/routes/users';

// --- 5. Tipos ---
import Heading from '@/components/Heading.vue';
import { type BreadcrumbItem } from '@/types';

// Configuración de la línea de tiempo/navegación
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Usuarios', href: users.index().url },
    { title: 'Crear usuarios', href: users.create().url },
];

/**
 * Lógica de visibilidad para las contraseñas
 */
const showPassword = ref(false);
const showConfirmPassword = ref(false);

/**
 * Esquema de validación con Zod.
 * .refine() se utiliza para comparar campos entre sí (validación cruzada).
 */

const formSchema = toTypedSchema(
    z.object({
        name: z.string().min(5),
        email: z.string().email(),
        password: z.string().min(8),
        password_confirmation: z.string().min(1, 'Debes confirmar la contraseña'),
    }).refine((data) => data.password === data.password_confirmation, {
        message: "Las contraseñas no coinciden",
        path: ["password_confirmation"],
    })
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

/**
 * Manejador de envío del formulario.
 * Solo se ejecuta si todas las validaciones de Zod pasan correctamente.
 */
const onSubmit = handleSubmit((values) => {
    router.post(users.store().url, values);
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
    <Head title="Crear usuario" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Heading
            title="Nuevo usuario"
            description="Registra una cuenta para dar acceso a la plataforma"
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
                                placeholder="Ej: Juan Pérez"
                                :class="{ 'border-destructive': errors.name }"
                            />
                            <FieldError> {{ errors.name }} </FieldError>
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
                                placeholder="usuario@ejemplo.com"
                                :class="{ 'border-destructive': errors.email }"
                            />
                            <FieldError> {{ errors.email }} </FieldError>
                        </Field>
                        <Field>
                            <FieldLabel for="password">Contraseña</FieldLabel>
                            <div class="relative">
                                <Input
                                    :type="showPassword ? 'text' : 'password'"
                                    id="password"
                                    v-model="password"
                                    v-bind="passwordProps"
                                    placeholder="Mínimo 8 caracteres"
                                    :class="{
                                        'border-destructive': errors.password,
                                    }"
                                />
                                <button
                                    type="button"
                                    class="absolute top-1/2 right-3 -translate-y-1/2 text-muted-foreground transition-colors hover:text-foreground"
                                    @click="showPassword = !showPassword"
                                >
                                    <Eye v-if="!showPassword" class="h-4 w-4" />
                                    <EyeOff v-else class="h-4 w-4" />
                                </button>
                            </div>

                            <FieldError> {{ errors.password }} </FieldError>
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
                                    placeholder="Confirmar contraseña"
                                    :class="{
                                        'border-destructive':
                                            errors.password_confirmation,
                                    }"
                                />
                                <button
                                    type="button"
                                    class="absolute top-1/2 right-3 -translate-y-1/2 text-muted-foreground transition-colors hover:text-foreground"
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
                            <FieldError>
                                {{ errors.password_confirmation }}
                            </FieldError>
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
                    <Button type="submit" class="min-w-[120px] shadow-xl shadow-primary/20">Aceptar</Button>
                </CardFooter>
            </Card>
            
        </form>
    </AppLayout>
</template>
