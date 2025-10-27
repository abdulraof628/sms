<script setup lang="ts">
import { computed } from 'vue'
import { CalendarDate, getLocalTimeZone, parseDate, today } from '@internationalized/date'
import { Calendar as CalendarIcon } from 'lucide-vue-next'
import { cn } from '@/lib/utils'
import { Button } from '@/components/ui/button'
import { Calendar } from '@/components/ui/calendar'
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/components/ui/popover'

interface Props {
  modelValue?: string | null
  placeholder?: string
  disabled?: boolean
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  placeholder: 'Pick a date',
})

const emit = defineEmits<{
  'update:modelValue': [value: string | null]
}>()

// Convert string date to CalendarDate for the calendar component
const calendarValue = computed(() => {
  if (!props.modelValue) return undefined
  try {
    return parseDate(props.modelValue)
  } catch {
    return undefined
  }
})

// Format date for display
const formatDate = (date: string | null | undefined) => {
  if (!date) return props.placeholder
  try {
    const parsedDate = parseDate(date)
    return parsedDate.toDate(getLocalTimeZone()).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
    })
  } catch {
    return props.placeholder
  }
}

const handleDateSelect = (date: CalendarDate | undefined) => {
  if (!date) {
    emit('update:modelValue', null)
    return
  }
  
  // Convert CalendarDate to ISO string format (YYYY-MM-DD)
  const isoString = `${date.year}-${String(date.month).padStart(2, '0')}-${String(date.day).padStart(2, '0')}`
  emit('update:modelValue', isoString)
}
</script>

<template>
  <Popover>
    <PopoverTrigger as-child>
      <Button
        variant="outline"
        :class="cn(
          'w-full justify-start text-left font-normal',
          !modelValue && 'text-muted-foreground',
          props.class
        )"
        :disabled="disabled"
      >
        <CalendarIcon class="mr-2 h-4 w-4" />
        {{ formatDate(modelValue) }}
      </Button>
    </PopoverTrigger>
    <PopoverContent class="w-auto p-0" align="start">
      <Calendar
        :model-value="calendarValue"
        @update:model-value="handleDateSelect"
        :default-value="today(getLocalTimeZone())"
        initial-focus
      />
    </PopoverContent>
  </Popover>
</template>
