<script setup lang="ts">
import {
  DateFormatter,
  type DateValue,
  getLocalTimeZone,
  parseDate,
  today,
} from '@internationalized/date'
import { CalendarIcon } from 'lucide-vue-next'
import { ref, watch } from 'vue'
import { cn } from '@/lib/utils'
import { Button } from '@/components/ui/button'
import { Calendar } from '@/components/ui/calendar'
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover'

const props = defineProps<{
  modelValue?: any
  class?: string
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: any): void
}>()

const df = new DateFormatter('en-US', {
  dateStyle: 'long',
})

const value = ref<any>(props.modelValue)

watch(() => props.modelValue, (newValue) => {
  if (newValue) {
    value.value = parseDate(newValue.toString())
  } else {
    value.value = undefined
  }
})

const updateValue = (newValue: any) => {
  if (newValue) {
    value.value = parseDate(newValue.toString())
  } else {
    value.value = undefined
  }
  emit('update:modelValue', newValue)
}
</script>

<template>
  <Popover>
    <PopoverTrigger as-child>
      <Button
        variant="outline"
        :class="cn(
          'w-[280px] justify-start text-left font-normal',
          !value && 'text-muted-foreground',
          props.class
        )"
      >
        <CalendarIcon class="mr-2 h-4 w-4" />
        {{ value ? df.format(value.toDate(getLocalTimeZone())) : "Pick a date" }}
      </Button>
    </PopoverTrigger>
    <PopoverContent class="w-auto p-0">
      <Calendar v-model="value" @update:modelValue="updateValue" initial-focus />
    </PopoverContent>
  </Popover>
</template>

<style scoped>
/* Remove hover background for calendar cells */
:deep([data-slot="calendar-cell-trigger"][data-highlighted]) {
  background-color: transparent !important;
}
:deep([data-slot="calendar-cell-trigger"]:hover) {
  background-color: transparent !important;
}
</style> 