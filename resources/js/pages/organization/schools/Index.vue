<template>
  <Head title="School" />

  <AppLayout :breadcrumbs="breadcrumbs">

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <Card>
          <CardHeader>
            <div class="flex justify-between items-center">
              <CardTitle>Manage Schools</CardTitle>
              <Button asChild>
                <Link :href="route('schools.create')">
                  Create Schools
                </Link>
              </Button>
            </div>
          </CardHeader>
          <CardContent>
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Name</TableHead>
                  <TableHead>Principal</TableHead>
                  <TableHead>Email</TableHead>
                  <TableHead>Phone</TableHead>
                  <TableHead>Status</TableHead>
                  <TableHead class="text-center">Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody v-if="schools.data.length">
                <TableRow v-for="school in schools.data" :key="school.id">
                  <TableCell>{{ school.name }}</TableCell>
                  <TableCell>{{ school.principal_name }}</TableCell>
                  <TableCell>{{ school.email }}</TableCell>
                  <TableCell>{{ school.phone }}</TableCell>
                  <TableCell>
                    <Badge :variant="school.status ? 'default' : 'destructive'">
                      {{ school.status ? 'Active' : 'Inactive' }}
                    </Badge>
                  </TableCell>
                  <TableCell class="flex gap-1 justify-center">
                    <Button
                      variant="outline" 
                      size="sm" 
                      asChild
                    >
                      <Link :href="route('schools.edit', school.id)">
                        Edit
                      </Link>
                    </Button>
                    <AlertDialog>
                      <AlertDialogTrigger asChild>
                        <Button 
                          variant="destructive" 
                          size="sm"
                        >
                          Delete
                        </Button>
                      </AlertDialogTrigger>
                      <AlertDialogContent>
                        <AlertDialogHeader>
                          <AlertDialogTitle>Are you sure?</AlertDialogTitle>
                          <AlertDialogDescription>
                            This action cannot be undone. This will permanently delete the school and remove its data from our servers.
                          </AlertDialogDescription>
                        </AlertDialogHeader>
                        <AlertDialogFooter>
                          <AlertDialogCancel>Cancel</AlertDialogCancel>
                          <AlertDialogAction @click="destroy(school.id)">
                            Delete
                          </AlertDialogAction>
                        </AlertDialogFooter>
                      </AlertDialogContent>
                    </AlertDialog>
                  </TableCell>
                </TableRow>
              </TableBody>
              <TableBody v-else>
                <TableRow>
                  <TableCell colspan="6" class="text-center py-4">
                      No records found
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>

            <Pagination
              :page="schools.current_page"
              :total="schools.total"
              :items-per-page="schools.per_page"
              class="mt-6"
              @update:page="(page) => router.get(route('schools.index', { page }))"
            />
          </CardContent>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Pagination } from '@/components/ui/pagination'
import { Button } from '@/components/ui/button'
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card'
import { Table, TableHeader, TableBody, TableHead, TableRow, TableCell } from '@/components/ui/table'
import { Badge } from '@/components/ui/badge/index'
import { type BreadcrumbItem } from '@/types';
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
  AlertDialogTrigger,
} from '@/components/ui/alert-dialog'

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Schools',
        href: '/schools',
    },
];

interface School {
  id: string
  name: string
  principal_name: string
  email: string
  phone: string
  status: boolean
}

interface PaginatedData<T> {
  data: T[]
  links: any[]
  current_page: number
  total: number
  per_page: number
}

const props = defineProps<{
  schools: PaginatedData<School>
}>()

const destroy = (id: string) => {
  router.delete(route('schools.destroy', id))
}
</script> 