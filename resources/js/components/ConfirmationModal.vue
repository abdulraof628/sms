<script setup lang="ts">
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
  AlertDialogTrigger,
} from '@/components/ui/alert-dialog'
import { Tooltip, TooltipContent, TooltipTrigger } from '@/components/ui/tooltip'

interface Props {
  title: string
  description: string
  confirmText?: string
  cancelText?: string
  tooltip?: string
  tooltipSide?: 'top' | 'right' | 'bottom' | 'left'
  tooltipAlign?: 'start' | 'center' | 'end'
}

withDefaults(defineProps<Props>(), {
  confirmText: 'Confirm',
  cancelText: 'Cancel',
  tooltipSide: 'top',
  tooltipAlign: 'center'
})

defineEmits<{
  confirm: []
}>()
</script>

<template>
  <AlertDialog>
    <!-- When tooltip provided, stack triggers: AlertDialogTrigger(asChild) > TooltipTrigger(asChild) > trigger slot -->
    <template v-if="tooltip">
      <Tooltip>
        <TooltipTrigger asChild>
          <AlertDialogTrigger asChild>
            <slot name="trigger" />
          </AlertDialogTrigger>
        </TooltipTrigger>
        <TooltipContent :side="tooltipSide" :align="tooltipAlign">
          <p>{{ tooltip }}</p>
        </TooltipContent>
      </Tooltip>
    </template>

    <template v-else>
      <AlertDialogTrigger asChild>
        <slot name="trigger" />
      </AlertDialogTrigger>
    </template>
    <AlertDialogContent>
      <AlertDialogHeader>
        <AlertDialogTitle>{{ title }}</AlertDialogTitle>
        <AlertDialogDescription>
          {{ description }}
        </AlertDialogDescription>
      </AlertDialogHeader>
      <AlertDialogFooter>
        <AlertDialogCancel>{{ cancelText }}</AlertDialogCancel>
        <AlertDialogAction @click="$emit('confirm')">
          {{ confirmText }}
        </AlertDialogAction>
      </AlertDialogFooter>
    </AlertDialogContent>
  </AlertDialog>
</template>
