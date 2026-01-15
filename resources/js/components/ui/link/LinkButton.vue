<script setup lang="ts">
import { cn } from '@/lib/utils';
import { InertiaLinkProps, Link } from '@inertiajs/vue3';
import { type LinkVariants, linkVariants } from '.';

interface Props extends InertiaLinkProps {
    variant?: LinkVariants['variant'];
    size?: LinkVariants['size'];
    class?: string;
    disabled?: boolean;
}

const props = defineProps<Props>();
</script>

<template>
    <Link
        data-slot="link-button"
        :href="props.disabled ? '' : props.href"
        :class="
            cn(
                linkVariants({ variant: props.variant, size: props.size }),
                props.class,
                props.disabled &&
                    'pointer-events-none cursor-not-allowed opacity-50',
            )
        "
    >
        <slot />
    </Link>
</template>
