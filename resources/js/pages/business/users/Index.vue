<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { Combobox } from '@/components/ui/combobox';
import AppLayout from '@/layouts/AppLayout.vue';
import { Plus, Pencil, Trash2, UserCog } from 'lucide-vue-next';
import { ref } from 'vue';

interface Props {
    users: {
        data: Array<{
            id: number;
            name: string;
            email: string;
            roles: Array<{
                id: number;
                name: string;
            }>;
            is_active: boolean;
            organization?: {
                name: string;
            };
        }>;
        links: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
    };
    roles: Array<{
        id: number;
        name: string;
    }>;
}

const props = defineProps<Props>();

const handleDelete = (id: number) => {
    if (confirm('Are you sure you want to delete this user?')) {
        router.delete(route('users.destroy', id));
    }
};

const handleToggleStatus = (id: number) => {
    router.post(route('users.toggle-status', id));
};

const handleRoleChange = (userId: number, value: number | string): void => {
    router.post(route('users.assign-role', userId), {
        role: value,
    });
};
</script>

<template>
    <Head title="User Management" />

    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    User Management
                </h2>
                <Link :href="route('users.create')">
                    <Button>
                        <Plus class="mr-2 h-4 w-4" />
                        Add User
                    </Button>
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <div class="p-6">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Name</TableHead>
                                    <TableHead>Email</TableHead>
                                    <TableHead>Role</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead>Organization</TableHead>
                                    <TableHead class="text-right">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="user in users.data" :key="user.id">
                                    <TableCell>{{ user.name }}</TableCell>
                                    <TableCell>{{ user.email }}</TableCell>
                                    <TableCell>
                                        <Combobox
                                            :items="roles"
                                            :value="user.roles[0]?.id"
                                            @update:value="(value) => handleRoleChange(user.id, value)"
                                            class="w-[200px]"
                                        />
                                    </TableCell>
                                    <TableCell>
                                        <Badge
                                            :variant="user.is_active ? 'default' : 'destructive'"
                                            class="cursor-pointer"
                                            @click="handleToggleStatus(user.id)"
                                        >
                                            {{ user.is_active ? 'Active' : 'Inactive' }}
                                        </Badge>
                                    </TableCell>
                                    <TableCell>{{ user.organization?.name || '-' }}</TableCell>
                                    <TableCell class="text-right">
                                        <div class="flex justify-end gap-2">
                                            <Link :href="route('users.edit', user.id)">
                                                <Button variant="outline" size="icon">
                                                    <Pencil class="h-4 w-4" />
                                                </Button>
                                            </Link>
                                            <Button
                                                variant="destructive"
                                                size="icon"
                                                @click="handleDelete(user.id)"
                                            >
                                                <Trash2 class="h-4 w-4" />
                                            </Button>
                                        </div>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>

                        <!-- Pagination -->
                        <div class="mt-4 flex justify-center">
                            <div class="flex gap-2">
                                <Link
                                    v-for="link in users.links"
                                    :key="link.label"
                                    :href="link.url || '#'"
                                    :class="{
                                        'bg-primary text-white': link.active,
                                        'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600':
                                            !link.active,
                                    }"
                                    class="rounded px-3 py-1"
                                    v-html="link.label"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 