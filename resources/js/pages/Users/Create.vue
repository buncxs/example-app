<script setup lang="ts">
// --- 1. Imports de Librerías Externas ---
import { Head, router, usePage } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';
import { ref, watch } from 'vue';
import * as z from 'zod'; // Importación estándar de Zod

// --- 2. Iconos ---
import {
    Eye,
    EyeOff,
    LayoutGrid, // Icono por defecto
    Lock,
    Settings,
    ShieldAlert,
    ShieldCheck,
    UserCog,
    Users,
} from 'lucide-vue-next';

// --- 3. Componentes de UI (Shadcn) ---
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Field, FieldError, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';

// --- 4. Componentes Propios y Layouts ---
import AppLayout from '@/layouts/AppLayout.vue';
import users from '@/routes/users';

// --- 5. Tipos ---
import Heading from '@/components/Heading.vue';
import { Checkbox } from '@/components/ui/checkbox';
import FieldGroup from '@/components/ui/field/FieldGroup.vue';
import { type BreadcrumbItem } from '@/types';

// Configuración de la línea de tiempo/navegación
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Usuarios', href: users.index().url },
    { title: 'Crear usuarios', href: users.create().url },
];

const icons = {
    ShieldAlert,
    ShieldCheck,
    UserCog,
    Eye,
    EyeOff,
    Users,
    Lock,
    Settings,
    LayoutGrid,
};

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
    z
        .object({
            name: z.string().min(5),
            email: z.string().email(),
            password: z.string().min(8),
            password_confirmation: z
                .string()
                .min(1, 'Debes confirmar la contraseña'),
            // validamos que sea un array de numeros y que contenga al menos uno
            role_ids: z
                .array(z.number())
                .min(1, 'Debes asignar al menos un rol')
                .default([]),
        })
        .refine((data) => data.password === data.password_confirmation, {
            message: 'Las contraseñas no coinciden',
            path: ['password_confirmation'],
        }),
);

interface RoleItem {
    id: number;
    name: string;
    description: string;
    icon: string;
}

const props = defineProps<{
    roles: RoleItem[];
}>();

console.log(props.roles);
/**
 * Inicialización de Vee-Validate con el esquema de Zod.
 */
