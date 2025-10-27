<script setup lang="ts">
import { computed } from 'vue'
import { CheckboxIndicator, CheckboxRoot } from 'reka-ui'
import { Check } from 'lucide-vue-next'
import { cn } from '@/lib/utils'

interface Props {
  id?: string
  modelValue?: boolean | 'indeterminate'
  checked?: boolean | 'indeterminate'
  disabled?: boolean
  required?: boolean
  name?: string
  value?: string
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  modelValue: false,
})

const emits = defineEmits<{
  'update:modelValue': [value: boolean | 'indeterminate']
}>()

// Handle both v-model and checked prop for backward compatibility
const isChecked = computed(() => {
  if (props.modelValue !== undefined) return props.modelValue
  if (props.checked !== undefined) return props.checked
  return false
})

const handleUpdateModelValue = (value: boolean | 'indeterminate') => {
  emits('update:modelValue', value)
}
</script>

<template>
  <CheckboxRoot
    :id="id"
    :model-value="isChecked"
    :disabled="disabled"
    :required="required"
    :name="name"
    :value="value"
    :class="cn(
      'peer h-4 w-4 shrink-0 rounded-sm border border-primary shadow transition-all duration-200 ease-in-out focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 data-[state=checked]:bg-primary data-[state=checked]:text-primary-foreground',
      props.class
    )"
    @update:model-value="handleUpdateModelValue"
  >
    <CheckboxIndicator class="flex items-center justify-center text-current">
      <Check class="h-4 w-4" />
    </CheckboxIndicator>
  </CheckboxRoot>
</template>