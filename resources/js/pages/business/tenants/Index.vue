<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table/index';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';

interface Tenant {
    id: string;
    name: string | null;
    domain: string | null;
	email: string | null;
    created_at: string;
}

const props = defineProps<{
    tenants: Tenant[];
}>();

const tenants = ref<Tenant[]>(props.tenants);
const showDeleteDialog = ref(false);
const tenantToDelete = ref<string | null>(null);

const openDeleteDialog = (id: string) => {
    tenantToDelete.value = id;
    showDeleteDialog.value = true;
};

const deleteTenant = () => {
    if (tenantToDelete.value) {
        router.delete(route('tenants.destroy', tenantToDelete.value), {
            preserveState: false,
        });
    }
};
</script>

<template>
    <AppLayout title="Tenants">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tenants
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card>
                    <CardHeader>
                        <div class="flex justify-between items-center">
                            <CardTitle>Manage Tenants</CardTitle>
                            <Button @click="router.visit(route('tenants.create'))">
                                Create New Tenant
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <Table>
                            <TableHeader>
								<TableHead>ID</TableHead>
								<TableHead>Name</TableHead>
								<TableHead>Domain</TableHead>
								<TableHead>Email</TableHead>
								<TableHead>Created At</TableHead>
								<TableHead class="text-center">Actions</TableHead>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-if="tenants.length === 0">
                                    <TableCell colspan="6" class="text-center py-4">
                                        No records
                                    </TableCell>
                                </TableRow>
                                <TableRow v-for="tenant in tenants" :key="tenant.id">
                                    <TableCell>{{ tenant.id }}</TableCell>
                                    <TableCell>{{ tenant.name }}</TableCell>
                                    <TableCell>{{ tenant.domain }}</TableCell>
									<TableCell>{{ tenant.email }}</TableCell>
                                    <TableCell>{{ new Date(tenant.created_at).toLocaleDateString() }}</TableCell>
                                    <TableCell class="flex gap-1 justify-center">
                                        <Button 
                                            variant="outline" 
                                            size="sm"
                                            @click="router.visit(route('tenants.edit', tenant.id))"
                                        >
                                            Edit
                                        </Button>
                                        <Button 
                                            variant="destructive" 
                                            size="sm" 
                                            @click="openDeleteDialog(tenant.id)"
                                        >
                                            Delete
                                        </Button>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </CardContent>
                </Card>
            </div>
        </div>

        <AlertDialog v-model:open="showDeleteDialog">
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>Confirm Deletion</AlertDialogTitle>
                    <AlertDialogDescription>
                        Are you sure you want to delete this tenant? This action cannot be undone.
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel>Cancel</AlertDialogCancel>
                    <AlertDialogAction @click="deleteTenant">Delete</AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </AppLayout>
</template> 