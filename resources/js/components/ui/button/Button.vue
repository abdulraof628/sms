<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { Primitive, type PrimitiveProps } from 'reka-ui'
import { cn } from '@/lib/utils'
import { type ButtonVariants, buttonVariants } from '.'
import {
  Tooltip,
  TooltipContent,
  TooltipProvider,
  TooltipTrigger,
} from '@/components/ui/tooltip'

interface Props extends PrimitiveProps {
  variant?: ButtonVariants['variant']
  size?: ButtonVariants['size']
  class?: HTMLAttributes['class']
  tooltip?: string
  tooltipSide?: 'top' | 'right' | 'bottom' | 'left'
  tooltipAlign?: 'start' | 'center' | 'end'
  disableTooltipWhenAsChild?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  as: 'button',
  tooltipSide: 'top',
  tooltipAlign: 'center',
  disableTooltipWhenAsChild: false
})
</script>

<template>
  <!-- When tooltip is present and not disabled for asChild -->
  <TooltipProvider v-if="tooltip && !(asChild && disableTooltipWhenAsChild)">
    <Tooltip>
      <TooltipTrigger as-child>
        <Primitive
          data-slot="button"
          :as="as"
          :as-child="asChild"
          :class="cn(buttonVariants({ variant, size }), props.class)"
        >
          <slot />
        </Primitive>
      </TooltipTrigger>
      <TooltipContent :side="tooltipSide" :align="tooltipAlign">
        <p>{{ tooltip }}</p>
      </TooltipContent>
    </Tooltip>
  </TooltipProvider>
  
  <!-- No tooltip or tooltip disabled for asChild -->
  <Primitive
    v-else
    data-slot="button"
    :as="as"
    :as-child="asChild"
    :class="cn(buttonVariants({ variant, size }), props.class)"
  >
    <slot />
  </Primitive>
</template>
