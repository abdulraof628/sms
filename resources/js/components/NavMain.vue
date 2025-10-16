<script setup>
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const props = defineProps({
    items: {
        type: Array,
        required: true
    },
    title: {
        type: String,
        required: true
    }
});

const hasVisibleItems = computed(() => {
    return props.items.some(item => 
        item.permission && page.props.permissions.includes(item.permission)
    );
});
</script>

<template>
    <SidebarGroup v-if="hasVisibleItems" class="px-2 py-0">
        <SidebarGroupLabel>{{ title }}</SidebarGroupLabel>
        <SidebarMenu>
            <template v-for="item in items" :key="item.title">
                <SidebarMenuItem v-if="item.permission && page.props.permissions.includes(item.permission)">
                    <SidebarMenuButton 
                        as-child :is-active="page.url === item.href"
                        :tooltip="item.title"
                    >
                        <Link :href="item.href">
                            <component :is="item.icon" />
                            <span>{{ item.title }}</span>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </template>
        </SidebarMenu>
    </SidebarGroup>
</template>
