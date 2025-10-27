<template>
  <Head title="Create Student" />

  <AppLayout :breadcrumbs="breadcrumbs">

    <div class="mx-auto w-full p-6">
      <!-- Header Section -->
      <div class="flex items-center justify-between mb-6">
        <div>
          <h1 class="text-3xl font-bold text-foreground">Create New Student</h1>
          <p class="text-muted-foreground mt-2">Add a new student to the system</p>
        </div>
        <div>
          <Button variant="outline" @click="router.visit(route('students.index'))">
            <ArrowLeft class="h-4 w-4 mr-2" />
            Back to Students
          </Button>
        </div>
      </div>

      <form @submit.prevent="submit" class="space-y-6">
        <!-- Basic Information Card -->
        <Card>
          <CardHeader>
            <CardTitle>Basic Information</CardTitle>
            <CardDescription>Student's primary details and identification</CardDescription>
          </CardHeader>
          <CardContent>
            <div class="grid gap-6 md:grid-cols-2">
              <div class="space-y-2">
                <Label for="branch_id">Branch <span class="text-red-500">*</span></Label>
                <SimpleCombobox
                  v-model="form.branch_id"
                  :options="branchOptions"
                  placeholder="Select a branch"
                  class="w-full"
                />
                <InputError :message="form.errors.branch_id" />
              </div>

              <div class="space-y-2">
                <Label for="admission_number">Admission Number <span class="text-red-500">*</span></Label>
                <Input
                  id="admission_number"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.admission_number"
                />
                <InputError :message="form.errors.admission_number" />
              </div>

              <div class="space-y-2">
                <Label for="first_name">First Name <span class="text-red-500">*</span></Label>
                <Input
                  id="first_name"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.first_name"
                  autofocus
                />
                <InputError :message="form.errors.first_name" />
              </div>

              <div class="space-y-2">
                <Label for="last_name">Last Name <span class="text-red-500">*</span></Label>
                <Input
                  id="last_name"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.last_name"
                />
                <InputError :message="form.errors.last_name" />
              </div>

              <div class="space-y-2">
                <Label for="email">Email <span class="text-red-500">*</span></Label>
                <Input
                  id="email"
                  type="email"
                  class="mt-1 block w-full"
                  v-model="form.email"
                />
                <InputError :message="form.errors.email" />
              </div>

              <div class="space-y-2">
                <Label for="phone">Phone</Label>
                <Input
                  id="phone"
                  type="tel"
                  class="mt-1 block w-full"
                  v-model="form.phone"
                />
                <InputError :message="form.errors.phone" />
              </div>

              <div class="space-y-2">
                <Label for="date_of_birth">Date of Birth <span class="text-red-500">*</span></Label>
                <Input
                  id="date_of_birth"
                  type="date"
                  class="mt-1 block w-full"
                  v-model="form.date_of_birth"
                />
                <InputError :message="form.errors.date_of_birth" />
              </div>

              <div class="space-y-2">
                <Label for="gender">Gender <span class="text-red-500">*</span></Label>
                <SimpleCombobox
                  v-model="form.gender"
                  :options="genderOptions"
                  placeholder="Select gender"
                  class="w-full"
                />
                <InputError :message="form.errors.gender" />
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
                <Label for="class">Class <span class="text-red-500">*</span></Label>
                <Input
                  id="class"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.class"
                />
                <InputError :message="form.errors.class" />
              </div>

              <div class="space-y-2">
                <Label for="section">Section <span class="text-red-500">*</span></Label>
                <Input
                  id="section"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.section"
                />
                <InputError :message="form.errors.section" />
              </div>

              <div class="space-y-2">
                <Label for="roll_number">Roll Number</Label>
                <Input
                  id="roll_number"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.roll_number"
                />
                <InputError :message="form.errors.roll_number" />
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
                <Label for="parent_name">Parent Name <span class="text-red-500">*</span></Label>
                <Input
                  id="parent_name"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.parent_name"
                />
                <InputError :message="form.errors.parent_name" />
              </div>

              <div class="space-y-2">
                <Label for="parent_phone">Parent Phone <span class="text-red-500">*</span></Label>
                <Input
                  id="parent_phone"
                  type="tel"
                  class="mt-1 block w-full"
                  v-model="form.parent_phone"
                />
                <InputError :message="form.errors.parent_phone" />
              </div>

              <div class="space-y-2 md:col-span-2">
                <Label for="parent_email">Parent Email</Label>
                <Input
                  id="parent_email"
                  type="email"
                  class="mt-1 block w-full"
                  v-model="form.parent_email"
                />
                <InputError :message="form.errors.parent_email" />
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
                <Label for="address">Address</Label>
                <Textarea
                  id="address"
                  class="mt-1 block w-full"
                  v-model="form.address"
                />
                <InputError :message="form.errors.address" />
              </div>

              <div class="space-y-2">
                <Label for="medical_conditions">Medical Conditions</Label>
                <Textarea
                  id="medical_conditions"
                  class="mt-1 block w-full"
                  v-model="form.medical_conditions"
                />
                <InputError :message="form.errors.medical_conditions" />
              </div>

              <div class="space-y-2">
                <Label for="notes">Notes</Label>
                <Textarea
                  id="notes"
                  class="mt-1 block w-full"
                  v-model="form.notes"
                />
                <InputError :message="form.errors.notes" />
              </div>

              <div class="flex items-center space-x-2">
                <Switch id="status" v-model="form.status" />
                <Label for="status">Active</Label>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Actions -->
        <div class="flex justify-end gap-1">
          <Button type="button" variant="outline" @click="router.visit(route('students.index'))">
            Cancel
          </Button>
          <Button type="submit" :disabled="form.processing">
            <Plus class="h-4 w-4 mr-2" />
            Create Student
          </Button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Textarea } from '@/components/ui/textarea'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import InputError from '@/components/InputError.vue'
import { Button } from '@/components/ui/button'
import { Switch } from '@/components/ui/switch'
import SimpleCombobox from '@/components/ui/SimpleCombobox.vue'
import { ArrowLeft, Plus } from 'lucide-vue-next'
import { computed } from 'vue'
import { type BreadcrumbItem } from '@/types'

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
        title: 'Create Student',
        href: '/students/create',
    },
]

interface Branch {
    id: string
    name: string
}

interface BranchClass {
    id: string
    name: string
    code: string
}

interface Props {
    branches: Branch[]
    classes: BranchClass[]
}

const props = defineProps<Props>()

// Convert branches to the format expected by SimpleCombobox
const branchOptions = computed(() => {
    return props.branches.map(branch => ({
        value: branch.id,
        label: branch.name
    }))
})

const genderOptions = [
    { value: 'male', label: 'Male' },
    { value: 'female', label: 'Female' },
    { value: 'other', label: 'Other' }
]

const form = useForm({
    branch_id: '',
    admission_number: '',
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    date_of_birth: '',
    gender: '',
    class: '',
    section: '',
    roll_number: '',
    parent_name: '',
    parent_phone: '',
    parent_email: '',
    address: '',
    medical_conditions: '',
    notes: '',
    status: true,
})

const submit = () => {
    form.post(route('students.store'))
}
</script>