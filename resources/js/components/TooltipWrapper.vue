<script setup lang="ts">
import {
  Tooltip,
  TooltipContent,
  TooltipProvider,
  TooltipTrigger,
} from '@/components/ui/tooltip'

interface Props {
  text: string
  side?: 'top' | 'right' | 'bottom' | 'left'
  align?: 'start' | 'center' | 'end'
}

withDefaults(defineProps<Props>(), {
  side: 'top',
  align: 'center'
})
</script>

<template>
  <TooltipProvider>
    <Tooltip>
      <TooltipTrigger asChild>
        <slot />
      </TooltipTrigger>
      <TooltipContent :side="side" :align="align">
        <p>{{ text }}</p>
      </TooltipContent>
    </Tooltip>
  </TooltipProvider>
</template>
