<script setup lang="ts">
import { MoreHorizontal } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';

import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
  AlertDialogTrigger,
} from '@/components/ui/alert-dialog'

import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

// Definimos la estructura de una "Acción"
interface Action {
    label: string;
    icon?: any; // Para los iconos de lucide
    href?: string; // Si es un enlace (Editar)
    callback?: () => void; // Si es una función (Eliminar, Copiar)
    variant?: 'default' | 'danger';
    requiresConfirmation?: boolean;
}

defineProps<{
    title?: string;
    actions: Action[];
}>();


const isAlertOpen = ref(false);
const pendingAction = ref<Action | null>(null);

const handleActionClick = (action: Action) => {
    if(action.requiresConfirmation) {
        pendingAction.value = action;
        isAlertOpen.value = true;
    } else {
        action.callback?.();
    }
}

const confirmAction = () => {
    pendingAction.value?.callback?.();
    isAlertOpen.value = false;
    pendingAction.value = null;
}

</script>

<template>      
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button variant="ghost" class="h-8 w-8 p-0">
                <MoreHorizontal class="h-4 w-4" />
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
            <DropdownMenuLabel v-if="title">{{ title }}</DropdownMenuLabel>
            <DropdownMenuSeparator v-if="title" />

            <template v-for="(action, index) in actions" :key="index">
                <Link v-if="action.href" :href="action.href" class="w-full">
                    <DropdownMenuItem class="cursor-pointer">
                        <component v-if="action.icon" :is="action.icon" class="mr-2 h-4 w-4" />
                        {{ action.label }}
                    </DropdownMenuItem>
                </Link>

                <DropdownMenuItem 
                    v-else 
                    @click="handleActionClick(action)"
                    :class="['cursor-pointer', action.variant === 'danger' ? 'text-red-600' : '']"
                >
                    <component v-if="action.icon" :is="action.icon" class="mr-2 h-4 w-4" />
                    {{ action.label }}
                </DropdownMenuItem>
            </template>
        </DropdownMenuContent>
    </DropdownMenu>
    <AlertDialog :open="isAlertOpen" @update:open="isAlertOpen = $event">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>¿Estás completamente seguro?</AlertDialogTitle>
                <AlertDialogDescription>
                    Esta acción no se puede deshacer. Esto eliminará permanentemente el registro de la base de datos.
                </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel @click="pendingAction = null">Cancelar</AlertDialogCancel>
                <AlertDialogAction 
                    @click="confirmAction"
                    class="bg-red-600 hover:bg-red-700 focus:ring-red-600"
                >
                    Confirmar
                </AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>