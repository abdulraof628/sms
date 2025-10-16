<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { CheckIcon, ChevronsUpDownIcon, SearchIcon } from 'lucide-vue-next'
import { cn } from '@/lib/utils'
import {
  Combobox,
  ComboboxAnchor,
  ComboboxInput,
  ComboboxTrigger,
  ComboboxList,
  ComboboxItem,
  ComboboxItemIndicator,
  ComboboxEmpty,
  ComboboxGroup,
  ComboboxViewport,
} from './combobox'
import { Button } from './button'

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
</script>

<template>
  <div :class="cn('relative w-full', props.class)">
    <Combobox :open="open" @update:open="open = $event">
      <ComboboxAnchor as-child>
        <ComboboxTrigger as-child>
          <Button variant="outline" class="w-full justify-between">
            {{ displayValue }}
            <ChevronsUpDownIcon class="ml-2 h-4 w-4 shrink-0 opacity-50" />
          </Button>
        </ComboboxTrigger>
      </ComboboxAnchor>
      <ComboboxList>
        <div class="relative w-full min-w-full items-center">
          <ComboboxInput 
            :value="search"
            @input="handleInputChange"
            class="mt-0.5 focus-visible:ring-0 border-0 border-b rounded-none h-10" 
            :placeholder="placeholder || 'Search...'" 
          />
          <span class="absolute start-0 inset-y-0 flex items-center justify-center px-3">
            <SearchIcon class="size-4 text-muted-foreground" />
          </span>
        </div>
        <ComboboxEmpty>
          {{ emptyMessage || 'No results found.' }}
        </ComboboxEmpty>
        <ComboboxViewport v-if="props.options.length > 0">
          <ComboboxGroup>
            <ComboboxItem
              v-for="option in filteredOptions"
              :key="option.value"
              :value="option.value"
              class="w-full"
              @select="handleSelect(option.value)"
            >
              {{ option.label }}
              <ComboboxItemIndicator>
                <CheckIcon class="ml-auto h-4 w-4" />
              </ComboboxItemIndicator>
            </ComboboxItem>
          </ComboboxGroup>
        </ComboboxViewport>
        <div v-else class="p-2 text-sm text-muted-foreground text-center">
          No item to be select
        </div>
      </ComboboxList>
    </Combobox>
  </div>
</template> 