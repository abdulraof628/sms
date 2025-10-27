<template>
  <Head title="Edit Class" />

  <AppLayout :breadcrumbs="breadcrumbs">

    <div class="mx-auto w-full p-6">
      <!-- Header Section -->
      <div class="flex items-center justify-between mb-6">
        <div>
          <h1 class="text-3xl font-bold text-foreground">Edit Class</h1>
          <p class="text-muted-foreground mt-2">Update class information and Malaysian education settings</p>
        </div>
        <div>
          <Button variant="outline" @click="router.visit(route('classes.index'))">
            <ArrowLeft class="h-4 w-4 mr-2" />
            Back to Classes
          </Button>
        </div>
      </div>

      <form @submit.prevent="submit" class="space-y-6">
        <!-- Class Basic Information -->
        <Card>
          <CardHeader>
            <CardTitle>Class Information</CardTitle>
            <CardDescription>Basic class details and naming</CardDescription>
          </CardHeader>
          <CardContent>
            <div class="grid gap-6 md:grid-cols-2">
              <div class="space-y-2">
                <Label for="branch_id">Branch <span class="text-red-500">*</span></Label>
                <SimpleCombobox
                  v-model="form.branch_id"
                  :options="branchOptions"
                  placeholder="Select a branch"
                  emptyMessage="No branches found"
                  class="w-full"
                />
                <InputError :message="form.errors.branch_id" />
              </div>

              <div class="space-y-2">
                <Label for="name">Class Name <span class="text-red-500">*</span></Label>
                <Input
                  id="name"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.name"
                  placeholder="e.g., Form 4 Science A"
                  autofocus
                />
                <InputError :message="form.errors.name" />
              </div>

              <div class="space-y-2">
                <Label for="code">Class Code <span class="text-red-500">*</span></Label>
                <Input
                  id="code"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.code"
                  placeholder="e.g., 4SCI-001"
                />
                <InputError :message="form.errors.code" />
              </div>

              <div class="space-y-2 md:col-span-2">
                <Label for="description">Description</Label>
                <Textarea
                  id="description"
                  class="mt-1 block w-full"
                  v-model="form.description"
                  placeholder="Additional class information"
                />
                <InputError :message="form.errors.description" />
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Malaysian Education System -->
        <Card>
          <CardHeader>
            <CardTitle>Malaysian Education System</CardTitle>
            <CardDescription>Configure curriculum, grade level, and stream according to MOE standards</CardDescription>
          </CardHeader>
          <CardContent>
            <div class="grid gap-6 md:grid-cols-2">
              <div class="space-y-2">
                <Label for="curriculum_type">Curriculum Type <span class="text-red-500">*</span></Label>
                <SimpleCombobox
                  v-model="form.curriculum_type"
                  :options="curriculumOptions"
                  placeholder="Select curriculum"
                  emptyMessage="No curriculums found"
                  class="w-full"
                />
                <InputError :message="form.errors.curriculum_type" />
              </div>

              <div class="space-y-2">
                <Label for="grade_level">Grade Level / Tingkatan <span class="text-red-500">*</span></Label>
                <SimpleCombobox
                  v-model="form.grade_level"
                  :options="gradeLevelOptions"
                  placeholder="Select grade/form"
                  emptyMessage="No grades found"
                  class="w-full"
                />
                <InputError :message="form.errors.grade_level" />
              </div>

              <div class="space-y-2">
                <Label for="stream">Stream (Optional)</Label>
                <SimpleCombobox
                  v-model="form.stream"
                  :options="streamOptions"
                  placeholder="Select stream"
                  emptyMessage="No streams found"
                  :is-nullable="true"
                  class="w-full"
                />
                <InputError :message="form.errors.stream" />
              </div>

              <div class="space-y-2">
                <Label for="academic_session">Academic Session <span class="text-red-500">*</span></Label>
                <Input
                  id="academic_session"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.academic_session"
                  placeholder="e.g., 2025/2026"
                />
                <InputError :message="form.errors.academic_session" />
              </div>

              <div class="space-y-2">
                <Label for="language_medium">Language Medium <span class="text-red-500">*</span></Label>
                <SimpleCombobox
                  v-model="form.language_medium"
                  :options="languageMediumOptions"
                  placeholder="Select language medium"
                  emptyMessage="No languages found"
                  class="w-full"
                />
                <InputError :message="form.errors.language_medium" />
              </div>

              <div class="space-y-2">
                <Label for="capacity">Class Capacity <span class="text-red-500">*</span></Label>
                <Input
                  id="capacity"
                  type="number"
                  class="mt-1 block w-full"
                  v-model.number="form.capacity"
                  placeholder="e.g., 40"
                  min="1"
                  max="100"
                />
                <InputError :message="form.errors.capacity" />
              </div>

              <div class="space-y-2">
                <Label for="advisor_id">Class Advisor (Optional)</Label>
                <SimpleCombobox
                  v-model="form.advisor_id"
                  :options="staffOptions"
                  placeholder="Select staff member"
                  emptyMessage="No staff found"
                  :is-nullable="true"
                  class="w-full"
                />
                <InputError :message="form.errors.advisor_id" />
              </div>

              <div class="space-y-2 md:col-span-2">
                <Label for="notes">Notes</Label>
                <Textarea
                  id="notes"
                  class="mt-1 block w-full"
                  v-model="form.notes"
                  placeholder="Additional notes about the class"
                />
                <InputError :message="form.errors.notes" />
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Status -->
        <Card>
          <CardHeader>
            <CardTitle>Class Status</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="flex items-center space-x-2">
              <Switch id="status" v-model="form.status" />
              <Label for="status">Active Class</Label>
            </div>
          </CardContent>
        </Card>

        <!-- Actions -->
        <div class="flex justify-end gap-1">
          <Button type="button" variant="outline" @click="router.visit(route('classes.index'))">
            Cancel
          </Button>
          <Button type="submit" :disabled="form.processing">
            <Save class="h-4 w-4 mr-2" />
            Update Class
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
import { ArrowLeft, Save } from 'lucide-vue-next'
import { computed } from 'vue'
import { type BreadcrumbItem } from '@/types'

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Classes',
        href: '/classes',
    },
    {
        title: 'Edit Class',
        href: '/classes/edit',
    },
]

