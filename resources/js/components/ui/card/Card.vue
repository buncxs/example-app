<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>
border border-gray-200 bg-card shadow-sm
<template>
  <div
    data-slot="card"
    :class="
      cn(
        'bg-card text-card-foreground flex flex-col gap-6 rounded-xl border border-gray-200 p-6 shadow-xs',
        props.class,
      )
    "
  >
    <slot />
  </div>
</template>
