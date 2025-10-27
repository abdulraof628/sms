<template>
  <Head title="Classes" />

  <AppLayout :breadcrumbs="breadcrumbs">

    <div class="flex h-full flex-1 flex-col p-6">
      <!-- Header Section -->
      <div class="flex items-center justify-between mb-6">
        <div>
          <h1 class="text-3xl font-bold text-foreground">Classes</h1>
          <p class="text-muted-foreground mt-2">Manage classes and their settings</p>
        </div>
        <div>
          <Button @click="router.visit(route('classes.create'))">
            <Plus class="h-4 w-4 mr-2" />
            Add Class
          </Button>
        </div>
      </div>

      <Card>
        <CardContent>
          <!-- Search and Filters -->
          <div class="mb-4 space-y-4">
            <div class="flex flex-col sm:flex-row gap-4">
              <div class="flex-1">
                <Input v-model="search" placeholder="Search by name, code, or session..." />
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
                <Label for="curriculum-filter">Curriculum Type</Label>
                <SimpleCombobox
                  v-model="curriculumFilter"
                  :options="curriculumOptions"
                  placeholder="All Curriculums"
                  class="w-full"
                />
              </div>
              
              <div class="space-y-2">
                <Label for="grade-filter">Grade Level</Label>
                <SimpleCombobox
                  v-model="gradeFilter"
                  :options="gradeOptions"
                  placeholder="All Grades"
                  class="w-full"
                />
              </div>
              
              <div class="space-y-2">
                <Label for="status-filter">Status</Label>
                <SimpleCombobox
                  v-model="statusFilter"
                  :options="props.statusOptions || []"
                  placeholder="All Status"
                  class="w-full"
                />
              </div>
              
              <div class="flex gap-2 items-end">
                <Button variant="outline" @click="clearFilters">
                  <X class="h-4 w-4 mr-2" />
                  Clear Filters
                </Button>
              </div>
            </div>
          </div>
          
          <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Name</TableHead>
                  <TableHead>Code</TableHead>
                  <TableHead>Grade Level</TableHead>
                  <TableHead>Curriculum</TableHead>
                  <TableHead>Stream</TableHead>
                  <TableHead>Branch</TableHead>
                  <TableHead>Session</TableHead>
                  <TableHead>Status</TableHead>
                  <TableHead class="text-center">Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody v-if="classes.data.length">
                <TableRow v-for="classItem in classes.data" :key="classItem.id">
                  <TableCell>
                    <div class="font-medium">{{ classItem.name }}</div>
                    <div class="text-sm text-gray-500">{{ classItem.description }}</div>
                  </TableCell>
                  <TableCell class="font-mono text-sm">{{ classItem.code }}</TableCell>
                  <TableCell>
                    <Badge variant="outline">{{ getGradeLevelLabel(classItem.grade_level) }}</Badge>
                  </TableCell>
                  <TableCell>
                    <Badge variant="secondary" class="text-xs">{{ getCurriculumLabel(classItem.curriculum_type) }}</Badge>
                  </TableCell>
                  <TableCell>
                    <Badge v-if="classItem.stream" variant="outline" class="text-xs">{{ getStreamLabel(classItem.stream) }}</Badge>
                    <span v-else class="text-gray-400 text-sm">-</span>
                  </TableCell>
                  <TableCell>{{ classItem.branch.name }}</TableCell>
                  <TableCell class="text-sm">{{ classItem.academic_session }}</TableCell>
                  <TableCell>
                    <Badge :variant="classItem.status ? 'default' : 'destructive'">
                      {{ classItem.status ? 'Active' : 'Inactive' }}
                    </Badge>
                  </TableCell>
                  <TableCell class="flex gap-1 justify-center">
                    <!-- View Class -->
                    <Tooltip>
                      <TooltipTrigger asChild>
                        <Button 
                          variant="outline" 
                          size="sm"
                          asChild
                        >
                          <Link :href="route('classes.show', classItem.id)">
                            <Eye class="h-4 w-4" />
                          </Link>
                        </Button>
                      </TooltipTrigger>
                      <TooltipContent>
                        <p>View class details</p>
                      </TooltipContent>
                    </Tooltip>

                    <!-- Edit Class -->
                    <Tooltip>
                      <TooltipTrigger asChild>
                        <Button
                          variant="outline" 
                          size="sm" 
                          asChild
                        >
                          <Link :href="route('classes.edit', classItem.id)">
                            <Edit class="h-4 w-4" />
                          </Link>
                        </Button>
                      </TooltipTrigger>
                      <TooltipContent>
                        <p>Edit class information</p>
                      </TooltipContent>
                    </Tooltip>
                    
                    <!-- Delete Class -->
                    <ConfirmationModal
                      title="Are you sure?"
                      :description="getDeleteDescription(classItem)"
                      confirm-text="Delete"
                      tooltip="Delete class permanently"
                      @confirm="destroy(classItem.id)"
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
                  <TableCell colspan="9" class="text-center py-4">
                      No records found
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
import { ref, watch, computed, onMounted } from 'vue'
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
import { Plus, Edit, Trash2, Filter, ChevronDown, X, Eye } from 'lucide-vue-next'
import { Tooltip, TooltipTrigger, TooltipContent } from '@/components/ui/tooltip'
import SimpleCombobox from '@/components/ui/SimpleCombobox.vue'
import ConfirmationModal from '@/components/ConfirmationModal.vue'
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
]