const { handleSubmit, errors, defineField, setErrors, resetForm } = useForm({
    validationSchema: formSchema,
    initialValues: {
        role_ids: [],
    },
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
const [role_ids] = defineField('role_ids');

const toggleRole = (id: number) => {
    const current = [...(role_ids.value ?? [])];
    const index = current.indexOf(id);
    if (index > -1) {
        current.splice(index, 1);
    } else {
        current.push(id);
    }
    role_ids.value = current;
};

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
                    <div class="grid grid-rows-1 gap-3 md:grid-rows-2">
                        <FieldGroup>
                            <div class="grid w-full grid-cols-2 gap-4">
                                <Field>
                                    <FieldLabel for="name"
                                        >Nombre de usuario</FieldLabel
                                    >
                                    <Input
                                        id="name"
                                        v-model="name"
                                        v-bind="nameProps"
                                        placeholder="Ej: Juan Pérez"
                                        :class="{
                                            'border-destructive': errors.name,
                                        }"
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
                                        :class="{
                                            'border-destructive': errors.email,
                                        }"
                                    />
                                    <FieldError>
                                        {{ errors.email }}
                                    </FieldError>
                                </Field>
                            </div>
                        </FieldGroup>
                        <FieldGroup>
                            <div class="grid w-full grid-cols-2 gap-4">
                                <Field>
                                    <FieldLabel for="password"
                                        >Contraseña</FieldLabel
                                    >
                                    <div class="relative">
                                        <Input
                                            :type="
                                                showPassword
                                                    ? 'text'
                                                    : 'password'
                                            "
                                            id="password"
                                            v-model="password"
                                            v-bind="passwordProps"
                                            placeholder="Mínimo 8 caracteres"
                                            :class="{
                                                'border-destructive':
                                                    errors.password,
                                            }"
                                        />
                                        <button
                                            type="button"
                                            class="absolute top-1/2 right-3 -translate-y-1/2 text-muted-foreground transition-colors hover:text-foreground"
                                            @click="
                                                showPassword = !showPassword
                                            "
                                        >
                                            <Eye
                                                v-if="!showPassword"
                                                class="h-4 w-4"
                                            />
                                            <EyeOff v-else class="h-4 w-4" />
                                        </button>
                                    </div>

                                    <FieldError>
                                        {{ errors.password }}
                                    </FieldError>
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
                            </div>
                        </FieldGroup>
                    </div>
                </CardContent>
            </Card>
            <div class="mt-6 space-y-6">
                <div
                    class="flex flex-col justify-between gap-4 md:flex-row md:items-center"
                >
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-primary/10 p-2">
                            <ShieldCheck class="h-5 w-5 text-primary" />
                        </div>
                        <div>
                            <h2
                                class="text-xl font-bold tracking-tight text-foreground"
                            >
                                Asignar roles
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="my-5 space-y-4 md:col-span-2">
                <div
                    class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3"
                >
                    <div
                        v-for="role in props.roles"
                        :key="role.id"
                        @click="toggleRole(role.id)"
                        :class="[
                            'group relative flex cursor-pointer items-center justify-between gap-4 rounded-xl p-5 transition-all duration-300 select-none',
                            'border border-transparent', // Borde base invisible para evitar saltos de layout
                            role_ids?.includes(role.id)
                                ? '-translate-y-1 bg-card shadow-[0_10px_25px_-5px_rgba(0,0,0,0.1),0_8px_10px_-6px_rgba(0,0,0,0.1)] ring-2 ring-primary/20'
                                : 'border-muted/20 bg-card shadow-sm hover:-translate-y-1.5 hover:shadow-xl',
                        ]"
                    >
                        <div class="flex items-center gap-4">
                            <div
                                :class="[
                                    'flex h-14 w-14 shrink-0 items-center justify-center rounded-xl transition-all duration-300',
                                    role_ids?.includes(role.id)
                                        ? 'rotate-3 bg-primary text-primary-foreground shadow-lg shadow-primary/30'
                                        : 'bg-secondary text-muted-foreground group-hover:bg-secondary/80',
                                ]"
                            >
                                <component
                                    :is="
                                        icons[
                                            role.icon as keyof typeof icons
                                        ] || LayoutGrid
                                    "
                                    class="h-7 w-7"
                                    :stroke-width="1.75"
                                />
                            </div>

                            <div class="flex flex-col gap-1">
                                <Label
                                    :for="'role-' + role.id"
                                    class="cursor-pointer text-base font-bold tracking-tight text-foreground"
                                >
                                    {{ role.name }}
                                </Label>
                                <p
                                    class="line-clamp-2 max-w-[180px] text-xs leading-relaxed text-muted-foreground/80"
                                >
                                    {{ role.description }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-center pl-2">
                            <div
                                :class="[
                                    'flex h-6 w-6 items-center justify-center rounded-full border-2 transition-all duration-300',
                                    role_ids?.includes(role.id)
                                        ? 'scale-110 border-primary bg-primary text-primary-foreground shadow-md'
                                        : 'border-muted-foreground/30 bg-transparent',
                                ]"
                            >
                                <Checkbox
                                    :id="'role-' + role.id"
                                    :checked="role_ids?.includes(role.id)"
                                    class="sr-only"
                                    @update:checked="() => toggleRole(role.id)"
                                />
                                <svg
                                    v-if="role_ids?.includes(role.id)"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-3.5 w-3.5"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="4"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <polyline
                                        points="20 6 9 17 4 12"
                                    ></polyline>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <FieldError v-if="errors.role_ids">
                <p
                    class="mt-4 animate-in rounded-lg bg-destructive/10 p-2 text-center text-sm font-bold text-destructive fade-in zoom-in"
                >
                    ⚠ {{ errors.role_ids }}
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
