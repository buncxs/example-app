<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { computed } from 'vue'


defineProps({
    status: Number,
    title: String,
    message: String,
    
    // imageUrl: { type: String, default: null }
})

const iconComponent = computed(() => {
   switch (props.status) {
     case 403: return 'IconLock'; // Por ejemplo, un icono de candado
     case 404: return 'IconSearch'; // Un icono de búsqueda
     case 500: return 'IconBug';   // Un icono de bicho/error
     case 503: return 'IconSettings'; // Un icono de engranaje para mantenimiento
     default: return 'IconAlertCircle'; // Icono por defecto
   }
 });
</script>

<template>
    <Head :title="title" />

    <div class="min-h-screen flex flex-col items-center justify-center bg-background text-foreground p-4 sm:p-6 lg:p-8">
        <Card class="w-full max-w-md text-center">
            <CardHeader class="pb-4">
                <h1 class="text-6xl sm:text-7xl lg:text-8xl font-extrabold text-primary tracking-tight">
                    {{ status }}
                </h1>
                <CardTitle class="text-3xl sm:text-4xl font-bold mt-4">
                    {{ title }}
                </CardTitle>
                <CardDescription class="mt-2 text-lg text-muted-foreground">
                    {{ message }}
                </CardDescription>
            </CardHeader>
            <CardContent class="py-0">
                </CardContent>
            <CardFooter class="flex flex-col gap-3 mt-6">
                <Button as-child size="lg" class="w-full">
                    <Link href="/dashboard">Volver al Dashboard</Link>
                </Button>
                <Button v-if="status === 500" as-child variant="outline" size="lg" class="w-full">
                    <Link href="/contact">Contactar Soporte</Link>
                </Button>
            </CardFooter>
        </Card>
    </div>

</template>