interface Branch {
    id: string
    name: string
}

interface BranchClass {
    id: string
    name: string
    code: string
    description: string | null
    status: boolean
    branch: Branch
    grade_level: string
    curriculum_type: string
    stream: string | null
    academic_session: string
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
  classes: PaginatedData<BranchClass>
  branches: Branch[]
  filters?: {
    search?: string
    branch_id?: string
    curriculum_type?: string
    grade_level?: string
    status?: string
  }
  statusOptions?: Array<{ value: string; label: string }>
}

const props = defineProps<Props>()

const search = ref(props.filters?.search || '')
const branchFilter = ref(props.filters?.branch_id || '')
const curriculumFilter = ref(props.filters?.curriculum_type || '')
const gradeFilter = ref(props.filters?.grade_level || '')
const statusFilter = ref(props.filters?.status || '')
const showFilters = ref(
  (props.filters?.search && props.filters.search !== '') ||
  (props.filters?.branch_id && props.filters.branch_id !== '') ||
  (props.filters?.curriculum_type && props.filters.curriculum_type !== '') ||
  (props.filters?.grade_level && props.filters.grade_level !== '') ||
  (props.filters?.status && props.filters.status !== '')
)

let isInitialized = false

const branchOptions = computed(() => [
  { value: '', label: 'All Branches' },
  ...props.branches.map(branch => ({
    value: branch.id,
    label: branch.name
  }))
])

const curriculumOptions = [
  { value: '', label: 'All Curriculums' },
  { value: 'kurikulum_merdeka', label: 'Kurikulum Merdeka' },
  { value: 'kss', label: 'KSS' },
  { value: 'kssm', label: 'KSSM (Secondary)' },
  { value: 'kbsr', label: 'KBSR (Primary)' },
  { value: 'kbsm', label: 'KBSM (Secondary)' },
  { value: 'spm', label: 'SPM (Exam)' },
  { value: 'stpm', label: 'STPM (Advanced)' },
  { value: 'modular', label: 'Modular System' },
]

const gradeOptions = [
  { value: '', label: 'All Grades' },
  { value: 'year_1', label: 'Year 1 (Tahun 1)' },
  { value: 'year_2', label: 'Year 2 (Tahun 2)' },
  { value: 'year_3', label: 'Year 3 (Tahun 3)' },
  { value: 'year_4', label: 'Year 4 (Tahun 4)' },
  { value: 'year_5', label: 'Year 5 (Tahun 5)' },
  { value: 'year_6', label: 'Year 6 (Tahun 6)' },
  { value: 'form_1', label: 'Form 1 (Tingkatan 1)' },
  { value: 'form_2', label: 'Form 2 (Tingkatan 2)' },
  { value: 'form_3', label: 'Form 3 (Tingkatan 3)' },
  { value: 'form_4', label: 'Form 4 (Tingkatan 4)' },
  { value: 'form_5', label: 'Form 5 (Tingkatan 5)' },
  { value: 'form_6', label: 'Form 6 (Tingkatan 6)' },
]

