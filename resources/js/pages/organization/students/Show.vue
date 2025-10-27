<template>
  <Head :title="`${student.first_name} ${student.last_name}`" />

  <AppLayout :breadcrumbs="breadcrumbs">

    <div class="mx-auto w-full p-6">
      <!-- Header Section -->
      <div class="flex items-center justify-between mb-6">
        <div>
          <h1 class="text-3xl font-bold text-foreground">{{ student.first_name }} {{ student.last_name }}</h1>
          <p class="text-muted-foreground mt-2">Student details and information</p>
        </div>
        <div class="flex gap-2">
          <Button variant="outline" @click="router.visit(route('students.index'))">
            <ArrowLeft class="h-4 w-4 mr-2" />
            Back to Students
          </Button>
          <Button @click="router.visit(route('students.edit', student.id))">
            <Edit class="h-4 w-4 mr-2" />
            Edit Student
          </Button>
        </div>
      </div>

      <div class="grid gap-6">
        <!-- Basic Information Card -->
        <Card>
          <CardHeader>
            <CardTitle>Basic Information</CardTitle>
            <CardDescription>Student's primary details and identification</CardDescription>
          </CardHeader>
          <CardContent>
            <div class="grid gap-6 md:grid-cols-2">
              <div class="space-y-2">
                <Label class="text-sm font-medium text-muted-foreground">Full Name</Label>
                <p class="text-base">{{ student.first_name }} {{ student.last_name }}</p>
              </div>

              <div class="space-y-2">
                <Label class="text-sm font-medium text-muted-foreground">Admission Number</Label>
                <p class="text-base">{{ student.admission_number }}</p>
              </div>

              <div class="space-y-2">
                <Label class="text-sm font-medium text-muted-foreground">Email</Label>
                <p class="text-base">{{ student.email }}</p>
              </div>

              <div class="space-y-2">
                <Label class="text-sm font-medium text-muted-foreground">Phone</Label>
                <p class="text-base">{{ student.phone || 'Not provided' }}</p>
              </div>

              <div class="space-y-2">
                <Label class="text-sm font-medium text-muted-foreground">Date of Birth</Label>
                <p class="text-base">{{ formatDate(student.date_of_birth) }}</p>
              </div>

              <div class="space-y-2">
                <Label class="text-sm font-medium text-muted-foreground">Gender</Label>
                <p class="text-base capitalize">{{ student.gender }}</p>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Academic Information Card -->
        <Card>
          <CardHeader>
            <CardTitle>Academic Information</CardTitle>
            <CardDescription>Class details and academic records</CardDescription>
          </CardHeader>
          <CardContent>
            <div class="grid gap-6 md:grid-cols-2">
              <div class="space-y-2">
                <Label class="text-sm font-medium text-muted-foreground">Branch</Label>
                <p class="text-base">{{ student.branch.name }}</p>
              </div>

              <div class="space-y-2">
                <Label class="text-sm font-medium text-muted-foreground">Class</Label>
                <p class="text-base">{{ student.class }}</p>
              </div>

              <div class="space-y-2">
                <Label class="text-sm font-medium text-muted-foreground">Section</Label>
                <p class="text-base">{{ student.section }}</p>
              </div>

              <div class="space-y-2">
                <Label class="text-sm font-medium text-muted-foreground">Roll Number</Label>
                <p class="text-base">{{ student.roll_number || 'Not assigned' }}</p>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Parent Information Card -->
        <Card>
          <CardHeader>
            <CardTitle>Parent Information</CardTitle>
            <CardDescription>Parent or guardian contact details</CardDescription>
          </CardHeader>
          <CardContent>
            <div class="grid gap-6 md:grid-cols-2">
              <div class="space-y-2">
                <Label class="text-sm font-medium text-muted-foreground">Parent Name</Label>
                <p class="text-base">{{ student.parent_name }}</p>
              </div>

              <div class="space-y-2">
                <Label class="text-sm font-medium text-muted-foreground">Parent Phone</Label>
                <p class="text-base">{{ student.parent_phone }}</p>
              </div>

              <div class="space-y-2 md:col-span-2">
                <Label class="text-sm font-medium text-muted-foreground">Parent Email</Label>
                <p class="text-base">{{ student.parent_email || 'Not provided' }}</p>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Additional Information Card -->
        <Card>
          <CardHeader>
            <CardTitle>Additional Information</CardTitle>
            <CardDescription>Address and other details</CardDescription>
          </CardHeader>
          <CardContent>
            <div class="grid gap-6">
              <div class="space-y-2">
                <Label class="text-sm font-medium text-muted-foreground">Address</Label>
                <p class="text-base">{{ student.address || 'Not provided' }}</p>
              </div>

              <div class="space-y-2">
                <Label class="text-sm font-medium text-muted-foreground">Medical Conditions</Label>
                <p class="text-base">{{ student.medical_conditions || 'None reported' }}</p>
              </div>

              <div class="space-y-2">
                <Label class="text-sm font-medium text-muted-foreground">Notes</Label>
                <p class="text-base">{{ student.notes || 'No additional notes' }}</p>
              </div>

              <div class="space-y-2">
                <Label class="text-sm font-medium text-muted-foreground">Status</Label>
                <Badge :variant="student.status ? 'default' : 'destructive'">
                  {{ student.status ? 'Active' : 'Inactive' }}
                </Badge>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Label } from '@/components/ui/label'
import { Badge } from '@/components/ui/badge'
import { ArrowLeft, Edit } from 'lucide-vue-next'
import { type BreadcrumbItem } from '@/types'

interface Student {
    id: string
    first_name: string
    last_name: string
    email: string
    phone: string | null
    date_of_birth: string
    gender: string
    branch: {
        name: string
    }
    class: string
    section: string
    admission_number: string
    roll_number: string
    parent_name: string
    parent_phone: string
    parent_email: string | null
    address: string | null
    medical_conditions: string | null
    notes: string | null
    status: boolean
}

const props = defineProps<{
    student: Student
}>()

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Students',
        href: '/students',
    },
    {
        title: `${props.student.first_name} ${props.student.last_name}`,
        href: `/students/${props.student.id}`,
    },
]

const formatDate = (dateString: string) => {
    try {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        })
    } catch {
        return dateString
    }
}
</script>