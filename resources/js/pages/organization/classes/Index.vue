<template>
  <AppLayout title="Classes">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Classes
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <Card>
          <CardHeader>
            <div class="flex justify-between items-center">
              <CardTitle>Manage Classes</CardTitle>
              <Button @click="router.visit(route('classes.create'))">
                Add Class
              </Button>
            </div>
          </CardHeader>
          <CardContent>
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Name</TableHead>
                  <TableHead>Code</TableHead>
                  <TableHead>School</TableHead>
                  <TableHead>Status</TableHead>
                  <TableHead class="text-center">Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow v-if="classes.data.length === 0">
                  <TableCell colspan="5" class="text-center py-4">
                    No records
                  </TableCell>
                </TableRow>
                <TableRow v-for="classItem in classes.data" :key="classItem.id">
                  <TableCell>
                    <div class="font-medium">{{ classItem.name }}</div>
                    <div class="text-sm text-gray-500">{{ classItem.description }}</div>
                  </TableCell>
                  <TableCell>{{ classItem.code }}</TableCell>
                  <TableCell>{{ classItem.school.name }}</TableCell>
                  <TableCell>
                    <Badge :variant="classItem.status ? 'default' : 'destructive'">
                      {{ classItem.status ? 'Active' : 'Inactive' }}
                    </Badge>
                  </TableCell>
                  <TableCell class="flex gap-1 justify-center">
                    <Button 
                      variant="outline" 
                      size="sm"
                      @click="router.visit(route('classes.edit', classItem.id))"
                    >
                      Edit
                    </Button>
                    <Button 
                      variant="destructive" 
                      size="sm" 
                      @click="openDeleteDialog(classItem.id)"
                    >
                      Delete
                    </Button>
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>

            <div class="mt-4">
              <Pagination :links="classes.links" />
            </div>
          </CardContent>
        </Card>
      </div>
    </div>

    <AlertDialog v-model:open="showDeleteDialog">
      <AlertDialogContent>
        <AlertDialogHeader>
          <AlertDialogTitle>Confirm Deletion</AlertDialogTitle>
          <AlertDialogDescription>
            Are you sure you want to delete this class? This action cannot be undone.
          </AlertDialogDescription>
        </AlertDialogHeader>
        <AlertDialogFooter>
          <AlertDialogCancel>Cancel</AlertDialogCancel>
          <AlertDialogAction @click="deleteClass">Delete</AlertDialogAction>
        </AlertDialogFooter>
      </AlertDialogContent>
    </AlertDialog>
  </AppLayout>
</template>

<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { Badge } from '@/components/ui/badge'
import Pagination from '@/components/Pagination.vue'
import { ref } from 'vue'
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog'

interface School {
    id: string;
    name: string;
}

interface SchoolClass {
    id: string;
    name: string;
    code: string;
    description: string | null;
    status: boolean;
    school: School;
}

const props = defineProps<{
    classes: {
        data: SchoolClass[];
        links: any[];
    };
}>();

const showDeleteDialog = ref(false);
const classToDelete = ref<string | null>(null);

const openDeleteDialog = (id: string) => {
    classToDelete.value = id;
    showDeleteDialog.value = true;
};

const deleteClass = () => {
    if (classToDelete.value) {
        router.delete(route('classes.destroy', classToDelete.value), {
            preserveState: false,
        });
    }
};
</script> 