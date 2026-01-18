<script setup lang="ts">
import { ref, computed } from 'vue';
import { ChevronLeft, ChevronRight, ShieldCheck } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';

/**
 * Definición de la estructura de un permiso individual.
 */
interface PermissionItem {
    uuid: string;
    name: string;
    module: string;
    display_name: string;
}

/**
 * Props del componente:
 * - permissions: Objeto donde la llave es el nombre del módulo y el valor es un array de permisos.
 * - modelValue: Array de UUIDs seleccionados, vinculado mediante v-model desde el padre.
 */
const props = defineProps<{
    permissions: Record<string, PermissionItem[]>;
    modelValue: string[]; 
}>();

/**
 * Emite actualizaciones al padre para mantener la reactividad del v-model.
 */
const emit = defineEmits(['update:modelValue']);

/* --- LÓGICA DE NAVEGACIÓN Y PAGINACIÓN --- */
const currentPage = ref(0);
const itemsPerPage = 3; // Cantidad de tarjetas visibles por página
const slideDirection = ref('slide-next'); // Controla la dirección de la animación (izquierda/derecha)

// Extrae los nombres de los módulos (claves del objeto)
const moduleKeys = computed(() => Object.keys(props.permissions));

// Calcula el total de páginas basado en la cantidad de módulos
const totalPages = computed(() => Math.ceil(moduleKeys.value.length / itemsPerPage));

// Filtra los módulos que deben mostrarse en la página actual
const visibleModuleKeys = computed(() => {
    const start = currentPage.value * itemsPerPage;
    return moduleKeys.value.slice(start, start + itemsPerPage);
});

/**
 * Avanza a la siguiente página y ajusta la dirección de la animación.
 */
const nextPage = () => {
    if (currentPage.value < totalPages.value - 1) {
        slideDirection.value = 'slide-next';
        currentPage.value++;
    }
};

/**
 * Retrocede a la página anterior y ajusta la dirección de la animación.
 */
const prevPage = () => {
    if (currentPage.value > 0) {
        slideDirection.value = 'slide-prev';
        currentPage.value--;
    }
};

/* --- GESTIÓN DE SELECCIÓN DE PERMISOS --- */

/**
 * Agrega o elimina un permiso individual del array modelValue.
 * @param uuid Identificador único del permiso.
 */
const togglePermission = (uuid: string) => {
    const currentIds = [...props.modelValue];
    const index = currentIds.indexOf(uuid);
    
    if (index > -1) {
        currentIds.splice(index, 1);
    } else {
        currentIds.push(uuid);
    }
    
    emit('update:modelValue', currentIds);
};

/**
 * Selecciona o deselecciona todos los permisos de un módulo específico.
 * @param modulePermissions Array de permisos pertenecientes al módulo.
 */
const toggleModule = (modulePermissions: PermissionItem[]) => {
    const ids = modulePermissions.map((p) => p.uuid);
    const currentIds = [...props.modelValue];
    
    // Verifica si todos los permisos del módulo ya están seleccionados
    const allSelected = ids.every((id) => currentIds.includes(id));
    
    let nextValue: string[];
    if (allSelected) {
        // Quita todos los permisos de este módulo
        nextValue = currentIds.filter((id) => !ids.includes(id));
    } else {
        // Agrega los que falten evitando duplicados
        nextValue = [...new Set([...currentIds, ...ids])];
    }
    
    emit('update:modelValue', nextValue);
};

/**
 * Determina si todos los permisos de un módulo están marcados.
 * Útil para cambiar el estilo del botón "Todo/Deseleccionar".
 */
const isModuleFull = (moduleName: string) => {
    const moduleItems = props.permissions[moduleName];
    return moduleItems.every(p => props.modelValue.includes(p.uuid));
};
</script>

