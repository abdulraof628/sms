<script setup lang="ts">
import { computed } from 'vue'
import { Clock } from 'lucide-vue-next'
import { cn } from '@/lib/utils'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
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
  placeholder: 'Select time',
})

const emit = defineEmits<{
  'update:modelValue': [value: string | null]
}>()

// Generate time options (every 15 minutes)
const timeOptions = computed(() => {
  const options = []
  for (let hour = 0; hour < 24; hour++) {
    for (let minute = 0; minute < 60; minute += 15) {
      const timeString = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`
      const displayTime = new Date(`2000-01-01T${timeString}`).toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true,
      })
      options.push({ value: timeString, label: displayTime })
    }
  }
  return options
})

// Format time for display
const formatTime = (time: string | null | undefined) => {
  if (!time) return props.placeholder
  try {
    const displayTime = new Date(`2000-01-01T${time}`).toLocaleTimeString('en-US', {
      hour: 'numeric',
      minute: '2-digit',
      hour12: true,
    })
    return displayTime
  } catch {
    return time
  }
}

const handleTimeSelect = (timeValue: string) => {
  emit('update:modelValue', timeValue)
}

const handleInputChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  emit('update:modelValue', target.value || null)
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
        <Clock class="mr-2 h-4 w-4" />
        {{ formatTime(modelValue) }}
      </Button>
    </PopoverTrigger>
    <PopoverContent class="w-auto p-0" align="start">
      <div class="p-3 space-y-3">
        <!-- Direct time input -->
        <div class="space-y-2">
          <label class="text-sm font-medium">Enter time</label>
          <Input
            type="time"
            :value="modelValue || ''"
            @input="handleInputChange"
            class="w-full"
          />
        </div>
        
        <!-- Quick time selection -->
        <div class="space-y-2">
          <label class="text-sm font-medium">Quick select</label>
          <div class="grid grid-cols-3 gap-2 max-h-48 overflow-y-auto">
            <Button
              v-for="option in timeOptions"
              :key="option.value"
              variant="ghost"
              size="sm"
              class="h-8 text-xs justify-start"
              :class="{ 'bg-accent': modelValue === option.value }"
              @click="handleTimeSelect(option.value)"
            >
              {{ option.label }}
            </Button>
          </div>
        </div>
      </div>
    </PopoverContent>
  </Popover>
</template>