const applyFilters = () => {
  showFilters.value = true
  router.get(route('classes.index'), {
    search: search.value,
    branch_id: branchFilter.value,
    curriculum_type: curriculumFilter.value,
    grade_level: gradeFilter.value,
    status: statusFilter.value,
  }, {
    preserveScroll: true,
    preserveState: true,
    replace: true,
  })
}

watch([search, branchFilter, curriculumFilter, gradeFilter, statusFilter], () => {
  if (isInitialized) {
    applyFilters()
  }
})

onMounted(() => {
  isInitialized = true
})

const clearFilters = () => {
  search.value = ''
  branchFilter.value = ''
  curriculumFilter.value = ''
  gradeFilter.value = ''
  statusFilter.value = ''
}

const totalPages = computed(() => {
  if (!props.classes?.links) return 0
  return props.classes.links.filter(link => !isNaN(parseInt(link.label))).length
})

const currentPage = computed(() => {
  const activePage = props.classes.links.find(link => link.active && !isNaN(parseInt(link.label)))
  return activePage ? parseInt(activePage.label) : 1
})

const handlePageChange = (page: number) => {
  router.get(route('classes.index'), {
    page,
    search: search.value,
    branch_id: branchFilter.value === '' ? '' : branchFilter.value,
    curriculum_type: curriculumFilter.value === '' ? '' : curriculumFilter.value,
    grade_level: gradeFilter.value === '' ? '' : gradeFilter.value,
    status: statusFilter.value === '' ? '' : statusFilter.value,
  }, {
    preserveScroll: true,
    preserveState: true,
  })
}

const destroy = (id: string) => {
  router.delete(route('classes.destroy', id))
}

const getDeleteDescription = (classItem: BranchClass) => {
  return `This action cannot be undone. This will permanently delete the class "${classItem.name}" and remove its data from our servers.`
}

const getGradeLevelLabel = (level: string) => {
  const gradeLevelMap: Record<string, string> = {
    'year_1': 'Year 1 (Tahun 1)',
    'year_2': 'Year 2 (Tahun 2)',
    'year_3': 'Year 3 (Tahun 3)',
    'year_4': 'Year 4 (Tahun 4)',
    'year_5': 'Year 5 (Tahun 5)',
    'year_6': 'Year 6 (Tahun 6)',
    'form_1': 'Form 1 (Tingkatan 1)',
    'form_2': 'Form 2 (Tingkatan 2)',
    'form_3': 'Form 3 (Tingkatan 3)',
    'form_4': 'Form 4 (Tingkatan 4)',
    'form_5': 'Form 5 (Tingkatan 5)',
    'form_6': 'Form 6 (Tingkatan 6)',
  }
  return gradeLevelMap[level] || level
}

const getCurriculumLabel = (type: string) => {
  const curriculumMap: Record<string, string> = {
    'kurikulum_merdeka': 'Kurikulum Merdeka',
    'kss': 'KSS',
    'kssm': 'KSSM (Secondary)',
    'kbsr': 'KBSR (Primary)',
    'kbsm': 'KBSM (Secondary)',
    'spm': 'SPM (Exam)',
    'stpm': 'STPM (Advanced)',
    'modular': 'Modular System',
  }
  return curriculumMap[type] || type
}

const getStreamLabel = (stream: string | null) => {
  if (!stream) return '-'
  const streamMap: Record<string, string> = {
    'general': 'General (Umum)',
    'science': 'Science (Sains)',
    'arts': 'Arts (Sastera)',
    'commerce': 'Commerce (Perdagangan)',
    'technical': 'Technical (Teknik)',
    'vocational': 'Vocational (Vokasional)',
    'engineering': 'Engineering (Kejuruteraan)',
    'biology': 'Biology (Biologi)',
    'accounting_finance': 'Accounting & Finance',
    'humanities_social': 'Humanities & Social Studies',
  }
  return streamMap[stream] || stream
}
</script> 