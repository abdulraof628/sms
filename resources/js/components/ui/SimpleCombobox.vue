<script setup lang="ts">
import { ref, computed, watch, nextTick, onMounted } from 'vue'
import { CheckIcon, ChevronsUpDownIcon } from 'lucide-vue-next'
import { cn } from '@/lib/utils'
import { Button } from './button'
import { Popover, PopoverTrigger, PopoverContent } from './popover'
import {
  Command,
  CommandInput,
  CommandList,
  CommandItem,
  CommandItemIndicator,
  CommandEmpty,
  CommandGroup,
} from './command'

interface Option {
  value: string
  label: string
}

const props = defineProps<{
  modelValue: string | null
  options: Option[]
  placeholder?: string
  label?: string
  emptyMessage?: string
  disabled?: boolean
  class?: string
  asChild?: boolean
}>()

const emit = defineEmits(['update:modelValue'])

const open = ref(false)
const search = ref('')
const triggerRef = ref<HTMLElement | null>(null)

const displayValue = computed(() => {
  if (!props.modelValue) return props.placeholder || 'Select an option'
  return props.options.find(option => option.value === props.modelValue)?.label || ''
})

const filteredOptions = computed(() => {
  if (props.options.length === 0) return []
  if (!search.value) return props.options
  
  return props.options.filter(option => 
    option.label.toLowerCase().includes(search.value.toLowerCase())
  )
})

watch(() => props.modelValue, () => {
  search.value = ''
})

const handleSelect = (value: string) => {
  emit('update:modelValue', value)
  open.value = false
}

const handleInputChange = (event: Event) => {
  search.value = (event.target as HTMLInputElement).value
}

watch(() => open.value, async (newOpen) => {
  if (newOpen) await nextTick()
})

onMounted(async () => {
  await nextTick()
})

// Helper function to get a valid key for CommandItem
const getItemKey = (value: string, index: number) => {
  return value === '' ? `__empty-string-${index}` : value
}

// Helper function to get the actual value when selecting
const getActualValue = (key: string) => {
  return key.startsWith('__empty-string-') ? '' : key
}

// Check if an option is selected
const isSelected = (value: string) => {
  return props.modelValue === value
}
</script>

<template>
  <div :class="cn('relative w-full', props.class)">
    <Popover :open="open" @update:open="open = $event">
      <PopoverTrigger as-child>
        <Button ref="triggerRef" :disabled="props.disabled" variant="outline" class="w-full justify-between">
          {{ displayValue }}
          <ChevronsUpDownIcon class="ml-2 h-4 w-4 shrink-0 opacity-50" />
        </Button>
      </PopoverTrigger>
      <PopoverContent class="w-(--reka-popover-trigger-width) p-0">
        <Command>
          <CommandInput 
            :value="search"
            @input="handleInputChange"
            :placeholder="props.placeholder || 'Search...'"
          />
          <CommandList>
            <CommandEmpty>
              {{ props.emptyMessage || 'No results found.' }}
            </CommandEmpty>
            <CommandGroup>
              <CommandItem
                v-for="(option, index) in filteredOptions"
                :key="getItemKey(option.value, index)"
                :value="option.value === '' ? '__empty_value__' : option.value"
                :class="cn('w-full cursor-pointer', isSelected(option.value) && 'bg-accent text-accent-foreground')"
                @select="() => handleSelect(option.value)"
              >
                {{ option.label }}
                <CommandItemIndicator>
                  <CheckIcon class="ml-auto h-4 w-4" />
                </CommandItemIndicator>
              </CommandItem>
            </CommandGroup>
          </CommandList>
        </Command>
      </PopoverContent>
    </Popover>
  </div>
</template> 