<template>
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-primary/10 rounded-lg">
                    <ShieldCheck class="w-5 h-5 text-primary" />
                </div>
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-foreground">Permisos del Sistema</h2>
                    <p class="text-xs text-muted-foreground font-medium uppercase tracking-wide">
                        Página {{ currentPage + 1 }} de {{ totalPages }}
                    </p>
                </div>
            </div>

            <div class="flex items-center border bg-background rounded-full p-1 shadow-sm">
                <Button 
                    type="button" variant="ghost" size="icon" class="h-8 w-8 rounded-full"
                    :disabled="currentPage === 0" @click="prevPage"
                >
                    <ChevronLeft class="h-4 w-4" />
                </Button>
                
                <div class="flex gap-1.5 px-3">
                    <div 
                        v-for="i in totalPages" :key="i"
                        class="h-1.5 rounded-full transition-all duration-300"
                        :class="i - 1 === currentPage ? 'w-6 bg-primary' : 'w-1.5 bg-muted-foreground/30'"
                    ></div>
                </div>

                <Button 
                    type="button" variant="ghost" size="icon" class="h-8 w-8 rounded-full"
                    :disabled="currentPage >= totalPages - 1" @click="nextPage"
                >
                    <ChevronRight class="h-4 w-4" />
                </Button>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-xl border border-dashed border-muted-foreground/20 p-4">
            <transition :name="slideDirection" mode="out-in">
                <div :key="currentPage" class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <Card
                        v-for="moduleName in visibleModuleKeys"
                        :key="moduleName"
                        class="flex flex-col border-none shadow-md bg-card overflow-hidden h-full transition-all hover:ring-2 hover:ring-primary/20"
                    >
                        <div class="flex items-center justify-between border-b bg-muted/20 px-4 py-3">
                            <span class="text-xs font-bold tracking-widest text-foreground uppercase">
                                {{ moduleName }}
                            </span>
                            <Button
                                type="button" variant="ghost" size="sm"
                                class="h-7 px-2 text-[10px] font-bold"
                                :class="isModuleFull(moduleName) ? 'text-destructive' : 'text-primary'"
                                @click="toggleModule(permissions[moduleName])"
                            >
                                {{ isModuleFull(moduleName) ? 'Deseleccionar' : 'Todo' }}
                            </Button>
                        </div>

                        <CardContent class="flex-1 space-y-4 p-5">
                            <div
                                v-for="permission in permissions[moduleName]"
                                :key="permission.uuid"
                                class="flex items-center gap-3 group cursor-pointer"
                                @click="togglePermission(permission.uuid)"
                            >
                                <div 
                                    class="w-5 h-5 rounded border-2 flex items-center justify-center transition-all duration-200"
                                    :class="modelValue.includes(permission.uuid) ? 'bg-primary border-primary' : 'border-muted-foreground/30 bg-background'"
                                >
                                    <div v-if="modelValue.includes(permission.uuid)" class="w-1.5 h-1.5 bg-white rounded-full"></div>
                                </div>
                                <label class="cursor-pointer text-sm font-semibold text-muted-foreground group-hover:text-foreground transition-colors select-none">
                                    {{ permission.display_name }}
                                </label>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </transition>
        </div>
    </div>
</template>

<style scoped>
/* ANIMACIONES DE DESLIZAMIENTO (Slide)
   Se utiliza cubic-bezier para un movimiento más fluido y orgánico.
*/
.slide-next-enter-active, .slide-next-leave-active,
.slide-prev-enter-active, .slide-prev-leave-active {
    transition: all 0.1s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Estado cuando vas a la derecha (Siguiente) */
.slide-next-enter-from { opacity: 0; transform: translateX(60px); }
.slide-next-leave-to { opacity: 0; transform: translateX(-60px); }

/* Estado cuando vas a la izquierda (Anterior) */
.slide-prev-enter-from { opacity: 0; transform: translateX(-60px); }
.slide-prev-leave-to { opacity: 0; transform: translateX(60px); }
</style>