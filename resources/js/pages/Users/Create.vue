<script setup lang="ts">
// --- 1. Imports de Librerías Externas ---
import { Head } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';
import * as z from 'zod'; // Importación estándar de Zod
import { ref } from 'vue';

// --- 2. Iconos ---
import { Eye, EyeOff } from 'lucide-vue-next';

// --- 3. Componentes de UI (Shadcn) ---
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import {
    FormControl,
    FormDescription,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from '@/components/ui/form';

// --- 4. Componentes Propios y Layouts ---
import PaddingContainer from '@/components/PaddingContainer.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import users from '@/routes/users';

// --- 5. Tipos ---
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
    z
        .object({
            username: z.string().min(2),
            email: z.string().email(),
            password: z.string().min(8),
            password_confirmation: z.string(),
        })
        .refine((values) => values.password === values.password_confirmation, {
            message: 'Las contraseñas no coinciden',
            path: ['password_confirmation'], // El error se mostrará en este campo específico
        }),
);

/**
 * Inicialización de Vee-Validate con el esquema de Zod.
 */
const form = useForm({
    validationSchema: formSchema,
});

/**
 * Manejador de envío del formulario.
 * Solo se ejecuta si todas las validaciones de Zod pasan correctamente.
 */
const onSubmit = form.handleSubmit((values) => {
    console.log('Formulario enviado con éxito:', values);
    // Aquí podrías usar router.post('/users', values) de Inertia
});
</script>

<template>
    <Head title="Crear usuario" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <PaddingContainer>
            <form @submit="onSubmit">
                <div class="w-full md:max-w-xl">
                    <Card>
                        <CardContent class="space-y-4 pt-6">
                            
                            <FormField v-slot="{ componentField }" name="username">
                                <FormItem>
                                    <FormLabel>Nombre de usuario</FormLabel>
                                    <FormControl>
                                        <Input
                                            type="text"
                                            placeholder="Ingresa tu nombre"
                                            v-bind="componentField"
                                        />
                                    </FormControl>
                                    <FormMessage />
                                </FormItem>
                            </FormField>

                            <FormField v-slot="{ componentField }" name="email">
                                <FormItem>
                                    <FormLabel>Correo electrónico</FormLabel>
                                    <FormControl>
                                        <Input
                                            type="email"
                                            placeholder="Ingresa tu correo"
                                            v-bind="componentField"
                                        />
                                    </FormControl>
                                    <FormMessage />
                                </FormItem>
                            </FormField>

                            <FormField v-slot="{ componentField }" name="password">
                                <FormItem>
                                    <FormLabel>Contraseña</FormLabel>
                                    <FormControl>
                                        <div class="relative">
                                            <Input
                                                :type="showPassword ? 'text' : 'password'"
                                                placeholder=".........."
                                                v-bind="componentField"
                                                class="pr-10" 
                                            />
                                            <button
                                                type="button"
                                                class="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground transition-colors hover:text-foreground"
                                                @click="showPassword = !showPassword"
                                            >
                                                <Eye v-if="!showPassword" class="h-4 w-4" />
                                                <EyeOff v-else class="h-4 w-4" />
                                            </button>
                                        </div>
                                    </FormControl>
                                    <FormDescription>Usa una combinación de letras y números.</FormDescription>
                                    <FormMessage />
                                </FormItem>
                            </FormField>

                            <FormField v-slot="{ componentField }" name="password_confirmation">
                                <FormItem>
                                    <FormLabel>Confirmar contraseña</FormLabel>
                                    <FormControl>
                                        <div class="relative">
                                            <Input
                                                :type="showConfirmPassword ? 'text' : 'password'"
                                                placeholder=".........."
                                                v-bind="componentField"
                                                class="pr-10"
                                            />
                                            <button
                                                type="button"
                                                class="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground transition-colors hover:text-foreground"
                                                @click="showConfirmPassword = !showConfirmPassword"
                                            >
                                                <Eye v-if="!showConfirmPassword" class="h-4 w-4" />
                                                <EyeOff v-else class="h-4 w-4" />
                                            </button>
                                        </div>
                                    </FormControl>
                                    <FormMessage />
                                </FormItem>
                            </FormField>

                        </CardContent>

                        <CardFooter class="flex justify-end gap-3 border-t py-4">
                            <Button
                                variant="outline"
                                type="button"
                                @click="form.resetForm()"
                            >
                                Cancelar
                            </Button>
                            <Button type="submit">Aceptar</Button>
                        </CardFooter>
                    </Card>
                </div>
            </form>
        </PaddingContainer>
    </AppLayout>
</template>