<template>
  <Head title="Students" />

  <AppLayout :breadcrumbs="breadcrumbs">

    <div class="flex h-full flex-1 flex-col p-6">
      <!-- Header Section -->
      <div class="flex items-center justify-between mb-6">
        <div>
          <h1 class="text-3xl font-bold text-foreground">Students</h1>
          <p class="text-muted-foreground mt-2">Manage students and their information</p>
        </div>
        <div>
          <Button @click="router.visit(route('students.create'))">
            <Plus class="h-4 w-4 mr-2" />
            Add Student
          </Button>
        </div>
      </div>

      <Card>
        <CardContent>
          <!-- Search and Filters -->
          <div class="mb-4 space-y-4">
            <div class="flex flex-col sm:flex-row gap-4">
              <div class="flex-1">
                <Input v-model="search" placeholder="Search by name, email, phone, admission number..." />
              </div>
              <Button 
                variant="outline" 
                @click="showFilters = !showFilters"
                class="sm:w-auto w-full"
              >
                <Filter class="h-4 w-4 mr-2" />
                Filters
                <ChevronDown class="h-4 w-4 ml-2" :class="{ 'rotate-180': showFilters }" />
              </Button>
            </div>
            
            <!-- Advanced Filters -->
            <div v-if="showFilters" class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-muted/50 rounded-lg">
              <div class="space-y-2">
                <Label for="branch-filter">Branch</Label>
                <SimpleCombobox
                  v-model="branchFilter"
                  :options="branchOptions"
                  placeholder="All Branches"
                  class="w-full"
                />
              </div>
              
              <div class="space-y-2">
                <Label for="class-filter">Class</Label>
                <SimpleCombobox
                  v-model="classFilter"
                  :options="classOptions"
                  placeholder="All Classes"
                  class="w-full"
                />
              </div>
              
              <div class="space-y-2">
                <Label for="gender-filter">Gender</Label>
                <SimpleCombobox
                  v-model="genderFilter"
                  :options="genderOptions"
                  placeholder="All Genders"
                  class="w-full"
                />
              </div>
              
              <div class="space-y-2">
                <Label for="status-filter">Status</Label>
                <SimpleCombobox
                  v-model="statusFilter"
                  :options="statusOptions"
                  placeholder="All Status"
                  class="w-full"
                />
              </div>
              
              <div class="md:col-span-4 flex gap-2">
                <Button variant="outline" @click="clearFilters" size="sm">
                  <X class="h-4 w-4 mr-2" />
                  Clear Filters
                </Button>
              </div>
            </div>
          </div>
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Student</TableHead>
                <TableHead>Branch</TableHead>
                <TableHead>Class</TableHead>
                <TableHead>Contact</TableHead>
                <TableHead>Status</TableHead>
                <TableHead class="text-center">Actions</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody v-if="students.data.length">
              <TableRow v-for="student in students.data" :key="student.id">
                <TableCell>
                  <div class="font-medium">{{ student.first_name }} {{ student.last_name }}</div>
                  <div class="text-sm text-muted-foreground">{{ student.admission_number }}</div>
                </TableCell>
                <TableCell>{{ student.branch.name }}</TableCell>
                <TableCell>
                  <div class="font-medium">{{ student.class }}</div>
                  <div class="text-sm text-muted-foreground">Section {{ student.section }}</div>
                </TableCell>
                <TableCell>
                  <div class="text-sm">{{ student.email }}</div>
                  <div class="text-sm text-muted-foreground" v-if="student.phone">{{ student.phone }}</div>
                </TableCell>
                <TableCell>
                  <Badge :variant="student.status ? 'default' : 'destructive'">
                    {{ student.status ? 'Active' : 'Inactive' }}
                  </Badge>
                </TableCell>
                <TableCell class="flex gap-1 justify-center">
                  <!-- View Student -->
                  <Tooltip>
                    <TooltipTrigger asChild>
                      <Button
                        variant="outline" 
                        size="sm" 
                        asChild
                      >
                        <Link :href="route('students.show', student.id)">
                          <Eye class="h-4 w-4" />
                        </Link>
                      </Button>
                    </TooltipTrigger>
                    <TooltipContent>
                      <p>View student details</p>
                    </TooltipContent>
                  </Tooltip>
                  
                  <!-- Edit Student -->
                  <Tooltip>
                    <TooltipTrigger asChild>
                      <Button
                        variant="outline" 
                        size="sm" 
                        asChild
                      >
                        <Link :href="route('students.edit', student.id)">
                          <Edit class="h-4 w-4" />
                        </Link>
                      </Button>
                    </TooltipTrigger>
                    <TooltipContent>
                      <p>Edit student information</p>
                    </TooltipContent>
                  </Tooltip>
                  
                  <!-- Delete Student -->
                  <ConfirmationModal
                    title="Are you sure?"
                    :description="getDeleteDescription(student)"
                    confirm-text="Delete"
                    tooltip="Delete student permanently"
                    @confirm="destroy(student.id)"
                  >
                    <template #trigger>
                      <Button 
                        variant="destructive" 
                        size="sm"
                      >
                        <Trash2 class="h-4 w-4" />
                      </Button>
                    </template>
                  </ConfirmationModal>
                </TableCell>
              </TableRow>
            </TableBody>
            <TableBody v-else>
              <TableRow>
                <TableCell colspan="6" class="text-center py-4">
                    No students found
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>

          <div v-if="totalPages >= 1" class="mt-6">
            <Pagination
              :total="totalPages * 10"
              :items-per-page="10"
              :sibling-count="1"
              show-edges
              :default-page="currentPage"
              @update:page="handlePageChange"
            >
              <PaginationContent>
                <PaginationFirst />

                <template v-for="(item, index) in totalPages" :key="index">
                  <PaginationItem
                    :value="item"
                    :is-active="currentPage === item"
                    @click="handlePageChange(item)"
                  >
                    {{ item }}
                  </PaginationItem>
                </template>

                <PaginationLast />
              </PaginationContent>
            </Pagination>
          </div>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import { ref, watch, computed } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import {
  Pagination,
  PaginationEllipsis,
  PaginationFirst,
  PaginationItem,
  PaginationLast,
  PaginationContent,
} from '@/components/ui/pagination'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Card, CardContent } from '@/components/ui/card'
import { Table, TableHeader, TableBody, TableHead, TableRow, TableCell } from '@/components/ui/table'
import { Badge } from '@/components/ui/badge/index'
import SimpleCombobox from '@/components/ui/SimpleCombobox.vue'
import { Plus, Edit, Trash2, Eye, Filter, ChevronDown, X } from 'lucide-vue-next'
import ConfirmationModal from '@/components/ConfirmationModal.vue'
import { Tooltip, TooltipContent, TooltipTrigger } from '@/components/ui/tooltip'
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
]

