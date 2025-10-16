<script setup lang="ts">
import { Alert, AlertDescription } from '@/components/ui/alert';
import { AlertCircle, X } from 'lucide-vue-next';
import { computed } from 'vue';

interface Props {
    errors?: Record<string, string[]>;
    message?: string;
    dismissible?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    dismissible: true,
});

const emit = defineEmits<{
    dismiss: [];
}>();

const hasErrors = computed(() => {
    return props.errors && Object.keys(props.errors).length > 0;
});

const hasMessage = computed(() => {
    return props.message && props.message.length > 0;
});

const shouldShow = computed(() => {
    return hasErrors.value || hasMessage.value;
});

const dismiss = () => {
    emit('dismiss');
};
</script>

<template>
    <div v-if="shouldShow" class="mb-6">
        <!-- Single Error Message -->
        <Alert v-if="hasMessage && !hasErrors" variant="destructive">
            <AlertCircle class="h-4 w-4" />
            <AlertDescription class="flex items-center justify-between">
                <span>{{ message }}</span>
                <button v-if="dismissible" @click="dismiss" class="ml-2 hover:opacity-70">
                    <X class="h-4 w-4" />
                </button>
            </AlertDescription>
        </Alert>

        <!-- Multiple Validation Errors -->
        <div v-else-if="hasErrors" class="space-y-3">
            <Alert variant="destructive">
                <AlertCircle class="h-4 w-4" />
                <AlertDescription class="flex items-center justify-between">
                    <span>Please correct the following errors:</span>
                    <button v-if="dismissible" @click="dismiss" class="ml-2 hover:opacity-70">
                        <X class="h-4 w-4" />
                    </button>
                </AlertDescription>
            </Alert>
            
            <div class="bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <ul class="space-y-2">
                    <li v-for="(fieldErrors, field) in errors" :key="field" class="text-sm">
                        <div class="font-medium text-red-800 dark:text-red-200 capitalize">
                            {{ field.replace(/_/g, ' ') }}:
                        </div>
                        <ul class="ml-4 space-y-1">
                            <li v-for="error in fieldErrors" :key="error" class="text-red-700 dark:text-red-300">
                                • {{ error }}
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
