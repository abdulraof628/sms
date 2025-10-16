<script setup lang="ts">
import { Loader2 } from 'lucide-vue-next';

interface Props {
    size?: 'sm' | 'md' | 'lg';
    text?: string;
    fullScreen?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    size: 'md',
    fullScreen: false,
});

const sizeClasses = {
    sm: 'h-4 w-4',
    md: 'h-6 w-6',
    lg: 'h-8 w-8',
};

const textSizeClasses = {
    sm: 'text-sm',
    md: 'text-base',
    lg: 'text-lg',
};
</script>

<template>
    <div :class="[
        'flex items-center justify-center',
        fullScreen ? 'fixed inset-0 bg-background/80 backdrop-blur-sm z-50' : '',
        props.text ? 'space-x-2' : ''
    ]">
        <div :class="[
            'flex items-center justify-center',
            fullScreen ? 'flex-col space-y-4' : 'space-x-2'
        ]">
            <Loader2 :class="[
                'animate-spin text-primary',
                sizeClasses[size]
            ]" />
            <span v-if="text" :class="[
                'text-muted-foreground',
                textSizeClasses[size]
            ]">
                {{ text }}
            </span>
        </div>
    </div>
</template>
