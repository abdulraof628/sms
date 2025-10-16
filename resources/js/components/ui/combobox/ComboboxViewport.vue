<script setup lang="ts">
import type { ComboboxViewportProps } from 'reka-ui'
import type { HTMLAttributes } from 'vue'
import { reactiveOmit } from '@vueuse/core'
import { ComboboxViewport, useForwardProps } from 'reka-ui'
import { cn } from '@/lib/utils'

const props = defineProps<ComboboxViewportProps & { class?: HTMLAttributes['class'] }>()

const delegatedProps = reactiveOmit(props, 'class')

const forwarded = useForwardProps(delegatedProps)
</script>

<template>
  <ComboboxViewport
    data-slot="combobox-viewport"
    v-bind="forwarded"
    :class="cn('max-h-[300px] scroll-py-1 overflow-x-hidden overflow-y-auto', props.class)"
  >
    <slot />
  </ComboboxViewport>
</template>

<style scoped>
[data-slot="combobox-viewport"] {
  scrollbar-width: thin;
  scrollbar-color: var(--color-muted-foreground, #b0b0b0) var(--color-muted, #f0f0f0);
}
[data-slot="combobox-viewport"]::-webkit-scrollbar {
  width: 8px;
  background: var(--color-muted, #f0f0f0);
}
[data-slot="combobox-viewport"]::-webkit-scrollbar-thumb {
  background: var(--color-muted-foreground, #b0b0b0);
  border-radius: 4px;
}
[data-slot="combobox-viewport"]::-webkit-scrollbar-thumb:hover {
  background: var(--color-accent, #c0c0c0);
}
</style>
