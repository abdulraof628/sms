<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { Button } from '@/components/ui/button'
import TooltipWrapper from '@/components/TooltipWrapper.vue'
import { type ButtonVariants } from '@/components/ui/button'
import { type PrimitiveProps } from 'reka-ui'

interface Props extends PrimitiveProps {
  variant?: ButtonVariants['variant']
  size?: ButtonVariants['size']
  class?: HTMLAttributes['class']
  tooltip?: string
  tooltipSide?: 'top' | 'right' | 'bottom' | 'left'
  tooltipAlign?: 'start' | 'center' | 'end'
}

const props = withDefaults(defineProps<Props>(), {
  as: 'button',
  tooltipSide: 'top',
  tooltipAlign: 'center'
})
</script>

<template>
  <TooltipWrapper 
    v-if="tooltip" 
    :text="tooltip" 
    :side="tooltipSide" 
    :align="tooltipAlign"
  >
    <Button
      :variant="variant"
      :size="size"
      :class="class"
      :as="as"
      :as-child="asChild"
    >
      <slot />
    </Button>
  </TooltipWrapper>
  
  <Button
    v-else
    :variant="variant"
    :size="size"
    :class="class"
    :as="as"
    :as-child="asChild"
  >
    <slot />
  </Button>
</template>