interface Branch {
    id: string
    name: string
}

interface Staff {
    id: string
    name: string
}

interface BranchClassData {
    id: string
    branch_id: string
    name: string
    code: string
    curriculum_type: string
    grade_level: string
    stream: string | null
    academic_session: string
    language_medium: string
    capacity: number
    advisor_id: string | null
    description: string
    notes: string
    status: boolean
}

interface DropdownOption {
    value: string
    label: string
}

interface Props {
    branches: Branch[]
    staff: Staff[]
    classData: BranchClassData
    curriculums: DropdownOption[]
    gradeLevels: DropdownOption[]
    streams: DropdownOption[]
    languageMediums: DropdownOption[]
}

const props = defineProps<Props>()

// Convert branches to the format expected by SimpleCombobox
const branchOptions = computed(() => {
    return props.branches.map(branch => ({
        value: branch.id,
        label: branch.name
    }))
})

// Convert staff to the format expected by SimpleCombobox
const staffOptions = computed(() => {
    return props.staff.map(s => ({
        value: s.id,
        label: s.name
    }))
})

const curriculumOptions = computed(() => props.curriculums)
const gradeLevelOptions = computed(() => props.gradeLevels)
const streamOptions = computed(() => props.streams)
const languageMediumOptions = computed(() => props.languageMediums)

const form = useForm({
    branch_id: props.classData.branch_id,
    name: props.classData.name,
    code: props.classData.code,
    curriculum_type: props.classData.curriculum_type || 'kss',
    grade_level: props.classData.grade_level || 'form_1',
    stream: props.classData.stream || '',
    academic_session: props.classData.academic_session,
    language_medium: props.classData.language_medium || 'malay',
    capacity: props.classData.capacity,
    advisor_id: props.classData.advisor_id || '',
    description: props.classData.description,
    notes: props.classData.notes,
    status: props.classData.status,
});

const submit = () => {
    // Ensure required fields have values before submitting
    if (!form.curriculum_type) form.curriculum_type = 'kss';
    if (!form.grade_level) form.grade_level = 'form_1';
    if (!form.language_medium) form.language_medium = 'malay';
    
    form.put(route('classes.update', props.classData.id));
};
</script> 