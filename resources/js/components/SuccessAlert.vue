<script setup lang="ts">
import { Alert, AlertDescription } from '@/components/ui/alert';
import { CheckCircle, X } from 'lucide-vue-next';
import { computed } from 'vue';

interface Props {
    message?: string;
    dismissible?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    dismissible: true,
});

const emit = defineEmits<{
    dismiss: [];
}>();

const shouldShow = computed(() => {
    return props.message && props.message.length > 0;
});

const dismiss = () => {
    emit('dismiss');
};
</script>

<template>
    <Alert v-if="shouldShow" variant="default" class="mb-6 border-green-200 bg-green-50 dark:border-green-800 dark:bg-green-950/20">
        <CheckCircle class="h-4 w-4 text-green-600 dark:text-green-400" />
        <AlertDescription class="flex items-center justify-between text-green-800 dark:text-green-200">
            <span>{{ message }}</span>
            <button v-if="dismissible" @click="dismiss" class="ml-2 hover:opacity-70">
                <X class="h-4 w-4" />
            </button>
        </AlertDescription>
    </Alert>
</template>
