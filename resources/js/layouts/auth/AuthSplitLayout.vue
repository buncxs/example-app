<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import AppLogo4T from '@/components/AppLogo4T.vue';
import { home } from '@/routes';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const name = page.props.name;
const quote = page.props.quote;

defineProps<{
    title?: string;
    description?: string;
    logo?: string;
}>();
</script>

<template>
    <div
        class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-[60%_40%] lg:px-0"
    >
        <div
            class="relative hidden h-full flex-col bg-muted p-10 text-white lg:flex dark:border-r"
        >
            <div class="absolute inset-0 bg-zinc-900" />

            <div class="flex flex-col justify-center items-center h-full">
                <img src="/img/logo3.png" alt="" class="w-90 -translate-y-20 z-20">
                <h1 class="display-7 -translate-y-10 z-20">Tamaulipas</h1>
                <h2 class="display-8 -translate-y-10 z-20">Gobierno del Estado</h2>
            </div>
        </div>
        <div class="lg:p-8">
            <div
                class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]"
            >
                <div class="flex flex-col space-y-2 text-center">
                    <AppLogo4T size="h-20 w-25" src="/img/logo4.png"/>
                    <h1 class="text-xl font-medium tracking-tight" v-if="title">
                        {{ title }}
                    </h1>
                    <p class="text-sm text-muted-foreground" v-if="description">
                        {{ description }}
                    </p>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>
