<script setup lang="ts">

interface AppLogoProps {
    src?: string,
    alt?: string,
    size?: string,
}

const props = defineProps<AppLogoProps>()

</script>

<template>
    <img 
    :src="props.src ?? '/images/logo.png'" 
    :alt="props.alt ?? 'Logo del sistema'" 
    class="mx-auto filter grayscale" 
    :class="props.size ?? 'h-12 w-12'">
</template>