interface Branch {
    id: string
    name: string
}

interface Student {
    id: string
    first_name: string
    last_name: string
    email: string
    phone: string | null
    admission_number: string
    class: string
    section: string
    status: boolean
    branch: {
        name: string
    }
}

interface PaginatedData<T> {
  data: T[]
  links: {
    url: string | null
    label: string
    active: boolean
  }[]
  current_page: number
  total: number
  per_page: number
}

interface Props {
  students: PaginatedData<Student>
  branches: Branch[]
  classes: string[]
  genders: string[]
  filters?: {
    search?: string
    branch?: string
    class?: string
    gender?: string
    status?: boolean | null
  }
}

const props = defineProps<Props>()

const search = ref(props.filters?.search || '')
const branchFilter = ref(props.filters?.branch || '')
const classFilter = ref(props.filters?.class || '')
const genderFilter = ref(props.filters?.gender || '')
const statusFilter = ref(props.filters?.status !== null ? props.filters?.status?.toString() : '')
const showFilters = ref(false)

// Options for SimpleCombobox components
const branchOptions = computed(() => [
  { value: '', label: 'All Branches' },
  ...props.branches.map(branch => ({ value: branch.id, label: branch.name }))
])

const classOptions = computed(() => [
  { value: '', label: 'All Classes' },
  ...props.classes.map(cls => ({ value: cls, label: cls }))
])

const genderOptions = computed(() => [
  { value: '', label: 'All Genders' },
  ...props.genders.map(gender => ({ 
    value: gender, 
    label: gender.charAt(0).toUpperCase() + gender.slice(1) 
  }))
])

const statusOptions = computed(() => [
  { value: '', label: 'All Status' },
  { value: '1', label: 'Active' },
  { value: '0', label: 'Inactive' }
])

// Watch for search changes with debounce
watch(search, (value) => {
  const filters: any = {}
  
  if (value) filters.search = value
  if (branchFilter.value) filters.branch = branchFilter.value
  if (classFilter.value) filters.class = classFilter.value
  if (genderFilter.value) filters.gender = genderFilter.value
  if (statusFilter.value !== '') filters.status = statusFilter.value === '1'
  
  router.get(route('students.index'), filters, {
    preserveScroll: true,
    preserveState: true,
    replace: true,
  })
})

// Watch for filter changes (immediate)
watch([branchFilter, classFilter, genderFilter, statusFilter], () => {
  const filters: any = {}
  
  if (search.value) filters.search = search.value
  if (branchFilter.value) filters.branch = branchFilter.value
  if (classFilter.value) filters.class = classFilter.value
  if (genderFilter.value) filters.gender = genderFilter.value
  if (statusFilter.value !== '') filters.status = statusFilter.value === '1'
  
  router.get(route('students.index'), filters, {
    preserveScroll: true,
    preserveState: true,
    replace: true,
  })
})

const totalPages = computed(() => {
  if (!props.students?.links) return 0
  return props.students.links.filter(link => !isNaN(parseInt(link.label))).length
})

const currentPage = computed(() => {
  const activePage = props.students.links.find(link => link.active && !isNaN(parseInt(link.label)))
  return activePage ? parseInt(activePage.label) : 1
})

const handlePageChange = (page: number) => {
  const filters: any = { page }
  
  if (search.value) filters.search = search.value
  if (branchFilter.value) filters.branch = branchFilter.value
  if (classFilter.value) filters.class = classFilter.value
  if (genderFilter.value) filters.gender = genderFilter.value
  if (statusFilter.value !== '') filters.status = statusFilter.value === '1'
  
  router.get(route('students.index'), filters, {
    preserveScroll: true,
    preserveState: true,
  })
}

const clearFilters = () => {
  search.value = ''
  branchFilter.value = ''
  classFilter.value = ''
  genderFilter.value = ''
  statusFilter.value = ''
}

const destroy = (id: string) => {
  router.delete(route('students.destroy', id))
}

const getDeleteDescription = (student: Student) => {
  return `This action cannot be undone. This will permanently delete the student "${student.first_name} ${student.last_name}" and remove their data from our servers.`
}
</script> 