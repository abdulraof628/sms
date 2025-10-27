<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import TooltipWrapper from '@/components/TooltipWrapper.vue'

interface Props {
  href: string
  tooltip?: string
  tooltipSide?: 'top' | 'right' | 'bottom' | 'left'
  tooltipAlign?: 'start' | 'center' | 'end'
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
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
    <Link :href="href" :class="class">
      <slot />
    </Link>
  </TooltipWrapper>
  
  <Link v-else :href="href" :class="class">
    <slot />
  </Link>
</template>
