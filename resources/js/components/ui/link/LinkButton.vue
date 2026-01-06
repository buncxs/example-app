<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { Link } from '@inertiajs/vue3'
import { type LinkVariants, linkVariants } from '.'

interface Props {
  href: string | Record<string, any>
  variant?: LinkVariants['variant']
  size?: LinkVariants['size']
  class?: HTMLAttributes['class']
}

const props = defineProps<Props>()
</script>

<template>
  <Link
    data-slot="link-button"
    :href="props.href"
    :class="cn(linkVariants({ variant: props.variant, size: props.size }), props.class)"
  >
    <slot />
  </Link>
</template>