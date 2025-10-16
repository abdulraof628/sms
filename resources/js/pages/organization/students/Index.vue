<template>
  <Head title="Students" />

  <AppLayout title="Students">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Students
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <Card>
          <CardHeader>
            <div class="flex justify-between items-center">
              <CardTitle>Manage Students</CardTitle>
              <Button @click="router.visit(route('students.create'))">
                Add Student
              </Button>
            </div>
          </CardHeader>
          <CardContent>
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Name</TableHead>
                  <TableHead>School</TableHead>
                  <TableHead>Class</TableHead>
                  <TableHead>Admission Number</TableHead>
                  <TableHead class="text-center">Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow v-if="students.data.length === 0">
                  <TableCell colspan="5" class="text-center py-4">
                    No records
                  </TableCell>
                </TableRow>
                <TableRow v-for="student in students.data" :key="student.id">
                  <TableCell>
                    <div class="font-medium">{{ student.first_name }} {{ student.last_name }}</div>
                    <div class="text-sm text-gray-500">{{ student.email }}</div>
                  </TableCell>
                  <TableCell>{{ student.school.name }}</TableCell>
                  <TableCell>{{ student.class }} - {{ student.section }}</TableCell>
                  <TableCell>{{ student.admission_number }}</TableCell>
                  <TableCell class="flex gap-1 justify-center">
                    <Button 
                      variant="outline" 
                      size="sm"
                      @click="router.visit(route('students.show', student.id))"
                    >
                      View
                    </Button>
                    <Button 
                      variant="outline" 
                      size="sm"
                      @click="router.visit(route('students.edit', student.id))"
                    >
                      Edit
                    </Button>
                    <Button 
                      variant="destructive" 
                      size="sm" 
                      @click="openDeleteDialog(student.id)"
                    >
                      Delete
                    </Button>
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>

            <div class="mt-4">
              <Pagination :links="students.links" />
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
            Are you sure you want to delete this student? This action cannot be undone.
          </AlertDialogDescription>
        </AlertDialogHeader>
        <AlertDialogFooter>
          <AlertDialogCancel>Cancel</AlertDialogCancel>
          <AlertDialogAction @click="deleteStudent">Delete</AlertDialogAction>
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

interface Student {
    id: string;
    first_name: string;
    last_name: string;
    email: string;
    school: {
        name: string;
    };
    class: string;
    section: string;
    admission_number: string;
}

const props = defineProps<{
    students: {
        data: Student[];
        links: any[];
    };
}>();

const showDeleteDialog = ref(false);
const studentToDelete = ref<string | null>(null);

const openDeleteDialog = (id: string) => {
    studentToDelete.value = id;
    showDeleteDialog.value = true;
};

const deleteStudent = () => {
    if (studentToDelete.value) {
        router.delete(route('students.destroy', studentToDelete.value), {
            preserveState: false,
        });
    }
};
</script> 